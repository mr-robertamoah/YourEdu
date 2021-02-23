<template>
    <div class="payment-types-wrapper">
        <div class="radio-section">
            <radio-input
                name="payment"
                label="free"
                radioValue="free"
                v-model="payment"
                class="radio-button"
                key="payment1"
            ></radio-input>
            <radio-input
                name="payment"
                label="commission"
                radioValue="commission"
                v-model="payment"
                class="radio-button"
                v-if="computedCommission"
                key="payment2"
            ></radio-input>
            <radio-input
                name="payment"
                label="subscription"
                radioValue="subscription"
                v-model="payment"
                class="radio-button"
                v-if="computedSubscription"
                key="payment3"
            ></radio-input>
            <radio-input
                name="payment"
                label="one-time"
                radioValue="price"
                v-model="payment"
                class="radio-button"
                v-if="computedOneTime"
                key="payment4"
            ></radio-input>
            <radio-input
                name="payment"
                label="fee"
                radioValue="fee"
                v-model="payment"
                class="radio-button"
                v-if="computedFee"
                key="payment5"
            ></radio-input>
        </div>

        <div class="preview-section" v-if="computedOneTime || computedSubscription || 
            computedFee">
            <div class="main">
                <template v-if="computedOneTime">
                    <price-badge
                        v-for="(price,index) in prices"
                        :key="index"
                        :data="price"
                        @clickedRemoveData="clickedRemovePrice"
                        class="payment-badge"
                    ></price-badge>
                </template>
                <template v-if="computedSubscription">
                    <subscription-badge
                        v-for="(subscription,index) in subscriptions"
                        :key="index"
                        :data="subscription"
                        @clickedRemoveData="clickedRemoveSubscription"
                        class="payment-badge"
                    ></subscription-badge>
                </template>
                <template v-if="computedFee">
                    <fee-badge
                        v-for="(fee,index) in fees"
                        :key="index"
                        :data="fee"
                        @clickedRemoveData="clickedRemoveFee"
                        class="payment-badge"
                    ></fee-badge>
                </template>
            </div>
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
                v-model="fee.amount"
                :bottomBorder="true"
                :hasMax="false"
            ></number-input>

            <div class="message">
                Please note: if you do not select academic year section(s) for this fee, the selected academic years will be used for the fee. You can create fees for different academic year sections.
            </div>
            <div class="academic-sections" v-if="sections.length">
                <item-badge
                    v-for="(item,index) in sections"
                    :key="index"
                    :item="item"
                    type="section"
                    :hasRemove="inItemSelection(item)"
                    class="class-badge"
                    @clickedItem="itemSelected"
                    @clickedRemoveItem="removeItem"
                ></item-badge>
            </div>

            <div class="message" v-if="!sections.length">
                there are no academic year sections
            </div>
            <div class="fee-ok" @click="clickedFeeOk">
                set up fee
            </div>
        </div>

        <div class="price-section" v-if="payment === 'price'">
            <number-input 
                placeholder="one time price"
                v-model="price.amount"
                :hasMax="false"
                :bottomBorder="true"
            ></number-input>
            
            <text-textarea
                placeholder="description" 
                v-model="price.description"
                :bottomBorder="true"
            ></text-textarea>

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
            
            <text-textarea
                placeholder="description" 
                v-model="subscription.description"
                :bottomBorder="true"
            ></text-textarea>

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
import ItemBadge from './dashboard/ItemBadge';
import MainSelect from './MainSelect';
import TextInput from './TextInput';
import PriceBadge from './PriceBadge';
import FeeBadge from './FeeBadge';
import SubscriptionBadge from './SubscriptionBadge';
import NumberInput from './NumberInput';
import TextTextarea from './TextTextarea.vue';
    export default {
        components: {
            NumberInput,
            SubscriptionBadge,
            FeeBadge,
            PriceBadge,
            TextInput,
            MainSelect,
            ItemBadge,
            RadioInput,
            TextTextarea,
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
            sections: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data() {
            return {
                payment: '',
                fee: {
                    amount: '',
                    id: '',
                    sections: [],
                },
                price: {
                    amount: '',
                    description: '',
                    for: '',
                },
                subscription: {
                    amount: '',
                    for: '',
                    name: '',
                    description: '',
                    period: '',
                },
                prices: [],
                fees: [],
                commission: '',
                errorMessage: '',
                errorLengthy: false,
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
            fees(newValue) {
                this.$emit('paymentType',{
                    type: this.payment,
                    data: newValue
                })
            },
            payment(newValue){
                if (newValue === 'free') {
                    this.$emit('paymentType',{type: this.payment,data: ''})
                } else {
                    this.$emit('paymentType',{type: this.payment,data: null})
                }
                this.cleanUp()
            },
            price: {
                deep: true,
                handler(newValue,oldValue){
                    if ((newValue.amount.length === 1 || newValue.description.length === 1) 
                        && newValue.for.length) {
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
                        newValue.period.length === 1 || newValue.description.length === 1) 
                        && newValue.for.length) {
                        this.subscription.for = ''
                    } else if (newValue.name.length && newValue.amount.length && 
                        newValue.for.length) {
                        this.updateSubscriptions()
                    }
                }
            },
            errorMessage(newValue){
                if (newValue.length) {
                    this.$emit('paymentTypeError',{
                        message: newValue,
                        lengthy: this.errorLengthy
                    })
                    this.errorLengthy = false
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
            clickedFeeOk() {
                if (this.fee.amount.length) {
                    this.updateFees()
                } else {
                    this.errorMessage = 'please enter fee amount'
                }
            },
            inItemSelection(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            itemSelected(data) {
                let index = this.findItemIndex(data)
                if (index === -1) {
                    this.fee.sections.push(data)
                }
            },
            findItemIndex(data) {
                return this.fee.sections.findIndex(cl=>{
                    return cl.id === data.id
                })
            },
            removeItem(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.fee.sections.splice(index,1)
                }
            },
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
            clickedRemoveFee(data){
                let index = this.fees.findIndex(fee=>{
                    return fee.id === data.id
                })
                if (index > -1) {
                    this.fees.splice(index,1)
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
                        this.errorLengthy = true
                        this.errorMessage = `There is already a price for ${this.price.for}. Either remove existing or change it for this.`
                    }
                    return
                }
                this.prices.push({
                    amount: this.price.amount,
                    description: this.price.description,
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
                        this.errorLengthy = true
                        this.errorMessage = `There is already a subscription for ${this.subscription.for}. Either remove existing or change it for this.`
                    }
                    return
                }
                this.subscriptions.push({
                    name: this.subscription.name,
                    description: this.subscription.description,
                    for: this.subscription.for,
                    amount: this.subscription.amount,
                    period: this.subscription.period,
                })
                this.clearSubscription()
            },
            updateFees() {
                let sectionIndex
                let index = this.fees.findIndex(fee=>{
                    for (let i = 0; i < this.fee.sections.length; i++) {
                        const section = this.fee.sections[i];
                        sectionIndex = fee.sections.findIndex(sec=>{
                            return sec.id === section.id
                        })
                        if (sectionIndex > -1) {
                            return true
                        }
                        if (!fee.sections.length && !section.length) {
                            return true
                        }
                    }
                    return false
                })
                if (index > -1) {
                    this.errorLengthy = true
                    if (this.fee.sections.length) {
                        this.errorMessage = `There is already a fee for one of the selected academic year sections. Either remove existing fee or change it for this.`
                    } else {
                        this.errorMessage = 'There is a fee for the academic years selected. Either remove that or set up this fee for specific academic year sections.'
                    }
                    return
                }
                this.fees.push({
                    amount: this.fee.amount,
                    sections: this.fee.sections.map(section=>{
                        return {
                            id: section.id,
                            name: section.name,
                            type: 'academicYearSection'
                        }
                    }),
                    id: Math.round(Math.random() * 100)
                })
                this.clearFee()
            },
            clearPrice(){
                this.price.for = ''
                this.price.amount = ''
                this.price.description = ''
                this.errorLengthy = false
            },
            clearSubscription(){
                this.subscription.name = ''
                this.subscription.amount = ''
                this.subscription.description = ''
                this.subscription.for = ''
                this.subscription.period = ''
                this.errorLengthy = false
            },
            clearFee() {
                this.fee.amount = ''
                this.fee.id = ''
                this.fee.sections = []
                this.errorLengthy = false
            },
            cleanUp(){
                this.clearPrice()
                this.clearSubscription()
                this.clearFee()
                this.commission = ''
                this.prices = []
                this.subscriptions = []
            },
        },
    }
</script>

<style lang="scss" scoped>

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
        }

        .error{
            color: red;
            font-size: 12px;
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

            .message{
                font-size: 12px;
                color: gray;
                padding: 20px 10px;
                text-align: center;
            }

            .academic-sections{
                width: 100%;
                padding: 10px;
                max-height: 300px;
                overflow-y: auto;
                display: flex;
                margin-bottom: 10px;
            }

            .fee-ok{
                font-size: 12px;
                padding: 5pxx 10px;
                width: fit-content;
                cursor: pointer;
                margin: 0px auto;
                box-shadow: 0 0 3px grey;
                border-radius: 10px;
                padding: 5px 10px;
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
            border: 2px solid $color-main;
        }
    }
</style>