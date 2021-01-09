<template>
    <div>
        <just-fade>
            <template slot="transition" v-if="show">
                <main-modal
                    :show="show"
                    :mainOther="false"
                    :requests="false"
                    @mainModalDisappear='closeModal'
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
                                    :sticky="true"
                                    @hideAlert="clearAlert"
                                ></auto-alert>
                                <div class="loading" v-if="loading">
                                    <pulse-loader :loading="loading"></pulse-loader>
                                </div>
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
                                    v-if="!edit && computedAccount.account === 'facilitator'"
                                >
                                    <div class="message">
                                        How do you want your class structured?
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

                                <div class="attachments" v-if="data.grade.id">
                                    <attachment-badge
                                        :attachment="data.grade"
                                        :hasClose="true"
                                        @removeAttachment="removeGrade"
                                    ></attachment-badge>
                                </div>
                                <post-attachment
                                    :show="true"
                                    :hasSelect="true"
                                    mainSearchItem="grades"
                                    :hasClose="false"
                                    @clickedAttachmentSelection="gradeSelected"
                                    class="class-input"
                                ></post-attachment>
                                <main-select
                                    class="other-input"
                                    v-if="computedPossibleOwners.length > 1"
                                    placeholder="select owner of this class"
                                    backgroundColor='white'
                                    :objects="computedPossibleOwners"
                                    :value="data.owner.name"
                                    @selection="ownerSelection"
                                ></main-select>
                                <div class="class-payment" 
                                    v-if="computedAccount.account === 'school' ||
                                        data.owner.account === 'school'"
                                >
                                    <pulse-loader
                                        :loading="specificItemLoading"
                                        size="10px"
                                    ></pulse-loader>

                                    <div class="message">
                                        {{computedMessage}}
                                    </div>
                                    <academic-year-section-badge
                                        :hasClose="false"
                                        :section="computedAcademicYearSections[0]"
                                        v-if="!edit && computedAcademicYearSections"
                                    ></academic-year-section-badge>
                                </div>

                                <payment-types
                                    v-if="computedPayment && !edit"
                                    @paymentType="getPaymentType"
                                    :type="paymentType"
                                    :radioValue="data.type"
                                    class="payment-types"
                                ></payment-types>

                                <main-select
                                    v-if="edit"
                                    :items="['pending','accepted','declined','suspended']"
                                    :value="data.state"
                                    backgroundColor="white"
                                    @selection="classStateSelection"
                                    class="other-input"
                                    placeholder="change state of class"
                                ></main-select>
                                <div class="feeable" 
                                    v-if="data.type === 'fee'"
                                >
                                    <div class="message">
                                        fee should be assigned to?
                                    </div>
                                    <div class="main">
                                        <radio-input
                                            name="academicYear"
                                            label="current academic year"
                                            radioValue="academicYear"
                                            v-model="data.feeable"
                                            class="radio-button"
                                        ></radio-input>
                                        <radio-input
                                            name="academicYear"
                                            label="current academic year section"
                                            radioValue="academicYearSection"
                                            v-model="data.feeable"
                                            class="radio-button"
                                        ></radio-input>
                                    </div>
                                </div>

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
                                <main-checkbox
                                    v-model="data.discussion"
                                    label="automatically add a discussion?"
                                    class="class-input"
                                ></main-checkbox>
                                <!-- discussion preview -->
                                <div class="discussion-preview"
                                    v-if="data.discussionData.title"
                                >
                                    {{data.discussionData}}
                                    <item-badge
                                        type="discussion"
                                        :item="data.discussionData"
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
import AcademicYearSectionBadge from '../dashboard/AcademicYearSectionBadge';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import { mapActions, mapGetters } from 'vuex';
import { dates } from '../../services/helpers';
import {bus} from '../../app';
import DashboardCreateForm from '../../mixins/DashboardCreateForm.mixin';
    export default {
        components: {
            PulseLoader,
            AcademicYearSectionBadge,
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
            }
        },
        watch: {
            data: {
                deep: true,
                handler(newValue,oldValue){

                }
            },
            'data.owner': {
                handler(newValue) {
                    if (newValue && newValue.account === 'school') {
                        this.specificItemDetails = []
                        this.specificItemDetailsNextPage = 0
                        this.specificItem = 'academicYear'
                        this.getSpecificAccountItem()
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
                return this.computedAccount.account !== 'school' || 
                    this.computedAcademicYearSections ? '' :
                    !this.computedAcademicYearSections.length ? 
                        'You cannot use fee payment for this class because you do not have a current academic year section. If you desire to have a fee section, then please create a new academic year with a current section or add a new section to an existing academic year.' :
                    this.computedAcademicYearSections.length ? 
                        'You can use the fee section because you have a current academic year section' : ''
            },
            computedAcademicYearSections(){
                if (!this.computedAcademicYear) {
                    return null
                }
                let now = dates.toDate(new Date())
                return this.computedAcademicYear.sections.filter(section=>{
                        return dates.toDate(new Date(section.startDate)) < now && 
                            dates.toDate(new Date(section.endDate)) > now
                    })
            },
            computedAcademicYear(){
                let academicYears = [],
                    index,
                    now = dates.toDate(new Date())
                if (this.computedAccount.account !== 'school') {
                    academicYears = this.specificItemDetails
                } else {
                    academicYears = this["dashboard/getAccountDetails"].academicYears
                }
                index = academicYears.findIndex(year=>{
                    return dates.toDate(new Date(year.startDate)) < now && 
                        dates.toDate(new Date(year.endDate)) > now
                })
                if (index > -1) {
                    return academicYears[index]
                }
                return null
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
        },
        methods: {
            ...mapActions(['dashboard/createClass','dashboard/editClass',
                'dashboard/getAccountSpecificItem']),
            closeModal() {
                this.clearData()
                this.$emit('closeCreateClass')
            },
            getDiscussionData(data) {
                this.data.discussionData.title = data.title
                this.data.discussionData.preamble = data.preamble
                this.data.discussionData.type = data.type
                this.data.discussionData.restricted = data.restricted
                this.data.discussionData.allowed = data.allowed
                this.discussionFiles = data.files
            },
            closeDiscussionModal() {
                this.data.discussion = false
            },
            setData(data) {
                this.data.name = data.name
                this.data.classId = data.id
                this.data.grades = data.grades
                this.data.state = data.state.toLowerCase()
                this.data.description = data.description
                if (data.maxLearners) {
                    this.data.maximum = `${data.maxLearners}`
                }
                this.hasMaxLearners = true
                this.data.grade = data.grades.length ? 
                    data.grades[0] : {}
                this.buttonText = 'edit'
            },
            clearAlert(){
                this.alertMessage = ''
                this.alertDanger = false
                this.alertSuccess = false
            },
            async getSpecificAccountItem(){
                if (this.specificItemDetailsNextPage === null) {
                    return
                }
                let response,
                    data = {
                        account: this.data.owner.account,
                        accountId: this.data.owner.accountId,
                        item: this.specificItem
                    }

                this.specificItemLoading = true
                response = await this['dashboard/getAccountSpecificItem']({
                    data, nextPage: this.specificItemDetailsNextPage
                })
                this.specificItemLoading = false

                if (response.status) {
                    this.specificItemDetails.push(...response.items)
                    if (response.next) {
                        this.specificItemDetailsNextPage += 1
                    } else {
                        this.specificItemDetailsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            classStateSelection(data){
                this.data.state = data
            },
            ownerSelection(data){
                this.data.owner = data
                this.data.paymentData = null
                this.data.type = 'free'
                // this.paymentType = ''
            },
            getPaymentType(data){
                this.data.type = data.type
                this.data.paymentData = data.data
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
                        } else if (this.data.type === 'fee' && 
                            !this.data.feeable.length) {
                            msg = 'Please select between academic year and academic year section to which to assign the fee.'
                        } else if (!this.data.structure.length) {
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
                    data.append('description', this.data.description)
                
                if (this.edit) {
                    if (this.computedAdmin) { 
                        data.append('account', 'admin')
                        data.append('accountId', this.computedAdmin.id)
                    } else {
                        data.append('account', this.computedAccount.account)
                        data.append('accountId', this.computedAccount.accountId)
                    }

                    if (this.computedAccount.account === 'facilitator' ||
                        this.computedAccount.account === 'professional') { 
                        data.append('facilitate', JSON.stringify(this.data.facilitate))
                    }
                    data.append('classId', this.data.classId)
                    data.append('state', this.data.state)
                    if (this.data.grades.length && 
                        this.data.grade.id !== this.data.grades[0].id) {                        
                        data.append('gradeId', this.data.grade.id)
                    }
                    data.append('maxLearners', this.data.maximum.length && this.data.maximum !== 'null' ?
                             this.data.maximum :
                            JSON.stringify(null))

                    response = await this['dashboard/editClass'](data)
                } else {
                    if (this.data.discussionData.title) {                        
                        data.append('discussionData', JSON.stringify(this.data.discussionData))
                        this.discussionFiles.forEach(file=>{
                            data.append('discussionFile[]', file)
                        })
                    }
                    data.append('gradeId', this.data.grade.id)
                    data.append('type', this.data.type)
                    data.append('paymentData', JSON.stringify(this.data.paymentData))
                    data.append('maxLearners', this.hasMaxLearners ? this.data.maximum :
                            JSON.stringify(null))
                    if (this.computedAccount.account === 'facilitator') {                    
                        data.append('owner', this.data.owner.account ? 
                            this.data.owner.account : 
                            this.computedAccount.account)
                        data.append('ownerId', this.data.owner.account ? 
                            this.data.owner.accountId : 
                            this.computedAccount.accountId)
                        data.append('account', this.computedAccount.account)
                        data.append('accountId', this.computedAccount.accountId)
                        data.append('facilitate', JSON.stringify(this.data.facilitate))
                    } else if (this.computedAccount.account === 'school') {                  
                        data.append('owner', this.computedAccount.account)
                        data.append('ownerId', this.computedAccount.accountId)
                        if (this.computedAccount.owner) {                        
                            data.append('account', this.computedAccount.account)
                            data.append('accountId', this.computedAccount.accountId)
                        } else if (this.computedAdmin) {
                            data.append('account', 'admin')
                            data.append('accountId', this.computedAdmin.id)
                        }
    
                        if (this.data.type === 'fee') {
                            data.append('feeable', this.data.feeable)
                            data.append('feeableId', data.feeable === 'academicYear' ?
                                this.computedAcademicYear.id : 
                                this.computedAcademicYearSections[0].id)
                        }
                    }

                    response = await this['dashboard/createClass'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.name} was successfully ${action}`
                    if (this.edit) {
                        this.$emit('classSuccessfullyEdited', response.classResource)
                    }
                    this.clearData()
                } else {
                    let action = this.edit ? 'editing' : 'creation'
                    this.alertDanger = true
                    this.alertMessage = `class ${action} was unsuccessful`
                    console.log('response :>> ', response);
                }
            },
            clearData(){
                this.data.name = ''
                this.data.feeable = ''
                this.data.feeableId = ''
                this.data.type = 'free'
                this.data.paymentData = null
                this.data.description = ''
                this.data.grade = {}
                this.data.owner = {name: ''}
                this.data.subjects = []
                this.data.maximum = ''
                this.data.facilitate = false
                this.data.discussionData = {
                        title: '',
                        type: '',
                        preamble: '',
                        allowed: '',
                        restricted: false,
                    }
                this.discussionFiles = []
                this.data.discussion = false
                this.hasMaxLearners = false
            },
            subjectSelected(data){
                this.data.subjects.push(data.data)
            },
            gradeSelected(data){
                this.data.grade = data.data
            },
            removeSubject(data){
                let index = this.data.subjects.findIndex(subject=>{
                    return subject.id === data.id
                })
                if (index > -1) {
                    this.data.subjects.splice(index,1)
                }
            },
            removeGrade(data){
                this.data.grade = {}
            },
        },
    }
</script>

<style lang="scss" scoped>

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
            border-bottom: 2px solid $background-color-main;
            border-radius: 0;
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
    }
</style>