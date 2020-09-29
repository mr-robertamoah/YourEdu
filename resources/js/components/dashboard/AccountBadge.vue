<template>
    <div>
        <div class="account-badge-wrapper"
            v-if="account"
            @click.self="goToProfile(account)"
        >
            <div class="right-section">
                <div class="profile-picture"
                    @click="goToProfile(account)"
                >
                    <profile-picture>
                        <template slot="image">
                            <img :src="account.url" >
                        </template>
                    </profile-picture>
                </div>
                <div class="action-button"
                    v-if="computedActionFollow"
                    @click="clickedFollow"
                >
                    {{loading ? '' : computedFollowing}}
                    <pulse-loader :loading="loading" :size="'6px'"></pulse-loader>
                </div>
                <action-button
                    @click="clickedAccept"
                    :green="true"
                    :title="greenActionTitle"
                    v-if="computedActionAccept"
                    :loading="acceptLoading"
                    :text="'accept'"
                ></action-button>
                <action-button
                    @click="clickedDecline"
                    :red="true"
                    :title="redActionTitle"
                    v-if="computedActionAccept"
                    :loading="declineLoading"
                    :text="'decline'"
                ></action-button>
            </div>
            <div class="other-section"
                @click="goToProfile(account)"
            >
                <div class="name">
                    {{account.name}}
                </div>
                <div class="account-type">
                    {{computedAccountType}}
                </div>
                <div class="extra" v-if="account.about">
                    {{account.about}}
                </div>
            </div>
            <div class="profiles"
                v-if="showProfiles"
            >
                <span>
                    {{`${profileAction} as`}}
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
        </div>
        <div class="action-button-special">
            <action-button
                @click="clickedFlag"
                text="unflag"
                :loading="flagLoading"
                :background="true"
                v-if="computedActionFlag"
                class="action-button"
            ></action-button>
        </div>
    </div>
</template>

<script>
import ProfilePicture from '../profile/ProfilePicture'
import ProfileBar from '../profile/ProfileBar'
import ActionButton from '../ActionButton'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import { mapActions, mapGetters } from 'vuex'

    export default {
        props: {
            account: {
                type: Object,
                default(){
                    return null
                }
            },
            request: {
                type: Boolean,
                default: false
            },
            flag: {
                type: Boolean,
                default: false
            },
            action: {
                type: Boolean,
                default: true
            },
        },
        components: {
            PulseLoader,
            ActionButton,
            ProfileBar,
            ProfilePicture,
        },
        data() {
            return {
                loading: false,
                myFollow: [],
                followButtonText: 'follow',
                showProfiles: false,
                profileAction: '',
                flagLoading: false,
                declineLoading: false,
                acceptLoading: false,
                greenActionTitle: 'accept request',
                redActionTitle: 'decline request',
            }
        },
        watch: {
            request: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.profileAction = 'accept'
                    } else {
                        this.profileAction = 'follow'
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['getProfiles','getUser']),
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedFollowing(){ //determines if a user follows this account
                let followIndex = -1
                if (this.account && this.account.follows) {

                    followIndex = this.account.follows.findIndex(follower=>{
                        return this.getUser.id === follower.user_id
                    })
                }
                if (followIndex > -1) {
                    this.myFollow = this.account.follows[followIndex]
                    this.followButtonText = 'unfollow'
                    return 'unfollow'
                }
                this.followButtonText = 'follow'
                return 'follow'
            },
            computedOwner(){
                if (this.getUser && this.account) {
                    
                    return this.getUser.id === this.account.user_id ? true : false
                }
                return false
            },
            computedAccountType(){
                return this.account.hasOwnProperty('account_type') ? this.account.account_type :
                    this.account.hasOwnProperty('params') ? this.account.params.account :
                    this.account.hasOwnProperty('account') ? this.account.account : ''
            },
            computedActionFollow(){
                return this.computedProfiles.length && !this.computedOwner &&
                    !this.flag && !this.request && this.action ? true : false
            },
            computedActionAccept(){
                return this.computedProfiles.length && 
                    this.request && this.action ? true : false
            },
            computedActionFlag(){
                return this.computedProfiles.length && 
                    this.flag && this.action ? true : false
            },
        },
        methods: {
            ...mapActions(['profile/follow','profile/unfollow']),
            clickedFlag(){
                this.flagLoading = true
                this.$emit('clickedAction',{
                    account: this.computedAccountType,
                    accountId: this.account.id,
                    flag: this.account.flags.filter(flag=>{
                        return flag.user_id === this.getUser.id
                    })[0]
                })
            },
            profilesAppear(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            clickedFollow() {
                if (this.followButtonText === 'follow') {
                    this.profilesAppear()
                } else {
                    this.clickedUnfollow()
                }
            },
            clickedDecline(){
                this.declineLoading = true
                this.$emit('clickedAction',{
                    account: this.account.params.account, 
                    accountId: this.account.params.accountId,
                    requestId: this.account.id,
                    action: 'decline'
                })
            },
            clickedAccept(){
                this.profilesAppear()
            },
            async clickedProfile(who){
                this.showProfiles = false
                let response = null,
                    data = {}
                if (this.profileAction === 'follow') {
                    this.loading = true
                    data = {
                        account: who.account, //the account about to follow
                        accountId: who.accountId,
                        follow: this.account.account_type, // account about to be followed
                        followId: this.account.account_id,
                    }
    
                    response =  await this['profile/follow'](data)
    
                    if (response.status) {
                        // this.follows += 1
                        this.followButtonText = 'unfollow'
                        this.$emit('addMyfollow',{
                            follow: response.follow,
                            account: this.account.account_type,
                            accountId: this.account.account_id,
                        })
                    } else {
    
                    }
                    this.loading = false
                } else if (this.profileAction === 'accept') {
                    this.acceptLoading = true
                    data = {
                        requestId: this.account.id,
                        account: who.account,
                        accountId: who.accountId,
                        action: this.profileAction
                    }
                    this.$emit('clickedAction',data)
                }
            },
            async clickedUnfollow(){
                this.loading = true 
                let data = {
                    followId: this.myFollow.id, //id of the follow model
                }

                let response =  await this['profile/unfollow'](data)

                if (response === 'successful') {
                    // this.follows -= 1
                    this.followButtonText = 'follow'
                    this.$emit('removeMyfollow',{
                        follow: this.myFollow,
                        account: this.account.account_type,
                        accountId: this.account.account_id,
                    })
                } else {
                    
                }
                this.loading = false
            },
            goToProfile(data){
                if (this.request) {
                    return
                }
                this.$emit('clearData')
                this.$router.push({
                    name: 'profile', 
                    params: {
                        account: data.account_type, 
                        accountId: data.account_id
                    }
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

    .account-badge-wrapper{
        width: 100%;
        display: flex;
        justify-content: space-between;
        padding: 10px;
        box-shadow: 0 0 2px;
        border-radius: 10px;

        .profiles{
            position: absolute;
            font-size: 12px;
            font-weight: 500;
        }

        .right-section{
            width: 30%;
            text-align: center;
            
            .profile-picture{
                width: 30px;
                height: 30px;
                margin: auto;
            }

            .action-button{
                width: 100%;
                text-align: center;
                padding: 5px;
                border-radius: 5px;
                color: white;
                background: gray;
                font-size: 10px;
                margin: 10px 0 5px;

                &:hover{
                    box-shadow: 0 0 2px gray;
                }
            }
        }

        .other-section{
            width: 70%;
            margin-left: 5px;

            .name{
                font-size: 12px;
                font-weight: 500;
                text-transform: capitalize;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
            }

            .account-type{
                font-size: 10px;
                text-align: right;
                padding-right: 5px;
            }
            
            .extra{
                font-size: 11px;
                text-align: justify;
                font-style: italic;
            }
        }
    }

    .action-button-special{
        width: 100%;
        padding: 5px;
    }

    .action-button{
        width: fit-content;
        text-align: center;
        padding: 5px;
        border-radius: 10px;
        color: white;
        background: gray;
        font-size: 10px;
        min-width: 30px;
        margin: 5px 0 5px auto;

        &:hover{
            box-shadow: 0 0 2px gray;
        }
    }
</style>