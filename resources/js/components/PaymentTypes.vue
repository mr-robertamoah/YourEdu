<template>
    <div class="payment-types-wrapper">
        <div class="radio-section">
            <radio-input
                name="payment"
                label="free"
                radioValue="free"
                v-model="payment"
                class="radio-button"
            ></radio-input>
            <radio-input
                name="payment"
                label="commission"
                radioValue="commission"
                v-model="payment"
                class="radio-button"
                v-if="computedCommission"
            ></radio-input>
            <radio-input
                name="payment"
                label="subscription"
                radioValue="subscription"
                v-model="payment"
                class="radio-button"
                v-if="computedSubscription"
            ></radio-input>
            <radio-input
                name="payment"
                label="one-time"
                radioValue="price"
                v-model="payment"
                class="radio-button"
                v-if="computedOneTime"
            ></radio-input>
            <radio-input
                name="payment"
                label="fee"
                radioValue="fee"
                v-model="payment"
                class="radio-button"
                v-if="computedFee"
            ></radio-input>
        </div>
        <div class="commission-section" v-if="payment === 'commission'">

            <number-input placeholder="commission"
                v-model="commission"
                class="input"
                :noBorder="true"
            ></number-input>
            <div class="per">%</div>
        </div>
        <div class="fee-section" v-if="payment === 'fee'">

            <number-input placeholder="fee"
                v-model="fee"
                :bottomBorder="true"
                :hasMax="false"
            ></number-input>
        </div>

        <div class="preview-section" v-if="computedOneTime || computedSubscription">
            <div class="error" v-if="errorMessage.length">
                {{errorMessage}}
            </div>
            <div class="main">
                <template v-if="computedOneTime">
                    <price-badge
                        v-for="(price,index) in prices"
                        :key="index"
                        :price="price"
                        @clickedRemovePrice="clickedRemovePrice"
                        class="badge"
                    ></price-badge>
                </template>
                <template v-if="computedSubscription">
                    <subscription-badge
                        v-for="(subscription,index) in subscriptions"
                        :key="index"
                        :subscription="subscription"
                        @clickedRemoveSubscription="clickedRemoveSubscription"
                        class="badge"
                    ></subscription-badge>
                </template>
            </div>
        </div>
        <div class="price-section" v-if="payment === 'price'">
            <number-input 
                placeholder="one time price"
                v-model="price.amount"
                :hasMax="false"
                :bottomBorder="true"
            ></number-input>
            <main-select
                :items="['all','learners','facilitators','parents','professionals','schools',]"
                :value="price.for"
                backgroundColor="white"
                @selection="priceForSelection"
                class="main-select"
                placeholder="set price for"
            ></main-select>
        </div>
        <div class="subscription-section" v-if="payment === 'subscription'">            
            <text-input
                placeholder="name" 
                v-model="subscription.name"
                :bottomBorder="true"
            ></text-input>

            <div class="sub-section">
                <number-input placeholder="amount"
                    v-model="subscription.amount"
                    :bottomBorder="true"
                    :hasMax="false"
                    class="text-input"
                ></number-input>
                <div class="per">per</div>
                <main-select
                    :items="['month','quarter','year',]"
                    :value="subscription.period"
                    backgroundColor="white"
                    @selection="subscriptionPeriodSelection"
                    class="main-select"
                    placeholder="select a period*"
                ></main-select>
            </div>

            <main-select
                :items="['all','learners','facilitators','parents','professionals','scools',]"
                :value="subscription.for"
                backgroundColor="white"
                @selection="subscriptionForSelection"
                class="main-select"
                placeholder="set subscription for"
            ></main-select>
        </div>
    </div>
</template>

<script>
import RadioInput from './RadioInput';
import MainSelect from './MainSelect';
import TextInput from './TextInput';
import PriceBadge from './PriceBadge';
import SubscriptionBadge from './SubscriptionBadge';
import NumberInput from './NumberInput';
    export default {
        components: {
            NumberInput,
            SubscriptionBadge,
            PriceBadge,
            TextInput,
            MainSelect,
            RadioInput,
        },
        props: {
            type: {
                type: String,
                default: ''
            },
            radioValue: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                payment: '',
                fee: '',
                price: {
                    amount: '',
                    for: '',
                },
                subscription: {
                    amount: '',
                    for: '',
                    name: '',
                    period: '',
                },
                prices: [],
                commission: '',
                errorMessage: '',
                subscriptions: [],
            }
        },
        watch: {
            radioValue(newValue) {
                if (newValue.length && newValue !== this.payment) {
                    this.payment = newValue
                }
            },
            prices(newValue) {
                this.$emit('paymentType',{type: this.payment,data: newValue})
            },
            subscriptions(newValue) {
                this.$emit('paymentType',{type: this.payment,data: newValue})
            },
            fee(newValue) {
                this.$emit('paymentType',{
                    type: this.payment,
                    data: newValue
                })
            },
            payment(newValue){
                if (newValue === 'free') {
                    this.$emit('paymentType',{type: this.payment,data: ''})
                }
                this.cleanUp()
            },
            price: {
                deep: true,
                handler(newValue,oldValue){
                    if (newValue.amount.length === 1 && newValue.for.length) {
                        this.price.for = ''
                    } else if (newValue.amount.length && newValue.for.length) {
                        this.updatePrices()
                    }
                }
            },
            subscription: {
                deep: true,
                handler(newValue){
                    if ((newValue.name.length === 1 || newValue.amount.length === 1 ||
                        newValue.period.length === 1) 
                        && newValue.for.length) {
                        this.subscription.for = ''
                    } else if (newValue.amount.length && newValue.for.length) {
                        this.updateSubscriptions()
                    }
                }
            },
            errorMessage(newValue){
                if (newValue.length) {
                    setTimeout(() => {
                        this.errorMessage = ''
                    }, 3000);
                }
            },
        },
        computed: {
            computedSubscription() {
                return this.type === 'subsciption' || this.type === 'subscription and one-time'
            },
            computedOneTime() {
                return this.type === 'one-time' || this.type === 'subscription and one-time' ||
                    this.type === 'fee and one-time'
            },
            computedFee() {
                return this.type === 'fee' || this.type === 'fee and one-time'
            },
            computedCommission() {
                return this.type === 'commission'
            },
        },
        methods: {
            priceForSelection(data) {
                this.price.for = data
            },
            subscriptionForSelection(data) {
                this.subscription.for = data
            },
            subscriptionPeriodSelection(data){
                this.subscription.period = data
            },
            clickedRemovePrice(data){
                let index = this.prices.findIndex(price=>{
                    return price.amount === data.amount &&
                        price.for === data.for
                })
                if (index > -1) {
                    this.prices.splice(index,1)
                }
            },
            clickedRemoveSubscription(data){
                let index = this.subscriptions.findIndex(subscription=>{
                    return subscription.amount === data.amount &&
                        subscription.for === data.for
                })
                if (index > -1) {
                    this.subscriptions.splice(index,1)
                }
            },
            clickedAction(data){
                if (this.payment === 'price') {
                    this.updatePrices()
                    this.clearPrice()
                } else if (this.payment === 'subscription') {
                    this.updateSubscriptions()
                    this.clearSubscription()
                }
            },
            updatePrices(){
                let index = this.prices.findIndex(price=>{
                    return price.for === this.price.for
                })
                if (index > -1) {
                    if (this.price.for.length) {
                        this.errorMessage = `There is already a price for ${this.price.for}. Either remove existing or change it for this.`
                    }
                    return
                }
                this.prices.push({
                    amount: this.price.amount,
                    for: this.price.for,
                })
                this.clearPrice()
            },
            updateSubscriptions(){
                let index = this.subscriptions.findIndex(subscription=>{
                    return subscription.for === this.subscription.for
                })
                if (index > -1) {
                    if (this.subscription.for.length) {
                        this.errorMessage = `There is already a subscription for ${this.subscription.for}. Either remove existing or change it for this.`
                    }
                    return
                }
                this.subscriptions.push({
                    name: this.subscription.name,
                    for: this.subscription.for,
                    amount: this.subscription.amount,
                    period: this.subscription.period,
                })
                this.clearSubscription()
            },
            clearPrice(){
                this.price.for = ''
                this.price.amount = ''
            },
            clearSubscription(){
                this.subscription.name = ''
                this.subscription.amount = ''
                this.subscription.for = ''
                this.subscription.period = ''
            },
            cleanUp(){
                this.clearPrice()
                this.clearSubscription()
                this.commission = ''
                this.fee = ''
            },
        },
    }
</script>

<style lang="scss" scoped>
$background-color-main: rgba(22, 233, 205, 1);

@mixin description(){
    width: 90%;
    padding: 10px;
    color: gray;
    font-style: italic;
    margin: auto;
}

    .payment-types-wrapper{

        .radio-section{
            display: flex;
            width: 100%;
            margin-bottom: 10px;
            flex-wrap: wrap;

            .radio-button{

            }
        }

        .preview-section{

            .main{
                display: flex;
                width: 100%;
                align-items: center;
                flex-wrap: nowrap;
            }

            .error{
                color: red;
                font-size: 12px;
            }
        }

        .commission-section{
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            
            .per{
                min-width: fit-content;
                margin-left: 10px;
            }
        }

        .fee-section{

            .description{
                @include description();
            }
        }

        .subscription-section{

            .sub-section{
                display: flex;
                flex-wrap: nowrap;
                align-items: center;
                width: 100%;

                .per{
                    margin: 0 10px;
                }

                .main-select{
                    max-width: 150px;
                    min-width: 130px;
                }

                .text-input{
                    max-width: 150px;
                }
            }
        }

        .action-button{
            margin: 10px auto;
        }

        .input{
            border: 2px solid $background-color-main;
        }
    }
</style>