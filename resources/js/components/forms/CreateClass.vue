<template>
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
                                v-model="classData.name"
                                class="other-input"
                            ></text-input>
                            <text-textarea
                                :bottomBorder="true"
                                placeholder="description of the class"
                                v-model="classData.description"
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
                                        v-model="classData.structure"
                                        class="radio-button"
                                    ></radio-input>
                                    <radio-input
                                        name="classStructure"
                                        label="class only has courses"
                                        radioValue="courses"
                                        v-model="classData.structure"
                                        class="radio-button"
                                    ></radio-input>
                                </div>
                            </div>

                            <div class="attachments" v-if="classData.grade.id">
                                <attachment-badge
                                    :attachment="classData.grade"
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
                                select="select owner of this class"
                                backgroundColor='white'
                                :objects="computedPossibleOwners"
                                :selectedItem="classData.owner.name"
                                @selection="ownerSelection"
                            ></main-select>
                            <div class="class-payment" 
                                v-if="computedAccount.account === 'school'"
                            >
                                <div class="message">
                                    {{computedMessage}}
                                </div>
                                <academic-year-section-badge
                                    :hasClose="false"
                                    :section="computedAcademicYearSections[0]"
                                    v-if="!edit"
                                ></academic-year-section-badge>
                            </div>

                            <payment-types
                                v-if="computedPayment && !edit"
                                @paymentType="getPaymentType"
                                :type="paymentType"
                                :radioValue="classData.type"
                            ></payment-types>

                            <main-select
                                v-if="edit"
                                :items="['pending','accepted','declined','suspended']"
                                :value="classData.state"
                                backgroundColor="white"
                                @selection="classStateSelection"
                                class="other-input"
                                placeholder="change state of class"
                            ></main-select>
                            <div class="feeable" 
                                v-if="classData.type === 'fee' && !edit"
                            >
                                <div class="message">
                                    fee should be assigned to?
                                </div>
                                <div class="main">
                                    <radio-input
                                        name="academicYear"
                                        label="current academic year"
                                        radioValue="academicYear"
                                        v-model="classData.feeable"
                                        class="radio-button"
                                    ></radio-input>
                                    <radio-input
                                        name="academicYear"
                                        label="current academic year section"
                                        radioValue="academicYearSection"
                                        v-model="classData.feeable"
                                        class="radio-button"
                                    ></radio-input>
                                </div>
                            </div>

                            <main-checkbox
                                v-if="computedCreator.account === 'facilitator'"
                                v-model="classData.facilitate"
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
                                v-model="classData.maximum"
                                class="other-input"
                            ></number-input>
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
import AcademicYearSectionBadge from '../dashboard/AcademicYearSectionBadge';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import { mapActions, mapGetters } from 'vuex';
import { dates } from '../../services/helpers';
    export default {
        components: {
            PulseLoader,
            AcademicYearSectionBadge,
            PaymentTypes,
            AutoAlert,
            PostAttachment,
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
            show: {
                type: Boolean,
                default: true
            },
            edit: {
                type: Boolean,
                default: false
            },
            editableClass: {
                type: Object,
                default(){
                    return null
                }
            },
            schoolAdmin: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        data() {
            return {
                classData: {
                    name: '',
                    feeable: '',
                    feeableId: '',
                    type: 'free',
                    description: '',
                    grade: {},
                    owner: {name: ''},
                    subjects: [],
                    maximum: '',
                    structure: '',
                    state: '',
                    state: '',
                    facilitate: false,
                    paymentData: null
                },
                hasMaxLearners: false,
                loading: false,
                title: 'create a class',
                buttonText: 'create',
                alertMessage: '',
                alertDanger: false,
                alertSuccess: false,
                paymentType: ''
            }
        },
        watch: {
            edit: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.classData.name = this.editableClass.name
                        this.classData.state = this.editableClass.state.toLowerCase()
                        this.classData.description = this.editableClass.description
                        if (this.editableClass.maxLearners) {
                            this.classData.maximum = `${this.editableClass.maxLearners}`
                        }
                        this.hasMaxLearners = true
                        this.classData.grade = this.editableClass.grades.length ? 
                            this.editableClass.grades[0] : {}
                        this.buttonText = 'edit'
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','dashboard/getCurrentAccount',
                'getUser']),
            computedPossibleOwners() {
                if (this.computedAccount.account === 'facilitator') {
                    let a = []
                    a.push(this.computedCreator)
                    a.push(...this['dashboard/getAccountDetails'].schools)
                    return a
                }
                return []
            },
            computedAccount(){
                return this["dashboard/getCurrentAccount"]
            },
            computedCreator() {
                return {
                    name: this['dashboard/getAccountDetails'].name,
                    account: this.computedAccount.account,
                    accountId: this.computedAccount.accountId,
                }
            },
            computedAdmin(){
                if (this.computedAccount.account !== 'school') {
                    return null
                }
                let index = this["dashboard/getAccountDetails"].admins.findIndex(admin=>{
                    return admin.userId === this.getUser.id
                })
                if (index > -1) {
                    return this["dashboard/getAccountDetails"].admins[index]
                } else {
                    return null
                }
            },
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
                if (this.computedAccount.account !== 'school') {
                    return null
                }
                let now = dates.toDate(new Date())
                let index = this["dashboard/getAccountDetails"].academicYears.findIndex(year=>{
                    return dates.toDate(new Date(year.startDate)) < now && 
                        dates.toDate(new Date(year.endDate)) > now
                })
                if (index > -1) {
                    return this["dashboard/getAccountDetails"].academicYears[index]
                }
                return []
            },
            computedPayment(){
                if (this.computedAccount.account === 'school') {
                    this.paymentType = 'fee and one-time'
                    return true
                } else {
                    if (this.classData.owner === 'school') {
                        this.paymentType = 'fee and one-time'
                    } else if (this.classData.owner === 'facilitator') {
                        this.paymentType = 'subscription and one-time'
                    } 
                }
                return false
            },
        },
        methods: {
            ...mapActions(['dashboard/createClass','dashboard/editClass']),
            closeModal() {
                this.clearData()
                this.$emit('closeCreateClass')
            },
            clearAlert(){
                this.alertMessage = ''
                this.alertDanger = false
                this.alertSuccess = false
            },
            classStateSelection(data){
                this.classData.state = data
            },
            ownerSelection(data){
                this.classData.owner = data
            },
            getPaymentType(data){
                this.classData.type = data.type
                this.classData.paymentData = data.data
            },
            async clickedCreate() {
                let msg = ''
                if (!this.classData.name.length) {
                    msg = 'class requires a name'
                } else if (!this.classData.grade.id) {
                    msg = 'class requires a grade'
                }
                if (this.edit) {
                    if (!this.classData.state.length) {
                        msg = 'class requires a state'
                    }
                } else {
                    if (this.computedCreator.account === 'school') {
                        
                    } else if (this.computedCreator.account === 'facilitator') {
                        if (!this.computedPossibleOwners.length > 1 && 
                            !this.classData.owner.account) {
                            msg = 'Please select the owner of this class you are creating.'
                        }
                    } else if (this.classData.type !== 'free' && 
                        !this.classData.paymentData) {
                        msg = 'Please enter the required data for the payment.'                    
                    } else if (this.classData.type === 'fee' && 
                        !this.classData.feeable.length) {
                        msg = 'Please select between academic year and academic year section to which to assign the fee.'
                    } else if (!this.classData.structure.length) {
                        msg = 'Please choose how you would want to structure your class.'
                    }
                }

                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return 
                }

                this.loading = true
                let response,
                    data = {
                        name: this.classData.name,
                        description: this.classData.description,
                    }

                if (this.schoolAdmin) {
                    data.adminId = this.schoolAdmin.id
                }
                
                if (this.edit) {
                    data.classId = this.editableClass.id
                    data.state = this.classData.state
                    if (this.editableClass.grades.length && 
                        this.classData.grade.id !== this.editableClass.grades[0].id) {                        
                        data.gradeId = this.classData.grade.id 
                    }
                    data.maxLearners = this.classData.maximum.length && this.classData.maximum !== 'null' ?
                             this.classData.maximum :
                            JSON.stringify(null)

                    response = await this['dashboard/editClass'](data)
                } else {
                    data.gradeId = this.classData.grade.id
                    data.type = this.classData.type
                    data.paymentData = JSON.stringify(this.classData.paymentData)
                    data.maxLearners = this.hasMaxLearners ? this.classData.maximum :
                            JSON.stringify(null)
                    if (this.computedAccount.account === 'facilitator') {                    
                        data.owner = this.classData.owner.account ? 
                            this.classData.owner.account : 
                            this.computedAccount.account
                        data.ownerId = this.classData.owner.account ? 
                            this.classData.owner.accountId : 
                            this.computedAccount.accountId
                        data.account = this.computedAccount.account
                        data.accountId = this.computedAccount.accountId
                        data.facilitate = JSON.stringify(this.classData.facilitate)
                    } else if (this.computedAccount.account === 'school') {                  
                        data.owner = this.computedAccount.account
                        data.ownerId = this.computedAccount.accountId
                        if (this.computedAccount.owner) {                        
                            data.account = this.computedAccount.account
                            data.accountId = this.computedAccount.accountId
                        } else if (this.computedAdmin) {
                            data.account = 'admin'
                            data.accountId = this.computedAdmin.id
                        }
    
                        if (this.classData.type === 'fee') {
                            data.feeable = this.classData.feeable
                            data.feeableId = data.feeable === 'academicYear' ?
                                this.computedAcademicYear.id : 
                                this.computedAcademicYearSections[0].id
                        }
                    }

                    response = await this['dashboard/createClass'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.classData.name} was successfully ${action}`
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
                this.classData.name = ''
                this.classData.feeable = ''
                this.classData.feeableId = ''
                this.classData.type = 'free'
                this.classData.paymentData = null
                this.classData.description = ''
                this.classData.grade = {}
                this.classData.owner = {name: ''}
                this.classData.subjects = []
                this.classData.maximum = ''
                this.classData.facilitate = false
                this.hasMaxLearners = false
            },
            subjectSelected(data){
                this.classData.subjects.push(data.data)
            },
            gradeSelected(data){
                this.classData.grade = data.data
            },
            removeSubject(data){
                let index = this.classData.subjects.findIndex(subject=>{
                    return subject.id === data.id
                })
                if (index > -1) {
                    this.classData.subjects.splice(index,1)
                }
            },
            removeGrade(data){
                this.classData.grade = {}
            },
        },
    }
</script>

<style lang="scss" scoped>
$background-color-main: rgb(22,233,205);

    .create-class-wrapper{

        .loading{
            position: sticky;
            width: 100%;
            text-align: center;
            top: 10px;
        }

        .class-input{
            width: 90%;
            margin: 10px auto;
            border: none;
            border-bottom: 2px solid $background-color-main;
            border-radius: 0;
        }

        .feeable,
        .class-structure{

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

        .other-input{
            width: 90%;
            margin: 10px auto;
        }

        .class-payment{
            
            .message{
                font-size: 12px;
                color: gray;
                margin-bottom: 10px;
            }
        }
    }
</style>