<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear="invitationModalDisappear"
                :main="false"
                :mainOther="false"
                :requestsAlone="true"
                class="invitation-modal-wrapper"
            >
                <template slot="requests">
                    <auto-alert
                        :message="alertMessage"
                        :success="alertSuccess"
                        :danger="alertDanger"
                        :sticky="true"
                        @hideAlert="clearAlert"
                        @doneShowingSuccessMessage="afterAlertSuccess"
                    ></auto-alert>
                    <pulse-loader 
                        v-if="loading"
                        class="loading" 
                        :loading="loading"
                    ></pulse-loader>
                    
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
                    <div class="send-request search-section"
                        v-if="steps === 1 && computedShowReceiversSection"
                        infinite-wrapper
                    >
                        <search-input
                            class="search-input"
                            @search="getReceiverSearchText"
                            v-if="receiverTypes.length"
                            :placeholder="`search for ${receiverTypes.toString()}`"
                        ></search-input>
                        <div class="buttons" v-if="receiverTypes.length > 1">
                            <grey-button
                                class="grey-button"
                                v-for="(type, index) in receiverTypes"
                                :key="index"
                                :text="type"
                                :active="type === receiverSearchType"
                                @clickedAction="clickedReceiverType"
                            ></grey-button>
                        </div>
                        <div class="no-data"
                            v-if="!accountsLoading && !accountsNextPage && !computedAccounts.length"
                        >
                            you got no one...try a different search
                        </div>
                        <div class="output-section" v-if="computedAccounts.length">
                            <other-user-account
                                class="account"
                                v-for="(account,index) in computedAccounts"
                                :key="index"
                                :account="account"
                                :active="inAccountsSelection(account)"
                                :chat="false"
                                @clickedOtherUserAccount="clickedAccount"
                            ></other-user-account>
                        </div>
                        <pulse-loader
                            v-if="accountsLoading"
                            class="loading" 
                            :loading="accountsLoading" 
                            size="10px"
                        ></pulse-loader>

                        <infinite-loader
                            v-if="!accountsLoading && accountsNextPage && accountsNextPage > 1"
                            @infinite="infiniteHandler"
                            force-use-infinite-wrapper
                        ></infinite-loader>
                        
                        <div 
                            v-if="computedWards.length"
                        >
                            <search-input
                                class="search-input"
                                @search="getWardSearchText"
                                :placeholder="`search for wards`"
                                v-if="account.account === 'parent'"
                            ></search-input>
                            <div class="small-msg">
                                select one of your wards for whom you are sending this request
                            </div>
                            <div class="output-section">
                                <other-user-account
                                    class="account"
                                    v-for="(ward,index) in computedWards"
                                    :key="index"
                                    :account="ward"
                                    :active="inWardsSelection(ward)"
                                    :chat="false"
                                    @clickedOtherUserAccount="clickedWard"
                                ></other-user-account>
                            </div>
                        </div>                    
                    </div>
                    <div class="form-section" 
                        v-if="steps === 1"
                    >
                        <template v-if="computedAdministration">
                            <text-input
                                :bottomBorder="true"
                                placeholder="administrator name*"
                                v-model="administrationData.name"
                                class="other-input"
                            ></text-input>
                            <text-input
                                :bottomBorder="true"
                                placeholder="administrator title"
                                v-model="administrationData.title"
                                class="other-input"
                            ></text-input>
                            <text-textarea
                                placeholder="job description" 
                                v-model="administrationData.description"
                                :bottomBorder="true"
                                class="input"
                            ></text-textarea>
                            <main-select
                                :items="['9','8','7','6','5','4','3','2','1']"
                                :value="administrationData.level"
                                backgroundColor="white"
                                @selection="levelSelection"
                                class="main-select"
                            ></main-select>
                        </template>
                        
                        <main-select
                            :items="['traditional', 'virtual']"
                            :value="schoolType"
                            backgroundColor="white"
                            @selection="schoolTypeSelection"
                            class="main-select"
                            v-if="computedSchoolType"
                        ></main-select>

                        <div class="small-msg text-left">
                            {{computedPaymentType}}:
                        </div>
                        <payment-types
                            class="payment-types"
                            v-if="computedPaymentType.length"
                            :type="paymentType"
                            :showRadios="false"
                            @paymentType="getPaymentType"
                            @paymentTypeError="setErrorAlert"
                            :radioValue="computedPaymentType"
                        ></payment-types>

                        <div class="other-selections search-section" 
                            v-if="computedHasItemSelection"
                            infinite-wrapper
                        >                                
                            <search-input
                                class="search-input"
                                @search="getSelectableItemsSearchText"
                                :placeholder="`search for ${itemTypes.toString()}`"
                            ></search-input>
                            <div class="buttons" v-if="itemTypes.length">
                                <grey-button
                                    class="grey-button"
                                    v-for="(type, index) in itemTypes"
                                    :key="index"
                                    :text="type"
                                    :active="type === selectableItemsSearchType"
                                    @clickedAction="clickedItemType"
                                ></grey-button>
                            </div>
                            
                            <pulse-loader 
                                v-if="selectableItemsLoading"
                                class="loading" 
                                :loading="selectableItemsLoading"
                                size="10px"
                            ></pulse-loader>
                            <div class="description small-msg" v-if="computedItemSelectionText.length">
                                {{computedItemSelectionText}}
                            </div>

                            <div class="items-wrapper">
                                <item-badge
                                    v-for="(item,index) in computedItemSelectionArray"
                                    :key="index"
                                    :item="item"
                                    type="class"
                                    :hasRemove="inItemsSelection(item)"
                                    class="badge"
                                    @clickedItem="itemSelected"
                                    @clickedRemoveItem="removeItem"
                                ></item-badge>
                            </div>

                            <infinite-loader
                                v-if="!selectableItemsLoading && selectableItemsNextPage && selectableItemsNextPage > 1"
                                @infinite="itemsInfiniteHandler"
                                force-use-infinite-wrapper
                            ></infinite-loader>
                        </div>
                        <div class="attachments" v-if="attachments.length">
                            <attachment-badge
                                v-for="(attachment,index) in attachments"
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
                            :buttons="computedPostAttachmentButtons"
                            v-if="computedPostAttachment"
                            @clickedAttachmentSelection="attachmentSelected"
                            class="input"
                        ></post-attachment>
                        <div class="upload-section" v-if="computedFiles">
                            <div class="note"
                                v-if="files.length"
                            >these are the files to be sent with request</div>
                            <div class="files">
                                <attachment-badge
                                    v-for="(file,index) in files"
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
                                <div class="icon" v-if="files.length < 3">
                                    <font-awesome-icon :icon="['fa','plus']"></font-awesome-icon>
                                </div>
                                <div class="text">
                                    {{files.length === 3 ?
                                        'you have reached the maximum of 3 files':
                                        `add a file to send with request`}}
                                </div>
                            </div>
                            <file-preview
                                v-if="files.length"
                                :show="showPreview"
                                :middle="true"
                                :showRemove="true"
                                :file="previewFile"
                                @removeFile="removeFile"
                                class="file-preview-wrapper"
                            ></file-preview>
                        </div>
                    </div>
                    <div class="preview-section" v-if="steps === 2">
                        <div class="small-msg" v-if="selectedAccounts.length">
                            the request will be sent to the following:
                        </div>
                        <div class="" v-if="selectedAccounts.length">
                            <other-user-account
                                class="account"
                                v-for="(account,index) in selectedAccounts"
                                :key="index"
                                :account="account"
                                :chat="false"
                            ></other-user-account>
                        </div>
                        <div class="small-msg" v-if="selectedItems.length">
                            the request regards the following:
                        </div>
                        <div 
                            class="output-section"
                            v-if="selectedItems.length"
                        >
                            <item-badge
                                class="item"
                                v-for="(item,index) in selectedItems"
                                :key="index"
                                :item="item"
                            ></item-badge>
                        </div>
                        <div class="small-msg">
                            nothing more to do here 🙂. if you are ready, send the request...
                        </div>
                    </div>
                    <action-button
                        @click="clickedAction"
                        :text="actionButttonText"
                        v-if="actionButttonText.length"
                        class="action-button"
                    ></action-button>

                    <input type="file" class="hidden" 
                        @change="fileChange"
                        ref="inputfile"
                        v-if="computedFiles"
                    >
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
import TextInput from './TextInput';
import MainSelect from './MainSelect';
import PostAttachment from './PostAttachment';
import PaymentTypes from './PaymentTypes';
import GreyButton from './GreyButton';
import ItemBadge from './dashboard/ItemBadge';
import InfiniteLoader from 'vue-infinite-loading';
import { mapActions, mapGetters } from 'vuex';
import Alert from '../mixins/Alert.mixin';
    export default {
        components: {
            ActionButton,
            ItemBadge,
            GreyButton,
            PaymentTypes,
            PostAttachment,
            OtherUserAccount,
            InfiniteLoader,
            MainList,
            MainSelect,
            TextInput,
            TextTextarea,
            AttachmentBadge,
            RadioInput,
            MainCheckbox,
            NumberInput,
            FilePreview,
            SearchInput,
            PulseLoader,
        },
        mixins: [Alert],
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
                accountsNextPage: 1,
                accountsLoading: false,
                wardId: null,
                wardsNextPage: 1,
                wardsLoading: false,
                wardsSearchText: '',
                administrationData: {
                    title: '', 
                    name: '', 
                    level: '', 
                    description: '',
                },
                schoolType: null,
                gradeId: null,
                selectedAccounts: [],
                attachments: [],
                files: [],
                selectedItems: [],
                selectableItemsLoading: false,
                selectableItemsNextPage: 1,
                selectableItemsSearchText: '',
                selectableItemsSearchType: '',
                itemTypes: [],
                receiverTypes: [],
                receiversSearchText: '',
                receiverSearchType: '',
                detailTypes: [],
                showPreview: false,
                showFileNote: false,
                previewFile: null,
                paymentType: '', 
                paymentData: null,
            }
        },
        watch: {
            steps (newValue,oldValue) {
                if (newValue === 0) {
                    this.actionDescription = ''
                    this.action = ''
                    this.actionButttonText = ''
                } else if (newValue === 1) {
                    this.actionButttonText = 'next'
                    this.receiversSearchText = ''
                } else if (newValue === 2) {
                    this.actionButttonText = 'send request'
                } else if (newValue === 3) {
                    this.actionButttonText = 'send another request'
                }

                if (newValue === 1 && oldValue === 0) {
                    this.initiateReceiverSearch()
                }
            },
            action (newValue) {

            },
            account: {
                immediate: true,
                handler(newValue){
                    this.actionsList = Number(this.getUser.age) >= 18 && 
                        newValue.account ==! 'school' ?
                        this.computedAccountPossibleRequestTypes[newValue.account].push('school administration request') :
                        this.computedAccountPossibleRequestTypes[newValue.account]
                }
            },
            type: {
                immediate: true,
                handler(newValue){
                    if (newValue.length) {
                        if (newValue === 'add admin') {
                            this.setAdministrationRequestForm()
                            this.steps = 1
                        }
                    }
                }
            },
            wardsSearchText(newValue){
                this.initiateWardsSearch()
            },
            receiversSearchText(newValue){
                this.initiateReceiverSearch()
            },
            selectableItemsSearchText(newValue){
                this.initiateItemSearch()
            },
            receiverSearchType(newValue){
                this.initiateReceiverSearch()
            },
            selectableItemsSearchType(newValue){
                this.initiateItemSearch()
            },
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails', 'getUser']),
            computedAccounts() {
                return this.accounts
            },
            computedPostAttachment(){
                return this.detailTypes.includes('attachments')
            },
            computedPostAttachmentButtons() {
                let buttons = []

                if (! this.computedPostAttachment) {
                    return []
                }

                if (this.detailTypes.includes('attachment grades')) {
                    buttons.push('grades')
                }

                if (this.detailTypes.includes('attachment subjects')) {
                    buttons.push('subjects')
                }

                if (this.detailTypes.includes('attachment courses')) {
                    buttons.push('courses')
                }

                if (this.detailTypes.includes('attachment programs')) {
                    buttons.push('programs')
                }

                return buttons
            },
            computedSalary(){
                return this.detailTypes.includes('salary')
            },
            computedCommission(){
                return this.detailTypes.includes('commission')
            },
            computedHasWards(){
                return this.account?.account === 'parent'
            },
            computedWards(){
                return this.wards.filter(ward=>{
                    return ward.name.includes(this.wardsSearchText)
                })
            },
            computedFiles(){
                return this.detailTypes.includes('files')
            },
            computedClasses(){
                if (!(this.account.account === 'facilitator' ||
                    this.account.account === 'school')) {
                    return []
                }
                
                let data = []
                if (this["dashboard/getAccountDetails"].classes) {
                    data.push(...this["dashboard/getAccountDetails"].classes)
                }
                
                if (this['dashboard/getAccountDetails'].ownedClasses) {
                    data.push(...this['dashboard/getAccountDetails'].ownedClasses)
                }

                return data
            },
            computedExtracurriculums(){
                if (!this.computedItemOwningAccountType) {
                    return []
                } 
                let data = []

                if (this['dashboard/getAccountDetails'].extracurriculums) {
                    data.push(...this['dashboard/getAccountDetails'].extracurriculums)
                }

                if (this['dashboard/getAccountDetails'].ownedExtracurriculums) {
                    data.push(...this['dashboard/getAccountDetails'].ownedExtracurriculums)
                }

                return data
            },
            computedCourses(){
                if (!this.computedItemOwningAccountType) {
                    return []
                } 
                let data = []

                if (this['dashboard/getAccountDetails'].courses) {
                    data.push(...this['dashboard/getAccountDetails'].courses)
                }

                if (this['dashboard/getAccountDetails'].ownedCourses) {
                    data.push(...this['dashboard/getAccountDetails'].ownedCourses)
                }

                return data
            },
            computedCollaborations(){
                if (!this.computedItemOwningAccountType) {
                    return []
                } 
                let data = []

                if (this['dashboard/getAccountDetails'].collaborations) {
                    data.push(...this['dashboard/getAccountDetails'].collaborations)
                }

                return data
            },
            computedPrograms(){
                if (!this.computedItemOwningAccountType) {
                    return []
                } 
                let data = []

                if (this['dashboard/getAccountDetails'].programs) {
                    data.push(...this['dashboard/getAccountDetails'].programs)
                }

                if (this['dashboard/getAccountDetails'].ownedPrograms) {
                    data.push(...this['dashboard/getAccountDetails'].ownedPrograms)
                }

                return data
            },
            computedItemOwningAccountType() {
                return this.account.account === 'facilitator' || 
                    this.account.account === 'professional' || 
                    this.account.account === 'school'
            },
            computedHasItemSelection(){
                return this.itemTypes.length
            },
            computedItemSelectionText(){
                return this.computedHasItemSelection && this.computedItemSelectionArray.length ?
                    `make a selection from these` :
                    this.computedHasItemSelection && !this.computedItemSelectionArray.length ? 
                    `sorry, no ${this.itemTypes.toString()} to select from. try another search.`
                    : ''
            },
            computedItemSelectionArray(){
                let data = []

                if (this.selectableItemsLoading) {
                    return []
                }
                
                if (this.computedSearchItem && this.selectedItems.length) {
                    data.push(...this.selectedItems)
                }

                if (this.itemTypes.includes('collaborations')) {
                    data.push(...this.mappedWithType(
                        this.computedCollaborations, 'collaboration'
                    ))
                }
                    
                if (this.itemTypes.includes('classes') && 
                    this.selectableItemsSearchType === 'classes') {
                    data.push(...this.mappedWithType(
                        this.computedClasses, 'class'
                    ))
                }
                
                if (this.itemTypes.includes('extracurriculums') && 
                    this.selectableItemsSearchType === 'extracurriculums') {
                    data.push(...this.mappedWithType(
                        this.computedExtracurriculums, 'extracurriculum'
                    ))
                }
                
                if (this.itemTypes.includes('programs') && 
                    this.selectableItemsSearchType === 'programs') {
                    data.push(...this.mappedWithType(
                        this.computedPrograms, 'program'
                    ))
                }
                
                if (this.itemTypes.includes('courses') && 
                    this.selectableItemsSearchType === 'courses') {
                    data.push(...this.mappedWithType(
                        this.computedCourses, 'course'
                    ))
                }

                return data
            },
            computedSearchItem() {
                return this.detailTypes.includes('search items')
            },
            computedPayment() {
                return this.detailTypes.includes('payment')
            },
            computedAccountPossibleRequestTypes() {
                return {
                    school: [
                        'learning request',
                        'admission request',
                        'facilitation request',
                        'collaboration request',
                        'administration request',
                    ],
                    facilitator: [
                        'facilitation request',
                        'collaboration request',
                        'school administration request'
                    ],
                    professional: [
                        'facilitation request',
                        'collaboration request',
                        'school administration request'
                    ],
                    learner: [
                        'learning request',
                        'admission request',
                    ],
                    parent: [
                        'learning request',
                        'admission request',
                    ],
                }
            },
            computedPaymentType() {
                return this.detailTypes.includes('commission') ? 'commission' :
                    this.detailTypes.includes('salary') ? 'salary' :
                    this.detailTypes.includes('discount') ? 'discount' : ''
            },
            computedShowReceiversSection() {
                return this.receiverTypes.length || this.wards.length
            },
            computedAdministration() {
                return this.action.includes('administration')
            },
            computedAdmission() {
                return this.action.includes('admission')
            },
            computedSchoolType() {
                if (! this.detailTypes.includes('school type')) {
                    return false
                }

                if (this.account.account !== 'school') {
                    return false
                }

                if (this.account.role === 'virtual') {
                    this.schoolType = 'virtual'
                    return false
                }

                return true
            },
        },
        methods: {
            ...mapActions(['dashboard/searchAccounts',
                'dashboard/searchItems','dashboard/sendRequest'
            ]),
            mappedWithType(items, itemType) {
                return items.map(item=>{
                    item.type = itemType
                    return item
                })
            },
            error(data) {
                this.alertDanger = true
                this.alertLengthy = data.lengthy
                this.alertMessage = data.message
            },
            initiateReceiverSearch() {
                this.debouncedSearchAccounts()
            },
            initiateWardsSearch() {
                this.debouncedSearchWards()
            },
            initiateItemSearch() {
                this.debouncedSearchSelectableItems()
            },
            invitationModalDisappear() {
                this.$emit('invitationDisappear')
            },
            clickedReceiverType(data) {
                if (!data.length) {
                    this.receiverSearchType = this.receiverTypes[0]
                    return
                }

                this.receiverSearchType = data
            },
            clickedItemType(data) {
                if (!data.length) {
                    this.selectableItemsSearchType = this.itemTypes[0]
                    return
                }
                
                this.selectableItemsSearchType = data
            },
            getPaymentType(data){
                this.paymentType = data.type
                this.paymentData = data.data
            },
            getWardSearchText(text) {
                this.wardsSearchText = text
            }, 
            levelSelection(data){
                this.administrationData.level = data
            },
            schoolTypeSelection(data){
                this.schoolType = data
            },
            periodSelection(data){
                this.data.salaryPeriod = data
            },
            otherSelection(data){
                this.data.selection = data
            },
            clearRequestFormData() {
                this.receiverTypes = []
                this.detailTypes = []
                this.itemTypes = []
                this.selectedItems = []
                this.selectedAccounts = []
                this.actionDescription = ''
                this.action = ''
                this.actionButttonText = ''
            },
            actionSelection(data){
                this.clearRequestFormData()

                if (!data.length) {
                    return
                }

                if (data === 'learning request') {
                    this.setLearningRequestForm()
                }
                
                if (data === 'facilitation request') {
                    this.setFacilitationRequestForm()
                }
                
                if (data === 'collaboration request') {
                    this.setCollaborationRequestForm()
                }
                
                if (data === 'admission request') {
                    this.setAdmissionRequestForm()
                }
                
                if (data === 'administration request' ||
                    data === 'school administration request') {
                    this.setAdministrationRequestForm()
                }

                this.actionButttonText = 'next'
            },
            setLearningRequestForm() {
                this.action = 'learning'
                this.itemTypes = ['classes', 'courses', 'programs', 'extracurriculums']
                
                if (this.account.account === 'learner') {
                    this.actionDescription = `sending a request to the owner of a class, course, etc in order to have access to it. You can use this avenue if you want a discount.`
                    this.detailTypes.push(...['search items'])
                }
                
                if (this.account.account === 'parent') {
                    this.actionDescription = `sending a request to the owner of a class, course, etc in order for some of your wards have access to it. You can use this avenue if you want a discount.`
                    this.detailTypes.push('search items')
                }

                if (!this.actionDescription.length) {
                    this.receiverTypes = ['learners']
                    this.actionDescription = `sending a request to learners (and indirectly to parents of learners) for them to notice/buy/access your classes, courses, etc. You can use this avenue if you want to give a discount.`
                }

                this.detailTypes.push(...['discount', 'files', ])
            },
            setFacilitationRequestForm() {
                this.action = 'facilitation'
                this.itemTypes = ['classes', 'courses', 'programs', 'extracurriculums']
                this.detailTypes.push('search items')
                
                if (this.account.account === 'school') {
                    this.actionDescription = `sending a request to a facilitator so he/she can facilitate your class, course, school, etc You can use this avenue to specify salary/commission.`
                    this.receiverTypes = ['facilitators']
                }
                
                if (this.account.account === 'facilitator') {
                    this.actionDescription = `sending a request to the owner of a class, course, etc in order to become a facilitator for it. You can use this avenue to ask for a salary/commission.`
                }
                
                this.detailTypes.push(...['payment', 'salary', 'commission', 'files'])
            },
            setCollaborationRequestForm() {
                this.action = 'collaboration'
                this.itemTypes = ['collaborations']
                this.receiverTypes = ['facilitators', 'professionals']
                this.actionDescription = `sending a request to facilitators or professionals in order for them to collaborate with you by joining your collaboration. You can use this avenue to specify commissions as well.`
                this.detailTypes = ['payment', 'commission', 'search items', 'search']
            },
            setAdmissionRequestForm() {
                this.action = 'admission'
                this.detailTypes = ['attachments', 'attachment grades', 'attachment single', 'school type']

                if (this.account.account === 'school') {
                    this.actionDescription = `sending a request to a learner (and indirectly to parents) so they can be enrolled in the school (virtually/traditionally).`
                    this.receiverTypes = ['learners']
                    this.itemTypes = ['assessments']
                    this.detailTypes.push(...['search', 'search items'])
                }
                
                if (this.account.account === 'learner') {
                    this.actionDescription = `sending a request to the administrators of a school so you can be enrolled (virtually/traditionally).`
                    this.receiverTypes = ['schools']
                    this.detailTypes.push('search')
                }
                
                if (this.account.account === 'parent') {
                    this.actionDescription = `sending a request to the administrators of a school so you can be enrolled (virtually/traditionally).`
                    this.receiverTypes = ['schools']
                    this.detailTypes.push(['search', 'search items'])
                }
                
                this.detailTypes.push(...['payment', 'price', 'files'])
            },
            setAdministrationRequestForm() {
                this.action = 'administration'
                if (this.account.account === 'school') {
                    this.receiverTypes = ['users']
                    this.actionDescription = 'send a request to a user to become an administrator of your school. You can use this avenue to specify salary/commission'
                }
                
                if (this.account.account !== 'school') {
                    this.receiverTypes = ['schools']
                    this.actionDescription = 'send a request to schools in order to become an administrator of the school. You can use this avenue to specify salary/commission'
                }
                    
                this.detailTypes = ['payment', 'salary', 'commission', 'search']
            },
            clickedAction(data){
                if (this.loading) {
                    return
                }

                if (this.actionButttonText === 'send request') {
                    this.sendRequest()
                    return
                } else if (this.actionButttonText === 'send another request') {
                    this.clearData()
                    return
                }

                if (this.steps === 1) {
                    
                    let message = this.validateStepOne()

                    if (message.length) {
                        this.setErrorAlert({message})
                        return
                    }
                }
                this.steps += 1
            },
            validateStepOne() {
                if (this.receiverTypes.length && !this.selectedAccounts.length) {
                    return `please you need to select ${this.receiverTypes.toString()}`
                }

                if (this.computedHasWards && !this.wardId) {
                    return `please you need to select one of your wards`
                }

                if (this.itemTypes.toString() === 'assessments') {
                    return ''
                }

                if (this.itemTypes.length && !this.selectedItems.length) {
                    return `please you need to select ${this.itemTypes.toString()}`
                }

                return ''
            },
            clearData(){
                this.actionButttonText = ''
                this.steps = 0
                this.adminstrationData = {
                    title: '', 
                    name: '', 
                    level: '', 
                    description: '',
                }
                this.paymentType = ''
                this.paymentData = null
                this.schoolType = null
                this.gradeId = null
                this.accounts = []
                this.attachments = []
                this.files = []
                this.showPreview = false,
                this.showFileNote = false,
                this.previewFile = null,
                this.clearRequestFormData()
            },
            attachmentSelected(data){
                let index = this.findAttachmentIndex(data)
                if (index > -1) {
                    return
                }

                if (this.detailTypes.includes('attachment grades')) {
                    this.gradeId = data.data.id
                }

                if (this.detailTypes.includes('attachment single')) {
                    this.attachments = [data]
                    return
                }

                this.attachments.push(data)
            },
            findAttachmentIndex(data) {
                return this.attachments.findIndex(attachment=>{
                    return attachment.data.name === data.data.name && 
                        attachment.data.description === data.data.description && 
                        attachment.data.id === data.data.id
                })
            },
            removeAttachment(data){
                let index = this.findAttachmentIndex(data)

                if (index === -1) {
                    return
                }

                if (! this.detailTypes.includes('attachment single')) {
                    this.attachments.splice(index,1)
                    return
                }
                
                if (this.detailTypes.includes('attachment grades')) {
                    this.gradeId = null
                }

                this.attachments = []
            },
            removeFile(data){
                this.showPreview = false
                this.previewFile = null
            },
            removeShownFile(data){
                this.showPreview = false
                let file = data.data ? data.data : data,
                index = this.files.findIndex(f=>{
                    return file.name === f.name && file.size === f.size
                })
                if (index > -1) {
                    this.files.splice(index,1)
                }
            },
            clickedAccount(account){
                this.accountSelected(account)
            },
            inWardsSelection(data) {
                return this.findWardIndex(data)
            },
            clickedWard(data) {
                if (this.findWardIndex(data)) {
                    this.wardId = null
                    return
                }

                this.wardId = data.accountId
            },
            findWardIndex(data) {
                return this.wardId === data.accountId
            },
            clickedUpload(){
                if (this.files.length === 3) {
                    this.showFileNote = true
                } else {
                    this.$refs.inputfile.click()
                }
            },
            fileChange(){
                this.files.push(this.$refs.inputfile.files[0])
                this.$refs.inputfile.value = ''
            },
            sliceAttachmentType(type){
                return type.slice(0,type.length - 1)
            },
            getFailedValidationMessage() {

                if (this.computedPayment && 
                    this.paymentData === null) {
                    return 'Please enter the required data for the payment.'                    
                }

                return ''
            },
            async sendRequest(){
                let msg = this.getFailedValidationMessage()
                
                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return
                }

                this.loading = true
                let response,
                    formData = new FormData

                formData.append('action', this.action)

                formData.append('level',this.data.level)
                formData.append('description',this.data.description.trim())

                for (let i = 0; i < this.files.length; i++) {
                    formData.append('files[]',this.files[i]);
                }

                formData.append('account',this.account.account)
                formData.append('accountId',this.account.accountId)

                if (this.account.account === 'school' && this.admin) {
                    formData.append('adminId',this.admin.id)
                }  
                
                formData.append('receivers', JSON.stringify(this.selectedAccounts.map(account=>{
                    return {
                        account: account.account,
                        accountId: account.accountId,
                    }
                })))

                if (this.computedAdmission) {
                    formData.append('admissionData', JSON.stringify({
                        type: this.schoolType,
                        gradeId: this.gradeId
                    }))
                }

                if (this.computedAdministration) {
                    formData.append('administrationData', JSON.stringify(this.administrationData))
                }

                formData.append('wardId',this.wardId)
                
                formData.append('paymentData', JSON.stringify(this.paymentData)) 
                formData.append('paymentType', JSON.stringify(this.paymentType)) 
                
                formData.append('items', JSON.stringify(this.selectedItems.map(item=>{
                    return {
                        item: item.type,
                        itemId: item.id,
                    }
                })))

                if (this.attachments.length) {
                    formData.append('attachments',
                        JSON.stringify(this.attachments.map(attachment=>{
                            return {
                                'item': this.sliceAttachmentType(attachment.type),
                                'itemId': attachment.data.id
                            }
                        }))
                    )
                }

                response = await this['dashboard/sendRequest'](formData)

                this.loading = false

                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = `request has been sent successfully 😎`
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = `oops! it failed 😕. please try again later.`
                }

            },
            afterAlertSuccess() {
                this.steps = 0
                this.clearData()
            },
            clickedIconBack(){
                if (this.type.length) {
                    this.invitationModalDisappear()
                    return              
                }
                
                if (this.steps === 1) {
                    this.clearRequestFormData()    
                }

                this.steps -= 1
            },
            getReceiverSearchText(data){
                this.receiversSearchText = data
            },
            getSelectableItemsSearchText(data){
                this.selectableItemsSearchText = data
            },
            debouncedSearchAccounts: _.debounce(function(){
                this.accounts = []
                this.accountsNextPage = 1
                this.getAccounts()
            },300),
            async getAccounts(){                
                this.accounts = await this.searchAccounts()
            },
            async searchAccounts(){
                if (!this.accountsNextPage || !this.receiverTypes.length) {
                    return 
                }

                let response,
                    data = {
                        nextPage: this.accountsNextPage,
                        data: {
                            type: this.receiverSearchType.length ? this.receiverSearchType :
                                this.receiverTypes[0],
                            search: this.receiversSearchText,
                            others: JSON.stringify(true)
                        }
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
            debouncedSearchWards: _.debounce(function(){
                this.wards = []
                this.wardsNextPage = 1
                this.getWards()
            },300),
            async getWards(){                
                this.wards = await this.searchWards()
            },
            async searchWards(){
                if (!this.wardsNextPage) {
                    return 
                }

                let response,
                    data = {
                        nextPage: this.wardsNextPage,
                        data: {
                            accountId: this.account.accountId,
                            search: this.wardsSearchText,
                        }
                    }

                this.wardsLoading = true
                response = await this['dashboard/searchWards'](data)
                this.wardsLoading = false

                if (response.status) {
                    if (response.next) {
                        this.wardsNextPage += 1
                    } else {
                        this.wardsNextPage = null
                    }
                    return response.wards
                } else {
                    console.log('response :>> ', response);
                    return []
                }
            },
            async wardsInfiniteHandler($state){

                this.wards.push(...await this.searchWards())

                if (this.wardsNextPage) {
                    $state.loaded()
                } else {
                    $state.complete()
                }
            },
            debouncedSearchSelectableItems: _.debounce(function(){
                this.selectedItems = []
                this.selectableItemsNextPage = 1
                this.getSelectableItems()
            },300),
            async getSelectableItems(){                
                this.selectedItems = await this.searchSelectableItems()
            },
            async searchSelectableItems(){
                if (!this.selectableItemsNextPage) {
                    return 
                }
                let response,
                    data = {
                        nextPage: this.selectableItemsNextPage,
                        data: {
                            type: this.selectableItemsSearchType.length ? this.selectableItemsSearchType :
                                this.itemTypes[0],
                            search: this.selectableItemsSearchText,
                            others: JSON.stringify(true)
                        }
                    }

                this.selectableItemsLoading = true
                response = await this['dashboard/searchItems'](data)
                this.selectableItemsLoading = false

                if (response.status) {
                    if (response.next) {
                        this.selectableItemsNextPage += 1
                    } else {
                        this.selectableItemsNextPage = null
                    }
                    return response.items
                } else {
                    console.log('response :>> ', response);
                    return []
                }
            },
            async itemsInfiniteHandler($state){

                this.selectedItems.push(...await this.searchSelectableItems())

                if (this.selectableItemsNextPage) {
                    $state.loaded()
                } else {
                    $state.complete()
                }
            },
            inItemsSelection(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            itemSelected(data) {
                let index = this.findItemIndex(data)
                if (index === -1) {
                    this.selectedItems.push(data)
                }
            },
            findItemIndex(data) {
                return this.selectedItems.findIndex(item=>{
                    return item.id === data.id &&
                        item.type === data.type
                })
            },
            removeItem(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.selectedItems.splice(index,1)
                }
            },
            inAccountsSelection(data) {
                let index = this.findAccountIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            accountSelected(data) {
                let index = this.findAccountIndex(data)
                if (index === -1) {
                    this.selectedAccounts.push(data)
                } else {
                    this.selectedAccounts.splice(index, 1)
                }
            },
            findAccountIndex(data) {
                return this.selectedAccounts.findIndex(account=>{
                    return account.account === data.account &&
                        account.accountId === data.accountId
                })
            },
            removeAccount(data) {
                let index = this.findAccountIndex(data)
                if (index > -1) {
                    this.selectedAccounts.splice(index,1)
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin text-description(){
    font-size: 14px;
    color: gray;
    font-style: italic;
}

    .invitation-modal-wrapper{

        .loading{
            @include sticky-loader();
            top: 49%;
        }

        .back-icon{
            position: relative;
            width: 100%;
            text-align: end;
            padding: 5px;
            cursor: pointer;
        }

        .search-section{
            min-height: 200px;
        }

        .buttons{
            margin: 10px;
            padding: 10px;
            width: 100%;
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            overflow-x: auto;

            .grey-button{
                margin: 0 5px
            }
        }

        .section{

            .description{
                @include text-description();
                text-align: center;
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
                padding: 10px;
                overflow-y: auto;
                max-height: 200px;
                margin-bottom: 10px;

                .account{
                    margin: 5px 0;
                }
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
            
            .payment-types{
                margin: 10px auto;
            }

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
                border-bottom: 2px solid $color-primary;
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
                        color: $color-primary;
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

            .items-wrapper{
                display: flex;
                width: 90%;
                margin: 10px auto;
                align-items: center;
                overflow: auto;

                .badge{
                    min-width: 150px;
                    min-height: 65px;
                }
            }
        }

        .preview-section{

            .output-section{
                display: flex;
                width: 100%;
                flex-wrap: nowrap;
                align-items: center;
                overflow-x: auto;

                .item{
                    min-width: 150px;
                }
            }
        }
    }
</style>