<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear="requestsModalDisappear"
                @clickedMain="showFollowProfiles = false"
                :main="false"
                :loading="requestLoading"
            >
                <template slot="loading" v-if="requestLoading">
                    <pulse-loader :loading="requestLoading"></pulse-loader>
                </template>
                <template slot="requests">
                    <div class="no-requests" 
                        v-if="!computedRequests">
                        there are no requests
                    </div>
                    <slide-right-group>
                        <template slot="transition">
                            <account-badge
                                v-for="request in requests"
                                :key="request.id"
                                @clickedAction="clickedRequestAction"
                                :account="request"
                                :request="true"
                                class="account-badge"
                            ></account-badge>
                        </template>
                    </slide-right-group>
                    <div class="show-more"
                        @click="moreRequests"
                        v-if="showMoreRequests"
                    >
                        <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                    </div>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import JustFade from './transitions/JustFade';
import SlideRightGroup from './transitions/SlideRightGroup';
import MainModal from './MainModal';
import AccountBadge from "./dashboard/AccountBadge";
import PulseLoader from "vue-spinner/src/PulseLoader";
import { mapActions, mapGetters } from 'vuex';
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            notifications: {
                type: Number,
                default: 0
            },
        },
        components: {
            MainModal,
            SlideRightGroup,
            JustFade,
            PulseLoader,
            AccountBadge,
        },
        data() {
            return {
                showMoreRequests: false,
                requestNextPage: 1,
                requests: [],
                showMoreRequests: false,
                requestLoading: false,
                requestId : null,
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.requestNextPage = 1
                        this.requestLoading = true
                        this.moreRequests()
                    }
                }
            }
        },
        computed: {
            ...mapGetters([]),
            computedRequests(){
                return this.requests.length
            },
        },
        methods: {
            ...mapActions(['acceptFollowRequest','declineFollowRequest','userFollowRequests']),
            removeRequest(){ //remove request on success
                let requestIndex = this.requests.findIndex(request=>{
                    return request.id === this.requestId
                })
                if (requestIndex > -1) {
                    this.requests.splice(requestIndex,1)
                }
            },
            requestsModalDisappear(){
                this.$emit('requestsModalDisappear')
            },
            async clickedRequestAction(accountData){
                let response = null
                this.requestId = accountData.requestId
                let data = {}
                if (accountData.action === 'accept') {
                    data = {
                        requestId: accountData.requestId,
                        account: accountData.account,
                        accountId: accountData.accountId,
                    }
                    response = await this.acceptFollowRequest(data)
                } else if (accountData.action === 'decline') {
                    data = {
                        requestId: accountData.requestId,
                    }
                    response = await this.declineFollowRequest(data)
                }
                if (response === 'successful') {
                    this.removeRequest()
                }
            },
            async moreRequests(){
                let data = {
                    nextPage: this.requestNextPage,
                }

                let response = await this.userFollowRequests(data)

                this.requestLoading = false
                if (response !== 'unsuccessful' ) {
                    this.requests.push(...response.data)
                    if (response.links.next) {
                        this.requestNextPage += 1
                        this.showMoreRequests = true
                    } else {
                        this.showMoreRequests= false
                    }
                } else {
                    // this.showMoreRequests = true
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .show-more{
        width: fit-content;
        text-align: center;
        padding: 5px;
        margin: 5px auto;
    }

    .no-requests{
        min-height: 100px;
        width: 100%;
        text-align: center;
        padding: 10px;
        font-size: 12px;
        font-weight: 450;
    }

    .account-badge{
        margin-bottom: 5px;
    }
</style>