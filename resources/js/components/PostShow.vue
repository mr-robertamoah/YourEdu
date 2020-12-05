<template>
    <div class="post-show-wrapper"
        @dblclick.self="clickedShowPostComments"
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
            @click="clickedEditIcon"
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
                :hasAttachment="computedProfiles.length ? true : false"
                :hasDelete="computedOwner"
                @clickedOption="clickedOption"
            ></optional-actions>
        </div>
        <div class="post-attachment" 
            v-if="computedAttachments"
            @click.self="showAttach = false"
        >
            <post-attachment
                :show="showAttach"
                :isAttached="isAttached"
                :attachmentsNumber="attachments"
                :attachments="myAttachments"
                @itemClicked="attachmentClicked"
                @clickedUnattach="clickedUnattach"
            ></post-attachment>
        </div>
        <div class="top"
            @dblclick="clickedShowPostComments"
        >
            <div class="name"
                @click="clickedProfilePicture"
            >
                {{computedName}}
            </div>
            <div class="created">
                {{computedCreated}}
            </div>
        </div>
        <div class="body" 
            v-if="!computedLesson"
            @dblclick="clickedShowPostComments"
        >
            <div class="creator">
                <profile-picture>
                    <template slot="image">
                        <img :src="computedUrl">
                    </template>
                </profile-picture>
            </div>
            <div class="other">
                <div class="post-media" 
                    v-if="computedImageUrl.length"
                    :class="{postMediaFull:postMediaFull}"
                    @click="clickedPostMedia(computedImageUrl,'image')"
                >
                    <img :src="computedImageUrl">
                </div>
                <div class="post-media" 
                    v-if="computedAudioUrl.length"
                    :class="{postMediaFull:postMediaFull}"
                    @click="clickedPostMedia(computedAudioUrl,'audio')"
                >
                    <audio :src="computedAudioUrl" controls controlslist="nodownload">
                    </audio>
                </div>
                <div class="post-media" 
                    v-if="computedVideoUrl.length"
                    :class="{postMediaFull:postMediaFull}"
                    @click="clickedPostMedia(computedVideoUrl,'video')"
                >
                    <video :src="computedVideoUrl" controls controlslist="nodownload">
                    </video>
                </div>
                <div class="text-short"
                    @click="clickedShowPostComments">
                    <slot name="text"></slot>
                    <main-textarea
                        :disabled="true"
                        v-model="computedContent"
                    >
                    </main-textarea>
                    <div
                        v-if="computedShowMore"
                    >see more</div>
                </div>
            </div>
        </div>
        <template v-if="computedType && !computedLesson">
            <post-preview
                :type="computedType"
                :typeName="computedTypeName"
                :typeMediaFull="postMediaFull"
                @clickedMedia="clickedMedia"
                @clickedShowPostPreview="clickedShowPostPreview"
                :post="post"
            ></post-preview>
        </template>
        <template v-if="computedType && computedLesson">
            <lesson-preview
                :lesson="computedType"
                :profileUrl="post.profile_url"
                :full="postMediaFull"
                @clickedMedia="clickedMedia"
                :post="post"
            ></lesson-preview>
        </template>
        <div class="bottom">
            <div class="main">
                <div class="reaction" 
                    :class="{unsetPosition: showProfilesAction === 'save' || 
                        showProfilesAction === 'attach'}"
                >
                    <post-button 
                        :titleText="flagTitle"
                        v-if="computedFlags && !computedOwner"
                        @click="clickedFlag"
                        :postButtonClass="flagRed"
                    >
                        <template slot="icon">
                            <font-awesome-icon
                                :icon="['fa','flag']"
                            ></font-awesome-icon>
                        </template>
                    </post-button>
                    <div class="reason">
                        <flag-reason
                            :show="showFlagReason"
                            :hasBackground="true"
                            @continueFlagProcess="continueFlagProcess"
                            @reasonGiven="reasonGiven"
                            @cancelFlagProcess="cancelFlagProcess"
                        ></flag-reason>
                    </div>
                    <div class="like">
                        <number-of>
                            {{likes === 1 ? `${likes} like` : `${likes} likes`}}
                        </number-of>
                        <div class="others" 
                            :class="{unsetPosition: showProfilesAction === 'save' || 
                            showProfilesAction === 'attach'}"
                        >
                            <div class="like-post"
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
                                title="add a comment"
                                @click="clickedAddComment"
                                v-if="!showAddComment"
                                :class="{success:commentSuccess,fail:commentFail}"
                            >
                                <font-awesome-icon
                                    :icon="['fa','comment']"
                                ></font-awesome-icon>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-comment">
                    <add-comment
                        what="post"
                        :id="computedId"
                        :onPostModal="postMediaFull"
                        :showAddComment="showAddComment"
                        @hideAddComment="showAddComment = false"
                        @postAddComplete="postAddComplete"
                        @postModalCommentCreated="postModalCommentCreated"
                    ></add-comment>
                </div>
                <div class="comment-section"
                    @dblclick.self="clickedShowPostComments">
                    <template v-if="!postMediaFull && computedComments">
                        <comment-single
                            :key="comment.id" 
                            v-for="comment in computedComments"
                            :comment="comment"
                            :simple="true"
                            @askLoginRegister="askLoginRegister"
                            @clickedMedia="clickedMedia"
                            @clickedShowPostComments="clickedShowPostComments"
                        ></comment-single>
                    </template>
                </div>
            </div>
        </div>
        <div class="attachments-section">
            <attachment-badge 
                v-for="(attachment,index) in postAttachments"
                :key="index"
                :hasClose="false"
                :attachment="attachment.data"
                :type="attachment.type"
            ></attachment-badge>
        </div>
        <just-fade>
            <template slot="transition" v-if="showEdit">
                <create-post
                    :edit="showEdit"
                    :editableData="post"
                    :showForm="showEdit"
                    :loading="createPostLoading"
                    :type="post.typeName"
                    @clickedEdit="clickedEdit"
                    @mainModalDisappear="showEdit=false"
                ></create-post>
            </template>
        </just-fade>
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
                            v-if="smallModalDelete "
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture'
import PostButton from './PostButton'
import AddComment from './AddComment'
import MainTextarea from './MainTextarea'
import PostPreview from './PostPreview'
import LessonPreview from './LessonPreview'
import NumberOf from './NumberOf'
import ProfileBar from './profile/ProfileBar'
import CreatePost from './forms/CreatePost'
import OptionalActions from './OptionalActions'
import PostAttachment from './PostAttachment'
import AttachmentBadge from './AttachmentBadge'
import FlagReason from './FlagReason'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import FadeUp from './transitions/FadeUp'
import JustFade from './transitions/JustFade'
import _ from 'lodash'
import {dates, strings, files} from '../services/helpers'
import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'PostShow',
        components: {
            ProfilePicture,
            JustFade,
            FadeUp,
            AttachmentBadge,
            PostAttachment,
            OptionalActions,
            CreatePost,
            ProfileBar,
            NumberOf,
            LessonPreview,
            PostPreview,
            MainTextarea,
            AddComment,
            PostButton,
            PulseLoader,
            FlagReason,
        },
        props: {
            post: {
                type: Object,
                default(){
                    return {}
                },
            },
            postMediaFull: { //true means its on the post modal
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                showOptions: false,
                showEdit: false,
                profile: null,
                alertMessage: '',
                showModal: false,
                alertSuccess: false,
                alertDanger: false,
                loading: false,
                createPostLoading: false,
                //small modal
                showSmallModal: false,
                smallModalLoading: false,
                smallModalAlerting: false,
                smallModalDelete: false,
                smallModalTitle: '',
                smallModalInfo: false,
                //
                showAddComment: false,
                isLiked: false,
                likes: 0,
                myLike: null,
                showProfiles: false,
                likeTitle: '',
                commentSuccess: false,
                commentFail: false,
                //flags
                showFlagReason: false,//it also pushes reaction section down to show flag reason
                flagReason: '',
                isFlagged: false,
                myFlag: null,
                flagTitle: '',
                flagRed: '',
                //profiles
                showProfilesAction: '',
                showProfilesText: '',
                //save
                isSaved: false,
                mySave: null,
                saves: 0,
                //attach
                isAttached: false, //means i have attached and it goes for likes, etc
                myAttachments: null,
                attachments: 0,
                postAttachments: [],
                attachable: null,
                showAttach: false,
                selectedAttachment: null
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
            isLiked(newValue){
                if (newValue) {
                    this.likeTitle = 'unlike this post'
                } else {
                    this.likeTitle = 'like this post'
                }
            },
            isFlagged(newValue){
                if (newValue) {
                    this.flagTitle = 'unflag this post'
                    this.flagRed = 'red'
                } else {
                    this.flagTitle = 'flag this post'
                    this.flagRed = ''
                }
            },
            likes(newValue){
                if (!newValue) {
                    this.myLike = null
                    this.isLiked = false
                }
            },
            saves(newValue){
                if (!newValue) {
                    this.mySave = null
                    this.isSaved = false
                }
            },
            attachments(newValue){
                if (!newValue) {
                    this.myAttachments = null
                    this.isAttached = false
                }
            },
        },
        computed: {
            ...mapGetters(['getUser','getProfiles','profile/getMsg']),
            computedLikes(){
                //do not show like if any of your profiles has liked the item
                if (this.getUser) {
                    if (this.post && this.post.hasOwnProperty('likes')){
                        let likes = this.post.likes
                        this.likes = this.post.likes.length
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
            computedSaves(){
                if (this.getUser) {
                    if (this.post && this.post.hasOwnProperty('saves')){
                        let saves = this.post.saves
                        this.saves = this.post.saves.length
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
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedOwner(){
                let profiles = this.getProfiles
                let profile = null

                if (profiles) {
                    
                    profile =  profiles.findIndex(el=>{
                        return this.post.postedby_id === el.params.accountId && 
                            this.post.postedby_type === el.profile
                    })

                    if (profile > -1) {
                        this.profile = this.getProfiles[profile]
                        return true
                    }
                }
                return false
            },
            computedComments(){
                return this.post && this.post.comments.length > 0 ?
                    _.take(this.post.comments,2) : null
            },
            computedLesson(){
                return this.post.typeName === 'lesson' ? true : false
            },
            computedFileUrl(){

            },
            computedImageUrl(){
                return this.post && this.post.images ? this.post.images[0].url : ''
            },
            computedVideoUrl(){
                return this.post && this.post.videos ? this.post.videos[0].url : ''
            },
            computedAudioUrl(){
                return this.post && this.post.audios ? this.post.audios[0].url : ''
            },
            computedType(){
                return this.post && this.post.type ?
                    this.post.type[0] : null
            },
            computedTypeName(){
                return this.post && this.post.typeName ?
                    this.post.typeName : null
            },
            computedUrl(){
                return this.post && this.post.hasOwnProperty('profile_url') ?
                    this.post.profile_url : ''
            },
            computedContent() {
                return this.post && this.post.hasOwnProperty('content') ? 
                    strings.content(this.post.content,100) : null
            },
            computedName(){
                return this.post && this.post.hasOwnProperty('postedby') ?
                    this.post.postedby : ''
            },
            computedCreated(){
                return this.post ? dates.createdAt(this.post.created_at) : ''
            },
            computedShowMore(){
                return this.post && this.post.hasOwnProperty('content') &&
                    this.post.content && this.post.content.length > 200 ? true : false
            },
            computedId(){
                return this.post && this.post.hasOwnProperty('id') ?
                    this.post.id : null
            },
            computedAttachments(){ //check attachment .....
                if (this.getUser) {
                    if (this.post && this.post.hasOwnProperty('attachments')){
                        let attachments = []
                        this.attachments = this.post.attachments.length
                        attachments = this.post.attachments.filter(attachment=>{
                            return attachment.user_id === this.getUser.id
                        }).map(attachment=>{
                            return {
                                id: attachment.id,
                                with_type: strings.getAccount(attachment.attachedwith_type),
                                with_id: attachment.attachedwith_id,
                                with: attachment.name,
                            }
                        })

                        this.postAttachments = this.post.attachments.map(attach=>{
                            return {
                                data: {name: attach.name},
                                type: strings.getAccount(attach.attachedwith_type)
                            }
                        })
                        if (attachments.length) {
                            this.isAttached = true
                            this.myAttachments = attachments
                        }
                    }
                }
                return this.showAttach && !this.computedLesson
            },
            computedFlags(){ //check flagging
                if (this.getUser) {
                    if (this.post && this.post.hasOwnProperty('flags')){
                        let flags = this.post.flags
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
            computedPostOwnerAccount(){
                let postOwner = this.post ? {
                    account: strings.getAccount(this.post.postedby_type),
                    accountId: `${this.post.postedby_id}`
                } : {}

                return postOwner
            },
        },
        methods: {
            ...mapActions(['profile/deletePost','profile/updatePost',
                'profile/createLike','profile/deleteLike','profile/createFlag',
                'profile/deleteFlag','profile/deleteSave','profile/createSave',
                'profile/createAttachment','profile/deleteAttachment']),
            clickedShowPostPreview(data){
                this.$emit('clickedShowPostPreview',data)
            },
            clickedEditIcon(){
                this.showOptions = !this.showOptions
                this.showAttach = false
            },
            clickedMedia(data){
                this.$emit('clickedMedia',data)
            },
            clickedPostMedia(url,mediaType){
                this.clickedMedia({url,mediaType})
            },
            postModalCommentCreated(data){
                if (this.postMediaFull) {
                    this.$emit('postModalCommentCreated',data)
                }
            },
            clickedShowPostComments(){
                this.$emit('clickedShowPostComments',{post: this.post,type:'post'})
            },
            clickedProfilePicture(){
                if (this.$route.name !== 'profile' &&
                    this.computedPostOwnerAccount) {
                    this.$router.push({
                        name: 'profile',
                        params: {
                            account: this.computedPostOwnerAccount.account,
                            accountId: this.computedPostOwnerAccount.accountId,
                        }
                    })
                } else if (this.computedPostOwnerAccount) {
                    if (this.$route.params.account !== this.computedPostOwnerAccount.account &&
                        this.$route.params.accountId !== this.computedPostOwnerAccount.accountId) {
                        this.$router.push({
                        name: 'profile',
                        params: {
                            account: this.computedPostOwnerAccount.account,
                            accountId: this.computedPostOwnerAccount.accountId,
                        }
                    })
                    }
                }
            },
            askLoginRegister(){
                this.$emit('askLoginRegister','postShow')
            },
            clickedInfoOk(){
                this.showSmallModal = false
            },
            clickedAddComment(){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','postShow')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can comment.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                } else {
                    this.showAddComment = true
                }
            },
            profilesAppear(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            clearSmallModal(){
                this.showSmallModal = false
                this.showProfilesAction = ''
                this.smallModalDelete = false
                this.smallModalTitle = ''
                this.alertSuccess = false
                this.alertDanger = false
                // this.smallModalAlerting = false
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
                    this.$emit('askLoginRegister','postShow')
                } else if (!this.getProfiles.length) { // to ensure that people with no profiles dont like/comment/flag
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can flag.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                } else {
                    this.showFlagReason = true
                }
            },
            async flag(who){
                this.loading = true
                let data = {}
                data.where = this.$route.name
                data.post = true
                data.itemId = this.post.id
                let response = null
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    data.item = 'post'
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
                        this.$emit('postUnflaggedSuccess', {
                            flag: response.flag,
                            answerId: this.post.id
                        })
                    } else {
                        this.alertModalMessage = 'successfully flagged'
                        this.$emit('postDeleteSuccess',{postId: data.itemId})
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
            async clickedLike(){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','postShow')
                } else if (!this.getProfiles.length) {
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can like.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
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
                                item: 'post',
                                itemId: this.post.id,
                                owner: this.post.postedby_type,
                                ownerId: this.post.postedby_id,
                            }

                            newData.where = this.$route.name
                            let response = await this['profile/deleteLike'](newData)
                            if (response === 'unsuccessful') {
                                this.isLiked = true
                                this.likes += 1
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
            ///attachments
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
                // this.showProfiles = true
                this.profilesAppear()
            },
            async attach(who){

                this.loading = true
                let data = {},
                    response = null,
                    state = ''

                data.where = this.$route.name
                data.item = 'post'
                data.itemId = this.post.id
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
                setTimeout(() => {
                    this.clearAlert()
                }, 3000);
            },
            clearAlert(){
                this.alertSuccess = false
                this.alertDanger = false
                this.alertMessage = ''
            },
            postAddComplete(data){
                if (data === 'successful') {
                    this.showAddComment = false
                    this.commentSuccess = true
                    setTimeout(() => {
                        this.commentSuccess = false
                    }, 2000);
                } else {
                    this.commentFail = true
                    setTimeout(() => {
                        this.commentFail = false
                    }, 2000);
                }
            },
            async like(who){
                this.showProfiles = false
                this.isLiked = true
                this.likes += 1
                let data = {
                    item: 'post',
                    itemId: this.post.id,
                    account: who.account,
                    accountId: who.accountId,
                    owner: this.post.postedby_type,
                    ownerId: this.post.postedby_id,
                }

                data.where = this.$route.name
                let response = await this['profile/createLike'](data)

                if (response === 'unsuccessful') {
                    this.isLiked = false
                    this.likes -= 1
                }
            },
            async save(who){
                this.showProfiles = false
                this.loading = true
                let data = {
                    item: 'post',
                    itemId: this.post.id,
                    owner: 'post',
                    ownerId: this.post.id,
                },
                    response = null,
                    state = ''

                data.where = this.$route.name
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
                    } else {
                        this.saves -= 1
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
            clickedProfile(who){
                this.showProfiles = false
                if (this.showProfilesAction === 'like') {
                    this.like(who)
                } else if (this.showProfilesAction === 'save') {
                    this.save(who)
                } else if (this.showProfilesAction === 'flag') {
                    this.smallModalTitle = 'are you sure you want to flag this?'
                    this.smallModalDelete = true
                    this.showSmallModal = true
                    this.smallModalData = who
                    // setTimeout(() => {
                    //     this.clearSmallModal()
                    // }, 4000);
                } else if (this.showProfilesAction === 'attach'){
                    this.attach(who)
                }
            },
            async clickedEdit(data){
                let otherData = {},
                    formData = new FormData
                this.createPostLoading = true

                // if (!this.showPreview) {
                if (data.hasOwnProperty('contentFile') && data.contentFile) {
                    formData.append('file', data.contentFile)  
                } else {
                    console.log('enters',data)
                    if (data.type !== '' && data.type !== null) {
                        formData.append('type', this.post.typeName)
                        formData.append('typeId', this.post.type[0].id)
                    }
                    
                    if (data.type === 'book') {
                        formData.append('title', data.title)
                        formData.append('author', data.author)
                        formData.append('about', data.about)
                        formData.append('published', data.published)
                    } else if (data.type === 'poem') {
                        formData.append('title', data.title)
                        formData.append('author', data.author)
                        formData.append('about', data.about)
                        formData.append('sections', JSON.stringify(data.sections))
                        formData.append('published', data.published)
                    } else if (data.type === 'riddle') {
                        formData.append('author', data.author)
                        formData.append('riddle', data.riddle)
                        formData.append('published', data.published)
                    } else if (data.type === 'question') {
                        formData.append('question', data.question)
                        formData.append('published', data.published)
                    } else if (data.type === 'activity') {
                        formData.append('description', data.description)
                        formData.append('published', data.published)
                    }

                    if (data && data.file &&
                        data.file.length > 0) {
                        formData.append('previewFile', data.file[0])
                        formData.append('previewFileType', files.fileType(data.file[0]))
                    }
                }
                formData.append('content', data.content)                
                
                otherData['postId'] = this.post.id
                otherData['account'] = this.profile.params.account
                otherData['accountId'] = this.profile.params.accountId
                otherData['where'] = this.$route.name

                let main = {
                    otherData, formData
                }
                
                let response = await this['profile/updatePost'](main)

                this.createPostLoading = false
                if (response === 'successful') {
                    this.showEdit = false
                } else {

                }
            },
            async clickedYes(){
                if (this.showProfilesAction === 'flag') {
                    this.flag(this.smallModalData)
                    return
                }
                this.smallModalLoading = true
                let data = {
                    postId: this.post.id,
                    account: this.profile.params.account,
                    accountId: this.profile.params.accountId,
                }
                data.where = this.$route.name
                let response = await this['profile/deletePost'](data)
                
                if (response === 'successful') {
                    this.alertSuccess = true
                    this.alertDanger = false
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                }
                this.smallModalLoading = false
                
                if (this['profile/getMsg']) {
                    this.smallModalAlerting = true
                    this.alertMessage = this['profile/getMsg']

                    setTimeout(() => {
                        this.smallModalAlerting = false
                        this.showSmallModal = false
                        this.clearAlert()
                        if (this.postMediaFull) {
                            this.$emit('postDeleteSuccess',{postId: data.postId})
                        }
                    }, 2000);
                }
            },
            clickedNo(){
                this.clearSmallModal()
            },
            clickedOption(data) {
                if (data === 'edit') {
                    this.showEdit = true
                } else if (data === 'attach') {
                    this.showAttach = true
                } else if (data === 'save') {
                    if (this.isSaved) {
                        this.save(null)
                        return
                    }
                    this.showProfilesText = 'save as'
                    this.showProfilesAction = 'save'
                    this.profilesAppear()
                } else if (data === 'delete') {
                    this.smallModalTitle = 'are you sure you want to delete this'
                    this.smallModalInfo = false
                    this.smallModalDelete = true
                    this.showSmallModal = true
                }
                this.showOptions = false
            }
        },
    }
</script>

<style lang="scss" scoped>
@mixin text-overflow(){
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

    .loading,
    .alert{
        width: 100%;
        text-align: center;
        padding: 5px;
    }

    .alert{
        font-size: 12px;
        color: white;
    }

    .success{
        background-color: green;
    }

    .danger{
        background-color: red;
    }

    .post-attachment{
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
        z-index: 1;
    }

    .post-show-wrapper{
        min-height: 200px;
        border: 1px solid dimgrey;
        padding: 10px;
        font-size: 1.5vw;
        margin: 10px auto;
        position: relative;
        border-right: 2px solid rgb(105, 105, 105);

        .edit{
            font-size: 16px;
            margin-top: -10px;
            cursor: pointer;
            text-align: end;
        }

        .top{
            display: flex;
            justify-content: space-between;
            align-items: center;

            .name{
                font-weight: 500;
                font-size: 18px;
                cursor: pointer;
                width: 60%;
                @include text-overflow();
            }

            .created{
                font-weight: 400;
                color: rgba(150, 150, 150, 1);
                font-size: 11px;
                text-align: end;
                width: 40%;
                @include text-overflow();
            }
        }

        .body{
            width: 100%;
            margin: 0 0 0 auto;
            min-height: 50px;
            text-align: justify;
            padding: 10px;
            font-size: 1.2vw;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            .creator{
                width: 75px;
                height: 75px;
                background-color: antiquewhite;
                border-radius: 100%;
            }

            .other{
                width: 75%;
                height: auto;

                .post-media{
                    width: 100%;
                    max-height: 150px;
                    overflow: hidden;
                    padding-left: 10px;

                    img,
                    video,
                    audio{
                        width: inherit;
                        height: auto;
                    }
                }

                .postMediaFull{
                    max-height: none;
                    overflow: visible;
                }
            }

            .text{
                width: 90%;
                margin: 0 0 10px auto;
            }

            .text-short{
                height: 90;

                & > div:first-child{
                    width: 90%;
                    margin: 0 auto;
                    height: 100px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                & > div:nth-child(2){
                    text-align: end;
                    font-weight: 400;
                    color: rgba(150, 150, 150, 1);
                    font-size: 12px;
                    cursor: pointer;
                }
            }
        }

        .bottom{
            min-height: 50px;
            border-top: 1px solid dimgrey;
            margin: 10px 0 0;
            width: 100%;
            

            .main{
                width: 100%;
                padding-top: 5px;

                .reaction{
                    display: flex;
                    justify-content: space-between;
                    align-items: center; 
                    position: relative;               

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

                            .comment{
                                cursor: pointer;
                                font-size: 16px;
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

                            .success{
                                color: green;
                                box-shadow: 0 0 3px gray;
                            }

                            .fail{
                                color: red;
                                box-shadow: 0 0 3px gray;
                            }
                        }

                        .unsetPosition{
                            position: unset;
                        }
                    }

                    .reason{
                        position: absolute;
                        top: 100%;
                        z-index: 3;
                    }
                }

                .unsetPosition{
                    position: unset;
                }

                .comment-section{
                    width: 85%;
                    margin: 5px 0 0 auto;
                }

                .add-comment{
                    width: 75%;
                    position: relative;
                    margin: 10px 0 0 auto;
                }
            }
        }

        .attachments-section{
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
    .post-show-wrapper{
        font-size: 2.2vw;

        .body{
            font-size: 2vw;

            .creator{
                width: 60px;
                height: 60px;
                top: 45px;
            }

            .text{
                width: 90%;
                margin: 0 0 10px auto;
            }
        }
    }
}


@media screen and (max-width:800px){
    .post-show-wrapper{
        font-size: 2.4vw;

        .top{
            .name{
                font-size: 16px;
            }
        }

        .body{
            font-size: 2.2vw;

            .creator{
                width: 60px;
                height: 60px;
            }

            .media{
                width: 100%;

                img{
                    width: inherit;
                }
            }

            .text{
                width: 90%;
                margin: 0 0 10px auto;
            }

            .text-short{

                & > div:nth-child(2){
                    font-size: 11px;
                }
            }
        }
    }
}
</style>