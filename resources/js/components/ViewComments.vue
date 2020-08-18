<template>
    <fade-up>
        <template slot="transition" v-if="show">
            <view-modal
                @mainModalDisappear="viewModalDisappear"
                @mainModalAppear="viewModalAppear"
                infinite-wrapper
            >
                <template slot="main">
                    <div class="main-comment" v-if="comment">
                        <comment-single
                            :comment="comment"
                            :showCommentNumber="false"
                            @commentDeleteSuccess="commentViewDeleteSuccess"
                            @postModalCommentEdited="postModalCommentEdited"
                            @commentUnlikeSuccessful="commentUnlikeSuccessful"
                            @commentLikeSuccessful="commentLikeSuccessful"
                        ></comment-single>
                    </div>
                    <div class="main-comment" v-if="!comment">
                        waiting...
                    </div>
                    <div class="view-comments">
                        <template v-if="computedComments">
                            <div class="comment">
                                <comment-single
                                    :key="key" v-for="(comment, key) in comments"
                                    :comment="comment"
                                    @postModalCommentEdited="postModalCommentEdited"
                                    @viewModalCommentEditedMain="viewModalCommentEditedMain"
                                    @commentDeleteSuccess="commentViewDeleteSuccess"
                                    @commentUnlikeSuccessful="commentUnlikeSuccessful"
                                    @commentLikeSuccessful="commentLikeSuccessful"
                                    @commentUnlikeSuccessfulMain="commentUnlikeSuccessfulMain"
                                    @commentLikeSuccessfulMain="commentLikeSuccessfulMain"
                                    @commentViewParentDeleteSuccess="commentViewParentDeleteSuccess"
                                ></comment-single>
                            </div>
                        </template>
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
                    <infinite-loader
                        @infinite="infiniteHandler"
                        v-if="computedComments"
                        force-use-infinite-wrapper
                    ></infinite-loader>
                </template>
            </view-modal>
        </template>
    </fade-up>
</template>

<script>
import ViewModal from '../components/ViewModal';
import InfiniteLoader from 'vue-infinite-loading';
import FadeUp from '../components/transitions/FadeUp';
import { mapGetters, mapActions } from 'vuex';
    export default {
        components: {
            FadeUp,
            InfiniteLoader,
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
                default: false
            },
        },
        data() {
            return {
                nextPage: 1,
                comments: [],
                showLoginRegister: false,
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

                },
            }
        },
        computed: {
            ...mapGetters(['profile/getStateComments','profile/getCommentNextPage']),
            computedComments() {
                if (this['profile/getStateComments']) {
                    // this.comments.push(...this['profile/getStateComments'])
                }
                return true

            }
        },
        methods: {
            ...mapActions(['profile/getComments','profile/clearComments',
                'profile/getCommentsDone','profile/getComment']),
            viewModalDisappear(){
                this.$emit('viewModalDisappear')
            },
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
            commentLikeSuccessful(data){
                if (this.comment.id === data.itemId) {
                    this.$emit('commentLikeSuccessfulMain', data)//alert parent view modal to add this like
                    return
                }

                this.addLike(data.itemId,data.like)
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
                this.getComments()
            },
            askLoginRegister(){
                this.showLoginRegister = true
                setTimeout(() => {
                    this.showLoginRegister = false
                }, 4000);
            }, 
            async getComments(){
                // console.log('get comments',this.nextPage)
                this['profile/clearComments']()
                let data = {
                    item : 'comment',
                    itemId : this.comment.id,
                    nextPage: this.nextPage
                }
                let response = await this['profile/getComments'](data)

                // console.log('get comments',response)
                this.comments.push(...response.data.data)
                if (response.status) {
                    this.nextPage += 1
                }
            },
            async infiniteHandler($state) {
                if (!this['profile/getCommentsDone']) {
                    let data = {
                        item : 'comment',
                        itemId : this.comment.id,
                        nextPage: this.nextPage
                    }
                    let response = await this['profile/getComments'](data)
                        this.comments.push(...response.data.data)
                    if (response.status) {
                        this.nextPage += 1
                        $state.loaded()
                    } else {
                        $state.complete()
                    }
                } 
            },
        },
    }
</script>

<style lang="scss" scoped>

    .main-comment{
        width: 100%;
        margin: 0 auto 10px;
        padding: 5%;
        border-bottom: 1px solid;
    }

    .view-comments{
        width: 80%;
        margin: 0 auto 10px;

        .comment{
            width: 100%;
            margin-top: 40px;
            margin-bottom: 5px;
        }
    }

@media screen and (max-width:800px) {
    
    .main-comment{
        margin: 10px auto 10px;
    }

    .view-comments{

        .comment{
            margin-top: 0;
        }
    }
}
</style>