<template>
    <just-fade>
        <template slot="transition" v-if="show"> 
            <main-modal :show='show' 
                @mainModalDisappear='modalDisappear'
                :requests="false"
                :mainOther="false"
            >
                <template slot="main">
                    <welcome-form :title="title" 
                        class="welcome-form"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="hideAlert"
                            ></auto-alert>
                            <div class='loading'>
                                <sync-loader :loading="modalLoading"></sync-loader>
                            </div>
                            <template v-if="showInputSection">
                                <div class="back-icon account"
                                    @click="clickedAccountIconBack"
                                    v-if="account.length"
                                >
                                    <font-awesome-icon 
                                        :icon="['fa','long-arrow-alt-left']">
                                    </font-awesome-icon>
                                </div>

                                <template v-if="type.length || (steps === 2 || steps === 5)">
                                    <input type="text" class="form-control form-input" 
                                    placeholder="name*" 
                                    v-model="inputName">

                                    <input placeholder="title"
                                        v-model="admin.title"
                                        v-if="steps === 2 && creating === 'admin'"
                                        class="form-control form-input"
                                    >

                                    <div class="class-structure" 
                                        v-if="account === 'school'"
                                    >
                                        <div class="message">
                                            How do you want your class structured?
                                        </div>
                                        <div class="main">
                                            <radio-input
                                                name="classStructure"
                                                label="school only has subjects"
                                                radioValue="subjects"
                                                v-model="classStructure"
                                                class="radio-button"
                                            ></radio-input>
                                            <radio-input
                                                name="classStructure"
                                                label="school only has courses"
                                                radioValue="courses"
                                                v-model="classStructure"
                                                class="radio-button"
                                            ></radio-input>
                                            <radio-input
                                                name="classStructure"
                                                label="school has programs with subjects"
                                                radioValue="programs and subjects"
                                                v-model="classStructure"
                                                class="radio-button"
                                            ></radio-input>
                                            <radio-input
                                                name="classStructure"
                                                label="school has programs with course"
                                                radioValue="programs and course"
                                                v-model="classStructure"
                                                class="radio-button"
                                            ></radio-input>
                                        </div>
                                    </div>

                                    <textarea class="form-control form-input" 
                                        :placeholder="creating === 'admin' ? 
                                            'job description' :`description`" 
                                        v-if="description || creating === 'admin'" 
                                        v-model="inputDescription"></textarea>

                                    <main-select
                                        :items="['9','8','7','6','5','4','3','2','1']"
                                        :value="admin.level"
                                        backgroundColor="white"
                                        v-if="creating === 'admin'"
                                        @selection="levelSelection"
                                        class="main-select"
                                    ></main-select>

                                    <main-checkbox
                                        v-if="creating === 'admin' || creating === 'facilitator'"
                                        label="has salary?"
                                        class="text-input"
                                        v-model="admin.hasSalary"
                                    ></main-checkbox>

                                    <div class="salary-section" v-if="admin.hasSalary">
                                        <number-input placeholder="salary"
                                            v-model="admin.salary"
                                            class="text-input"
                                            :noBorder="true"
                                            :hasMax="false"
                                        ></number-input>
                                        <div class="per">per</div>
                                        <main-select
                                            :items="['day','week','month','quarter','year',]"
                                            :value="admin.salaryPeriod"
                                            backgroundColor="white"
                                            @selection="periodSelection"
                                            class="main-select"
                                            select="select period"
                                        ></main-select>
                                    </div>

                                    <div class="upload-section" 
                                        v-if="creating === 'admin' || 
                                            creating === 'facilitator'">
                                        <div class="note"
                                            v-if="admin.files.length"
                                        >these are the files to be sent with request</div>
                                        <div class="files">
                                            <attachment-badge
                                                v-for="(file,index) in admin.files"
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
                                            <div class="icon" v-if="admin.files.length < 3">
                                                <font-awesome-icon :icon="['fa','plus']"></font-awesome-icon>
                                            </div>
                                            <div class="text">
                                                {{admin.files.length === 3 ?
                                                    'you have reached the maximum of 3 files':
                                                    `add a file to send to ${creating}`}}
                                            </div>
                                        </div>
                                        <file-preview
                                            v-if="admin.files.length"
                                            :show="showPreview"
                                            :middle="true"
                                            :showRemove="true"
                                            :file="previewFile"
                                            @removeFile="removeFile"
                                            class="file-preview-wrapper"
                                        ></file-preview>
                                    </div>

                                    <main-list v-if="list" 
                                        @listItemSelected='selection'
                                        :multiple='multiple'
                                        :itemList='itemList'
                                        :select="computedSelectList"
                                    ></main-list>

                                    <main-list v-if="steps === 5" 
                                        @listItemSelected='parentRoleSelection'
                                        :multiple='false'
                                        :itemList="['father','mother','guardian']"
                                        :value="userDataTwo.parentRole"
                                        :select="computedSelectList"
                                    ></main-list>

                                    <input type="text" class="form-control form-input" 
                                        placeholder="other" 
                                        v-if="other" v-model="inputOther">

                                    <div class="role-info" v-if="roleInfo.length">
                                        {{roleInfo}}
                                    </div>
                                </template>
                            </template>
                            <template v-else>
                                <div class="section">
                                    <div class="back-icon"
                                        @click="clickedIconBack"
                                        v-if="actionDescription.length && !showButtonOk"
                                    >
                                        <font-awesome-icon 
                                            :icon="['fa','long-arrow-alt-left']">
                                        </font-awesome-icon>
                                    </div>
                                    <div class="description" 
                                        v-if="!localType.length && steps < 1">
                                        {{actionDescription}}
                                    </div>

                                    <main-list
                                        v-if="!type.length && !localType.length && 
                                            steps < 1"
                                        @listItemSelected='actionSelection'
                                        :multiple='false'
                                        :itemList="creation"
                                        select="which action do you want to perform?"
                                    ></main-list>

                                    <div class="actions"
                                        v-if="showButtonOk"
                                    >
                                        <post-button
                                            buttonText="ok"
                                            @click="clickedOk"
                                        ></post-button>
                                    </div>

                                </div>
                                <template v-if="!userType.length && localType === 'user'">
                                    <main-list
                                        @listItemSelected='actionSelection'
                                        :multiple='false'
                                        :itemList="[createUser]"
                                        select="action to perform"
                                    ></main-list>

                                </template>
                                <div v-if="userType === 'create a new user' && 
                                    (steps === 1 || steps === 4)">
                                    
                                    <text-input placeholder="username*"
                                        v-model="username"
                                        class="text-input"
                                        :noBorder="true"
                                    ></text-input>

                                    <text-input placeholder="email"
                                        v-model="email"
                                        class="text-input"
                                        :noBorder="true"
                                    ></text-input>

                                    <text-input placeholder="password*"
                                        :inputType="passwordType" 
                                        v-model="password"
                                        @iconChange='passwordIconChange' 
                                        :title='passwordTitle'
                                        :icon='passwordIcon'
                                        :prepend='true'
                                        class="text-input"
                                        :noBorder="true"
                                    ></text-input>
                                    <text-input placeholder="password confirmation*"
                                        :inputType="passwordConfirmationType" 
                                        v-model="passwordConfirmation"
                                        @iconChange='passwordIconChange' 
                                        :title='passwordConfirmationTitle'
                                        :icon='passwordIcon'
                                        :prepend='true'
                                        :noBorder="true"
                                        class="text-input"
                                    ></text-input>

                                    <input type="text" class="form-control form-input" 
                                        placeholder="first name*" 
                                        v-model="firstName">
                                        
                                    <input type="text" class="form-control form-input" 
                                        placeholder="last name*" 
                                        v-model="lastName">
                                        
                                    <input type="text" class="form-control form-input" 
                                        placeholder="other names" 
                                        v-model="otherNames">

                                    <main-select
                                        :items="['male','female']"
                                        :value="computedGender"
                                        backgroundColor="white"
                                        @selection="genderSelection"
                                        class="main-select"
                                        v-if="steps === 1 || steps === 4"
                                    ></main-select>

                                    <date-picker
                                        placeholder="date of birth"
                                        @datePicked="dobPicked"
                                        :bottomBorder="true"
                                        class="date-picker"
                                    ></date-picker>

                                </div>
                                <template v-if="steps === 6">
                                    <div class="user-section"
                                        v-for="account in createdAccountsData"
                                        :key="account.user.username"
                                    >
                                        <div class="username">{{`@${account.user.username}`}}</div>
                                        <div class="body" v-if="creating !== 'admin'">
                                            <profile-picture
                                                class="profile-picture"
                                            >
                                                <template slot="image">
                                                    <img :src="account.account.url">
                                                </template>
                                            </profile-picture>
                                            <div class="account">
                                                <div class="name">
                                                    {{account.account.name}}
                                                </div>
                                                <div class="tyoe">
                                                    {{account.account.account}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="info" v-else>
                                            {{`admin request has been sent to ${account.user.username}`}}
                                        </div>
                                        <div class="info" v-if="creating === 'facilitator'">
                                            {{`facilitating request has been sent as well`}}
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <div class="actions" v-if="accountActionText.length && 
                                !modalLoading">
                                <div class="action"
                                    @click="clickedAccountAction"
                                >{{accountActionText}}</div>
                            </div>

                            <input type="file" class="hidden" 
                                @change="fileChange"
                                ref="inputfile"
                                v-if="creating === 'admin' || creating === 'facilitator'"
                            >
                        </template>
                        <template slot="buttons" v-if="showInputSection && type.length">
                            <post-button buttonText='create' buttonStyle='success'
                                @click="clickedMainCreate"
                            ></post-button>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import AutoAlert from '../AutoAlert.vue'
import MainList from '../MainList.vue'
import TextInput from '../TextInput.vue'
import DatePicker from '../DatePicker.vue'
import SearchInput from '../SearchInput.vue'
import MainSelect from '../MainSelect.vue'
import AttachmentBadge from '../AttachmentBadge.vue'
import MainCheckbox from '../MainCheckbox.vue'
import ProfilePicture from '../profile/ProfilePicture.vue'
import PostButton from '../PostButton.vue'
import FilePreview from '../FilePreview.vue'
import NumberInput from '../NumberInput.vue'
import RadioInput from '../RadioInput.vue'
import { mapActions } from 'vuex'
import { dates } from '../../services/helpers'
    export default {
        components: {
            
            RadioInput,
            NumberInput,
            FilePreview,
            PostButton,
            
            ProfilePicture,
            MainCheckbox,
            AttachmentBadge,
            MainSelect,
            SearchInput,
            DatePicker,
            TextInput,
            MainList,
            AutoAlert,
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
            creator: {
                type: Object,
                default(){
                    return {
                        account: 'user'
                    }
                }
            },
        },
        data() {
            return {
                inputName: '',
                inputRole: '',
                inputName: '',
                title: '',
                localType: '',
                classStructure: '',
                roleInfo: '',
                multiple: false,
                list: false,
                itemList: [],
                other: false,
                inputDescription: '',
                alertMessage: '',
                alertSuccess: false,
                alertDanger: false,
                modalLoading: false,
                description: false,
                showButtonOk: false,
                showInputSection: false,
                actionDescription: '',
                creating: '',
                account: '',
                userType: '',
                userData: {
                    username: '',
                    email: '',
                    password: '',
                    passwordConfirmation: '',
                    gender: '',
                    firstName: '',
                    lastName: '',
                    otherNames: '',
                    dob: '',
                    accountName: '',
                },
                userDataTwo: {
                    username: '',
                    email: '',
                    password: '',
                    passwordConfirmation: '',
                    gender: '',
                    firstName: '',
                    parentRole: '',
                    lastName: '',
                    otherNames: '',
                    dob: '',
                    accountName: '',
                },
                username: '',
                email: '',
                password: '',
                passwordConfirmation: '',
                gender: '',
                firstName: '',
                lastName: '',
                otherNames: '',
                dob: '',
                createUser: '',
                creation: [],
                steps: 0,
                passwordTitle: 'show password',
                passwordConfirmationTitle: 'show password',
                passwordType: 'password',
                passwordConfirmationType: 'password',
                passwordIcon: ['fa','eye'],
                accountActionText: '',
                createdAccountsData: [],
                //admin
                admin: {
                    title: '', 
                    level: '', 
                    files: [],
                    hasSalary: false,
                    salary: '',
                    currency: '',
                    salaryPeriod: 'month',
                },
                showPreview: false,
                previewFile: null,
                showFileNote: false,
            }
        },
        watch: {
            show: {
                immediate:true,
                handler(newValue){
                    if (newValue) {                        
                        if (this.type.length) this.createFormDetails (this.type)
                    } else {
                        this.cleanUp()
                    }
                }
            },
            creator: {
                immediate: true,
                handler(newValue){
                    if (newValue.account === 'school') {
                        this.creation = ['create an administrating member account',
                            'create a facilitator account', 'create a parent account',
                            'create a learner account', 
                            'create a learner and parent account']
                    } else if (newValue.account === 'facilitator') {
                        this.creation = ['create a learner account','create a parent account',
                            'create a learner and parent account']
                    }
                }
            },
            inputName(newValue){
                if (this.steps === 2) {
                    this.userData.accountName = newValue
                } else if (this.steps === 5) {
                    this.userDataTwo.accountName = newValue
                }
            },
            creating(newValue){
                if (newValue === 'learner and parent') {
                    this.createUser = "create learner's account"
                } else {
                    this.createUser = `create ${this.creating}'s user` 
                }
            },
            steps(newValue,oldValue){
                if (this.steps === 0) {
                    this.accountActionText = ''
                    this.actionDescription = ''
                    this.createUser = this.creating !== 'learner and parent' ? 
                        `create ${this.creating}'s user` : "create learner's account"
                } else if (newValue === 1) {
                    if (this.userDataHasContent(this.userData)) {
                        this.fillUserInput(this.userData)
                    }
                    this.userType = 'create a new user'
                    this.showInputSection = false
                    this.account = ''
                    this.actionDescription = 'a'
                } else if (newValue === 2) {
                    if (this.userData.accountName.length) {
                        this.inputName = this.userData.accountName
                    } else {
                        this.inputName = ''
                    }
                    if (this.username.length && oldValue === 1) {  
                        this.inputName = `${this.lastName.trim()} ${this.firstName.trim()} ${this.otherNames.trim()}`
                        this.takeUserInput()
                        this.clearUserInput()
                    }
                    this.account = this.creating !== 'learner and parent' ? this.creating :
                        'learner'
                    if (this.creating !== 'learner and parent') {
                        this.accountActionText = 'create'
                    } else {
                        this.accountActionText = 'next'
                    }
                    
                } else if (newValue === 3) {
                    this.createUser = `create parent's user`
                    this.showInputSection = false
                    this.account = ''
                    this.accountActionText = ''
                    this.actionDescription = 'a'
                    this.localType = 'user'
                    this.userType = ''
                    this.clearUserInput() 
                } else if (newValue === 4) {
                    if (this.userDataHasContent(this.userDataTwo)) {
                        this.fillUserInput(this.userDataTwo)
                    }
                    this.userType = 'create a new user'
                    this.showInputSection = false
                    this.account = ''
                    this.actionDescription = 'a'
                    this.accountActionText = 'next'
                } else if (newValue === 5) {
                    if (this.userDataTwo.accountName.length) {
                        this.inputName = this.userDataTwo.accountName
                    } else {
                        this.inputName = ''
                    }
                    if (this.username.length && oldValue === 4) {                        
                        this.inputName = `${this.lastName.trim()} ${this.firstName.trim()} ${this.otherNames.trim()}`
                        this.takeUserInput()
                        this.clearUserInput()
                    }
                    this.accountActionText = 'create'
                    this.account = 'parent'
                } else if (newValue === 6) {
                    this.account = ''
                    this.actionDescription = ''
                    this.localType = ''
                    this.userType = ''
                    this.showInputSection = false
                    this.accountActionText = 'another'
                }
                
                if (this.account.length) {
                    this.showInputSection = true
                    this.title = `create ${this.account} account`
                } else {
                    this.title = ''
                    this.showInputSection = false
                }
            },
            inputRole(newValue){
                if (newValue === 'traditional') {
                    this.roleInfo = 'you will have tools to help you run both physical and virtual school'
                } else if (newValue === 'virtual') {
                    this.roleInfo = 'you will have tools to help you run a virtual school'                    
                } else if (newValue === 'nanny') {
                    this.roleInfo = 'you will have tools to help you improve your babysitting skils'
                } else if (newValue === 'trainer') {
                    this.roleInfo = ''
                } else if (newValue === 'counselor') {
                    this.roleInfo = 'you will have tools to help you sell and improve your selfmore'
                } else if (newValue === 'other') {
                    this.roleInfo = ''
                }
            },
            showFileNote(newValue){
                if (newValue) {
                    setTimeout(() => {
                        this.showFileNote = false
                    }, 4000);
                }
            }
        },
        computed: {
            computedSelectList(){
                return this.type.length ? `select role of ${this.type}` :
                    this.steps === 5 ? `select role of parent` : ''
            },
            computedGender(){
                return this.steps === 1 ? this.userData.gender : this.steps === 4 ?
                    this.userDataTwo.gender : ''
            },
        },
        methods: {
            ...mapActions(['createAccount','findUser']),
            getSearchData(data){
                this.userSearchData = data
            },
            clickedUpload(){
                if (this.admin.files.length === 3) {
                    this.showFileNote = true
                } else {
                    this.$refs.inputfile.click()
                }
            },
            fileChange(){
                this.admin.files.push(this.$refs.inputfile.files[0])
                this.$refs.inputfile.value = ''
            },
            removeFile(data){
                this.showPreview = false
                this.previewFile = null
            },
            removeShownFile(data){
                this.showPreview = false
                let file = data.data ? data.data : data,
                index = this.admin.files.findIndex(f=>{
                    return file.name === f.name && file.size === f.size
                })
                if (index > -1) {
                    this.admin.files.splice(index,1)
                }
            },
            preview(file){
                this.previewFile = file
                this.showPreview = true
            },
            levelSelection(data){
                this.admin.level = data
            },
            periodSelection(data){
                this.admin.salaryPeriod = data
            },
            cleanUp(){
                if (this.type.length) {
                    this.inputName = ''
                    this.inputDescription = ''
                    this.inputRole = ''
                } else {
                    this.steps = 0
                    this.actionDescription = ''
                    this.localType = ''
                    this.account = ''
                    this.inputName = ''
                    this.title = ''
                    this.userType = ''
                    this.createdAccountsData = []
                    this.accountActionText = ''
                    this.admin.files = []
                    this.admin.title = ''
                    this.admin.level = ''
                    this.admin.hasSalary = false
                    this.admin.salary = ''
                    this.admin.salaryPeriod = 'month'
                    this.admin.currency = ''
                    this.clearUserInput()
                    this.dumpUserData()
                }
                if (this.alertMessage.length) {
                    this.clearAlert()
                }
            },
            hideAlert(){
                this.clearAlert()
                // if (this.type.length) {
                //     this.modalDisappear()
                // }
            },
            parentRoleSelection(data){
                this.userDataTwo.parentRole = data
            },
            genderSelection(data){
                this.gender = data
            },
            clickedIconBack(){
                if (this.accountActionText === 'next' ||
                    this.accountActionText === 'create'){
                    this.steps = this.steps > 1 ? this.steps - 1 : 0
                }
                if (this.steps == 1) {
                    if (this.userData.username !== '') {
                        this.fillUserInput()
                    }
                } else if (this.steps === 3) {
                    this.steps--
                }
                if (this.userType.length) {
                    if (this.steps === 0) {                        
                        this.userType = ''
                        this.accountActionText = ''
                        return
                    } else {
                        this.title = `create ${this.account} account`
                    }
                }
                if (this.localType.length) {
                    this.actionDescription = ''
                    this.localType = ''
                }
            },
            clearUserInput(){
                this.username = ''
                this.firstName = ''
                this.lastName = ''
                this.otherNames = ''
                this.dob = ''
                this.passwordConfirmation = ''
                this.password = ''
                this.gender = ''
                this.email = ''
            },
            dumpUserData(){
                this.userData.username = ''
                this.userData.firstName = ''
                this.userData.lastName = ''
                this.userData.otherNames = ''
                this.userData.dob = ''
                this.userData.passwordConfirmation = ''
                this.userData.password = ''
                this.userData.gender = ''
                this.userData.email = ''
                this.userData.accountName = ''
                this.userDataTwo.username = ''
                this.userDataTwo.firstName = ''
                this.userDataTwo.lastName = ''
                this.userDataTwo.otherNames = ''
                this.userDataTwo.dob = ''
                this.userDataTwo.passwordConfirmation = ''
                this.userDataTwo.password = ''
                this.userDataTwo.gender = ''
                this.userDataTwo.email = ''
                this.userDataTwo.accountName = ''
            },
            fillUserInput(data){
                this.username = data.username
                this.firstName = data.firstName
                this.lastName = data.lastName
                this.otherNames = data.otherNames
                this.dob = data.dob
                this.passwordConfirmation = data.passwordConfirmation
                this.password = data.password
                this.gender = data.gender
                this.email = data.email
            },
            userDataHasContent(data){
                if (data.username.length ||
                    data.password.length ||
                    data.passwordConfirmation.length ||
                    data.email.length ||
                    data.dob.length ||
                    data.otherNames.length ||
                    data.firstName.length ||
                    data.lastName.length ||
                    data.gender.length) {
                    return true
                }
                return false
            },
            takeUserInput(data){              
                if (this.steps === 5) {
                    this.userDataTwo.username = this.username
                    this.userDataTwo.firstName = this.firstName
                    this.userDataTwo.lastName = this.lastName
                    this.userDataTwo.otherNames = this.otherNames
                    this.userDataTwo.dob = this.dob
                    this.userDataTwo.passwordConfirmation = this.passwordConfirmation
                    this.userDataTwo.password = this.password
                    this.userDataTwo.gender = this.gender
                    this.userDataTwo.email = this.email
                } else if (this.steps === 2) {
                    this.userData.username = this.username
                    this.userData.firstName = this.firstName
                    this.userData.lastName = this.lastName
                    this.userData.otherNames = this.otherNames
                    this.userData.dob = this.dob
                    this.userData.passwordConfirmation = this.passwordConfirmation
                    this.userData.password = this.password
                    this.userData.gender = this.gender
                    this.userData.email = this.email
                }
            },
            clickedAccountIconBack(){
                this.steps--
            },
            clickedAccountAction(data){
                if (this.userType === 'create a new user' && 
                    (this.steps === 1 || this.steps === 4)) {
                    this.validateUserData()
                } else if (this.steps === 2 && this.creating === 'learner and parent') {
                    this.clickedCreate(true)
                    
                } else if (this.steps === 4) {
                    this.steps++
                } else if (this.steps === 2 || this.steps === 5) {
                    this.clickedCreate()
                } else if (this.steps === 6) {
                    this.cleanUp()
                }
            },
            validateUserData(){
                let msg = ''

                if (!this.username.length) {
                    msg = 'please enter username'
                } else if (!this.password.length) {
                    msg = 'please enter password'
                } else if (this.password.length < 8) {
                    msg = 'please enter at least 8 characters for your password'
                } else if (this.password !== this.passwordConfirmation) {
                    msg = 'please the confirmation password must match your password'
                } else if (!this.firstName.length) {
                    msg = 'please your the first name for this user'
                } else if (!this.lastName.length) {
                    msg = 'please your the last name for this user'
                }

                if (this.creating === 'admin' || this.creating === 'parent' || 
                    this.creating === 'facilitator' || 
                    this.steps === 4) {
                    if (!this.dob.length) {
                        msg = 'date of birth is required for users that want to own parent accounts.'
                    } else if (dates.age(this.dob) < 18) {
                        msg = 'user must be at least 18 years in order to own a parent account.'
                    }
                }

                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                } else {
                    this.steps++
                }

            },
            modalDisappear(){
                this.cleanUp()
                this.inputName = ''
                this.inputOther = ''
                this.inputRole = ''
                this.actionDescription = ''
                this.other = false
                this.inputDescription = ''
                this.showInputSection = false
                this.title = ''
                this.$emit('closeCreateAccount')
            },
            actionSelection(data){
                if (this.steps === 0 && this.userType === data) {
                    this.localType = ''
                    this.userType = ''
                    return
                }
                if (data === 'create an administrating member account') {
                    this.showButtonOk = true
                    this.creating = 'admin'
                    this.actionDescription = "with this, you will be creating a user and send a request to the same user to become part of this school's administrating team"
                } else if (data === 'create a learner account') {
                    this.showButtonOk = true
                    this.creating = 'learner'
                    this.actionDescription = 'with this, you will be creating a user and a learner account for that same user'
                } else if (data === 'create a facilitator account') {
                    this.showButtonOk = true
                    this.creating = 'facilitator'
                    this.actionDescription = 'with this, you will be creating a user and a facilitator account for that same user. A request will be sent to this user to join your school.'
                } else if (data === 'create a parent account') {
                    this.showButtonOk = true
                    this.creating = 'parent'
                    this.actionDescription = 'with this, you will be creating a user and a parent account for that same user'
                } else if (data === 'create a learner and parent account') {
                    this.showButtonOk = true
                    this.creating = 'learner and parent'
                    this.actionDescription = 'with this, you will be creating two users, one having a learner account and the other a parent account.'
                } else {
                    this.steps++
                    this.userType = 'create a new user'
                    this.accountActionText = 'next'
                } 
            },
            clickedOk(){
                this.localType = 'user'
                this.showButtonOk = false
            },
            dobPicked(date){
                this.dob = date
            },
            passwordIconChange(){
                if (this.passwordIcon[1] === 'eye') {
                    this.passwordIcon[1] = 'eye-slash'
                    this.passwordType = 'text'
                    this.passwordConfirmationType = 'text'
                    this.passwordTitle = 'hide password'
                    this.passwordConfirmationTitle = 'hide password'
                } else {
                    this.passwordIcon[1] = 'eye'
                    this.passwordConfirmationType = 'password'
                    this.passwordType = 'password'
                    this.passwordConfirmationTitle = 'show password'
                    this.passwordTitle = 'show password'
                }
            },
            createFormDetails(data){                
                if (data === 'learner') {
                    
                } else if (data === 'parent') {

                } else if (data === 'facilitator') {

                } else if (data === 'professional') {
                    this.list = true
                    this.itemList = [
                        {name:'nanny',title:''},
                        {name:'trainer',title:''},
                        {name:'counselor',title:''},
                        {name:'other',title:''}
                    ]
                    this.description = true
                } else if (data === 'school') {
                    this.itemList = [
                        {name:'traditional',title:'physical school plus virtual'},
                        {name:'virtual',title:'a virtual only school'}
                    ]
                    this.list = true
                }

                this.title = `create a ${data} account`
                this.showInputSection = true
            },
            selection(data){
                if (this.type === 'professional' && data.name === 'other') {
                    this.other = true
                } else {
                    this.inputRole = data.name
                }
            },
            clearAlert(data){
                this.alertMessage = ''
                this.alertSuccess = false
                this.alertDanger = false
            },
            clickedMainCreate(){
                this.clickedCreate()
            },
            async clickedCreate(skip = false){
                if (this.modalLoading) return
                let formData = new FormData,
                    message = ''

                formData.append('creator',this.creator.account)
                formData.append('creatorId',this.creator.accountId)
                formData.append('create',this.type.length ? this.type : 
                        this.creating === 'learner and parent' ? "learner" : this.creating)
                formData.append('parent',this.creating === 'learner and parent' ? JSON.stringify(true) :
                        JSON.stringify(false))
                
                if (this.type === 'learner' || this.account === 'learner') {
                    if (!this.inputName || this.inputName.trim() === '') {
                        message = 'Please enter name of learner'
                    } else {
                        formData.append('name',this.inputName)
                    }
                } else if (this.type === 'facilitator') {
                    if (!this.inputName || this.inputName.trim() === '') {
                        message = 'Please enter name of facilitator'
                    } else {
                        formData.append('name',this.inputName)
                    }
                } else if (this.type === 'parent' || this.account === 'parent') {
                    if (!this.inputName || this.inputName.trim() === '') {
                        message = 'Please enter name of parent'
                    }if (this.steps === 5 && this.userDataTwo.parentRole === '') {
                        message = 'please select role of parent in the life of learner.'
                    } else {
                        formData.append('name',this.inputName)
                    }
                } else if (this.type === 'professional') {
                    if (!this.inputName || this.inputName.trim() === '') {
                        message = 'Please enter name of professional'
                    } else if (this.inputRole === '') {
                        message = 'Please select role of professional'
                    } else {
                        formData.append('name',this.inputName)
                        formData.append('role',this.inputRole ? this.inputRole.trim() : '')
                        if (this.other) {
                            formData.append('other_name',this.inputOther ? this.inputOther.trim() : '')
                        }         
                        formData.append('description',this.inputDescription ? this.inputDescription.trim() : '')   
                    }
                } else if (this.type === 'school') {
                    if (!this.inputName || this.inputName.trim() === '') {
                        message = 'Please enter name of school'
                    } else if (this.inputRole.trim() === '') {
                        message = 'Please select role of school'
                    } else if (this.classStructure.trim() === '') {
                        message = 'Please select a class structure for your school'
                    } else {
                        formData.append('name',this.inputName)
                        formData.append('classStructure',this.classStructure)
                        formData.append('role',this.inputRole ? this.inputRole.trim() : '')
                    }
                } else if (this.creating === 'admin') {
                    if (!this.inputName || this.inputName.trim() === '') {
                        message = 'Please enter name of admin'
                    } else if (this.admin.hasSalary && this.admin.salary.trim() === '') {
                        message = 'Please enter salary of admin'
                    } else {
                        formData.append('title',this.admin.title)
                        if (this.admin.files.length) {  
                            for (let i = 0; i < this.admin.files.length; i++) {
                                formData.append('files[]',this.admin.files[i]);
                                
                            }
                        }
                        if (this.admin.hasSalary) {                            
                            formData.append('salary',this.admin.salary)
                            formData.append('salaryPeriod',this.admin.salaryPeriod)
                            formData.append('currency',this.admin.currency)
                        }
                        formData.append('level',this.admin.level)
                        formData.append('description',this.inputDescription.trim())
                    }
                } else if (this.creating === 'facilitator') {
                    if (!this.inputName || this.inputName.trim() === '') {
                        message = 'Please enter name of facilitator'
                    } else if (this.admin.hasSalary && this.admin.salary.trim() === '') {
                        message = 'Please enter salary of facilitator'
                    } else {
                        if (this.admin.hasSalary) {                            
                            formData.append('salary',this.admin.salary)
                            formData.append('salaryPeriod',this.admin.salaryPeriod)
                            formData.append('currency',this.admin.currency)
                        }
                        if (this.admin.files.length) {  
                            for (let i = 0; i < this.admin.files.length; i++) {
                                formData.append('files[]',this.admin.files[i]);
                                
                            }
                        }
                    }
                }


                if (!message.length && !skip) {
                    if (!this.type.length) {                 
                        formData.append('new_username',this.userData.username)
                        formData.append('new_first_name',this.userData.firstName)
                        formData.append('new_last_name',this.userData.lastName)
                        formData.append('new_other_names ',this.userData.otherNames)
                        formData.append('new_email',this.userData.email)
                        formData.append('new_dob',this.userData.dob)
                        formData.append('new_password',this.userData.password)
                        formData.append('new_password_confirmation',this.userData.passwordConfirmation)
                        formData.append('new_gender',this.userData.gender)
                        formData.append('name',this.userData.accountName)
    
                        if (this.steps === 5) {                            
                            formData.append('parent_username',this.userDataTwo.username)
                            formData.append('parent_first_name',this.userDataTwo.firstName)
                            formData.append('parent_last_name',this.userDataTwo.lastName)
                            formData.append('parent_other_names ',this.userDataTwo.otherNames)
                            formData.append('parent_email',this.userDataTwo.email)
                            formData.append('parent_dob',this.userDataTwo.dob)
                            formData.append('parent_password',this.userDataTwo.password)
                            formData.append('parent_password_confirmation',this.userDataTwo.passwordConfirmation)
                            formData.append('parent_gender',this.userDataTwo.gender)
                            formData.append('parent_name',this.userDataTwo.accountName)
                            formData.append('parent_role',this.userDataTwo.parentRole)
                        }
                    }

                    this.modalLoading = true
                    let response = await this.createAccount(formData)
                    this.modalLoading = false

                    if (response.status) {
                        let msg
                        if (this.type.length) {
                            msg = `successfully created ${this.type}`
                        } else {
                            msg = `successful`
                            this.createdAccountsData.push({
                                account: response.accountOne,
                                user: response.userOne
                            })
                            if (response.accountTwo && response.userTwo) {
                                this.createdAccountsData.push({
                                    account: response.accountTwo,
                                    user: response.userTwo
                                })
                            }
                            this.steps = 6
                        }
                        this.alertSuccess = true
                        this.alertMessage = msg
                        this.cleanUp()
                    } else {
                        console.log('response :>> ', response);
                        let msg
                        if (!response.response.data.errors) {
                            msg = `${this.type} creation was unsuccessful.`
                        } else if (response.response.data.errors) {
                            msg = Object.values(response.response.data.errors).toString()
                        } else msg = `unsuccessful`
                        this.alertDanger = true
                        this.alertMessage = msg
                    }
                } else if (!message.length && !this.type.length) {
                    this.steps++
                }

                if (message.length) {
                    this.alertDanger = true
                    this.alertMessage = message
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .welcome-form{
        position: relative;

        .back-icon{
            position: absolute;
            right: 0;
            top: 0;
            padding: 5px;
            cursor: pointer;
        }

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

        .role-info{
            font-size: 12px;
            color: gray;
            text-align: center;
        }

        .loading{
           @include sticky-loader()
        }

        .date-picker,
        .text-input,
        input{
            width: 90%;
            margin: 10px auto;
            border: none;
            border-bottom: 2px solid $color-primary;
            border-radius: 0;
        }

        .main-select{
            width: 90%;
            margin: 10px auto;

            .selected-section{
                background: white;
            }
        }

        .salary-section{
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            margin: 10px auto;

            .per{
                margin: 0 10px;
            }
        }

        .user-section{
            margin-bottom: 20px;
            border-left: 1px solid;
            padding: 5px;

            .username{
                font-size: 14px;
                color: gray;
                text-align: end;
                margin-bottom: 5px;
            }

            .body{
                display: flex;
                justify-content: flex-start;
                width: 100%;

                .profile-picture{
                    width: 40px;
                    height: 40px;
                    min-width: 40px;
                    margin-right: 10px;
                }

                .account{
                    width: 100%;
                    font-size: 12px;

                    .name{
                        font-size: 14px;
                    }
                }
            }

            .info{
                margin: 10px 0 0;
                color: gray;
                font-size: 14px;
                width: 100%;
                text-align: justify;
            }
        }

        .actions{
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;

            .action{
                font-size: 14px;
                width: fit-content;
                padding: 5px;
                box-shadow: 0 0 2px gray;
                color: gray;
                border-radius: 10px;
                cursor: pointer;
            }
        }

        .upload-section{
            margin: 10px auto;
            width: 90%;

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
    }

@media screen and (max-width: 800px) {
    
    .welcome-form{

        input{
            font-size: 14px;
        }
    }
}
</style>