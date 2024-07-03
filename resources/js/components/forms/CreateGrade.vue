<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear='closeModal'
                :alertMessage="alertMessage"
                :alertError="alertError"
                :alertSuccess="alertSuccess"
                @clearAlert="clearAlert"
                :loading="loading"
            >
                <template slot="loading" v-if="loading">
                    <pulse-loader :loading="loading"></pulse-loader>
                </template>
                <template slot="main">
                    <welcome-form
                        :title="title"
                        class="create-grade-wrapper"
                    >
                        <template slot="input">
                            <div class="form-section">

                                <text-input
                                    :bottomBorder="true"
                                    :error="errorName"
                                    placeholder="grade name*"
                                    v-model="inputName"
                                ></text-input>
                            </div>
                            <slide-up-group
                                v-if="computedShowGrade"
                                class="search-section"
                            >
                                <template slot="transition">
                                    <div class="search-subject"
                                        v-for="grade in grades"
                                        :key="grade.id"
                                        @click="clickedGrade(grade)"
                                    >
                                        <div class="grade">{{grade.name}}</div>
                                        <p
                                            v-if="grade.description && grade.description.length"
                                        >{{`description: ${grade.description}`}}</p>
                                        <p
                                            v-if="grade.age_group && grade.age_group.length"
                                        >{{`age group: ${grade.age_group} years`}}</p>
                                        <p
                                            v-if="grade.aliases.length"
                                        >{{`aliases: ${grade.aliases}`}}</p>
                                    </div>
                                </template>
                            </slide-up-group>
                            <p class="search-section-message"
                                v-if="computedShowGrade"
                            >click to add an alias</p>
                            <div class="add-grade"
                                @click="clickedAddGrade"
                                v-if="actionType === 'alias'"
                            >
                                add grade
                            </div>
                            <div class="add-age-group"
                                v-if="actionType === 'grade'"
                                @click="clickedAddGroup"
                            >
                                {{ageGroupText}}
                            </div>
                            <div class="form-section age-group"
                                v-if="hasAgeGroup && showAgeGroup"
                            >
                                <div class="age-section">
                                    <div class="label">from</div>
                                    <number-input
                                        :value="inputAgeStart"
                                        :error="errorAgeStart"
                                        placeholder="0"
                                        @numberinput="ageStart"
                                        :inputMax="50"
                                        :inputMin="0"
                                        :bottomBorder="true"
                                        :sm="true"
                                    ></number-input>
                                </div>
                                <div class="label" 
                                    v-if="computedAgeMore"
                                >and more</div>
                                <div class="age-section" v-else>
                                    <div class="label">to</div>
                                    <number-input
                                        :value="inputAgeEnd"
                                        :error="errorAgeEnd"
                                        placeholder="10"
                                        @numberinput="ageEnd"
                                        :sm="true"
                                        :bottomBorder="true"
                                        :inputMax="50"
                                        :inputMin="0"
                                    ></number-input>
                                </div>
                                <div class="label">years</div>
                            </div>
                            <div class="form-section" v-if="showAliases">
                                
                                <text-array
                                    :show="true"
                                    :bottomBorder="true"
                                    :propsArray="aliasesArray"
                                    example="soc, social"
                                    placeholder="aliases for grade name"
                                    @textArrayData="textArrayData"
                                ></text-array>
                            </div>
                            <div class="form-section">
                                
                                <text-textarea
                                    :bottomBorder="true"
                                    placeholder="description of the grade"
                                    v-model="inputDescription"
                                ></text-textarea>
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
import NumberInput from '../NumberInput.vue';
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
            NumberInput,
            TextArray,
            TextInput,
            MainModal,
            SlideUpGroup,
        },
        data() {
            return {
                errorName: false,
                errorAgeEnd: false,
                errorAgeStart: false,
                showProfiles: false,
                loading: false,
                inputName: '',
                inputDescription: '',
                ageGroupText: 'add age group',
                inputAgeEnd: '0',
                inputAgeStart: '0',
                hasAgeGroup: false,
                alertError: false,
                alertSuccess: false,
                showAgeGroup: false,
                alertMessage: '',
                buttonText: 'create',
                actionType: 'grade',
                title: 'create a grade',
                gradeRequestnumber: 0,
                grades: [],
                grade: {},
                //textarray
                aliasesArray: [], //array for textarray
                temporaryInputAliases: '',
                inputAliases: '',
                aliasesCurrentPosition: null,
                aliasesArrayPositions: null,
                showAliases: true
            }
        },
        watch: {
            inputName(newValue,oldValue) {
                if (newValue.length) {
                    this.errorName = false
                    if (newValue.trim().length && this.actionType === 'grade') {
                        this.debouncedGetGrades()
                    }
                    if (newValue.length < oldValue.length) {
                        this.gradeRequestnumber = 0
                    }
                }
            },
            hasAgeGroup(newValue){
                if (newValue) {
                    this.inputAgeEnd = '0'
                    this.inputAgeEnd = '5'
                    this.ageGroupText = 'no age group'
                } else {
                    this.ageGroupText = 'add age group'
                }
            },
            inputAgeStart(newValue){
                this.errorAgeStart = false
                this.errorAgeEnd = false
                if (Number(this.inputAgeStart) + 5 <= 50) {
                    this.inputAgeEnd = `${Number(this.inputAgeStart) + 5}`
                } else {
                    this.inputAgeEnd = '50'
                }
            },
            inputAgeEnd(newValue){
                this.errorAgeStart = false
                this.errorAgeEnd = false
            },
            actionType(newValue){
                if (newValue === 'grade') {
                    this.buttonText = 'create'
                    this.showAliases = true
                    this.showAgeGroup = true
                    this.title = 'create a grade'
                    if (this.inputName.length) {
                        this.getGrades()
                    }
                } else {
                    this.buttonText = 'add alias'
                    this.showAliases = false
                    this.showAgeGroup = false
                    this.title = `create alias of ${this.grade.name}`
                    this.hasAgeGroup = false
                }
            },
            grades:{
                handler(newValue){
                    if (!newValue || !newValue.length) {
                        this.grade = {}
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
            computedShowGrade(){
                return this.actionType === 'grade' && this.grades.length 
                    && this.inputName.length
            },
            computedHasNoMore(){ //check if there are more
                return this.gradeRequestnumber === 0 ||
                    (this.gradeRequestnumber > 0 && this.grades.length > 0)
            },
            computedAgeMore(){
                return this.inputAgeStart >= 50 
            },
        },
        methods: {
            ...mapActions(['profile/createGrade','profile/searchGrades',
                'profile/createGradeAlias']),
            clickedCreate(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            clickedAddGroup(){
                this.hasAgeGroup = !this.hasAgeGroup
            },
            clickedAddGrade(){
                this.actionType = 'grade'
            },
            clickedProfile(who){
                this.showProfiles = false
                this.createGrade(who)
            },
            ageStart(data){
                this.inputAgeStart = data
            },
            ageEnd(data){
                this.inputAgeEnd = data
            },
            debouncedGetGrades: _.debounce(function(){this.getGrades()},100),
            async getGrades(){
                if (this.computedHasNoMore) {
                    
                    let response = await this['profile/searchGrades'](this.inputName)

                    if (response.status) {
                        this.gradeRequestnumber += 1
                        this.grades = response.data.grades.length ? 
                            response.data.grades.map(grade=>{
                                return {
                                    id: grade.id,
                                    name: grade.name,
                                    description: grade.description,
                                    age_group: grade.age_group,
                                    aliases: _.take(grade.aliases.map(alias=>{
                                        return ` ${alias.name}`
                                    }),4).toString()
                                }
                            }) : []
                        
                    } else {
                        console.log(response.message)
                    }
                }
            },
            clickedGrade(grade){
                this.actionType = 'alias'
                this.grade = grade
            },
            async createGrade(who) {
                if (!this.inputName || !this.inputName.length) {
                    this.errorName = true
                    this.alertError = true
                    this.alertMessage = `please enter the grade name`
                    return
                }
                
                if (this.hasAgeGroup) {
                    if (!this.inputAgeStart.length || !this.inputAgeEnd.length) {
                        this.errorAgeStart = true
                        this.errorAgeEnd = true
                        this.alertError = true
                        this.alertMessage = `please start and end age inputs must have values`
                        return
                    }
                    if (Number(this.inputAgeStart) > Number(this.inputAgeEnd)) {
                        this.errorAgeStart = true
                        this.errorAgeEnd = true
                        this.alertError = true
                        this.alertMessage = `please start must be lower than the end`
                        return
                    }
                }

                this.loading = true
                let data = {},
                    response = null
                data.account = who.account
                data.accountId = who.accountId
                if (this.inputName) {
                    data.name = this.inputName.trim()
                }
                if (this.inputDescription) {
                    data.description = this.inputDescription.trim()
                }

                if (this.actionType === 'grade') {

                    if (who.account === 'learner' || who.account === 'parent') {
                        this.alertError = true
                        this.alertMessage = `a ${who.account} cannot create a grade. Try creating an alias of a grade instead or contact us.`
                        this.loading = false
                        return
                    }

                    if (this.hasAgeGroup) {
                        data.ageGroup = `${this.inputAgeStart.trim()} - ${this.inputAgeEnd.trim()}`
                    }
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
                    
                    response = await this['profile/createGrade'](data)
                } else {

                    response = await this['profile/createGradeAlias']({
                        data, gradeId: this.grade.id
                    })
                }

                this.loading = false
                if (response.status) {
                    this.gradeRequestnumber = 0
                    this.alertSuccess = true
                    this.alertMessage = `successfully created ${data.name} ${this.actionType}`
                    this.$emit('attachmentCreated', {
                        attachment: response.data.grade,
                        type: 'grade'
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
                this.$emit('closeCreateGrade')
            },
            clearData(){
                this.inputName = ''
                this.inputDescription = ''
                this.inputAgeStart = '0'
                this.inputAgeEnd = '0'
                this.aliasesArray = []
                this.temporaryInputAliases = ''
                this.aliasesCurrentPosition = null
                this.aliasesArrayPositions = null
                this.inputAliases = ''
                this.actionType = 'grade'
                this.hasAgeGroup = false
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

    .create-grade-wrapper{

        .form-section{
            margin-bottom: 10px;
        }

        .age-group{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            font-size: 12px;
            color: gray;

            .age-section{
                max-width: 120px;
                display: inline-flex;
                align-items: center;
            }

            .label{
                margin: 10px;
            }
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

                .grade{
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

        .add-age-group,
        .add-grade{
            font-size: 11px;
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