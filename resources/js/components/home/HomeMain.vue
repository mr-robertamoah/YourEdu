<template>
    <div class="home-main-wrapper">
        <post-create v-if="computedPostCreate"></post-create>
        <div 
            v-else-if="!loading && !computedPostCreate"
            @askLoginRegister="askLoginRegister"
        >
            must finish work on this
        </div>
        <div class="loading" v-if="loading">
            <pulse-loader
                :loading="loading"
            ></pulse-loader>
        </div>
        <template 
            v-if="!loading && (type === 'posts' || type === 'questions' ||
            type === 'riddles' || type === 'poems'|| type === 'activities' ||
            type === 'books')"
        >
            <template 
                v-if="computedPosts"
            >
                <post-show
                    :key="post.id"
                    v-for="post in computedPosts"
                    :post="post"
                    @askLoginRegister="askLoginRegister"
                    @clickedMedia="clickedMedia"
                    @clickedShowPostComments="clickedShowPostComments"
                    @clickedShowPostPreview="clickedShowPostPreview"
                ></post-show>
            </template>
        </template>
    </div>
</template>

<script>
import PostCreate from '../PostCreate'
import PostCreateAlt from '../PostCreateAlt'
import PostShow from '../PostShow'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import { mapGetters, mapActions } from 'vuex'
import InfiniteLoading from 'vue-infinite-loading'

    export default {
        components: {
            PostCreate,
            PostCreate,
            PulseLoader,
            PostShow,
            InfiniteLoading,
        },
        props: {
            loading: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: ''
            },
            params: {
                type: Object,
                default(){
                    return {}
                }
            },
        },
        data() {
            return {
                showLoginRegister: false,
            }
        },
        computed: {
            ...mapGetters(['home/getHomePosts','home/getHomePostsMine','home/getHomePostsFollowers',
                'home/getHomePostsFollowings','home/getHomePostsAttachments',
                'home/getHomeReads','getProfiles','home/getHomeDiscussions',
                'home/getHomeQuestions','home/getHomeQuestionsMine','home/getHomeQuestionsFollowers',
                'home/getHomeQuestionsFollowings','home/getHomeQuestionsAttachments',
                'home/getHomeRiddles','home/getHomeRiddlesMine','home/getHomeRiddlesFollowers',
                'home/getHomeRiddlesFollowings','home/getHomeRiddlesAttachments',
                'home/getHomePoems','home/getHomePoemsMine','home/getHomePoemsFollowers',
                'home/getHomePoemsFollowings','home/getHomePoemsAttachments',
                'home/getHomeBooks','home/getHomeBooksMine','home/getHomeBooksFollowers',
                'home/getHomeBooksFollowings','home/getHomeBooksAttachments',
                'home/getHomeActivities','home/getHomeActivitiesMine','home/getHomeActivitiesFollowers',
                'home/getHomeActivitiesFollowings','home/getHomeActivitiesAttachments',]),
            computedPosts() {
                if (this.type === 'posts') {
                    if (this.params.hasOwnProperty('mine')) {
                        return this['home/getHomePostsMine'] ? 
                            this['home/getHomePostsMine'] : null
                    } else if (this.params.hasOwnProperty('followers')) {
                        return this['home/getHomePostsFollowers'] ? 
                            this['home/getHomePostsFollowers'] : null
                    } else if (this.params.hasOwnProperty('followings')) {
                        return this['home/getHomePostsFollowings'] ? 
                            this['home/getHomePostsFollowings'] : null
                    } else if (this.params.hasOwnProperty('all')) {
                        return this['home/getHomePosts'] ? this['home/getHomePosts'] : null
                    } else { //
                        return this['home/getHomePostsAttachments'] ? 
                            this['home/getHomePostsAttachments'] : null
                    }
                } else if (this.type === 'questions' || this.type === 'poems' ||
                    this.type === 'riddles' || this.type === 'books' ||
                    this.type === 'activities'){
                    return this.computedPostTypes
                }
            },
            computedPostCreate(){
                return this.getProfiles && this.getProfiles.length ? true : false
            },
            computedReads() {
                return this['home/getHomeReads'] ? this['home/getHomeReads'] : null
            },
            computedDiscussions() {
                return this['home/getHomeDiscussions'] ? this['home/getHomeDiscussions'] : null
            },
            computedPostTypes() {
                if (this.type === 'questions') {
                    if (this.params.hasOwnProperty('mine')) {
                        return this['home/getHomeQuestionsMine'] ? 
                            this['home/getHomeQuestionsMine'] : null
                    } else if (this.params.hasOwnProperty('followers')) {
                        return this['home/getHomeQuestionsFollowers'] ? 
                            this['home/getHomeQuestionsFollowers'] : null
                    } else if (this.params.hasOwnProperty('followings')) {
                        return this['home/getHomeQuestionsFollowings'] ? 
                            this['home/getHomeQuestionsFollowings'] : null
                    } else if (this.params.hasOwnProperty('all')) {
                        return this['home/getHomeQuestions'] ? 
                            this['home/getHomeQuestions'] : null
                    } else { //
                        return this['home/getHomeQuestionsAttachments'] ? 
                            this['home/getHomeQuestionsAttachments'] : null
                    }
                } else if (this.type === 'poems') {
                    if (this.params.hasOwnProperty('mine')) {
                        return this['home/getHomePoemMine'] ? 
                            this['home/getHomePoemMine'] : null
                    } else if (this.params.hasOwnProperty('followers')) {
                        return this['home/getHomePoemFollowers'] ? 
                            this['home/getHomePoemFollowers'] : null
                    } else if (this.params.hasOwnProperty('followings')) {
                        return this['home/getHomePoemFollowings'] ? 
                            this['home/getHomePoemFollowings'] : null
                    } else if (this.params.hasOwnProperty('all')) {
                        return this['home/getHomePoem'] ? 
                            this['home/getHomePoem'] : null
                    } else { //
                        return this['home/getHomePoemsAttachments'] ? 
                            this['home/getHomePoemsAttachments'] : null
                    }
                } else if (this.type === 'riddles') {
                    if (this.params.hasOwnProperty('mine')) {
                        return this['home/getHomeRiddlesMine'] ? 
                            this['home/getHomeRiddlesMine'] : null
                    } else if (this.params.hasOwnProperty('followers')) {
                        return this['home/getHomeRiddlesFollowers'] ? 
                            this['home/getHomeRiddlesFollowers'] : null
                    } else if (this.params.hasOwnProperty('followings')) {
                        return this['home/getHomeRiddlesFollowings'] ? 
                            this['home/getHomeRiddlesFollowings'] : null
                    } else if (this.params.hasOwnProperty('all')) {
                        return this['home/getHomeRiddles'] ? 
                            this['home/getHomeRiddles'] : null
                    } else { //
                        return this['home/getHomeRiddlesAttachments'] ? 
                            this['home/getHomeRiddlesAttachments'] : null
                    }
                } else if (this.type === 'activities') {
                    if (this.params.hasOwnProperty('mine')) {
                        return this['home/getHomeActivitiesMine'] ? 
                            this['home/getHomeActivitiesMine'] : null
                    } else if (this.params.hasOwnProperty('followers')) {
                        return this['home/getHomeActivitiesFollowers'] ? 
                            this['home/getHomeActivitiesFollowers'] : null
                    } else if (this.params.hasOwnProperty('followings')) {
                        return this['home/getHomeActivitiesFollowings'] ? 
                            this['home/getHomeActivitiesFollowings'] : null
                    } else if (this.params.hasOwnProperty('all')) {
                        return this['home/getHomeActivities'] ? 
                            this['home/getHomeActivities'] : null
                    } else { //
                        return this['home/getHomeActivitiesAttachments'] ? 
                            this['home/getHomeActivitiesAttachments'] : null
                    }
                } else if (this.type === 'books') {
                    if (this.params.hasOwnProperty('mine')) {
                        return this['home/getHomeBooksMine'] ? 
                            this['home/getHomeBooksMine'] : null
                    } else if (this.params.hasOwnProperty('followers')) {
                        return this['home/getHomeBooksFollowers'] ? 
                            this['home/getHomeBooksFollowers'] : null
                    } else if (this.params.hasOwnProperty('followings')) {
                        return this['home/getHomeBooksFollowings'] ? 
                            this['home/getHomeBooksFollowings'] : null
                    } else if (this.params.hasOwnProperty('all')) {
                        return this['home/getHomeBooks'] ? 
                            this['home/getHomeBooks'] : null
                    } else { //
                        return this['home/getHomeBooksAttachments'] ? 
                            this['home/getHomeBooksAttachments'] : null
                    }
                }
                return null
            },
        },
        methods: {
            clickedShowPostComments(data){
                this.$emit('clickedShowPostComments',data)
            },
            clickedShowPostPreview(data){
                this.$emit('clickedShowPostPreview',data)
            },
            askLoginRegister(){
                this.$emit('askLoginRegister','HomeMain')
            },
            clickedMedia(data){
                this.$emit('clickedMedia',data)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .home-main-wrapper{

        .loading{
            text-align: center;
            width: 100%;
        }

        .create{
            width: 100%;
            min-height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid dimgrey;
            border-right: 2px solid rgb(105, 105, 105);

            div{
                padding: 10px;
                font-size: 16px;

                &:hover{
                    transition: all 1s ease;
                    box-shadow: 0 0 3px gray;
                }
            }
        }
    }
</style>