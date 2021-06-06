<template>
    <dashboard-modal
        :show="show"
        :main="false"
        :normal="true"
        @closeDashboardModal="closeDashboardModal"
        class="dashboard-modal"
    >
        <template slot="normal">

            <div class="top">
                <template v-if="computedAccounts">
                    <div class="account"
                        v-for="(acc,index) in computedAccounts"
                        :key="index"
                        @click="clickedAccount(acc)"
                        :class="{active: mainAccount === acc}"
                        :title="acc.name"
                    >
                        <div class="account-type">
                            {{acc.account}}
                        </div>
                        <div class="seperator">
                            <font-awesome-icon :icon="['fa','circle']"></font-awesome-icon>
                        </div>
                        <div class="account-name">
                            {{acc.name}}
                        </div>
                        <fade-right>
                            <template slot="transition" v-if="mainAccount === account">
                                <div class="active-section"></div>
                            </template>
                        </fade-right>
                    </div>
                </template>
                <div class="account" 
                    v-else
                    @click="clickedAccount(account)"
                    :class="{active: mainAccount === account}"
                    :title="account.name"
                >
                    <div class="account-type">
                        {{account.account}}
                    </div>
                    <div class="seperator">
                        <font-awesome-icon :icon="['fa','circle']"></font-awesome-icon>
                    </div>
                    <div class="account-name">
                        {{account.name}}
                    </div>
                    <fade-right>
                        <template slot="transition" v-if="mainAccount === account">
                            <div class="active-section"></div>
                        </template>
                    </fade-right>                    
                </div>
            </div>
            <div class="bottom">
                <template v-if="computedActivities.length && mainAccount">
                    <profile-picture
                        class="profile-picture"
                        v-if="mainAccount"
                    >
                        <template slot="image">
                            <img :src="mainAccount.url">
                        </template>
                    </profile-picture>
                    <div class="details">
                        <div class="name">
                            {{mainAccount.name}}
                        </div>
                        <div class="account-type">
                            {{mainAccount.account}}
                        </div>
                    </div>
                    <div class="activities"
                        v-if="!dataText.length"
                        infinite-wrapper
                    >
                        <activity-badge
                            v-for="(activity,index) in computedActivities"
                            :key="index"
                            :activity="activity"
                            class="activity-badge"
                            @viewItem="viewItem"
                        ></activity-badge>

                        <infinite-loader
                            v-if="activitiesNextPage !== 1"
                            @infinite="activitiesInfiniteLoader"
                            force-use-infinite-wrapper
                        ></infinite-loader>
                    </div>
                </template>
                <div class="no-data"
                    v-if="activitiesNextPage === 1 || dataText.length"
                >
                    {{dataText.length ? dataText : `click on the account to get activities`}}
                </div>
            </div>
        </template>
    </dashboard-modal>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ActivityBadge from './ActivityBadge';
import ProfilePicture from '../profile/ProfilePicture';
import FadeRight from '../transitions/FadeRight';
import DashboardModal from './DashboardModal';
import InfiniteLoader from 'vue-infinite-loading'
    export default {
        components: {
            DashboardModal,
            FadeRight,
            ProfilePicture,
            ActivityBadge,
            InfiniteLoader,
        },
        props: {
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
            admin: {
                type: Object,
                default(){
                    return null
                }
            },
            show: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                activitiesNextPage: 1,
                activitiesLoading: false,
                dataText: '',
                mainAccount: null,
            }
        },
        watch: {
            mainAccount(newValue, oldValue) {
                if (newValue && newValue !== oldValue) {
                    this.activitiesNextPage = 1
                    this.dataText = 'getting activities'
                    this['dashboard/clearAccountActivities']()
                    this.getActivitiesForAdmin()
                }
            },
            activitiesNextPage(newValue){
                if (!newValue || newValue !== 1) {
                    this.dataText = ''
                }
            },
        },
        computed: {
            ...mapGetters(['dashboard/getActivities']),
            computedActivities() {
                return this['dashboard/getActivities'] 
            },
            computedAccounts() {
                return this.account.username && !this.account.hasOwnProperty('level') ? 
                    this.account.profiles.map(el=>{
                        return  {
                            name: el.name ? el.name : 'no name',
                            url: el.url ? el.url: '',
                            profile: el.profile ? el.profile : '',
                            account: el.account,
                            accountId: el.accountId,
                        }
                    }) : null
            },
        },
        methods: {
            ...mapActions(["dashboard/fetchAccountActivities",'dashboard/clearAccountActivities']),
            closeDashboardModal(){
                this['dashboard/clearAccountActivities']()
                this.$emit('activityModalDisappear')
            },
            viewItem(data){
                this.$emit('viewItem',data)
            },
            clickedAccount(account){
                if (!this.activitiesLoading) {
                    this.mainAccount = account
                }
            },
            async getActivitiesForAdmin(){
                let data = await this.getActivities()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.activitiesNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.activitiesNextPage += 1
                }
            },
            async getActivities(){
                let response,
                    data = {
                        account: this.account.username ? 
                            this.mainAccount.account : this.account.account,
                        accountId: this.account.username ? 
                            this.mainAccount.accountId : this.account.accountId,
                        adminId: this.admin.accountId,
                        nextPage: this.activitiesNextPage
                    }
                this.activitiesLoading = true

                response = await this["dashboard/fetchAccountActivities"](data)

                this.activitiesLoading = false
                if (response.status) {
                    return {next: response.next}
                } else {
                    console.log('response :>> ', response);
                }
            },
            async activitiesInfiniteLoader($state){
                if (this.activitiesNextPage === null) {
                    $state.complete()
                    return
                }

                let data = await this.getActivities()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.activitiesNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.activitiesNextPage += 1
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-modal{

        .top{
            display: flex;
            width: 100%;
            overflow-x: auto;
            align-items: center;
            margin-top: 10px;
            
            .account{
                width: fit-content;
                padding: 5px;
                background: gray;
                color: white;
                margin: 0 10px 10px;
                cursor: pointer;
                display: flex;
                align-items: center;

                .account-type{
                    font-size: 12px;
                }
                
                .seperator{
                    font-size: 4px;
                    margin: 0 5px;
                }
                
                .account-name{
                    font-size: 14px;
                    text-transform: capitalize;
                }

                .active-section{

                }
            }

            .account.active{
                // border-bottom: 2px solid;
            }
        }

        .bottom{
            width: 100%;
            height: 600px;

            .profile-picture{
                width: 80px;
                height: 80px;
                margin: 20px auto 0;
            }

            .details{
                display: flex;
                align-items: center;
                justify-content: space-between;
                color: gray;
                font-size: 12px;
                margin-bottom: 20px;

                .name{
                    width: 60%;
                    text-overflow: ellipsis;
                    overflow: hidden;
                    white-space: nowrap;
                    text-transform: capitalize;
                    font-size: 14px;
                }
            }

            .activities{
                padding: 10px;
            }

            .no-data{
                display: flex;
                align-items: center;
                width: 100%;
                justify-content: center;
                height: inherit;
            }
        }
    }
</style>