<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear="requestsModalDisappear"
                @clickedMain="showFollowProfiles = false"
                :main="false"
                :mainOther="false"
                class="dashboard-request-wrapper"
            >
                <template slot="requests">
                    <auto-alert
                        :message="alertMessage"
                        :success="alertSuccess"
                        :danger="alertDanger"
                        :sticky="true"
                        @hideAlert="clearAlert"
                    ></auto-alert>
                    <pulse-loader 
                        class="loading" 
                        v-if="requestLoading"
                        :loading="requestLoading"
                    ></pulse-loader>
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
                        <request-badge
                            v-for="request in requests"
                            :key="request.id"
                            :request="request"
                            :dashboard="true"
                            @clickedAction="clickedRequestAction"
                            @showDetails="clickedShowDetails"
                            @showMessages="clickedShowMessages"
                            @newMessage="newRequestMessage"
                        ></request-badge>
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

                    <messages-modal
                        :show="showMessageModal"
                        :newMessage="newMessage"
                        @closeMessageModal="closeMessageModal"
                        :request="modalRequest"
                    ></messages-modal>
                    <details-modal
                        :show="showDetailsModal"
                        @closeDetailsModal="closeDetailsModal"
                        :details="modalRequest"
                    ></details-modal>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import RequestBadge from '../RequestBadge.vue';
import MessagesModal from "../MessagesModal.vue";
import DetailsModal from "../DetailsModal.vue";
import { mapActions } from 'vuex'
import Alert from '../../mixins/Alert.mixin';
    export default {
        components: {
            
            RequestBadge,
            DetailsModal,
            MessagesModal,
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
        mixins: [Alert],
        data() {
            return {
                showMessageModal: false,
                showDetailsModal: false,
                requestLoading: false,
                requests: [],
                requestsNextPage: 1,
                actionType: 'view',
                steps: 0,
                accountType: '',
                modalRequest: null,
                newMessage: null,
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
            ...mapActions(['dashboard/getAccountRequests', 
                'schoolRequestResponse', 'dashboard/sendResponse'
            ]),
            newRequestMessage(data) {
                this.newMessage = data.message
            },
            closeMessageModal(request) {
                this.showMessageModal = false
                this.modalRequest = null
            },
            clickedShowMessages(request) {
                this.showMessageModal = true
                this.modalRequest = request
            },
            clickedShowDetails(request) {
                this.showDetailsModal = true
                this.modalRequest = request
            },
            closeDetailsModal() {
                this.showDetailsModal = false
                this.modalRequest = null
            },
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
                this.acceptOrDeclineRequest(data)
            },
            async acceptOrDeclineRequest(request){
                let formData = new FormData,
                    response

                formData.append('requestId', request.id)
                formData.append('response', request.response !== 'decline' ? 'accepted' : 'declined')

                response = await this['dashboard/sendResponse'](formData)

                if (response.status) {
                    this.updateRequest({
                        requestId: request.id,
                        state: formData.get('response')
                    })
                    this.alertSuccess = true
                    this.alertMessage = `response was successfully sent 😎`
                    return
                }

                console.log('response :>> ', response);
                this.alertDanger = true
                this.alertMessage = `oops! it failed 😕. please try again later.`
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
            @include sticky-loader();
            top: 49%;
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