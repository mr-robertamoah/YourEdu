<template>
    <div class="post-modal-wrapper" v-if="show" @click.self="disappear">
        <div class="main-modal">
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <div class="main" v-if="!dataLoading">
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
                <div class="post-loading" v-if="postLoading">
                    <pulse-loader :loading="postLoading"></pulse-loader>
                </div>
                <div class="post"
                    v-if="(type === 'post' ||
                        computedPostTypeName === 'post') && !postLoading"
                >
                    <post-single
                        :post="computedData"
                        :postMediaFull="true"
                        :disabled="item ? true : false"
                        @postModalCommentCreated="postModalCommentCreated"
                        @clickedShowPostComments="clickedShowPostComments"
                        @clickedShowPostPreview="clickedShowPostPreview"
                        @clickedMedia="clickedMedia"
                        @postDeleteSuccess="postMainDeleteSuccess"
                    ></post-single>
                </div>
                <div class="answer"
                    v-if="computedShowAnswer"
                >
                    <answer-single
                        :answerFull="true"
                        :answer="data.type"
                        :disabled="item ? true : false"
                        @answerUnlikeSuccessful="answerUnlikeSuccessful"
                        @answerLikeSuccessful="answerLikeSuccessful"
                        @answerUnsaveSuccessful="answerUnsaveSuccessful"
                        @answerSaveSuccessful="answerSaveSuccessful"
                        @updateAnswerSuccessful="updateAnswerSuccessful"
                        @deleteAnswerSuccess="deleteAnswerSuccess"
                        @answerMarkedSuccessful="answerMarkedSuccessful"
                        @answerCommentSuccessful="answerCommentSuccessful"
                    ></answer-single>
                </div>
                <div class="post-preview"
                    v-if="computedShowPostType"
                >
                    <post-preview
                        :type="computedPostType"
                        :typeName="computedPostTypeName"
                        :owner="computedOwner"
                        :disabled="item ? true : false"
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
                                :itemList="data.type.possibleAnswers"
                                @listItemSelected="listItemSelected"
                                @clickedListButton="clickedListButton"
                                :edit="answerListEdit"
                                :selectable="answerListSelectable"
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
                                    :smallType="true"
                                    :profile="profile"
                                    :navigate="false"
                                    @clickedProfile="clickedProfile"
                                ></profile-bar>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading" v-else>
                <pulse-loader :loading="dataLoading"></pulse-loader>
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
                                    :disabled="item ? true : false"
                                    :onPostModal="true"
                                    @clickedMedia="clickedMedia"
                                    @commentDeleteSuccess="commentDeleteSuccess"
                                    @postModalCommentCreated="postModalCommentCreated"
                                    @postModalCommentEdited="postModalCommentEdited"
                                    @postAddComplete="comment"
                                    @viewModalCommentEditedMain="viewModalCommentEditedMain"
                                    @commentUnlikeSuccessfulMain="commentUnlikeSuccessfulMain"
                                    @commentLikeSuccessfulMain="commentLikeSuccessfulMain"
                                    @commentUnlikeSuccessful="commentUnlikeSuccessful"
                                    @commentLikeSuccessful="commentLikeSuccessful"
                                    @commentUnsaveSuccessful="commentUnsaveSuccessful"
                                    @commentSaveSuccessful="commentSaveSuccessful"
                                    @commentUnsaveSuccessfulMain="commentUnsaveSuccessfulMain"
                                    @commentSaveSuccessfulMain="commentSaveSuccessfulMain"
                                ></comment-single>
                            </template>
                            <template v-if="answers.length">
                                <answer-single
                                    v-for="answer in answers"
                                    :key="answer.id"
                                    :answer="answer"
                                    :disabled="item ? true : false"
                                    :possibleAnswers="computedPossibleAnswers"
                                    @askLoginRegister="askLoginRegister"
                                    @clickedShowAnswer="clickedShowAnswer"
                                    @askCreateAccount="askCreateAccount"
                                    @answerMarked="answerMarked"
                                    @answerUnlikeSuccessful="answerUnlikeSuccessful"
                                    @answerLikeSuccessful="answerLikeSuccessful"
                                    @answerUnsaveSuccessful="answerUnsaveSuccessful"
                                    @answerSaveSuccessful="answerSaveSuccessful"
                                    @clickedShowAnswerComments="clickedShowAnswerComments"
                                    @updateAnswerSuccessful="updateAnswerSuccessful"
                                    @deleteAnswerSuccess="deleteAnswerSuccess"
                                    @answerUnflaggedSuccess="answerUnflaggedSuccess"
                                    @isAnswerOwner="isAnswerOwner"
                                    @answerMarkedSuccessful="answerMarkedSuccessful"
                                    @answerCommentSuccessful="answerCommentSuccessful"
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
                        <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
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
                    @answerUnlikeSuccessful="postModalAnswerUnlikeSuccessful"
                    @answerLikeSuccessful="postModalAnswerLikeSuccessful"
                    @answerSaveSuccessfulMain="answerSaveSuccessfulMain"
                    @answerUnsaveSuccessfulMain="answerUnsaveSuccessfulMain"
                    :type="postModalType"
                    @updateAnswerSuccessfulMain="updateAnswerSuccessfulMain"
                    @deleteAnswerSuccessMain="deleteAnswerSuccessMain"
                    @answerMarkedSuccessfulMain="answerMarkedSuccessfulMain"
                    @answerCommentSuccessfulMain="answerCommentSuccessfulMain"
                >
                </post-modal>
            </template>
        </just-fade>
    </div>
</template>

<script>

import PostSingle from './PostSingle.vue'
import PostPreview from './PostPreview.vue'
import CommentSingle from './CommentSingle.vue'
import AnswerSingle from './AnswerSingle.vue'
import MainList from './MainList.vue'
import AddAnswer from './AddAnswer.vue'
import ProfileBar from './profile/ProfileBar.vue'
import FadeRightFast from './transitions/FadeRightFast.vue'
import FadeUp from './transitions/FadeUp.vue'
import SlideUpGroup from './transitions/SlideUpGroup.vue'
import { mapActions, mapGetters } from 'vuex';
import { strings } from '../services/helpers';
import { useRoute } from 'vue-router'

    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
            },
            postLoading: {
                type: Boolean,
                default: false,
            },
            type: {
                type: String,
                default: 'post',
            },
            data: {
                type: Object,
                default: null,
            },
            itemData: {
                type: Object,
                default: null,
            },
            heading: {
                type: String,
                default: ''
            },
        },
        components: {
            
            AnswerSingle,
            CommentSingle,
            MainList,
            SlideUpGroup,
            FadeUp,
            FadeRightFast,
            ProfileBar,
            AddAnswer,
            PostPreview,
            PostSingle,
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
                answerListSelectable: true,
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
                //
                dataLoading: false,
                item: null,
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue && this.data) {
                        if (this.data.hasOwnProperty('typeName') && 
                            (this.data.typeName === 'question' || this.data.typeName === 'riddle')) {
                            this.noCommentAnswer = 'there are no answers'
                        } else {
                            this.noCommentAnswer = 'there are no comments'
                        }
                        if (this.data.hasOwnProperty('type') && this.data.type &&
                            this.data.type.hasOwnProperty('possible_answers')) {
                            this.showAnswerList = true
                        }
                        this.listen()
                    } else if (this.itemData) {
                        this.getPost()
                    } else {
                        this.dataLoading = true
                    }
                }
            },
            data: {
                immediate: true,
                handler(newValue){
                    if (newValue !==  null) {
                        this.getCommentsAnswers()
                    }
                }
            },
            item: {
                immediate: true,
                handler(newValue){
                    if (newValue !==  null) {
                        this.listen()
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
        beforeRouteEnter(to, from, next) {
            next(vm => {
                console.log(to);
                if (to.name === 'post') {
                    vm.getPost()
                }
            });
        },
        computed: {
            ...mapGetters(['getProfiles','getUser']),
            computedRoute() {
                return useRoute()
            },
            computedNoCommentAnswer(){
                if (this.type === 'posttype' && 
                    (this.data && this.data.typeName === 'question' || this.data.typeName === 'riddle')) {
                    return !this.answers.length && !this.loading && !this.postLoading
                        ? true : false
                } else {
                    return !this.comments.length && !this.loading && !this.postLoading
                        ? true : false
                }
            },
            computedShowAnswer(){
                return this.data && this.data.typeName === 'answer' && !this.postLoading
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
            computedPossibleAnswers(){
                if (this.data && this.data.typeName === 'question' && this.type === 'posttype' &&
                    this.data.type.hasOwnProperty('possible_answers')) {
                    return this.data.type.possibleAnswers
                } else if (this.item && this.item.typeName === 'question' &&
                    this.item.type.hasOwnProperty('possible_answers')) {
                    return this.item.type.possibleAnswers
                }
                return []
            },
            computedData(){
                return this.data ? this.data : this.item ? this.item : null
            },
            computedPostType(){
                return this.data ? this.data.type : this.item ? this.item.type[0] : null
            },
            computedPostTypeName(){
                return this.data ? this.data.typeName : this.item ? this.item.typeName : ''
            },
            computedOwner(){
                return this.data ? this.data.owner : null
            },
            computedShowPostType() {
                return this.type && this.computedPostTypeName && 
                    !this.postLoading
            },
        },
        methods: {
            ...mapActions(['profile/getComments','profile/getAnswers',
                'profile/updateAnswer','profile/createAnswer',
                'dashboard/fetchItem']),
            listen(){
                let itemId
                if ((this.data && this.type === 'post') || 
                    (this.item && this.item.typeName === 'post')) {

                    itemId = this.data ? this.data.id : this.item.id
                    Echo.channel(`.youredu.post.${itemId}`)
                        .listen('.deleteComment', commentInfo=>{
                            // this.removeItem(commentInfo.comment.id, 'comment')
                            console.log('commentInfo :>> ', commentInfo);
                        })
                } else if ((this.data && this.type === 'posttype' && 
                    this.data.typeName === 'question') || 
                    (this.item && this.item.typeName === 'question')) {

                    itemId = this.data ? this.data.type.id : this.item.type.id
                    Echo.channel(`.youredu.question.${itemId}`)
                        .listen('.deleteAnswer', answerInfo=>{
                            this.removeItem(answerInfo.answer.id, 'answer')
                        })
                } else if ((this.data && this.type === 'posttype' && 
                    this.data.typeName === 'riddle') || 
                    (this.item && this.item.typeName === 'riddle')) {
                    
                    itemId = this.data ? this.data.type.id : this.item.type.id
                    Echo.channel(`.youredu.riddle.${itemId}`)
                        .listen('.deleteAnswer', answerInfo=>{
                            this.removeItem(answerInfo.answer.id, 'answer')
                        })
                }
            },
            async getPost(){
                let item,
                    itemId,
                    response
                if (this.computedRoute.name === 'post') {
                    item = this.computedRoute.name
                    itemId = this.computedRoute.params.itemId
                } else {
                    item = this.itemData.item
                    itemId = this.itemData.itemId
                }

                this.dataLoading = true
                if (!this.getUser) {
                    setTimeout(() => {
                        this.getPost()
                    }, 1000);
                    return
                }

                response = await this['dashboard/fetchItem']({item,itemId})

                this.dataLoading = false
                if (response.status) {
                    this.item = response.item
                } else {
                    console.log('response :>> ', response);
                }
            },
            answerMarkedSuccessful(data){ //from main answer or ans in answers
                if (data.main) {
                    this.$emit("answerMarkedSuccessfulMain",data)
                    return
                }
                this.addMark(data)
            },
            answerMarkedSuccessfulMain(data){ //to deal with answers in main area
                this.addMark(data)
            },
            addMark(data){
                let answerIndex = this.answers.findIndex(answer=>{
                    return answer.id === data.answerId
                })
                if (answerIndex > -1) {
                    this.answers[answerIndex].marks.push(data.mark)
                    this.answers[answerIndex].avg_score = data.avg_score
                    this.answers[answerIndex].max_score = data.max_score
                    this.answers[answerIndex].min_score = data.min_score
                }
            },
            answerMarked(data){
                let answerIndex = this.answers.findIndex(answer=>{
                    return answer.id === data.id
                })
                if (answerIndex > -1) {
                    this.answers.splice(answerIndex,1,data)
                }
            },
            isAnswerOwner(data){ //user cannot answer a question more than once
                this.answerListSelectable = false
            },
            deleteAnswerSuccessMain(data){
                this.showPostModal = false
                this.postModalData = null
                this.removeAnswer(data.answerId) 
            },
            updateAnswerSuccessfulMain(data){
                this.replaceAnswer(data.answer) 
            },
            answerUnflaggedSuccess(data){
                let answerIndex = this.answers.findIndex(answer=>{
                    return answer.id === data.answerId
                })
                if (answerIndex > -1) {
                    let flagIndex = this.answers[answerIndex].flags.findIndex(flag=>{
                        return flag.id === data.flag.id
                    })
                    if (flagIndex > -1) {
                        this.answers[answerIndex].flags.splice(flagIndex,1)
                    }
                }
            },
            updateAnswerSuccessful(data){
                if (data.main) {
                    this.$emit('updateAnswerSuccessfulMain',data) //emit to parent post modal
                    return 
                }
                this.replaceAnswer(data.answer)
            },
            deleteAnswerSuccess(data){
                if (data.main) {
                    this.$emit('deleteAnswerSuccessMain',data) //emit to parent post modal
                    return 
                }
                this.removeAnswer(data.answerId)
            },
            replaceAnswer(answer){
                let answerIndex = this.answers.findIndex(ans=>{
                    return ans.id === answer.id
                })
                if (answerIndex > -1) {
                    this.answers.splice(answerIndex,1,answer)
                }
            },
            removeAnswer(answerId){
                let answerIndex = this.answers.findIndex(ans=>{
                    return ans.id === answerId
                })
                if (answerIndex > -1) {
                    this.answers.splice(answerIndex,1)
                }
            },
            postModalAnswerUnlikeSuccessful(data){
                this.removeLike(data.itemId,data.likeId,'answer')
            },
            postModalAnswerLikeSuccessful(data){
                this.addLike(data.itemId,data.like,'answer')
            },
            answerUnlikeSuccessful(data){
                if (data.main) {
                    this.$emit('answerUnlikeSuccessful',data)
                } else {
                    this.removeLike(data.itemId,data.likeId,'answer')
                }
            },
            answerLikeSuccessful(data){
                if (data.main) {
                    this.$emit('answerLikeSuccessful',data)
                } else {
                    this.addLike(data.itemId,data.like,'answer')
                }
            },
            //comment added to answer
            answerCommentSuccessfulMain(data){
                this.addAnswerCommentNumber(data)
            },
            answerCommentSuccessful(data){ 
                if (this.data.typeName === 'answer' && 
                    this.data.type.hasOwnProperty('answerable_type')) {
                    this.$emit('answerCommentSuccessfulMain',data)
                    this.comments.unshift(data)
                }
                this.addAnswerCommentNumber(data)
            },
            addAnswerCommentNumber(data){
                let answerIndex = this.answers.findIndex(answer=>{
                    return answer.id === data.commentable_id
                })
                if (answerIndex > -1) {
                    this.answers[answerIndex].comments_number += 1
                }
            },
            //adding and removing saves to and from comments
            commentSaveSuccessfulMain(data){
                this.addSave(data.itemId,data.save,'comment')
            },
            commentUnsaveSuccessfulMain(data){
                this.removeSave(data.itemId,data.saveId,'comment')
            },
            commentSaveSuccessful(data){
                this.addSave(data.itemId,data.save,'comment') 
            },
            commentUnsaveSuccessful(data){
                this.removeSave(data.itemId,data.saveId,'comment') 
            },
            //adding and removing likes to and from comments
            commentUnlikeSuccessfulMain(data){
                this.removeLike(data.itemId,data.likeId)
            },
            commentLikeSuccessfulMain(data){
                this.addLike(data.itemId,data.like)
            },
            commentLikeSuccessful(data){
                this.addLike(data.itemId,data.like,'comment') 
            },
            commentUnlikeSuccessful(data){
                this.removeLike(data.itemId,data.likeId,'comment') 
            },
            ///adding and removing saves to and from answers
            answerSaveSuccessfulMain(data){
                this.addSave(data.itemId,data.save,'answer')
            },
            answerUnsaveSuccessfulMain(data){
                this.removeSave(data.itemId,data.saveId,'answer')
            },
            answerSaveSuccessful(data){
                if (data.main) {
                    this.$emit('answerSaveSuccessfulMain',data)
                } else {
                    this.addSave(data.itemId,data.save,'answer') 
                }
            },
            answerUnsaveSuccessful(data){
                if (data.main) {
                    this.$emit('answerUnsaveSuccessfulMain',data)
                } else {
                    this.removeSave(data.itemId,data.saveId,'answer') 
                }
            },
            removeSave(id,saveId,type = 'comment'){ //for removing the save object
                if (type === 'comment') {
                    let commentIndex = this.comments.findIndex(comment=>{
                        return comment.id === id
                    })
                    if (commentIndex > -1) {
                        let saveIndex =  this.comments[commentIndex].saves.findIndex(save=>{
                            return save.id === saveId
                        })
                        if (saveIndex > -1) {
                            this.comments[commentIndex]
                                .saves.splice(saveIndex,1)
                        }
                    }
                } else if (type === 'answer') {
                    let answerIndex = this.answers.findIndex(answer=>{
                        return answer.id === id
                    })
                    if (answerIndex > -1) {
                        let saveIndex =  this.answers[answerIndex].saves.findIndex(save=>{
                            return save.id === saveId
                        })
                        if (saveIndex > -1) {
                            this.answers[answerIndex]
                                .saves.splice(saveIndex,1)
                        }
                    }
                }
            },
            addSave(id,save,type = 'comment'){
                if (type === 'comment') {
                    let commentIndex = this.comments.findIndex(comment=>{
                        return comment.id === id
                    })
                    if (commentIndex > -1) {
                        this.comments[commentIndex].saves.unshift(save)
                    }
                } else if (type === 'answer') {
                    let answerIndex = this.answers.findIndex(answer=>{
                        return answer.id === id
                    })
                    if (answerIndex > -1) {
                        this.answers[answerIndex].saves.unshift(save)
                    }
                }
            },
            removeLike(id,likeId,type = 'comment'){ //for removing the like object
                if (type === 'comment') {
                    let commentIndex = this.comments.findIndex(comment=>{
                        return comment.id === id
                    })
                    if (commentIndex > -1) {
                        let likeIndex =  this.comments[commentIndex].likes.findIndex(like=>{
                            return like.id === likeId
                        })
                        if (likeIndex > -1) {
                            this.comments[commentIndex]
                                .likes.splice(likeIndex,1)
                        }
                    }
                } else if (type === 'answer') {
                    let answerIndex = this.answers.findIndex(answer=>{
                        return answer.id === id
                    })
                    if (answerIndex > -1) {
                        let likeIndex =  this.answers[answerIndex].likes.findIndex(like=>{
                            return like.id === likeId
                        })
                        if (likeIndex > -1) {
                            this.answers[answerIndex]
                                .likes.splice(likeIndex,1)
                        }
                    }
                }
            },
            addLike(id,like,type = 'comment'){
                if (type === 'comment') {
                    let commentIndex = this.comments.findIndex(comment=>{
                        return comment.id === id
                    })
                    if (commentIndex > -1) {
                        this.comments[commentIndex].likes.unshift(like)
                    }
                } else if (type === 'answer') {
                    let answerIndex = this.answers.findIndex(answer=>{
                        return answer.id === id
                    })
                    if (answerIndex > -1) {
                        this.answers[answerIndex].likes.unshift(like)
                    }
                }
            },
            postMainDeleteSuccess(data){
                this.$emit('mainModalDisappear')
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
            viewModalCommentEditedMain(comment){
                let commentIndex = this.comments.findIndex(c=>{
                    return c.id === comment.id
                })
                if (commentIndex > -1) {
                    this.comments.splice(commentIndex,1,comment)
                }
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
                    type = '',
                    data = {}

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
                    
                    type = 'update'
                    response = await this['profile/updateAnswer']({answerId: who.itemId,formData})
                } else {
                    data = {
                        item: this.data.typeName,
                        itemId: this.data.type.id,
                        postId: this.data.postId
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
                if (this.data.type.possibleAnswers.length) {
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
                        return c.id === comment.id
                    })
                    if (commentIndex > -1) {
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
                    return c.id === comment.id
                })
                if (commentIndex > -1) {
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
                if (this.data === null && this.item === null) {
                    return
                }
                this.loading = true
                let data = {},
                    response = null
                data.nextPage = this.nextPage
                if (this.data && this.type === 'post') {
                    data.item = 'post'
                    data.itemId = this.data.id
                    response = await this['profile/getComments'](data)
                } else if (this.type === 'posttype') {
                    data.item = this.data.typeName
                    data.itemId = this.data.type.id

                    if (this.type === 'posttype' && 
                        (this.data.typeName === 'question' || 
                        this.data.typeName === 'riddle')) {
                        response = await this['profile/getAnswers'](data)
                    } else {
                        response = await this['profile/getComments'](data)
                    }
                } else if (this.type === 'answer') {
                    data.item = this.data.typeName
                    data.itemId = this.data.type.id

                    response = await this['profile/getComments'](data)
                } else if (this.item.typeName === 'post') {
                    data.item = 'post'
                    data.itemId = this.item.id
                    response = await this['profile/getComments'](data)
                } else if (this.item.typeName !== 'question' &&
                    this.item.typeName === 'riddle') {
                    data.item = this.item.typeName
                    data.itemId = this.item.type[0].id
                    response = await this['profile/getComments'](data)
                } else if (this.item.typeName === 'question' || 
                    this.item.typeName === 'riddle') {
                    data.item = this.item.typeName
                    data.itemId = this.item.type[0].id
                    response = await this['profile/getAnswers'](data)
                }

                if (this.data && (this.type === 'posttype' && 
                   (this.data.typeName === 'riddle' || this.data.typeName === 'question'))) {
                    this.answers.push(...response.data.data)
                } else if (this.item &&
                   (this.item.typeName === 'riddle' || this.item.typeName === 'question')) {
                    this.answers.push(...response.data.data)
                } else if (this.item &&
                   (this.item.typeName !== 'riddle' && this.item.typeName !== 'question')) {
                    this.comments.push(...response.data.data)
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
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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

                .post-loading{
                    width: 100%;
                    height: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

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
            
            .loading{
                width: 100%;
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .main-comments{
                width: 55%;
                margin: 30px auto 20vh;
                overflow-y: auto;
                min-height: 65vh;
                max-height: 100vh;
                padding: 10px 15px 10px 0;

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
                
                .slide-up-wrapper{
                    padding: 10px 0 30px;
                }
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 70%;
$modal-height: 90vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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