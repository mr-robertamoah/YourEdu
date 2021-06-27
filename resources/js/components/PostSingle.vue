<template>
    <div class="post-single-wrapper"
        @dblclick.self="clickedShowPostComments"
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
        <div class="edit absolute top-2 right-2"
            @click="clickedEditIcon"
            v-if="computedProfiles.length"
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
                :hasEdit="computedIsOwner"
                :hasAttachment="computedProfiles.length ? true : false"
                :hasDelete="computedIsOwner"
                @clickedOption="clickedOption"
            ></optional-actions>
        </div>
        <div class="post-attachment" 
            v-if="attachmentData.showAttach && !computedLesson"
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
                :profileUrl="post.addedby.url"
                :full="postMediaFull"
                @clickedMedia="clickedMedia"
                :post="post"
            ></lesson-preview>
        </template>
        <div class="bottom">
            <reaction-component
                :comments="computedComments"
                :item="computedItem"
                :isOwner="computedIsOwner"
                :full="postMediaFull"
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
                @postModalCommentCreated="postModalCommentCreated"
            ></reaction-component>
        </div>
        <div class="attachments-section">
            <attachment-badge 
                v-for="(attachment,index) in attachmentData.postAttachments"
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
import MainTextarea from './MainTextarea'
import PostPreview from './PostPreview'
import LessonPreview from './LessonPreview'
import NumberOf from './NumberOf'
import CreatePost from './forms/CreatePost'
import OptionalActions from './OptionalActions'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import FadeUp from './transitions/FadeUp'
import JustFade from './transitions/JustFade'
import Like from '../mixins/Like.mixin';
import Flag from '../mixins/Flag.mixin';
import Save from '../mixins/Save.mixin';
import Alert from '../mixins/Alert.mixin';
import Participation from '../mixins/Participation.mixin';
import Profiles from '../mixins/Profiles.mixin';
import Attachments from '../mixins/Attachments.mixin';
import SmallModal from '../mixins/SmallModal.mixin'
import Comments from '../mixins/Comments.mixin'
import {dates, strings, files} from '../services/helpers'
import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'PostSingle',
        components: {
            ProfilePicture,
            JustFade,
            FadeUp,
            OptionalActions,
            CreatePost,
            NumberOf,
            LessonPreview,
            PostPreview,
            MainTextarea,
            PostButton,
            PulseLoader,
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
            Profiles, Attachments,
        ],
        data() {
            return {
                showOptions: false,
                showEdit: false,
                profile: null,
                showModal: false,
                loading: false,
                createPostLoading: false,
                //
                commentSuccess: false,
                commentFail: false,
            }
        },
        watch: {
            computedItemable(newValue){
                if (newValue) {
                    
                    this.setMyFlag()
                }
            },
            "post.likes": {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        
                        this.likeData.likes = newValue.length
                    }
                }
            },
            "post.saves": {
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
            this.setMyAttachment()
            this.listen()
            this.listenForComments()
            this.listenForLikes()
            this.listenForFlags()
            this.listenForSaves()
            this.listenForAttachments()
        },
        computed: {
            ...mapGetters(['getUser','profile/getMsg']),
            computedIsOwner() {
                return this.post.addedby.userId === this.getUser?.id
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
            computedOwner(){
                if (!this.getProfiles) {
                    return null
                }
                    
                let index =  profiles.findIndex(profile=>{
                    return this.post.addedby.accountId === profile.accountId && 
                        this.post.addedby.account === profile.account
                })

                if (index > -1) {
                    return this.getProfiles[index]
                }
                
                return null
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
                return this.post && this.post.hasOwnProperty('addedby') ?
                    this.post.addedby.url : ''
            },
            computedContent() {
                return this.post && this.post.hasOwnProperty('content') ? 
                    strings.trim(this.post.content,100) : null
            },
            computedName(){
                return this.post && this.post.hasOwnProperty('addedby') ?
                    this.post.addedby.name : ''
            },
            computedCreated(){
                return this.post ? dates.createdAt(this.post.created_at) : ''
            },
            computedShowMore(){
                return this.post && this.post.hasOwnProperty('content') &&
                    this.post.content && this.post.content.length > 200 ? true : false
            },
            computedItem(){
                return {
                    item: 'post',
                    itemId: this.post.id,
                    postType: this.post.typeName
                }
            },
            computedItemable() {
                return this.post
            },
            computedPostOwnerAccount(){
                let postOwner = this.post ? {
                    account: this.post.addedby.account,
                    accountId: `${this.post.addedby.accountId}`
                } : {}

                return postOwner
            },
        },
        methods: {
            ...mapActions([
                'profile/deletePost','profile/updatePost',
                'home/removePost','home/replacePost',
                'profile/removePost','profile/replacePost',
            ]),
            listen() {
                
                Echo.channel(`youredu.post.${this.post.id}`)
                    .listen('.updatePost', data=>{
                        this[`${this.$route.name}/replacePost`](data.post)
                    })
                    .listen('.deletePost', data=>{
                        this[`${this.$route.name}/removePost`](data)
                    })
            },
            clickedShowPostPreview(data){
                this.$emit('clickedShowPostPreview',data)
            },
            clickedEditIcon(){
                if (this.disabled) {
                    return
                }
                this.showOptions = !this.showOptions
                this.attachmentData.showAttach = false
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
                this.$emit('askLoginRegister','post')
            },
            clickedInfoOk(){
                this.showSmallModal = false
            },
            clickedAddComment(){
                if (this.disabled) {
                    return
                }

                if (!this.getUser) {
                    this.askLoginRegister()
                    return
                }
                
                if (!this.getProfiles || !this.getProfiles.length) {
                    this.issueSmallModalInfoMessage({message: 'you must have an account (eg. learner, parent, etc) before you can comment.'})
                    
                    this.clearSmallModal(false)

                    return
                }

                this.showAddComment = true
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
            clickedProfile(who){
                this.showProfiles = false

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
                        type: 'delete',
                        message: 'are you sure you want to flag this?',
                        data: who
                    })
                    return
                }
                
                if (this.showProfilesAction === 'attach'){
                    this.attach(who)
                }
            },
            async clickedEdit(data){
                let otherData = {},
                    formData = new FormData
                this.createPostLoading = true

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
                        formData.append('authorNames', data.author)
                        formData.append('about', data.about)
                        formData.append('published', data.published)
                    } else if (data.type === 'poem') {
                        formData.append('title', data.title)
                        formData.append('authorNames', data.author)
                        formData.append('about', data.about)
                        formData.append('sections', JSON.stringify(data.sections))
                        formData.append('published', data.published)
                    } else if (data.type === 'riddle') {
                        formData.append('authorNames', data.author)
                        formData.append('body', data.body)
                        formData.append('published', data.published)
                    } else if (data.type === 'question') {
                        formData.append('body', data.body)
                        formData.append('published', data.published)
                    } else if (data.type === 'activity') {
                        formData.append('description', data.description)
                        formData.append('published', data.published)
                    }

                    if (data && data.file &&
                        data.file.length > 0) {
                        formData.append('typeFiles', data.file[0])
                        formData.append('typeFilesType', files.fileType(data.file[0]))
                    }
                }
                formData.append('content', data.content)
                formData.append('account',this.computedOwner.account)
                formData.append('accountId',this.computedOwner.accountId)
                formData.append('postId',this.post.id)
                
                otherData['postId'] = this.post.id
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
            clickedYes(){
                if (this.showProfilesAction === 'flag') {
                    this.flag(this.smallModalData)
                    return
                }

                this.deletePost()
            },
            async deletePost(){
                this.loading = true
                let data = {
                    postId: this.post.id,
                    account: this.computedOwner.account,
                    accountId: this.computedOwner.accountId,
                }
                data.where = this.$route.name
                let response = await this['profile/deletePost'](data)
                
                if (response === 'successful') {
                    this.alertSuccess = true
                } else {
                    this.alertDanger = true
                }

                this.loading = false
                
                if (! this['profile/getMsg']) {
                    return
                }

                this.alertMessage = this['profile/getMsg']

                if (this.postMediaFull) {
                    this.$emit('postDeleteSuccess',{postId: data.postId})
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
                
                if (data === 'attach') {
                    this.attachmentData.showAttach = true
                    return
                }
                
                if (data === 'delete') {
                    this.issueSmallModalDeletionMessage()
                    return
                }
                
                if (data !== 'save') {
                    return
                }

                if (this.saveData.isSaved) {
                    this.save(null)
                    return
                }

                this.clickedSave()
            }
        },
    }
</script>

<style lang="scss" scoped>

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

    .post-single-wrapper{
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
    .post-single-wrapper{
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
    .post-single-wrapper{
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