<template>
    <div class="comment-wrapper" 
        v-if="showCommentSingle"
        @dblclick.self="clickedViewComments"
        :class="{simple: simple || dashboard}"
    >
        <div class="loading" v-if="loading">
            <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
        </div>
        <div class="alert"
            v-if="alertMessage.length"
            :class="{success:alertSuccess, danger:alertDanger}"
        >
            {{alertMessage}}
        </div>
        <div class="edit"
            @click="clickedShowOptions"
            v-if="computedProfiles.length"
        >
            <font-awesome-icon
                :icon="['fa','chevron-down']"
            ></font-awesome-icon>
        </div>
        <div class="options" v-if="showOptions && computedSaves">
            <optional-actions
                :show="showOptions"
                :hasSave="!computedOwner"
                :isSaved="isSaved"
                :hasEdit="computedOwner"
                :hasDelete="computedOwner"
                @clickedOption="clickedOption"
            ></optional-actions>
        </div>
        <div class="top" v-if="!simple">
            <div class="name"
                @click="clickedProfilePicture"
            >
                {{computedName}}
            </div>
            <div class="created" v-if="computedCreatedAt">
                {{computedCreatedAt}}
            </div>
        </div>
        <div class="middle">
            <div class="profile-picture"
                @click="clickedProfilePicture"
            >
                <profile-picture
                    @click="clickedProfilePicture"
                >
                    <template slot="image">
                        <img :src="computedProfileUrl">
                    </template>
                </profile-picture>
            </div>
            <div class="comment-body"
                @dblclick.self="clickedViewComments">
                <div class="text" v-if="computedBody"
                    @dblclick.self="clickedViewComments">
                    {{computedBody}}
                </div>
                <div class="media" 
                    v-if="computedImageUrl.length"
                    :class="{commentMediaFull:!showCommentNumber}"
                    @click="clickedMedia(computedImageUrl,'image')"
                >
                    <img :src="computedImageUrl">
                </div>
                <div class="media" v-if="computedAudioUrl.length"
                    :class="{commentMediaFull:!showCommentNumber}"
                    @click="clickedMedia(computedAudioUrl,'audio')"
                >
                    <audio :src="computedAudioUrl" controls controlslist="nodownload">
                    </audio>
                </div>
                <div class="media" v-if="computedVideoUrl.length"
                    :class="{commentMediaFull:!showCommentNumber}"
                    @click="clickedMedia(computedVideoUrl,'video')"
                >
                    <video :src="computedVideoUrl" controls controlslist="nodownload">
                    </video>
                </div>
            </div>
        </div>
        <div class="bottom"
            @dblclick.self="clickedViewComments"
            v-if="!simple"
        >
            <number-of>
                {{likes === 1 ? `${likes} like` : `${likes} likes`}}
            </number-of>
            <div class="comment-number" 
                v-if="computedCommentNumber"
                @click="clickedViewComments"
                :title="commentTitle"
            >
                {{`${comments}`}} <font-awesome-icon
                    :icon="['fa','comment-alt']"
                ></font-awesome-icon>
            </div>
            <div class="other">
                <div class="like"
                    @click="clickedLike"
                    v-if="computedLikes"
                    :class="{liked:isLiked}"
                    :title="likeTitle"
                >
                    <font-awesome-icon
                        :icon="['fa','thumbs-up']"
                    ></font-awesome-icon>
                </div>
                <div class="profiles"
                    v-if="showProfiles"
                >
                    <span>
                        {{showProfilesText}}
                    </span>
                    <div :key="key" v-for="(profile,key) in computedProfiles">
                        <profile-bar
                            :smallType="true"
                            :profile="profile"
                            :navigate="false"
                            @clickedProfile="clickedProfile"
                        ></profile-bar>
                    </div>
                </div>
                <div class="comment" 
                    v-if="!showAddComment"
                    @click="clickedAddComment"
                    :class="{success:commentSuccess,fail:commentFail}"
                >
                    <font-awesome-icon
                        :icon="['fa','comment']"
                    ></font-awesome-icon>
                </div>
                <div class="comment" 
                    v-if="computedFlags && !computedOwner"
                    @click="clickedFlag"
                    :class="{flagged:isFlagged}"
                >
                    <font-awesome-icon
                        :icon="['fa','flag']"
                    ></font-awesome-icon>
                </div>
            </div>
            <div class="reason">
                <flag-reason
                    :show="showFlagReason"
                    :hasBackground="true"
                    @continueFlagProcess="continueFlagProcess"
                    @reasonGiven="reasonGiven"
                    @cancelFlagProcess="cancelFlagProcess"
                ></flag-reason>
            </div>
        </div>
            <div class="comment-section" 
                v-if="!simple"
            >
                <add-comment
                    what="comment"
                    :id="comment.id"
                    :onPostModal="!showCommentNumber"
                    :schoolAdmin="schoolAdmin"
                    :showAddComment="showAddComment"
                    @hideAddComment="showAddComment = false"
                    @postAddComplete="postAddComplete"
                    @postModalCommentCreated="postModalCommentCreated"
                    @postModalCommentEdited="postModalCommentEdited"
                    :editableData="comment"
                    :account="account"
                    :edit="editComment"
                ></add-comment>
            </div>
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    :title="smallModalTitle"
                    :show="showSmallModal"
                    :message="alertMessage"
                    :success="alertSuccess"
                    :danger="alertDanger"
                    :loading="smallModalLoading"
                    :alerting="smallModalAlerting"
                    @disappear="showSmallModal = false"
                >
                    <template slot="actions">
                        <post-button
                            buttonText="ok"
                            @click="clickedInfoOk"
                            v-if="smallModalInfo"
                        ></post-button>
                        <post-button
                            buttonText="yes"
                            @click="clickedYes"
                            v-if="smallModalDelete"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedNo"
                            v-if="smallModalDelete"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>
        <view-comments
            :show="showViewComments"
            :comment="comment"
            @viewModalDisappear="showViewComments = false"
            @postModalCommentEditedMain="viewModalCommentEditedMain"
            @commentViewParentDeleteSuccess='commentViewParentDeleteSuccess'
            @commentUnlikeSuccessfulMain="commentUnlikeSuccessfulMain"
            @commentLikeSuccessfulMain="commentLikeSuccessfulMain"
            @commentUnsaveSuccessfulMain="commentUnsaveSuccessfulMain"
            @commentSaveSuccessfulMain="commentSaveSuccessfulMain"
        ></view-comments>
    </div>
</template>

<script>
import PostButton from './PostButton'
import ProfilePicture from './profile/ProfilePicture'
import NumberOf from './NumberOf'
import WelcomeForm from './welcome/WelcomeForm'
import AddComment from './AddComment'
import ProfileBar from './profile/ProfileBar'
import AutoAlert from './AutoAlert'
import OptionalActions from './OptionalActions'
import JustFade from './transitions/JustFade'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import FlagReason from './FlagReason'
import FadeUp from './transitions/FadeUp'
import {dates, strings} from '../services/helpers'
import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'SingleComment',
        components: {
            FadeUp,
            JustFade,
            OptionalActions,
            AutoAlert,
            ProfileBar,
            AddComment,
            WelcomeForm,
            PulseLoader,
            NumberOf,
            ProfilePicture,
            PostButton,
            FlagReason,
        },
        props: {
            comment: {
                type: Object,
                default(){
                    return {}
                },
            },
            account: {
                type: Object,
                default(){
                    return null
                },
            },
            schoolAdmin: {
                type: Object,
                default(){
                    return null
                },
            },
            showCommentNumber: { //when false, comment is in comment modal
                typpe: Boolean,
                default: true
            },
            onPostModal: { 
                typpe: Boolean,
                default: false
            },
            disabled: { 
                typpe: Boolean,
                default: false
            },
            simple: { 
                typpe: Boolean,
                default: false
            },
            dashboard: { 
                typpe: Boolean,
                default: false
            },
        },
        data() {
            return {
                id: null,
                profile: null,
                showOptions: null,
                showSmallModal: false,
                smallModalLoading: false,
                smallModalAlerting: false,
                alertSuccess: false,
                alertMessage: '',
                likeTitle: '',
                alertDanger: false,
                inputComment: '',
                showEdit: false,
                showAlert: false,
                showAddComment: false,
                isLiked: false,
                editComment: false,
                likes: 0,
                comments: 0,
                commentTitle: '',
                myLike: null,
                showProfiles: false,
                showViewComments: false,
                commentSuccess: false,
                commentFail: false,
                showCommentSingle: true,
                smallModalTitle: '',
                smallModalDelete: false,
                smallModalInfo: false,
                loading: false,
                //flags
                showFlagReason: false,//it also pushes reaction section down to show flag reason
                flagReason: '',
                isFlagged: false,
                myFlag: null,
                flagTitle: '',
                //profiles
                showProfilesAction: '',
                showProfilesText: '',
                //save
                isSaved: false,
                mySave: null,
                saves: 0,
            }
        },
        watch: {
            showOptions(newValue, oldValue) {
                if (newValue) {
                    setTimeout(() => {
                        this.showOptions = false
                    }, 3000);
                }
            },
            comment: {
                immediate: true,
                handler(newValue, oldValue){
                    if (newValue) {
                        this.comments = newValue.comments ? newValue.comments : 0
                        this.commentTitle =  this.comments ? 'click to view comments' : ''
                    }
                },
                deep: true
            },
            isLiked(newValue){
                if (newValue) {
                    this.likeTitle = 'unlike this comment'
                } else {
                    this.likeTitle = 'like this comment'
                }
            },
            likes(newValue){
                if (!newValue) {
                    this.myLike = null
                    this.isLiked = false
                }
            },
            showEdit(newValue){
                if (newValue) {
                    this.editComment = true
                    this.showAddComment = true
                } else {
                    this.editComment = false
                }
            },
            isFlagged(newValue){
                if (newValue) {
                    this.flagTitle = 'unflag this answer'
                } else {
                    this.flagTitle = 'flag this answer'
                }
            },
            saves(newValue){
                if (!newValue) {
                    this.mySave = null
                    this.isSaved = false
                }
            },
        },
        mounted () {
            this.listen();
        },
        computed: {
            ...mapGetters(['getUser','getProfiles','profile/getMsg']),
            computedCommentOwnerAccount(){
                let postOwner = this.post ? {
                    account: strings.getAccount(this.comment.commentedby_type),
                    accountId: `${this.comment.commentedby_id}`
                } : null

                return postOwner
            },
            computedSaves(){
                if (this.getUser) {
                    if (this.comment && this.comment.hasOwnProperty('saves')){
                        let saves = this.comment.saves
                        this.saves = this.comment.saves.length
                        let index = null
                        index = saves.findIndex(save=>{
                                return save.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.mySave = saves[index]
                            this.isSaved = true
                        }
                    }
                }
                return true
            },
            computedProfileUrl() {
                return this.comment && this.comment.hasOwnProperty('profile_url') ?
                    this.comment.profile_url : ''
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedName() {
                return this.comment && this.comment.hasOwnProperty('commentedby') ?
                    this.comment.commentedby : ''
            },
            computedCreatedAt() {
                return this.comment ? dates.createdAt(this.comment.created_at) : '' 
            },
            computedImageUrl() {
                return this.comment && this.comment.images && this.comment.images.length ? 
                    this.comment.images[0].url : ''
            },
            computedAudioUrl() {
                return this.comment && this.comment.audios && this.comment.audios.length ? 
                    this.comment.audios[0].url : ''
            },
            computedVideoUrl() {
                return this.comment && this.comment.videos && this.comment.videos.length ? 
                    this.comment.videos[0].url : ''
            },
            computedBody() {
                return this.comment && this.comment.hasOwnProperty('body') ? 
                    strings.content(this.comment.body,200) : null
            },
            computedCommentNumber(){
                return this.showCommentNumber && this.comments > -1 ? true : false
            },
            computedFlags(){ //check flagging
                if (this.getUser) {
                    if (this.comment && this.comment.hasOwnProperty('flags')){
                        let flags = this.comment.flags
                        let index = null
                        index = flags.findIndex(flag=>{
                                return flag.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.myFlag = flags[index]
                            this.isFlagged = true
                        }
                    }
                }
                return true
            },
            computedLikes(){
                //do not show like if any of your profiles has liked the item
                if (this.getUser) {
                    if (this.comment && this.comment.hasOwnProperty('likes')){
                        let likes = this.comment.likes
                        this.likes = this.comment.likes.length
                        let index = null
                        index = likes.findIndex(like=>{
                                return like.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.myLike = likes[index]
                            this.isLiked = true
                        }
                    }
                }
                return true
            },
            computedOwner(){
                let profiles = this.getProfiles
                let profile = null

                if (profiles) {
                    
                    profile =  profiles.findIndex(el=>{
                        return this.comment.commentedby_id === el.accountId && 
                            this.comment.commentedby_type === el.profile
                    })

                    if (profile > -1) {
                        this.profile = this.getProfiles[profile]
                        return true
                    }
                }
                return false
            },
        },
        methods: {
            ...mapActions(['profile/deleteComment','profile/createLike','profile/createFlag',
                'profile/deleteLike','profile/deleteFlag','profile/deleteSave',
                'profile/createSave','home/newLike','profile/newLike',
                'dashboard/newLike','home/removeLike','profile/removeLike',
                'dashboard/removeLike','home/newAttachment','profile/newAttachment',
                'dashboard/newAttachment','home/removeAttachment','profile/removeAttachment',
                'dashboard/removeAttachment','home/newComment','profile/newComment',
                'dashboard/newComment']),
            clickedMedia(url,mediaType){
                this.$emit('clickedMedia',{url,mediaType})
            },
            //for adding and removing likes
            commentUnlikeSuccessfulMain(data){
                this.$emit('commentUnlikeSuccessfulMain',data)
            },
            commentLikeSuccessfulMain(data){
                this.$emit('commentLikeSuccessfulMain',data)
            },
            //for adding and removing saves
            commentUnsaveSuccessfulMain(data){
                this.$emit('commentUnsaveSuccessfulMain',data)
            },
            commentSaveSuccessfulMain(data){
                this.$emit('commentSaveSuccessfulMain',data)
            },
            //
            commentViewParentDeleteSuccess(data){ //delete comment in comments cos its deleted from main of child view modal
                this.$emit('commentViewParentDeleteSuccess',data)
            },
            viewModalCommentEditedMain(comment){
                //now in comment single
                this.$emit('viewModalCommentEditedMain',comment) //emit to the viewcomment this came from
            },
            listen(){
                Echo.channel(`youredu.comment.${this.comment.id}`)
                    .listen('.updateComment', commentData=>{
                        console.log(commentData)
                        this[`${this.$route.name}/replaceComment`](commentData)
                    })
                    .listen('.deleteComment', commentInfo=>{
                        console.log(commentInfo)
                        this[`${this.$route.name}/removeComment`](commentInfo)
                    })
                    .listen('.newComment', comment=>{ // for replies
                        console.log(comment)
                        this[`${this.$route.name}/newComment`](comment)
                    })
                    .listen('.newLike', data=>{
                        console.log('data :>> ', data);
                        this[`${this.$route.name}/newLike`](data)
                    })
                    .listen('.deleteLike', data=>{
                        console.log('data :>> ', data);
                        this[`${this.$route.name}/removeLike`](data)
                    })
                    .listen('.newAttachment', (attachmentData)=>{
                        console.log(attachmentData)
                        this[`${this.$route.name}/newAttachment`](attachmentData)
                    })
                    .listen('.deleteAttachment', attachmentInfo=>{
                        console.log(attachmentInfo)
                        this[`${this.$route.name}/removeAttachment`](attachmentInfo)
                    })
                    .listen('.newFlag', (flag)=>{
                        console.log(flag)
                        this[`${this.$route.name}/newFlag`](flag)
                    })
            },
            clickedProfilePicture(){
                if (this.$route.name !== 'profile' &&
                    this.computedCommentOwnerAccount) {
                    this.$router.push({
                        name: 'profile',
                        params: {
                            account: this.computedCommentOwnerAccount.account,
                            accountId: this.computedCommentOwnerAccount.accountId,
                        }
                    })
                } else if (this.computedCommentOwnerAccount) {
                    if (this.$route.params.account !== this.computedCommentOwnerAccount.account &&
                        this.$route.params.accountId !== this.computedCommentOwnerAccount.accountId) {
                        this.$router.push({
                        name: 'profile',
                        params: {
                            account: this.computedCommentOwnerAccount.account,
                            accountId: this.computedCommentOwnerAccount.accountId,
                        }
                    })
                    }
                }
            },
            clickedInfoOk(){
                this.clearSmallModal()
            },
            postModalCommentCreated(comment){
                this.$emit('postModalCommentCreated', comment)
            },
            postModalCommentEdited(comment){
                this.$emit('postModalCommentEdited', comment)
            },
            postAddComplete(data){
                if (data === 'successful') {
                    this.showAddComment = false
                    this.comments += 1
                    this.commentSuccess = true
                    setTimeout(() => {
                        this.commentSuccess = false
                    }, 2000);
                } else if (data === 'unsuccessful') {
                    this.commentFail = true
                    setTimeout(() => {
                        this.commentFail = false
                    }, 2000);
                }
            },  
            clearSmallModal(){
                this.showSmallModal = false
                this.smallModalDelete = false
                this.smallModalInfo = false
                this.showProfilesAction = ''
                this.smallModalTitle = ''
                this.alertSuccess = false
                this.alertDanger = false
                // this.smallModalAlerting = false
            },
            profilesAppear(){
                if (this.account) {
                    this.clickedProfile(this.account)
                    return
                }
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            reasonGiven(data){
                this.showFlagReason = false
                this.flagReason = data
                this.profilesAppear()
            },
            continueFlagProcess(){
                this.flagReason = null
                this.showFlagReason = false
                this.profilesAppear()
            },
            cancelFlagProcess(){
                this.flagReason = ''
                this.showFlagReason = false
            },      
            clickedFlag(){
                if (this.disabled) {
                    return
                }
                if (this.isFlagged) {
                    this.flag(null)
                    return
                }
                this.showProfilesText = 'flag as'
                this.showProfilesAction = 'flag'
                if (!this.getUser) {
                    this.$emit('askLoginRegister','commentSingle')
                } else if (this.getProfiles && !this.getProfiles.length) {
                    this.smallModalInfo= false
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can flag.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                } else {
                    this.smallModalInfo = false
                    this.smallModalDelete = true
                    this.showFlagReason = true
                }
            },
            async flag(who){
                this.loading = true
                let data = {}
                data.comment = true
                data.commentable_type = this.comment.commentable_type
                data.commentable_id = this.comment.commentable_id
                data.itemId = this.comment.id
                data.where = this.$route.name
                if (this.schoolAdmin) {
                    data.adminId = this.schoolAdmin.id
                }
                
                let response = null
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    data.item = 'comment'
                    data.reason = this.flagReason

                    response = await this['profile/createFlag'](data)
                } else {
                    data.flagId = this.myFlag.id

                    response = await this['profile/deleteFlag'](data)
                }

                this.loading =false
                if (response.status) {
                    this.alertSuccess = true
                    if (this.isFlagged) {
                        this.isFlagged = false
                        // this.$emit('commentUnflaggedSuccess', {
                        //     flag: response.flag,
                        //     commentId: this.comment.id
                        // })
                    } else {
                        this.alertModalMessage = 'successfully flagged'
                        this.$emit('commentDeleteSuccess', {
                            commentId: this.comment.id
                        })
                    }
                    this.smallModalAlerting = true
                } else {
                    this.alertDanger = true
                    this.alertModalMessage = 'flagging successful'
                }
                this.flagReason = ''
                this.smallModalData = null
                setTimeout(() => {
                    this.clearSmallModal()
                }, 3000);
            },
            clickedViewComments(){
                if (this.comments) {
                    this.showViewComments = true
                } else if (this.simple) {
                    this.$emit('clickedShowPostComments')
                }
            },
            async clickedProfile(who){
                if (this.showProfilesAction === 'like') {
                    this.showProfiles = false
                    this.isLiked = true
                    this.likes += 1
                    let data = {
                        item: 'comment',
                        itemId: this.comment.id,
                        account: who.account,
                        accountId: who.accountId,
                        owner: this.comment.commentable_type,
                        ownerId: this.comment.commentable_id,
                    }

                    data.where = this.$route.name
                    if (this.schoolAdmin) {
                        data.adminId = this.schoolAdmin.id
                    }
                    let response = await this['profile/createLike'](data)

                    if (response === 'unsuccessful') {
                        this.isLiked = false
                        this.likes -= 1
                    } else {
                        this.$emit('commentLikeSuccessful',{
                            itemId: data.itemId,
                            like: response
                        })
                    }
                } else if (this.showProfilesAction === 'save') {
                    this.save(who)
                } else if (this.showProfilesAction === 'flag') {
                    this.smallModalTitle = 'are you sure you want to flag this?'
                    this.smallModalDelete = true
                    this.showSmallModal = true
                    this.smallModalData = who
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                }
            },
            viewModalDisappear(){
                this.showViewComments = false
            },
            clickedShowOptions(){
                if (this.disabled) {
                    return
                }
                this.showAddComment = false
                this.showOptions = !this.showOptions
            },
            alertDisappear() {
                
            },
            async save(who){
                this.showProfiles = false
                this.loading = true
                let data = {
                    item: 'comment',
                    itemId: this.comment.id,
                    owner: this.comment.commentedby_type,
                    ownerId: this.comment.commentedby_id,
                },
                    response = null,
                    state = ''

                data.where = this.$route.name
                if (this.schoolAdmin) {
                    data.adminId = this.schoolAdmin.id
                }
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    state = 'saving'
                    response = await this['profile/createSave'](data)
                } else {
                    data.saveId = this.mySave.id
                    state = 'unsaving'
                    response = await this['profile/deleteSave'](data)
                }

                this.loading = false
                if (response.status) {
                    if (who) {
                        this.saves += 1
                        this.$emit('commentSaveSuccessful',{ //emit to post modal or comment view
                            itemId: this.comment.id,
                            save: response.save,
                        })
                    } else {
                        this.saves -= 1
                        this.$emit('commentUnsaveSuccessful',{ //emit to post modal or comment view
                            itemId: this.comment.id,
                            saveId: data.saveId,
                        })
                    }
                    this.isSaved = !this.isSaved
                    this.alertSuccess = true
                    this.alertMessage = `${state} successful`
                } else {
                    this.alertDanger = true
                    this.alertMessage = `${state} unsuccessful`
                }
                setTimeout(() => {
                    this.alertSuccess = false
                    this.alertDanger = false
                    this.alertMessage = ''
                }, 3000);
            },
            async clickedLike(){
                if (this.disabled) {
                    return
                }
                if (!this.getUser) {
                    this.$emit('askLoginRegister','commentSingle')
                } else if (!this.getProfiles.length) {
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can like.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.showSmallModal = false
                    }, 4000);
                } else {
                    this.showProfilesText = 'like as'
                    this.showProfilesAction = 'like'
                    if (this.isLiked) {
                        this.likes -= 1
                        this.isLiked = false
                        
                        if (this.myLike && this.myLike.hasOwnProperty('id')) {
                            let newData = {
                                likeId: this.myLike.id,
                                item: 'comment',
                                itemId: this.comment.id,
                                owner: this.comment.commentable_type,
                                ownerId: this.comment.commentable_id,
                            }

                            newData.where = this.$route.name
                            if (this.schoolAdmin) {
                                newData.adminId = this.schoolAdmin.id
                            }
                            let response = await this['profile/deleteLike'](newData)
                            if (response === 'unsuccessful') {
                                this.isLiked = true
                                this.likes += 1
                            } else {
                                this.$emit('commentUnlikeSuccessful',newData)
                            }
                        } else {
                            this.likes += 1
                            this.isLiked = true
                        }
                    } else {
                        this.profilesAppear() //here
                    }
                }
            },
            clickedAddComment(){
                if (this.disabled) {
                    return
                }
                if (!this.getUser) {
                    this.$emit('askLoginRegister','commentSingle')
                } else if (!this.getProfiles.length) {
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can comment.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.showSmallModal = false
                    }, 4000);
                } else {
                    this.editComment = false
                    this.showAddComment = true
                }
            },
            async clickedYes(){
                if (this.showProfilesAction === 'flag') {
                    this.flag(this.smallModalData)
                    return
                }
                this.smallModalLoading = true
                let data = {
                    commentId: this.comment.id,
                    owner: this.comment.commentable_type,
                    ownerId: this.comment.commentable_id,
                    account: this.profile.account,
                    accountId: this.profile.accountId,
                    where: this.$route.name
                }

                if (this.schoolAdmin) {
                    data.adminId = this.schoolAdmin.id
                }
                let response = await this['profile/deleteComment'](data)
                
                this.smallModalLoading = false
                if (response !== 'unsuccessful') {
                    this.$emit('commentDeleteSuccess', {
                        commentId: data.commentId
                    })
                    this.showCommentSingle = false
                } else {
                    this.alertDanger = true
                    this.alertMessage = 'comment deletion unsuccessful'
                    this.clearAlert()
                }
            },
            clearAlert(){
                setTimeout(() => {
                    this.alertMessage = ''
                    this.alertDanger = false
                    this.alertSuccess = false
                }, 2000);
            },
            clickedNo(){
                this.clearSmallModal()
            },
            clickedOption(data) {
                this.showOptions = false
                if (data === 'edit') {
                    this.showEdit = true
                } else if (data === 'save') {
                    if (this.isSaved) {
                        this.save(null)
                        return
                    }
                    this.showProfilesText = 'save as'
                    this.showProfilesAction = 'save'
                    this.profilesAppear() //
                } else if (data === 'delete') {
                    this.smallModalTitle = 'are you sure you want to delete this'
                    this.smallModalDelete = true
                    this.smallModalInfo = false
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
$profile-picture-width: 40px;
$comment-font-size: 13px;

    .loading{
        width: 100%;
        text-align: center;
        padding: 5px;
    }

    .alert{
        width: 100%;
        text-align: center;
        padding: 0;
        font-size: 12px;
        margin: 0;
    }

    .success{
        color: green;
    }

    .danger{
        color: red;
    }

    .comment-wrapper{
        display: block;
        position: relative;
        border-right: 1px solid dimgrey;
        background-color: rgba(105, 105, 105,.08);
        margin-top: 10px;
        padding: 5px;
        cursor: pointer;

        .edit{
            font-size: 16px;
            margin-top: -10px;
            cursor: pointer;
            text-align: end;
        }

        .top{
            display: inline-flex;
            justify-content: space-between;
            width: 100%;
            padding-left: 5px;
            padding-right: 5px;

            .name{
                font-weight: 500;
                text-transform: capitalize;
                width: auto;
                font-size: 14px;
                @include text-overflow();
            }

            .created{
                @include text-overflow();
                max-width: 40%;
                color: rgba(128, 128, 128, 0.9);
                font-size: 11px;
                text-align: end;
            }
        }

        .middle{
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 5px;
            border-top: 1px solid dimgrey;
            border-bottom: 1px solid dimgrey;

            .profile-picture{
                width: $profile-picture-width;
                height: $profile-picture-width;
            }

            .comment-body{
                min-width: 50%;
                max-width: 75%;
                text-align: justify;
                margin: 0 auto 0 10px;

                .text{
                    width: 100%;
                    font-size: $comment-font-size;
                }

                .media{
                    width: 100%;
                    margin: 10px 0 10px;
                    max-height: 100px;
                    overflow: hidden;

                    video,
                    img{
                        width: 100%;
                        height: 100%;
                        object-fit: contain;
                    }

                    audio{
                        width: 100%;
                    }
                }

                .commentMediaFull{
                    max-height: none;
                }
            }
        }

        .bottom{
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 5px;
            padding-left: 5px; 
            width: 100%;  

            .comment-number{
                font-size: 12px;
                padding: 5px;
                cursor: pointer;
                background-color: whitesmoke;
                border-radius: 10%;
            }

            .other{
                display: inline-flex;
                align-items: center;
                position: relative;

                .like,
                .comment{
                    padding: 5px;
                    margin-right: 5px;
                    font-size: 14px;
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

                .flagged{
                    color: red;
                }

                .success{
                    color: green;
                    box-shadow: 0 0 3px gray;
                }

                .fail{
                    color: red;
                    box-shadow: 0 0 3px gray;
                }
            }

            .reason{
                position: absolute;
                top: 90%;
                right: 10px;
                z-index: 2;
            }
        }

        .comment-section{
            margin: 5px 5px 5px 0;
        }
    }

    .simple{
        background: transparent;
        border: none; 
        box-shadow: 0 0 2px grey;
        border-radius: 10px;

        .middle{
            border: none;
            padding: 0;

            .profile-picture{
                width: 30px;
                height: 30px;
            }
        }
    }

@media screen and (min-width: 800px) and (max-width: 1100px){
    $profile-picture-width: 35px;
    $comment-font-size: 12px;

    .comment-wrapper{
        
        .top{

            .profile-picture{
                width: $profile-picture-width;
                height: $profile-picture-width;
            }

            .comment-body{

                .text{
                    font-size: $comment-font-size;
                }
            }
        }
    }

}

@media screen and (max-width: 800px){
    $profile-picture-width: 40px;
    $comment-font-size: 13px;

    .comment-wrapper{
        
        .top{

            .profile-picture{
                width: $profile-picture-width;
                height: $profile-picture-width;
            }

            .comment-body{

                .text{
                    font-size: $comment-font-size;
                }
            }
        }
    }
}
</style>