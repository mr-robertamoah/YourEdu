<template>
    <div>
        <div class="loading" v-if="computedLoading">
            <sync-loader
                :loading="computedLoading"
            ></sync-loader>
        </div>
        <div v-if="!computedLoading">
            <app-nav
                :profileAccountId="profileAccountId"
                :profileAccount="profileAccount"
            ></app-nav>
            <div class="profile-wrapper">
                <div class="switch-buttons">
                    <div class="sub" v-if="computedSwitch">
                        <post-button
                            buttonText='edit'
                            :active='true'
                            @click="editProfile"
                        ></post-button>
                    </div>
                </div>
                <profile-first
                    @clickedMedia="clickedMedia"
                ></profile-first>
                <profile-second
                    @clickedMedia="clickedMedia"
                    @clickedShowPostComments="clickedShowPostComments"
                    @clickedShowPostPreview="clickedShowPostPreview"
                    @editProfile="showEditProfile = true"
                    @askLoginRegister="askLoginRegister"
                    :account="profileAccount"
                    :accountId="profileAccountId"
                     infinite-wrapper
                ></profile-second>
            </div>
                <infinite-loading
                    @infinite="infiniteHandler"
                    v-if="computedPosts"
                    force-use-infinite-wrapper
                ></infinite-loading>
        </div>
        <!-- for editing profiel -->
        <fade-up>
            <template slot="transition" v-if="showEditProfile">
                <edit-profile
                    @closeModal="showEditProfile = false"
                    :showForm='showEditProfile'
                ></edit-profile>
            </template>
        </fade-up>
        <!-- small modal for getting people to register or login -->
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
        <!--showing single media in a modal  -->
        <just-fade>
            <template slot="transition" v-if="showMediaModal">
                <media-modal
                    @mainModalDisappear="mediaModalDisappear"
                    :mediaData="mediaData"
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
    </div>
</template>

<script>
import PostButton from '../components/PostButton'
import EditProfile from '../components/forms/EditProfile'
import FadeUp from '../components/transitions/FadeUp'
import JustFade from '../components/transitions/JustFade'
import AppNav from '../components/Nav'
import MainList from '../components/MainList'
import ProfileFirst from '../components/profile/ProfileFirst'
import ProfileSecond from '../components/profile/ProfileSecond'
import SyncLoader from 'vue-spinner/src/SyncLoader'
import InfiniteLoading from 'vue-infinite-loading'
import { mapGetters, mapActions } from "vuex";

    export default {
        components: {
            InfiniteLoading,
            AppNav,
            JustFade,
            FadeUp,
            SyncLoader,
            EditProfile,
            PostButton,
            MainList,
            ProfileFirst,
            ProfileSecond
        },
        computed: {
            ...mapGetters(['authenticatingUser','getUser','getLoading','profile/getProfile',
                'profile/getHomePosts','profile/getPostNextPage']),
            computedPosts(){
                return this['profile/getHomePosts'] && this['profile/getHomePosts'].length > 0 ? 
                    this['profile/getHomePosts'] : null
            },
            computedItemList(){
                return ['learner', 'parent', 'facilitator','schools','professionals']
            },
            computedSwitch(){
                // return true
                return this.getUser && this['profile/getProfile'] && this.getUser.id === this['profile/getProfile'].user_id ? true : false
            },
            computedLoading(){
                return this.authenticatingUser || this.getLoading ? true : false
            },
        },
        data() {
            return {
                showList: false,
                itemList: [],
                switchButtonText: 'switch account',
                showEditProfile: false,
                profileAccountId: null,
                profileAccount: '',
                nextPage: 0,
                showLoginRegister: '', //for asking guests to login or register"
                //media modal
                showMediaModal: false,
                modalAlertError: false,
                modalAlertSuccess: false,
                modalAlertMessage: '',
                mediaData: null,
                mediaJustUrl: false,
                mediaUrl: '',
                mediaUrlType: '',
                //post modal
                showPostModal: false,
                postModalData: null,
                postModalType: '',
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                vm.profileAccountId = to.params.accountId
                vm.profileAccount = to.params.account
                vm.getData()
                vm.getPosts()
            });
        },
        beforeRouteUpdate(to, from, next) {
            this.profileAccountId = to.params.accountId
            this.profileAccount = to.params.account
            this.getData()
            this.getPosts()
            next();
        },
        methods: {
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
            clearAlert(){
                this.modalAlertError = false
                this.modalAlertSuccess = false
                this.modalAlertMessage = ''
            },
            askLoginRegister(){
                this.showLoginRegister = true
            },
            mediaModalDisappear(){
                this.showMediaModal = false
                this.mediaData = null
                this.mediaJustUrl = false
                this.mediaUrl = ''
                this.mediaUrlType = ''
            },
            clickedMedia(data){
                if (data.hasOwnProperty('media')) {
                    this.mediaData = data
                    this.mediaJustUrl = false
                } else {
                    this.mediaUrl = data.url
                    this.mediaUrlType = data.mediaType
                    this.mediaJustUrl = true
                }
                this.showMediaModal = true
            },
            async infiniteHandler($state){
                if (!this['profile/getPostsDone']) {
                    let data = {
                        account: this.profileAccount,
                        accountId: this.profileAccountId,
                        nextPage: this['profile/getPostNextPage']
                    }
                    let response = await this['profile/getProfilePosts'](data)

                    if (response ) {
                        $state.loaded()
                    } else {
                        $state.complete()
                    }
                } 
            },
            getPosts(){
                let account = this.profileAccount
                let accountId = this.profileAccountId

                this['profile/clearPosts']()
                this['profile/getProfilePosts']({
                    account, accountId
                })
            },
            editProfile(){
                this.showEditProfile = true
            },
            editProfilePicture(){
                
            },
            switchClicked(){
                if (this.switchButtonText === 'switch account') {
                    this.switchButtonText = 'stop switching'
                    this.showList = true
                } else {
                    this.switchButtonText = 'switch account'
                    this.showList = false
                }
            },
            getData() {
                let account = this.profileAccount
                let accountId = this.profileAccountId

                this.profileGet({
                    account, accountId
                })
            },
            ...mapActions(['profileGet','profile/getProfilePosts','profile/clearPosts']),
        },
    }
</script>

<style lang="scss" scoped>
    $profile-picture-main-width: 150px;

    .loading{
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-wrapper{
        position: relative;

        .switch-buttons{
            position: absolute;
            right: 0;
            padding: 5px;
            display: block;
            z-index: 1000;
            width: 40%;
            text-align: end;
        }

        .activity {
            
            .activity-main{
                .created,
                .body .text-short > div:nth-child(2){
                    font-size: 2.2vw;
                }
            }
        }
    }

@media screen and (min-width:800px) and (max-width:1100px){

}

// ....................................//
@media screen and (max-width: 800px){
    $profile-picture-main-width: 100px;

    .profile-wrapper{

        .switch-buttons{
            width: 50%;
        }
        
        .first-section{
            
            .profile-picture {
                width: $profile-picture-main-width;
                height: $profile-picture-main-width;
            }
        }
    }

    .activity {
        
        .activity-main{
            .created,
            .body .text-short > div:nth-child(2){
                font-size: 2.2vw;
            }
        }
    }
}

@media screen and (max-width:400px) {
    .profile-picture{

        .switch-buttons{
            width: 70%;
        }
    }
}
</style>