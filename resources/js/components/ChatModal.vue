<template>
    <addon-modal
        :show="true"
        :loading="addonLoading"
        @clickedClose="clickedClose"
        :hasClose="false"
    >
        <template slot="data">
            <div class="chat-top-section">
                <div 
                    v-if="computedMenu"
                    @click="clickedButton('accounts')"
                    class="top-section-item"
                    :class="{active: menuText === 'accounts'}"
                >accounts</div>
                <div 
                    v-if="computedMenu"
                    @click="clickedButton('conversations')"
                    class="top-section-item"
                    :class="{active: menuText === 'conversations'}"
                >conversations</div>
                <div 
                    v-if="computedMenu"
                    @click="clickedButton('profile')"
                    class="top-section-item"
                    :class="{active: menuText === 'profile'}"
                >profiles</div>
                <div class="icon-ellipsis"
                    @click="clickedConversationOptions('more')"
                    :class="{iconEllipsisRed:newConversationAlert}"
                    v-if="menuText === 'conversations' && conversationIcon === 'more' && !showMessages"
                >
                    <font-awesome-icon :icon="['fa','ellipsis-v']"></font-awesome-icon>
                </div>
                <div class="icon-ellipsis"
                    @click="clickedConversationOptions('back')"
                    v-if="menuText === 'conversations' && conversationIcon === 'back' && !showMessages"
                >
                    <font-awesome-icon :icon="['fa','long-arrow-alt-left']"></font-awesome-icon>
                </div>
                <div class="message-options" v-if="showConversationOptions">
                    <div class="option" 
                        @click="clickedPendingConversations"
                        v-if="conversationType !== 'pending'"
                        title="conversations pending your acceptance"
                    >pending 
                        <sup
                            v-if="newPendingConversationsNumber"
                        >{{newPendingConversationsNumber}}</sup>
                    </div>
                    <div class="option" 
                        @click="clickedBlockedConversations"
                        v-if="conversationType !== 'blocked'"
                        title="conversations declined or blocked"
                    >blocked</div>
                </div>
                <div class="accounts-section" v-if="showAccounts">
                    <div class="account-section-item friends"
                        @click="clickedAccountSection('friends')"
                        :class="{accountSectionActive: accountSectionText === 'friends'}"
                    >friends</div>
                    <div class="account-section-item follwers"
                        @click="clickedAccountSection('followers')"
                        :class="{accountSectionActive: accountSectionText === 'followers'}"
                    >followers</div>
                    <div class="account-section-item followings"
                        @click="clickedAccountSection('followings')"
                        :class="{accountSectionActive: accountSectionText === 'followings'}"
                    >followings</div>
                    <div class="account-section-item back"
                        @click="clickedAccountSection('back')"
                        :class="{accountSectionActive: accountSectionText === 'back'}"
                    >
                        <font-awesome-icon :icon="['fa','long-arrow-alt-left']"></font-awesome-icon>
                    </div>

                </div>
                <div class="message-account-section"
                    v-if="showMessages"
                >
                    <profile-picture
                        class="profile-picture"
                    >
                    <template slot="image">
                        <img :src="chattingAccount.url">
                    </template>
                    </profile-picture>
                    <div class="middle">
                        <div class="online-alert" v-if="computedChattingAccountOnlineStatus"></div>
                        <div class="name">{{chattingAccount.name}}</div>
                        <div class="account" v-if="!chattingAccountTyping">{{chattingAccount.account}}</div>
                        <div class="typing" v-if="chattingAccountTyping">typing...</div>
                    </div>
                    <div class="icon"
                        @click="clickedMessageBack"
                    >
                        <font-awesome-icon :icon="['fa','long-arrow-alt-left']"></font-awesome-icon>
                    </div>
                    <div class="icon-ellipsis"
                        @click="clickedMessageOptions"
                        v-if="computedChatStatus && !computedChatBlock"
                    >
                        <font-awesome-icon :icon="['fa','ellipsis-v']"></font-awesome-icon>
                    </div>
                    <div class="message-options" v-if="showMessageOptions">
                        <div class="option" 
                            @click="clickedBlockAccount"
                            title="block this conversation"
                        >block</div>
                    </div>
                </div>
            </div>
            <div class="chat-middle-section"
                :class="{noBorderBottom: !showMessages}"
            >
                <fade-right>
                    <template slot="transition" v-if="showChatAccounts">
                        <div class="chat-accounts-section">
                            <div class="status-sections">
                                <div class="status-button"
                                    @click="clickedStatusButton('all')"
                                    :class="{statusActive: chattingAccountsStatus === 'all'}"
                                >all</div>
                                <div class="status-button"
                                    @click="clickedStatusButton('online')"
                                    :class="{statusActive: chattingAccountsStatus === 'online'}"
                                >online</div>
                            </div>
                            <other-user-account
                                v-for="(account, index) in computedOtherAccounts"
                                :key="index"
                                class="other-user"
                                :account="account"
                                :online="checkOnlineStatus(account)"
                                :friend="accountSectionText === 'friends'"
                                @clickedOtherUserAccount="clickedOtherUserAccount"
                            ></other-user-account>
                        </div>
                    </template>
                </fade-right>
                <fade-right>
                    <template slot="transition" v-if="showChatConversations">
                        <div class="chat-conversations-section" infinite-wrapper>
                            <div class="loading">
                                <pulse-loader :loading="conversationsLoading" size="10px"></pulse-loader>
                            </div>
                            <template v-if="computedConversations.length">
                                <conversation-badge
                                    class="conversation-badge"
                                    v-for="(conversation, index) in computedConversations"
                                    :key="index"
                                    :conversation="conversation"
                                    :typing="setConversationTyping(conversation.id)"
                                    @clickedConversation="clickedConversation"
                                    @clearConversationTyping="clearConversationTyping"
                                    @clickedAction="clickedUnblockAccount"
                                    :action="conversationType === 'blocked'"
                                ></conversation-badge>
                            </template>
                            <div class="no-conversations" 
                                v-if="computedNoConversations">
                                <h4>{{conversationType === 'blocked'? 'No Blocked Conversations': 'No Conversations'}}</h4>
                                <div class="added-message" v-if="conversationType !== 'blocked'">
                                    you can create one by clicking an account
                                </div>
                            </div>

                            <infinite-loading
                                @infinite="infiniteHandler"
                                v-if="computedConversations.length"
                                force-use-infinite-wrapper
                            >
                                <template slot="noResults">
                                    that's it :)
                                </template>
                            </infinite-loading>
                        </div>
                    </template>
                </fade-right>
                <fade-right>
                    <template slot="transition" v-if="showMessages">
                        <div class="chat-messages-section" ref="messagesection">
                            <div class="conversation-creation" v-if="creatingConversation">
                                <h5>creating conversation...</h5>
                            </div>
                            <div class="loading" v-if="!creatingConversation && messagesLoading">
                                <pulse-loader :loading="messagesLoading" size="10px"></pulse-loader>
                            </div>
                            <div class="more-messages" 
                                @click="clickedMoreMessages"
                                v-if="computedMoreMessages"
                            >
                                <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                            </div>
                            <template v-if="!creatingConversation && chatMessages.length">
                                <template class="message-area"
                                        v-for="(message, index) in chatMessages"
                                >
                                    <message-badge
                                        class="message-badge"
                                        v-if="message.hasOwnProperty('message')"
                                        :message="message"
                                        :key="index"
                                        @clickedMedia="clickedMedia"
                                        @clickedOption="clickedOption"
                                    ></message-badge>
                                    <question-badge
                                        class="question-badge"
                                        v-else
                                        :question="message"
                                        :key="index"
                                        @clickedMedia="clickedMedia"
                                        @clickedAnswer="clickedAnswer"
                                        @markChatAnswer="markChatAnswer"
                                        @clickedOption="clickedOption"
                                    ></question-badge>
                                </template>
                            </template>
                            <div class="message-sending" v-if="messageSending">
                                <pulse-loader :loading="messageSending" size="10px"></pulse-loader>
                            </div>
                            <div class="no-messages" 
                                v-if="computedNoMessages"
                            >
                                <h4>No Messages</h4>
                                <div class="added-message">
                                    you can be the first to send a message
                                </div>
                            </div>
                            <div class="chat-request-response"
                                v-if="computedShowRequestResponse"
                            >
                                <div class="accept"
                                    @click="clickedMessageResponse('accept')"
                                    v-if="!messageResponseLoading"
                                    title="accept to chat this account"
                                >accept</div>
                                <div class="decline"
                                    @click="clickedMessageResponse('decline')"
                                    v-if="!messageResponseLoading && !computedChatStatus"
                                    title="decline to chat this account"
                                >decline</div>
                                <div class="response-loading" v-if="messageResponseLoading">
                                    <pulse-loader :loading="messageResponseLoading" size="10px"></pulse-loader>
                                </div>
                            </div>
                        </div>
                    </template>
                </fade-right>
                <fade-right>
                    <template slot="transition" v-if="showChatProfile">
                        <div class="chat-profile-section">
                            <user-account
                                v-for="(profile, index) in computedProfiles"
                                :key="index"
                                :account="profile"
                                :isActive="makeActiveProfile(profile.params)"
                                @clickedActivate="clickedActivate"
                                class="user-account-item"
                            ></user-account>
                        </div>
                    </template>
                </fade-right>
            </div>
            <div class="chat-bottom-section">
                <chat-textarea
                    v-if="showMessages"
                    placeholder="anything to say?"
                    v-model="chatMessage"
                    @sendMessage="sendChatMessage"
                    @sendQuestion="sendChatQuestion"
                    @fileChange="chatFileChange"
                    @clickedClearAnswer="clickedClearAnswer"
                    :disabledChat="!computedOtherChatStatus || !computedChatStatus"
                    :blocked="computedChatBlock"
                    :answer="answering"
                    :possibleAnswer="questionObject && questionObject.answer ? true : false"
                ></chat-textarea>
            </div>
            <!-- media modal for viewing media -->
            <just-fade>
                <template slot="transition" v-if="showMediaModal">
                    <media-modal
                        :show="showMediaModal"
                        :justUrl="true"
                        :url="mediaModalUrl.url"
                        :urlType="mediaModalUrl.type"
                        @mainModalDisappear="closeMediaModal"
                    ></media-modal>
                </template>
            </just-fade>
        </template>
    </addon-modal>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import FadeRight from './transitions/FadeRight'
import UserAccount from './chat/UserAccount'
import ChatTextarea from './chat/ChatTextarea'
import MessageBadge from './chat/MessageBadge'
import QuestionBadge from './chat/QuestionBadge'
import ConversationBadge from './chat/ConversationBadge'
import OtherUserAccount from './chat/OtherUserAccount'
import ProfilePicture from './profile/ProfilePicture'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import { dates, strings } from '../services/helpers'
import InfiniteLoading from 'vue-infinite-loading'
    export default {
        components: {
            PulseLoader,
            ProfilePicture,
            OtherUserAccount,
            ConversationBadge,
            QuestionBadge,
            MessageBadge,
            ChatTextarea,
            UserAccount,
            FadeRight,
            InfiniteLoading,
        },
        data() {
            return {
                addonLoading: false,
                showChatAccounts: true,
                showChatConversations: false,
                showChatProfile: false,
                showAccounts: false,
                showMessages: false,
                showMenu: false,
                menuText: 'conversations',
                accountSectionText: 'friends',
                ///users
                onlineFollowers: [],
                onlineFollowings: [],
                //
                fromToMessages: '', //what was clicked to get to messages
                conversationType: 'normal', //to help get blocked converations
                chatMessage: '',
                chatMessageFileCaption: '',
                chatMessageFile: null,
                chatMessagesNextPage: 1,
                chatMessages: [], //for messages and questions
                conversationId: 0,
                conversationsNextPage: 1,
                blockedConversationsNextPage: 1,
                blockedConversations: [],
                pendingConversationsNextPage: 1,
                pendingConversations: [],
                conversations: [],
                chattingAccountTyping: false,
                chattingAccount: {}, //current account being chatted
                myAccount: {}, //my current account when user clicked conversation
                messageResponseLoading: false,
                conversationsLoading: false,
                messagesLoading: false,
                messageSending: false,
                showMessageOptions: false,
                showConversationOptions: false,
                creatingConversation: false,
                chattingAccountsStatus: 'all',
                conversationIcon: 'more',
                typingAccounts: [],
                newConversationAlert: false,
                newPendingConversationsNumber: 0,
                //for media modal
                showMediaModal: false,
                mediaModalUrl: {},
                //for answering
                answering: false,
                questionObject: null,
            }
        },
        watch: {
            menuText: {
                immediate: true,
                handler(newValue){
                    if (newValue === 'accounts') {
                        this.showChatAccounts = true
                        this.showChatConversations = false
                        this.showChatProfile = false
                        this.showAccounts = true
                        this.showMenu = true
                        this.accountSectionText = 'friends'
                    } else if (newValue === 'profile') {
                        this.showChatProfile = true
                        this.showChatAccounts = false
                        this.showChatConversations = false
                        this.showMenu = true
                    } else if (newValue === 'conversations') {
                        this.showChatConversations = true
                        this.showChatAccounts = false
                        this.showChatProfile = false
                        this.showMenu = true
                    }
                }
            },
            accountSectionText(newValue){
                if (newValue === 'back') {
                    this.showAccounts = false
                    this.showMenu = true
                    this.menuText = 'conversations'
                }
            },
            conversationId(newValue, oldValue){
                if (newValue) {
                    if (newValue !== oldValue) {
                        this.chatMessagesNextPage = 1
                    }
                }
            },
            chatMessage(newValue){
                if (newValue.length) {
                    this.whisper()
                }
            },
            chattingAccountTyping(newValue){
                if (newValue) {
                    setTimeout(() => {
                        this.chattingAccountTyping = false
                    }, 300);
                }
            },
        },
        mounted () {
            this.getChatConversations()
            this.listen()
        },
        computed: {
            ...mapGetters(['getUserFollowings','getUserFollowers','getProfiles',
                'getActiveProfile','getUser']),
            computedMenu() {
                return this.showAccounts || this.showMessages ? false : this.showMenu ?
                    true : false
            },
            computedChattingAccountOnlineStatus(){
                let index = -1
                index = this.onlineFollowers.findIndex(follow=>{
                    return follow.account === this.chattingAccount.account && 
                        follow.accountId === this.chattingAccount.accountId
                })
                if (index > -1) {
                    return true
                }
                index = this.onlineFollowings.findIndex(follow=>{
                    return follow.account === this.chattingAccount.account && 
                        follow.accountId === this.chattingAccount.accountId
                })
                if (index > -1) {
                    return true
                }
            },
            computedMoreMessages(){
                return this.creatingConversation || this.messagesLoading  || 
                    !this.chatMessagesNextPage  || this.chatMessages.length < 10 ? false : true
            },
            computedShowRequestResponse(){
                return this.messagesLoading ? false : this.computedChatStatusRequest ? false : 
                    this.computedChatStatus ? false : this.computedChatStatusPending && 
                    !this.chatMessages.length ? true : this.computedChatStatusPending &&  
                    this.chatMessages.filter(message=>message.from_user_id === this.getUser.id).length < 1
                    ? true : false
            },
            computedChatStatus(){ 
                if (this.chattingAccount.hasOwnProperty('myChatStatus')) {
                    return this.chattingAccount.myChatStatus === 'DECLINE' ||
                        this.chattingAccount.myChatStatus === 'BLOCK' ? false : true
                } else {
                    return this.myAccount.state === 'DECLINE' || 
                        this.myAccount.state === 'BLOCK' ? false : true
                }
            },
            computedChatStatusPending(){ 
                if (this.chattingAccount.hasOwnProperty('myChatStatus')) {
                    return this.chattingAccount.myChatStatus === 'PENDING' ? true : false
                } else {
                    return this.myAccount.state === 'PENDING' ? true : false
                }
            },
            computedChatStatusRequest(){ 
                if (this.chattingAccount.hasOwnProperty('myChatStatus')) {
                    return this.chattingAccount.myChatStatus === 'REQUEST' ? true : false
                } else {
                    return this.myAccount.state === 'REQUEST' ? true : false
                }
            },
            computedOtherChatStatus(){ //true means u can chat the chattingaccount
                if (this.chattingAccount.hasOwnProperty('myChatStatus')) {
                    return this.chattingAccount.otherChatStatus === 'DECLINE' || 
                        this.chattingAccount.otherChatStatus === 'BLOCK' ? false : true
                } else {
                    return this.chattingAccount.state === 'DECLINE' || 
                        this.chattingAccount.state === 'BLOCK' ? false : true
                }
            },
            computedProfiles() {
                return this.getProfiles ? this.getProfiles : []
            },
            computedActiveProfile() {
                return this.getActiveProfile ? this.getActiveProfile : []
            },
            computedFollowers() {
                let computedArray = []
                if (this.chattingAccountsStatus === 'all') {
                    computedArray = this.getUserFollowers
                } else if (this.chattingAccountsStatus === 'online') {
                    return this.onlineFollowers
                }
                return computedArray ? this.mapAccounts(computedArray,'follower') : []
            },
            computedFollowings() {
                let computedArray = []
                if (this.chattingAccountsStatus === 'all') {
                    computedArray = this.getUserFollowings
                } else if (this.chattingAccountsStatus === 'online') {
                    return this.onlineFollowings
                }
                return computedArray ? this.mapAccounts(computedArray,'following') : []
            },
            computedFriends() {
                return _.intersectionWith(this.computedFollowers, this.computedFollowings, 
                    (follower, following)=>{
                        return follower.accountId === following.accountId && 
                            follower.account === following.account && 
                            follower.myAccountId === following.myAccountId && 
                            follower.myAccount === following.myAccount
                    })
            },
            computedOtherAccounts(){ //this is used in the template
                if (this.accountSectionText === 'friends') {
                    return this.computedFriends
                } else if (this.accountSectionText === 'followers') {
                    return this.computedFollowers
                } else if (this.accountSectionText === 'followings') {
                    return this.computedFollowings
                } 
            },
            computedChatMessages(){
                return this.chatMessages.length ? _.sortBy(this.chatMessages,function(message){
                    return message.id
                }) : []
            },
            computedConversations(){
                if (this.conversationType === 'blocked') {
                    return this.blockedConversations
                } else if (this.conversationType === 'pending') {
                    return this.pendingConversations
                } else if (this.conversationType === 'normal') {
                    return this.conversations
                }
            },
            computedNoConversations(){
                if (this.conversationType === 'normal') {
                    return !this.conversations.length && this.conversationsNextPage !== 1
                } else if (this.conversationType === 'blocked') {
                    return !this.blockedConversations.length && this.blockedConversationsNextPage !== 1
                } else if (this.conversationType === 'pending') {
                    return !this.pendingConversations.length && this.pendingConversationsNextPage !== 1
                }
            },
            computedChatBlock(){
                let status = this.chattingAccount.hasOwnProperty('state') ? this.chattingAccount.state :
                    this.chattingAccount.otherChatStatus
                if (status === 'BLOCK' || status === 'DECLINE'){
                    return true
                }
                return false
            },
            computedNoMessages(){
                return this.creatingConversation || this.messagesLoading ? false : 
                    this.chatMessages.length ? false : true
            },
        },
        methods: {
            ...mapActions(['profile/setActiveProfile','profile/sendChatMessage',
                'profile/getChatMessages','profile/getChatConversations',
                'profile/createConversation','profile/sendMessageResponse',
                'profile/blockConversation','profile/unblockConversation',
                'profile/getBlockedConversations','profile/getPendingConversations',
                'updateUserFollows','profile/sendChatQuestion',
                'profile/sendChatAnswer','profile/sendChatMark',
                'profile/deleteChatMessage','profile/deleteChatQuestion',
                'profile/deleteChatAnswer','profile/updateChatItemStatus']),
            clickedOption(data){
                if (data.type === 'message' && data.action.includes('delete')) {
                    this.deleteMessage(data)
                } else if (data.type === 'question' && data.action.includes('delete')) {
                    this.deleteQuestion(data)
                } else if (data.type === 'answer' && data.action.includes('delete')) {
                    this.deleteAnswer(data)
                }
            },
            clickedMedia(data){
                this.mediaModalUrl = data
                this.showMediaModal = true
            },
            clickedAnswer(data){
                if (data.answer) {
                    this.chatMessage = data.answer.option
                }
                this.questionObject = data
                this.answering = true
            },
            clickedClearAnswer(){
                this.answering = false
                this.chatMessage = ''
                this.questionObject = null
            },
            async deleteMessage(message){
                let response,
                    userId = this.chattingAccount.hasOwnProperty('account_user_id') ?
                    this.chattingAccount.account_user_id : this.chattingAccount.user_id

                response = await this['profile/deleteChatMessage']({
                    item: 'message',
                    itemId: message.message.id,
                    userId,
                    conversationId: message.conversationId,
                    action: message.action === 'delete' ? message.action : 'self'
                })

                if (response.status) {
                    if (message.action === 'delete') {
                        this.removeChatItem('message',message.message.id,message.conversationId)
                    } else {
                        this.replaceChatItem('message',response.chatMessage,message.conversationId)
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async deleteQuestion(question){
                let response,
                    userId = this.chattingAccount.hasOwnProperty('account_user_id') ?
                    this.chattingAccount.account_user_id : this.chattingAccount.user_id

                response = await this['profile/deleteChatQuestion']({
                    item: 'question',
                    itemId: question.question.id,
                    userId,
                    conversationId: question.conversationId,
                    action: question.action === 'delete' ? question.action : 'self'
                })

                if (response.status) {
                    if (question.action === 'delete') {
                        this.removeChatItem('question',question.question.id,question.conversationId)
                    } else {
                        this.replaceChatItem('question',response.chatQuestion,question.conversationId)
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async deleteAnswer(answer){
                let response,
                    userId = this.chattingAccount.hasOwnProperty('account_user_id') ?
                    this.chattingAccount.account_user_id : this.chattingAccount.user_id

                response = await this['profile/deleteChatAnswer']({
                    item: 'answer',
                    itemId: answer.answer.id,
                    userId,
                    conversationId: answer.conversationId,
                    action: 'delete'
                })

                if (response.status) {
                    this.removeChatItem('answer',answer.answer.id,answer.conversationId)
                } else {
                    console.log('response :>> ', response);
                }
            },
            removeChatItem(item, itemId, conversationId){
                if (this.conversationId != conversationId) {
                    return
                }
                let index
                if (item === 'question') {
                    index = this.chatMessages.findIndex(question=>{
                        return question.id === itemId && question.hasOwnProperty('question')
                    })
                } else if (item === 'message') {
                    index = this.chatMessages.findIndex(message=>{
                        return message.id === itemId && message.hasOwnProperty('message')
                    })
                } else if (item === 'answer') {
                    this.chatMessages.forEach(item=>{
                        if (item.hasOwnProperty('question')) {
                            index = item.answers.findIndex(answer=>{
                                return answer.id === itemId
                            })
                            if (index > -1) {
                                item.answers.splice(index,1)
                                return
                            }
                        }
                    })
                }
                if (index > -1) {
                    this.chatMessages.splice(index,1)
                }
                this.moveConversationUp(conversationId)
            },
            replaceChatItem(item, chatItem, conversationId){
                if (this.conversationId != conversationId) {
                    return
                }
                let index
                if (item === 'question') {
                    index = this.chatMessages.findIndex(question=>{
                        return question.id === chatItem.id && question.hasOwnProperty('question')
                    })
                } else if (item === 'message') {
                    index = this.chatMessages.findIndex(message=>{
                        return message.id === chatItem.id && message.hasOwnProperty('message')
                    })
                }
                if (index > -1) {
                    this.chatMessages.splice(index,1,chatItem)
                }
                this.moveConversationUp(conversationId)
            },
            async markChatAnswer(markDetails){
                this.messageSending = true
                let response,
                    data = {},
                    userId = this.chattingAccount.hasOwnProperty('account_user_id') ? 
                        this.chattingAccount.account_user_id : this.chattingAccount.user_id
                
                data.account = this.getActiveProfile.params.account
                data.accountId = this.getActiveProfile.params.accountId
                data.chattingUserId = userId
                data.answerId = markDetails.answerId
                data.questionId = markDetails.questionId
                data.score = markDetails.score
                data.score_over = markDetails.scoreOver
                data.remark = markDetails.remark

                response = await this['profile/sendChatMark']({
                    conversationId:this.conversationId,data})

                if (response.status) {
                    this.moveQuestionDownById(response.chatAnswer)
                    this.moveConversationUp(this.conversationId)
                } else {
                    console.log('response :>> ', response);
                }

                this.messageSending = false
            },
            async sendChatItemStatus(data){
                let response

                response = await this['profile/updateChatItemStatus'](data)

                if (response.status) {
                    this.replaceChatItem(data.item,response.chatItem,data.conversationId)
                } else {
                    console.log('response :>> ', response);
                }
            },
            moveQuestionDownById(answer){ //for updating the position of a question by id and replacing answer
                let index,
                    ques,
                    answerIndex
                index = this.chatMessages.findIndex(message=>{
                    return message.id === answer.answerable_id && 
                        message.hasOwnProperty('question')
                })
                if (index > -1) {
                    answerIndex = this.chatMessages[index].answers.findIndex(ans=>{
                        return ans.id === answer.id
                    })
                    if (answerIndex > -1) {
                        this.chatMessages[index].answers.splice(answerIndex,1,answer)
                    }
                    ques = this.chatMessages[index]
                    this.chatMessages.splice(index,1)
                    this.chatMessages.push(ques)
                }
            },
            closeMediaModal(){
                this.showMediaModal = false
            },
            chatFileChange(fileData){
                this.chatMessageFile = fileData.file
                this.chatMessageFileCaption = fileData.caption
                this.sendChatMessage()
            },
            setChattingAccountTyping(conversationId){
                if (this.chattingAccount.conversationId === conversationId ||
                    this.chattingAccount.conversation_id === conversationId) {
                    this.chattingAccountTyping =  true
                }
            },
            setTyping(conversationId){
                this.setChattingAccountTyping(conversationId,true)
                let index = this.typingAccounts.findIndex(typing=>typing === conversationId)
                if (index === -1) {
                    this.typingAccounts.push(conversationId)
                }
            },
            unsetTyping(conversationId){
                let index = this.typingAccounts.findIndex(typing=>typing === conversationId)
                if (index === -1) {
                    this.typingAccounts.splice(index,1)
                }
            },
            checkTyping(conversationId){
                let index = this.typingAccounts.findIndex(typing=>typing === conversationId)
                if (index > -1) {
                    return true
                } else {
                    return false
                }
            },
            setConversationTyping(conversationId){
                return this.checkTyping(conversationId)
            },
            clearConversationTyping(conversationId){
                this.unsetTyping(conversationId)
            },
            whisper(){
                let userId = this.chattingAccount.hasOwnProperty('account_user_id') ?
                    this.chattingAccount.account_user_id : this.chattingAccount.user_id
                Echo.private(`youredu.message.${userId}`)
                    .whisper('typing',{
                        conversationId: this.conversationId
                    })
            },
            clickedMessageOptions(){
                this.showMessageOptions = true
                setTimeout(() => {
                    this.showMessageOptions = false
                }, 4000);
            },
            clickedConversationOptions(data){
                this.newConversationAlert = false
                if (data === 'more') {
                    this.showConversationOptions = true
                    setTimeout(() => {
                        this.showConversationOptions = false
                    }, 4000);
                } else if (data === 'back') {
                    this.conversationIcon = 'more'
                    this.conversationType = 'normal'
                }
            },
            clickedBlockedConversations(){
                this.conversationIcon = 'back'
                this.conversationType = 'blocked'
                this.showConversationOptions = false
                this.blockedConversationsNextPage = 1
                this.blockedConversations = []
                this.getBlockedConversations()
            },
            clickedPendingConversations(){
                this.conversationIcon = 'back'
                this.conversationType = 'pending'
                this.newPendingConversationsNumber = 0
                this.showConversationOptions = false
                this.pendingConversationsNextPage = 1
                this.pendingConversations = []
                this.getPendingConversations()
            },
            async clickedBlockAccount(){
                this.showMessageOptions = false
                let response,
                    data = {}
                
                data.account = this.getActiveProfile.params.account
                data.accountId = this.getActiveProfile.params.accountId
                data.chattingAccount = this.chattingAccount.account
                data.chattingAccountId = this.chattingAccount.accountId
                
                response = await this['profile/blockConversation']({
                    conversationId: this.conversationId,
                    data
                })

                if (response.status) {
                    let conversationAccount = response.conversation.conversationAccounts.find(account=>{
                        return account.user_id === this.getUser.id
                    })
                    this.removeConversation(response.conversation,'normal')
                    this.menuText = 'conversations'
                    this.updateChattingAccount(conversationAccount.conversation_id,'BLOCK')
                    this.updateOnlineAccounts(conversationAccount.conversation_id,'BLOCK')
                } else {

                }
            },
            async clickedUnblockAccount(conversation){
                let response,
                    data = {}
                
                data.accountOne = conversation.conversationAccounts[0].account
                data.accountOneId = conversation.conversationAccounts[0].accountId
                data.accountTwo = conversation.conversationAccounts[1].account
                data.accountTwoId = conversation.conversationAccounts[1].accountId
                
                response = await this['profile/unblockConversation']({
                    conversationId: conversation.id,
                    data
                })

                if (response.status) {
                    let index = this.blockedConversations.findIndex(conv=>{
                        return conv.id === response.conversation.id
                    })
                    if (index > -1) {
                        this.blockedConversations.splice(index,1)
                    }
                    this.conversations.unshift(response.conversation)
                    this.removeConversation(response.conversation,'blocked')
                    this.updateChattingAccount(response.conversation.id,'ACCEPT')
                    this.updateOnlineAccounts(response.conversation.id,'ACCEPT')
                } else {
                    console.log(response);
                }
            },
            markMessagesQuestionsSeen(conversationId){
                if (this.conversationId != conversationId) {
                    console.log('returned');
                    return
                }
                this.chatMessages.forEach(chatItem=>{
                    if (chatItem.from_user_id === this.getUser.id ||
                        chatItem.user_id === this.getUser.id) {
                        chatItem.state = 'SEEN'
                    }
                })
            },
            listen(){
                Echo.private(`youredu.message.${this.getUser.id}`)
                    .listenForWhisper('typing',data=>{
                        console.log(data);
                        this.setTyping(data.conversationId)
                    })
                    .listen('.newChatMessage', message=>{
                        this.moveConversationUp(message.message.conversationId)
                        if (message.message.conversationId === this.conversationId) {
                            this.chatMessages.push(message.message)
                            this.scrollDown()
                            this.sendChatItemStatus({
                                item: 'message',
                                itemId: message.message.id,
                                conversationId: message.message.conversationId,
                                state: 'SEEN',
                                userId: message.message.from_user_id
                            })
                        } else {
                            this.sendChatItemStatus({
                                item: 'message',
                                itemId: message.message.id,
                                conversationId: message.message.conversationId,
                                state: 'RECEIVED',
                                userId: message.message.from_user_id
                            })
                        }
                    })
                    .listen('.newChatQuestion', question=>{
                        this.moveConversationUp(question.question.questionableId)
                        if (question.question.questionableId === this.conversationId) {
                            this.chatMessages.push(question.question)
                            this.scrollDown()
                            this.sendChatItemStatus({
                                item: 'question',
                                itemId: question.question.id,
                                conversationId: question.question.questionableId,
                                state: 'SEEN',
                                userId: question.question.user_id
                            })
                        } else {
                            this.sendChatItemStatus({
                                item: 'question',
                                itemId: question.question.id,
                                conversationId: question.question.questionableId,
                                state: 'RECEIVED',
                                userId: question.question.user_id
                            })
                        }
                    })
                    .listen('.newChatAnswer', question=>{
                        this.moveConversationUp(question.question.questionableId)
                        this.moveQuestionDown(question.question)
                    })
                    .listen('.newChatMark', question=>{
                        this.moveConversationUp(question.question.questionableId)
                        this.moveQuestionDown(question.question)
                    })
                    .listen('.newConversation', data=>{
                        this.newConversationAlert = true
                        this.newPendingConversationsNumber += 1
                        this.updateUserFollows({
                            type: 'conversation',
                            conversation: data.conversation,
                        })
                        this.updateOnlineAccounts(data.conversation.id, null,'conversation',data.conversation)
                    })
                    .listen('.newConversationResponse', data=>{
                        this.updateUserFollows({
                            type: 'response',
                            conversationId: data.conversation.id,
                            response: data.response
                        })
                        let otherAccount = data.conversation.conversationAccounts.find(account=>{
                            return account.id !== this.getUser.id
                        })  
                        if (otherAccount) {
                            this.updateConversationAccountState(otherAccount)
                        }                   
                        this.updateChattingAccount(data.conversation.id,data.response,false)
                        this.updateOnlineAccounts(data.conversation.id,data.response,'response',data.conversation,false)
                    })
                    .listen('.deleteChatItem', data=>{
                        this.removeChatItem(data.item, data.itemId, data.conversationId)
                    })
                    .listen('.updatedChatItemState', data=>{
                        this.replaceChatItem(data.item, data.chatItem, data.conversationId)
                    })
                    .listen('.updatedChatItemsState', data=>{
                        this.markMessagesQuestionsSeen(data.conversationId)
                    })

                Echo.join('youredu.chat')
                    .here(users=>{
                        console.log(users);
                        this.onlineFollowings = this.mapAccounts(this.getUserFollowings.filter(following=>{
                            return users.includes(following.followed_user_id)
                        }), 'following')

                        this.onlineFollowers = this.mapAccounts(this.getUserFollowers.filter(follower=>{
                            return users.includes(follower.user_id)
                        }),'follower')

                    })
                    .joining(userId=>{
                        console.log(userId);
                        let followArray = []
                        this.getUserFollowings.forEach(follow=>{
                            if (follow.followed_user_id === userId) {
                                followArray.push(follow)
                            }
                        })
                        this.onlineFollowings.unshift(...this.mapAccounts(followArray,'following'))
                        followArray = []
                        this.getUserFollowers.forEach(follow=>{
                            if (follow.user_id === userId) {
                                followArray.push(follow)
                            }
                        })
                        this.onlineFollowers.unshift(...this.mapAccounts(followArray,'follower'))
                    })
                    .leaving(userId=>{
                        console.log(userId);
                        this.onlineFollowings = this.onlineFollowings.filter(follow=>{
                            return follow.account_user_id !== userId
                        })
                        this.onlineFollowers = this.onlineFollowers.filter(follow=>{
                            return follow.account_user_id !== userId
                        })
                    })
            },
            mapAccounts(accounts, type){
                return  accounts.map(account=>{
                    return {
                        id: account.id,
                        name: account.name,
                        url: account.url,
                        otherChatStatus: type === 'following' ? 
                            account.followable_chat_status :
                            account.followedby_chat_status,
                        account: type === 'following' ? 
                            strings.getAccount(account.followable_type) :
                            strings.getAccount(account.followedby_type),
                        accountId: type === 'following' ?
                            account.followable_id :
                            account.followedby_id,
                        myChatStatus: type === 'following' ? 
                            account.followedby_chat_status :
                            account.followable_chat_status,
                        myAccount: type === 'following' ? 
                            strings.getAccount(account.followedby_type) :
                            strings.getAccount(account.followable_type),
                        myAccountId: type === 'following' ?
                            account.followedby_id :
                            account.followable_id,
                        account_user_id: type === 'following' ?
                            account.followed_user_id :
                            account.user_id,
                        myName: account.my_name,
                        conversationId: account.conversation_id,
                        type
                    }
                }) 
            },
            clickedMoreMessages(){
                this.getChatMessages()
            },
            clickedStatusButton(data){
                this.chattingAccountsStatus = data
            },
            scrollDown(){
                setTimeout(() => {
                    this.$refs.messagesection.scrollTo(0,99999999)
                }, 100);
            },
            clickedClose(){
                this.$emit('clickedClose','chat')
            },
            clickedButton(data){
                this.menuText = data
            },
            clickedAccountSection(data){
                this.accountSectionText = data
            },
            checkOnlineStatus(account){
                if (this.chattingAccountsStatus === 'online') {
                    return true
                } else if (this.chattingAccountsStatus === 'all') {
                    let index = -1
                    if (this.accountSectionText === 'followers') {
                        index = this.onlineFollowers.findIndex(follow=>{
                            return follow.account === account.account && 
                                follow.accountId === account.accountId
                        })
                        if (index > -1) {
                            return true
                        }
                    } 
                    index = this.onlineFollowings.findIndex(follow=>{
                        return follow.account === account.account && 
                            follow.accountId === account.accountId
                    })
                    if (index > -1) {
                        return true
                    }
                }
            },
            makeActiveProfile(params){
                return params.account === this.computedActiveProfile.params.account &&
                    params.accountId === this.computedActiveProfile.params.accountId ?
                    true : false
            },
            clickedActivate(data){
                this['profile/setActiveProfile']({
                    account: data.params.account,
                    account_id: data.params.accountId,
                })
            },
            setActiveProfile(data){
                this['profile/setActiveProfile']({
                    account: data.account,
                    account_id: data.accountId,
                })
            },
            async clickedMessageResponse(messageResponse){
                this.messageResponseLoading = true
                let response = null,
                    data = {}
                
                data.account = this.getActiveProfile.params.account
                data.accountId = this.getActiveProfile.params.accountId
                data.chattingAccount = this.chattingAccount.account
                data.chattingAccountId = this.chattingAccount.accountId

                if (messageResponse === 'accept') {
                    data.response = 'ACCEPT'
                } else if (messageResponse === 'decline') {
                    data.response = 'DECLINE'
                }

                response = await this['profile/sendMessageResponse']({
                    conversationId: this.conversationId,
                    data
                })

                if (response.status) {
                    let conversationAccount = response.conversation.conversationAccounts.find(account=>{
                        return account.user_id === this.getUser.id
                    })
                    if (messageResponse === 'accept') {
                        this.removeConversation(response.conversation, 'blocked')
                        this.removeConversation(response.conversation, 'pending')
                        this.conversations.unshift(response.conversation)
                    } else if (messageResponse === 'decline') {
                        this.removeConversation(response.conversation, 'pending')
                        this.menuText = 'conversations'
                    }
                    this.updateChattingAccount(conversationAccount.conversation_id,conversationAccount.state)
                    this.updateOnlineAccounts(conversationAccount.conversation_id,conversationAccount.state)
                } else {

                }
                this.messageResponseLoading = false
            },
            removeConversation(conversation, where='normal'){
                let index
                if (where === 'normal') {
                    index = this.conversations.findIndex(conv=>{
                        return conv.id === conversation.id
                    })
                    if (index > -1) {
                        this.conversations.splice(index,1)
                    }
                } else if (where === 'blocked') {
                    index = this.blockedConversations.findIndex(conv=>{
                        return conv.id === conversation.id
                    })
                    if (index > -1) {
                        this.blockedConversations.splice(index,1)
                    }
                } else if (where === 'pending') {
                    index = this.pendingConversations.findIndex(conv=>{
                        return conv.id === conversation.id
                    })
                    if (index > -1) {
                        this.pendingConversations.splice(index,1)
                    }
                }
            },
            updateChattingAccount(conversationId, conversationStatus, mine = true, id = false){
                if (id) {
                    this.chattingAccount.conversationId = conversationId
                }
                if (this.chattingAccount.hasOwnProperty('myChatStatus') && 
                    this.chattingAccount.hasOwnProperty('conversationId') && 
                    this.chattingAccount.conversationId === conversationId) {
                    if (mine) {
                        this.chattingAccount.myChatStatus = conversationStatus
                        if (id) {
                            this.chattingAccount.otherChatStatus = 'PENDING'
                        }
                    } else {
                        this.chattingAccount.otherChatStatus = conversationStatus
                    }
                } else if (this.chattingAccount.hasOwnProperty('state') && 
                    this.chattingAccount.hasOwnProperty('conversation_id') && 
                    this.chattingAccount.conversation_id === conversationId) {
                    if (mine) {
                        this.myAccount.state = conversationStatus
                    } else {
                        this.chattingAccount.state = conversationStatus
                    }
                }
            },
            updateOnlineAccounts(conversationId, conversationStatus= null,type= 'response',conversation = null, mine = true){
                let index

                if (type === 'response') {
                    index = this.onlineFollowers.findIndex(follow=>{
                        return follow.conversationId === conversationId
                    })
                    if (index > -1) {
                        if (mine) {
                            this.onlineFollowers[index].myChatStatus = conversationStatus
                        } else {
                            this.onlineFollowers[index].otherChatStatus = conversationStatus
                        }
                    }
                    index = this.onlineFollowings.findIndex(follow=>{
                        return follow.conversationId === conversationId
                    })
                    if (index > -1) {
                        if (mine) {
                            this.onlineFollowings[index].myChatStatus = conversationStatus
                        } else {
                            this.onlineFollowings[index].otherChatStatus = conversationStatus
                        }
                    }
                } else if (type === 'conversation') {
                    let otherAccount,
                        myAccount
                    index = conversation.conversationAccounts.findIndex(account=>{
                        return account.user_id === this.getUser.id
                    })
                    if (index === -1) {
                        return 
                    } else if (index === 0) {
                        myAccount = conversation.conversationAccounts[0]
                        otherAccount = conversation.conversationAccounts[1]
                    } else if (index === 1) {
                        myAccount = conversation.conversationAccounts[1]
                        otherAccount = conversation.conversationAccounts[0]
                    }
                    //find and update conversationid for the follower and following
                    index = this.onlineFollowers.findIndex(follow=>{
                        return follow.account === otherAccount.account && 
                            follow.accountId === otherAccount.accountId && 
                            follow.myAccount === myAccount.account &&
                            follow.myAccountId === myAccount.accountId
                    })
                    if (index > -1) {
                        this.onlineFollowers[index].conversationId = conversationId
                    }
                    index = this.onlineFollowings.findIndex(follow=>{
                        return follow.account === otherAccount.account && 
                            follow.accountId === otherAccount.accountId && 
                            follow.myAccount === myAccount.account &&
                            follow.myAccountId === myAccount.accountId
                    })
                    if (index > -1) {
                        this.onlineFollowings[index].conversationId = conversationId
                    }
                }
            },
            updateConversationAccountState(conversationAccount){
                let cIndex = this.conversations.findIndex(conversation=>{
                    return conversation.id === conversationAccount.conversation_id
                })
                if (cIndex > -1) {
                    let aIndex = this.conversations[cIndex].conversationAccounts.findIndex(account=>{
                        return account.id === conversationAccount.id
                    })
                    if (aIndex > -1) {
                        this.conversations[cIndex].conversationAccounts.splice(aIndex,1,conversationAccount)
                    }
                }
            },
            clickedConversation({conversation, myAccount, otherAccount}){
                this.fromToMessages = 'conversations'
                this.showChatConversations = false
                this.conversationId = conversation.id
                this.conversationMessaging(conversation.id)
                this.showMessages = true
                this.chattingAccount = otherAccount
                this.myAccount = myAccount
                this.setActiveProfile(myAccount)
                this.scrollDown()
            },
            clickedOtherUserAccount(account){
                this.fromToMessages = 'accounts'
                this.showAccounts = false
                this.myAccount = {}
                this.chattingAccount = account
                this.conversationId = account.conversationId
                this.conversationMessaging(account.conversationId)
                this.showChatAccounts = false
                this.showMessages = true
            },
            conversationMessaging(conversationId){
                if (conversationId === null) {
                    this.chatMessages = []
                    this.createConversation()
                } else {
                    this.chatMessages = []
                    this.getChatMessages()
                }
            },
            clickedMessageBack(){
                this.showMessages = false
                this.chatMessagesNextPage = 1
                this.showMenu = true
                if (this.fromToMessages === 'accounts') {
                    this.showChatAccounts = true
                    this.showAccounts = true
                } else if (this.fromToMessages === 'conversations') {
                    this.showChatConversations = true
                }
                if (this.answering) {
                    this.clickedClearAnswer()
                }
            },
            async createConversation(){
                let response = null,
                    data = {}
                this.creatingConversation = true
                data.account = this.chattingAccount.myAccount
                data.accountId = this.chattingAccount.myAccountId
                data.userId = this['getUser'].id
                data.chatAccount = this.chattingAccount.account
                data.chatAccountId = this.chattingAccount.accountId
                data.otherUserId = this.chattingAccount.account_user_id

                response = await this['profile/createConversation'](data)

                if (response.status) {
                    this.updateChattingAccount(response.conversation.id, 'REQUEST', true, true)
                    this.conversations.unshift(response.conversation)
                    this.conversationId = response.conversation.id
                    this.updateOnlineAccounts(response.conversation.id, null,'conversation',response.conversation,true)
                    this.updateUserFollows({
                        type: 'conversation',
                        conversation: response.conversation
                    })
                } else {

                }
                this.creatingConversation = false
            },
            moveConversationUp(conversationId){ //for updating the position of a conversation
                
                let index,
                    conversation
                index = this.conversations.findIndex(conv=>{
                    return conv.id === conversationId
                })
                if (index > -1) {
                    conversation = this.conversations[index]
                    this.conversations.splice(index,1)
                    this.conversations.unshift(conversation)
                }
            },
            moveQuestionDown(question){ //for updating the position of a question

                if (this.conversationId !== question.questionableId) {
                    return 
                }
                let index = this.chatMessages.findIndex(message=>{
                    return message.id === question.id && message.hasOwnProperty('question')
                })
                if (index > -1) {
                    this.chatMessages.splice(index,1)
                }
                this.chatMessages.push(question)
            },
            async sendChatQuestion(data){
                this.messageSending = true
                let formData = new FormData,
                    conversationId =  this.conversationId,
                    response,
                    userId = this.chattingAccount.hasOwnProperty('account_user_id') ? 
                        this.chattingAccount.account_user_id : this.chattingAccount.user_id

                formData.append('account', this.getActiveProfile.params.account)
                formData.append('accountId', this.getActiveProfile.params.accountId)
                formData.append('chattingUserId',userId)
                formData.append('question', data.question)
                formData.append('published', new Date().toDateString())
                formData.append('score', data.score ? data.score : null)
                if (data.file && data.file.length) {
                    formData.append('file', data.file[0])
                }
                if (data.possibleAnswers) {
                    formData.append('possibleAnswers', JSON.stringify(data.possibleAnswers))
                }

                response = await this['profile/sendChatQuestion']({
                    conversationId, formData
                })

                if (response.status) {
                    this.chatMessages.push(response.chatQuestion)
                    this.chatMessage = ''
                    this.moveConversationUp(response.chatQuestion.questionableId)
                    if (this.computedShowRequestResponse) {
                        this.clickedMessageResponse('accept')
                    }
                } else {
                    console.log('response :>> ', response);
                }
                this.messageSending = false
                this.scrollDown()
            },
            async sendChatMessage(){
                if ((this.chatMessage === null || this.chatMessage === '') &&
                    this.chatMessageFile === null) {
                    return 
                }
                this.messageSending = true
                let response = null,
                    formData = new FormData,
                    data = {},
                    userId = this.chattingAccount.hasOwnProperty('account_user_id') ? 
                        this.chattingAccount.account_user_id : this.chattingAccount.user_id

                formData.append('account',this['getActiveProfile'].params.account)
                formData.append('accountId',this['getActiveProfile'].params.accountId)
                formData.append('chattingUserId',userId)
                
                data.conversationId = this.conversationId
                if (!this.answering) {
                    
                    if (this.chatMessageFile) {
                        formData.append('message',this.chatMessageFileCaption)
                        formData.append('file',this.chatMessageFile)
                    } else {
                        formData.append('message',this.chatMessage)
                    }

                    formData.append('chattingAccount',this.chattingAccount.account)
                    formData.append('chattingAccountId',this.chattingAccount.accountId)
    
                    data.formData = formData
                    
                    response = await this['profile/sendChatMessage'](data)
                } else {
                    
                    formData.append('questionId',this.questionObject.question.id)
                    if (this.questionObject.answer) {
                        formData.append('possible_answer_id',this.questionObject.answer.id)
                        formData.append('answer',this.chatMessage)
                    } else {
                        if (this.chatMessageFile) {
                            formData.append('file',this.chatMessageFile)
                            formData.append('answer',this.chatMessageFileCaption)
                        } else {
                            formData.append('answer',this.chatMessage)
                        }
                    }

                    data.formData = formData
                    
                    response = await this['profile/sendChatAnswer'](data)
                }

                if (response.status) {
                    if (!this.answering) {
                        this.chatMessages.push(response.chatMessage)
                        this.moveConversationUp(response.chatMessage.conversationId)
                    } else {
                        this.moveQuestionDown(response.chatQuestion)
                        this.moveConversationUp(response.chatQuestion.questionableId)
                        this.clickedClearAnswer()
                    }
                    this.chatMessage = ''
                    if (this.computedShowRequestResponse) {
                        this.clickedMessageResponse('accept')
                    }
                } else {
                    console.log(response);
                }
                this.messageSending = false
                this.scrollDown()
            },
            async getChatMessages(){
                if (this.chatMessagesNextPage === null) {
                    return
                }
                this.messagesLoading = true
                let response = null,
                    data = {}

                data.nextPage = this.chatMessagesNextPage
                data.conversationId = this.conversationId

                response = await this['profile/getChatMessages'](data)

                if (response.status) {
                    let responseArray = _.reverse(response.data)
                    if (this.chatMessagesNextPage === 1) {
                        this.chatMessages.push(...responseArray)
                        this.scrollDown()
                    } else {
                        this.chatMessages.unshift(...responseArray)
                    }
                    if (response.next) {
                        this.chatMessagesNextPage +=1
                    } else {
                        this.chatMessagesNextPage = null
                    }
                } else {
                    console.log(response);
                }
                this.messagesLoading = false
            },
            async getChatConversations(){
                if (this.conversationsNextPage === null) {
                    return
                }
                this.conversationsLoading = true
                let response = null,
                    data = {}

                data.nextPage = this.conversationsNextPage

                response = await this['profile/getChatConversations'](data)

                if (response.status) {
                    this.conversations.push(...response.data)
                    if (response.next) {
                        this.conversationsNextPage += 1
                    } else {
                        this.conversationsNextPage = null
                    }
                } else {
                    console.log(response);
                }
                this.conversationsLoading = false
            },
            async getBlockedConversations(){
                if (this.blockedConversationsNextPage === null) {
                    return
                }
                this.conversationsLoading = true
                let response,
                    data = {
                        nextPage: this.blockedConversationsNextPage
                    }

                response = await this['profile/getBlockedConversations'](data)

                if (response.status) {
                    this.blockedConversations.push(...response.data)
                    if (response.next) {
                        this.blockedConversationsNextPage += 1
                    } else {
                        this.blockedConversationsNextPage = null
                    }
                } else {
                    console.log(response);
                }
                this.conversationsLoading = false
            },
            async getPendingConversations(){
                if (this.pendingConversationsNextPage === null) {
                    return
                }
                this.conversationsLoading = true
                let response,
                    data = {
                        nextPage: this.pendingConversationsNextPage
                    }

                response = await this['profile/getPendingConversations'](data)

                if (response.status) {
                    this.pendingConversations.push(...response.data)
                    if (response.next) {
                        this.pendingConversationsNextPage += 1
                    } else {
                        this.pendingConversationsNextPage = null
                    }
                } else {
                    console.log(response);
                }
                this.conversationsLoading = false
            },
            async infiniteHandler($state){
                if (this.conversationType === 'normal') {
                    if (this.conversationsNextPage === 1) {
                        return
                    } else if ( this.conversationsNextPage === null) {
                        $state.complete()
                        return
                    }
                } else if (this.conversationType === 'blocked') {
                    if (this.blockedConversationsNextPage === 1) {
                        return
                    } else if ( this.blockedConversationsNextPage === null) {
                        $state.complete()
                        return
                    }
                } else if (this.conversationType === 'pending') {
                    if (this.pendingConversationsNextPage === 1) {
                        return
                    } else if ( this.pendingConversationsNextPage === null) {
                        $state.complete()
                        return
                    }
                }

                let response,
                    data = {
                        nextPage: this.conversationType === 'normal' ? this.conversationsNextPage
                            : this.conversationType === 'blocked' ? this.blockedConversationsNextPage
                            : this.conversationType === 'pending' ? this.pendingConversationsNextPage
                            : null
                    }

                if (this.conversationType === 'normal') {
                    response === await this['profile/getChatConversations'](data)
                } else if (this.conversationType === 'blocked') {
                    response === await this['profile/getBlockedConversations'](data)
                } else if (this.conversationType === 'pending') {
                    response === await this['profile/getPendingConversations'](data)
                }

                let next = response.next
                if (response.status) {
                    if (this.conversationType === 'normal') {
                        this.conversations.push(...response.data)
                        this.conversationsNextPage = next ? 
                            this.conversationsNextPage + 1 : null
                    } else if (this.conversationType === 'blocked') {
                        this.blockedConversations.push(...response.data)
                        this.blockedConversationsNextPage = next ? 
                            this.blockedConversationsNextPage + 1 : null
                    } else if (this.conversationType === 'pending') {
                        this.pendingConversations.push(...response.data)
                        this.pendingConversationsNextPage = next ? 
                            this.pendingConversationsNextPage + 1 : null
                    }
                }

                if (next) {
                    $state.loaded()
                } else {
                    $state.complete()
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .chat-top-section{
        padding: 5px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        
        .icon-ellipsis{
            font-size: 14px;
            font-weight: 700;
            padding: 5px;
            cursor: pointer;
        }

        .iconEllipsisRed{
            color: red;
        }

        .message-options{
            position: absolute;
            right: 5%;
            top: 0;
            z-index: 1;
            margin: 10px;
            text-align: center;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 0 2px grey;

            .option{
                font-size: 14px;
                padding: 5px;
                cursor: pointer;

                &:hover{
                    box-shadow: 0 0 1px grey;
                }

                sup{
                    color: red;
                    padding: 1px;
                    border-radius: 50%;
                    border: 1px solid red;
                }
            }
        }

        .top-section-item{
            width: 30%;
            text-align: center;
            padding: 5px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
            @include text-overflow();

            &:hover{
                box-shadow: 0 0 2px gray;
                transition: box-shadow .5s ease;
            }
        }

        .accounts-section{
            width: 100%;
            display: inline-flex;
            justify-content: space-around;
            font-size: 12px;
            // padding: 5px;
            align-items: center;

            .account-section-item{
                padding: 5px;
                border-radius: 5px;
                cursor: pointer;

                &:hover{
                    box-shadow: 0 0 2px gray;
                    background: silver;
                    transition: all .5s ease-in;
                }
            }

            .accountSectionActive{
                box-shadow: 0 0 2px gray;
                background: silver;
                transition: all .5s ease-in;
            }
        }

        .message-account-section{
            display: inline-flex;
            width: 100%;
            justify-content: space-between;
            position: relative;

            .profile-picture{
                height: 40px;
                width: 40px;
            }

            .middle{
                width: 50%;
                padding: 5px;
                position: relative;

                .online-alert{
                    position: absolute;
                    bottom: 80%;
                    left: -10px;
                    background: green;
                    width: 12px;
                    height: 12px;
                    border-radius: 10px;
                    border: 2px solid aquamarine;
                }

                .name{
                    font-size: 12px;
                    width: 100%;
                    text-transform: capitalize;
                    font-weight: 500;
                    @include text-overflow()
                }

                .account{
                    font-size: 10px;
                    width: 100%;
                }

                .typing{
                    font-size: 10px;
                    font-weight: 700;
                    color: green;
                }
            }

            .icon{
                font-size: 14px;
                font-weight: 700;
                padding: 5px;
                cursor: pointer;
            }
            
        }

        .active{
            box-shadow: 0 0 2px gray;
            background-color: whitesmoke;
            transition: all .5s ease;
        }
    }

    .chat-middle-section{
        padding: 10px;
        border-top: 1px solid gray;
        border-bottom: 1px solid gray;
        display: flex;
        height: 55vh;
        justify-content: center;

        .chat-accounts-section,
        .chat-profile-section,
        .chat-conversations-section,
        .chat-messages-section{
            text-align: center;
            height: 100%;
            overflow-y: auto;
            padding: 10px;
            width: 90%;

            .loading{
                width: 100%;
                text-align: center;
            }
        }

        .chat-accounts-section{

            .status-sections{
                width: 100%;
                display: inline-flex;
                justify-content: space-between;
                align-items: center;

                .status-button{
                    font-size: 12px;
                    font-weight: 500;
                    padding: 5px;
                    color: gray;
                    margin-bottom: 5px;
                    width: 45%;
                    cursor: pointer;

                    &:hover{
                        border-bottom: 1px solid rgb(192,192,192);
                        transition: all .3s ease-in;
                    }
                }

                .statusActive{
                    border-bottom: 1px solid rgb(192,192,192);
                    transition: all .3s ease-in;
                }
            }
            
            .other-user{
                margin: 0 0 10px;
            }
        }

        .chat-conversations-section{

            .conversation-badge{
                margin: 5px auto;
                cursor: pointer;
            }

            .no-conversations{
                color: gray;

                .add-message{
                    font-size: 14px;
                }
            }
        }

        .chat-messages-section{
            position: relative;
            width: 100%;

            .conversation-creation{
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .message-badge{
                margin: 0 auto 30px;
            }

            .more-messages{
                width: fit-content;
                font-size: 20px;
                color: gray;
                font-weight: 500;
                margin: 0 auto;
                text-align: center;
                cursor: pointer;
            }

            .no-messages{
                color: gray;

                .add-message{
                    font-size: 14px;
                }
            }

            .message-sending{
                width: fit-content;
                margin: 10px 0 0;
                position: absolute;
                bottom: 0;
                right: 10px;
            }

            .chat-request-response{
                width: 100%;
                display: inline-flex;
                justify-content: space-around;
                align-items: center;
                padding: 5px;
                position: absolute;
                left: 0;

                .accept,
                .decline{
                    padding: 5px;
                    font-size: 14px;
                    border-radius: 10px;
                    cursor: pointer;
                    color: white;

                    &:hover{
                        box-shadow: 0 0 2px gray;
                    }
                }

                .accept{
                    background: green;
                }

                .decline{
                    background: rgba(255,0,0,.8);
                }
            }
        }

        .chat-profile-section{
            width: auto;

            .user-account-item{
                max-width: 250px;
                min-height: 70px;
                margin-right: 10px;
            }
        }
        
    }

    .noBorderBottom{
        border-bottom: none;
    }

    .chat-bottom-section{
        
    }

@media screen and (max-width: 1100px) and (min-width: 800px){

    .chat-middle-section{
        height: 65vh;
    }
}

@media screen and (max-width: 800px){

    .chat-middle-section{
        height: 75vh;
    }
}

@media screen and (max-width: 300){

    .chat-middle-section{
        
        .chat-accounts-section,
        .chat-profile-section,
        .chat-conversations-sectionv,
        .chat-messages-section{
            width: 100%;
        }
    }
}
</style>