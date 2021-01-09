<template>
    <div class="account-badge-wrapper"
        @click.self="goToProfile(account)"
        :class="{request,notification}"
    >
        <div class="top">
            <profile-picture class="profile-picture"
                @click="goToProfile(account)"
                v-if="account"
            >
                <template slot="image">
                    <img :src="account.url" >
                </template>
            </profile-picture>
            <div class="name" v-if="!message.length">
                {{account.name}}
            </div>
            <div class="account-type" v-if="!message.length">
                {{computedAccountType}}
            </div>
            <div class="message" v-if="message.length">
                {{message}}
            </div>
            <div class="created-at" v-if="message.length && createdAt.length">
                {{computedCreatedAt}}
            </div>
        </div>
        <div class="middle" v-if="request && !message.length">
            <div class="info"> 
                {{computedInfo}}
            </div>
            <div class="title">{{account.title}}</div>
        </div>
        <div class="bottom" v-if="!message.length">
            <action-button
                @click="clickedAccept"
                :green="true"
                title="accept request to join discussion"
                :loading="acceptLoading"
                :text="greenActionButtonText"
            ></action-button>
            <action-button
                @click="clickedDecline"
                :red="true"
                title="decline request to join discussion"
                :loading="declineLoading"
                :text="'decline'"
                v-if="!invite"
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
import { dates } from '../../services/helpers'

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
            notification: {
                type: Boolean,
                default: false
            },
            alert: {
                type: Boolean,
                default: false
            },
            message: {
                type: String,
                default: ''
            },
            createdAt: {
                type: String,
                default: ''
            },
            invite: {
                type: Boolean,
                default: false
            },
            inviting: {
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
                declineLoading: false,
                acceptLoading: false,
                greenActionButtonText: 'accept',
            }
        },
        watch: {
            invite: {
                immediate:true,
                handler(newValue){
                    if (newValue) {
                        this.greenActionButtonText = 'invite'
                    }
                }
            },
            inviting(newValue){
                if (!newValue) {
                    this.loading = false
                }
            }
        },
        computed: {
            ...mapGetters(['getProfiles','getUser']),
            computedAccountType(){
                return this.account.hasOwnProperty('account') ? this.account.account : ''
            },
            computedAccountId(){
                return this.account.hasOwnProperty('accountId') ? this.account.accountId : ''
            },
            computedFromAdmin(){
                return this.account.isAdmin !== false ? true : false
            },
            computedInfo(){
                return this.computeedFromAdmin ? `wants to join your discussion with title:` :
                    'invites you to join the discussion with title'
            },
            computedCreatedAt(){
                return this.createdAt.length ? dates.createdAt(this.createdAt) : ''
            },
        },
        methods: {
            ...mapActions([]),
            clickedDecline(){
                if (this.acceptLoading) return
                this.declineLoading = true
                this.$emit('clickedAction',{
                    account: this.account,
                    action: 'declined',
                    type: this.computedFromAdmin ? 'invitation' : 'join'
                })
            },
            clickedAccept(){
                if (this.declineLoading) return
                this.acceptLoading = true
                if (this.greenActionButtonText === 'accept') {
                    this.$emit('clickedAction',{
                        action: 'accepted',
                        account: this.account,
                        type: this.computedFromAdmin ? 'invitation' : 'join'
                    })
                    return
                }
                this.$emit('clickedAction',{
                    action: 'invite',
                    account: this.account
                })
            },
            goToProfile(data){
                if (this.request) {
                    return
                }
                this.$emit('clearData')
                this.$router.push({
                    name: 'profile', 
                    params: {
                        account: this.computedAccountType, 
                        accountId: this.computedAccountId
                    }
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

    .account-badge-wrapper{
        padding: 10px;
        box-shadow: 0 0 2px;
        border-radius: 10px;

        .top{
            width: 100%;
            display: flex;
            justify-content: space-between;
                            
            .profile-picture{
                width: 30px;
                height: 30px;
                min-width: 30px;
            }

            .name{
                font-size: 12px;
                font-weight: 500;
                @include text-overflow()
            }

            .account-type{
                font-size: 10px;
                text-align: right;
                color: gray;
                max-width: 25%;
                @include text-overflow()
            }

            .message{
                width: auto;
                margin-right: auto;
                margin-left: 0;
                color: gray;
                font-size: 14px;
                padding: 0 0 5px 5px;
            }

            .created-at{
                width: 100%;
                text-align: end;
                font-size: 12px;
                color: gray;
            }
        }

        .middle{
            width: 100%;
            text-align: center;
            margin: 5px 0;

            .info{
                color: gray;
                font-size: 11px;

            }

            .title{
                font-size: 12px;
                font-variant: small-caps;
            }
        }

        .bottom{
            width: 100%;
            margin-top: 5px;
            display: inline-flex;
            justify-content: space-around;

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
        }
    }

</style>