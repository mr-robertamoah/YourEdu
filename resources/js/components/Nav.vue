<template>
    <div class="nav-outer">
        <div class="nav-shadow-wrapper" 
            @click.self="showOrHide()"
            v-if="show && computedUser"
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
        <fade-up>
            <template slot="transition">
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
                                    class="request"
                                    @click="clickedRequest">
                                        <div>Requests</div>
                                        <div
                                            class="notification"
                                            v-if="followNotifications.length"
                                        >{{followNotifications.length}}</div>
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
            </template>
        </fade-up>
        <request-modal
            :show="showRequestModal"
            @requestsModalDisappear="requestsModalDisappear"
            :notifications="followNotifications.length"
        ></request-modal>
    </div>
</template>

<script>
import ProfilePicture from "../components/profile/ProfilePicture";
import ProfileBar from "../components/profile/ProfileBar";
import FadeRight from "../components/transitions/FadeRight";
import FadeUp from "../components/transitions/FadeUp";
import RequestModal from "../components/RequestModal";
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
            RequestModal,
            FadeUp,
            FadeRight,
            ProfileBar,
            ProfilePicture,
        },
        data() {
            return {
                navState: ['fa','bars'],
                show: false,
                isUser: null,
                showProfiles: false,
                ///follow requests
                showRequestModal: false,
                followNotifications: []
            }
        },
        watch: {
            isUser(newValue) {
                if (newValue) {
                    this.listen()
                }
            }
        },
        beforeRouteUpdate(to, from, next) {
            this.showProfiles = false
            next();
        },
        computed:{
            ...mapGetters(['getProfiles','getUser', 'getLoggedin','getUserFollowRequest']),
            computedRegistration(){
                return this.getLoggedin ? false : 
                    this.$route.name !== 'register' ? true : false
            },
            computedLogin(){
                return this.getLoggedin ? false : 
                    this.$route.name !== 'login' ? true : false
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
            computedUser(){ 
                if (this.getUser) {
                    this.isUser = true
                } else {
                    this.isUser = false
                }
                return true
            },
        },
        methods: {
            ...mapActions([
                'logout', 'userFollowNotifications', 'markFollowNotifications'
            ]),
            requestsModalDisappear(){
                this.showRequestModal = false
            },
            clickedRequest(){
                this.showRequestModal = true
                if (this.followNotifications.length) {
                    this.markFollowNotifications()
                }
            },
            async getFollowNotifications(){
                let response = await this.userFollowNotifications()

                if (response !== 'unsuccessful') {
                    this.followNotifications = response
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
            listen(){
                Echo.private(`youredu.user.${this.getUser.id}`)
                    .notification(notification=>{
                        console.log(notification);
                    })
            },
            navLogout(){
                this.logout()
            },
        },
        mounted () {
            this.getFollowNotifications()
            // this.listen()
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

        .nav-main-login{

            .request{
                display: inline-flex;
                position: relative;

                .notification{
                    font-size: 9px;
                    color: $nav-main-container-acolor;
                    padding: 5px;
                    border-radius: 50%;
                    border: 2px solid $nav-main-container-acolor;
                    bottom: 65%;
                    right: -10%;
                    background-color: aliceblue;
                    position: absolute;
                }
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