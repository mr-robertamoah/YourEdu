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
                                    @viewModalCommentEditedMain="postModalCommentEdited"
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
                //     this.mainComment = this.comment
                //     this.itemId = this.comment.id
                // //    this.getComment()
                //    this.getComments()
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
            postModalCommentEdited(comment){
                //editing comments in the comments view section
                let commentIndex = this.comments.findIndex(c=>{
                    return c.id === comment.commentable_id
                })
                if (commentIndex > -1) {
                    this.comments.splice(commentIndex,1,comment)
                } else if (comment.id === this.comment.id) {
                    this.$emit('postModalCommentEditedMain',comment) //emit to commentsingle
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