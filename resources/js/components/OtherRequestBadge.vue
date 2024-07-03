<template>
    <div class="other-request-wrapper">
        <div class="username" v-if="dashboard">
            {{`@${request.username}`}}
        </div>
        <div class="message-section">
            <profile-picture
                class="profile-picture"
                v-if="computedAccountUrl.length"
            >
                <template slot="image">
                    <img :src="computedAccountUrl">
                </template>
            </profile-picture>
            <div class="message">
                {{computedMessage}}
            </div>
        </div>
        <div class="salary"
            v-if="computedSalary.length"
        >
            <div class="title">Salary:</div>
            <div class="amount">{{computedSalary}}</div>
        </div>
        <div class="description" v-if="computedAdminDescription.length">
            {{computedAdminDescription}}
        </div>
        <div class="state" v-if="dashboard">
            {{request.state}}
        </div>
        <div class="files-section" v-if="computedFiles.length">
            <div class="note">these files where attached. click to download</div>
            <div class="files">
                
                <a 
                    v-for="(file,index) in computedFiles"
                    :key="index"
                    :href="file.url"
                >
                    {{file.name}}
                </a>
            </div>
        </div>
        <div class="buttons">
            <action-button
                @click="clickedAccept"
                :green="true"
                :title="`accept request to become an ${computedAdminTitle} of the school`"
                :loading="acceptLoading"
                :text="'accept'"
                v-if="!request.isFrom"
            ></action-button>
            <action-button
                @click="clickedReject"
                :red="true"
                :title="`decline request to become an ${computedAdminTitle} of the school`"
                :loading="rejectLoading"
                :text="'decline'"
                v-if="!request.isFrom"
            ></action-button>
            <action-button
                @click="clickedShowMessages"
                :green="true"
                :title="`send or view messages`"
                :text="'messages'"
            ></action-button>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex';
import ActionButton from './ActionButton.vue';
import ProfilePicture from './profile/ProfilePicture.vue';
    export default {
        components: {
            ProfilePicture,
            
            ActionButton,
        },
        props: {
            request: {
                type: Object,
                default(){
                    return {}
                }
            },
            dashboard: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                acceptLoading: false,
                rejectLoading: false,
            }
        },
        watch: {
            showMessages(newValue){
                if (newValue && !this.messages.length && 
                    this.messagesNextPage === 1) {
                    this.getMessages()
                }
            },
        },
        computed: {
            computedAccountUrl() {
                return this.dashboard ? '' : this.request.url
            },
            computedFiles() {
                return this.request.file ? this.request.file : []
            },
            computedAdminTitle() {
                return this.request.title && this.request.title.length ? this.request.title :
                    this.request.data && this.request.data.title && this.request.data.title.length ? this.request.data.title :
                    this.request.adminDetails && this.request.adminDetails.title && this.request.adminDetails.title.length ? 
                    this.request.adminDetails.title : 'administrating team member'
            },
            computedAdminDescription() {
                return this.request.adminDetails && this.request.adminRequest.description && this.request.adminRequest.description.length ? 
                    this.request.adminRequest.description : ''
            },
            computedSalary() {
                return this.request.salary ? 
                    `${this.request.salary.amount} per ${this.request.salary.period}` : 
                    ''
            },
            computedMessage(){
                return !this.dashboard ? 
                    `${this.computedName} requires you to be ${this.computedAdminTitle}.` :
                    `you required ${this.computedName} to be ${this.computedAdminTitle} of your school`
            },
            computedName(){
                return !this.dashboard ? this.request.name : this.request.data ?
                    this.request.data.name : this.request.account ? 
                    this.request.account.name : ''
            },
        },
        mounted () {
            this.listen();
        },
        methods: {
            ...mapActions(['dashboard/sendRequestMessage','dashboard/deleteRequestMessage',
                'dashboard/getRequestMessages']),
            listen(){
                Echo.private(`youredu.request.${this.request.id}`) 
                    .listen('.newRequestMessage',data=>{
                        if (this.messages.length) {                            
                            this.messages.unshift(data.message)
                        }
                    })
                    .listen('.deleteRequestMessage',data=>{
                        this.removeMessage(data.messageId)
                    })
                    .listen('.updateRequest',data=>{
                        console.log('data :>> ', data);
                        this.$emit('updateRequest',data)
                    })
            },
            clickedReject(){
                if (this.acceptLoading) return
                this.rejectLoading = true
                this.$emit('clickedAction', {
                    schoolRequest: this.request,
                    action: 'declined',
                })
            },
            clickedAccept(){
                if (this.rejectLoading) return
                this.acceptLoading = true
                this.$emit('clickedAction', {
                    schoolRequest: this.request,
                    action: 'accepted'
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

    .other-request-wrapper{
        margin: 10px auto;
        border-radius: 10px;
        box-shadow: 0 0 2px gray;
        width: 100%;
        background: rgb(240,248,255);
        position: relative;

        .username{
            font-size: 14px;
            color: gray;
            width: 100%;
            text-align: center;
        }

        .buttons{
            width: 100%;
            display: inline-flex;
            justify-content: space-around;
            align-items: center;
            position: absolute;
            top: 0;
        }

        .message-section{
            padding: 0 10px;    
            display: flex;

            .profile-picture{   
                width: 50px;
                height: 50px;
                min-width: 50px;
                margin-right: 10px;
            }

            .message{    
                font-size: 14px;
                color: gray;
            }
        }

        .description{
            width: 100%;
            text-align: justify;
            padding: 0 10px;
            font-size: 14px;
            color: gray;
        }

        .salary{
            width: 100%;
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 0 5px;

            .title{
                color: gray;
                min-width: fit-content;
                margin-right: 5px;
                font-size: 10px;
            }

            .amount{
                text-transform: lowercase;
                font-size: 12px;
            }
        }

        .state{
            font-size: 10px;
            color: gray;
            font-style: italic;
            width: fit-content;
            margin-left: auto;
            margin-right: 10px;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .files-section{    
            width: 100%;
            text-align: center;

            .note{
                font-size: 10px;
                color: gray;
            }

            .files{
                font-size: 10px;
                padding-bottom: 10px;
            }
        }
    }
</style>