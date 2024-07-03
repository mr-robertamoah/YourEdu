<template>
    <div class="first-section">
        <div class="profile-picture">
            <profile-picture
                @editProfile="editProfilePicture"
            >
                <template slot="image">
                    <img :src="computedUrl" alt="profile picture"
                        @click="clickedMedia(computedUrl,'image')"
                    >
                </template>
            </profile-picture>
        </div>
        <div class="profile-about">
            <div class="border-style"></div>
            <div class="follows">
                <number-of v-if="computedFollows > -1">
                    followed by {{follows}}
                </number-of>
                <number-of v-if="computedFollowings > -1">
                    follows {{followings}}
                </number-of>
                <div class="action"
                >
                    <special-button 
                        :buttonText="followButtonText"
                        @click="clickedFollow"
                        v-if="computedFollowButton"
                    >
                        <template slot="loader" v-if="followLoading">
                            <pulse-loader 
                                :loading="followLoading"
                                :size="'9px'"
                            ></pulse-loader>
                        </template>
                    </special-button>
                    <div class="following"
                        v-if="followSuccessMessage.length"
                    >
                        {{followSuccessMessage}}
                    </div>
                    <div class="profiles"
                        v-if="showProfiles"
                    >
                        <span>
                            follow as
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
            </div> 
            <div class="name">
                {{computedName}}
            </div>
            <div class="username">
                @{{computedUsername}}
            </div>
            <div class="as">
                <div>{{computedAccount}}</div>
                <!-- <post-button buttonText="change"></post-button> -->
            </div>
            <div class="verification"
                v-if="computedVerification.length"
            >
                <div>
                    {{computedVerification}}
                </div>
            </div>   
            <div class="created-at">
                {{computedDate}}
            </div>            
        </div>
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    :title="smallModalTitle"
                    :show="showSmallModal"
                    @disappear="showSmallModal = false"
                >
                    <template slot="actions">
                        <post-button
                            buttonText="ok"
                            @click="clickedOk"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>
        <just-fade>
            <template slot="transition" 
                v-if="showPictureModal"
            >
                <main-modal
                    @mainModalDisappear="showPictureModal=false"
                    @mainModalAppear="pictureModalAppear"
                    :alertMessage="modalAlertMessage"
                    :alertError="modalAlertError"
                    :alertSuccess="modalAlertSuccess"
                    @clearAlert="clearAlert"
                    :loading="modalLoading"
                >
                    <template slot="loading">
                        <sync-loader :loading="modalLoading"></sync-loader>
                    </template>
                    <template slot="main">
                        <welcome-form>
                            <template slot="input">
                                <file-preview
                                    type="circle"
                                    :show="showPreview"
                                    :file="file"
                                    :hasCircleImg="showPreviousPic"
                                    :imgSrc="computedUrl"
                                    @removeFile="removeFile"
                                ></file-preview>
                                <div class="upload" @click="clickedAddFile">
                                    <font-awesome-icon
                                        :icon="['fa','file-image']"
                                    ></font-awesome-icon>
                                </div>
                                <input type="file" ref="inputfile" class="hidden"
                                    @change="inputFileChange">
                            </template>
                            <template slot="buttons" v-if="showPictureAdd">
                                <post-button
                                    buttonText="add"
                                    @click="clickedAdd"
                                ></post-button>
                            </template>
                        </welcome-form>
                    </template>
                </main-modal>
            </template>
        </just-fade>
    </div>
</template>

<script>
import SpecialButton from '../SpecialButton.vue'
import ProfileBar from '../profile/ProfileBar.vue'
import FadeUp from '../transitions/FadeUp.vue'
import PostButton from '../PostButton.vue'
import ProfilePicture from './ProfilePicture.vue'
import FilePreview from '../FilePreview.vue'
import NumberOf from '../NumberOf.vue'
import {dates} from '../../services/helpers'
import { mapGetters, mapActions } from 'vuex'
import { useRoute } from 'vue-router'

    export default {
        name: 'FirstSection',
        props: {
            isFlagged: {
                type: Boolean,
                default: false
            },
        },
        components: {
            FadeUp,
            ProfileBar,
            SpecialButton,
            
            FilePreview,
            PostButton,
            ProfilePicture,
            NumberOf,
        },
        data() {
            return {
                showSmallModal: false,
                smallModalLoading: false,
                showProfiles: false,
                followButtonText: '', // text for the follow and unfollow button
                myFollowId : -1, // id of this users follow on this account
                followSuccessMessage: '',
                follows: 0,
                followings: 0,
                smallModalTitle: '',
                modalLoading: false,
                showPictureModal: false,
                showPictureAdd: false,
                //alert
                modalAlertMessage: '',
                modalAlertError: false,
                modalAlertSuccess: false,
                //file preview
                showPreview: false,
                file: null,
                showPreviousPic: false,
                followLoading: false,
                imageType: 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp',
            }
        },
        watch: {
            file(newValue) {
                if (newValue) {
                    this.showPreviousPic = false
                } else {
                    this.showPreviousPic = true
                }
            }
        },
        computed: {
            ...mapGetters(['profile/getProfile','profile/getAccount','getProfiles',
                'getUser']),
            computedName(){
                if (! this['profile/getProfile']) {
                    return ''
                }

                return this['profile/getProfile'].name
            },
            computedUsername(){
                return this['profile/getProfile'] ? 
                    this['profile/getProfile'].username : ''
            },
            computedAccount(){
                if (this['profile/getProfile'] ) {
                    if (this['profile/getAccount'] === 'professional') {
                        return `${this['profile/getAccount']} (${this['profile/getProfile'].owner.role})`
                    }
                } else {
                    return ''
                }
                return this['profile/getAccount'] ? 
                    this['profile/getAccount'] : ''
            },
            computedRoute() {
                return useRoute()
            },
            computedFollowings(){
                if (this['profile/getProfile']) {
                    this.followings = this['profile/getProfile'].followings
                    return this['profile/getProfile'].followings
                } else {
                    this.followings = 0
                    return 0
                }
            },
            computedFollowing(){ //determines if a user follows this account
                if (this['profile/getProfile'] && this.getUser) {
                    let followers = this['profile/getProfile'].follows

                    let myFollowIndex = followers.findIndex(follower=>{
                        return this.getUser.id = follower.user_id
                    })

                    if (myFollowIndex > -1) {
                        this.myFollowId = followers[myFollowIndex].id
                        return true
                    }
                }
                return false
            },
            computedFollowButton(){
                if (!this.getUser) {
                    this.followButtonText = 'follow'
                    this.smallModalTitle = 'you must be logged in or registered to follow with any of your accounts'
                } else if (this.getUser && !this.getProfiles.length) {
                    this.followButtonText = 'follow'
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can follow.'
                } else if (this.computedOwner) {
                    return false
                } else if (this.getProfiles.length && this.computedFollowing) {
                    this.followButtonText = 'unfollow'
                } else if (this.getProfiles.length) {
                    this.followButtonText = 'follow'
                }
                return true
            },
            computedFollows(){
                if (this['profile/getProfile']) {
                    this.follows = this['profile/getProfile'].follows.length
                    return this['profile/getProfile'].follows.length
                } else {
                    this.follows = 0
                    return 0
                }
            },
            computedVerification(){
                if (this['profile/getAccount'] === 'learner') {
                    return ''
                }
                
                if (this['profile/getProfile']?.owner?.verification.status === 'VERIFIED') {
                    return 'verified'
                }

                return 'not verified'
            },
            computedDate(){
                return dates.dateReadable(this['profile/getProfile'].created_at)
            },
            computedUrl(){
                return this['profile/getProfile'] ? 
                    this['profile/getProfile'].url : ''
            },
            computedOwner(){
                if (this.getUser && this['profile/getProfile']) {
                    
                    return this.getUser.id === this['profile/getProfile'].user_id ? true : false
                }
                return false
            },
            computedShowFollow(){
                return this.computedOwner ? false : 
                    this.getProfiles ? true : false
            },
            computedProfiles(){
                return this.getProfiles
            },
        },
        methods: {
            ...mapActions(['profile/follow','profile/unfollow','profile/updateProfilePic']),
            clickedMedia(url,mediaType){
                if (this.computedOwner) {
                    this.editProfilePicture()
                } else {
                    this.$emit('clickedMedia',{url,mediaType})
                }
            },
            pictureModalAppear(){
                this.showPreviousPic = true
            },
            inputFileChange(event){
                let file = event.target.files[0]
                this.showPreview = true
                this.file = file
            },
            clickedAddFile(){ 
                this.showPreview = false
                this.$refs.inputfile.setAttribute('accept', this.imageType)
                this.$refs.inputfile.click()
            },
            removeFile(){
                this.file = null
                this.showPreviousPic = true
                // this.showPreview = false
            },
            clearAlert(){
                this.modalAlertError = false
                this.modalAlertSuccess = false
                this.modalAlertMessage = ''
                this.showPictureAdd = true
            },
            async clickedAdd(){

                if (this.file) {
                    this.showPictureAdd = false
                    let formData = new FormData
                    let profileId = this['profile/getProfile'].id

                    formData.append('file', this.file)
                    formData.append('account', this.computedRoute.params.account)
                    formData.append('accountId', this.computedRoute.params.accountId)

                    let data = {
                        profileId, formData
                    }
                    let response = await this['profile/updateProfilePic'](data)

                    if (response === 'successful') {
                        this.modalAlertSuccess = true
                        this.modalAlertMessage = 'successfully uploaded profile picture'
                    } else {
                        this.modalAlertError = true
                        this.modalAlertMessage = 'upload of picture was unsuccessful'
                    }
                } else {
                    this.modalAlertError = true
                    this.modalAlertMessage = 'please select an image first'
                }
            },
            editProfilePicture(){
                this.showPictureModal = true
            },
            clickedFollow() {
                if (this.getProfiles && !this.computedOwner) {
                    if (this.followButtonText === 'follow') {
                        this.showProfiles = true
                        setTimeout(() => {
                            this.showProfiles = false
                        }, 4000);
                    } else {
                        this.clickedUnfollow()
                    }
                } else {
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.showSmallModal = false
                    }, 4000);
                }
            },
            async clickedProfile(who){
                this.showProfiles = false

                if (!this.computedFollowing) {
                    this.followLoading = true
                    let data = {
                        account: who.account, //the account about to follow
                        accountId: who.accountId,
                        follow: this.computedRoute.params.account, // account about to be followed
                        followId: this.computedRoute.params.accountId,
                    }

                    let response =  await this['profile/follow'](data)

                    this.followLoading = false
                    if (response.status) {
                        // this.follows += 1
                        this.followButtonText = 'unfollow'
                        this.followSuccessMessage = 'you are now following'
                        setTimeout(() => {
                            this.followSuccessMessage = ''
                        }, 2000);
                    } else {
                        this.followSuccessMessage = 'something went wrong'
                        setTimeout(() => {
                            this.followSuccessMessage = ''
                        }, 2000);
                    }
                }
            },
            async clickedUnfollow(){
                this.followLoading = true
                let data = {
                    followId: this.myFollowId, //id of the follow model
                }
                console.log('unfollow',data)

                let response =  await this['profile/unfollow'](data)

                this.followLoading = false
                if (response === 'successful') {
                    // this.follows -= 1
                    this.followButtonText = 'follow'
                    this.followSuccessMessage = 'you just unfollowed'
                    setTimeout(() => {
                        this.followSuccessMessage = ''
                    }, 2000);
                } else {
                    this.followSuccessMessage = 'something went wrong'
                    setTimeout(() => {
                        this.followSuccessMessage = ''
                    }, 2000);
                }
            },
            clickedOk(){
                this.showSmallModal = false
            }
        },
    }
</script>

<style lang="scss" scoped>
    $profile-picture-main-width: 140px;
    $profile-picture-color: aquamarine;

    .first-section{
            background-color: aliceblue;
            padding-bottom: 5px;

        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
            z-index: 2;

            .profile-picture-out{
                z-index: 2;
            }
        }
        
        .profile-about {
            display: block;
            position: relative;
            background-color: inherit;
            min-height: 130px;
            width: 45%;
            margin: -50px 0 0px 50px;
            padding: 10px;
            // border: 2px solid $profile-picture-color;
            font-size:16px;
            // border-left: none;

            .border-style{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 2px solid $profile-picture-color;
                border-radius: 0 0 50px 0;
                z-index: 1;
            }

            .follows{
                position: absolute;
                left: 100%;
                margin: -10px 0 0 75%;
                padding: 10px;
                width: 35%;
                text-align: end;
                border-top: 2px solid $profile-picture-color;
                border-right: 2px solid $profile-picture-color;

                div{
                    font-size: 14px;
                }

                .action{
                    font-weight: 700;
                    font-size: 15px;
                    text-align: center;
                    opacity: .7;
                    margin: 10px 0 0;
                    position: relative;
                    z-index: 1;

                    .following{
                        text-align: end;
                        color: green;
                        position: relative;
                    }

                    .profiles{
                        position: relative;
                        z-index: 1;
                        text-align: start;
                        left: -70px;
                        top: -20px;
                        width: 150%;
                    }
                }
            }

            .name{
                font-variant: small-caps;
                font-weight: 600;
                width: calc(100% - 110px);
                text-align: end;
                margin: 10px 0 0 auto;
                text-overflow: ellipsis;
                text-transform: uppercase;
                white-space: nowrap;
                overflow: hidden;
                font-size: 18px;
                font-style: italic;
            }

            .username{
                color: grey;
                width: auto;
                display: block;
                font-size: 12px;
                text-align: end;
            }

            .as{
                margin-bottom: 5px;
                font-weight: 550;
                text-transform: capitalize;
            }

            .verification{
                background-color: rgba(105, 105, 105, .2);
                padding: 5px;
                width: 80%;
                margin: 0 auto;
                text-align: center;
                border-radius: 5px;
                font-size: 14px;
            }

            .created-at{
                font-size: 12px;
                color: rgba(128, 128, 128, 0.452);
                padding: 5px;
            }
        }
    }

@media screen and (max-width:1100px) {
    $profile-picture-main-width: 120px;
        
    .first-section{
        
        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
        }

        .profile-about {
            width: 60%;
            margin: -50px 0 0px 50px;

            .follows{
                margin: -10px 0 0 20%;
                width: 35%;

                div{
                    font-size: 12px;
                }
            }

            .name{
                width: calc(100% - 80px);
                font-size: 16px;
            }

            .username{
                font-size: 12px;
            }

            .as{
                font-size: 14px;
            }

            .verification{
                // width: 10%;
                font-size: 12px;
            }
        }
    }
}


@media screen and (max-width: 800px){
    $profile-picture-main-width: 100px;
        
    .first-section{
        
        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
        }

        .profile-about {
            font-size: 14px;
            width: 60%;
            margin: -50px 0 0 50px;

            .follows{
                margin: -10px 0 0 12%;
                width: 35%;

                div{
                    font-size: 11px;
                }

                .action{
                    

                    .profiles{
                        left: -40px;
                        // top: -20px;
                    }
                }
            }

            .name{
                width: calc(100% - 30px);
                font-size: 16px;
            }

            .username{
                font-size: 12px;
            }

            .as{
                font-size: 14px;
            }

            .verification{
                // width: 15%;
                font-size: 12px;
            }

            .created-at{
                font-size: 11px;
            }
        }
    }
}

@media screen and (max-width: 400px){
    $profile-picture-main-width: 100px;
        
    .first-section{
        
        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
        }

        .profile-about {
            font-size: 14px;
            width: 80%;
            margin: -50px auto 0;

            .follows{
                width: 100%;
                position: relative;
                margin: -10px 0 0 5%;
                left: 0;
                border: none;

                div{
                    font-size: 11px;
                }

                .action{
                    text-align: right;
                }
            }

            .name{
                width: calc(100% - 30px);
                font-size: 16px;
            }

            .username{
                font-size: 12px;
            }

            .as{
                font-size: 14px;
            }

            .verification{
                font-size: 12px;
                width: 70%;
            }
        }
    }
}
</style>