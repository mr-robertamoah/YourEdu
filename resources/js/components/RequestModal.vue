<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear="requestsModalDisappear"
                @clickedMain="showFollowProfiles = false"
                :main="false"
                :mainOther="false"
                :loading="requestLoading"
            >
                <template slot="loading" v-if="requestLoading">
                    <pulse-loader :loading="requestLoading"></pulse-loader>
                </template>
                <template slot="requests">
                    <div class="no-requests" 
                        v-if="!computedRequests && !requestLoading">
                        {{`there are no ${type}`}}
                    </div>
                    <div class="has-requests" 
                        v-if="computedRequests && !requestLoading">
                        {{`these are your${type === 'requests' ? ' pending' : ''} ${type}`}}
                    </div>
                    <slide-right-group>
                        <template slot="transition" v-if="requests.length">
                            <template
                                v-for="request in requests"
                            >
                                <account-badge
                                    v-if="request.isAccount"
                                    :key="`account.${request.id}`"
                                    @clickedAction="clickedRequestAction"
                                    :account="request"
                                    :request="true"
                                    class="request-badge"
                                ></account-badge>
                                <participant-badge
                                    v-if="request.isParticipant"
                                    :key="`participant.${request.id}`"
                                    @clickedAction="clickedParticipantAction"
                                    :account="request"
                                    :request="true"
                                    class="request-badge"
                                ></participant-badge>
                                <discussion-badge
                                    v-if="request.isMessage"
                                    :key="`message.${request.id}`"
                                    :message="request"
                                    :request="true"
                                    @clickedAction="clickedRequestAction"
                                    class="request-badge"
                                ></discussion-badge>
                                <other-request-badge
                                    v-if="request.isAdminRequest"
                                    :key="`request.${request.id}`"
                                    :request="request"
                                    @clickedAction="clickedRequestAction"
                                    @updateRequest="updateRequest"
                                ></other-request-badge>
                            </template>
                        </template>
                        <template slot="transition" v-if="notifications.length">
                            <template
                                v-for="notification in notifications"
                            >
                                <participant-badge
                                    :key="notification.id"
                                    @clickedAction="clickedParticipantAction"
                                    :account="getNotificationAccount(notification.data)"
                                    :message="notification.data.message"
                                    :createdAt="notification.data.created_at"
                                    :notification="true"
                                    class="request-badge"
                                ></participant-badge>
                            </template>
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
import ParticipantBadge from "./discussion/ParticipantBadge";
import DiscussionBadge from "./DiscussionBadge";
import OtherRequestBadge from "./OtherRequestBadge";
import PulseLoader from "vue-spinner/src/PulseLoader";
import { mapActions, mapGetters } from 'vuex';
    export default {
        components: {
            MainModal,
            SlideRightGroup,
            JustFade,
            PulseLoader,
            OtherRequestBadge,
            DiscussionBadge,
            ParticipantBadge,
            AccountBadge,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                showMoreRequests: false,
                requestNextPage: 1,
                notifications: [],
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
                    } else {
                        this.requests = []
                        this.notifications = []
                    }
                }
            }
        },
        computed: {
            ...mapGetters([]),
            computedRequests(){
                return this.type === 'requests' ? this.requests.length :
                    this.notifications.length
            },
        },
        methods: {
            ...mapActions(['acceptFollowRequest','declineFollowRequest','userRequests',
                'profile/discusionContributionResponse','profile/joinDiscussionResponse',
                'userNotifications','profile/invitationDiscussionResponse',
                'schoolRequestResponse']),
            removeRequest(id, type ="message"){ //remove request on success
                let requestIndex = this.requests.findIndex(request=>{
                    return request.id === id && 
                        (type === 'message' && request.isMessage || 
                        type === 'account' && request.isAccount || 
                        type === 'participant' && request.isParticipant ||
                        request.isAdminRequest)
                })
                if (requestIndex > -1) {
                    this.requests.splice(requestIndex,1)
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
            getNotificationAccount(data){
                return data.account ? data.account : data.facilitator ? data.facilitator :
                    data.school ? data.school : null
            },
            requestsModalDisappear(){
                this.$emit('requestsModalDisappear')
            },
            clickedRequestAction(data){
                if (data.hasOwnProperty('message')) {
                    this.acceptOrRejectMessage(data)
                } else if (data.hasOwnProperty('account')) {
                    this.acceptOrDeclineAccount(data)
                } else if (data.hasOwnProperty('schoolRequest')) {
                    this.acceptOrDeclineSchoolRequest(data)
                }
            },
            clickedParticipantAction(participantData){
                if (participantData.type === 'invitation') {
                    this.invitationDiscussionResponse(participantData)
                } else {
                    this.joinDiscussionResponse(participantData)
                }
            },
            async acceptOrDeclineSchoolRequest(requestData){
                let response,
                    data = {
                        requestId: requestData.schoolRequest.id,
                        action: requestData.action,
                        other: 'school', 
                        mine: requestData.schoolRequest.myAccount === 'user' ? 'admin' :
                            requestData.schoolRequest.myAccount
                    }

                response = await this.schoolRequestResponse(data)

                if (response.status) {
                    this.removeRequest(data.requestId)
                } else {
                    console.log('response :>> ', response);
                }
            },
            async invitationDiscussionResponse(participantData){
                let response,
                    data = {
                        account: participantData.account.account,
                        accountId: participantData.account.accountId,
                        requestId: participantData.account.id,
                        discussionId: participantData.account.discussionId,
                        action: participantData.action
                    }
                
                response = await this['profile/invitationDiscussionResponse'](data)

                if (response.status) {
                    this.removeRequest(participantData.account.id,'participant')
                } else {
                    console.log('response :>> ', response);
                }
            },
            async joinDiscussionResponse(participantData){
                let response,
                    data = {
                        account: participantData.account.account,
                        accountId: participantData.account.accountId,
                        requestId: participantData.account.id,
                        discussionId: participantData.account.discussionId,
                        action: participantData.action
                    }
                
                response = await this['profile/joinDiscussionResponse'](data)

                if (response.status) {
                    this.removeRequest(participantData.account.id,'participant')
                } else {
                    console.log('response :>> ', response);
                }
            },
            async acceptOrDeclineAccount(accountData){
                let response,
                    data = {
                        account: accountData.account.account_type,
                        userId: accountData.account.userId,
                        accountId: accountData.account.account_id,
                        myAccount: accountData.account.myAccount,
                        myAccountId: accountData.account.myAccountId
                    }
                if (accountData.action === 'accept') {
                    
                    response = await this.acceptFollowRequest(data)
                } else if (accountData.action === 'decline') {
                    
                    response = await this.declineFollowRequest(data)
                }
                if (response === 'successful') {
                    this.removeRequest(accountData.account.id, 'account')
                }
            },
            async acceptOrRejectMessage(messageData){
                let response,
                    data = {
                        userId: messageData.message.userId,
                        messageId: messageData.message.messageId,
                        action: messageData.action
                    }

                response = await this['profile/discusionContributionResponse'](data)

                if (response.status) {
                    this.removeRequest(messageData.message.id,'message')
                } else {
                    console.log('response :>> ', response);
                }
            },
            async moreRequests(){
                let response,
                    data = {
                        nextPage: this.requestNextPage,
                    }
                if (this.type === 'requests') {
                    response = await this.userRequests(data)
                } else if (this.type === 'notifications') {
                    response = await this.userNotifications(data)
                }

                this.requestLoading = false
                if (response.status) {
                    if (this.type === 'requests') {
                        this.requests.push(...response.data)
                    } else if (this.type === 'notifications') {
                        this.notifications.push(...response.data)
                    }
                    
                    if (response.next) {
                        this.requestNextPage += 1
                        this.showMoreRequests = true
                    } else {
                        this.showMoreRequests= false
                    }
                } else {
                    // this.showMoreRequests = true
                    console.log('response :>> ', response);
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

    .no-requests,
    .has-requests{
        min-height: 100px;
        width: 100%;
        text-align: center;
        padding: 10px;
        font-size: 12px;
        font-weight: 450;
    }

    .has-requests{
        min-height: unset;
    }

    .request-badge{
        margin-bottom: 10px;
    }
</style>