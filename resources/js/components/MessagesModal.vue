<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                :long="true"
                @mainModalDisappear='closeModal'
                class="messages-modal-wrapper"
            >
                <template slot="main">
                    <welcome-form
                        class="welcome-form"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="clearAlert"
                            ></auto-alert>

                            <div class="messages-section">
                                <div class="messages" 
                                    infinte-wrapper
                                >
                                    <div class="account">
                                        <profile-picture 
                                            class="profile-picture"
                                            v-if="request.account.url"
                                        >
                                            <template slot="image">
                                                <img :src="request.account.url">
                                            </template>
                                        </profile-picture>
                                        <div class="name">
                                            {{request.account.name}}
                                        </div>
                                    </div>

                                    <div class="no-data"
                                        v-if="computedNoMessages"
                                    > no messages for this request</div>
                                    
                                    <div
                                        class="main-messages"
                                        ref="mainmessages"
                                    >
                                        <pulse-loader 
                                            v-if="messagesLoading"
                                            class="loading" 
                                            :loading="messagesLoading"
                                        ></pulse-loader>
                                        <infinite-loader
                                            v-if="computedInfiniteLoader"
                                            @infinite="infiniteHandler"
                                            force-use-infinite-wrapper
                                            direction="top"
                                        ></infinite-loader>

                                        <discussion-badge
                                            v-for="message in messages"
                                            :key="message.id"
                                            :message="message"
                                            :simple="true"
                                            @clickedOption="deleteMessage"
                                            @deleteMessage="removeMessage"
                                        ></discussion-badge>
                                    </div>

                                    <div class="more-data"
                                        @click="infiniteHandler"
                                        v-if="messagesNextPage && messagesNextPage !== 1"
                                    >
                                    </div>
                                </div>
                                
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
                            </div>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import { mapActions } from 'vuex';
import Alert from './../mixins/Alert.mixin';
import InfiniteLoader from 'vue-infinite-loading';
import DiscussionTextarea from './DiscussionTextarea';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import DiscussionBadge from './DiscussionBadge';
import ProfilePicture from "./profile/ProfilePicture"
    export default {
        components: {
            InfiniteLoader,
            DiscussionTextarea,
            PulseLoader,
            DiscussionBadge,
            ProfilePicture,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            request: {
                type: Object,
                default() {
                    return null
                }
            },
            newMessage: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        mixins: [Alert],
        data() {
            return {
                messageText: '',
                messages: [],
                messagesNextPage: 1,
                messageLoading: false,
                messagesLoading: false,
                afterInitial: false,
            }
        },
        watch: {
            request: {
                immediate: true,
                async handler(newValue) {
                    if (newValue && this.show) {
                        await this.getInitialMessages()
                        this.moveToFirstMessage()
                    }
                }
            },
            newMessage(newValue) {
                if (this.show) {
                    this.addMessage(newValue)
                }
            },
        },
        computed: {
            computedNoMessages() {
                return !this.messages.length && this.messagesNextPage !== 1 &&
                    !this.messagesLoading
            },
            computedInfiniteLoader() {
                return this.messages.length && !this.messagesLoading && this.afterInitial
            },
        },
        methods: {
            ...mapActions(['dashboard/getRequestMessages',
                'dashboard/sendRequestMessage','dashboard/deleteRequestMessage'
            ]),
            addMessage(message) {
                this.messages.push(message)
            },
            closeModal() {
                this.clearData()
                this.$emit('closeMessageModal')
            },
            clearData() {
                this.messages = []
                this.messageText = ''
            },
            inputMessage(data){
                this.messageText = data
            },
            moveToFirstMessage() {
                if (this.$refs.mainmessages) {
                    this.$refs.mainmessages.scrollTo(
                        0,
                        window.innerHeight
                    )
                    
                    return 
                }
                
                setTimeout(
                    this.moveToFirstMessage(),
                    200
                )
            },
            async getInitialMessages() {
                this.afterInitial = false
                this.messagesNextPage = 1
                this.messages = await this.getMessages()
                this.afterInitial = true
            },
            async getMessages(){
                let response,
                    nextPage = this.messagesNextPage

                this.messagesLoading = true
                response = await this['dashboard/getRequestMessages']({
                    requestId: this.request.id, nextPage
                })

                this.messagesLoading = false

                if (response.status) {
                    
                    if (response.next) {
                        this.messagesNextPage += 1
                    } else {
                        this.messagesNextPage = null
                    }

                    return response.messages
                }

                console.log('response :>> ', response);
                this.alertDanger = true
                this.alertMessage = `oops! something happened 😒`

                return []
            },
            async infiniteHandler($state){
                if (this.messagesNextPage === 1) {
                    return
                }
                
                if (this.messagesNextPage === null) {
                    $state.complete()
                    return
                }

                this.messages.push(...await this.getMessages())

                if (this.messagesNextPage === null) {
                    $state.complete()
                    return
                }

                $state.loaded()
            },
            async sendMessage(){
                let response,
                    formData = new FormData
                
                this.messageLoading = true
                formData.append('message', this.messageText)
                formData.append('account', this.request.myAccount.account)
                formData.append('accountId', this.request.myAccount.accountId)
                
                response = await this['dashboard/sendRequestMessage']({
                    formData,requestId: this.request.id
                })

                this.messageLoading = false
                if (response.status) {
                    this.addMessage(response.message)
                    this.moveToFirstMessage()
                    return
                }
                
                console.log('response :>> ', response);
                this.alertDanger = true
                this.alertMessage = `oops! message was't sent 😒`
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
                    this.removeMessage(data)
                } else {
                    console.log('response :>> ', response);
                }
            },
            removeMessage(data){
                console.log('data :>> ', data);
                let index = this.messages.findIndex(message=>{
                    return message.id == data.messageId
                })
                if (index === -1) {
                    return
                }
                this.messages.splice(index,1)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .messages-modal-wrapper{
        
        .welcome-form{

            .loading{
                @include sticky-loader();
                top: 0;
            }

            .messages-section{
                margin-bottom: 10px;
                padding: 10px;
                height: 80vh;
                position: relative;

                .message{
                    margin-bottom: 10px;

                    .loading{
                        width: 100%;
                        text-align: center;
                    }
                }

                .messages{
                    height: 90%;

                    .account{
                        display: flex;
                        align-items: flex-start;
                        flex-wrap: nowrap;
                        width: 100%;
                        justify-content: space-between;

                        .profile-picture{
                            min-width: 50px;
                            width: 50px;
                            height: 50px;
                            margin-right: 10px;
                        }

                        .name{
                            @include small-msg;
                            text-align: left;
                            font-size: 14px;
                            text-transform: capitalize;
                            font-weight: bold;
                        }
                    }

                    .main-messages{
                        max-height: 85%;
                        overflow-y: auto;
                    }

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

                .message{
                    position: absolute;
                    bottom: 0;
                    width: 100%;
                }
            }
        }
    }
</style>