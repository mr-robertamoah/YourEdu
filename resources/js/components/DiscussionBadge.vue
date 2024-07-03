<template>
    <div class="discussion-badge-wrapper"
        :class="{discussionBadgeMine: computedOwner, request: request || alert}"
        @dblclick="clickedShowOptions"
    >
        <div class="deleted" v-if="computedDeletedForMe">
            <div class="icon">
                <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
            </div>
            <div class="text">message deleted</div>
        </div>
        <optional-actions
            :hasEdit="false"
            :hasDelete="computedOwner"
            :hasExtra="simple ? false : true"
            extraText="delete for me"
            :hasAttachment="false"
            :hasSave="false"
            :show="showOptionalActions"
            v-if="showOptionalActions && !request && !alert && !computedDeletedForMe"
            @clickedOption="clickedOption"
            class="optional-actions"
        >
            <template slot="extraicon">
                <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
            </template>
        </optional-actions>
        <div class="top" v-if="!computedDeletedForMe">
            <div class="name">
                {{message.fromable.name}}
            </div>
            <div class="type">
                {{computedAccountType}}
            </div>
        </div>
        <div class="message-section" v-if="message.message && 
            message.message.length && !computedDeletedForMe">
            <profile-picture
                class="profile-picture"
                v-if="message.fromable.url.length"
            >
                <template slot="image">
                    <img :src="message.fromable.url">
                </template>
            </profile-picture>
            <div class="message">
                {{message.message}}
            </div>
        </div>
        <div class="media-section" 
            @click="clickedShowMedia"
            v-if="computedResources.length && !computedDeletedForMe"
        >
            <div class="media-cover" v-if="computedResources.length > 1">
                {{`+${computedResources.length - 1}`}}
            </div>
            <div class="media" v-if="computedResources.length">
                <img :src="computedResources[0].url" 
                    v-if="computedResources[0].type === 'image'">
                <video :src="computedResources[0].url" controls 
                    v-if="computedResources[0].type === 'video'"></video>
                <audio :src="computedResources[0].url" controls
                    v-if="computedResources[0].type === 'audio'"></audio>
            </div>
        </div>
        <div class="created-at" v-if="!request && !alert &&!computedDeletedForMe">
            {{computedCreatedAt}}
        </div>
        <div class="created-at" v-if="request">
            pending
        </div>
        <div class="created-at" v-if="alert"
            :class="{success:message.state==='ACCEPTED',
                danger:message.state==='REJECTED'}"
        >
            {{computedAdminAction}}
        </div>
        <div class="buttons" v-if="computedButtons">
            <action-button
                @click="clickedAccept"
                :green="true"
                title="accept this discussion contribution"
                :loading="acceptLoading"
                :text="'accept'"
                v-if="computedButtonAccept"
            ></action-button>
            <action-button
                @click="clickedReject"
                :red="true"
                title="reject this discussion contribution"
                :loading="rejectLoading"
                :text="'reject'"
                v-if="computedButtonReject"
            ></action-button>
        </div>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture.vue'
import OptionalActions from './OptionalActions.vue'
import ActionButton from './ActionButton.vue'
import { mapGetters } from 'vuex'
import { dates, strings } from '../services/helpers'
    export default {
        components: {
            ActionButton,
            OptionalActions,
            ProfilePicture,
        },
        props: {
            message: {
                type: Object,
                default(){
                    return {}
                }
            },
            request: {
                type: Boolean,
                default: false
            },
            admin: {
                type: Boolean,
                default: false
            },
            simple: {
                type: Boolean,
                default: false
            },
            adminText: {
                type: String,
                default: 'all'
            },
            alert: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                showOptionalActions: false,
                acceptLoading: false,
                rejectLoading: false,
            }
        },
        watch: {
            message: {
                immediate: true,
                handler(newValue) {
                    this.listenToMessageChannels()
                }
            }
        },
        computed: {
            ...mapGetters(['getUser']),
            computedOwnerId() {
                return this.message.fromable.userId 
            },
            computedOwner() {
                return this.getUser && this.getUser.id === this.message.fromable.userId 
            },
            computedFlags() {
                return this.message.flags 
            },
            computedDeletedForMe(){
                return this.getUser && this.message.userDeletes && 
                    this.message.userDeletes.includes(this.getUser.id)
            },
            computedButtons(){
                return this.simple ? false : this.alert ? false : this.request || 
                    (this.admin && this.adminText !== 'all') ||
                    this.message.state !== "ACCEPTED"
            },
            computedButtonAccept(){
                return this.simple ? false : 
                    this.request ? true : !this.alert && (this.adminText === 'pending' || 
                    this.adminText === 'rejected')
            },
            computedButtonReject(){
                return this.simple ? false : 
                    this.request ? true : !this.alert && (this.adminText === 'pending' || 
                    this.adminText === 'accepted')
            },
            computedResources(){
                let resources = []

                if (this.message.images) {
                    
                    this.message.images.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            type: 'image'
                        })
                    })
                }
                if (this.message.videos) {
                    
                    this.message.videos.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            type: 'video'
                        })
                    })
                }
                if (this.message.audios) {
                    
                    this.message.audios.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            type: 'audio'
                        })
                    })
                }
                return resources
            },
            computedAccountType() {
                return this.simple ? '' : this.message.fromable.account 
            },
            computedCreatedAt() {
                return dates.createdAt(this.message.created_at)
            },
            computedAdminAction(){
                return this.simple ? '' : this.alert && this.message.state !== 'PENDING' ? 
                    `admin ${this.message.state.toLowerCase()} your message created at ${this.computedCreatedAt}` 
                    : ''
            },
        },
        methods: {
            listenToMessageChannels() {
                Echo
                    .channel(`youredu.message.${this.message.id}`)
                    .listen(`.deleteMessage`, data=>{
                        this.deleteMessage(data)
                    })
            },
            deleteMessage(data) {
                this.$emit('deleteMessage', data)
            },
            clickedShowMedia() {
                
            },
            clickedReject(){
                if (this.acceptLoading) return
                this.rejectLoading = true
                this.$emit('clickedAction', {
                    message: this.message, 
                    where: this.request ? "request" : this.message.state,
                    action: 'rejected',
                    adminText: this.adminText
                })
            },
            clickedAccept(){
                if (this.rejectLoading) return
                this.acceptLoading = true
                this.$emit('clickedAction', {
                    message: this.message, 
                    where: this.request ? "request" : this.message.state,
                    action: 'accepted'
                })
            },
            clickedOption(data){
                this.showOptionalActions = false
                this.$emit('clickedOption',{
                    action: data,
                    message: this.message,
                })
            },
            clickedShowOptions(){
                if (this.request || this.alert) {
                    return
                }
                this.showOptionalActions = true
                setTimeout(() => {
                    this.showOptionalActions = false
                }, 4000);
            },
        },
    }
</script>

<style lang="scss" scoped>

    .discussion-badge-wrapper{
        width: 300px;
        max-width: 65%;
        padding: 5px;
        position: relative;
        cursor: pointer;

        .deleted{
            width: 100%;
            font-size: 14px;
            padding: 5px;
            display: inline-flex;
            justify-content: flex-start;
            align-items: center;
            color: gray;

            .icon{
                padding: 5px;
                margin-right: 5px;
            }
        }

        .optional-actions{
            left: 0;
            z-index: 1;
        }

        .top{
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            width: 100%;
            color: gray;

            .name{
                font-size: 11px;
                text-transform: capitalize;
                max-width: 65%;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
            }
        }

        .message-section{
            display: inline-flex;
            width: 100%;

            .profile-picture{
                width: 30px;
                height: 30px;
                min-width: 30px;
            }

            .message{
                font-size: 14px;
                padding: 5px;
                background: antiquewhite;
                border-radius: 10px;
                margin: 0 0 0 5px;
            }
        }

        .media-section{
            width: 100%;
            height: 80px;
            position: relative;

            .media-cover{
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                font-size: 25px;
                cursor: pointer;
                background: rgb(128 128 128 / 43%);
                z-index: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                font-weight: 600;
            }

            .media{
                width: inherit;
                height: inherit;

                img,
                video{
                    width: inherit;
                    height: inherit;
                    object-fit: contain;
                }

                audio{
                    width: 100%;
                }
            }
        }

        .created-at{
            width: 100%;
            text-align: start;
            color: gray;
            font-size: 10px;
        }

        .success{
            color: green;
        }

        .danger{
            color: red;
        }

        .buttons{
            width: 100%;
            display: inline-flex;
            justify-content: space-around;
            align-items: center;
        }
    }

    .discussionBadgeMine{
        margin: 0 0 10px auto;

        .deleted{
            justify-content: flex-end;
        }

        .optional-actions{
            right: 0;
            left: unset;
        }

        .top{
            flex-direction: row-reverse;
        }

        .message-section{
            flex-direction: row-reverse;
            justify-content: flex-start;

            .message{
                margin: 0 5px 0 0;
                text-align: end;
            }
        }

        .media-section{

            .media-cover{

            }

            .media{


            }
        }

        .created-at{
            text-align: end;
        }
    }

    .request{
        margin: 10px auto;
        border-radius: 10px;
        box-shadow: 0 0 2px gray;
        width: 100%;
        max-width: unset;
        background: rgb(240,248,255);

        .buttons{
            width: 100%;
            display: inline-flex;
            justify-content: space-around;
            align-items: center;
        }
    }
</style>