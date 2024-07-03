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
                    <div class="sub" >
                        <post-button
                            buttonText='edit'
                            :active='true'
                            @click="editProfile"
                            v-if="computedSwitch"
                        ></post-button>
                        <div class="flag-profile"
                            @click="clickedFlag"
                            :class="{flagged:isFlagged,flagActive:flagActive}"
                            :title="flagTitle"
                            v-if="!computedOwner && computedFlags"
                        >
                            <font-awesome-icon :icon="['fa','flag']"></font-awesome-icon>
                        </div>
                    </div>
                    <div class="reason">
                        <flag-reason
                            :show="showFlagReason"
                            :hasBackground="true"
                            @continueFlagProcess="continueFlagProcess"
                            @reasonGiven="reasonGiven"
                            @cancelFlagProcess="cancelFlagProcess"
                        ></flag-reason>
                    </div>
                    <div class="profile-profiles"
                        v-if="showProfiles"
                    >
                        <span>
                            {{showProfilesText}}
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
                <profile-first
                    @clickedMedia="clickedMedia"
                    :isFlagged="isFlagged"
                ></profile-first>
                <profile-second
                    @clickedMedia="clickedMedia"
                    @clickedShowPostComments="clickedShowPostComments"
                    @clickedShowPostPreview="clickedShowPostPreview"
                    @editProfile="showEditProfile = true"
                    @askLoginRegister="askLoginRegister"
                    :account="profileAccount"
                    :accountId="profileAccountId"
                    :isFlagged="isFlagged"
                    @clickedUnflag="flag(null)"
                    infinite-wrapper
                ></profile-second>
            </div>
                <Infinite-Loading
                    @infinite="infiniteHandler"
                    v-if="computedPosts"
                    force-use-infinite-wrapper
                ></Infinite-Loading>
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
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    @disappear="showSmallModal = false"
                    :showForm='showLoginRegister'
                    :title="smallModalTitle"
                >
                    <template slot="other" v-if="showSmallModalOther && !smallModalDelete">
                        <router-link to="/login">login</router-link> or 
                        <router-link to='/register'>register</router-link> to interact and grow in a positve way.
                    </template>
                    <template slot="other" v-if="!showSmallModalOther && !smallModalDelete">
                        <router-link to="/welcome">welcome</router-link>
                    </template>
                    <template slot="actions" v-if="smallModalDelete">
                        <post-button
                            buttonText="yes"
                            @click="clickedYes"
                            v-if="smallModalDelete"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedNo"
                            v-if="smallModalDelete"
                        ></post-button>
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
import PostButton from '../components/PostButton.vue'
import EditProfile from '../components/forms/EditProfile.vue'
import FadeUp from '../components/transitions/FadeUp.vue'
import JustFade from '../components/transitions/JustFade.vue'
import AppNav from '../components/Nav.vue'
import MainList from '../components/MainList.vue'
import ProfileBar from '../components/profile/ProfileBar.vue'
import FlagReason from '../components/FlagReason.vue'
import ProfileFirst from '../components/profile/ProfileFirst.vue'
import ProfileSecond from '../components/profile/ProfileSecond.vue'
import { mapGetters, mapActions } from "vuex";
import { useRoute, useRouter } from 'vue-router'

    export default {
        components: {
            
            AppNav,
            JustFade,
            FadeUp,
            
            EditProfile,
            PostButton,
            ProfileBar,
            MainList,
            ProfileFirst,
            ProfileSecond,
            FlagReason,
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
                // flags
                showFlagReason: false,//it also pushes reaction section down to show flag reason
                flagReason: '',
                isFlagged: false,
                myFlag: null,
                flagTitle: '',
                flagActive: false,
                //small modal
                smallModalTitle: '',
                showSmallModalOther: false,
                // profiles
                showProfilesText: '',
                showProfiles: false,
                showProfilesAction: '',
                smallModalData: null,
                showSmallModal: false,
                smallModalDelete: false,
            }
        },
        watch: {
            isFlagged(newValue){
                if (newValue) {
                    this.flagTitle = 'unflag this answer'
                } else {
                    this.flagTitle = 'flag this answer'
                }
            },
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
        mounted () {
            this.listen()
        },
        computed: {
            ...mapGetters(['authenticatingUser','getUser','getLoading','profile/getProfile',
                'profile/profilePosts','profile/getPostNextPage','getProfiles']),
            computedPosts(){
                return this['profile/profilePosts'] && this['profile/profilePosts'].length > 0 ? 
                    this['profile/profilePosts'] : null
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
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
            computedOwner(){
                if (this.getUser && this['profile/getProfile']) {
                    
                    return this.getUser.id === this['profile/getProfile'].user_id ? true : false
                }
                return false
            },
            computedFlags(){ //check flagging
                if (this.getUser) {
                    if (this['profile/getProfile'] && this['profile/getProfile'].hasOwnProperty('flags')){
                        let flags = this['profile/getProfile'].flags
                        let index = null
                        index = flags.findIndex(flag=>{
                                return flag.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.myFlag = flags[index]
                            this.isFlagged = true
                        }
                    }
                }
                return true
            },
            computedRoute() {
                return useRoute()
            },
        },
        methods: {
            ...mapActions(['profileGet','profile/getProfilePosts','profile/clearPosts',
                'profile/createFlag','profile/deleteFlag',
                'profile/newPost','profile/removePost','profile/replacePost',
                'profile/newComment','profile/removeComment','profile/replaceComment',
                'profile/newDiscussion','profile/removeDiscussion','profile/replaceDiscussion',
                'profile/newFlag','profile/newLike','profile/removeLike',
                'profile/newAttachment','profile/removeAttachment',
            ]),
            listen(){
                Echo.channel(`youredu.${this.computedRoute.params.account}.${this.computedRoute.params.accountId}`)
                .listen('.newPost', (post)=>{
                    console.log(post)
                    this['profile/newPost'](post.post)
                })
                .listen('.updatePost', post=>{
                    console.log(post)
                    this['profile/replacePost'](post.post)
                })
                .listen('.deletePost', postInfo=>{
                    console.log(postInfo)
                    this['profile/removePost'](postInfo)
                })
                .listen('.newComment', (commentData)=>{
                    console.log(commentData)
                    this['profile/newComment'](commentData)
                })
                .listen('.updateComment', commentData=>{
                    console.log(commentData)
                    this['profile/replaceComment'](commentData)
                })
                .listen('.deleteComment', commentInfo=>{
                    console.log(commentInfo)
                    this['profile/removeComment'](commentInfo)
                })
                .listen('.newAttachment', (attachmentData)=>{
                    console.log(attachmentData)
                    this['profile/newAttachment'](attachmentData)
                })
                .listen('.deleteAttachment', attachmentInfo=>{
                    console.log(attachmentInfo)
                    this['profile/removeAttachment'](attachmentInfo)
                })
                .listen('.newFlag', (flag)=>{
                    console.log(flag)
                    this['profile/newFlag'](flag)
                })
                .listen('.newLike', (like)=>{
                    console.log(like)
                    this['profile/newLike'](like)
                })
                .listen('.deleteLike', likeInfo=>{
                    console.log(likeInfo)
                    this['profile/removeLike'](likeInfo)
                })
                .listen('.newDiscussion', (discussion)=>{
                    console.log(discussion)
                    this['profile/newDiscussion'](discussion.discussion)
                })
                .listen('.updateDiscussion', discussion=>{
                    console.log(discussion)
                    this['profile/replaceDiscussion'](discussion.discussion)
                })
                .listen('.deleteDiscussion', discussionInfo=>{
                    console.log(discussionInfo)
                    this['profile/removeDiscussion'](discussionInfo)
                })
            },
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
            askCreateAccount(){
                this.smallModalTitle = 'visit your welcome page to create accounts with which to interact.'
                this.showSmallModalOther = false
                this.showSmallModal = true
                this.showLoginRegister = true
                setTimeout(() => {
                    this.clearSmallModal()
                }, 3000);
            },
            clearSmallModal(){
                this.showLoginRegister = false
                this.smallModalDelete = false
                this.showSmallModal = false
                this.showProfilesAction = ''
                this.smallModalTitle = ''
                this.alertSuccess = false
                this.alertDanger = false
                // this.smallModalAlerting = false
                this.smallModalAction = ''
            },
            askLoginRegister(){
                this.smallModalTitle = 'welcome to this new community.'
                this.showSmallModalOther = true
                this.showLoginRegister = true
                this.showSmallModal = true
                setTimeout(() => {
                    this.clearSmallModal()
                }, 3000);
            },
            mediaModalDisappear(){
                this.showMediaModal = false
                this.mediaData = null
                this.mediaJustUrl = false
                this.mediaUrl = ''
                this.mediaUrlType = ''
            },
            clickedYes(){
                this.flag(this.smallModalData)
            },
            clickedNo(){
                this.clearSmallModal()
            },
            profilesAppear(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            reasonGiven(data){
                this.showFlagReason = false
                this.flagReason = data
                this.showProfilesText = 'flag as'
                this.profilesAppear()
            },
            continueFlagProcess(){
                this.flagReason = null
                this.showFlagReason = false
                this.showProfilesText = 'flag as'
                this.profilesAppear()
            },
            cancelFlagProcess(){
                this.flagReason = ''
                this.showFlagReason = false
            },
            clickedProfile(who){
                this.showProfiles = false
                this.smallModalTitle = 'are you sure you want to flag this?'
                this.smallModalAction = 'flag'
                this.smallModalDelete = true
                this.showSmallModal = true
                this.smallModalData = who
                setTimeout(() => {
                    this.clearSmallModal()
                }, 4000);
            },
            clickedFlag(){
                this.flagActive = !this.flagActive
                if (this.isFlagged) {
                    this.flag(null)
                    return
                }
                if (!this.getUser) {
                    this.askLoginRegister()
                } else if (!this.getProfiles.length) { // to ensure that people with no profiles dont like/comment/flag
                    this.askCreateAccount()
                } else {
                    this.showFlagReason = true
                }
            },
            async flag(who){
                this.loading = true
                let data = {}
                data.profile = true
                let response = null
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    data.item = this.computedRoute.params.account
                    data.itemId = this.computedRoute.params.accountId
                    data.reason = this.flagReason

                    response = await this['profile/createFlag'](data)
                } else {
                    data.flagId = this.myFlag.id
 
                    response = await this['profile/deleteFlag'](data)
                }

                this.loading =false
                if (response.status) {
                    this.alertSuccess = true
                    if (this.isFlagged) {
                        this.isFlagged = false
                    } else {
                        this.alertModalMessage = 'successfully flagged'
                    }
                    // this.smallModalAlerting = true
                } else {
                    this.alertDanger = true
                    this.alertModalMessage = 'flagging successful'
                    console.log(response)
                }
                this.flagReason = ''
                this.smallModalData = null
                setTimeout(() => {
                    this.clearSmallModal()
                }, 3000);
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
            async getData() {
                let account = this.profileAccount
                let accountId = this.profileAccountId

                const router = useRouter()

                if (!['learner', 'parent', 'facilitator', 'school', 'professional'].includes(account)) {
                    router.push({
                        name: '404',
                        params:{
                            message: `The url you entered doesn't work. Replace ${account} with learner, parent, or any other user type.`
                        }
                    })

                    return
                }

                const result = await this.profileGet({
                    account, accountId
                })

                if (!result.status)
                    router.push({
                        name: '404',
                        params: {
                            message: response.data.message ?? "something might have happened or you are accessing a profile which doen't exits."
                        }
                    }) 
            },
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

            .sub{
                display: inline-flex;
                align-items: center;

                .flag-profile{
                    font-size: 14px;
                    margin: 0 10px;
                    padding: 5px;
                    cursor: pointer;
                    color: gray;

                    &:hover{
                        transition: all .5s ease;
                        box-shadow: 0 0 3px gray;
                    }
                }

                .flagActive{
                    transition: all .5s ease;
                    box-shadow: 0 0 3px gray;
                }

                .flagged{
                    color: red;
                }
            }

            .profile-profiles{
                font-size: 14px;
                background-color: aliceblue;
                text-align: start;
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