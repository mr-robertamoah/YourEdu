<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear="requestsModalDisappear"
                @clickedMain="showFollowProfiles = false"
                :main="false"
                :mainOther="false"
            >
                <template slot="requests">
                    <div class="dashboard-request-wrapper">
                        <div class="loading" v-if="requestLoading">
                            <pulse-loader :loading="requestLoading"></pulse-loader>
                        </div>
                        <div class="view-requests"
                            v-if="actionType === 'view'"
                        >
                            <div class="no-requests" 
                                v-if="!computedRequests && !requestLoading">
                                {{`there are no requests`}}
                            </div>
                            <div class="has-requests" 
                                v-if="computedRequests && !requestLoading">
                                {{`these are your sent requests`}}
                            </div>
                            <other-request-badge
                                v-for="request in requests"
                                :key="request.id"
                                :request="request"
                                :dashboard="true"
                                @updateRequest="updateRequest"
                                @clickedAction="clickedRequestAction"
                            ></other-request-badge>
                        </div>
                        <div class="more-data"
                            @click="infiniteHandler"
                            v-if="requestsNextPage && requestsNextPage !== 1"
                        >
                            <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                        </div>
                        
                        <div class="no-data"
                            @click="infiniteHandler"
                            v-if="!requestsNextPage && requests.length && requestsNextPage !== 1"
                        > no more requests</div>
                    </div>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import OtherRequestBadge from '../OtherRequestBadge';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import { mapActions } from 'vuex'
    export default {
        components: {
            PulseLoader,
            OtherRequestBadge,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            action: {
                type: String,
                default: ''
            },
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
        },
        data() {
            return {
                requestLoading: false,
                requests: [],
                requestsNextPage: 1,
                actionType: 'view',
                steps: 0,
                accountType: ''
            }
        },
        watch: {
            action: {
                immediate: true,
                handler(newValue) {
                    if (newValue.length) {
                        this.actionType = newValue
                    }
                    if (this.actionType === 'view') {                        
                        this.getRequests()
                    }
                }
            },
            steps(newValue){
                if (newValue === 0) {
                    
                }
            }
        },
        computed: {
            computedRequests() {
                return this.requests.length ? true : false 
            }
        },
        methods: {
            ...mapActions(['dashboard/getAccountRequests', 'schoolRequestResponse']),
            async getRequests() {
                let response,
                    data = {
                        account: this.account.account,
                        accountId: this.account.accountId,
                    }

                this.requestLoading = true
                response = await this['dashboard/getAccountRequests']({
                    data, nextPage: this.requestsNextPage
                })

                this.requestLoading = false
                if (response.status) {
                    this.requests.push(...response.requests)
                    if (response.next) {
                        this.requestsNextPage += 1
                    } else {
                        this.requestsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async infiniteHandler($state){
                if (this.requestsNextPage === 1 || this.requestsNextPage === null) {
                    return
                }

                await this.getRequests()
            },
            requestsModalDisappear(){
                this.$emit('requestsModalDisappear')
            },
            clickedRequestAction(data){
                if (data.hasOwnProperty('schoolRequest')) {
                    this.acceptOrDeclineSchoolRequest(data)
                }
            },
            async acceptOrDeclineSchoolRequest(requestData){
                let response,
                    data = {
                        requestId: requestData.schoolRequest.id,
                        action: requestData.action,
                        other: requestData.schoolRequest.account ? 
                            requestData.schoolRequest.account.account : 'admin', 
                        mine: 'school'
                    }

                response = await this.schoolRequestResponse(data)

                if (response.status) {
                    this.removeRequest(data.requestId)
                } else {
                    console.log('response :>> ', response);
                }
            },
            updateRequest(data){
                let index = this.requests.findIndex(request=>{
                    return request.id == data.requestId
                })
                if (index > -1) {
                    this.requests[index].state = data.state
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-request-wrapper{

        .loading{
            width: 100%;
            text-align: center;
        }

        .send-request{

        }

        .view-requests{

            .no-requests,
            .has-requests{
                width: 100%;
                text-align: center;
                font-size: 14px;
                color: gray;
            }
        }

        .more-data,
        .no-data{
            width: 100%;
            text-align: center;
            cursor: pointer;
            color: gray;
            font-size: 12px;
        }

        .more-data{
            font-size: 16px;
        }
    }
</style>