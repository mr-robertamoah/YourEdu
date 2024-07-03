<template>
    <div class="comment-wrapper" 
        v-if="showCommentSingle"
        @dblclick.self="clickedViewComments"
        :class="{simple: simple || dashboard}"
    >
        <div class="loading" v-if="loading">
            <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
        </div>
        <div class="alert">
            <auto-alert
                :message="alertMessage"
                :success="alertSuccess"
                :danger="alertDanger"
                :sticky="true"
                @hideAlert="clearAlert"
            ></auto-alert>
        </div>
        <div class="edit absolute right-2"
            @click="clickedShowOptions"
            v-if="computedProfiles.length"
        >
            <font-awesome-icon
                :icon="['fa','chevron-down']"
            ></font-awesome-icon>
        </div>
        <div class="options" v-if="showOptions">
            <optional-actions
                :show="showOptions"
                :hasSave="simple ? false : true"
                :hasEdit="computedOwner && !simple"
                :hasDelete="computedOwner"
                :hasAttachment="simple ? false : true"
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
        
        <reaction-component
            v-if="!simple"
            :schoolAdmin="schoolAdmin"
            :editableComment="comment"
            :commentingAccount="account"
            :editComment="editComment"
            :comments="computedComments"
            :commentCountData="{count: commentCount, title: commentTitle}"
            :item="computedItem"
            :isOwner="computedIsOwner"
            :full="!showCommentNumber"
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
            @clickedShowPostComments="clickedViewComments"
            @postModalCommentCreated="postModalCommentCreated"
            @postModalCommentEdited="postModalCommentEdited"
        ></reaction-component>
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
import PostButton from './PostButton.vue'
import ProfilePicture from './profile/ProfilePicture.vue'
import NumberOf from './NumberOf.vue'
import WelcomeForm from './welcome/WelcomeForm.vue'
import AddComment from './AddComment.vue'
import ProfileBar from './profile/ProfileBar.vue'
import AutoAlert from './AutoAlert.vue'
import OptionalActions from './OptionalActions.vue'
import JustFade from './transitions/JustFade.vue'
import FlagReason from './FlagReason.vue'
import FadeUp from './transitions/FadeUp.vue'
import Like from '../mixins/Like.mixin';
import Flag from '../mixins/Flag.mixin';
import Save from '../mixins/Save.mixin';
import Alert from '../mixins/Alert.mixin';
import Profiles from '../mixins/Profiles.mixin';
import Comments from '../mixins/Comments.mixin';
import SmallModal from '../mixins/SmallModal.mixin';
import {dates, strings} from '../services/helpers'
import { mapGetters, mapActions } from 'vuex'
import { useRoute, useRouter } from 'vue-router'

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
        mixins: [
            Like, Flag, Save, Alert, SmallModal, Comments,
            Profiles, 
        ],
        data() {
            return {
                id: null,
                profile: null,
                showOptions: null,

                inputComment: '',
                showEdit: false,
                showAlert: false,
                editComment: false,

                commentCount: 0,
                commentTitle: '',
                showViewComments: false,
                commentSuccess: false,
                commentFail: false,
                showCommentSingle: true,
                loading: false,
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
                required: true,
                handler(newValue, oldValue){
                    if (newValue) {
                        this.commentCount = newValue.comments ? newValue.comments : 0
                        this.commentTitle =  this.commentCount ? 'click to view comments' : ''
                    }
                },
                deep: true
            },
            showEdit(newValue){
                if (newValue) {
                    this.editComment = true
                    this.showAddComment = true
                } else {
                    this.editComment = false
                }
            },
            "comment.likes": {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        
                        this.likeData.likes = newValue.length
                    }
                }
            },
            "comment.saves": {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        
                        this.saveData.saves = newValue.length
                    }
                }
            },
        },
        mounted () {
            this.setMyFlag()
            this.setMyLike()
            this.setMySave()
            this.listen();
            this.listenForComments()
            this.listenForLikes()
            this.listenForFlags()
            this.listenForSaves()
        },
        computed: {
            ...mapGetters(['getUser','getProfiles','profile/getMsg']),
            computedCommentOwnerAccount(){
                let postOwner = this.post ? {
                    account: this.comment.commentedby.account,
                    accountId: `${this.comment.commentedby.accountId}`
                } : null

                return postOwner
            },
            computedClasses(){
                let classes = ''

                if (this.showProfilesAction === 'save' || 
                    this.showProfilesAction === 'attach') {
                    classes += 'absolute top-5 left-2/3 -ml-1/4 top-10 max-w-content'
                }
                
                if (this.steps === 0) {
                    classes += ' bottom-8'
                }

                return classes
            },
            computedItem() {
                return {
                    item: 'comment',
                    itemId: this.comment.id
                }
            },
            computedItemable() {
                return this.comment
            },
            computedIsOwner() {
                return this.comment.commentedby.userId === this.getUser?.id
            },
            computedProfileUrl() {
                return this.comment?.commentedby.url ?
                    this.comment.commentedby.url : ''
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedName() {
                return this.comment.commentedby.name
            },
            computedCreatedAt() {
                return this.comment.createdAt
            },
            computedImageUrl() {
                return this.comment.images && this.comment.images.length ? 
                    this.comment.images[0].url : ''
            },
            computedAudioUrl() {
                return this.comment.audios && this.comment.audios.length ? 
                    this.comment.audios[0].url : ''
            },
            computedVideoUrl() {
                return this.comment.videos && this.comment.videos.length ? 
                    this.comment.videos[0].url : ''
            },
            computedBody() {
                return this.comment.hasOwnProperty('body') ? 
                    strings.trim(this.comment.body, 200) : null
            },
            computedCommentNumber(){
                return this.showCommentNumber && this.commentCount > -1 ? true : false
            },
            computedOwner(){
                let profiles = this.getProfiles
                let profile = null

                if (profiles) {
                    
                    profile =  profiles.findIndex(profile=>{
                        return this.comment.commentedby.accountId === profile.accountId && 
                            this.comment.commentedby.account === profile.account
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
            ...mapActions([
                'profile/deleteComment',
                'home/removePost','home/replacePost',
                'profile/removePost','profile/replacePost',
                'dashboard/removePost','dashboard/replacePost',
            ]),
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
                const route = useRoute()
                
                Echo.channel(`youredu.comment.${this.comment.id}`)
                    .listen('.updateComment', data=>{
                        this[`${route.name}/replaceComment`](data.comment)
                    })
                    .listen('.deleteComment', data=>{
                        this[`${route.name}/removeComment`](data)
                    })
            },
            clickedProfilePicture(){
                const router = useRouter()
                const route = useRoute()

                if (route.name !== 'profile' &&
                    this.computedCommentOwnerAccount) {
                    router.push({
                        name: 'profile',
                        params: {
                            account: this.computedCommentOwnerAccount.account,
                            accountId: this.computedCommentOwnerAccount.accountId,
                        }
                    })
                }
                
                if (this.computedCommentOwnerAccount) {
                    if (route.params.account !== this.computedCommentOwnerAccount.account &&
                        route.params.accountId !== this.computedCommentOwnerAccount.accountId) {
                        router.push({
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
                    this.commentCount += 1
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
            clickedViewComments(){
                if (this.commentCount) {
                    this.showViewComments = true
                } else if (this.simple) {
                    this.$emit('clickedShowPostComments')
                }
            },
            async clickedProfile(who){
                if (this.showProfilesAction === 'like') {
                    this.like(who)
                    return
                }
                
                if (this.showProfilesAction === 'save') {
                    this.save(who)
                    return
                }
                
                if (this.showProfilesAction === 'flag') {
                    this.issueCustomMessage({
                        message: 'are you sure you want to flag this?',
                        type: 'delete',
                        data: who
                    })

                    this.clearSmallModal(false)
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
            askLoginRegister() {
                this.$emit('askLoginRegister','comment')
            },
            clickedAddComment(){
                if (this.disabled) {
                    return
                }
                if (!this.getUser) {
                    this.askLoginRegister()
                }
                
                if (!this.getProfiles.length) {
                    this.issueCustomMessage({
                        message: 'you must have an account (eg. learner, parent, etc) before you can comment.',
                        type: 'info'
                    })
                    
                    this.clearSmallModal(false)
                    return
                }

                this.editComment = false
                this.showAddComment = true
            },
            clickedYes(){
                if (this.showProfilesAction === 'flag') {
                    this.flag(this.smallModalData)
                    return
                }

                this.deleteComment()
            },
            async deleteComment() {
                const route = useRoute()
                
                this.loading = true
                let data = {
                    commentId: this.comment.id,
                    item: strings.getAccount(this.comment.commentable_type),
                    itemId: this.comment.commentable_id,
                    account: this.profile.account,
                    accountId: this.profile.accountId,
                    where: route.name
                }

                if (this.schoolAdmin) {
                    data.adminId = this.schoolAdmin.id
                }
                let response = await this['profile/deleteComment'](data)
                
                this.loading = false
                if (response !== 'unsuccessful') {
                    this.$emit('commentDeleteSuccess', {
                        commentId: data.commentId
                    })
                    this.showCommentSingle = false
                } else {
                    this.alertDanger = true
                    this.alertMessage = 'comment deletion unsuccessful'
                }
            },
            clickedNo(){
                this.clearSmallModal()
            },
            clickedOption(data) {
                this.showOptions = false
                if (data === 'edit') {
                    this.showEdit = true
                    return
                }
                
                if (data === 'save') {
                    if (this.saveData.isSaved) {
                        this.save(null)
                        return
                    }

                    this.clickedSave()
                    return
                }
                
                if (data === 'delete') {
                    this.issueSmallModalDeletionMessage()
                    
                    this.clearSmallModal(false)
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