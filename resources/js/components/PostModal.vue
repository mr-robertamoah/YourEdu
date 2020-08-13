<template>
    <div class="post-modal-wrapper" v-if="show" @click.self="disappear">
        <div class="main-modal">
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <div class="main">
                <fade-right-fast>
                    <template slot="transition" v-if="alertMessage.length">
                        <div class="alert-message"
                            v-if="alertMessage.length"
                            :class="{success:alertSuccess,danger:alertDanger}"
                        >
                            {{alertMessage}}
                        </div>
                    </template>
                </fade-right-fast>
                <div class="post"
                    v-if="type === 'post'"
                >
                    <post-show
                        :post="data"
                        :postMediaFull="true"
                        @postModalCommentCreated="postModalCommentCreated"
                        @clickedShowPostComments="clickedShowPostComments"
                        @clickedShowPostPreview="clickedShowPostPreview"
                        @clickedMedia="clickedMedia"
                    ></post-show>
                </div>
                <div class="answer"
                    v-if="data.typeName === 'answer'"
                >
                    <answer-single
                        :answerFull="true"
                        :answer="data.type"
                    ></answer-single>
                </div>
                <div class="post-preview"
                    v-if="type === 'posttype'"
                >
                    <post-preview
                        :type="data.type"
                        :typeName="data.typeName"
                        :owner="data.owner"
                        :typeMediaFull="true"
                        @clickedMedia="clickedMedia"
                        @clickedAnswer="clickedAnswer"
                        @clickedShowPostPreview="clickedShowPostPreview"
                        :showButton="showAnswerButton"
                    ></post-preview>
                    <div class="answer-question">
                        <div class="loading" v-if="answerLoading">
                            <pulse-loader :loading="answerLoading"
                                :size="'10px'"
                            ></pulse-loader>
                        </div>
                        <template v-if="showAnswerText">
                            <add-answer
                                :showAddAnswer="showAnswerText"
                                @addAnswer="addAnswer"
                                @hideAddAnswer="hideAddAnswer"
                                @hideProfiles="hideProfiles"
                                :editableData="answerTextEditable"
                                :edit="answerTextEdit"
                            ></add-answer>
                        </template>
                        <template v-if="showAnswerList">
                            <main-list
                                :itemList="data.type.possible_answers"
                                @listItemSelected="listItemSelected"
                                @clickedListButton="clickedListButton"
                                :editableData="answerListEditable"
                                :edit="answerListEdit"
                                select="select your answer"
                                buttonText="submit"
                            ></main-list>
                        </template>
                        <div class="profiles"
                            v-if="showProfiles"
                        >
                            <span>
                                answer as
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
                    </div>
                </div>
            </div>
            <div class="main-comments">
                <div class="no-comments" 
                    v-if="computedNoCommentAnswer">
                    {{noCommentAnswer}}
                </div>
                <template v-else>
                    <slide-up-group>
                        <template slot="transition">
                            <template v-if="comments.length">
                                <comment-single
                                    v-for="comment in comments"
                                    :key="comment.id"
                                    :comment="comment"
                                    :onPostModal="true"
                                    @clickedMedia="clickedMedia"
                                    @commentDeleteSuccess="commentDeleteSuccess"
                                    @postModalCommentCreated="postModalCommentCreated"
                                    @postModalCommentEdited="postModalCommentEdited"
                                    @postAddComplete="comment"
                                ></comment-single>
                            </template>
                            <template v-if="answers.length">
                                <answer-single
                                    v-for="answer in answers"
                                    :key="answer.id"
                                    :answer="answer"
                                    @askLoginRegister="askLoginRegister"
                                    @clickedShowAnswer="clickedShowAnswer"
                                    @askCreateAccount="askCreateAccount"
                                    @answerMarked="answerMarked"
                                    @clickedShowAnswerComments="clickedShowAnswerComments"
                                ></answer-single>
                            </template>
                        </template>
                    </slide-up-group>
                    <div class="loading" v-if="loading">
                        <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
                    </div>
                    <div class="show-more"
                        @click="clickedShowMore"
                        v-if="showMoreComments"
                    >
                        show more
                    </div>
                </template>
            </div>
        </div>
        <!-- for showing single media -->
        <just-fade>
            <template slot="transition" v-if="showMediaModal">
                <media-modal
                    @mainModalDisappear="mediaModalDisappear"
                    :url="mediaUrl"
                    :urlType="mediaUrlType"
                    :justUrl="mediaJustUrl"
                >
                </media-modal>
            </template>
        </just-fade>
        <!-- small modal for getting people to register or login -->
        <fade-up>
            <template slot="transition" v-if="showLoginRegister">
                <small-modal
                    @disappear="showLoginRegister = false"
                    :showForm='showLoginRegister'
                    :title="smallModalTitle"
                >
                    <template slot="other" v-if="showSmallModalOther">
                        <router-link to="/login">login</router-link> or 
                        <router-link to='/register'>register</router-link> to interact and grow in a positve way.
                    </template>
                    <template slot="other" v-if="!showSmallModalOther">
                        <router-link to="/welcome">welcome</router-link>
                    </template>
                </small-modal>
            </template>
        </fade-up>
        <!-- post modal for showing post/type and its comments -->
        <just-fade>
            <template slot="transition" v-if="showPostModal">
                <post-modal
                    @mainModalDisappear="postModalDisappear"
                    :data="postModalData"
                    :type="postModalType"
                >
                </post-modal>
            </template>
        </just-fade>
    </div>
</template>

<script>
import PulseLoader from 'vue-spinner/src/PulseLoader';
import PostShow from './PostShow';
import PostPreview from './PostPreview';
import CommentSingle from './CommentSingle';
import AnswerSingle from './AnswerSingle';
import MainList from './MainList'
import AddAnswer from './AddAnswer'
import ProfileBar from './profile/ProfileBar'
import FadeRightFast from './transitions/FadeRightFast'
import FadeUp from './transitions/FadeUp'
import SlideUpGroup from './transitions/SlideUpGroup'
import { mapActions, mapGetters } from 'vuex';
import { strings } from '../services/helpers';

    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
            },
            type: {
                type: String,
                default: 'post',
            },
            data: {
                type: Object,
                default: null,
            },
            heading: {
                type: String,
                default: ''
            },
        },
        components: {
            PulseLoader,
            AnswerSingle,
            CommentSingle,
            MainList,
            SlideUpGroup,
            FadeUp,
            FadeRightFast,
            ProfileBar,
            AddAnswer,
            PostPreview,
            PostShow,
        },
        data() {
            return {
                nextPage: 1,
                comments: [],
                answers: [],
                loading: false,
                showMoreComments: false,
                noCommentAnswer: '',
                //media modal
                mediaUrl: '',
                mediaJustUrl: true,
                showMediaModal: false,
                mediaJustUrl: true, //for now this will only be receiving url type
                mediaUrlType: '',
                //post modal
                showPostModal: false,
                postModalData: null,
                postModalType: '',
                //for answering
                showAnswerList: false,
                showAnswerText: false,
                inputAnswerList: '',
                inputAnswerText: '',
                answerLoading: false,
                showAnswerButton: false,
                //for editing
                answerListEditable: {},
                answerListEdit: false,
                answerTextEditable: {},
                answerTextEdit: false,
                //alert
                alertMessage: '',
                alertSuccess: false,
                alertDanger: false,
                showProfiles: false,
                file: null,
                //small modal
                showLoginRegister: false,
                showSmallModalOther: false,
                smallModalTitle: '',
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        if (this.data.hasOwnProperty('typeName') && 
                            (this.data.typeName === 'question' || this.data.typeName === 'riddle')) {
                            this.noCommentAnswer = 'there are no answers'
                        } else {
                            this.noCommentAnswer = 'there are no comments'
                        }
                        if (this.data.hasOwnProperty('type') && 
                            this.data.type.hasOwnProperty('possible_answers')) {
                            this.showAnswerList = true
                        }
                        this.getCommentsAnswers()
                    }
                }
            },
            alertMessage(value){
                if (!value.length) {
                    this.alertSuccess = false
                    this.alertDanger = false
                }
            },
            showAnswerList(value){
                if (value) {
                    this.showAnswerButton = false
                }
            },
            showAnswerText(value){
                if (value) {
                    this.showAnswerButton = false
                }
            },
        },
        computed: {
            ...mapGetters(['getProfiles']),
            computedNoCommentAnswer(){
                if (this.data.typeName === 'question' || this.data.typeName === 'riddle') {
                    return !this.answers.length && !this.loading && !this.computedComments
                        ? true : false
                } else {
                    return !this.comments.length && !this.loading && !this.computedComments
                        ? true : false
                }
                
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedHeading(){
                return this.heading.length > 0 ? true : false
            },
            computedComments(){
                return this.post ? this.post.comments_number : 0
            },
        },
        methods: {
            ...mapActions(['profile/getComments','profile/getAnswers',
                'profile/updateAnswer','profile/createAnswer']),
            answerMarked(data){
                let answerIndex = this.answers.findIndex(answer=>{
                    return answer.id === data.id
                })
                if (answerIndex > -1) {
                    this.answers.splice(answerIndex,1,data)
                }
            },
            askCreateAccount(){
                this.smallModalTitle = 'visit your welcome page to create accounts with which to interact.'
                this.showSmallModalOther = false
                this.showLoginRegister = true
                setTimeout(() => {
                    this.showLoginRegister = false
                }, 3000);
            },
            askLoginRegister(){
                this.smallModalTitle = 'welcome to this new community.'
                this.showSmallModalOther = true
                this.showLoginRegister = true
                setTimeout(() => {
                    this.showLoginRegister = false
                }, 3000);
            },
            clickedShowAnswer(data){ //event handler for showing answer as main in post modal

            },
            clickedListButton(who){
                if (this.inputAnswerList.hasOwnProperty('option')) {
                    this.showProfiles = true
                    setTimeout(() => {
                        this.showProfiles = false
                    }, 4000);
                } else {
                    this.alertDanger = true
                    this.alertMessage = 'no option has been selected'
                    setTimeout(() => {
                        this.alertMessage = ''
                    }, 3000);
                }
            },
            hideProfiles(){
                this.showProfiles = false
            },
            hideAddAnswer(){
                this.showAnswerText =  !this.showAnswerText
            },
            addAnswer(data) {
                this.inputAnswerText = data.input
                this.file = data.file
                if (data.who.hasOwnProperty('account')) {

                    this.clickedProfile(data.who)
                } else {
                    if (data.inputText.length) {
                        this.showProfiles = true
                        setTimeout(() => {
                            this.showProfiles = false
                        }, 4000);
                    }
                }
            },
            commentDeleteSuccess(data){
                let commentIndex = this.comments.findIndex(comment =>{
                    return comment.id === data.commentId
                })
                if (commentIndex > -1) {
                    this.comments.splice(commentIndex,1)
                }
            },
            async clickedProfile(who){
                this.showProfiles = false
                this.answerLoading = true
                let formData = new FormData,
                    type = ''

                if (this.file) {
                    formData.append('file', this.file)
                }

                formData.append('account', who.account)
                formData.append('accountId', who.accountId)    
                
                if (this.showAnswerList) {
                    if (this.inputAnswerList && 
                        this.inputAnswerList.hasOwnProperty('id')) {
                        formData.append('answer', this.inputAnswerList.option.trim())
                        formData.append('possible_answer_id', this.inputAnswerList.id)
                    } else {
                        this.alertDanger = true
                        this.alertMessage= 'answer must be given'
                        setTimeout(() => {
                            this.alertMessage = ''
                        }, 3000);
                        return
                    }
                } else if (this.showAnswerText) {
                    if (this.inputAnswerText && this.inputAnswerText.length) {
                        formData.append('answer', this.inputAnswerText.trim())
                    } else {
                        this.alertDanger = true
                        this.alertMessage= 'answer must be given'
                        setTimeout(() => {
                            this.alertMessage = ''
                        }, 3000);
                    }
                }
                
                let response = null
                if (who.hasOwnProperty('itemId')) {
                    
                    let data = {
                        itemId: who.itemId,
                    }
                    type = 'update'
                    response = await this['profile/updateAnswer']({data,formData})
                } else {
                    let data = {
                        item: this.data.typeName,
                        itemId: this.data.type.id,
                    }
                    type = 'create'
                    response = await this['profile/createAnswer']({data,formData})
                }
                
                this.answerLoading = false
                if (response.hasOwnProperty('answer')) {
                    if (type === 'create') {
                        this.answers.unshift(response.answer)
                    } else {
                        let answerIndex = this.answers.findIndex(answer=>{
                            return answer.id === response.answer.id
                        })
                        if (answerIndex > -1) {
                            this.answers.splice(answerIndex,1,response.answer)
                        }
                    }
                    this.clearAnswerData()
                    this.alertSuccess = true
                    this.alertDanger = false
                    this.alertMessage = 'answered successfully'
                    
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                    this.alertMessage = 'answering unsuccessful'
                }
                setTimeout(() => {
                    this.alertMessage = ''
                }, 3000);
            },
            clearAnswerData(){
                this.file = null
                this.inputAnswerText = ''
                this.inputAnswerList = ''
            },
            listItemSelected(data){
                this.inputAnswerList = data
            },
            clickedAnswer(){
                if (this.data.type.possible_answers.length) {
                    this.showAnswerList = true
                } else {
                    this.showAnswerText = false
                }
            },
            postModalDisappear(){
                this.showPostModal = false
            },
            clickedShowPostPreview(data){
                this.postModalData = data.data
                this.postModalType = 'posttype'
                this.showPostModal = true
            },
            clickedShowAnswerComments(data){
                this.postModalData = data.data
                this.postModalType = data.type
                this.showPostModal = true
            },
            clickedShowPostComments(data){
                this.postModalData = data.answer
                this.postModalType = 'post'
                this.showPostModal = true
            },
            clickedMedia(data){
                this.mediaUrl = data.url
                this.mediaUrlType = data.mediaType
                this.showMediaModal = true
            },
            mediaModalDisappear(){
                this.showMediaModal = false
                this.mediaUrl = ''
                this.mediaUrlType = ''
            },
            postModalCommentCreated(comment){
                if (comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                    let commentIndex = this.comments.findIndex(c=>{
                        return c.id === comment.commentable_id
                    })
                    if (comemntIndex > -1) {
                        this.comments[commentIndex].comments += 1
                    }
                } else {
                    this.showMoreComments = false
                    this.comments.unshift(comment)
                }
            },
            postModalCommentEdited(comment){
                //editing comments in the comments view section
                let commentIndex = this.comments.findIndex(c=>{
                    return c.id === comment.commentable_id
                })
                if (comemntIndex > -1) {
                    this.comments.splice(commentIndex,1,comment)
                }
            },
            postModalAnswerCreated(answer){
                this.showMoreComments = false
                this.answers.unshift(answer)
            },
            clickedShowMore(){
                this.getCommentsAnswers()
            },
            async getCommentsAnswers(){
                this.loading = true
                let data = {},
                    response = null
                data.nextPage = this.nextPage
                if (this.type === 'post') {
                    data = {
                        item : 'post',
                        itemId : this.data.id,
                    }
                    response = await this['profile/getComments'](data)
                } else if (this.type === 'posttype') {
                    data = {
                        item : this.data.typeName,
                        itemId : this.data.type.id,
                    }

                    if (this.data.typeName === 'riddle' || 
                        this.data.typeName === 'question') {
                        response = await this['profile/getAnswers'](data)
                    } else {
                        response = await this['profile/getComments'](data)
                    }
                }

                if (this.type === 'posttype' && 
                   ( this.data.typeName === 'riddle' || this.data.typeName === 'question')) {
                    this.answers.push(...response.data.data)
                } else {
                    this.comments.push(...response.data.data)
                }
                this.loading = false
                if (response.status) {
                    this.nextPage += 1
                    this.showMoreComments = true
                } else {
                    this.showMoreComments = false
                }
            },
            disappear() {
                this.$emit('mainModalDisappear')
            },
        },
    }
</script>

<style lang="scss" scoped>
$wrapper-background: rgba(102, 51, 153, .2);
$modal-background: aliceblue;
$modal-width: 60%;
$modal-height: 80vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .post-modal-wrapper{
        position: fixed;
        background-color: $wrapper-background;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        padding: auto;
        z-index: 10000;
        overflow: scroll;

        .main-modal{
            background-color: $modal-background;
            width: $modal-width;
            height: $modal-height;
            position: relative;
            top: $modal-margin-height;
            left: $modal-margin-width;
            border-radius: 10px;
            box-shadow: 1px 1px 1px rgba(105, 105, 105,.6), 
                -1px -1px 1px rgba(105, 105, 105,.6);
            display: block;
            position: relative;
            overflow-y: auto;
            
            .close{
                position: fixed;
                width: 20px;
                top: 15%;
                right: 21%;
                margin: 10px 10px 0 0;
                color: rgba(105, 105, 105,.8);
                cursor: pointer;

                &:hover{
                    color: rgba(255, 0, 0, 0.603);
                }
            }

            .main{
                display: block;
                padding: 20px 10px 0;
                border-bottom: 1px solid gray;

                .alert-message{
                    font-size: 14px;
                    width: 80%;
                    margin: 5px auto;
                }

                .success{
                    color: green;
                }

                .danger{
                    color: red;
                }

                .post,
                .post-preview{ 
                    width: 60%;
                    margin: 20px auto;
                    border-bottom: none;
                }

                .post-preview{
                    border: 1px solid gray;
                    border-right: 2px solid gray;
                    padding-bottom: 10px;
                    position: relative;

                    .loading{
                        text-align: center;
                        width: 100%;
                    }

                    .profiles{
                        position: absolute;
                        width: 200px;
                        left: 0;
                        top: 95%;
                        z-index: 1000;
                        text-align: start;
                    }
                }
            }

            .main-comments{
                width: 55%;
                margin: 10px auto 0;
                overflow-y: auto;
                max-width: 85vh;
                min-height: 70vh;
                padding-bottom: 10px;

                .loading{
                    width: 100%;
                    text-align: center; 
                }

                .no-comments{
                    text-align: center;
                    font-size: 14px;
                }

                .show-more{
                    color: gray;
                    background-color: azure;
                    border: 1px solid dimgrey;
                    width: 50%;
                    margin: 10px auto;
                    text-align: center;
                    padding: 5px;
                    border-radius: 10px;
                    font-size: 14px;
                    cursor: pointer;
                }
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 70%;
$modal-height: 90vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .post-modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;

            .close{
                top: 10%;
                right: 16%;
            }
        }
    }
}


@media screen and (max-width:800px){
$modal-width: 95%;
$modal-height: 95vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .post-modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;

            .close{
                top: 5%;
                right: 6%;
            }

            .main{

                .post,
                .post-preview{ 
                    width: 80%;
                }

                .post-preview{

                    .profiles{
                        position: absolute;
                        width: 200px;
                        right: 0;
                        z-index: 1000;
                        text-align: start;

                        span{
                            font-size: 12px;
                            font-weight: 500;
                        }
                    }
                }
            }

            .main-comments{
                width: 75%;
            }
        }
    }
}
</style>