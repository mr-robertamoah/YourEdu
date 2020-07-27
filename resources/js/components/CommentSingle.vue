<template>
    <div class="wrapper" v-if="showCommentSingle">
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
            <div class="name">
                {{computedName}}
            </div>
            <div class="created" v-if="computedCreatedAt">
                {{computedCreatedAt}}
            </div>
        </div>
        <div class="middle">
            <div class="profile-picture">
                <profile-picture>
                    <template slot="image">
                        <img :src="computedProfileUrl">
                    </template>
                </profile-picture>
            </div>
            <div class="comment-body">
                <div class="text" v-if="computedBody">
                    {{computedBody}}
                </div>
                <div class="media" v-if="computedImageUrl.length">
                    <img :src="computedImageUrl">
                </div>
                <div class="media" v-if="computedAudioUrl.length">
                    <audio :src="computedAudioUrl" controls controlslist="nodownload">
                    </audio>
                </div>
                <div class="media" v-if="computedVideoUrl.length">
                    <video :src="computedVideoUrl" controls controlslist="nodownload">
                    </video>
                </div>
            </div>
        </div>
        <div class="bottom">
            <number-of>
                {{`${likes} likes`}}
            </number-of>
            <div class="comment-number" 
                v-if="computedCommentNumber"
                @click="clickedViewComments"
                :title="commentTitle"
            >
                {{`${comments} comments`}}
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
                    v-if="!showAddComment"
                    @click="clickedAddComment"
                    :class="{success:commentSuccess,fail:commentFail}"
                >
                    <font-awesome-icon
                        :icon="['fa','comment']"
                    ></font-awesome-icon>
                </div>
                <div class="comment" 
                    v-if="computedFlag"
                    @click="clickedFlag"
                >
                    <font-awesome-icon
                        :icon="['fa','flag']"
                    ></font-awesome-icon>
                </div>
            </div>
        </div>
            <div class="comment-section" 
                >
                <add-comment
                    what="comment"
                    :id="comment.id"
                    :showAddComment="showAddComment"
                    @hideAddComment="showAddComment = false"
                    @postAddComplete="postAddComplete"
                    :editableData="comment"
                    :edit="editComment"
                ></add-comment>
            </div>
        <!-- <just-fade>
            <template slot="transition" v-if="showEdit">
                <main-modal
                    @mainModalDisappear="showEdit = false"
                >
                    <template slot="main">
                        <template
                            v-if="showAlert">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                @hideAlert="alertDisappear"
                            ></auto-alert>
                        </template>
                        <welcome-form v-if="!showAlert">
                            <template slot="input">
                                <add-comment
                                    what="comment"
                                    :id="comment.id"
                                    :edit="true"
                                    :editableData="comment"
                                    :showAddComment="true"
                                ></add-comment>
                            </template>
                        </welcome-form>
                    </template>
                </main-modal>
            </template>
        </just-fade> -->
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    title="are you sure you want to delete this"
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
                            buttonText="yes"
                            @click="clickedYes"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedNo"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>
        <!-- <view-comments
            @viewModalDisappear="viewModalDisappear"
            :show="showViewComments"
            :comment="comment"
        ></view-comments> -->
    </div>
</template>

<script>
import PostButton from './PostButton'
import ProfilePicture from './profile/ProfilePicture'
import NumberOf from './NumberOf'
import SmallModal from './SmallModal'
import WelcomeForm from './welcome/WelcomeForm'
import MainModal from './MainModal'
import AddComment from './AddComment'
import ProfileBar from './profile/ProfileBar'
import AutoAlert from './AutoAlert'
import JustFade from './transitions/JustFade'
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
            MainModal,
            WelcomeForm,
            SmallModal,
            NumberOf,
            ProfilePicture,
            PostButton,
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
            }
        },
        props: {
            comment: {
                type: Object,
                default(){
                    return {}
                },
            },
            showCommentNumber: {
                typpe: Boolean,
                default: true
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
            showEdit(newValue){
                if (newValue) {
                    this.editComment = true
                    this.showAddComment = true
                } else {
                    this.editComment = false
                }
            },
        },
        computed: {
            ...mapGetters(['getUser','getProfiles','profile/getMsg']),
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
            // computedComments(){
            //     return this.comment && this.comment.comments ? 
            //         this.comment.comments.length : -1
            // },
            computedLikes(){
                //do not show like if any of your profiles has liked the item
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
                    return true
                } else {
                    return false
                }
            },
            computedOwner(){
                let profiles = this.getProfiles
                let profile = null

                if (profiles) {
                    
                    profile =  profiles.findIndex(el=>{
                        return this.comment.commentedby_id === el.params.accountId && 
                            this.comment.commentedby_type === el.profile
                    })

                    if (profile >= 0) {
                        this.profile = this.getProfiles[profile]
                        return true
                    } else {
                        this.profile = null
                    }
                }
                return false
            },
            computedFlag(){
                return true
            },
        },
        methods: {
            ...mapActions(['profile/deleteComment','profile/createLike'
                ,'profile/deleteLike']),
            postAddComplete(data){
                if (data === 'successful') {
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
            clickedFlag(){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','commentSingle')
                } else {

                }
            },
            clickedViewComments(){
                // if (this.comments) {
                //     this.showViewComments = true
                // }
                this.$router.push({
                    name: 'comments',
                    params:{
                        comment: this.comment,
                        commentId: this.comment.id,
                    }
                })
            },
            async clickedProfile(who){
                this.isLiked = true
                this.likes += 1
                // console.log('who',who)
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
                }
                this.showProfiles = false
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
                } else {
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
            clickedAddComment(){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','commentSingle')
                } else {
                    this.editComment = false
                    this.showAddComment = true
                }
            },
            async clickedYes(){
                this.smallModalLoading = true
                let data = {
                    commentId: this.comment.id,
                    owner: this.comment.commentable_type,
                    ownerId: this.comment.commentable_id,
                    account: this.profile.params.account,
                    accountId: this.profile.params.accountId,
                }
                let response = await this['profile/deleteComment'](data)
                
                if (response === 'successful') {
                    this.alertSuccess = true
                    this.showCommentSingle = false
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
                    this.showSmallModal = true
                }
                this.showOptions = false
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
    .wrapper{
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
                    width: 90%;
                    margin: 5px 0 10px auto;

                    img{
                        width: 70%;
                    }

                    video,
                    audio{
                        width: 90%;
                    }
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

        .comment-section{
            margin: 5px 5px 5px 0;
        }
    }

@media screen and (min-width: 800px) and (max-width: 1100px){
    $profile-picture-width: 35px;
    $comment-font-size: 12px;

    .wrapper{
        
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

    .wrapper{
        
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