<template>
    <div class="home-wrapper">
        <app-nav></app-nav>
        <div class="home-top">

        </div>
        <div class="home-middle">
            <div class="menu">
                <home-menu></home-menu>
            </div>
            <div class="main" infinite-wrapper>
                <home-main 
                    @askLoginRegister="askLoginRegister"
                    @clickedMedia="clickedMedia"
                    :loading="loading"
                    @clickedShowPostComments="clickedShowPostComments"
                    @clickedShowPostPreview="clickedShowPostPreview"
                ></home-main>
            </div>
            <div class="side">
                <home-side></home-side>
            </div>
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
        <!-- post modal for showing post/type and its comments -->
        <just-fade>
            <template slot="transition" v-if="showPostModal">
                <post-modal
                    @mainModalDisappear="showPostModal = false"
                    :data="postModalData"
                    :type="postModalType"
                >
                </post-modal>
            </template>
        </just-fade>

        <infinite-loading
            @infinite="infiniteHandler"
            v-if="computedPosts"
            force-use-infinite-wrapper
        ></infinite-loading>
    </div>
</template>

<script>
import HomeMain from '../components/home/HomeMain'
import HomeMenu from '../components/home/HomeMenu'
import HomeSide from '../components/home/HomeSide'
import FadeUp from '../components/transitions/FadeUp'
import SmallModal from '../components/SmallModal'
import InfiniteLoading from 'vue-infinite-loading'
import { mapActions, mapGetters } from 'vuex'

    export default {
        components: {
            HomeMain,
            HomeMenu,
            SmallModal,
            FadeUp,
            HomeSide,
            InfiniteLoading,
        },
        computed: {
            ...mapGetters(['profile/getPostNextPage','profile/getHomePosts','getAccessToken']),
            computedPosts() {
                return this['profile/getHomePosts'] ? this['profile/getHomePosts'] : null
            }
        },
        data() {
            return {
                showLoginRegister: false,
                nextPage: 1,
                loading: false,
                //media modal
                mediaUrl: '',
                mediaJustUrl: true,
                showMediaModal: false,
                mediaJustUrl: true, // for now this will be receiving url type
                mediaUrlType: '',
                //post modal
                showPostModal: false,
                postModalData: null,
                postModalType: '',
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                // vm.getData()
                vm.getPosts()
            });
        },
        methods: {
            ...mapActions(['profile/getPosts','profile/clearPosts','profile/getUserPosts']),
            clickedShowPostPreview(data){
                this.postModalData = data.data
                this.postModalType = 'posttype'
                this.showPostModal = true
            },
            clickedShowPostComments(data){
                this.postModalData = data.post
                this.postModalType = 'post'
                this.showPostModal = true
            },
            mediaModalDisappear(){
                this.showMediaModal = false
                this.mediaUrl = ''
                this.mediaUrlType = ''
            },
            clickedMedia(data){
                this.mediaUrl = data.url
                this.mediaUrlType = data.mediaType
                this.showMediaModal = true
            },
            askLoginRegister(){
                this.showLoginRegister = true
                setTimeout(() => {
                    this.showLoginRegister = false
                }, 4000);
            },
            async getPosts() {
                this.loading = true
                this['profile/clearPosts']()
                let response = null
                if (this.getAccessToken) {
                    response = await this['profile/getUserPosts'](this.nextPage)
                } else {
                    response = await this['profile/getPosts'](this.nextPage)
                }
                this.loading = false
                if (response !== 'unsuccessful') {
                    this.nextPage += 1
                }
            },
            async infiniteHandler($state){
                if (!this['profile/getPostsDone']) {
                    
                    let response = await this['profile/getPosts'](this.nextPage)

                    if (response !== 'unsuccessful') {
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
$color-main: rgba(127,255,212,1.0);
$background-color-main: whitesmoke;

    .home-wrapper{
        position: relative;
        background: $background-color-main;
        // height: 100vh;

        .home-top{
            background: $color-main;
            width: 100%;
            min-height: 40px;
            margin: 0;
            position: relative;
        }

        .home-middle{
            width: 95%;
            margin: 0 auto 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;

            .menu{
                background-color: red;
                min-height: 100px;
                width: 15%;
            }

            .main{
                width: 50%;
                background-color: inherit;
            }

            .side{
                min-height: 100px;
                background-color:green;
                width: 25%;
                
            }
        }
    }

@media screen and (max-width: 800px) {
    
    .home-wrapper{

        .home-top{

        }

        .home-middle{

            .menu{
                width: 100%;
                margin: 10px 0;
            }

            .main{
                width: 60%;
                
            }

            .side{
                width: 35%;
                
            }
        }
    }
}


@media screen and (max-width: 550px) {
    
    .home-wrapper{

        .home-top{

        }

        .home-middle{

            .menu{
                width: 45%;
            }

            .main{
                width: 100%;
                order: 10;
            }

            .side{
                width: 50%;
                margin: 10px 0;
            }
        }
    }
}
</style>