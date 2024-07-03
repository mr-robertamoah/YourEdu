<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear='closeModal'
                :requests="false"
                :mainOther="false"
            >
                <template slot="main">
                    <welcome-form
                        :title="title"
                        class="create-subject-wrapper"
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

                            <div class="form-section">

                                <text-input
                                    :bottomBorder="true"
                                    :error="errorName"
                                    placeholder="subject name*"
                                    v-model="inputName"
                                ></text-input>
                            </div>
                            <slide-up-group
                                v-if="computedShowSubject"
                                class="search-section"
                            >
                                <template slot="transition">
                                    <div class="search-subject"
                                        v-for="subject in subjects"
                                        :key="subject.id"
                                        @click="clickedSubject(subject)"
                                    >
                                        <div class="subject">{{subject.name}}</div>
                                        <p
                                            v-if="subject.description && subject.description.length"
                                        >{{`description: ${subject.description}`}}</p>
                                        <p
                                            v-if="subject.aliases.length"
                                        >{{`aliases: ${subject.aliases}`}}</p>
                                    </div>
                                </template>
                            </slide-up-group>
                            <p class="search-section-message"
                                v-if="computedShowSubject"
                            >click a sbject to which you want to add an alias</p>
                            <div class="add-subject"
                                @click="clickedAddSubject"
                                v-if="actionType === 'alias'"
                            >
                                add subject
                            </div>
                            <div class="form-section">
                                
                                <text-textarea
                                    :bottomBorder="true"
                                    placeholder="description of the subject"
                                    v-model="inputDescription"
                                ></text-textarea>
                            </div>
                            <div class="form-section" v-if="showRationale">
                                
                                <text-textarea
                                    :bottomBorder="true"
                                    placeholder="rationale for this subject"
                                    v-model="inputRationale"
                                ></text-textarea>
                            </div>
                            <div class="form-section" v-if="showAliases">
                                
                                <text-array
                                    :show="true"
                                    :bottomBorder="true"
                                    :propsArray="aliasesArray"
                                    example="soc, social"
                                    placeholder="aliases for subject name"
                                    @textArrayData="textArrayData"
                                ></text-array>
                            </div>
                            <div class="profiles"
                                v-if="showProfiles"
                            >
                                <span>
                                    create as
                                </span>
                                <div :key="key" v-for="(profile,key) in computedProfiles">
                                    <profile-bar
                                        :smallType="true"
                                        :profile="profile"
                                        :navigate="false"
                                        @clickedProfile="clickedProfile"
                                    ></profile-bar>
                                </div>
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
</template>

<script>
import SlideUpGroup from '../transitions/SlideUpGroup.vue';
import MainModal from '../MainModal.vue';
import TextInput from '../TextInput.vue';
import TextArray from '../TextArray.vue';
import PostButton from '../PostButton.vue';
import TextTextarea from '../TextTextarea.vue';
import WelcomeForm from '../welcome/WelcomeForm.vue';
import ProfileBar from '../profile/ProfileBar.vue';
import { default as _ } from 'lodash';
import { mapActions, mapGetters } from 'vuex';
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
        },
        components: {
            ProfileBar,
            
            WelcomeForm,
            TextTextarea,
            PostButton,
            TextArray,
            TextInput,
            MainModal,
            SlideUpGroup,
        },
        data() {
            return {
                errorName: false,
                showProfiles: false,
                loading: false,
                inputName: '',
                inputDescription: '',
                inputRationale: '',
                alertError: false,
                alertSuccess: false,
                showRationale: true,
                showAliases: true,
                alertMessage: '',
                buttonText: 'create',
                actionType: 'subject',
                title: 'create a subject',
                //textarray
                aliasesArray: [], //array for textarray
                temporaryInputAliases: '',
                inputAliases: '',
                aliasesCurrentPosition: null,
                aliasesArrayPositions: null,
                //
                subjectRequestnumber: 0,
                subjects: [],
                subject: {},
            }
        },
        watch: {
            inputName(newValue,oldValue) {
                // console.log('new',newValue);
                if (newValue.length) {
                    this.errorName = false
                    if (newValue.trim().length && this.actionType === 'subject') {
                        this.debouncedGetSubjects()
                    }
                    if (newValue.length < oldValue.length) {
                        this.subjectRequestnumber = 0
                    }
                }
            },
            actionType(newValue){
                if (newValue === 'subject') {
                    this.buttonText = 'create'
                    this.showRationale = true
                    this.showAliases = true
                    this.title = 'create a subject'
                    if (this.inputName.length) {
                        this.getSubjects()
                    }
                } else {
                    this.buttonText = 'add alias'
                    this.showRationale = false
                    this.showAliases = false
                    this.title = `create alias of ${this.subject.name}`
                }
            },
            subjects:{
                handler(newValue){
                    if (!newValue || !newValue.length) {
                        this.subject = {}
                    }
                },
                deep: true
            },
        },
        computed: {
            ...mapGetters(['getProfiles']),
            computedProfiles() {
                return this.getProfiles ? this.getProfiles : []
            },
            computedShowSubject(){
                return this.actionType === 'subject' && this.subjects.length 
                    && this.inputName.length
            },
            computedHasNoMore(){ //check if there are more
                return this.subjectRequestnumber === 0 ||
                    (this.subjectRequestnumber > 0 && this.subjects.length > 0)
            },
        },
        methods: {
            ...mapActions(['profile/createSubject','profile/searchSubjects',
                'profile/createSubjectAlias']),
            clickedCreate(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            clickedAddSubject(){
                this.actionType = 'subject'
            },
            clickedProfile(who){
                this.showProfiles = false
                this.createSubject(who)
            },
            debouncedGetSubjects: _.debounce(function(){this.getSubjects()},100),
            async getSubjects(){
                if (this.computedHasNoMore) {
                    
                    let response = await this['profile/searchSubjects'](this.inputName)

                    if (response.status) {
                        this.subjectRequestnumber = 0
                        this.subjects = response.data.subjects.length ? 
                            response.data.subjects.map(subject=>{
                                return {
                                    id: subject.id,
                                    name: subject.name,
                                    description: subject.description,
                                    aliases: _.take(subject.aliases.map(alias=>{
                                        return ` ${alias.name}`
                                    }),4).toString()
                                }
                            }) : []
                        
                    } else {
                        console.log(response.message)
                    }
                }
            },
            clickedSubject(subject){
                this.actionType = 'alias'
                this.subject = subject
            },
            async createSubject(who) {
                if (!this.inputName || !this.inputName.length) {
                    this.errorName = true
                    this.alertError = true
                    this.alertMessage = `please enter the ${this.actionType} name`
                    return
                }
                this.loading = true
                let data = {},
                    response = null
                data.account = who.account
                data.accountId = who.accountId
                data.name = this.inputName ? this.inputName.trim() : this.inputName
                data.description = this.inputDescription ? this.inputDescription.trim() : this.inputDescription

                if (this.actionType === 'subject') {

                    if (who.account === 'learner' || who.account === 'parent') {
                        this.alertError = true
                        this.alertMessage = `a ${who.account} cannot create a subject. Try creating an alias of a subject instead or contact us.`
                        this.loading = false
                        return
                    }
                    
                    data.rationale = this.inputRationale ? this.inputRationale.trim() : this.inputRationale
                    
                    let sections = this.aliasesArray
                    if (this.aliasesArrayPositions === this.aliasesCurrentPosition) {
                        if (this.inputAliases && this.inputAliases.trim() !== '') {
                            sections.push(this.inputAliases.trim())
                        } else {
                            sections.push(this.temporaryInputAliases.trim())
                        }
                    } else {
                        if (this.temporaryInputAliases && this.temporaryInputAliases.trim() !== '') {
                            sections.push(this.temporaryInputAliases.trim())
                        }
                    }
                    
                    data.aliases = sections

                    response = await this['profile/createSubject'](data)
                } else {
                    
                    response = await this['profile/createSubjectAlias']({
                        data, subjectId: this.subject.id})
                }

                this.loading = false
                if (response.status) {
                    this.subjectRequestnumber = 0
                    this.alertSuccess = true
                    this.alertMessage = `successfully created ${data.name} ${this.actionType}`
                    this.$emit('attachmentCreated', {
                        attachment: response.data.subject,
                        type: 'subject'
                    })
                    this.clearData()
                } else {
                    this.alertError = true
                    this.alertMessage = response.message
                }
            },
            textArrayData(data) {
                this.aliasesArray = data.inputArray
                this.temporaryInputAliases = data.temporaryInput
                this.aliasesCurrentPosition = data.currentPosition
                this.inputAliases = data.input
                this.aliasesArrayPositions = data.positions
            },
            closeModal(){
                this.clearData()
                this.$emit('closeCreateSubject')
            },
            clearData(){
                this.inputName = ''
                this.inputDescription = ''
                this.inputRationale = ''
                this.aliasesArray = []
                this.temporaryInputAliases = ''
                this.aliasesCurrentPosition = null
                this.aliasesArrayPositions = null
                this.inputAliases = ''
                this.actionType = 'subject'
            },
            clearAlert(){
                this.alertMessage = ''
                this.alertError = false
                this.alertSuccess = false
            },
        },
    }
</script>

<style lang="scss" scoped>

    .create-subject-wrapper{       

        .loading{
            position: sticky;
            width: 100%;
            text-align: center;
            top: 10px;
        }

        .form-section{
            margin-bottom: 10px;
        }

        .search-section{
            margin-top: -10px;

            .search-subject{
                padding: 5px;
                cursor: pointer;
                background-color: whitesmoke;

                &:hover{
                    background-color: white;
                }

                .subject{
                    font-size: 12px;
                    text-transform: capitalize;
                }

                p{
                    font-size: 10px;
                    color: gray;
                    margin-bottom: 0;
                    text-indent: 10px;
                    font-style: italic;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    overflow: hidden;
                }
            }
        }

        .search-section-message{
            margin: 3px 0;
            font-size: 12px;
        }

        .add-subject{
            font-size: 14px;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 0 2px gray;
            color: green;
            width: fit-content;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .profiles{
            margin: 10px 0;
            width: 50%;
            font-size: 14px;
            font-weight: 450;
        }
    }
</style>