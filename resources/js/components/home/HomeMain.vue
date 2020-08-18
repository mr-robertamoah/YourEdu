<template>
    <div class="home-main-wrapper">
        <post-create v-if="!loading && computedPostCreate"></post-create>
        <div 
            v-else
            @askLoginRegister="askLoginRegister"
        >
        </div>
        <div class="loading" v-if="loading">
            <pulse-loader
                :loading="loading"
            ></pulse-loader>
        </div>
        <template v-if="!loading && computedPosts">
            <post-show
                :key="key"
                v-for="(post,key) in computedPosts"
                :post="post"
                @askLoginRegister="askLoginRegister"
                @clickedMedia="clickedMedia"
                @clickedShowPostComments="clickedShowPostComments"
                @clickedShowPostPreview="clickedShowPostPreview"
            ></post-show>
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
        },
        data() {
            return {
                showLoginRegister: false,
            }
        },
        computed: {
            ...mapGetters(['profile/getHomePosts','profile/getPostNextPage',
                'profile/getPostsDone','getProfiles']),
            computedPosts() {
                return this['profile/getHomePosts'] ? this['profile/getHomePosts'] : null
            },
            computedPostCreate(){
                return this.getProfiles && this.getProfiles.length ? true : false
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