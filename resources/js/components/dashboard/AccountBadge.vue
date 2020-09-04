<template>
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
                v-if="computedProfiles.length && !computedOwner"
                @click="clickedFollow"
            >
                {{loading ? '' : computedFollowing}}
                <pulse-loader :loading="loading" :size="'6px'"></pulse-loader>
            </div>
        </div>
        <div class="other-section"
            @click="goToProfile(account)"
        >
            <div class="name">
                {{account.name}}
            </div>
            <div class="account-type">
                {{account.account_type}}
            </div>
            <div class="extra" v-if="account.about">
                {{account.about}}
            </div>
        </div>
        <div class="profiles"
            v-if="showProfiles"
        >
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
    </div>
</template>

<script>
import ProfilePicture from '../profile/ProfilePicture'
import ProfileBar from '../profile/ProfileBar'
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
        },
        components: {
            PulseLoader,
            ProfileBar,
            ProfilePicture,
        },
        data() {
            return {
                loading: false,
                myFollow: [],
                followButtonText: 'follow',
                showProfiles: false,
            }
        },
        computed: {
            ...mapGetters(['getProfiles','getUser']),
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedFollowing(){ //determines if a user follows this account
                let followIndex = -1
                if (this.account) {

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
        },
        methods: {
            ...mapActions(['profile/follow','profile/unfollow']),
            clickedFollow() {
                if (this.followButtonText === 'follow') {
                    this.showProfiles = true
                    setTimeout(() => {
                        this.showProfiles = false
                    }, 4000);
                } else {
                    this.clickedUnfollow()
                }
            },
            async clickedProfile(who){
                this.showProfiles = false

                this.loading = true
                let data = {
                    account: who.account, //the account about to follow
                    accountId: who.accountId,
                    follow: this.account.account_type, // account about to be followed
                    followId: this.account.account_id,
                }

                let response =  await this['profile/follow'](data)

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
                font-size: 8px;
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
</style>