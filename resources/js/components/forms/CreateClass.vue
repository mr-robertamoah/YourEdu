<template>
    <div>
        <just-fade>
            <template slot="transition" v-if="show">
                <main-modal
                    :show="show"
                    :mainOther="false"
                    :requests="false"
                    @mainModalDisappear='closeModal'
                    class="modal-wrapper"
                >
                    <template slot="main">
                        <welcome-form
                            :title="title"
                            class="create-class-wrapper"
                        >
                            <template slot="input">
                                <auto-alert
                                    :message="alertMessage"
                                    :success="alertSuccess"
                                    :danger="alertDanger"
                                    :lengthy="alertLengthy"
                                    :sticky="true"
                                    @hideAlert="clearAlert"
                                ></auto-alert>
                                <div class="loading" v-if="loading">
                                    <pulse-loader :loading="loading"></pulse-loader>
                                </div>
                                <div class="section">Class Info</div>
                                <text-input
                                    :bottomBorder="true"
                                    placeholder="class name*"
                                    v-model="data.name"
                                    class="other-input"
                                ></text-input>
                                <text-textarea
                                    :bottomBorder="true"
                                    placeholder="description of the class"
                                    v-model="data.description"
                                    class="class-input"
                                ></text-textarea>

                                <div class="class-structure" 
                                    v-if="computedShowStructure"
                                >
                                    <div class="message">
                                        How do you want the class structured?
                                    </div>
                                    <div class="main">
                                        <radio-input
                                            name="classStructure"
                                            label="class only has subjects"
                                            radioValue="subjects"
                                            v-model="data.structure"
                                            class="radio-button"
                                        ></radio-input>
                                        <radio-input
                                            name="classStructure"
                                            label="class only has courses"
                                            radioValue="courses"
                                            v-model="data.structure"
                                            class="radio-button"
                                        ></radio-input>
                                    </div>
                                </div>

                                <div class="attachments" v-if="data.grade.id && computedShowGrades">
                                    <attachment-badge
                                        :attachment="data.grade"
                                        :hasClose="true"
                                        @removeAttachment="removeGrade"
                                    ></attachment-badge>
                                </div>
                                <post-attachment
                                    v-if="computedShowGrades"
                                    :show="true"
                                    :hasSelect="true"
                                    mainSearchItem="grades"
                                    :hasClose="false"
                                    @clickedAttachmentSelection="gradeSelected"
                                    class="class-input"
                                ></post-attachment>

                                <main-select
                                    v-if="edit"
                                    :items="['pending','accepted','declined','suspended']"
                                    :value="data.state"
                                    backgroundColor="white"
                                    @selection="stateSelection"
                                    class="other-input"
                                    placeholder="change state of class"
                                ></main-select>
                                <main-checkbox
                                    v-if="computedCreator.account === 'facilitator'"
                                    v-model="data.facilitate"
                                    label="will you be a faciliatator in this class?"
                                    class="class-input"
                                ></main-checkbox>
                                <main-checkbox
                                    v-model="hasMaxLearners"
                                    v-if="!edit"
                                    label="check this if class has a maximum number of possible learner entries?"
                                    class="class-input"
                                ></main-checkbox>
                                <number-input
                                    v-if="hasMaxLearners"
                                    :bottomBorder="true"
                                    placeholder="maximum learner participants"
                                    :hasMax="false"
                                    v-model="data.maximum"
                                    class="other-input"
                                ></number-input>
                                
                                <div class="section" v-if="computedShowOwnership">Class Ownership</div>
                                <main-select
                                    class="other-input"
                                    v-if="computedShowOwnership"
                                    placeholder="select owner of this class"
                                    backgroundColor='white'
                                    :objects="computedPossibleOwners"
                                    :value="data.owner.name"
                                    @selection="ownerSelection"
                                ></main-select>

                                <div class="section"
                                    v-if="computedHasSchoolAccount || computedHasStructure"
                                >Class Attachments</div>
                                <div class="attachment-heading"
                                    v-if="computedHasStructure">
                                    {{data.structure}}
                                </div>
                                <search-input
                                    class="search-input"
                                    v-if="computedHasStructure"
                                    :searchPlaceholder="`search for ${data.structure}`"
                                    @search="getSearchItemsText"
                                ></search-input>
                                <div class="class-payment course-classes-section"
                                    v-if="computedHasStructure"
                                >
                                    <div
                                        v-if="computedSpecificItemStructure.length"
                                        class="class-wrapper"
                                    >
                                        <item-badge
                                            v-for="(item,index) in computedSpecificItemStructure"
                                            :key="index"
                                            :item="item"
                                            :type="item.type"
                                            :hasRemove="inItemSelection(item)"
                                            class="class-badge"
                                            @clickedItem="itemSelected"
                                            @clickedRemoveItem="removeItem"
                                        ></item-badge>
                                    </div>
                                    <div class="no-data"
                                        v-if="!specificItemLoadingStructure && 
                                            !computedSpecificItemStructure.length">
                                        {{`no ${data.structure} for this ${data.owner.account}`}}
                                    </div>
                                    <pulse-loader 
                                        :loading="specificItemLoadingStructure"
                                        size="12px"
                                        class="loading"
                                    ></pulse-loader>
                                    <div class="get-more" 
                                        @click="getSpecificAccountItemStructure"
                                        v-if="computedShowGetMore"
                                    >
                                        get more
                                    </div>
                                </div>
                                <div class="class-payment" 
                                    v-if="computedHasSchoolAccount"
                                >
                                    <pulse-loader
                                        :loading="specificItemLoading"
                                        size="10px"
                                    ></pulse-loader>

                                    <div class="message">
                                        {{computedMessage}}
                                    </div>
                                    <div class="academic-years">
                                        <item-badge
                                            v-for="(item,index) in computedAcademicYears"
                                            :key="index"
                                            :item="item"
                                            type="year"
                                            :hasRemove="inYearSelection(item)"
                                            class="class-badge"
                                            @clickedItem="yearSelected"
                                            @clickedRemoveItem="removeYear"
                                        ></item-badge>
                                    </div>
                                </div>
                                
                                <div class="section">Payments</div>                                
                                <div class="attachments" 
                                    v-if="data.mainPaymentData.length"
                                >
                                    <div
                                        v-for="(item,index) in data.mainPaymentData"
                                        :key="index"
                                    >
                                        <price-badge
                                            v-if="item.type === 'price'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'main')"
                                            class="payment-badge"
                                        ></price-badge>
                                        <subscription-badge
                                            v-if="item.type === 'subscription'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'main')"
                                            class="payment-badge"
                                        ></subscription-badge>
                                        <fee-badge
                                            v-if="item.type === 'fee'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'main')"
                                            class="payment-badge"
                                        ></fee-badge>
                                    </div>
                                </div>
                                
                                <div class="attachment-heading" 
                                    v-if="data.removedPaymentData.length"
                                >
                                    payment types to be removed
                                </div>
                                <div class="attachments danger" 
                                    v-if="data.removedPaymentData.length"
                                >
                                    <div
                                        v-for="(item,index) in data.removedPaymentData"
                                        :key="index"
                                    >
                                        <price-badge
                                            v-if="item.type === 'price'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></price-badge>
                                        <subscription-badge
                                            v-if="item.type === 'subscription'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></subscription-badge>
                                        <fee-badge
                                            v-if="item.type === 'fee'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></fee-badge>
                                    </div>
                                </div>
                                <div class="attachment-heading" 
                                    v-if="edit && data.paymentData"
                                >
                                    new payment types
                                </div>
                                <payment-types
                                    v-if="computedShowPayment"
                                    @paymentType="getPaymentType"
                                    :type="paymentType"
                                    :radioValue="data.type"
                                    class="payment-types"
                                    :sections="computedAcademicYearSections"
                                    @paymentTypeError="error"
                                ></payment-types>

                                <div class="section" v-if="!edit">Discussion</div>
                                <main-checkbox
                                    v-model="data.discussion"
                                    v-if="!edit && !data.discussionData.title.length"
                                    label="automatically add a discussion?"
                                    class="class-input"
                                ></main-checkbox>
                                <!-- discussion preview -->
                                <div class="discussion-preview"
                                    v-if="data.discussionData.title.length"
                                >
                                    <item-badge
                                        type="discussion"
                                        :item="data.discussionData"
                                        :hasRemove="true"
                                        @clickedRemoveItem="clearDiscussionData"
                                    ></item-badge>
                                </div>
                            </template>
                            <template slot="buttons">
                                <post-button
                                    :buttonText="buttonText"
                                    buttonStyle='success'
                                    @click="clickedCreate"
                                ></post-button>
                            </template>
                        </welcome-form>
                    </template>
                </main-modal>
            </template>
        </just-fade>
        <create-discussion
            :show="data.discussion"
            v-if="data.discussion"
            :edit="edit"
            :auto="true"
            @clickedCreate="getDiscussionData"
            @createDiscussionDisappear="closeDiscussionModal"
        ></create-discussion>
    </div>
</template>

<script>
import NumberInput from '../NumberInput';
import MainCheckbox from '../MainCheckbox';
import MainSelect from '../MainSelect';
import TextTextarea from '../TextTextarea';
import PostButton from '../PostButton';
import AttachmentBadge from '../AttachmentBadge';
import TextInput from '../TextInput';
import RadioInput from '../RadioInput';
import PostAttachment from '../PostAttachment';
import AutoAlert from '../AutoAlert';
import PaymentTypes from '../PaymentTypes';
import ItemBadge from '../dashboard/ItemBadge';
import CreateDiscussion from './CreateDiscussion';
import PriceBadge from '../PriceBadge';
import FeeBadge from '../FeeBadge';
import SubscriptionBadge from '../SubscriptionBadge';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import { mapActions, mapGetters } from 'vuex';
import { dates } from '../../services/helpers';
import {bus} from '../../app';
import DashboardCreateForm from '../../mixins/DashboardCreateForm.mixin';
    export default {
        components: {
            PulseLoader,
            CreateDiscussion,
            ItemBadge,
            PaymentTypes,
            AutoAlert,
            PostAttachment,
            SubscriptionBadge,
            FeeBadge,
            PriceBadge,
            RadioInput,
            TextInput,
            AttachmentBadge,
            PostButton,
            TextTextarea,
            MainSelect,
            MainCheckbox,
            NumberInput,
        },
        props: {

        },
        data() {
            return {
                hasMaxLearners: false,
                selectedAcademicYears: [],
                mainAcademicYears: [],
                removedAcademicYears: [],
                specificItemDetailsStructure: [],
                specificItemLoadingStructure: false,
                specificItemDetailsStructureNextPage: 0,
                learners: 0,
            }
        },
        watch: {
            'data.owner': {
                handler(newValue) {
                    if (newValue && newValue.account === 'school' &&
                        this.computedCreator.account !== 'school') {
                        this.specificItemDetails = []
                        this.specificItemDetailsNextPage = 0
                        this.getSpecificAccountItem()
                    }
                    if (newValue.account && this.computedShowStructure) {
                        if (this.specificItemDetailsStructureNextPage !== 0) this.data.items = []
                        this.debouncedSearchItems()
                    }
                }
            },
            'data.structure': {
                handler(newValue,oldValue) {
                    if (oldValue && oldValue.length) {
                        this.data.removedItems = []
                        this.data.removedItems.push(...this.data.items)
                        if (this.specificItemDetailsStructureNextPage !== 0) this.data.items = []
                    }
                    if (newValue && newValue !== 'null' && this.data.owner.account) {                        
                        this.debouncedSearchItems()
                    }
                }
            },
        },
        created () {
            this.title = 'create a class'
            this.paymentType = ''
            bus
            .$on('editClass',(data)=>{
                this.setData(data)
            })
            .$on('classOwnership',()=>{
                this.hasOwnership = true
                this.data.owner = this.computedCreator
            })
        },
        mixins: [DashboardCreateForm],
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','dashboard/getCurrentAccount',
                'getUser']),
            computedMessage(){
                if (this.data.owner.account === 'school' || 
                    this.computedAccount.account === 'school') { 
                    !this.computedAcademicYears.length ? 
                        'The school does not have a current(present/future) academic year, Hence, you cannot use a fee payment type for this class. If you desire to have a fee, then please go back and create a current academic year (with sections).' :
                    this.computedAcademicYears.length ? 
                        'You can use the fee section after selecting some current academic years' : ''
                }
                return ''
            },
            computedSpecificItemStructure() {
                return this.specificItemDetailsStructure
            },
            computedAcademicYearSections(){ //for selected academic years
                let sections = []
                this.selectedAcademicYears.forEach(year=>{
                    sections.push(...year.sections)
                })
                return sections
            },
            computedShowGetMore() {
                return this.specificItemDetailsStructureNextPage && this.specificItemDetailsStructureNextPage > 1 &&
                    !this.specificItemLoadingStructure
            },
            computedAcademicYears(){
                let academicYears = [],
                    now = dates.toDate(new Date())
                if (this.computedAccount.account !== 'school') {
                    academicYears = this.specificItemDetails
                } else {
                    academicYears = this["dashboard/getAccountDetails"].academicYears
                }
                return academicYears.filter(year=>{
                    return dates.toDate(new Date(year.startDate)) < now 
                })
            },
            computedPayment(){
                if (this.computedAccount.account === 'school' ||
                    this.data.owner.account === 'school') {
                    this.paymentType = 'fee and one-time'
                    return true
                } else if (this.computedAccount.account === 'facilitator' ||
                    this.data.owner.account === 'facilitator') {
                    this.paymentType = 'subscription and one-time'
                    return true
                }
                return false
            },
            computedShowStructure() {
                return true //but work on hiding it if it has some resources like lessons etc
            },
            computedHasStructure() {
                return this.data.structure && this.data.structure.length && 
                    this.data.structure !== 'null'
            },
            computedShowGrades() {
                return this.edit && this.learners > 0 ? false : true
            },
            computedHasSchoolAccount() {
                return this.computedAccount.account === 'school' ||
                    this.data.owner.account === 'school'
            }
        },
        methods: {
            ...mapActions(['dashboard/createClass','dashboard/editClass',
                'dashboard/getAccountSpecificItem']),
            closeModal() {
                this.data.owner = {name: ''}
                this.data.structure = ''
                this.clearData()
                this.clearExtraData()
                this.$emit('closeCreateClass')
            },
            initiateGetItems() {
                this.specificItemDetailsStructure = []
                this.specificItemDetailsStructureNextPage = 0
                this.getSpecificAccountItemStructure()
            },
            clearExtraData() {
                this.mainAcademicYears = []
                this.removedAcademicYears = []
                this.specificItemLoadingStructure = false
                this.learners = 0
                if (this.data.owner.account) {
                    this.selectedAcademicYears = []
                    this.specificItemDetailsStructure = []
                    this.specificItemDetailsStructureNextPage = 0
                }
            },
            setData(data) {
                this.data.name = data.name
                this.data.classId = data.id
                this.data.grades = data.grades
                this.data.state = data.state?.toLowerCase()
                this.data.description = data.description
                this.data.structure = data.structure
                if (data.maxLearners) {
                    this.data.maximum = `${data.maxLearners}`
                }
                this.hasMaxLearners = true
                this.data.items = []
                this.data.mainItems = []
                this.data.items.push(...data.items)
                this.data.mainItems.push(...data.items)
                this.selectedAcademicYears = []
                this.mainAcademicYears = []
                this.selectedAcademicYears.push(...data.academicYears)
                this.mainAcademicYears.push(...data.academicYears)
                this.data.mainPaymentData = []
                if (data.subscriptions) this.data.mainPaymentData.push(...data.subscriptions)
                if (data.prices) this.data.mainPaymentData.push(...data.prices)
                if (data.fees) this.data.mainPaymentData.push(...data.fees)
                this.data.facilitate = data.facilitators.findIndex(facilitator=>{
                    return facilitator.userId === this.getUser.id
                }) > -1
                this.mainGrade = this.data.grade = data.grades.length ? 
                    data.grades[0] : {}
                this.data.grades = data.grades
                this.buttonText = 'edit'
                this.data.owner = {
                    name: data.ownedby.name,
                    account: data.ownedby.account,
                    accountId: data.ownedby.accountId,
                }
                this.learners = data.learners
                this.checkDiscussion(data)
            },
            //academic year
            inYearSelection(data) {
                let index = this.findYearIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            yearSelected(data) {
                let index = this.findYearIndex(data)
                if (index === -1) {
                    this.selectedAcademicYears.push(data)
                }
            },
            findYearIndex(data) {
                return this.selectedAcademicYears.findIndex(cl=>{
                    return cl.id === data.id && cl.type === data.type
                })
            },
            removeYear(data) {
                let index = this.findYearIndex(data)
                if (index > -1) {
                    this.selectedAcademicYears.splice(index,1)
                }
            },
            removedYearsUpdate(data) {
                let index = this.removedAcademicYears.findIndex(year=>{
                    return data.type === year.type && data.id === year.id
                })
                if (index === -1) {
                    this.data.removedAcademicYears.push(data)
                }
            },
            //courses or subjects
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
                    this.data.items.push(data)
                }
            },
            findItemIndex(data) {
                return this.data.items.findIndex(cl=>{
                    return cl.id === data.id && cl.type === data.type
                })
            },
            removeItem(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.data.items.splice(index,1)
                }
            },
            removedItemsUpdate(data) {
                let index = this.data.removedItems.findIndex(cl=>{
                    return data.type === cl.type && data.id === cl.id
                })
                if (index === -1) {
                    this.data.removedItems.push(data)
                }
            },
            async getSpecificAccountItem(){
                if (this.specificItemDetailsNextPage === null || this.specificItemLoading) {
                    return
                }
                let response,
                    data = {
                        account: this.data.owner.account,
                        accountId: this.data.owner.accountId,
                        item: 'academicYear',
                        search: this.searchItemsText
                    }

                this.specificItemLoading = true
                response = await this['dashboard/getAccountSpecificItem']({
                    data, nextPage: this.specificItemDetailsNextPage
                })
                this.specificItemLoading = false

                if (response.status) {
                    if (!this.specificItemDetailsNextPage) {
                        this.specificItemDetails = response.items
                    } else {
                        this.specificItemDetails.push(...response.items)
                    }
                    if (response.next) {
                        this.specificItemDetailsNextPage += 1
                    } else {
                        this.specificItemDetailsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async getSpecificAccountItemStructure(){
                if (this.specificItemDetailsStructureNextPage === null ||
                    !this.data.structure) {
                    return
                }
                let response,
                    data = {
                        account: this.data.owner.account,
                        accountId: this.data.owner.accountId,
                        item: this.data.structure,
                        search: this.searchItemsText
                    }

                this.specificItemLoadingStructure = true
                response = await this['dashboard/getAccountSpecificItem']({
                    data, nextPage: this.specificItemDetailsStructureNextPage
                })
                this.specificItemLoadingStructure = false

                if (response.status) {
                    this.specificItemDetailsStructure.push(...response.items)
                    if (response.next) {
                        this.specificItemDetailsStructureNextPage += 1
                    } else {
                        this.specificItemDetailsStructureNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async clickedCreate() {
                if (this.loading) return
                let msg = ''
                if (!this.data.name.length) {
                    msg = 'class requires a name'
                } else if (!this.data.grade.id) {
                    msg = 'class requires a grade'
                } else {
                    if (this.edit) {
                        if (!this.data.state.length) {
                            msg = 'class requires a state'
                        }
                    } else {
                        if (this.computedCreator.account === 'facilitator' &&
                            this.computedPossibleOwners.length > 1 && 
                            !this.data.owner.account) {
                            msg = 'Please select the owner of this class you are creating.'
                        } else if (this.data.type !== 'free' && 
                            this.data.paymentData === null) {
                            msg = 'Please enter the required data for the payment.'                    
                        } else if (this.data.type === 'fee' && !this.selectedAcademicYears.length) {
                            msg = 'You need an academic year to use the fee payment type.'
                        } else if ((!this.data.structure || !this.data.structure.length) &&
                            (this.computedCreator.account === 'facilitator' ||
                            !this['dashboard/getAccountDetails'].classStructure)) {
                            msg = 'Please choose how you would want to structure your class.'
                        }
                    }
                }

                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return 
                }

                this.loading = true
                let response,
                    data = new FormData
                    
                data.append('name', this.data.name)
                data.append('structure', this.data.structure && this.data.structure.length ?
                    this.data.structure : this['dashboard/getAccountDetails'].classStructure
                )
                data.append('description', this.data.description)
                data.append('type', this.data.type)
                data.append('paymentData', JSON.stringify(this.data.paymentData))
                data.append('maxLearners', this.hasMaxLearners || 
                    (this.data.maximum.length && this.data.maximum !== 'null') ? this.data.maximum :
                    JSON.stringify(null)
                )
                data.append('gradeId', this.data.grade.id)
    
                // if (this.data.type === 'fee') {
                    // if (!this.edit && !this.computedAcademicYearSections) {
                    //     this.data.feeable = 'academicYear'
                    // }
                    // data.append('feeable', this.data.feeable)
                    // data.append('feeableId', this.data.feeable === 'academicYear' ?
                    //     this.computedAcademicYear.id : 
                    //     this.computedAcademicYearSections[0].id)
                    
                // }
                if (this.computedAccount.account === 'facilitator' ||
                    this.computedAccount.account === 'professional') { 
                    data.append('facilitate', JSON.stringify(this.data.facilitate))
                }
                if (this.data.owner.account === 'school' ||
                    this.computedAccount.account === 'school') {
                    data.append('academicYears',JSON.stringify(this.selectedAcademicYears.map(year=>{
                        return {
                            id: year.id,
                            type: 'academicYear',
                        }
                    })))
                }
                data.append('items', JSON.stringify(this.data.items.map(cl=>{
                    return {
                        id: cl.id,
                        type: cl.type
                    }
                })))
                if (this.schoolAdmin) { 
                    data.append('account', 'admin')
                    data.append('accountId', this.schoolAdmin.id)
                } else {
                    data.append('account', this.computedAccount.account)
                    data.append('accountId', this.computedAccount.accountId)
                }
                
                if (this.edit) {
                    data.append('classId', this.data.classId)
                    data.append('state', this.data.state)
                    data.append('main', JSON.stringify(this.computedCheckMain))
                    if (this.data.grades.length && 
                        this.data.grade.id !== this.data.grades[0].id) {                        
                        data.append('gradeId', this.data.grade.id)
                    }
                    data.append('removedPaymentData', JSON.stringify(
                        this.data.removedPaymentData.map(payment=>{
                            return {
                                type: payment.type,
                                id: payment.id
                            }
                        }
                    )))
                    if (this.data.grades.length && 
                    this.data.grade.id !== this.data.grades[0].id) {
                        data.append('removedGradeId', this.data.grades[0].id)
                    }
                    //for main attachments
                    this.data.mainItems.forEach(mainCl=>{ //check if subject or course has been removed
                        let index = this.data.items.findIndex(cl=>{
                            return cl.type === mainCl.type && cl.id === mainCl.id
                        })
                        if (index === -1) {
                            this.removedItemsUpdate(mainCl)
                        }
                        console.table(this.data.removedItems)
                    })
                    data.append('removedItems', JSON.stringify(this.data.removedItems.map(attachment=>{
                        return {
                            type: attachment.type,
                            id: attachment.id
                        }
                    })))
                    if (this.computedCreator.account === 'school') {
                        //for academic years
                        this.mainAcademicYears.forEach(mainYear=>{ //check if subject or course has been removed
                            let index = this.selectedAcademicYears.findIndex(year=>{
                                return year.type === mainYear.type && year.id === mainYear.id
                            })
                            if (index === -1) {
                                this.removedYearsUpdate(mainYear)
                            }
                        })
                        data.append('removedAcademicYears', JSON.stringify(this.removedAcademicYears.map(year=>{
                            return {
                                type: 'academicYear',
                                id: year.id
                            }
                        })))
                    }

                    response = await this['dashboard/editClass'](data)
                } else {
                    if (this.data.discussionData.title.length) {                        
                        data.append('discussionData', JSON.stringify(this.data.discussionData))
                        this.discussionFiles.forEach(file=>{
                            data.append('discussionFile[]', file)
                        })
                    }
                    if (this.computedAccount.account === 'facilitator') {                    
                        data.append('owner', this.data.owner.account ? 
                            this.data.owner.account : 
                            this.computedAccount.account)
                        data.append('ownerId', this.data.owner.account ? 
                            this.data.owner.accountId : 
                            this.computedAccount.accountId)
                    } else if (this.computedAccount.account === 'school') {                  
                        data.append('owner', this.computedAccount.account)
                        data.append('ownerId', this.computedAccount.accountId)
                    }

                    response = await this['dashboard/createClass'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.name} was successfully ${action}`
                    if (this.edit) {
                        if (this.computedCheckMain) this.$emit('classSuccessfullyEdited', response.classResource)
                    } else {
                        this.clearData()
                        // this.clearExtraData()
                    }
                    bus.$emit('updateClass',response.class)
                } else {
                    this.responseErrorAlert(response.response)
                    console.log('response :>> ', response);
                }
            },
            gradeSelected(data){
                this.data.grade = data.data
            },
            removeGrade(data){
                this.data.grade = {}
            },
        },
    }
</script>

<style lang="scss" scoped>

    .modal-wrapper{
        z-index: 10005;
    }

    .create-class-wrapper{
        position: relative;

        .loading{
            @include sticky-loader()
        }

        .section{
            @include form-section()
        }

        .class-input{
            width: 90%;
            margin: 10px auto;
            border: none;
            border-bottom: 2px solid $color-primary;
            border-radius: 0;
        }

        .search-input{
            border: none;
            border-bottom: 2px solid $color-primary;
            background: white;
        }

        .feeable{

            .main{
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                width: 100%;
            }

            .message{
                font-size: 12px;
                color: gray;
                width: 100%;
                padding: 0 5px;
            }
        }

        .other-input,
        .class-structure,
        .payment-types,
        .feeable{
            width: 90%;
            margin: 10px auto;
        }

        .attachment-heading{
            font-size: 12px;
            color: gray;
            text-align: center;
        }

        .attachments{
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            overflow-y: auto;
        }

        .attachments.danger{
            background: red;
            padding: 5px;
        }

        .class-payment{
            
            .message{
                font-size: 12px;
                color: gray;
                margin-bottom: 10px;
            }

            .v-spinner{
                text-align: center;
            }
        }

        .course-classes-section{
            min-height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;

            .class-wrapper{
                display: flex;
                width: 90%;
                margin: 10px auto;
                align-items: center;
                overflow: auto;

                .class-badge{
                    min-width: 150px;
                }
            }

            .no-data{
                font-size: 12px;
                color: gray;
            }

            .get-more{
                width: fit-content;
                margin: 10px auto;
                padding: 5px;
                background: cadetblue;
                color: white;
                font-size: 12px;
                border-radius: 10px;
                cursor: pointer;
            }

            .loading{
                text-align: center;
            }
        }
    }
</style>