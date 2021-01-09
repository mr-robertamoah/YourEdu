<template>
    <div class="dashboard-section-account-wrapper" v-if="account">
        <div 
            class="state" 
            :class="{danger: computedState === 'DELETED'}"
            v-if="computedOwner && computedState.length"
        >
            {{computedState}}
        </div>
        <div class="name">
            {{computedName}}
            <div class="username" v-if="computedUsername">
                {{computedUsername}}
            </div>
        </div>
        <div class="detail">
            <div class="title" v-if="computedTitle.length">
                {{computedTitle}}
            </div>
            <div class="actions">
                <dashboard-action-button
                    text="view"
                    icon=""
                    :data="account"
                    v-if="computedShowView"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    text="activities"
                    icon=""
                    :data="account"
                    v-if="computedShowActivities"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :text="`profile`"
                    icon="eye"
                    :data="account"
                    v-if="computedViewProfile"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :text="`remove ${type}`"
                    icon=""
                    :data="account"
                    :hasLoading="true"
                    v-if="computedShowRemoveType"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`edit this ${type}'s information`"
                    text="edit"
                    icon="pencil-alt"
                    :data="account"
                    v-if="type === 'admin'|| this.type === 'admin'"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`remove this ${type}`"
                    text="remove"
                    icon="trash"
                    :data="account"
                    :hasLoading="true"
                    v-if="type === 'admin'"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`ban this ${type}`"
                    text="ban"
                    icon="ban"
                    :data="account"
                    v-if="computedShowBan"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`unban this ${type}`"
                    text="unban"
                    icon="ban"
                    :data="account"
                    v-if="computedShowUnban"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`edit this ${type}`"
                    text="edit"
                    icon="pencil-alt"
                    :data="account"
                    v-if="computedShowDelete && computedState !== 'DELETED'"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`delete this ${type}`"
                    text="delete"
                    icon="trash"
                    :data="account"
                    v-if="computedShowDelete && computedState !== 'DELETED'"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`undo delete this ${type}`"
                    text="undo delete"
                    icon="trash-restore"
                    :data="account"
                    v-if="computedState === 'DELETED'"
                    @click="clickedDashboardActionButton"
                    class="action-button"
                ></dashboard-action-button>
            </div>
        </div>
        <div class="other-details" v-if="computedOtherDetails.length">
            {{computedOtherDetails}}
        </div>
        <div class="payment-types" 
            v-text="computedShowPaymentType"
            v-if="computedPaymentDetails"
            :class="[showPaymentTypes ? 'hide' : 'show']"
            @click="showPaymentTypes = !showPaymentTypes"
        ></div>
        <div class="payment-details" v-if="showPaymentTypes">
            <template v-if="computedPaymentDetails.type === 'subscription'">
                <subscription-badge
                    v-for="(subscription,index) in computedPaymentDetails.data"
                    :key="index"
                    :hasClose="false"
                    :data="subscription"
                    class="payment-badge"
                ></subscription-badge>
            </template>
            <template v-if="computedPaymentDetails.type === 'price'">
                <price-badge
                    v-for="(price,index) in computedPaymentDetails.data"
                    :key="index"
                    :data="price"
                    :hasClose="false"
                    class="payment-badge"
                ></price-badge>
            </template>
            <template v-if="computedPaymentDetails.type === 'fee'">
                <fee-badge
                    v-for="(fee,index) in computedPaymentDetails.data"
                    :key="index"
                    :fee="fee"
                    :hasClose="false"
                    class="payment-badge"
                ></fee-badge>
            </template>
        </div>
    </div>
</template>

<script>
import { dates } from '../../services/helpers'
import DashboardActionButton from './DashboardActionButton'
import FeeBadge from '../FeeBadge'
import PriceBadge from '../PriceBadge'
import SubscriptionBadge from '../SubscriptionBadge'
import { mapGetters } from 'vuex'
    export default {
        components: {
            SubscriptionBadge,
            PriceBadge,
            FeeBadge,
            DashboardActionButton,
        },
        props: {
            type: {
                type: String,
                default: ''
            },
            admin: {
                type: Boolean,
                default: false
            },
            account: { //all classes, courses, extracurriculums, user accounttypes, etc
                type: Object,
                default(){
                    return null
                }
            },
        },
        data() {
            return {
                showPaymentTypes: false
            }
        },
        computed: {
            ...mapGetters(['getUser']),
            computedTitle() {
                return this.account.title ? this.account.title : '' 
            },
            computedShowPaymentType() {
                return this.showPaymentTypes ? 'hide payment' : 'show payment'
            },
            computedName(){
                return this.type === 'admin' ? `${this.account.name}` :
                    this.type === 'user' ? this.account.full_name : 
                    this.type === 'subject' || this.type === 'grade' ||
                    this.type === 'course' || this.type === 'program' ? 
                    this.account.data.name : this.account.name
            },
            computedUsername(){
                return this.type === 'admin' || this.type === 'user' ? ` 
                    (@${this.account.username})` : ''
            },
            computedOtherDetails() {
                let msg = ''
                if (this.type === 'admin') {
                    if (this.account.level) {
                        msg = `level: ${this.account.level} `
                    }
                    if (this.account.description) {
                        msg += `description: ${this.account.description} `
                    }
                } else if (this.type === 'user') {
                    if (this.account.gender) {
                        msg = `gender: ${this.account.gender} `
                    }
                    if (this.account.age) {
                        msg += `age: ${this.account.age} `
                    }
                    msg += `joined on: ${dates.dateReadable(this.account.created_at)} `
                } else if (this.type === 'subject' || this.type === 'grade' ||
                    this.type === 'course' || this.type === 'program') {
                    if (this.account.data.age_group) {
                        msg = `age group: ${this.account.data.age_group} `
                    }
                    if (this.account.data.description) {
                        msg += `description: ${this.account.data.description} `
                    }
                    if (this.account.data.rationale) {
                        msg += `rationale: ${this.account.data.rationale} `
                    }
                } else if (this.type === 'owned course' || this.type === 'normal course') {
                    if (this.account.description) {
                        msg += `description: ${this.account.description} `
                    }
                    if (this.account.lessons) {
                        msg += `lessons: ${this.account.lessons} `
                    }
                    if (this.account.learners) {
                        msg += `learners: ${this.account.learners.length} learners`
                    }
                    if (this.account.facilitators) {
                        msg += `facilitators: ${this.account.facilitators,length} facilitators`
                    }
                    if (this.account.professionanls) {
                        msg += `professionanls: ${this.account.professionanls.length} professionals`
                    }
                } else if (this.type === 'owned class') {
                    
                } else if (this.type === 'owned extracurriculum') {
                    
                }

                return msg
            },
            computedPaymentDetails() {
                if (this.type === 'owned course' || this.type === 'owned class') {
                    if (this.account.subscriptions.length) {
                        return {type: 'subscription', data: this.account.subscriptions}
                    } else if (this.account.prices.length) {
                        return {type: 'price', data: this.account.prices}
                    } else if (this.account.fees.length) {
                        return {type: 'fee', data: this.account.fees}
                    }
                }
                return null
            },
            computedIsAccount() {
                return this.type === 'facilitator' || this.type === 'parent' || 
                    this.type === 'professional' || this.type === 'learner'
            },
            computedViewProfile() {
                return this.computedIsAccount && this.admin
            },
            computedShowBan() {
                return (this.type === 'user' || this.type === 'learner' || 
                    this.computedIsAccount) &&
                    (this.getUser.is_superadmin || this.getUser.is_supervisoradmin)
            },
            computedShowUnban() {
                return this.type !== 'admin' && this.account.bans && this.account.bans.length &&
                    (this.getUser.is_superadmin || this.getUser.is_supervisoradmin)
            },
            computedShowActivities() {
                return this.computedShowBan || this.type === 'admin'
            },
            computedShowRemoveType() {
                return this.type === 'grade' || this.type === 'subject' ||
                    this.type === 'course' || this.type === 'program' ||
                    this.computedViewProfile
                    //school owner and admin should be able to remove account
            },
            computedShowDelete() {
                return this.type === 'owned course' || this.type === 'owned class' ||
                    this.type === 'owned extracurriculum'
            },
            computedState() {
                return this.account.state ? this.account.state : ''
            },
            computedShowView() {
                return this.type === 'owned course' || this.type === 'owned class' ||
                    this.type === 'normal course' || this.type === 'normal class' ||
                    this.type === 'normal extracurriculum' || this.type === 'owned extracurriculum'
            },
            computedOwner() {
                let ownerStatus = false
                if (this.account.hasOwnProperty('ownedby')) {
                    ownerStatus = this.account.ownedby.userId === this.getUser.id
                }
                return ownerStatus
            },
        },
        methods: {
            clickedDashboardActionButton(data) {
                this.$emit('clickedDashboardActionButton',{type: this.type, buttonData: data})
            }
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-section-account-wrapper{
        box-shadow: 0 0 2px grey;
        border-radius: 5px;
        padding: 5px;

        .state{
            background: green;
            color: white;
            padding: 5px;
            font-size: 14px;
            text-transform: lowercase;
            width: fit-content;
            border-radius: 5px;
            float: right;
            min-width: 100px;
            text-align: center;

            &.danger{
                background: red;
            }
        }

        .name{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            font-size: 14px;
            text-transform: capitalize;
            @include text-overflow();

            .username{
                font-size: 12px;
                color: gray;
                text-transform: initial;
            }
        }

        .detail{
            padding: 5px;
            border-left: 2px solid gray;
            display: flex;
            width: 100%;
            justify-content: space-between;

            .title{
                text-transform: uppercase;
                font-size: 10px;
                color: gray;
                margin-right: 10px;
            }

            .actions{
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                width: 100%;
                justify-content: flex-end;

                .action-button{
                    margin: 0 0 10px 10px;
                }
            }
        }

        .other-details{
            font-size: 12px;
            color: gray;
            text-transform: lowercase;
            font-style: italic;
        }

        .payment-types{
            font-size: 12px;
            padding: 5px;
            cursor: pointer;
            width: fit-content;
            border-radius: 5px;
            color: white;

            &.show{
                background: rgb(0, 128, 0, .9);
                margin-bottom: 10px;
            }

            &.hide{
                background: rgb(0, 128, 0, .6);
            }
        }

        .payment-details{
            display: flex;
            flex-wrap: nowrap;
            width: 100%;
            justify-content: center;
            align-items: center;
            overflow-x: auto;

            .payment-badge{
                max-width: 250px;
                min-width: 200px;
            }
        }
    }
</style>