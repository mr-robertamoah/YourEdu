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
        <div class="messages-section" v-if="showMessages">
            <div class="message">
                <div class="loading" v-if="messageLoading">
                    <pulse-loader :laoding="messageLoading" size="10px"></pulse-loader>
                </div>
                <discussion-textarea
                    :request="true"
                    @input="inputMessage"
                    @sendMessage="sendMessage"
                ></discussion-textarea>
            </div>
            <div class="messages" v-if="messages.length">
                <discussion-badge
                    v-for="message in messages"
                    :key="message.id"
                    :message="message"
                    :simple="true"
                    @clickedOption="deleteMessage"
                ></discussion-badge>

                <div class="more-data"
                    @click="infiniteHandler"
                    v-if="messagesNextPage && messagesNextPage !== 1"
                >
                    <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                </div>
                
                <div class="no-data"
                    @click="infiniteHandler"
                    v-if="!messagesNextPage && messages.length && messagesNextPage !== 1"
                > no more requests</div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex';
import ActionButton from './ActionButton';
import DiscussionBadge from './DiscussionBadge';
import DiscussionTextarea from './DiscussionTextarea';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import ProfilePicture from './profile/ProfilePicture';
    export default {
        components: {
            ProfilePicture,
            PulseLoader,
            DiscussionTextarea,
            DiscussionBadge,
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
                showMessages: false,
                messageText: '',
                messages: [],
                messagesNextPage: 1,
                messageLoading: false,
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
            async getMessages(){
                let response,
                    nextPage = this.messagesNextPage

                this.messageLoading = true
                response = await this['dashboard/getRequestMessages']({
                    requestId: this.request.id, nextPage
                })

                this.messageLoading = false
                if (response.status) {
                    this.messages.push(...response.messages)
                    if (response.next) {
                        this.messagesNextPage += 1
                    } else {
                        this.messagesNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async infiniteHandler(){
                if (this.messagesNextPage === 1 || this.messagesNextPage === null) {
                    return
                }

                await this.getMessages()
            },
            async sendMessage(){
                let response,
                    formData = new FormData
                
                this.messageLoading = true
                formData.append('message', this.messageText)
                formData.append('account', this.dashboard ? 'school' : this.request.myAccount)
                formData.append('accountId', this.dashboard ? this.request.schoolId : this.request.myAccountId)
                
                response = await this['dashboard/sendRequestMessage']({
                    formData,requestId: this.request.id
                })

                this.messageLoading = false
                if (response.status) {
                    this.messages.unshift(response.message)
                } else {
                    console.log('response :>> ', response);
                }
            },
            async deleteMessage(messageData){
                let response,
                    data = {
                    messageId: messageData.message.id,
                    requestId: this.request.id
                }
                this.messageLoading = true
                response = await this['dashboard/deleteRequestMessage'](data)
                this.messageLoading = false
                if (response.status) {
                    this.removeMessage(data.messageId)
                } else {
                    console.log('response :>> ', response);
                }
            },
            removeMessage(messageId){
                let index = this.messages.findIndex(message=>{
                    return message.id == messageId
                })
                if (index > -1) {
                    this.messages.splice(index,1)
                }
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
            clickedShowMessages(){
                this.showMessages = !this.showMessages
            },
            inputMessage(data){
                this.messageText = data
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
        }

        .message-section{
            padding: 10px;    
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
            font-size: 14px;
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 0 5px;

            .title{
                color: gray;
                min-width: fit-content;
                margin-right: 10px;
            }

            .amount{
                text-transform: lowercase;
            }
        }

        .messages-section{
            margin-bottom: 10px;
            padding: 10px;

            .message{
                margin-bottom: 10px;

                .loading{
                    width: 100%;
                    text-align: center;
                }
            }

            .messages{

                .more-data,
                .no-data{
                    width: 100%;
                    text-align: center;
                    cursor: pointer;
                    color: gray;
                    font-size: 12px;
                }

                .more-data{
                    font-size: 16px;
                }
            }
        }

        .state{
            font-size: 12px;
            color: gray;
            font-style: italic;
            width: fit-content;
            margin-left: auto;
            margin-right: 10px;
        }

        .files-section{    
            width: 100%;
            text-align: center;

            .note{
                font-size: 14px;
                color: gray;
            }

            .files{

                a{
                    font-size: 12px;
                }
            }
        }
    }
</style>