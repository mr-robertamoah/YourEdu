<template>
    <div>
        <div class="main-alert" v-if="alerts.length">
            <main-alert
                v-for="alert in alerts"
                :key="alert.id"
                :id="alert.id"
                :show="alert.hasOwnProperty('id')"
                :isMessage="alert.isMessage"
                :text="alert.text"
                :isAccount="alert.isAccount"
                @removeAlert="clickedRemoveAlert"
                :account="alert.account"
                :message="alert.message"
            ></main-alert>
        </div>
        <div class="nav-outer">
            <div class="nav-shadow-wrapper" 
                @click.self="showOrHide()"
                v-if="show"
            ></div>
            <div class="nav-menu-container" @click="showOrHide()">
                <div class="nav-container-outer"
                    :class="{navNotification:newNotification}"
                >
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
                                                v-if="requestNotifications.length"
                                            >{{requestNotifications.length}}</div>
                                    </a>
                                    <a href="#" 
                                        v-if="getUser" 
                                        class="request"
                                        @click="clickedNotifications">
                                            <div>
                                                <font-awesome-icon :icon="['fa','bell']"></font-awesome-icon>
                                            </div>
                                            <div
                                                class="notification"
                                                v-if="otherNotifications.length"
                                            >{{otherNotifications.length}}</div>
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
                                        v-if="profileAccount != profile.account ||
                                            profileAccountId != profile.accountId"
                                        :profile="profile"
                                    ></profile-bar>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </fade-up>
            <request-modal
                :show="showRequestModal"
                :type="requestModalType"
                @requestsModalDisappear="requestsModalDisappear"
                :requestNotifications="requestNotifications.length"
            ></request-modal>
        </div>
    </div>
</template>

<script>
import ProfilePicture from "../components/profile/ProfilePicture.vue";
import ProfileBar from "../components/profile/ProfileBar.vue";
import FadeRight from "../components/transitions/FadeRight.vue";
import FadeUp from "../components/transitions/FadeUp.vue";
import MainAlert from "../components/transitions/MainAlert.vue";
import RequestModal from "../components/RequestModal.vue";
import {TokenService} from "../services/token.service";
import { mapActions, mapGetters } from "vuex";
import { dates, strings } from '../services/helpers';
import { useRoute, useRouter } from "vue-router";

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
            MainAlert,
            FadeUp,
            FadeRight,
            ProfileBar,
            ProfilePicture,
        },
        data() {
            return {
                navState: ['fa','bars'],
                show: false,
                showProfiles: false,
                ///follow requests
                showRequestModal: false,
                otherNotifications: [], 
                requestNotifications: [], 
                newNotification: false,
                requestModalType: '',
                //alert
                alerts: [],
            }
        },
        watch: {
            newNotification(newValue) {
                if (newValue) {
                    setTimeout(() => {
                        this.newNotification = false
                    }, 4000);
                }
            }
        },
        beforeRouteUpdate(to, from, next) {
            this.showProfiles = false
            next();
        },
        computed:{
            ...mapGetters(['getProfiles','getUser', 'getLoggedin','getUserFollowRequest',
                ]),
            computedRouteName() {
                return useRoute().name
            },
            computedRegistration(){
                return this.getLoggedin ? false : 
                    this.computedRouteName !== 'register' ? true : false
            },
            computedLogin(){
                return this.getLoggedin ? false : 
                    this.computedRouteName !== 'login' ? true : false
            },
            computedWelcome(){
                return this.getLoggedin ? 
                    this.computedRouteName === 'welcome' ? false : true : false
            },
            computedDashboard(){
                return this.getLoggedin ? 
                    this.computedRouteName === 'dashboard' ? false : true : false
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
        },
        methods: {
            ...mapActions(['logout', 'userNotifications', 'markNotifications',
                'addUserFollower','markOtherNotifications','dashboard/addAccountDetails',
                'addProfile']),
            requestsModalDisappear(){
                this.showRequestModal = false
            }, //generalize requestNotifications
            clickedRequest(){
                this.requestModalType = 'requests'
                this.showRequestModal = true
                if (this.requestNotifications.length) {
                    this.markUserNotifications()
                }
            },
            clickedNotifications(){
                this.requestModalType = 'notifications'
                this.showRequestModal = true
                this.markOtherUserNotifications()
            },
            async markUserNotifications(){

                let response = await this.markNotifications()

                if (response.status) {
                    this.requestNotifications = []
                } else {
                    console.log('response :>> ', response);
                }
            },
            async markOtherUserNotifications(){

                let response = await this.markOtherNotifications({other:true})

                if (response.status) {
                    this.otherNotifications = []
                } else {
                    console.log('response :>> ', response);
                }
            },
            async getNotifications(){
                let response = await this.userNotifications()

                if (response.status) {
                    this.requestNotifications = response.data
                } else {
                    console.log('response :>> ', response);
                }
                this.listen()
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
            clickedRemoveAlert(id){
                let index = this.alerts.findIndex(a=>{
                    return a.id === id
                })
                if (index > -1) {
                    this.alerts.splice(index,1)
                }
            },
            clearAlert(id){
                setTimeout((id) => {
                    this.clickedRemoveAlert(id)
                }, 5000);
            },
            handleNotification(notification) {
                console.log(notification);
                let alert = {
                    isMessage: true,
                    account: notification.account,
                    text: notification.message,
                    id: Math.floor(Math.random() * 10000)
                }

                this.alerts.unshift(alert)

                this.clearAlert(alert.id)
            },
            listen(){

                if (this.getUser && TokenService.getToken()) {
                    
                    Echo.private(`youredu.user.${this.getUser.id}`)
                        .notification((notification) => {
                            console.log(notification);
                            if (notification.type == 'App\\Notifications\\DiscussionRequestNotification' ||
                                notification.type == 'App\\Notifications\\FollowRequest' ||
                                notification.type == 'App\\Notifications\\NewDiscussionMessageNotification') {
                                
                                this.requestNotifications.push(notification)
                            } else if (notification.type === 'App\\Notifications\\DiscussionJoinResponseNotification') {
                                this.otherNotifications.push(notification)
                                let alert = {
                                    isMessage: true,
                                    text: `your request to join discussion with title: ${notification.title.toUpperCase()} has been ${notification.action}`
                                }
                                alert.id = Math.floor(Math.random() * 10000)
                                this.alerts.unshift(alert)
                                this.clearAlert(alert.id)
                            } else if (notification.type === 'App\\Notifications\\DiscussionInvitationNotification') {
                                this.requestNotifications.push(notification)
                                this.otherNotifications.push(notification)
                                let alert = {
                                    isMessage: true,
                                    text: notification.message
                                }
                                alert.id = Math.floor(Math.random() * 100)
                                this.alerts.unshift(alert)
                                this.clearAlert(alert.id)
                            } else if (notification.type === 'App\\Notifications\\AccountRequestNotification' ||
                                notification.type === 'App\\Notifications\\AccountResponseNotification') {
                                this.requestNotifications.push(notification)

                                let alert = {
                                    isMessage: true,
                                    text: notification.message,
                                    account: notification.account,
                                }
                                alert.id = Math.floor(Math.random() * 10000)
                                this.alerts.unshift(alert)
                                this.newNotification = true
                                this.clearAlert(alert.id)
                            } else if (notification.type === 'App\\Notifications\\DiscussionInvitationResponseNotification' ||
                                notification.type === 'App\\Notifications\\UpdateParticipantStateNotification' ||
                                notification.type === 'App\\Notifications\\UpdateParticipantStateNotification' ||
                                notification.type === 'App\\Notifications\\AdminResponseNotification' ||
                                notification.type === 'App\\Notifications\\FacilitatorResponseNotification' ||
                                notification.type === 'App\\Notifications\\SchoolResponseNotification' ||
                                notification.type === 'App\\Notifications\\CollaborationNotification' ||
                                notification.type === 'App\\Notifications\\RemoveDiscussionParticipantNotification') {
                                this.otherNotifications.push(notification)
                                console.log(notification);
                                let alert = {
                                    isMessage: true,
                                    account: notification.account,
                                    text: notification.message
                                }
                                alert.id = Math.floor(Math.random() * 10000)
                                this.alerts.unshift(alert)
                                this.clearAlert(alert.id)
                                if (notification.admin && this.computedRouteName === 'dashboard') {                                    
                                    this['dashboard/addAccountDetails']({
                                        account: notification.accountData.account,
                                        accountId: notification.accountData.accountId,
                                        what: 'admin',
                                        data: notification.admin
                                    })
                                } else if (notification.facilitator && this.computedRouteName === 'dashboard') {                                    
                                    this['dashboard/addAccountDetails']({
                                        account: notification.accountData.account,
                                        accountId: notification.accountData.accountId,
                                        what: 'facilitator',
                                        data: notification.facilitator
                                    })
                                } else if (notification.school && 
                                    this.computedRouteName === 'dashboard' &&
                                    notification.accountData.account !== 'admin') {                                    
                                    this['dashboard/addAccountDetails']({
                                        account: notification.accountData.account,
                                        accountId: notification.accountData.accountId,
                                        what: 'school',
                                        data: notification.school
                                    })
                                } else if (notification.school && 
                                    this.computedRouteName === 'dashboard' &&
                                    notification.accountData.account === 'admin') {                                    
                                    this.addProfile(notification.school)
                                }
                            }  else if (notification.type === 'App\\Notifications\\RequestMessageNotification') {
                                
                                let alert = {
                                    isMessage: true,
                                    account: notification.account,
                                    text: notification.message
                                }
                                alert.id = Math.floor(Math.random() * 10000)
                                this.alerts.unshift(alert)
                                this.clearAlert(alert.id)
                            }  else if (notification.type === 'App\\Notifications\\BanNotification') {
                                let message,
                                    action = notification.ban.type === 'overall' ? '' : 
                                        `${notification.ban.type.toLowerCase()} `,
                                    account = notification.ban.account === 'user' ? 'you' :
                                        notification.ban.account
                                if (notification.ban === 'SERVED' || notification.ban === 'PENDING') {
                                    if (notifcation.ban.username) {
                                        message = `you have been served ${action}ban which will last until ${dates.dateReadable(notification.ban.dueDate)}`
                                    } else {
                                        message = `your ${account} account has been served ${action}ban which will last until ${dates.dateReadable(notification.ban.dueDate)}`
                                    }
                                } else if (notification.ban === 'UNSERVED' || notification.ban === 'RESOLVED') {
                                    if (notifcation.ban.username) {
                                        message = `your ${action} ban with the due date of ${dates.dateReadable(notification.ban.dueDate)}, has been removed.`
                                    } else {
                                        message = `the ${action} ban served to ${account} account, with due date of ${dates.dateReadable(notification.ban.dueDate)}, has been removed.`
                                    }
                                }
                                
                                let alert = {
                                    isMessage: true,
                                    text: message
                                }
                                alert.id = Math.floor(Math.random() * 10000)
                                this.alerts.unshift(alert)
                                this.clearAlert(alert.id)
                            } else {
                                this.handleNotification(notification)
                            }

                            this.newNotification = true
                        })
                        .listen('.newFollower', data=>{
                            console.log('data :>> ', data);
                            let alert = {}
                            alert.id = Math.floor(Math.random() * 10000)
                            alert.account = {
                                account: strings.getAccount(data.follower.followedby_type),
                                accountId: data.follower.followedby_id,
                                myAccount: strings.getAccount(data.follower.followable_type),
                                myName: data.follower.my_name,
                                url: data.follower.url,
                                action: data.action
                            }
                            alert.isAccount = true
                            this.alerts.unshift(alert)
                            setTimeout(() => {
                                this.clearAlert(alert.id)
                            }, 5000);
                            this.addUserFollower(data.follower)
                        })
                        .listen('.newDiscussionMessageResponse', data=>{
                            console.log('data :>> ', data);
                            let alert = {}
                            alert.id = Math.floor(Math.random() * 10000)
                            alert.message = data.message
                            alert.isMessage = true
                            this.alerts.unshift(alert)
                            setTimeout(() => {
                                this.clearAlert(alert.id)
                            }, 5000);
                        })
                } else {
                    setTimeout(() => {
                        this.listen()
                    }, 3000);
                }
            },
            async navLogout(){
                const result = await this.logout()

                if (result.status)
                    useRouter().push('/login')
            },
        },
        mounted () {
            setTimeout(() => {
                this.listen()                
            }, 2000);
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

.main-alert{
    background: transparent;
    position: fixed;
    width: fit-content;
    max-width: 50%;
    height: 300px;
    top: 40px;
    padding: 10px;
    right: 0;
    z-index: 1;
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

        .navNotification{
            background: red;
            animation-name: shake;
            animation-duration: 1.5s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;

            .nav-container-inner{

                animation-name: spin;
                animation-duration: 2s;
                animation-iteration-count: infinite;
                animation-timing-function: ease-in-out;
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

@media only screen and (max-width: 800px){

    .main-alert{
        max-width: 75%;
        width: 100%;
        z-index: 1;
    }
    
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

@media only screen and (max-width: 400px){

    .main-alert{
        max-width: 100%;
        width: 100%;
    }
    
    .nav-main-container{
        margin: -20px 0 0 auto;
        width: 100%;
    }
    
}

@keyframes spin {
    from{
        transform: rotateZ(0deg);
    }

    to{
        transform: rotateZ(360deg);
    }
}

@keyframes shake {
    0%{
        transform: translate(10px,0);
    }

    33%{
        transform: translate(0,10px);
    }

    66%{
        transform: translate(-10px,0);
    }

    100%{
        transform: translate(0,-10px);
    }
}

</style>