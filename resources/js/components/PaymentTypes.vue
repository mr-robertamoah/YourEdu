<template>
    <div class="payment-types-wrapper">
        <div class="radio-section" v-if="showRadios">
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
            <radio-input
                name="payment"
                label="salary"
                radioValue="salary"
                v-model="payment"
                class="radio-button"
                v-if="computedSalary"
                key="payment6"
            ></radio-input>
            <radio-input
                name="payment"
                label="discount"
                radioValue="discount"
                v-model="payment"
                class="radio-button"
                v-if="computedDiscount"
                key="payment6"
            ></radio-input>
        </div>

        <div class="preview-section" v-if="computedOneTime || computedSubscription || 
            computedFee || computedSalary || computedDiscount">
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
                <template v-if="computedSalary">
                    <salary-badge
                        v-if="salary.amount.length"
                        :data="salary"
                        @clickedRemoveData="clickedRemoveSalary"
                        class="payment-badge"
                    ></salary-badge>
                </template>
                <template v-if="computedDiscount">
                    <discount-badge
                        v-if="computedHasDiscountData"
                        :data="discount"
                        @clickedRemoveData="clickedRemoveDiscount"
                        class="payment-badge"
                    ></discount-badge>
                </template>
                <template v-if="computedCommission">
                    <commission-badge
                        v-if="commission.percentageOwned.length"
                        :data="commission"
                        @clickedRemoveData="clickedRemoveCommission"
                        class="payment-badge"
                    ></commission-badge>
                </template>
            </div>
        </div>
        
        <div class="commission-section" v-if="payment === 'commission'">

            <number-input 
                placeholder="commission"
                v-model="commission.percentageOwned"
                class="input"
                :noBorder="true"
            ></number-input>
            <div class="per">%</div>
        </div>
        <div class="discount-section" v-if="payment === 'discount'">
            <div class="small-msg">
                choose how you want to set up the discount
            </div>
            <div class="radio-section">
                <radio-input
                    name="discount"
                    label="discounted price"
                    radioValue="discountedPrice"
                    v-model="discountType"
                    class="radio-button"
                    key="discount1"
                ></radio-input>
                <radio-input
                    name="discount"
                    label="discount percentage"
                    radioValue="percentage"
                    v-model="discountType"
                    class="radio-button"
                    key="discount2"
                ></radio-input>
            </div>         
            <text-input
                placeholder="discount name" 
                v-model="discount.name"
                :bottomBorder="true"
                v-if="discountType.length"
            ></text-input>
            <number-input placeholder="discounted price"
                v-model="discount.discountedPrice"
                :bottomBorder="true"
                :hasMax="false"
                v-if="discountType === 'discountedPrice'"
            ></number-input>
            <number-input placeholder="discount percentage"
                v-model="discount.percentage"
                :bottomBorder="true"
                :hasMax="false"
                v-if="discountType === 'percentage'"
            ></number-input>
            <date-picker
                v-if="discountType.length"
                :bottomBorder="true"
                v-model="discount.expiresAt"
                class="other-input"
                placeholder="expiration date for discount"
                :flatPickrConfig="{
                    dateFormat: 'F j, Y H:i',
                    enableTime: true,
                    minDate: computedTomorrowDate,
                }"
            ></date-picker>
        </div>
        <div class="fee-section" v-if="payment === 'fee'">

            <number-input placeholder="fee"
                v-model="fee.amount"
                :bottomBorder="true"
                :hasMax="false"
            ></number-input>

            <div class="message">
                Please note: First select an academic year in the class attachments section. Then make a selection from here because you cannot create a fee without selecting academic years (or sections) for it. You can create fees for different academic years and sections.
            </div>
        
            <div class="small-msg" v-if="academicYears.length">
                choose one and make your selection
            </div>
            <div class="radio-section" v-if="academicYears.length">
                <radio-input
                    name="feeable"
                    label="academic years"
                    radioValue="academicYears"
                    v-model="feeableType"
                    class="radio-button"
                    key="feeable1"
                ></radio-input>
                <radio-input
                    name="feeable"
                    label="academic year sections"
                    radioValue="academicYearSections"
                    v-model="feeableType"
                    class="radio-button"
                    key="feeable2"
                ></radio-input>
            </div>
            <div class="academic-sections" v-if="computedAcademicYearsOrSections.length">
                <item-badge
                    v-for="(item,index) in computedAcademicYearsOrSections"
                    :key="index"
                    :item="item"
                    type="section"
                    :hasRemove="inItemSelection(item)"
                    class="class-badge"
                    @clickedItem="itemSelected"
                    @clickedRemoveItem="removeItem"
                ></item-badge>
            </div>

            <div class="small-msg" v-if="computedFeeableSelectionMessage.length">
                {{computedFeeableSelectionMessage}}
            </div>
            <div class="message" v-if="!academicYears.length">
                there are no academic years or sections
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
        <div class="salary-section" v-if="payment === 'salary'">
            <div class="sub-section">
                <number-input placeholder="amount"
                    v-model="salary.amount"
                    :bottomBorder="true"
                    :hasMax="false"
                    class="text-input"
                ></number-input>
                <div class="per">per</div>
                <main-select
                    :items="['month','quarter','year',]"
                    :value="salary.period"
                    backgroundColor="white"
                    @selection="salaryPeriodSelection"
                    class="main-select"
                    placeholder="select a period*"
                ></main-select>
            </div>
            <!-- add currency implementation here -->
        </div>
    </div>
</template>

<script>
import RadioInput from './RadioInput.vue';
import ItemBadge from './dashboard/ItemBadge.vue';
import MainSelect from './MainSelect.vue';
import TextInput from './TextInput.vue';
import PriceBadge from './PriceBadge.vue';
import CommissionBadge from './CommissionBadge.vue';
import FeeBadge from './FeeBadge.vue';
import SalaryBadge from './SalaryBadge.vue';
import DiscountBadge from './DiscountBadge.vue';
import SubscriptionBadge from './SubscriptionBadge.vue';
import NumberInput from './NumberInput.vue';
import DatePicker from './DatePicker.vue';
import TextTextarea from './TextTextarea.vue';
import { dates } from '../services/helpers';
    export default {
        components: {
            DatePicker,
            NumberInput,
            SubscriptionBadge,
            DiscountBadge,
            SalaryBadge,
            FeeBadge,
            CommissionBadge,
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
            showRadios: {
                type: Boolean,
                default: true
            },
            academicYears: {
                type: Array,
                default() {
                    return []
                }
            },
        },
        data() {
            return {
                payment: '',
                fee: {
                    amount: '',
                    id: '',
                    feeables: [],
                },
                discount: {
                    name: '',
                    discountedPrice: '',
                    percentage: '',
                    expiresAt: '',
                    type: '',
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
                salary: {
                    amount: '',
                    currency: '',
                    period: '',
                },
                prices: [],
                fees: [],
                feeableType: '',
                discountType: '',
                commission: {
                    percentageOwned: ''
                },
                errorMessage: '',
                errorLengthy: false,
                subscriptions: [],
            }
        },
        watch: {
            radioValue: {
                immediate: true,
                handler(newValue) {
                    if (newValue.length && newValue !== this.payment) {
                        this.payment = newValue
                    }
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
            discountType(newValue) {
                this.discount.percentage = ''
                this.discount.discountedPrice = ''
            },
            "salary.amount": {
                handler(newValue) {
                    this.$emit('paymentType',{type: this.payment,data: this.salary})
                }
            },
            "salary.currency": {
                handler(newValue) {
                    this.$emit('paymentType',{type: this.payment,data: this.salary})
                }
            },
            "salary.period": {
                handler(newValue) {
                    this.$emit('paymentType',{type: this.payment,data: this.salary})
                }
            },
            payment(newValue){
                if (newValue === 'free') {
                    this.$emit('paymentType',{type: this.payment,data: ''})
                } else {
                    this.$emit('paymentType',{type: this.payment,data: null})
                }
                this.cleanUp()
            },
            discount: {
                deep: true,
                handler(newValue) {
                    if (this.computedHasDiscountData) {
                        this.$emit('paymentType',{type: this.payment, data: [newValue]})
                    } else {
                        this.$emit('paymentType',{type: this.payment, data: null})
                    }
                }
            },
            "commission.percentageOwned": {
                handler(newValue) {
                    if (newValue.length) {
                        this.$emit('paymentType',{type: this.payment, data: [this.commission]})
                    } else {
                        this.$emit('paymentType',{type: this.payment, data: null})
                    }
                }
            },
            "price.amount": {
                handler(newValue) {
                    if (
                        newValue.length === 1 && this.price.for.length
                    ) {
                        this.price.for = ''
                    }
                }
            },
            "price.amount": {
                handler(newValue) {
                    if (
                        newValue.length === 1 && this.price.for.length
                    ) {
                        this.price.for = ''
                    }
                }
            },
            "price.for": {
                handler(newValue) {
                    if (
                        this.price.amount.length && 
                        newValue.length
                    ) {
                        this.updatePrices()
                    }
                }
            },
            "subscription.for": {
                handler(newValue) {
                    if (
                        this.subscription.name.length && 
                        this.subscription.amount.length && 
                        newValue.length
                    ) {
                        this.updateSubscriptions()
                    }
                }
            },
            "subscription.name": {
                handler(newValue) {
                    if (
                        newValue.length === 1 && this.subscription.for.length
                    ) {
                        this.subscription.for = ''
                    }
                }
            },
            "subscription.amount": {
                handler(newValue) {
                    if (
                        newValue.length === 1 && this.subscription.for.length
                    ) {
                        this.subscription.for = ''
                    }
                }
            },
            "subscription.description": {
                handler(newValue) {
                    if (
                        newValue.length === 1 && this.subscription.for.length
                    ) {
                        this.subscription.for = ''
                    }
                }
            },
            "subscription.period": {
                handler(newValue) {
                    if (
                        newValue.length === 1 && this.subscription.for.length
                    ) {
                        this.subscription.for = ''
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
            feeableType(newValue) {
                this.fee.feeables = []
            },
        },
        computed: {
            computedSubscription() {
                return this.type.includes('subscription')
            },
            computedOneTime() {
                return this.type.includes('one-time')
            },
            computedFee() {
                return this.type.includes('fee')
            },
            computedSalary() {
                return this.type.includes('salary')
            },
            computedCommission() {
                return this.type.includes('commission')
            },
            computedDiscount() {
                return this.type.includes('discount')
            },
            computedCommission() {
                return this.type.includes('commission')
            },
            computedAcademicYearsOrSections() {
                if (this.feeableType === 'academicYears') {
                    return this.computedMappedAcademicYears
                }

                if (this.feeableType === 'academicYearSections') {
                    return this.computedMappedAcademicYearSections
                }

                return []
            },
            computedMappedAcademicYears() {
                return this.academicYears.map(academicYear=>{
                    return {
                        id: academicYear.id,
                        name: academicYear.name,
                        description: academicYear.description,
                        endDate: academicYear.endDate,
                        startDate: academicYear.startDate,
                        type: academicYear.type,
                    }
                })
            },
            computedMappedAcademicYearSections() {
                let sections = []
                this.academicYears.forEach(academicYear=>{
                    sections.push(...academicYear.sections.map(section=>{
                        return {
                            id: section.id,
                            name: section.name,
                            academicYearId: section.academicYearId,
                            endDate: section.endDate,
                            startDate: section.startDate,
                            type: 'academicYearSection'
                        }
                    }))
                })

                return sections
            },
            computedFeeableSelectionMessage() {
                return this.feeableType === 'academicYearSections' && 
                    !this.computedMappedAcademicYearSections.length ?
                    'there are no academic year sections for the selected academic years' : ''
            },
            computedHasDiscountData() {
                return this.discount.name.length && 
                    (this.discount.discountedPrice.length || 
                    this.discount.percentage.length)
            },
            computedTomorrowDate() {
                return this.payment === 'discount' ? dates.tomorrow() : null
            }
        },
        methods: {
            clickedFeeOk() {
                if (!this.fee.amount.length) {
                    this.errorMessage = 'please enter fee amount'
                    return
                }
                
                if (!this.fee.feeables.length) {
                    this.errorMessage = 'please you require an academic year or academic year section for a selected academic year'
                    return
                }

                this.updateFees()
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
                    this.fee.feeables.push(data)
                }
            },
            findItemIndex(data) {
                return this.fee.feeables.findIndex(cl=>{
                    return cl.id === data.id && cl.type === data.type
                })
            },
            removeItem(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.fee.feeables.splice(index,1)
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
            salarySelection(data){
                this.salary.period = data
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
            clickedRemoveSalary(data){
                this.clearSalary()
            },
            clickedRemoveDiscount(data){
                this.clearDiscount()
            },
            clickedRemoveCommission(data){
                this.clearCommission()
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
                console.log('in subscription update');
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
                let feeableIndex
                let index = this.fees.findIndex(fee=>{
                    for (let i = 0; i < this.fee.feeables.length; i++) {
                        const feeable = this.fee.feeables[i];
                        feeableIndex = fee.feeables.findIndex(f=>{
                            return f.id === feeable.id &&
                                f.type === feeable.type
                        })
                        if (feeableIndex > -1) {
                            return true
                        }
                        if (!fee.feeables.length && !feeable.length) {
                            return true
                        }
                    }
                    return false
                })
                if (index > -1) {
                    this.errorLengthy = true
                    if (this.fee.feeables.length) {
                        this.errorMessage = `There is already a fee for one of the selected academic year (or section). Either remove existing fee or change it for this.`
                    } else {
                        this.errorMessage = 'There is a fee for the academic year (or section) selected. Either remove that or set up this fee for specific academic year (or section).'
                    }
                    return
                }

                this.fees.push({
                    amount: this.fee.amount,
                    feeables: this.fee.feeables.map(feeable=>{
                        return {
                            id: feeable.id,
                            type: feeable.type
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
            clearSalary(){
                this.salary.amount = ''
                this.salary.currency = ''
                this.salary.period = ''
                this.errorLengthy = false
            },
            clearDiscount(){
                this.discount.name = ''
                this.discount.expiresAt = ''
                this.discount.percentage = ''
                this.discount.discountedPrice = ''
                this.errorLengthy = false
            },
            clearCommission(){
                this.commission.percentageOwned = ''
                this.errorLengthy = false
            },
            clearFee() {
                this.fee.amount = ''
                this.fee.id = ''
                this.fee.feeables = []
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

        .subscription-section,
        .salary-section{

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

        .discount-section{

        }

        .action-button{
            margin: 10px auto;
        }

        .input{
            border: 2px solid $color-primary;
        }
    }
</style>