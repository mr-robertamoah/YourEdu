<template>
    <div 
        class="discussion-single-wrapper min-h-90vh flex flex-col relative"
    >
        <item-view-cover
            class="min-h-90vh"
            v-if="steps === 0"
            :data="computedCoverData"
            :transparent="flagData.isFlagged"
            :additionalText="discussion.type"
        >
            <template slot="buttons">
                <special-button 
                    buttonText="view discussion"
                    class="p-1 ml-5"
                    @click="steps = 1"
                    v-if="discussion.type === 'PUBLIC'"
                ></special-button>
                <special-button 
                    buttonText="join"
                    class="p-1 ml-5"
                    @click="clickedJoin"
                    v-if="computedShowCoverJoin"
                ></special-button>
            </template>
        </item-view-cover>
        <div v-if="steps" class="top flex-shrink-0">
            <div class="discussion-type">{{discussion.type}}</div>
            <div class="restriction" v-if="computedRestriction.length">
                {{computedRestriction}}
            </div>
        </div>
        <div 
            class="bottom flex flex-col h-full flex-1" 
            :class="[steps === 0 ? 'border-0' : '']"
            v-if="computedShowReaction"
        >
            <template v-if="steps">
                <div class="edit"
                    @click="clickedEditIcon"
                    v-if="computedUserParticipant"
                >
                    <font-awesome-icon
                        :icon="['fa','chevron-down']"
                    ></font-awesome-icon>
                </div>
                <div class="options" v-if="showOptions">
                    <optional-actions
                        :show="showOptions"
                        :hasSave="!computedIsOwner"
                        :isSaved="saveData.isSaved"
                        :hasExtra="computedIsOwner && discussion.type === 'PRIVATE'"
                        extraText="requests"
                        :hasEdit="computedIsOwner"
                        :hasAttachment="computedUserParticipant"
                        :hasDelete="computedIsOwner"
                        @clickedOption="clickedOption"
                    >
                        <template slot="extraicon">
                            <font-awesome-icon :icon="['fa','info-circle']"></font-awesome-icon>
                        </template>
                    </optional-actions>
                </div>
                <div class="alert w-full">
                    <auto-alert
                        :message="alertMessage"
                        :success="alertSuccess"
                        :danger="alertDanger"
                        :sticky="true"
                        @hideAlert="clearAlert"
                    ></auto-alert>
                </div>
                <div class="loading" v-if="loading && !showDiscussionEdit">
                    <pulse-loader 
                        :loading="loading" 
                        :size="'10px'"
                    ></pulse-loader>
                </div>
                <div class="post-attachment h-content"
                    @click.self="attachmentData.showAttach = false"
                >
                    <post-attachment
                        :show="attachmentData.showAttach"
                        :isAttached="attachmentData.isAttached"
                        :attachmentsNumber="attachmentData.postAttachments.length"
                        :attachments="attachmentData.myAttachments"
                        @itemClicked="attachmentClicked"
                        @clickedUnattach="clickedUnattach"
                    ></post-attachment>
                </div>
                <div class="first flex-grow-0 flex-shrink-0" v-if="messageSectionState !=='max'">
                    <div class="creator-info">
                        <div class="started">started by</div>
                        <profile-picture
                            class="profile-picture mr-1"
                        >
                            <template slot="image">
                                <img :src="discussion.raisedby.url" >
                            </template>
                        </profile-picture>
                        <div class="name">{{discussion.raisedby.name}}</div>
                        <div class="buttons">
                            <post-button
                                buttonText="invite"
                                v-if="computedIsOwner"
                                @click="clickedPostButton"
                            ></post-button>
                            <post-button
                                buttonText="join"
                                v-if="computedJoin"
                                @click="clickedPostButton"
                                class="z-10"
                            ></post-button>
                            <post-button
                                buttonText="leave"
                                v-if="!computedIsOwner && computedUserParticipant && !computedBanned"
                                @click="clickedPostButton"
                            ></post-button>
                            <div class="message" v-if="computedPendingParticipant">
                                your request is pending
                            </div>
                        </div>
                    </div>
                    <div class="discussion-info">
                        <div class="title">{{discussion.title}}</div>
                    </div>
                </div>
                <div class="second flex-grow-0 flex-shrink-0" v-if="messageSectionState !=='max'">
                    <div class="attachments-section">
                        <attachment-badge 
                            v-for="(attachment,index) in attachmentData.postAttachments"
                            :key="index"
                            :hasClose="false"
                            :attachment="attachment.data"
                            :type="attachment.type"
                        ></attachment-badge>
                    </div>
                    <div class="resources-section">
                        <template v-if="computedResources.length">
                            <div class="resource"
                                v-for="(resource, index) in computedResources"
                                :key="index"
                                @dblclick="clickedDiscussionMedia(resource)"
                            >
                                <img :src="resource.url" 
                                    v-if="resource.type === 'image'">
                                <video :src="resource.url" controls 
                                    v-if="resource.type === 'video'"></video>
                                <audio :src="resource.url" controls
                                    v-if="resource.type === 'audio'"></audio>
                            </div>
                        </template>
                        <div class="no-resources" v-else>
                            no discussion resources
                        </div>
                    </div>
                </div>
                <div class="flex flex-col flex-grow third w-full"
                    :class="[messageSectionState === 'max' ? 'z-50 h-full absolute top-0' : '']"
                >
                    <div class="admin-section flex-shrink-0" v-if="computedIsOwner && discussion.restricted">
                        <div class="admin-button"
                            @click="clickedAdminButton('all')"
                            :class="{active:adminButtonText === 'all'}"
                        >all</div>
                        <div class="admin-button"
                            @click="clickedAdminButton('accepted')"
                            :class="{active:adminButtonText === 'accepted'}"
                        >accepted</div>
                        <div class="admin-button"
                            @click="clickedAdminButton('declined')"
                            :class="{active:adminButtonText === 'declined'}"
                        >declined</div>
                        <div class="admin-button"
                            @click="clickedAdminButton('pending')"
                            :class="{active:adminButtonText === 'pending'}"
                        >pending</div>
                    </div>
                    <div class="preamble flex-shrink-0" 
                        v-if="discussion.preamble.length"
                        @click.self="clickedDiscussionInfo('view')"
                    >
                        {{discussion.preamble}}
                        <div class="toggle" 
                            @click="clickedMessageSectionToggle"
                            v-if="messages.length > 2"
                        >
                            <font-awesome-icon :icon="['fa','chevron-down']"
                                v-if="messageSectionState === 'max'"
                            ></font-awesome-icon>
                            <font-awesome-icon :icon="['fa','chevron-up']"
                                v-if="messageSectionState === 'min'"
                            ></font-awesome-icon>
                        </div>
                    </div>
                    <div class="discussion-section h-full w-full">
                        <div class="main-area h-full w-full" ref="mainarea">
                            <div class="unseen-messages"
                                v-if="unseenMessagesNumber && showUnseenMessages"
                                @click="clickedUnseenMessages"
                                @scroll="scrollingMainArea"
                            >
                                0
                            </div>
                            <div class="no-discussions" 
                                v-if="!messages.length && !messagesGetting && !messageSending"
                            >
                                no discussions yet
                            </div>
                            <discussion-badge
                                v-for="message in messages"
                                :key="message.id"
                                :message="message"
                                @clickedOption="clickedDiscussionOption"
                                @clickedAction="clickedDiscussionAction"
                                :admin="computedAdmin"
                                :adminText="adminButtonText"
                            ></discussion-badge>
                            <div class="show-discussions"
                                @click="getMessages"
                                v-if="computedShowDiscussions"
                            >
                                show discussions
                            </div>
                            <Infinite-Loading
                                @infinite="infiniteHandler"
                                v-if="showInfiniteLoader"
                            ></Infinite-Loading>
                            <fade-up>
                                <template slot="transition" v-if="messageSending">
                                    <div class="loading">
                                        <pulse-loader 
                                            :loading="messageSending" 
                                            :size="'10px'"
                                        ></pulse-loader>
                                    </div>
                                </template>
                            </fade-up>
                        </div>
                        <div class="text-area" v-if="computedUserParticipant">
                            <discussion-textarea
                                v-model="discussionText"
                                @sendMessage="sendDiscussionMessage"
                                @fileChange="discussionFileChange"
                                :blocked="computedBlocked"
                            ></discussion-textarea>
                        </div>
                    </div>
                </div>
            </template>
            <div class="forth flex-grow-0 flex-shrink-0">
                <reaction-component
                    :comments="computedComments"
                    :item="computedItem"
                    :isOwner="computedIsOwner"
                    :full="discussionFull"
                    :showAddComment="showAddComment"
                    :showFlagReason="showFlagReason"
                    :flagData="flagData"
                    :likeData="likeData"
                    :classes="computedClasses"
                    :showProfilesText="showProfilesText"
                    :showOnlyProfiles="computedClasses.length ? true : false"
                    :showProfiles="showProfiles"
                    :profiles="computedProfiles"
                    @hideAddComment="showAddComment = false"
                    @postAddComplete="postAddComplete"
                    @askLoginRegister="askLoginRegister"
                    @clickedMedia="clickedMedia"
                    @clickedProfile="clickedProfile"
                    @clickedLike="clickedLike"
                    @clickedAddComment="clickedAddComment"
                    @cancelFlagProcess="cancelFlagProcess"
                    @reasonGiven="reasonGiven"
                    @clickedFlag="clickedFlag"
                    @continueFlagProcess="continueFlagProcess"
                    @clickedShowPostComments="clickedShowPostComments"
                    @updateParticpantState="updateParticpantState"
                ></reaction-component>
            </div>
        </div>
       <!--  media modal -->
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
       <!--  info section -->
        <discussion-single-info
            :discussion="discussion"
            :participantsLoading="participantsLoading"
            :otherUserAccountLoading="otherUserAccountLoading"
            :participants="participants"
            :activeFile="activeFile"
            :loading="loading"
            :owner="computedIsOwner"
            :admin="computedAdmin"
            :participant="computedUserParticipant"
            :files="computedResources"
            :showFilePreview="showFilePreview"
            :note="computedParticipationNote"
            :show="showDiscussionInfo"
            :showEdit="showDiscussionEdit"
            :nextPage="participantsNextPage"
            @updateNextPage="updateNextPage"
            @clickedEdit="clickedEdit"
            @clickedEditFile="clickedEditFile"
            @getDiscussionParticipants="getDiscussionParticipants"
            @clickedLeaveRemoveParticipant="clickedLeaveRemoveParticipant"
        ></discussion-single-info>
       <!--  request section -->
        <item-request-section
            :show="showDiscussionRequest"
            :computedItem="computedItem"
            :allowed="discussion.allowed"
            :removedParticipant="removedParticipant"
            :loading="loading"
            @clickedParticpantAction="clickedParticpantAction"
            @doneRemovingParticipant="doneRemovingParticipant"
            @clickedCloseRequest="showDiscussionRequest = false"
        ></item-request-section>
        
        <!-- media capture -->
        <media-capture
            v-if="showMediaCapture"
            :show="showMediaCapture"
            :type="mediaCaptureType"
            @closeMediaCapture="closeMediaCapture"
            @sendFile="receiveMediaCapture"
        ></media-capture>

        <!-- small modal for alerts -->
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    :title="smallModalMessage"
                    :show="showSmallModal"
                    :message="alertMessage"
                    :success="alertSuccess"
                    :danger="alertDanger"
                    :loading="loading"
                    :alerting="smallModalAlerting"
                    @disappear="clearSmallModal"
                >
                    <template slot="actions">
                        <post-button
                            buttonText="ok"
                            @click="clickedSmallModalButton"
                            v-if="smallModalInfo"
                        ></post-button>
                        <post-button
                            buttonText="yes"
                            @click="clickedSmallModalButton"
                            buttonStyle='danger'
                            v-if="smallModalDelete"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedSmallModalButton"
                            buttonStyle='success'
                            v-if="smallModalDelete"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>

        <flag-cover 
            v-if="flagData.myFlag"
            :item="computedItem.item"
        ></flag-cover>
    </div>
</template>

<script>
import MediaModal from './MediaModal.vue';
import FadeUp from './transitions/FadeUp.vue'
import DiscussionBadge from './DiscussionBadge.vue';
import PostButton from './PostButton.vue';
import DiscussionTextarea from './DiscussionTextarea.vue';
import OptionalActions from './OptionalActions.vue';
import MediaCapture from './MediaCapture.vue';
import TextTextarea from './TextTextarea.vue';
import TextInput from './TextInput.vue';
import ProfilePicture from './profile/ProfilePicture.vue';
import SpecialButton from './SpecialButton.vue'
import DiscussionSingleInfo from './DiscussionSingleInfo.vue';
import ItemRequestSection from './ItemRequestSection.vue';
import ItemViewCover from './ItemViewCover.vue'
import Like from '../mixins/Like.mixin';
import Flag from '../mixins/Flag.mixin';
import Save from '../mixins/Save.mixin';
import Alert from '../mixins/Alert.mixin';
import Participation from '../mixins/Participation.mixin';
import Profiles from '../mixins/Profiles.mixin';
import SmallModal from '../mixins/SmallModal.mixin'
import Comments from '../mixins/Comments.mixin'
import Attachments from '../mixins/Attachments.mixin'
import { mapActions, mapGetters } from 'vuex';
import { strings } from '../services/helpers';
import { useRoute } from 'vue-router';
    export default {
        components: {
            ProfilePicture,
            FadeUp,
            MediaCapture,
            OptionalActions,
            DiscussionTextarea,
            TextInput,
            TextTextarea,
            PostButton,
            DiscussionBadge,
            MediaModal,
            SpecialButton,
            ItemViewCover,
            ItemRequestSection,
            DiscussionSingleInfo,
        },
        props: {
            discussion: {
                type: Object,
                default(){
                    return {}
                }
            },
            discussionFull: {
                type: Boolean,
                default: false,
            },
            disabled: { //when being viewed by admin as an activity
                type: Boolean,
                default: false
            },
            schoolAdmin: {
                type: Object,
                default(){
                    return null
                },
            },
        },
        mixins: [
            Like, Flag, Save, Alert, SmallModal, Comments, Participation,
            Profiles, Attachments
        ],
        data() {
            return {
                steps: 0,
                showMediaModal: false,
                mediaModalUrl: {},
                showDiscussionInfo: false,
                showDiscussionRequest: false,
                showOptions: false,
                loading: false,
                participantsLoading: false,
                otherUserAccountLoading: false,
                requestType: '',
                messageSectionState: 'min',
                adminButtonText: 'all',
                participants: [],
                participantsNextPage: 1,
                //messages
                messageNextPage: 1,
                messages: [],
                discussionFiles: [],
                discussionText: '',
                messageSending: false,
                messagesGetting: false,
                showInfiniteLoader: false,
                showUnseenMessages: false,
                unseenMessagesNumber: 0,
                //editing
                showDiscussionEdit: false,
                showMediaCapture: false,
                mediaCaptureType: '',
                fileNumberError: false,
                showFilePreview: false,
                activeFile: null,
            }
        },
        watch: {
            discussion: {
                immediate: true,
                handler(newValue, oldValue){
                    if (newValue.messages) {
                        this.messages = newValue.messages
                    }
                },
                deep: true
            },
            "discussion.participants"(newValue, oldValue) {
                if (oldValue && 
                    newValue.participants?.length >
                    oldValue.participants?.length) {
                    this.alertSuccess = true
                    this.alertMessage = 'a new participant just joined'
                }
            },
            computedItemable(newValue){
                if (newValue) {
                    
                    this.setMyFlag()
                }
            },
            showUnseenMessages(newValue){
                if (newValue) {
                    setTimeout(() => {
                        this.showUnseenMessages = false
                    }, 5000);
                }
            },
            messageNextPage(newValue){
                if (newValue && newValue > 2) {
                    this.showInfiniteLoader = true
                } else {
                    this.showInfiniteLoader = false
                }
            },
            "discussion.likes": {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        
                        this.likeData.likes = newValue.length
                    }
                }
            },
            "discussion.saves": {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        
                        this.saveData.saves = newValue.length
                    }
                }
            },
        },
        mounted(){
            this.setMyFlag()
            this.setMyLike()
            this.setMySave()
            this.setMyAttachment()
            this.listen()
            this.listenForComments()
            this.listenForLikes()
            this.listenForFlags()
            this.listenForSaves()
            this.listenForAttachments()
            this.listenForParticipation()
        },
        computed: {
            ...mapGetters(['getUser',]),
            computedRestriction() {
                return this.discussion.restricted ? 'restricted participation' : ''
            },
            computedRestricted() {
                return !this.computedParticipant ? false : 
                    this.computedParticipant.state === 'RESTRICTED' || 
                    this.computedParticipant.state === 'BANNED'
            },
            computedBanned() {
                return !this.computedParticipant ? false : 
                    this.computedParticipant.state === 'BANNED'
            },
            computedResources(){
                let resources = []

                if (this.discussion.images) {
                    
                    this.discussion.images.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            id: resource.id,
                            type: 'image'
                        })
                    })
                }
                if (this.discussion.videos) {
                    
                    this.discussion.videos.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            id: resource.id,
                            type: 'video'
                        })
                    })
                }
                if (this.discussion.audios) {
                    
                    this.discussion.audios.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            id: resource.id,
                            type: 'audio'
                        })
                    })
                }
                return resources
            },
            computedClasses(){
                let classes = ''

                if (this.showProfilesAction === 'save' || 
                    this.showProfilesAction === 'attach' || 
                    this.showProfilesAction === 'join') {
                    classes += 'absolute top-5 left-2/3 -ml-1/4 top-10 max-w-content'
                }
                
                if (this.steps === 0) {
                    classes += ' bottom-8'
                }

                return classes
            },
            computedAdmin(){
                return this.computedIsOwner || (this.computedUserParticipant && 
                    this.discussion.participants.findIndex(participant=>{
                        return participant.userId === this.getUser.id && 
                            participant.state === 'ADMIN'
                    }) > -1)
            },
            computedShowDiscussions(){
                return this.computedMessagesCount > 2 &&
                    this.messages.length <= 2 && !this.messagesGetting
            },
            computedCanJoin(){
                if (this.discussion.allowed !== 'ALL') {
                    return this.computedProfiles.filter(profile=>{
                        return profile.account == this.discussion.allowed
                    }).map(profile=>{
                        return {
                            account: profile.account,
                            accountId: profile.accountId
                        }
                    })
                }
                return {}
            },
            computedMessagesCount(){
                return this.discussion.messages_count
            },
            computedBlocked(){
                return this.getUser && this.computedRestricted ? true : false
            }, //add ability to add other admins feature
            computedParticipationNote(){
                return this.computedIsOwner ? 'you are the owner and admin of this discussion' :
                    this.computedUserParticipant ? 'you are a participant in this discussion' :
                    this.discussion.allowed === 'ALL' && this.computedProfiles ? 'you can join this discussion' :
                    this.computedCanJoin.account ? 'you can join this discussion' :
                    this.computedProfiles && !this.computedCanJoin.account ? 
                        'you cannot join this discussion because you do not have an allowable account': ''
            },
            computedIsOwner(){
                return this.getUser && this.discussion.raisedby.userId === this.getUser.id
            },
            computedOwner(){
                return this.discussion.raisedby
            },
            computedParticipant(){
                if (!this.getUser) {
                    return null
                }
                let index = this.discussion.participants.findIndex(participant=>{
                    return participant.userId === this.getUser.id
                })
                if (index > -1) {
                    return this.discussion.participants[index]
                }
                return null
            },
            computedUserParticipant(){
                return this.computedIsOwner || (this.computedParticipant && 
                    this.computedParticipant.id) ? true : false
            },
            computedJoin(){
                return !this.computedPendingParticipant && !this.computedIsOwner && 
                    !this.computedUserParticipant && this.computedCanJoin && 
                    this.discussion.type === 'PUBLIC'
            },
            computedItem(){
                return {
                    item: 'discussion',
                    itemId: this.discussion.id
                }
            },
            computedItemable() {
                return this.discussion
            },
            computedCoverData() {
                return {
                    name: this.discussion.title,
                    description: this.discussion.preamble ? this.discussion.preamble : '',
                    type: 'discussion'
                }
            },
            computedShowReaction() {
                return this.flagData.isFlagged ? this.flagData.isFlagged :
                    this.computedShowCoverJoin ? computedShowCoverJoin :
                    this.steps
            },
            computedShowCoverJoin() {
                return this.discussion.type !== 'PUBLIC' && this.computedJoin
            },
        },
        methods: {
            ...mapActions([
                'profile/getDiscussionMessages','profile/sendDiscussionMessage',
                'profile/updateDiscussion',
                'profile/deleteDiscussion','profile/deleteDiscussionMessage',
                'profile/discusionContributionResponse',
                'profile/getDiscussionParticipants','profile/updateParticpantState',
                'profile/deleteDiscussionParticipant'
            ]),
            listen(){
                Echo.channel(`youredu.discussion.${this.discussion.id}`)
                    .listen('.newDiscussionMessage', data=>{
                        console.log('data :>> ', data);
                        if (this.discussion.restricted) {
                            if (this.computedIsOwner && this.adminButtonText === 'pending') {
                                this.messages.unshift(data.message)
                            } else {
                                this.alertSuccess = true
                                this.alertMessage = 'a new contribution has been sent'
                            }
                        } else  this.messages.unshift(data.message)
                    })
                    .listen('.newDiscussionMessageResponse', data=>{
                        console.log('data :>> ', data);
                        if (this.discussion.restricted) {
                            if (this.computedAdmin) {
                                if ((this.adminButtonText === 'all' ||
                                    this.adminButtonText === 'accepted') &&
                                    data.message.state === 'ACCEPTED') {
                                    this.alertSuccess = true
                                    this.messages.unshift(data.message)
                                } else if (this.adminButtonText === 'rejected' &&
                                    data.message.state === 'REJECTED'){
                                    this.messages.unshift(data.message)
                                    this.alertDanger = true
                                }
                                this.alertMessage = `a new contribution has been ${data.message.state.toLowerCase()}`
                                return
                            } else if (data.message.state === "ACCEPT") {

                                this.alertSuccess = true
                                this.alertMessage = 'a new contribution has been sent'
                                this.messages.unshift(data.message)
                            }
                        }
                    })
                    .listen('.deleteDiscussionMessage', data=>{
                        console.log('data :>> ', data);
                        this.spliceMessage(data.messageId)
                    })
            },
            clickedMessageSectionToggle(){
                this.messageSectionState = this.messageSectionState === 'max' ?
                    'min' : 'max'
            },
            async clickedDiscussionAction(messageData){
                let response,
                    data = {
                        userId: messageData.message.userId,
                        messageId: messageData.message.id,
                        action: messageData.action
                    }
                
                response = await this['profile/discusionContributionResponse'](data)

                if (response.status) {
                    if ((this.adminButtonText === 'all' && data.action === 'accepted' &&
                        messageData.message.state === 'ACCEPTED') ||
                        (this.adminButtonText === messageData.message.state.toLowerCase())) {
                        this.spliceMessage(messageData.message.id)
                    }
                    if ((this.adminButtonText === 'all' && data.action === 'accepted' &&
                        response.discussionMessage.state === 'ACCEPTED') ||
                        (this.adminButtonText === response.discussionMessage.state.toLowerCase())) {
                        this.unshiftMessage(response.discussionMessage,messageData.adminText)
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            unshiftMessage(message,adminText){
                this.messages.unshift(message)
            },
            spliceMessage(messageId){
                console.log('messageId :>> ', messageId);
                let index = this.messages.findIndex(message=>{
                    return message.id == messageId
                })
                console.log('index :>> ', index);
                if (index > -1) {
                    this.messages.splice(index,1)
                }
            },
            clickedDiscussionOption(data){
                this.deleteMessage(data)
            },
            async deleteMessage(data){
                let response

                response = await this['profile/deleteDiscussionMessage']({
                    messageId: data.message.id,
                    discussionId: this.discussion.id,
                    action: data.action === 'delete' ? data.action : 'self'
                })

                if (response.status) {
                    if (data.action === 'delete') {
                        this.spliceMessage(data.message.id)
                    } else {
                        this.replaceMessage(response.discussionMessage)
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            replaceMessage(discussionMessage){
                let index = this.messages.findIndex(message=>{
                    return message.id === discussionMessage.id
                })
                if (index > -1) {
                    this.messages.splice(index,1,discussionMessage)
                }
            },
            clickedSmallModalButton(data){
                if (data === 'yes') {
                    if (this.showProfilesAction === 'flag') {
                        this.flag(this.smallModalData)
                    } else if (this.showProfilesAction === 'delete') {
                        this.deleteDiscussion()
                    } else if (this.showProfilesAction === 'participant') {
                        this.deleteDiscussionParticipant(this.smallModalData)
                    }
                } else if (data === 'no') {
                    this.otherUserAccountLoading = false //incase this is for leaving or removing participants
                    this.clearSmallModal()
                } else if (data === 'ok') {
                    
                }
            },
            clickedEdit(data){
                if (data.type === 'cancel') {
                    this.showDiscussionInfo = false
                    this.showDiscussionEdit = false
                    return
                }
                
                if (data.type !== 'edit') {
                    return
                }

                if (! this.data.title.length) {
                    this.alertDanger = true
                    this.alertMessage = 'a title is needed'
                    return
                }

                this.updateDiscussion(data.data)
            },
            async deleteDiscussion(){
                const route = useRoute()
                
                this.loading = true
                    data = {where: route.name}

                let response = await this['profile/deleteDiscussion']({
                    discussionId: this.discussion.id,data})

                this.loading = false
                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = 'deletion was successful'
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = response.response.data.message
                }
                this.smallModalAlerting = true
            },
            async updateDiscussion(){
                let response,
                    formData = new FormData,
                    data = {}

                this.loading = true

                if (this.computedIsOwner) {
                    formData.append('account', strings.getAccount(this.discussion.raisedby.account))
                    formData.append('accountId', this.discussion.raisedby.accountId)
                }
                formData.append('title', data.title)
                formData.append('type', data.type)
                formData.append('restricted', JSON.stringify(data.restricted))
                formData.append('allowed', data.allowed)
                formData.append('preamble', data.preamble)
                this.files.forEach(file=>{
                    formData.append('files[]', file)
                })
                formData.append('deletedFiles', JSON.stringify(data.deletedFiles))
                data.discussionId = this.discussion.id
                data.formData = formData
                data.where = useRoute().name

                response = await this['profile/updateDiscussion'](data)

                this.loading = false
                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = 'update was successful'
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = 'update was unsuccessful'
                }
                setTimeout(() => {
                    this.showDiscussionEdit = false
                    this.showDiscussionInfo = false
                }, 3000);
            },
            updateNextPage(page) {
                this.participantsNextPage = page
            },
            clickedEditAction(data){
                if (data === 'video') {
                    this.mediaCaptureType = 'video'
                    this.showMediaCapture = true
                } else if (data === 'camera') {
                    this.mediaCaptureType = 'image'
                    this.showMediaCapture = true
                } else if (data === 'microphone') {
                    this.mediaCaptureType = 'audio'
                    this.showMediaCapture = true
                } else if (data === 'upload') {
                    this.$refs.inputfile.value = ''
                    this.$refs.inputfile.click()
                }
            },
            toLowercase(data){
                return data.toLowerCase()
            },
            closeMediaCapture(){
                this.showMediaCapture = false
            },
            receiveMediaCapture(file){
                let time = new Date().getTime(),
                    fileName
                if (file.type.includes('image')) {
                    fileName = `my_picture${time}.png`
                    this.activeFile = this.imageFile = new File([file],fileName,{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('video')) {
                    fileName = `my_video${time}.webm`
                    this.activeFile = this.videoFile = new File([file],fileName,{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('audio')) {
                    fileName = `my_audio${time}.mp3`
                    this.activeFile = this.audioFile = new File([file],fileName,{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
                this.showFilePreview = true
            },
            scrollingMainArea(){
                console.log('mainarea :>> ', this.$refs.mainarea.scrollTop);
            },
            clickedUnseenMessages(){
                this.showUnseenMessages = false,
                this.unseenMessagesNumber = 0
            },
            clickedEditIcon(){
                if (this.disabled) {
                    return
                }
                if(this.computedBanned) return
                this.showOptions = !this.showOptions
                if (this.showOptions) {
                    setTimeout(() => {
                        this.showOptions = false
                    }, 4000);
                }
                this.showAttach = false
            },
            clickedOption(data){
                this.showOptions = false
                if (data === 'edit') {
                    this.showDiscussionInfo = true
                    this.showDiscussionEdit = true
                    return
                }
                
                if (data === 'save') {
                    if (this.saveData.isSaved) {
                        this.save(null)
                        return
                    }
                    this.showProfilesText = 'save as'
                    this.showProfilesAction = 'save'
                    this.profilesAppear()
                    return
                }
                
                if (data === 'attach') {
                    this.showAttach = true
                    return
                }
                
                if (data === 'delete') {
                    this.showProfilesAction = 'delete'
                    this.issueSmallModalDeletionMessage()
                    return
                }
                
                if (data === 'requests') {
                    this.requestType = data
                    this.showDiscussionRequest = true
                }
            }, 
            clickedUnattach(attachment){
                this.showAttach = false
                this.selectedAttachment = attachment
                this.attach(null)
            },
            attachmentClicked(data){
                this.showAttach = false
                this.attachable = data
                this.showProfilesAction = 'attach'
                this.showProfilesText = 'attach as'
                this.profilesAppear()
            },
            async attach(who){

                this.loading = true
                let data = {},
                    response = null,
                    state = ''

                data.where = useRoute().name
                data.item = 'discussion'
                data.itemId = this.discussion.id
                if (who) {
                    state = 'attachment'
                    data.attachable = this.attachable.item
                    data.attachableId = this.attachable.itemId
                    data.account = who.account
                    data.accountId = who.accountId
                    data.note = this.attachable.note
                    response = await this['profile/createAttachment'](data)
                } else {
                    state = 'unattachment'
                    data.attachmentId = this.selectedAttachment.id
                    response = await this['profile/deleteAttachment'](data)
                }
                
                this.loading = false
                if (response.status) {
                    if (who) {
                        this.attachments += 1
                    } else {
                        this.attachments -= 1
                    }
                    this.alertSuccess = true
                    this.alertMessage = `${state} successful`
                } else {
                    this.alertDanger = true
                    this.alertMessage = `${state} unsuccessful`
                }
            },
            clickedAdminButton(data){
                if (this.adminButtonText !== data) {
                    this.messageNextPage = 1
                    this.getMessages(data)
                }
                this.adminButtonText = data
            },
            closeMediaModal() {
                this.showMediaModal = false
            },
            clickedPostButton(data){
                if (data === 'invite') {
                    this.requestType = data
                    this.showDiscussionRequest = true
                    return
                }
                
                if (data === 'join') {
                    this.clickedJoin()
                    return 
                }
                
                if (data === 'leave') {
                    this.showProfilesAction = 'participant'
                    this.clickedLeaveRemoveParticipant({action: data})
                }
            },
            clickedJoin() {
                if (this.discussion.allowed === 'ALL') {
                    this.showProfilesAction = 'join'
                    this.showProfilesText = 'join discussion as'
                    this.showProfiles = true
                    return
                }
                
                if (! this.getUser) {
                    this.askLoginRegister('discussion')
                    return
                }
                
                if (this.computedCanJoin.account) {
                    this.join(this.computedCanJoin)
                }
            },
            clickedLeaveRemoveParticipant(data){
                this.otherUserAccountLoading = true

                if (data.action === 'remove' || data.action === 'leave') {
                    this.showProfilesAction = 'participant'
                }
                
                let msg = 'are you sure you want to remove this participant from the discussion?'
                if (data.action === 'leave') {                    
                    msg = 'are you sure you want to leave this discussion?'
                }

                this.issueCustomMessageWithDeletion(msg)
            },
            askLoginRegister(data){
                this.$emit('askLoginRegister',data)
            },
            clickedDiscussionInfo(data){
                if(this.computedBanned) return
                if (data === 'view') {
                    if (this.showDiscussionInfo) {
                        this.showDiscussionEdit = false
                    }
                    this.showDiscussionInfo = !this.showDiscussionInfo
                } else if (data === 'edit') {
                    this.showDiscussionEdit = true
                }
            },
            clickedDiscussionMedia(resource){
                this.mediaModalUrl = resource
                this.showMediaModal = true
            },
            async sendDiscussionMessage(){
                this.messageSending = true
                let response,
                    formData = new FormData,
                    discussionId = this.discussion.id

                if (this.computedIsOwner) {
                    formData.append('account', this.discussion.raisedby.account)
                    formData.append('accountId', this.discussion.raisedby.accountId)
                } else {
                    formData.append('userId', this.getUser.id)
                }
                formData.append('message', this.discussionText)
                if (this.discussionFiles.length) {
                    this.discussionFiles.forEach(file=>{
                        formData.append('file[]', file)
                    })
                }
                if (this.discussion.restricted) {
                    if (this.computedAdmin) {
                        formData.append('state','ACCEPTED')
                    } else formData.append('state','PENDING')
                } else {
                    formData.append('state','ACCEPTED')
                }

                response = await this['profile/sendDiscussionMessage']({
                    discussionId, formData
                })

                this.messageSending = false
                if (response.status) {
                    if (this.discussion.restricted) {
                        this.alertSuccess = true
                        this.alertMessage = 'your message is pending'
                    } else this.messages.unshift(response.discussionMessage)
                    if (this.$refs.mainarea.scrollTop !== 0) {
                        this.showUnseenMessages = true
                        this.unseenMessagesNumber += 1
                    }
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = 'message sending failed'
                }
            },
            discussionFileChange(data){
                this.discussionText = data.caption.length ? data.caption.length : this.discussionText
                this.discussionFiles = data.files
            },
            async getMessages(data = null){
                let response,
                    discussionId = this.discussion.id,
                    nextPage = this.messageNextPage,
                    type = data ? data : this.adminButtonText

                this.messagesGetting = true
                response = await this['profile/getDiscussionMessages']({
                    discussionId, nextPage, type
                })

                if (response.status) {
                    this.messages = response.data
                    if (response.next) {
                        this.messageNextPage += 1
                        this.showInfiniteLoader = true
                    } else {
                        this.messageNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
                this.messagesGetting = false
            },
            async infiniteHandler($state){
                if (this.messageNextPage === 1) {
                    return
                }
                if (this.messageNextPage === null) {
                    $state.complete()
                    return
                }
                let response,
                    discussionId = this.discussion.id,
                    nextPage = this.messageNextPage,
                    type = this.adminButtonText

                this.messageSending = true
                response = await this['profile/getDiscussionMessages']({discussionId,nextPage})

                this.messageSending = false
                if (response.status) {
                    this.messages.push(...response.data)
                    if (response.next) {
                        this.messageNextPage += 1
                        $state.loaded()
                    } else {
                        this.messageNextPage = null
                        $state.complete()
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async getDiscussionParticipants(){
                this.participantsLoading = true

                let response,
                    data = {}

                data.nextPage = this.participantsNextPage
                data.discussionId = this.discussion.id
                
                response = await this['profile/getDiscussionParticipants'](data)

                this.participantsLoading = false
                if (response.status) {
                    this.participants = response.data
                    if (response.next) {
                        this.participantsNextPage +=1
                    } else {
                        this.participantsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async infiniteHandlerParticipants($state){
                if (this.participantsNextPage === 1) {
                    return
                }
                if (this.participantsNextPage === null) {
                    $state.complete()
                    return
                }
                this.participantsLoading = true
                data.nextPage = this.participantsNextPage
                data.discussionId = this.discussion.id
                
                response = await this['profile/getDiscussionParticipants'](data)

                this.participantsLoading = false
                if (response.status) {
                    this.participants.push(...response.data)
                    if (response.next) {
                        this.participantsNextPage +=1
                        $state.loaded()
                    } else {
                        this.participantsNextPage = null
                        $state.complete()
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async deleteDiscussionParticipant(deleteData){
                let response,
                    data = {
                        discussionId: this.discussion.id,
                        action: deleteData.action
                    }
                this.loading = true
                if (deleteData.action === 'leave') {
                    data.participantId = this.computedParticipant.id
                    data.userId = this.computedParticipant.userId
                } else {
                    data.participantId = deleteData.account.participantId
                    data.userId = deleteData.account.userId
                }

                response = await this['profile/deleteDiscussionParticipant'](data)

                this.loading = false
                this.otherUserAccountLoading = false
                if (response.status) {
                    this.alertSuccess = true
                    if (deleteData.action === 'leave') {
                        this.alertMessage = 'left successfully'
                    } else {
                        this.alertMessage = 'removed participant successfully'
                    }
                    this.removeParticipant(data.participantId)
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = response.response.data.message
                }
                this.smallModalAlerting = true
            },
            async updateParticpantState(updateData){
                this.otherUserAccountLoading = true

                let response,
                    data = {
                        discussionId: this.discussion.id,
                        participantId: updateData.account.participantId,
                        userId: updateData.account.userId,
                        action: updateData.action,
                    }

                response = await this['profile/updateParticpantState'](data)

                this.otherUserAccountLoading = false
                if (response.status) {
                    this.replaceParticipant(response.discussionParticipant)
                } else {
                    console.log('response :>> ', response);
                }
            },
            removeParticipant(participantId){
                let index = this.participants.findIndex(p=>{
                    return p.participantId === participantId
                })
                if (index > -1) {
                    this.participants.splice(index,1)
                }
            },
            replaceParticipant(participant){
                let index = this.participants.findIndex(p=>{
                    return p.participantId === participant.participantId
                })
                if (index > -1) {
                    this.participants.splice(index,1,participant)
                }
            },
            clickedShowPostComments(){
                this.$emit('clickedShowPostComments',{item: this.post, type:'discussion'})
            },
            postAddComplete(data){
                if (data === 'successful') {
                    this.showAddComment = false
                    this.alertSuccess = true
                    this.alertMessage = 'comment created successfully 😎'
                    return
                }

                this.alertDanger = true
                this.alertMessage = 'comment creation failed 😞'
            },
            clickedAddComment(){
                if (this.disabled) {
                    return
                }

                if(this.computedBanned) return

                if (!this.getUser) {
                    this.$emit('askLoginRegister','discussionsingle')
                    return
                }
                
                if (!this.getProfiles || !this.getProfiles.length) {
                    this.issueSmallModalInfoMessage({
                        message: 'you must have an account (eg. learner, parent, etc) before you can comment.'
                    })
                    
                    return
                }
                
                this.showAddComment = true
            },
            clickedProfile(data) {
                this.showProfiles = false
                if (this.showProfilesAction === 'join') {
                    this.join(data)
                    return
                }
                
                if (this.showProfilesAction === 'like') {
                    this.like(data)
                    return
                }
                
                if (this.showProfilesAction === 'save') {
                    this.save(data)
                    return
                }
                
                if (this.showProfilesAction === 'flag') {
                    this.issueCustomMessage({
                        message:'are you sure you want to flag this?',
                        data,
                        type: 'delete'
                    })
                    return
                }
                
                if (this.showProfilesAction === 'attach') {
                    this.attach(data)
                }
            },
            clickedEditFile(data){
                if (data.type === 'resource') {
                    this.mediaModalUrl = data.data
                    this.showMediaModal = true
                } else if (data.type === 'upload') {
                    this.showFilePreview = this.activeFile === data.data ? false : true
                    this.activeFile = data.data
                }
            },
            clickedMedia(data){

            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin info(){
    text-align: center;
    font-size: 12px;
    color: gray;
}

@mixin loading(){
    width: 100%;
    text-align: center;
    padding: 5px;
}

@mixin section(){
    width: 100%;
    color: gray;
    font-size: 12px;
    border-bottom: 1px solid gray;
    margin-top: 10px;
}

@mixin show-more(){
    padding: 5px;
    border-radius: 10px;
    margin: 5px auto;
    width: fit-content;
    background: white;
    font-size: 11px;
    box-shadow: 0 0 2px green;
    cursor: pointer;
    color: green;
    font-weight: 600;
}

@mixin button(){
    padding: 5px;
    width: fit-content;
    font-size: 14px;
    min-width: 35px;
    text-align: center;
    margin: 0 5px;
    border-radius: 10px;
    box-shadow: 0 0 2px grey;
    color: gray;
    cursor: pointer;
}

    .discussion-single-wrapper{
        position: relative;
        width: 100%;
        margin: 10px 0;
        background: inherit;

        .top{
            display: table;
            width: 100%;
            font-size: 11px;
            color: gray;

            .discussion-type{
                display: table-cell;
                width: 40%;
            }

            .restriction{
                width: 60%;
                display: table-cell;
                text-align: end;
                font-size: 10px;
                @include text-overflow();
            }
        }

        .bottom{
            border: 1px solid dimgrey;
            border-right: 2px solid dimgray;
            position: relative;
            background: inherit;

            .loading{
                @include loading();
            }

            .alert{
                position: relative;
            }

            .edit{
                font-size: 16px;
                margin-top: -5px;
                margin-right: 2px;
                cursor: pointer;
                text-align: end;
            }

            .options{ 
                z-index: 1;
                position: absolute;
                right: 0;
                top: 0;
            }

            .post-attachment{
                top: 0;
                width: 100%;
                position: absolute;
                z-index: 1;
            }

            .first{

                .creator-info{
                    position: relative;
                    width: 100%;
                    display: flex;
                    justify-content: flex-start;
                    align-items: center;
                    padding: 5px;

                    .started{
                        font-size: 10px;
                        margin-right: 5px;
                    }

                    .profile-picture{
                        width: 30px;
                        height: 30px;
                    }

                    .name{
                        font-size: 14px;
                    }

                    .buttons{
                        width: fit-content;
                        float: right;
                        margin-left: auto;

                        .message{
                            font-size: 11px;
                            color: gray;
                            text-align: end;
                        }
                    }
                }

                .discussion-info{
                    padding: 5px;
                    border-bottom: 1px solid gray;

                    .title{
                        text-transform: capitalize;
                        font-size: 14px;
                    }
                }     
            }

            .second{

                .attachments-section{
                    padding: 5px;
                    width: 100%;
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    overflow-x: auto;
                }

                .resources-section{
                    height: 120px;
                    width: 100%;
                    overflow: hidden;
                    display: inline-flex;
                    display: -webkit-inline-box;
                    display: -moz-inline-box;
                    border-bottom: 1px solid gray;
                    padding: 5px;
                    overflow-x: auto;

                    .no-resources{
                        width: 100%;
                        height: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }

                    .resource{
                        width: 150px;
                        height: 100%;
                        margin: 0 10px;

                        img,
                        video{
                            height: 100%;
                            width: 100%;
                            object-fit: fill;
                        }

                        audio{
                            max-width: 150px;
                        }
                    }
                }
            }

            .third{
                background: inherit;
                border-bottom: 1px solid gray;
                
                .admin-section{
                    padding: 5px;
                    display: inline-flex;
                    width: 100%;
                    justify-content: space-between;
                    border-bottom: 1px solid gray;

                    .admin-button{
                        @include button();
                    }

                    .active{
                        color: white;
                        background-color: gray;
                    }
                }

                .preamble{
                    font-size: 12px;
                    width: 100%;
                    padding: 5px;
                    color: gray;
                    background-color: mintcream;
                    cursor: pointer;
                    @include text-overflow();
                    position: relative;

                    .toggle{ 
                        font-size: 16px;
                        color: gray;
                        z-index: 1;
                        float: right;
                        margin-right: 5px;
                    }
                }

                .discussion-section{

                    .main-area{
                        padding: 10px;
                        overflow-y: auto;
                        position: relative;

                        .no-discussions{
                            width: 100%;
                            height: 150px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        .unseen-messages{
                            position: absolute;
                            right: 5px;
                            top: 5px;
                            width: fit-content;
                            border-radius: 50%;
                        }

                        .show-discussions{
                            @include show-more();
                        }

                        .loading{
                            position: absolute;
                            bottom: 0;
                            left: 0;
                            width: 100%;
                            text-align: center;
                        }
                    }

                    .text-area{
                        min-height: 75px;
                    }
                }
            }

            .forth{
                padding: 5px;

                .main{
                    width: 100%;
                    padding-top: 5px;

                    .reaction{
                        display: flex;
                        justify-content: space-between;
                        align-items: center; 
                        position: relative;

                        .reason{
                            position: absolute;
                            top: 100%;
                            z-index: 3;
                        }

                        .like{
                            display: inline-flex;
                            align-items: center;
                            justify-content: space-between;
                            width: 50%;  
                            margin-left: auto;

                            .others{
                                display: inline-flex;
                                justify-content: flex-end;
                                align-items: center;
                                position: relative;

                                .like-post{
                                    margin-right: 10px;
                                    padding: 5px;
                                    font-size: 16px;
                                    cursor: pointer;
                                }

                                .liked{
                                    color: green;
                                }   

                                .profiles{
                                    position: absolute;
                                    width: 200px;
                                    right: 0;
                                    z-index: 1000;
                                    text-align: start;
                                    top: 15px;

                                    span{
                                        font-size: 12px;
                                        font-weight: 500;
                                    }
                                }

                                .comment{
                                    cursor: pointer;
                                    font-size: 16px;
                                }
                            }

                            .unsetPosition{
                                position: unset;
                            }
                        }
                    }

                    .unsetPosition{
                        position: unset;
                    }

                    .add-comment{
                        width: 75%;
                        position: relative;
                        margin: 10px 0 0 auto;
                    }

                    .comment-section{
                        width: 85%;
                        margin: 5px 0 0 auto;
                    }
                }
            }
        }

    }
</style>