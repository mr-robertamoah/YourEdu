<template>
    <div class="nav-outer">
        <div class="nav-shadow-wrapper" 
            @click.self="showOrHide()"
            v-if="show"
        ></div>
        <div class="nav-menu-container" @click="showOrHide()">
            <div class="nav-container-outer">
                <div class="nav-container-inner">
                    <div>
                        <font-awesome-icon :icon="navState"/>
                    </div>
                </div>
            </div>
        </div>
        <transition name="nav-bar">
            <div class="nav-main-container" 
                v-if="show"
            >
                <div class="nav-main-main">
                    <div class="nav-main-logo">
                        <router-link class="logo" to="/">
                            <div class="logo-main">
                                YourEdu
                            </div>
                        </router-link>
                    </div>
                    <div class="nav-main-login">
                        <div class="nav-login-section">
                            <router-link 
                                v-if="computedRegistration"
                                to="/register">Register</router-link>
                            <router-link 
                                v-if="computedLogin"
                                to="/login">Login</router-link>
                            <a href="#" 
                                v-if="getUser" 
                                @click.prevent="navLogout()">Logout</a>
                            <a href="#" 
                                v-if="getProfiles" 
                                @click="clickedRequest">Requests
                                </a>
                        </div>
                    </div>
                </div>
                <div class="nav-main-other">
                    <div class="nav-other-section">
                        <router-link 
                            v-if="computedWelcome"
                            to="/welcome">Welcome</router-link>
                        <router-link 
                            v-if="computedDashboard" 
                            to="/dashboard">Dashboard</router-link>
                        <div class="a-profile"
                            v-if="computedProfiles"
                            @click.prevent="showProfiles = !showProfiles"
                        >Profiles</div>
                    </div>
                    <div class="nav-other-sub" v-if="showProfiles">
                        <div v-for="(profile,key) in computedProfiles"
                            :key="key">
                            <profile-bar
                                v-if="profileAccount != profile.params.account ||
                                    profileAccountId != profile.params.accountId"
                                :name="profile.name"
                                :type="profile.params.account"
                                :src="profile.url"
                                :routeParams="profile.params"
                            ></profile-bar>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <just-fade>
            <template slot="transition" v-if="showRequestModal">
                <main-modal
                    :show="showRequestModal"
                    @mainModalDisappear="requestsModalDisappear"
                    @clickedMain="showFollowProfiles = false"
                    :main="false"
                >
                    <template slot="main-other">
                        <fade-left-fast>
                            <template slot="transition"
                                v-if="showFollowProfiles"
                            >
                                <div class="profiles">
                                    <span>
                                        follow as
                                    </span>
                                    <div :key="key" v-for="(profile,key) in computedProfiles">
                                        <profile-bar
                                            :name="profile.name"
                                            :type="profile.params.account"
                                            :smallType="true"
                                            :routeParams="profile.params"
                                            :navigate="false"
                                            @clickedProfile="clickedProfile"
                                        ></profile-bar>
                                    </div>
                                </div>
                            </template>
                        </fade-left-fast>
                        <div class="no-requests" 
                            v-if="!computedRequests">
                            there are no requests
                        </div>
                        <fade-right>
                            <template slot="transition">
                                <profile-bar
                                    v-for="request in requests"
                                    :key="request.id"
                                    @clickedAction="clickedRequestAction"
                                    :id="request.id"
                                    :name="request.name"
                                    greenActionTitle="follow back"
                                    redActionTitle="decline"
                                    :routeParams="request.params"
                                    :type="request.params.account"
                                    :navigate="false"
                                    :actions="true"
                                    :maxType="true"
                                    @clickedProfileBar="showFollowProfiles = false"
                                ></profile-bar>
                            </template>
                        </fade-right>
                        <div class="show-more"
                            @click="infiniteHandler"
                            v-if="showMoreRequests"
                        >
                            show more
                        </div>
                        <!-- <infinite-loader @infinite="infiniteHandler"></infinite-loader> -->
                    </template>
                </main-modal>
            </template>
        </just-fade>
    </div>
</template>

<script>
import ProfilePicture from "../components/profile/ProfilePicture";
import ProfileBar from "../components/profile/ProfileBar";
import FadeRight from "../components/transitions/FadeRight";
import FadeLeftFast from "../components/transitions/FadeLeftFast";
import InfiniteLoader from "vue-infinite-loading";
import { mapActions, mapGetters } from "vuex";

    export default {
        props: {
            profileAccountId: {
                type: String,
                default: ''
            },
            profileAccount: {
                type: String,
                default: ''
            },
        },
        components: {
            InfiniteLoader,
            FadeLeftFast,
            FadeRight,
            ProfileBar,
            ProfilePicture,
        },
        data() {
            return {
                navState: ['fa','bars'],
                show: false,
                isUser: null,
                navRoutePath:'',
                loginRoutePath:null,
                registerRoutePath:null,
                welcomeRoutePath:null,
                dashboardRoutePath:null,
                profileRoutePath:null,
                showProfiles: false,
                ///follow requests
                showRequestModal: false,
                requestNextPage: 1,
                requests: [],
                noRequests : false,
                showRequestAlt: false,
                showMoreRequests: false,
                actionLoading: false,
                showFollowProfiles: false,
                requestId : null,
            }
        },
        watch: {
            showRequestModal(newValue) {
                if (newValue) {
                    
                }
            }
        },
        beforeRouteUpdate(to, from, next) {
            this.showProfiles = false
            next();
        },
        computed:{
            ...mapGetters(['getProfiles','getUser', 'getLoggedin','getUserFollowRequest']),
            computedRequests(){
                if (this.getUser) {
                    if (this.getUser.follow_requests) {
                        this.noRequests = false
                    } else{
                        this.noRequests = true
                    }

                    return this.getUser.follow_requests
                } else {
                    return 0
                }
            },
            computedRegistration(){
                return this.getLoggedin ? false : 
                    this.registerRoutePath ? true : false
            },
            computedLogin(){
                return this.getLoggedin ? false : 
                    this.loginRoutePath ? true : false
            },
            computedWelcome(){
                return this.getLoggedin ? 
                    this.$route.name === 'welcome' ? false : true : false
            },
            computedDashboard(){
                return this.getLoggedin ? 
                    this.$route.name === 'dashboard' ? false : true : false
            },
            computedProfiles(){ //replace with get profiles
                return this.getProfiles
            },
        },
        methods: {
            requestsModalDisappear(){
                this.showRequestModal = false
                this.showFollowProfiles = false
                this.requests = []
            },
            async clickedProfile(who){
                this.showFollowProfiles = false
                let data = {
                    requestId: this.requestId,
                    account: who.account,
                    accountId: who.accountId,
                }
                let response = await this.acceptFollowRequest(data)

                if (response === 'successful') {
                    this.removeRequest()
                }
            },
            removeRequest(){ //remove request on success
                this.showFollowProfiles = false
                    let requestIndex = this.requests.findIndex(request=>{
                        return request.id === this.requestId
                    })
                    if (requestIndex > -1) {
                        this.requests.splice(requestIndex,1)
                    }
            },
            async clickedRequestAction(barData){
                let response = null
                // this.showFollowProfiles = false
                this.requestId = barData.requestId
                let data = {
                    requestId: barData.requestId,
                }
                if (barData.action === 'accept') {
                    this.showFollowProfiles = true
                    // setTimeout(() => {
                    //     this.showFollowProfiles = false
                    // }, 4000);
                } else if (barData.action === 'decline') {
                    response = await this.declineFollowRequest(data)
                }
                if (response === 'successful') {
                    this.removeRequest()
                }
            },
            async clickedRequest(){
                if (this.computedRequests) {
                    this.infiniteHandler()
                }
                this.showRequestModal = true
            },
            async infiniteHandler(){
                let data = {
                    nextPage: this.requestNextPage,
                }

                let response = await this.userFollowRequests(data)

                if (response !== 'unsuccessful' ) {
                    this.requests.push(...response.data)
                    if (response.links.next) {
                        this.requestNextPage += 1
                        this.showMoreRequests = true
                    } else {
                        this.showMoreRequests= false
                    }
                } else {
                    $state.complete()
                    this.showMoreRequests = true
                }
            },
            showOrHide() {
                if (this.navState[1] ==='bars') {
                    this.navState[1] ='times'
                    this.show = true
                } else {
                    this.navState[1] ='bars'
                    this.show = false
                    this.showProfiles = false
                }
                
            },
            navLogout(){
                this.logout()
            },
            ...mapActions([
                'logout', 'acceptFollowRequest','declineFollowRequest',
                'userFollowRequests',
            ]),
        },
        created () {
            this.isUser = this.getUser
            
        },
    }
</script>

<style lang="scss" scoped>
$nav-container-outer: 50px;
$nav-container-inner: $nav-container-outer - 10px;
$nav-main-container: 85%;
$nav-container-outer-color: rgba(22, 233, 205, 1);
$nav-container-inner-color: aliceblue;
$nav-main-container-acolor: brown;
$nav-main-container-abackground: aliceblue;
$nav-main-container-background: lighten($nav-container-outer-color, 30);
$homeLogoColor : rgba(2, 104, 90, .6);

.nav-bar-enter, .nav-bar-leave-to{
    opacity: 0;
    transform: scale(0,0);
}

.nav-bar-enter-active, .nav-bar-leave-active{
    transition: all 1s ease-in-out;
}

.nav-outer{
    position: fixed;
    width: 100%;
    z-index: 1000;
    bottom: 30px;
    display: flex;

    .nav-shadow-wrapper{
        width: 100vw;
        height: 100vh;
        top: 0;
        position: inherit;
        background: none;
    }

    .nav-menu-container{
        position: absolute;
        width: auto;
        bottom: 0;
        z-index: 1;

        .nav-container-outer{
            width: $nav-container-outer;
            height: $nav-container-outer;
            background-color: $nav-container-outer-color;
            border-radius: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;

            .nav-container-inner{
                width: $nav-container-inner;
                height: $nav-container-inner;
                background-color: $nav-container-inner-color;
                border-radius: inherit;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
            }
        }
            
    }

    .nav-main-container{
        position: relative;
        background-color: $nav-main-container-background;
        width: $nav-main-container;
        min-height: $nav-container-inner;
        margin: -20px auto 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 10px;
        position: relative;
        border-radius: 5px;

        a, 
        .a-profile {
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            color: $nav-main-container-acolor;
            background-color: $nav-main-container-abackground;
            padding: 5px;
            margin: 5px 0;
            border-radius: 5px;
            padding: 5px;

            &:hover{
                background-color: $nav-main-container-acolor;
                color: $nav-main-container-abackground;
                opacity: .7;
                transition: all 1s ease;
            }
        }

        .nav-main-other{

            .nav-other-sub{
                    position: absolute;
                    // min-height: 100px;
                    right: 0;
                    bottom: 100%;
                    padding: 5px;
                    width: 50%;
                    margin: 0 auto;
                    padding-right: 0;

                div{
                    display: flex; 
                    flex-wrap: wrap;
                    flex-direction: row-reverse;
                }
            }
        }
    }

    .no-requests{
        min-height: 100px;
        width: 100%;
        text-align: center;
        padding: 10px;
    }
}

@media only screen and (max-width: 400px){
    
    .nav-main-container{
        margin: -20px 0 0 auto;
        width: 100%;
    }
    
}

@media only screen and (max-width: 800px){
    .nav-outer{

        .nav-main-container{

            .nav-main-main
            {
                min-width: 55%;
                display: block;
            }


            .nav-main-logo,
            .nav-main-login
            {
                width: 100%;
                display: flex;
                justify-content: center;
                margin: 20px 0;
            }

            .nav-main-other
            {
                width: 40%;
                margin-left: auto;

                .nav-other-section a,
                .nav-other-section .a-profile
                {
                    width: 100%;
                    display: block;
                }

                .nav-other-sub{
                    width: 80%;
                }
                
            }
            
        }
    }
    
}

@media only screen and (min-width: 800px){
    
    .nav-outer{

        .nav-main-container{
        .nav-main-main{
            display: contents;
        }

        .nav-main-login
        {
            order: 3;
        }
    }
    } 
}

</style>