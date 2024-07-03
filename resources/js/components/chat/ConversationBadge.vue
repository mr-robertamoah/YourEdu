<template>
    <div class="conversation-badge-wrapper"
        @click="clickedConversation"
    >
        <div class="top-section">
            <div class="top-left-section">
                <profile-picture
                    class="profile-picture"
                >
                    <template slot="image">
                        <img :src="computedOtherAccount.url">
                    </template>
                </profile-picture>
            </div>
            <div class="top-right-section">
                <div class="top">
                    <div class="name">{{computedOtherAccount.name}}</div>
                    <div class="account">{{computedOtherAccount.account}}</div>
                </div>
                <div class="bottom">
                    <div class="typing" v-if="typing">typing...</div>
                    <div class="message" v-if="!typing && !action && computedMessage">{{computedMessage}}</div>
                    <div class="alert" v-if="!typing && !action && alert">{{alert}}</div>
                    <div class="action-button" v-if="action">
                        {{computedActionText}}
                        <pulse-loader :loading="actionLoading" 
                            size="8px"
                            color="#ffffff"
                        ></pulse-loader>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-section">
            <div class="name">{{computedAccount.name}}</div>
            <div class="account">{{`(${computedAccount.account})`}}</div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ProfilePicture from '../profile/ProfilePicture.vue';

    export default {
        props: {
            conversation: {
                type: Object,
                default(){
                    return {}
                }
            },
            message: {
                type: Object,
                default(){
                    return {}
                }
            },
            alert: {
                type: Number,
                default: 0
            },
            action: {
                type: Boolean,
                default: false
            },
            typing: {
                type: Boolean,
                default: false
            },
        },
        components: {
            
            ProfilePicture,
        },
        data() {
            return {
                actionLoading: false,
                typingClock: null,
            }
        },
        watch: {
            typing: {
                handler(newValue, oldValue){
                    if (newValue) {
                        this.clearConversationTyping()
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['getUser']),
            computedOtherAccount() {
                return this.conversation.conversationAccounts.filter(account=>{
                    return account.user_id !== this.getUser.id
                })[0]
            },
            computedAccount() {
                return this.conversation.conversationAccounts.filter(account=>{
                    return account.user_id === this.getUser.id
                })[0]
            },
            computedMessage(){
                return this.message.message ? this.message.message : 
                    this.conversation.message ? this.conversation.message.message : null
            },
            computedActionText(){
                return this.actionLoading ? '' : 
                    this.computedAccount.state === 'DECLINE' ? 'accept' : 'unblock'
            },
        },
        methods: {
            clickedConversation() {
                if (this.action) {
                    this.clickedAction()
                    return
                }
                this.$emit('clickedConversation', {
                    conversation: this.conversation,
                    myAccount: this.computedAccount,
                    otherAccount: this.computedOtherAccount
                })
            },
            clickedAction() {
                this.actionLoading = true
                this.$emit('clickedAction', this.conversation)
            },
            clearConversationTyping(){
                console.log('setting timeout');
                if (this.typingClock) {
                    clearTimeout()
                }
                this.typingClock = setTimeout(() => {
                    this.$emit('clearConversationTyping',this.conversation.id)
                }, 300);
            },
        },
    }
</script>

<style lang="scss" scoped>

    .conversation-badge-wrapper{
        box-shadow: 0 0 2px grey;
        padding: 5px;
        border-radius: 0 10px 10px 0;
        border-left: 2px solid gray;
        width: 100%;

        .top-section{
            display: inline-flex;
            width: 100%;
            align-items: center;

            .top-left-section{

                .profile-picture{
                    width: 40px;
                    height: 40px;
                }
            }

            .top-right-section{
                width: 75%;
                margin-left: 10px;
                
                .top{
                    display: inline-flex;
                    justify-content: space-between;
                    width: 100%;
                    align-items: center;
                    color: gray;
                
                    .name{
                        font-size: 12px;
                        font-weight: 500;
                        text-transform: capitalize;
                        @include text-overflow()
                    }
                    
                    .account{
                        font-size: 10px;
                    }
                }
                
                .bottom{
                    width: 100%;
                    display: inline-flex;
                    justify-content: space-between;
                    align-items: center;
                    position: relative;

                    .typing{
                        font-size: 10px;
                        color: green;
                        font-weight: 600;
                    }
                    
                    .message{
                        font-size: 10px;
                        max-width: 80%;
                    }

                    .alert{
                        margin: 0;
                        font-size:10px;
                        font-weight: 500;
                        padding: 2px;
                        color: brown;
                        border-color: brown;
                        border-radius: 50px;
                        border: 1px solid;
                        position: absolute;
                        right: 10px;
                    }

                    .action-button{
                        margin: 0 0 0 auto;
                        padding: 5px;
                        font-size: 12px;
                        background: green;
                        color: white;
                        border-radius: 10px;
                    }
                }
            }
        }

        .bottom-section{
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            
            .name{
                font-weight: 500;
                font-size: 10px;
                margin-right: 5px;
                text-transform: capitalize;
            }
            
            .account{
                font-size: 8px;
            }
        }
    }
</style>