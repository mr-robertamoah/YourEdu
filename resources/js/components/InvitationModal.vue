<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear="invitationModalDisappear"
                :main="false"
                :mainOther="false"
            >
                <template slot="requests">
                    <div class="invitation-modal-wrapper">
                        <div class="loading" v-if="loading">
                            <pulse-loader :loading="loading"></pulse-loader>
                        </div>
                        <div class="back-icon"
                            @click="clickedIconBack"
                            v-if="steps > 0"
                        >
                            <font-awesome-icon 
                                :icon="['fa','long-arrow-alt-left']">
                            </font-awesome-icon>
                        </div>
                        <div class="section" v-if="steps === 0">
                            <div class="description">
                                {{actionDescription}}
                            </div>
                            <main-list
                                @listItemSelected='actionSelection'
                                :multiple='false'
                                :itemList="actionsList"
                                select="actions you can perform"
                            ></main-list>
                        </div>
                        <div class="send-request"
                            v-if="steps === 1"
                            infinite-wrapper
                        >
                            <search-input
                                class="search-input"
                                @search="getSearchText"
                            ></search-input>

                            <div class="no-data"
                                v-if="!accountsLoading && !accountsNextPage && !computedAccounts.length"
                            >
                                you got no one...try different search
                            </div>
                            <div class="output-section" v-if="computedAccounts.length">
                                <other-user-account
                                    v-for="(account,index) in computedAccounts"
                                    :key="index"
                                    :account="account"
                                    :chat="false"
                                    @clickedOtherUserAccount="clickedAccount"
                                ></other-user-account>
                            </div>
                            <div class="loading" v-if="accountsLoading">
                                <pulse-loader :loading="accountsLoading" size="10px"></pulse-loader>
                            </div>

                            <infinite-loader
                                v-if="!accountsLoading && accountsNextPage && accountsNextPage > 1"
                                @infinite="infiniteHandler"
                                force-use-infinite-wrapper
                            ></infinite-loader>                            
                        </div>
                        <div class="form-section" v-if="steps === 2">
                            
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                @hideAlert="hideAlert"
                            ></auto-alert>
                            <div class="nothing" v-if="computedNothing">
                                there is nothing more to do...just send the request
                            </div>
                            <text-textarea
                                placeholder="job description" 
                                v-if="what === 'admin'" 
                                v-model="data.description"
                                :bottomBorder="true"
                                class="input"
                            ></text-textarea>

                            <main-select
                                :items="['9','8','7','6','5','4','3','2','1']"
                                :value="data.level"
                                backgroundColor="white"
                                v-if="what === 'admin'"
                                @selection="levelSelection"
                                class="main-select"
                            ></main-select>

                            <div class="radios" v-if="computedRadios">
                                <radio-input
                                    name="payment"
                                    label="free"
                                    radioValue="free"
                                    v-model="data.payment"
                                    class="radio-button"
                                ></radio-input>
                                <radio-input
                                    name="payment"
                                    label="commission"
                                    radioValue="commission"
                                    v-model="data.payment"
                                    class="radio-button"
                                ></radio-input>
                            </div>

                            <payment-types
                                v-if="paymentType.length"
                                :type="paymentType"
                                @paymentType="getPaymentType"
                            ></payment-types>

                            <div class="commission-section"
                                v-if="data.payment === 'commission'"
                            >
                                <number-input placeholder="commission"
                                    v-model="data.commission"
                                    class="input"
                                    :noBorder="true"
                                ></number-input>
                                <div class="per">%</div>
                            </div>

                            <main-checkbox
                                v-if="computedSalary"
                                label="has salary?"
                                class="input"
                                v-model="data.hasSalary"
                            ></main-checkbox>

                            <div class="salary-section" v-if="data.hasSalary">
                                <number-input placeholder="salary"
                                    v-model="data.salary"
                                    class="input"
                                    :noBorder="true"
                                    :hasMax="false"
                                ></number-input>
                                <div class="per">per</div>
                                <main-select
                                    :items="['day','week','month','quarter','year',]"
                                    :value="data.salaryPeriod"
                                    backgroundColor="white"
                                    @selection="periodSelection"
                                    class="main-select"
                                    select="select period"
                                ></main-select>
                            </div>
                            <div class="other-selections" v-if="computedOtherSelection">
                                <div class="description" v-if="computedOtherSelectionText.length">
                                    {{computedOtherSelectionText}}
                                </div>
                                <main-select
                                    v-if="computedOtherSelectionArray.length"
                                    :objects="computedOtherSelectionArray"
                                    :value="data.selection"
                                    backgroundColor="white"
                                    @selection="otherSelection"
                                    class="main-select"
                                    select="make your selection"
                                ></main-select>
                            </div>
                            <div class="attachments" v-if="data.attachments.length">
                                <attachment-badge
                                    v-for="(attachment,index) in data.attachments"
                                    :key="index"
                                    :attachment="attachment.data"
                                    :hasClose="true"
                                    @removeAttachment="removeAttachment"
                                ></attachment-badge>
                            </div>
                            <post-attachment
                                :show="true"
                                :hasSelect="true"
                                :hasClose="false"
                                v-if="computedPostAttachment"
                                @clickedAttachmentSelection="attachmentSelected"
                                class="input"
                            ></post-attachment>
                            <div class="upload-section" 
                                v-if="computedUploads">
                                <div class="note"
                                    v-if="data.files.length"
                                >these are the files to be sent with request</div>
                                <div class="files">
                                    <attachment-badge
                                        v-for="(file,index) in data.files"
                                        :key="index"
                                        :hasClose="true"
                                        :file="file"
                                        @removeAttachment="removeShownFile"
                                        @click="preview(file)"
                                    ></attachment-badge>
                                </div>
                                <div class="note-red" 
                                    v-if="showFileNote"
                                >you can only have a maximum of three files</div>
                                <div class="upload" @click="clickedUpload">
                                    <div class="icon" v-if="data.files.length < 3">
                                        <font-awesome-icon :icon="['fa','plus']"></font-awesome-icon>
                                    </div>
                                    <div class="text">
                                        {{data.files.length === 3 ?
                                            'you have reached the maximum of 3 files':
                                            `add a file to send to ${what}`}}
                                    </div>
                                </div>
                                <file-preview
                                    v-if="data.files.length"
                                    :show="showPreview"
                                    :middle="true"
                                    :showRemove="true"
                                    :file="previewFile"
                                    @removeFile="removeFile"
                                    class="file-preview-wrapper"
                                ></file-preview>
                                <input type="file" class="d-none" 
                                    @change="fileChange"
                                    ref="inputfile"
                                    v-if="what === 'admin' || what === 'school facilitation'"
                                >
                            </div>
                        </div>
                        <div class="preview-section" v-if="steps === 3">

                        </div>
                        <action-button
                            @click="clickedAction"
                            :text="actionButttonText"
                            v-if="actionButttonText.length"
                            class="action-button"
                        ></action-button>
                    </div>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import PulseLoader from 'vue-spinner/src/PulseLoader';
import MainList from './MainList';
import ActionButton from './ActionButton';
import OtherUserAccount from './chat/OtherUserAccount';
import SearchInput from './SearchInput';
import FilePreview from './FilePreview';
import NumberInput from './NumberInput';
import MainCheckbox from './MainCheckbox';
import RadioInput from './RadioInput';
import AttachmentBadge from './AttachmentBadge';
import TextTextarea from './TextTextarea';
import MainSelect from './MainSelect';
import PostAttachment from './PostAttachment';
import PaymentTypes from './PaymentTypes';
import AutoAlert from './AutoAlert';
import InfiniteLoader from 'vue-infinite-loading';
import { mapActions, mapGetters } from 'vuex';
    export default {
        components: {
            ActionButton,
            AutoAlert,
            PaymentTypes,
            PostAttachment,
            OtherUserAccount,
            InfiniteLoader,
            MainList,
            MainSelect,
            TextTextarea,
            AttachmentBadge,
            RadioInput,
            MainCheckbox,
            NumberInput,
            FilePreview,
            SearchInput,
            PulseLoader,
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
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
            admin: {
                type: Object,
                default(){
                    return null
                }
            },
            wards: {
                type: Array,
                default(){
                    return []
                }
            },
        },
        data() {
            return {
                loading: false,
                steps: 0,
                actionsList: [],
                accounts: [],
                actionDescription: '',
                action: '',
                actionButttonText: '',
                searchText: '',
                accountsParams: '',
                accountsNextPage: 1,
                accountsLoading: false,
                alertSuccess: false,
                alertDanger: false,
                alertMessage: '',
                data: {
                    title: '', 
                    level: '', 
                    files: [],
                    hasSalary: false,
                    payment: '',
                    commission: '',
                    salary: '',
                    currency: '',
                    description: '',
                    salaryPeriod: 'month',
                    account: null,
                    ward: null,
                    selection: null, //extracurriculum course class
                    attachments: [],
                },
                what: '',
                requestType: '',
                showPreview: false,
                showFileNote: false,
                previewFile: null,
                paymentType: ''
            }
        },
        watch: {
            steps (newValue,oldValue) {
                if (newValue === 0) {
                    this.actionDescription = ''
                    this.action = ''
                    this.actionButttonText = ''
                } else if (newValue === 1) {
                    this.actionButttonText = ''
                    this.searchText = ''
                } else if (newValue === 2) {
                    this.actionButttonText = 'send request'
                } else if (newValue === 3) {
                    this.actionButttonText = 'send another request'
                }
            },
            action (newValue) {
                if (newValue === 'request facilitation') {
                    this.accountsParams = `account=facilitator`
                    this.what = `school facilitation`
                    this.actionDescription = 'A request will be sent to a facilitator of your choice. this facilitator will be joined to your school,s facilitators upon accepting your request'
                } else if (newValue === 'request administration from a user') {
                    this.accountsParams = `account=user`
                    this.what = `admin`
                    this.actionDescription = "A request will be sent to this user to become part of your school's dataistrating team."
                } else if (newValue === 'request virtual admission of learner') {
                    this.accountsParams = `account=learner&type=virtual`
                    this.what = `school learning`
                    this.actionDescription = "This will help you send a request directly to learner if he/she is 18 years and above. If not, request will be sent to parents, and learner notified. Upon accepting request, learner will either be added to your school or have the opportunity to take an admission test. Note: learner will be part of your school virtually."
                } else if (newValue === 'request physical admission of learner') {
                    this.accountsParams = `account=learner&type=traditional`
                    this.what = `school learning`
                    this.actionDescription = "This will help you send a request directly to learner if he/she is 18 years and above. If not, request will be sent to parents, and learner notified. Upon accepting request, learner will either be added to your school or have the opportunity to take an admission test."
                } else if (newValue === 'become a facilitator of school') {
                    this.accountsParams = `account=school`
                    this.what = `school facilitation`
                    this.actionDescription = 'Send a request to a school to become part of its facilitating staff.'
                } else if (newValue === 'become a facilitator of class') {
                    this.accountsParams = `account=facilitator`
                    this.what = `class facilitation`
                    this.actionDescription = 'Send a request to the owner of a private class to become part of the facilitating team of the class.'
                } else if (newValue === 'request the use of your extracurriculum') {
                    this.accountsParams = `account=school&account2=classModel`
                    this.what = `extracurriculum`
                    this.actionDescription = 'This will help you send a request to a school or private class to adopt the resources of your extracurriculum.'
                } else if (newValue === 'request the use of your course') {
                    this.accountsParams = `account=school&account2=classModel`
                    this.what = `course`
                    this.actionDescription = 'This will help you send a request to a school or private class to adopt the resources of your extracurriculum.'
                } else if (newValue === 'request a collaboration') {
                    this.what = `collaboration`
                    this.accountsParams = `account=facilitator&account2=professional`
                    this.actionDescription = 'This will help you send a collaboration request to a facilitator or professional. Note: your collaboration must already be created.'
                } else if (newValue === 'request admission for learner into class') {
                    this.accountsParams = `account=learner`
                    this.what = `class learning` //send request, if payable, parent or learner will pay when request is accepted
                    this.actionDescription = "This will help you send a request directly to learner if he/she is 18 years and above. If not, request will be sent to parents, and learner notified. Upon accepting request, learner will be added to your class."
                } else if (newValue === 'request virtual admission for ward') {
                    this.accountsParams = `account=school&type=virtual`
                    this.what = `school learning`
                    this.actionDescription = 'This will help you send a reques to a school, for your ward to be admitted into a school virtually.'
                } else if (newValue === 'request physical admission for ward') {
                    this.accountsParams = `account=school&type=traditional`
                    this.what = `school learning`
                    this.actionDescription = 'This will help you send a reques to a school, for your ward to be admitted into a school.'
                } else if (newValue === 'request admission for ward into private class') {
                    this.accountsParams = `account=classModel`
                    this.what = `class learning`
                    this.actionDescription = 'This will help you send a reques to the owner of a class, for your ward to be admitted into the class.'
                } else if (newValue === 'request a nanny') {
                    this.what = `nanny`
                    this.accountsParams = `account=professional&type=nanny`
                    this.actionDescription = 'This will help you request the services of a nanny.'
                } else if (newValue === 'request home tutoring') {
                    this.accountsParams = `account=facilitator`
                    this.what = `home facilitation`
                    this.actionDescription = 'This will help you request the services of a facilitator to tutor the ward at home.'
                } else if (newValue === 'request virtual admission') {
                    this.accountsParams = `account=school`
                    this.what = `school learning`
                    this.actionDescription = 'If you are 18 years and above, this will help you send a direct request to a school, seeking a virtual admission. If not, your parents will be notified so they can request on your behalf.'
                } else if (newValue === 'request physical admission') {
                    this.accountsParams = `account=school`
                    this.what = `school learning`
                    this.actionDescription = 'If you are 18 years and above, this will help you send a direct request to a school, seeking an admission. If not, your parents will be notified so they can request on your behalf.'
                } else if (newValue === 'request admission into private class') {
                    this.accountsParams = `account=classModel`
                    this.what = `class learning`
                    this.actionDescription = 'If you are 18 years and above, this will help you send a direct request to the owner of class, seeking an admission. If not, your parents will be notified so they can request on your behalf.'
                }
                this.actionButttonText = 'ok'
            },
            account: {
                immediate: true,
                handler(newValue){
                    if (newValue.account === 'school') {
                        this.actionsList = [
                            'request facilitation',
                            'request administration from a user',
                            'request virtual admission of learner',
                            'request physical admission of learner',
                        ]
                    } else if (newValue.account === 'facilitator') {
                        this.actionsList = [
                            'become a facilitator of school',
                            'become a facilitator of class',
                            'request the use of your extracurriculum',
                            'request the use of your course',
                            'request a collaboration',
                            'request admission for learner into class',
                        ]
                    } else if (newValue.account === 'professional') {
                        this.actionsList = [
                            'request the use of your extracurriculum',
                            'request the use of your course',
                            'request a collaboration',
                        ]
                    } else if (newValue.account === 'parent') {
                        this.actionsList = [
                            'request virtual admission for ward',
                            'request physical admission for ward',
                            'request admission for ward into private class',
                            'request a nanny',
                            'request home tutoring',
                        ]
                    } else if (newValue.account === 'learner') {
                        this.actionsList = [
                            'request virtual admission',
                            'request physical admission',
                            'request admission into private class',
                        ]
                    }
                }
            },
            type: {
                immediate: true,
                handler(newValue){
                    if (newValue.length) {
                        if (newValue === 'add admin') {
                            this.steps = 1
                            this.accountsParams = `account=user`
                            this.what = `admin`
                            this.actionDescription = "A request will be sent to this user to become part of your school's dataistrating team."
                        }
                    }
                }
            },
            searchText(newValue){
                if (newValue.length) {
                    this.accountsNextPage = 1
                    this.debouncedSearchAccounts()
                } else {
                    this.accounts = []
                }
            },
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails']),
            computedAccounts() {
                return this.accounts.map(account=>{
                    return {
                        account: account.account_type,
                        accountId: account.account_id,
                        name: account.profile_name ? account.profile_name : account.name,
                        url: account.profile_url,
                        username: account.username,
                    }
                }) 
            },
            computedPostAttachment(){
                return this.what === 'school learning' || this.what === 'school facilitation' ||
                    this.what === 'class facilitation'
            },
            computedNothing(){
                return false
            },
            computedRadios(){
                return this.computedCommission
            },
            computedSalary(){
                return this.what === 'admin' || this.what === 'school facilitation'
            },
            computedCommission(){
                return this.what === 'collaboration' || this.what === 'class facilitation'
            },
            computedWards(){
                return this.account.account === 'parent'? true : false
            },
            computedUploads(){
                return this.what === 'admin' || this.what === 'school facilitation' ||
                    this.what === 'class learning' || this.what === 'class facilitation'
            },
            computedClasses(){
                return this.account.account === 'facilitator' && 
                    this["dashboard/getAccountDetails"].classes ? 
                    this["dashboard/getAccountDetails"].classes : []
            },
            computedExtracurriculums(){
                return (this.account.account === 'facilitator' || 
                    this.account.account === 'professional') && 
                    this["dashboard/getAccountDetails"].extracurriculums ? 
                    this["dashboard/getAccountDetails"].extracurriculums : []
            },
            computedCourses(){
                return (this.account.account === 'facilitator' || 
                    this.account.account === 'professional') && 
                    this["dashboard/getAccountDetails"].courses ? 
                    this["dashboard/getAccountDetails"].courses : []
            },
            computedCollaborations(){
                return (this.account.account === 'facilitator' || 
                    this.account.account === 'professional') && 
                    this["dashboard/getAccountDetails"].collaborations ? 
                    this["dashboard/getAccountDetails"].collaborations : []
            },
            computedOtherSelection(){
                return (this.account.account === 'facilitator' || 
                    this.account.account === 'professional') && [
                        `collaboration`,`course`,`extracurriculum`,'class learning'
                    ].includes(this.what)
            },
            computedOtherSelectionText(){
                return this.computedOtherSelection && this.computedOtherSelectionArray ?
                    `make a selection from these` :
                    this.computedOtherSelection && !this.computedOtherSelectionArray ? 
                    `you need to have a ${this.what === 'class learning' ? 'class' : this.what} before you can complete this. Create one in your dashboard.`
                    : ''
            },
            computedOtherSelectionArray(){
                return this.what === 'collaboration' ? this.computedCollaborations : 
                    this.what === 'course' ? this.computedCourses : 
                    this.what === 'extracurriculum' ? this.computedExtracurriculums : 
                    this.what === 'class learning' ? this.computedClasses : []
            },
        },
        methods: {
            ...mapActions(['dashboard/searchAccounts','dashboard/sendRequest']),
            invitationModalDisappear() {
                this.$emit('invitationDisappear')
            },
            getPaymentType(data){

            },
            levelSelection(data){
                this.data.level = data
            },
            periodSelection(data){
                this.data.salaryPeriod = data
            },
            otherSelection(data){
                this.data.selection = data
            },
            actionSelection(data){
                this.action = data
            },
            clickedAction(data){
                if (this.actionButttonText === 'send request') {
                    this.sendRequest()
                    return
                } else if (this.actionButttonText === 'send another request') {
                    this.clearData()
                    return
                }
                this.steps += 1
            },
            hideAlert(){
                this.alertSuccess = false
                this.alertDanger = false
                this.alertMessage = ''
            },
            clearData(){
                this.actionButttonText = ''
                this.steps = 0
                this.data = {
                    title: '', 
                    level: '', 
                    files: [],
                    hasSalary: false,
                    salary: '',
                    currency: '',
                    commission: '',
                    description: '',
                    payment: '',
                    salaryPeriod: 'month',
                    account: null,
                    ward: null,
                    selection: null,
                }
                this.what = ''
                this.accounts = []
            },
            attachmentSelected(data){
                this.data.attachments.push(data)
            },
            removeAttachment(data){
                let index = this.data.attachments.findIndex(attachment=>{
                    return attachment.data.name === data.data.name && 
                        attachment.data.description === data.data.description && 
                        attachment.data.id === data.data.id
                })
                if (index > -1) {
                    this.data.attachments.splice(index,1)
                }
            },
            removeFile(data){
                this.showPreview = false
                this.previewFile = null
            },
            removeShownFile(data){
                this.showPreview = false
                let file = data.data ? data.data : data,
                index = this.data.files.findIndex(f=>{
                    return file.name === f.name && file.size === f.size
                })
                if (index > -1) {
                    this.data.files.splice(index,1)
                }
            },
            clickedAccount(account){
                this.data.account = account
                this.steps += 1
            },
            clickedUpload(){
                if (this.data.files.length === 3) {
                    this.showFileNote = true
                } else {
                    this.$refs.inputfile.click()
                }
            },
            fileChange(){
                this.data.files.push(this.$refs.inputfile.files[0])
                this.$refs.inputfile.value = ''
            },
            sliceAttachmentType(type){
                return type.slice(0,type.length - 1)
            },
            async sendRequest(){
                let msg = ''

                let response,
                    formData = new FormData

                if (this.data.hasSalary && this.data.salary.trim() === '') {
                    message = 'Please enter salary'
                } else if (this.data.payment === 'commission' && 
                    this.data.commission.trim() === '') {
                    message = 'Please enter commission'
                } 
                
                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return
                }
                formData.append('title',this.data.title)
                if (this.data.hasSalary) {                            
                    formData.append('salary',this.data.salary)
                    formData.append('salaryPeriod',this.data.salaryPeriod)
                    formData.append('currency',this.data.currency)
                }
                formData.append('level',this.data.level)
                formData.append('description',this.data.description.trim())

                if (this.data.files.length) {  
                    for (let i = 0; i < this.data.files.length; i++) {
                        formData.append('files[]',this.data.files[i]);                        
                    }
                }

                formData.append('from',this.account.account)
                formData.append('fromId',this.account.accountId)
                if (this.account.account === 'school' && this.admin) {
                    formData.append('adminId',this.admin.id)
                }                
                formData.append('what',this.what)
                if (this.data.account) {
                    formData.append('to',this.data.account.account)
                    formData.append('toId',this.data.account.accountId)
                }
                if (this.data.ward) {
                    formData.append('item','learner')
                    formData.append('itemId',this.data.ward.accountId)
                }
                if (this.data.payment === 'commission') {
                    formData.append('commission',this.data.commission)
                }
                if (this.data.selection) {
                    if (this.what.includes('extracurriculum')) {
                        formData.append('item','extracurriculum')
                    } else if (this.what.includes('class')) {
                        formData.append('item','class')
                    } else if (this.what.includes('course')) {
                        formData.append('item','course')
                    } else if (this.what.includes('collaboration')) {
                        formData.append('item','collaboration')
                    }                    
                    formData.append('itemId',this.data.selection.id)
                }
                if (this.data.attachments.length) {
                    formData.append('attachments',
                        JSON.stringify(this.data.attachments.map(attachment=>{
                            return {
                                'item': this.sliceAttachmentType(attachment.type),
                                'itemId': attachment.data.id
                            }
                        }))
                    )
                }

                response = await this['dashboard/sendRequest'](formData)

                if (response.status) {
                    this.steps += 1
                } else {
                    console.log('response :>> ', response);
                }

            },
            clickedIconBack(){
                if (this.type.length) {
                    this.invitationModalDisappear()                  
                } else this.steps -= 1
            },
            getSearchText(data){
                this.searchText = data
            },
            debouncedSearchAccounts: _.debounce(function(){
                this.getAccounts()
            },300),
            async getAccounts(){                
                this.accounts = await this.searchAccounts()
            },
            async searchAccounts(){
                if (!this.accountsNextPage) {
                    return 
                }
                let response,
                    data = {
                        nextPage: this.accountsNextPage,
                        params: `${this.accountsParams}&search=${this.searchText}`
                    }

                this.accountsLoading = true
                response = await this['dashboard/searchAccounts'](data)
                this.accountsLoading = false

                if (response.status) {
                    if (response.next) {
                        this.accountsNextPage += 1
                    } else {
                        this.accountsNextPage = null
                    }
                    return response.accounts
                } else {
                    console.log('response :>> ', response);
                    return []
                }
            },
            async infiniteHandler($state){

                this.accounts.push(...await this.searchAccounts())

                if (this.accountsNextPage) {
                    $state.loaded()
                } else {
                    $state.complete()
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
$background-color-main: rgb(22,233,205);

@mixin text-description(){
    font-size: 14px;
    color: gray;
    font-style: italic;
}

    .invitation-modal-wrapper{
        position: relative;

        .loading{
            width: 100%;
            text-align: center;
        }

        .back-icon{
            position: relative;
            width: 100%;
            text-align: end;
            padding: 5px;
            cursor: pointer;
        }

        .section{

            .description{
                @include text-description();
            }
        }

        .action-button{
            margin: 10px auto 0;
        }

        .send-request{

            .output-section{
                margin-top: 20px;
                padding: 10px;
                border-top: 1px solid gray;
                border-radius: 0;

            }

            .no-data{
                color: gray;
                font-size: 14px;
                width: 100%;
                text-align: center;
            }
        }

        .form-section{
            position: relative;
            
            .nothing{
                text-align: center;
                @include text-description();
            }

            .input,
            .text-input,
            .nothing,
            .main-select{
                width: 90%;
                margin: 10px auto;
            }

            .input{
                padding: 0;
            }

            .text-input{
                border: none;
                border-bottom: 2px solid $background-color-main;
                border-radius: 0;
            }

            .attachments{
                display: flex;
                align-items: center;
                width: 100%;
                flex-wrap: wrap;
            }

            .upload-section{
                margin: 10px auto;
                width: 90%;
                cursor: pointer;

                .upload{
                    display: flex;
                    width: 100%;
                    border: none;

                    .icon{
                        color: $background-color-main;
                        margin-right: 10px;
                    }

                    .text{
                        font-size: 14px;
                        color: gray;
                    }
                }

                .note{
                    font-size: 14px;
                    color: gray;
                }

                .note-red{
                    font-size: 14px;
                    color: red;
                }
            }

            .main-select{
                width: 90%;
                margin: 10px auto;

                .selected-section{
                    background: white;
                }
            }

            .radios{
                display: flex;
                width: 100%;
                margin-bottom: 10px;
            }

            .salary-section,
            .commission-section{
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 90%;
                margin: 10px auto;

                .per{
                    margin: 0 10px;
                }
            }

            .commission-section{
                
                .input{
                    max-width: 100px;
                }
            }
        }
    }
</style>