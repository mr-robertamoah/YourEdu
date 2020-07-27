<template>
    <fade-up>
        <template slot="transition">
            <view-modal
                @goBack="goBack"
                :showGoBack="true"
            >
                <template slot="main">
                    <div class="main-comment" v-if="mainComment">
                        <comment-single
                            :comment="mainComment"
                        ></comment-single>
                    </div>
                    <div class="main-comment" v-if="!mainComment">
                        waiting...
                    </div>
                    <!-- <div class="add-comment">
                        <add-comment
                            
                        ></add-comment>
                    </div> -->
                    <div class="view-comments">
                        <template v-if="computedComments">
                            <div class="comment">
                                <comment-single
                                    :key="key" v-for="(comment, key) in computedComments"
                                    :comment="comment"
                                ></comment-single>
                            </div>
                        </template>
                    </div>
                    <infinite-loader
                        @infinite="infiniteHandler"
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
            // show: {
            //     type: Boolean,
            //     default: false
            // },
        },
        data() {
            return {
                nextPage: 0,
                itemId: 0,
                mainComment: null,
                // comment: null,
            }
        },
        watch: {
            comment: {
                immediate: true,
                handler(newValue){
                   
                },
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                vm.itemId = to.params.commentId
                 if (to.params.comment && to.params.comment.hasOwnProperty('id') &&
                    to.params.comment.id === to.params.commentId) {
                    vm.mainComment = to.params.comment
                } else {
                    vm.getComment()
                }
                vm.getComments()
            });
        },
        beforeRouteUpdate(to, from, next) {
            this['profile/clearComments']()
            this.itemId = to.params.commentId
            if (to.params.comment && to.params.comment.hasOwnProperty('id') &&
                    to.params.comment.id === to.params.commentId) {
                this.mainComment = to.params.comment
            } else {
                this.getComment()
            }
            
            this.getComments()
            next();
        },
        computed: {
            ...mapGetters(['profile/getStateComments','profile/getCommentNextPage']),
            computedComments() {
                return this['profile/getStateComments'] ? this['profile/getStateComments'] : null
            }
        },
        methods: {
            ...mapActions(['profile/getComments','profile/clearComments',
                'profile/getCommentsDone','profile/getComment']),
            async getComment(){
                let itemId = this.itemId
                
                let response = await this['profile/getComment'](itemId)

                if (response === 'unsuccessful') {
                    
                } else {
                    this.mainComment = response
                }
            },
            async getComments(){
                console.log('route', this.$route)
                console.log('router', this.$router)
                let data = {
                    item : 'comment',
                    itemId : this.itemId,
                    nextPage: this['profile/getCommentNextPage']
                }
                let response = await this['profile/getComments'](data)

                if (response !== 'unsuccessful') {
                    
                } else {
                    this.nextPage = response
                }
            },
            async infiniteHandler($state) {
                if (!this['profile/getCommentsDone']) {
                    let data = {
                        item : 'comment',
                        itemId : this.itemId,
                        nextPage: this['profile/getCommentNextPage']
                    }
                    let response = await this['profile/getComments'](data)
                } else {
                    $state.complete()
                }
                // if (response !== 'unsuccessful') {
                //     $state.complete()
                // } else {
                //     this.nextPage = response
                //     $state.loaded()
                // }
            },
            mainModalDisappear(){
                // this['profile/clearComments']()
                // this.$emit('viewModalDisappear')
            },
            goBack(){
                // this['profile/clearComments']()
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
            margin-bottom: 5px;
        }
    }
</style>