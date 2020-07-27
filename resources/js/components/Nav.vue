<template>
    <div class="outer" @click.self="showOrHide()">
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
                                v-if="registerRoutePath"
                                to="/register">Register</router-link>
                            <router-link 
                                v-if="loginRoutePath"
                                to="/login">Login</router-link>
                            <a href="#" 
                                v-if="isUser" 
                                @click.prevent="navLogout()">Logout</a>
                        </div>
                    </div>
                </div>
                <div class="nav-main-other">
                    <div class="nav-other-section">
                        <router-link 
                            v-if="welcomeRoutePath"
                            to="/welcome">Welcome</router-link>
                        <router-link 
                            v-if="dashboardRoutePath" 
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
                                :src="profile.src"
                                :routeParams="profile.params"
                            ></profile-bar>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import ProfilePicture from "../components/profile/ProfilePicture";
import ProfileBar from "../components/profile/ProfileBar";
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
            }
        },
        beforeRouteUpdate(to, from, next) {
            this.showProfiles = false
            next();
        },
        components: {
            ProfileBar,
            ProfilePicture,
        },
        computed:{
            ...mapGetters(['getUser']),
            computedProfiles(){
                let profilesArray = []
                let computedArray = []
                if (this.getUser) {
                    profilesArray = this['getUser'].owned_profiles
                } else {
                    return null
                }
                        
                // console.log(profilesArray)

                if (profilesArray) {
                    computedArray =  profilesArray.map(el => {
                        return {
                            name: el.profile_name ? el.profile_name : 'no name',
                            src: el.profile_url,
                            params: {
                                account: el.account_type,
                                accountId: el.account_id,
                            },
                        }
                    })
                        
                    return computedArray
                } else {
                    return null
                }

            },
        },
        methods: {
            showOrHide() {
                if (this.navState[1] ==='bars') {
                    this.navState[1] ='times'
                    this.show = true
                } else {
                    this.navState[1] ='bars'
                    this.show = false
                }
            },
            navLogout(){
                this.logout()
            },
            ...mapActions([
                'logout',  
            ]),
            navCurrentLocation(){
                let navRoutePath = this.$router.history.current.name
                if (this.isUser) {
                    this.registerRoutePath = false
                    this.loginRoutePath = false
                    this.welcomeRoutePath = true
                    this.profileRoutePath = true
                    this.dashboardRoutePath = true

                    if (navRoutePath==='welcome') {
                        this.welcomeRoutePath = false
                    } else {
                        this.welcomeRoutePath = true
                    }

                    if (navRoutePath==='dashboard') {
                        this.dashboardRoutePath = false
                    } else {
                        this.dashboardRoutePath = true
                    }

                    if (navRoutePath==='profile') {
                        this.profileRoutePath = false
                    } else {
                        this.profileRoutePath = true
                    }
                } else {

                    if (navRoutePath==='register') {
                        this.registerRoutePath = false
                    } else {
                        this.registerRoutePath = true
                    }

                    if (navRoutePath==='login') {
                        this.loginRoutePath = false
                    } else {
                        this.loginRoutePath = true
                    }
                }

            }
        },
        created () {
            this.isUser = this.getUser
            this.navCurrentLocation()
            
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

.outer{
    position: fixed;
    width: 100%;
    z-index: 1000;
    bottom: 30px;
    display: flex;

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
}

@media only screen and (max-width: 400px){
    
    .nav-main-container{
        margin: -20px 0 0 auto;
        width: 100%;
    }
    
}

@media only screen and (max-width: 800px){
    .outer{

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
    
    .outer{

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