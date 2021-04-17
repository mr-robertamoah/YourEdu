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
                            <pulse-loader 
                                v-if="messagesLoading"
                                class="loading" 
                                :loading="messagesLoading"
                            ></pulse-loader>

                            <div class="messages-section">
                                <div class="messages" 
                                    infinte-wrapper
                                >
                                    
                                    <infinite-loader
                                        v-if="!messagesLoading && messagesNextPage && messagesNextPage > 1"
                                        @infinite="infiniteHandler"
                                        force-use-infinite-wrapper
                                        direction="top"
                                    ></infinite-loader>

                                    <div class="no-data"
                                        v-if="computedNoMessages"
                                    > no messages for this request</div>
                                    
                                    <template v-if="messages.length">
                                        <discussion-badge
                                            v-for="message in messages"
                                            :key="message.id"
                                            :message="message"
                                            :simple="true"
                                            @clickedOption="deleteMessage"
                                        ></discussion-badge>
                                    </template>

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
    export default {
        components: {
            InfiniteLoader,
            DiscussionTextarea,
            PulseLoader,
            DiscussionBadge,
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
        },
        mixins: [Alert],
        data() {
            return {
                messageText: '',
                messages: [],
                messagesNextPage: 1,
                messageLoading: false,
                messagesLoading: false,
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue) {
                    if (newValue && this.request) {
                        this.getInitialMessages()
                    }
                }
            },
            request: {
                immediate: true,
                handler(newValue) {
                    if (newValue && this.show) {
                        this.getInitialMessages()
                    }
                }
            }
        },
        computed: {
            computedNoMessages() {
                return !this.messages.length && this.messagesNextPage !== 1 &&
                    !this.messagesLoading
            }
        },
        methods: {
            ...mapActions(['dashboard/getRequestMessages',
                'dashboard/sendRequestMessage','dashboard/deleteRequestMessage'
            ]),
            closeModal() {
                this.$emit('closeMessageModal')
            },
            inputMessage(data){
                this.messageText = data
            },
            async getInitialMessages() {
                this.messagesNextPage = 1
                this.messages = await this.getMessages()
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
                if (this.messagesNextPage === 1 || this.messagesNextPage === null) {
                    return
                }

                this.messages.unshift(...await this.getMessages())

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
        },
    }
</script>

<style lang="scss" scoped>

    .messages-modal-wrapper{
        
        .welcome-form{

            .loading{
                @include sticky-loader();
                top: 49%;
            }

            .messages-section{
                margin-bottom: 10px;
                padding: 10px;
                height: 80vh;

                .message{
                    margin-bottom: 10px;

                    .loading{
                        width: 100%;
                        text-align: center;
                    }
                }

                .messages{
                    height: 90%;

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
        }
    }
</style>