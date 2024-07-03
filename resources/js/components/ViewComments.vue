<template>
    <fade-up>
        <template slot="transition" v-if="show">
            <view-modal
                @mainModalDisappear="viewModalDisappear"
                @mainModalAppear="viewModalAppear"
                class="view-modal"
            >
                <template slot="main">
                    <div class="main-comment" v-if="computedComment && !dataLoading">
                        <answer-single
                            v-if="item && item.type === 'answer'"
                            :answer="item"
                            :disabled="item ? true : false"
                        ></answer-single>
                        <comment-single
                            :comment="computedComment"
                            :disabled="item ? true : false"
                            :showCommentNumber="false"
                            @commentDeleteSuccess="commentViewDeleteSuccess"
                            @postModalCommentEdited="postModalCommentEdited"
                            @commentUnlikeSuccessful="commentUnlikeSuccessful"
                            @commentLikeSuccessful="commentLikeSuccessful"
                            @commentUnsaveSuccessful="commentUnsaveSuccessful"
                            @commentSaveSuccessful="commentSaveSuccessful"
                        ></comment-single>
                    </div>
                    <div class="main-comment" v-else>
                        <pulse-loader :loading="dataLoading"></pulse-loader>
                    </div>
                    <div class="view-comments">
                        <template v-if="computedComments" infinite-wrapper>
                            <div class="comment">
                                <comment-single
                                    :key="key" v-for="(comment, key) in comments"
                                    :comment="comment"
                                    :disabled="item ? true : false"
                                    @postModalCommentEdited="postModalCommentEdited"
                                    @viewModalCommentEditedMain="viewModalCommentEditedMain"
                                    @commentDeleteSuccess="commentViewDeleteSuccess"
                                    @commentUnlikeSuccessful="commentUnlikeSuccessful"
                                    @commentLikeSuccessful="commentLikeSuccessful"
                                    @commentUnlikeSuccessfulMain="commentUnlikeSuccessfulMain"
                                    @commentLikeSuccessfulMain="commentLikeSuccessfulMain"
                                    @commentUnsaveSuccessful="commentUnsaveSuccessful"
                                    @commentSaveSuccessful="commentSaveSuccessful"
                                    @commentUnsaveSuccessfulMain="commentUnsaveSuccessfulMain"
                                    @commentSaveSuccessfulMain="commentSaveSuccessfulMain"
                                    @commentViewParentDeleteSuccess="commentViewParentDeleteSuccess"
                                ></comment-single>
                            </div>
                        </template>
                        <Infinite-Loading
                            @infinite="infiniteHandler"
                            v-if="nextPage === null || nextPage > 0"
                            force-use-infinite-wrapper
                        ></Infinite-Loading>
                    </div>
                    <fade-up>
                        <template slot="transition" v-if="showLoginRegister">
                            <small-modal
                                @disappear="showLoginRegister = false"
                                :showForm='showLoginRegister'
                                title="welcome to this new community."
                            >
                                <template slot="other">
                                    <router-link to="/login">login</router-link> or 
                                    <router-link to='/register'>register</router-link> to interact and grow in a positve way.
                                </template>
                            </small-modal>
                        </template>
                    </fade-up>
                </template>
            </view-modal>
        </template>
    </fade-up>
</template>

<script>
import ViewModal from '../components/ViewModal.vue';
import FadeUp from '../components/transitions/FadeUp.vue';
import AnswerSingle from '../components/AnswerSingle.vue';
import { mapGetters, mapActions } from 'vuex';
import { useRoute } from 'vue-router';
    export default {
        components: {
            AnswerSingle,
            FadeUp,
            
            
            ViewModal,
        },
        props: {
            comment: {
                type: Object,
                default(){
                    return {}
                }
            },
            show: {
                type: Boolean,
                default: true
            },
            itemData: {
                type: Object,
                default: null,
            },
        },
        data() {
            return {
                nextPage: 0,
                comments: [],
                showLoginRegister: false,
                item: null,
                dataLoading: false,
            }
        },
        watch: {
            comment: {
                immediate: true,
                handler(newValue){
                   
                },
            },
            show: {
                immediate: true,
                handler(newValue){
                    if (this.itemData) {
                        this.getComment()
                    } 
                },
            },
            item: {
                immediate: true,
                handler(newValue){
                    if (newValue !==  null) {
                        this.listen()
                        this.getComments()
                    }
                }
            },
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                console.log(to);
                if (to.name === 'comment') {
                    vm.getComment()
                } else if (to.name === 'answer') {
                    vm.getComment()
                }
            });
        },
        computed: {
            ...mapGetters(['profile/getStateComments','profile/getCommentNextPage','getUser']),
            computedComments() {
                if (this['profile/getStateComments']) {
                    // this.comments.push(...this['profile/getStateComments'])
                }
                return true
            },
            computedComment(){
                return this.comment ? this.comment : this.item ? this.item : null
            },
        },
        methods: {
            ...mapActions(['profile/getComments','profile/clearComments',
                'profile/getCommentsDone','profile/getComment','dashboard/fetchItem',]),
            listen(){

            },
            async getComment(){ //also for answer
                const route = useRoute()
                
                let item,
                    itemId,
                    response
                if (!this.itemData) {
                    item = route.name
                    itemId = route.params.itemId
                } else {
                    item = this.itemData.item
                    itemId = this.itemData.itemId
                }

                this.dataLoading = true
                if (!this.getUser) {
                    setTimeout(() => {
                        this.getComment()
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
            viewModalDisappear(){
                this.$emit('viewModalDisappear')
            },
            //for adding and removing saves to and from comments
            commentSaveSuccessfulMain(data){
                this.addSave(data.itemId,data.save)
            },
            commentUnsaveSuccessfulMain(data){
                this.removeSave(data.itemId,data.saveId)
            },
            commentUnsaveSuccessful(data){
                if (this.comment.id === data.itemId) {
                    this.$emit('commentUnsaveSuccessfulMain', data)//event to alert parent view modal to remove this like
                    return
                }

                this.removeLike(data.itemId,data.likeId)
            },
            commentSaveSuccessful(data){
                if (this.comment.id === data.itemId) {
                    this.$emit('commentSaveSuccessfulMain', data)//alert parent view modal to add this like
                    return
                }

                this.addSave(data.itemId,data.save)
            },
            removeSave(commentId,saveId){
                let commentIndex = this.comments.findIndex(comment=>{
                    return comment.id === commentId
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
            },
            addSave(commentId,save){
                 let commentIndex = this.comments.findIndex(comment=>{
                    return comment.id === commentId
                })
                if (commentIndex > -1) {
                    this.comments[commentIndex].saves.unshift(save)
                }
            },
            //for adding and removing likes to and from comments
            commentLikeSuccessfulMain(data){
                this.addLike(data.itemId,data.likeId)
            },
            commentUnlikeSuccessfulMain(data){
                this.removeLike(data.itemId,data.likeId)
            },
            commentUnlikeSuccessful(data){
                if (this.comment.id === data.itemId) {
                    this.$emit('commentUnlikeSuccessfulMain', data)//event to alert parent view modal to remove this like
                    return
                }

                this.removeLike(data.itemId,data.likeId)
            },
            commentLikeSuccessful(data){
                if (this.comment.id === data.itemId) {
                    this.$emit('commentLikeSuccessfulMain', data)//alert parent view modal to add this like
                    return
                }

                this.addLike(data.itemId,data.like)
            },
            removeLike(commentId,likeId){
                let commentIndex = this.comments.findIndex(comment=>{
                    return comment.id === commentId
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
            },
            addLike(commentId,like){
                 let commentIndex = this.comments.findIndex(comment=>{
                    return comment.id === commentId
                })
                if (commentIndex > -1) {
                    this.comments[commentIndex].likes.unshift(like)
                }
            },
            commentViewParentDeleteSuccess(data){
                this.removeCommentId(data.commentId)
            },
            commentViewDeleteSuccess(data){
                if (this.comment.id === data.commentId) {
                    this.$emit('commentViewParentDeleteSuccess') //this event is to delete the main comment from the comments of its parent view modal
                    this.viewModalDisappear()
                }
                this.removeCommentId(data.commentId)
            },
            viewModalCommentEditedMain(comment){
                cconsole.log('in view',comment);
                this.removeComment(comment)
            },
            postModalCommentEdited(comment){
                if (this.comment.id === comment.id) {
                    this.$emit('postModalCommentEditedMain',comment) //emit to commentsingle
                    return
                }
                //editing comments in the comments view section
                this.removeComment(comment)
            },
            removeCommentId(id){ //for deletion
                let commentIndex = this.comments.findIndex(c=>{
                    return c.id === id
                })
                if (commentIndex > -1) {
                    this.comments.splice(commentIndex,1)
                }
            },
            removeComment(comment){ //for editing
                // cconsole.log('in remove',comment);
                let commentIndex = this.comments.findIndex(c=>{
                    return c.id === comment.id
                })
                if (commentIndex > -1) {
                    this.comments.splice(commentIndex,1,comment)
                }
            },
            viewModalAppear(){
                this.comments = []
                this['profile/clearComments']()
                if (this.comment.id) {
                    this.getComments()
                }
            },
            askLoginRegister(){
                this.showLoginRegister = true
                setTimeout(() => {
                    this.showLoginRegister = false
                }, 4000);
            }, 
            async getComments(){
                console.log('get comments')
                this['profile/clearComments']()
                let data = {
                    item : this.item ? this.item.type : 'comment',
                    itemId : this.comment.id ? this.comment.id : 
                        this.item ? this.item.id : null,
                    nextPage: this.nextPage
                }
                
                if (!data.itemId) return
                let response = await this['profile/getComments'](data)

                // console.log('get comments',response)
                this.comments.push(...response.data.data)
                if (response.status !== null) {
                    this.nextPage += 1
                } else {
                    this.nextPage = null
                }
            },
            async infiniteHandler($state) {
                if (this.nextPage) {
                    let data = {
                        item : 'comment',
                        itemId : this.comment.id ? this.comment.id : 
                            this.item ? this.item.id : null,
                        nextPage: this.nextPage
                    }
                    
                    if (!data.itemId) return
                    let response = await this['profile/getComments'](data)
                    if (response.currentPage !== 1) {
                        this.comments.push(...response.data.data)
                    }
                    if (response.status) {
                        this.nextPage += 1
                        $state.loaded()
                    } else {
                        this.nextPage = null
                        $state.complete()
                    }
                }  else {
                    $state.complete()
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .view-modal{
        overflow: hidden;
    }

    .main-comment{
        width: 100%;
        margin: 0 auto 10px;
        padding: 5%;
        border-bottom: 1px solid;
    }

    .view-comments{
        width: 90%;
        margin: 0 auto 10px;
        max-height: 600px;
        overflow-y: auto;
        padding: 5px 10px 20px;

        .comment{
            width: 100%;
            margin-top: 40px;
            margin-bottom: 5px;
        }
    }

@media screen and (max-width:800px) {
    
    .main-comment{
        margin: 10px auto 10px;
        text-align: center;
        width: 100%;
    }

    .view-comments{
        overflow-y: auto;
        height: 370px;

        .comment{
            margin-top: 0;
        }
    }
}
</style>