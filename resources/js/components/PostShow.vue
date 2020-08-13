<template>
    <div class="post-show-wrapper"
        @dblclick.self="clickedShowPostComments"
    >
        <div class="edit"
            @click="showOptions = computedOwner"
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
            @dblclick="clickedShowPostComments"
        >
            <div class="creator"
                @click="clickedProfilePicture"
            >
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
                <div class="text-short">
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
        <template v-if="computedType">
            <post-preview
                :type="computedType"
                :typeName="computedTypeName"
                :typeMediaFull="postMediaFull"
                @clickedMedia="clickedMedia"
                @clickedShowPostPreview="clickedShowPostPreview"
                :post="post"
            ></post-preview>
        </template>
        <div class="bottom">
            <div class="main">
                <div class="reaction">
                    <post-button 
                        titleText="flag this"
                        v-if="computedFlag"
                        @click="clickedFlag"
                    >
                        <template slot="icon">
                            <font-awesome-icon
                                :icon="['fa','flag']"
                            ></font-awesome-icon>
                        </template></post-button>
                    <div class="like">
                        <number-of>
                            {{`${likes} likes`}}
                        </number-of>
                        <div class="others">
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
                                    like as
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
                <div class="comment-section">
                    <template v-if="!postMediaFull && computedComments">
                        <comment-single
                            :key="key" v-for="(comment, key) in computedComments"
                            :comment="comment"
                            @askLoginRegister="askLoginRegister"
                            @clickedMedia="clickedMedia"
                        ></comment-single>
                    </template>
                </div>
            </div>
        </div>
        <just-fade>
            <template slot="transition" v-if="showEdit">
                <create-post
                    :edit="showEdit"
                    :editableData="post"
                    :showForm="showEdit"
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
import NumberOf from './NumberOf'
import ProfileBar from './profile/ProfileBar'
import CreatePost from './forms/CreatePost'
import FadeUp from './transitions/FadeUp'
import JustFade from './transitions/JustFade'
import {dates, strings, files} from '../services/helpers'
import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'PostShow',
        components: {
            ProfilePicture,
            JustFade,
            FadeUp,
            CreatePost,
            ProfileBar,
            NumberOf,
            PostPreview,
            MainTextarea,
            AddComment,
            PostButton,
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
                showSmallModal: false,
                smallModalLoading: false,
                smallModalAlerting: false,
                alertSuccess: false,
                alertDanger: false,
                smallModalDelete: false,
                smallModalTitle: '',
                smallModalInfo: false,
                showAddComment: false,
                isLiked: false,
                likes: 0,
                myLike: null,
                showProfiles: false,
                likeTitle: '',
                commentSuccess: false,
                commentFail: false,
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
                    this.likeTitle = 'unlike this comment'
                } else {
                    this.likeTitle = 'like this comment'
                }
            }
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
                    } else {
                        this.profile = null
                    }
                }
                return false
            },
            computedComments(){
                return this.post && this.post.comments.length > 0 ?
                    this.post.comments : null
            },
            computedFileUrl(){

            },
            computedImageUrl(){
                return this.post && this.post.images ? this.post.images.data[0].url : ''
            },
            computedVideoUrl(){
                return this.post && this.post.videos ? this.post.videos.url : ''
            },
            computedAudioUrl(){
                return this.post && this.post.audios ? this.post.audios.url : ''
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
            computedFlag(){
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
            clickedShowPostPreview(data){
                this.$emit('clickedShowPostPreview',data)
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
                if (this.$route.name !== 'profile') {
                    this.$router.push({
                        name: 'profile',
                        params: {
                            account: this.computedPostOwnerAccount.account,
                            accountId: this.computedPostOwnerAccount.accountId,
                        }
                    })
                } else {
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
            ...mapActions(['profile/deletePost','profile/updatePost',
                'profile/createLike','profile/deleteLike']),
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
                        this.showSmallModal = false
                    }, 4000);
                } else {
                    this.showAddComment = true
                }
            },
            clickedFlag(){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','postShow')
                } else if (!this.getProfiles.length) { // to ensure that people with no profiles dont like/comment/flag
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can flag.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.showSmallModal = false
                    }, 4000);
                } else {

                }
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
                        this.showSmallModal = false
                    }, 4000);
                } else {
                    if (this.isLiked) {
                        this.likes -= 1
                        this.isLiked = false
                        
                        if (this.myLike && this.myLike.hasOwnProperty('id')) {
                            let data = {
                                likeId: this.myLike.id,
                                item: 'post',
                                itemId: this.post.id,
                                owner: this.post.postedby_type,
                                ownerId: this.post.postedby_id,
                            }

                            let response = await this['profile/deleteLike'](data)
                            if (response === 'unsuccessful') {
                                this.isLiked = true
                                this.likes += 1
                            }
                        } else {
                            this.likes += 1
                            this.isLiked = true
                        }
                    } else {
                        this.showProfiles = true
                        setTimeout(() => {
                            this.showProfiles = false
                        }, 4000);
                    }
                }
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
            async clickedProfile(who){
                this.isLiked = true
                this.likes += 1
                // console.log('who',who)
                let data = {
                    item: 'post',
                    itemId: this.post.id,
                    account: who.account,
                    accountId: who.accountId,
                    owner: this.post.postedby_type,
                    ownerId: this.post.postedby_id,
                }

                let response = await this['profile/createLike'](data)

                if (response === 'unsuccessful') {
                    this.isLiked = false
                    this.likes -= 1
                }
                this.showProfiles = false
            },
            async clickedEdit(data){
                let otherData = {}
                let formData = new FormData

                // if (!this.showPreview) {
                if (data.hasOwnProperty('contentFile') && data.contentFile) {
                    formData.append('file', data.contentFile)  
                } else {
                    console.log('enters',data)
                    if (data.type !== '') {
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

                let main = {
                    otherData, formData
                }
                
                let response = await this['profile/updatePost'](main)

                if (response === 'successful') {
                    this.showEdit = false
                } else {

                }
            },
            async clickedYes(){
                this.smallModalLoading = true
                let data = {
                    postId: this.post.id,
                    account: this.profile.params.account,
                    accountId: this.profile.params.accountId,
                }
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
                    }, 2000);
                }
            },
            clickedNo(){
                this.showSmallModal = false
            },
            clickedOpion(data) {
                if (data === 'edit') {
                    this.showEdit = true
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
                width: 90px;
                height: 90px;
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

                    .like{
                        display: inline-flex;
                        align-items: center;
                        justify-content: space-between;
                        width: 50%;  

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
                    }
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
    }


@media screen and (min-width:800px) and (max-width:1100px){
    .post-show-wrapper{
        font-size: 2.2vw;

        .body{
            font-size: 2vw;

            .creator{
                width: 70px;
                height: 70px;
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
                width: 70px;
                height: 70px;
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