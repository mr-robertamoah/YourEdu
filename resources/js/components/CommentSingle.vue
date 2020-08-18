<template>
    <div class="comment-wrapper" 
        v-if="showCommentSingle"
        @dblclick.self="clickedViewComments"
    >
        <div class="edit"
            @click="clickedShowOptions"
            v-if="computedOwner"
        >
            <font-awesome-icon
                :icon="['fa','chevron-down']"
            ></font-awesome-icon>
        </div>
        <div class="options" v-if="showOptions">
            <span @click="clickedOpion('edit')">edit</span>
            <span @click="clickedOpion('delete')">delete</span>
        </div>
        <div class="top">
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
            @dblclick.self="clickedViewComments">
            <number-of>
                {{`${likes} likes`}}
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
                            :name="profile.name"
                            :type="profile.params.account"
                            :smallType="true"
                            :routeParams="profile.params"
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
                    v-if="computedFlags"
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
                >
                <add-comment
                    what="comment"
                    :id="comment.id"
                    :onPostModal="onPostModal"
                    :showAddComment="showAddComment"
                    @hideAddComment="showAddComment = false"
                    @postAddComplete="postAddComplete"
                    @postModalCommentCreated="postModalCommentCreated"
                    @postModalCommentEdited="postModalCommentEdited"
                    :editableData="comment"
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
import JustFade from './transitions/JustFade'
import FlagReason from './FlagReason'
import FadeUp from './transitions/FadeUp'
import {dates, strings} from '../services/helpers'
import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'SingleComment',
        components: {
            FadeUp,
            JustFade,
            AutoAlert,
            ProfileBar,
            AddComment,
            WelcomeForm,
            NumberOf,
            ProfilePicture,
            PostButton,
            FlagReason,
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
                //flags
                showFlagReason: false,//it also pushes reaction section down to show flag reason
                flagReason: '',
                isFlagged: false,
                myFlag: null,
                flagTitle: '',
                //profiles
                showProfilesAction: '',
                showProfilesText: '',
            }
        },
        props: {
            comment: {
                type: Object,
                default(){
                    return {}
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
                return this.comment && this.comment.images ? this.comment.images[0].url : ''
            },
            computedAudioUrl() {
                return this.comment && this.comment.audios ? this.comment.audios[0].url : ''
            },
            computedVideoUrl() {
                return this.comment && this.comment.videos ? this.comment.videos[0].url : ''
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
                        return this.comment.commentedby_id === el.params.accountId && 
                            this.comment.commentedby_type === el.profile
                    })

                    if (profile > -1) {
                        this.profile = this.getProfiles[profile]
                        return true
                    }
                }
                return false
            },
            computedFlag(){
                return true
            },
        },
        methods: {
            ...mapActions(['profile/deleteComment','profile/createLike','profile/createFlag'
                ,'profile/deleteLike','profile/deleteFlag']),
            clickedMedia(url,mediaType){
                this.$emit('clickedMedia',{url,mediaType})
            },
            commentUnlikeSuccessfulMain(data){
                this.$emit('commentUnlikeSuccessfulMain',data)
            },
            commentLikeSuccessfulMain(data){
                this.$emit('commentLikeSuccessfulMain',data)
            },
            commentViewParentDeleteSuccess(data){ //delete comment in comments cos its deleted from main of child view modal
                this.$emit('commentViewParentDeleteSuccess',data)
            },
            viewModalCommentEditedMain(comment){
                //now in comment single
                this.$emit('viewModalCommentEditedMain',comment) //emit to the viewcomment this came from
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
                        this.$emit('answerUnflaggedSuccess', {
                            flag: response.flag,
                            commentId: this.comment.id
                        })
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
                this.showAddComment = false
                this.showOptions = this.computedOwner
            },
            alertDisappear() {
                
            },
            async clickedLike(){
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
                            let data = {
                                likeId: this.myLike.id,
                                item: 'comment',
                                itemId: this.comment.id,
                                owner: this.comment.commentable_type,
                                ownerId: this.comment.commentable_id,
                            }

                            let response = await this['profile/deleteLike'](data)
                            if (response === 'unsuccessful') {
                                this.isLiked = true
                                this.likes += 1
                            } else {
                                this.$emit('commentUnlikeSuccessful',data)
                            }
                        } else {
                            this.likes += 1
                            this.isLiked = true
                        }
                    } else {
                        this.profilesAppear()
                    }
                }
            },
            clickedAddComment(){
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
                    account: this.profile.params.account,
                    accountId: this.profile.params.accountId,
                }
                let response = await this['profile/deleteComment'](data)
                
                this.smallModalLoading = false
                if (response !== 'unsuccessful') {
                    this.$emit('commentDeleteSuccess', {
                        commentId: data.commentId
                    })
                    this.alertSuccess = true
                    this.showCommentSingle = false
                    this.alertDanger = false
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                }
                
                if (this['profile/getMsg']) {
                    this.smallModalAlerting = true
                    this.alertMessage = this['profile/getMsg']

                    setTimeout(() => {
                        this.smallModalAlerting = false
                        this.showSmallModal = false
                    }, 2000);
                }
            },
            clickedNo(){
                this.clearSmallModal()
            },
            clickedOpion(data) {
                this.showOptions = false
                if (data === 'edit') {
                    this.showEdit = true
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

@mixin text-overflow(){
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}
    .comment-wrapper{
        display: block;
        position: relative;
        border-right: 1px solid dimgrey;
        // border-left: 1px solid dimgrey;
        background-color: rgba(105, 105, 105,.08);
        margin-top: 10px;
        padding: 5px;

        .edit{
            font-size: 16px;
            margin-top: -10px;
            cursor: pointer;
            text-align: end;
        }

        .options{
            font-size: 14px;
            margin: 5px;
            background-color: whitesmoke;
            width: 75px;
            position: absolute;
            right: 0;
            top: 15px;

            span{
                padding: 5px;
                cursor: pointer;
                display: block;
                width: 100%;
                text-align: center;

                &:hover{
                    box-shadow: 0 0 3px dimgray;
                }
            }
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
            }
        }

        .middle{
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 10px;
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
                    margin: 0px 0 10px;
                    max-height: 120px;
                    overflow: hidden;

                    video,
                    audio,
                    img{
                        width: 100%;
                        height: auto;
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