<template>
    <div class="other-user-account" 
        v-if="account.name"
        @click="clickedOtherUserAccount"
        :title="computedTitle"
        :class="{active}"
    >
        <div class="user-account-section">
            <div class="online-alert" v-if="online"></div>
            <profile-picture
                class="profile-picture"
                v-if="computedUrl.length"
            >
                <template slot="image">
                    <img :src="computedUrl">
                </template>
            </profile-picture>
            <div class="other-section">
                <div class="top">
                    <div class="name">{{computedName}}</div>
                    <div class="account">{{computedAccount}}</div>
                </div>
                <div class="bottom" v-if="computedBottom">
                    <div class="type">{{account.type === 'follower' ? 'follows' :'followed by'}}</div>
                    <div class="account-details">
                        <div class="name">{{computedMyName}}</div>
                        <div class="account">{{computedMyAccount}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons-section" v-if="computedAction">
            <action-button
                @click="clickedAccept"
                :green="true"
                :title="greenActionTitle"
                :loading="acceptLoading"
                v-if="computedActionAccept"
                :text="greenActionText"
            ></action-button>
            <action-button
                @click="clickedBan"
                :red="true"
                :title="redActionTitle"
                :loading="banLoading"
                v-if="computedActionBan"
                :text="redActionText"
            ></action-button>
            <action-button
                @click="clickedRemove"
                :red="true"
                :title="removeActionTitle"
                :loading="removeLoading"
                v-if="computedActionRemove"
                :text="removeActionText"
            ></action-button>
        </div>
    </div>
</template>

<script>
import { strings } from '../../services/helpers';
import ProfilePicture from '../profile/ProfilePicture.vue';
import ActionButton from '../ActionButton.vue';
import { mapGetters } from 'vuex';
    export default {
        components: {
            ActionButton,
            ProfilePicture,
        },
        props: {
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
            friend: {
                type: Boolean,
                default: false
            },
            online: {
                type: Boolean,
                default: false
            },
            chat: {
                type: Boolean,
                default: true
            },
            participant: {
                type: Boolean,
                default: false
            },
            participating: {
                type: Boolean,
                default: false
            },
            admin: {
                type: Boolean,
                default: false
            },
            owner: {
                type: Boolean,
                default: false
            },
            loading: {
                type: Boolean,
                default: false
            },
            active: {
                type: Boolean,
                default: false
            },
            request: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                greenActionText: '',
                greenActionTitle: '',
                redActionText: '',
                redActionTitle: '',
                removeActionText: 'leave',
                removeActionTitle: 'leave this discussion',
                removeLoading: false,
                acceptLoading: false,
                showRedAction: false,
                showGreenAction: false,
                banLoading: false,
            }
        },
        watch: {
            account: {
                immediate: true,
                handler(newValue){
                    this.showRedAction = false
                    this.showGreenAction = false
                    if (newValue && this.admin) {
                        if (newValue.state === 'ACTIVE') {
                            this.greenActionText = 'make admin'
                            this.greenActionTitle = 'turn this participant to an admin'
                            this.redActionText = 'restrict'
                            this.redActionTitle = 'restrict this admin'
                            this.makeButtonsActive()
                        } else if (newValue.state === 'RESTRICTED') {
                            this.greenActionText = 'make active'
                            this.greenActionTitle = 'make this restricted participant active'
                            this.redActionText = 'ban'
                            this.redActionTitle = 'ban this restricted participant'
                            this.makeButtonsActive()
                        } else if (newValue.state === 'BANNED') {
                            this.greenActionText = 'make normal'
                            this.greenActionTitle = 'make this banned participant active'
                            this.redActionText = 'restrict'
                            this.redActionTitle = 'restrict this banned participant'
                            this.makeButtonsActive()
                        }
                        if (!this.computedOwner) {                            
                            this.removeActionTitle = 'remove this participant'
                            this.removeActionText = 'remove'
                        }
                    }
                    if (newValue && this.owner) {
                        if (newValue.state === 'ADMIN') {
                            this.greenActionText = 'make normal'
                            this.greenActionTitle = 'turn this admin to a normal participant'
                            this.redActionText = 'restrict'
                            this.redActionTitle = 'restrict this admin'
                        }
                        this.makeButtonsActive()
                    }
                },
                deep: true 
            },
            loading(newValue){
                if (!newValue) {
                    this.acceptLoading = false
                    this.banLoading = false
                    this.removeLoading = false
                }
            },
        },
        computed: {
            ...mapGetters(['getUser']),
            computedName() {
                return this.account.name ? this.account.name : 
                    this.account.profile_name ? this.account.profile_name : ''
            },
            computedTitle(){
                return this.chat && this.online ? 'user is online' : 
                    this.chat ? 'user is offline' : ''
            },
            computedBottom(){
                return !this.chat ? false : !this.friend
            },
            computedAccount() {
                return this.account.account ? this.account.account : 
                    this.account.followedby_type ? 
                    strings.getAccount(this.account.followedby_type) : 
                    this.account.username ? this.account.username : ''
            },
            computedMyName() {
                return this.account.myName ? this.account.myName : 
                    this.account.my_name ? this.account.my_name : ''
            },
            computedMyAccount() {
                return this.account.myAccount ? this.account.myAccount : 
                    this.account.followable_type ? 
                    strings.getAccount(this.account.followable_type) : ''
            },
            computedUrl() {
                return this.account.url ? this.account.url : ''
            },
            computedOwner() {
                return this.getUser && this.getUser.id === this.account.userId
            },
            computedAction() {
                return !this.participating ? false : this.getUser && this.participant
            },
            computedActionAccept() {
                return this.computedAction && this.admin && this.showGreenAction
            },
            computedActionBan() {
                return this.computedAction && this.admin && this.showRedAction && !this.computedOwner
            },
            computedActionRemove(){
                return this.owner && this.computedOwner ? false : 
                    this.owner ? true :
                    (this.account.state === 'ADMIN' || this.account.state === 'OWNER') &&
                    !this.computedOwner ? false :
                    this.admin || this.computedOwner ? true : false
            },
        },
        methods: {
            clickedOtherUserAccount() {
                this.$emit('clickedOtherUserAccount',this.account)
            },
            clickedRemove(data){
                if (this.acceptLoading || this.banLoading || this.removeLoading) {
                    return
                }
                this.removeLoading = true
                this.$emit('clickedParticipantAction',{
                    account: this.account,
                    action: data
                })
            },
            clickedAccept(data){
                if (this.acceptLoading || this.banLoading || this.removeLoading) {
                    return
                }
                this.acceptLoading = true
                let action
                if (this.account.state === 'ACTIVE') {
                    action = 'admin'
                } else {
                    action = 'active'
                }
                this.$emit('clickedParticipantAction',{
                    account: this.account,
                    action
                })
            },
            clickedBan(data){
                if (this.acceptLoading || this.banLoading || this.removeLoading) {
                    return
                }
                this.banLoading = true
                let action
                if (this.account.state === 'RESTRICTED') {
                    action = 'banned'
                } else {
                    action = 'restricted'
                }
                this.$emit('clickedParticipantAction',{
                    account: this.account,
                    action
                })
            },
            makeButtonsActive(){
                this.showRedAction = true
                this.showGreenAction = true
            },
        },
    }
</script>

<style lang="scss" scoped>

    .other-user-account{
        width: 100%;
        border-radius: 10px;
        padding: 5px;

        &.active{
            background: wheat;
        }

        &:hover{
            box-shadow: 0 0 2px gray;
            transition: all .5s ease;
        }

        .user-account-section{
            width: 100%;
            display: inline-flex;
            cursor: pointer;
            position: relative;

            .online-alert{
                position: absolute;
                bottom: 80%;
                left: -10px;
                background: green;
                width: 12px;
                height: 12px;
                border-radius: 10px;
                border: 2px solid aquamarine;
            }

            .profile-picture{
                height: 40px;
                width: 40px;
                min-width: 40px;
            }

            .other-section{
                width: 80%;
                margin-left: 10px;

                .top{
                    width: 100%;
                    display: inline-flex;
                    align-items: center;
                    justify-content: space-between;
                    color: gray;

                    .name{
                        text-align: start;
                        width: 60%;
                        font-size: 12px;
                        font-weight: 500;
                        text-transform: capitalize;
                        @include text-overflow()
                    }

                    .account{
                        font-size: 10px;
                    }
                }

                .bottom{
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    
                    .type{
                        width: 40%;
                        font-size: 10px;
                        text-align: start;
                        @include text-overflow()
                    }
                    
                    .account-details{
                        display: inline-flex;
                        align-items: center;
                        justify-content: flex-end;
                        width: 60%;
                    
                        .name{
                            font-size: 11px;
                            margin-right: 2px;
                            @include text-overflow();
                        }
                        
                        .account{
                            @include text-overflow();
                            font-size: 10px;
                        }
                    }
                }
            }
        }

        .buttons-section{
            width: 100%;
            display: inline-flex;
            justify-content: space-around;
            align-items: center;
            padding: 5px;
        }
    }
</style>