<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                @mainModalDisappear='closeModal'
                class="create-asessment-modal-wrapper"
            >
                <template slot="main">
                    <welcome-form
                        :title="title"
                        class="welcome-form"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="clearAlert"
                            ></auto-alert>
                            <pulse-loader 
                                class="loading"
                                :loading="loading"></pulse-loader>
                            
                            <div class="section">Assessment Info</div>
                            <text-input
                                :bottomBorder="true"
                                placeholder="assessment name*"
                                v-model="data.name"
                                class="other-input"
                            ></text-input>
                            <text-textarea
                                :bottomBorder="true"
                                placeholder="description of the assessment"
                                v-model="data.description"
                                class="other-input"
                            ></text-textarea>
                            <main-checkbox
                                v-if="!main"
                                v-model="data.restricted"
                                label="restricted assessment"
                                class="other-input"
                                title="check if assessment will be taken by only invited accounts"
                            ></main-checkbox>
                            <main-checkbox
                                v-model="data.random"
                                label="has randomized questions"
                                title="check if you want all assessment sections to have randomized questions"
                                class="other-input"
                            ></main-checkbox>
                            <main-checkbox
                                v-model="data.hasDuration"
                                label="has duration"
                                title="check if assessment should be taken for a duration"
                                class="other-input"
                            ></main-checkbox>
                            <number-input
                                v-if="data.hasDuration"
                                :bottomBorder="true"
                                label="duration"
                                placeholder="duration"
                                prepend="in minutes"
                                v-model="data.duration"
                                :hasMax="false"
                                class="number-input"
                            ></number-input>
                            <number-input
                                :bottomBorder="true"
                                placeholder="score assessment over"
                                v-model="data.totalMark"
                                :hasMax="false"
                                :hasMin="false"
                                :inputMax="computedTotalMark"
                                class="number-input"
                            ></number-input>
                            <date-picker
                                :bottomBorder="true"
                                v-model="data.dueDate"
                                class="other-input"
                                placeholder="due date for assessment"
                                :flatPickrConfig="flatPickrConfig"
                            ></date-picker>
                            <date-picker
                                :bottomBorder="true"
                                v-model="data.publishedDate"
                                class="other-input"
                                placeholder="date to show this assessment"
                                :flatPickrConfig="flatPickrConfig"
                            ></date-picker>
                            <div class="type" v-if="!main">
                                <radio-input
                                    v-model="data.type"
                                    radioValue="public"
                                    label="public"
                                ></radio-input>
                                <radio-input
                                    v-model="data.type"
                                    radioValue="private"
                                    label="private"
                                ></radio-input>
                            </div>

                            <div class="section add">
                                Assessment Sections
                                <font-awesome-icon :icon="['fa','plus']"
                                    title="add new section"
                                    class="plus"
                                    @click="goToSectionForm"
                                ></font-awesome-icon>
                            </div>

                            <assessment-section-form
                                class="sections"
                                @addSection="addSection"
                                :assessmentSections="data.assessmentSections"
                            ></assessment-section-form>
                        </template>
                        <template slot="buttons">
                            <post-button
                                v-if="computedShowPostButton"
                                :buttonText="buttonText"
                                buttonStyle='success'
                                @click="clickedCreate"
                            ></post-button>
                        </template>
                    </welcome-form>

                    <arranging-modal
                        
                    ></arranging-modal>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import RadioInput from '../RadioInput';
import AssessmentSectionForm from './AssessmentSectionForm';
import TextTextarea from '../TextTextarea';
import TextInput from '../TextInput';
import PostButton from '../PostButton';
import MainCheckbox from '../MainCheckbox';
import MainSelect from '../MainSelect';
import NumberInput from '../NumberInput';
import DatePicker from '../DatePicker';
import AssessmentSectionBadge from '../dashboard/AssessmentSectionBadge';
import ArrangingModal from '../dashboard/ArrangingModal';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import Alert from '../../mixins/Alert.mixin';
import { mapActions, mapGetters } from 'vuex';
import {bus} from '../../app';
    export default {
        components: {
            NumberInput,
            DatePicker,
            ArrangingModal,
            AssessmentSectionBadge,
            MainSelect,
            MainCheckbox,
            PostButton,
            AssessmentSectionForm,
            RadioInput,
            TextInput,
            TextTextarea,
            PulseLoader,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            main: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                data: {
                    name: '',
                    description: '',
                    publishedDate: '',
                    dueDate: '',
                    duration: '',
                    hasDuration: false,
                    restricted: false,
                    random: false,
                    totalMark: '',
                    assessmentSections: [],
                    removedAssessmentSections: [],
                    editedAssessmentSections: [],
                    section: null
                },
                title: 'create assessment',
                validationStatus: false,
                loading: false,
                buttonText: 'create',
                flatPickrConfig: {
                    dateFormat: "F j, Y H:i",
                    enableTime: true,
                    minDate: "today"
                },
                specificItemsNextPage: 1,
                specificItemsLoading: false,
                specificItems: []
            }
        },
        watch: {

        },
        mixins: [Alert],
        mounted () {
            bus
            .$on('assessmentError',(data)=>{
                this.setErrorAlert(data)
            })
            .$on('arrangedAssessmentSections', assessmentSections=>{

            })
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','getUser']),
            computedTotalMark() {
                return Number.parseInt(this.data.totalMark) > 5 ?
                    this.data.totalMark : 100
            },
            computedAccount() {
                return this['dashboard/getAccountDetails']
            },
            computedProfiles() {
                return this.getUser?.profiles
            },
            computedShowPostButton() {
                return this.data.assessmentSections.length &&
                    this.data.name.length && 
                    this.data.assessmentSections.reduce(function(previousSection,currentSection) {
                        return previousSection.length + currentSection.length
                    }, 0)
            },
        },
        methods: {
            ...mapActions(['dashboard/getAccountSpecificItem']),
            debounceGetSpecificAccountItems: _.debounce(
                this.getSpecificAccountItems(), 200
            ),
            async getSpecificAccountItems(){
                if (this.specificItemsNextPage === null) {
                    return
                }
                let response,
                    data = {
                        account: this.data.owner.account,
                        accountId: this.data.owner.accountId,
                        item: 'class',
                        secondItem: 'program',
                        search: this.searchItemsText
                    }

                this.specificItemsLoading = true
                response = await this['dashboard/getAccountSpecificItem']({
                    data, nextPage: this.specificItemsNextPage
                })
                this.specificItemsLoading = false

                if (response.status) {
                    if (!this.specificItemsNextPage) {
                        this.specificItems = response.items
                    } else {
                        this.specificItems.push(...response.items)
                    }
                    if (response.next) {
                        this.specificItemsNextPage += 1
                    } else {
                        this.specificItemsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            closeModal() {
                this.clearData()
                this.$emit('closeCreateAssessment')
            },
            clearData() {
                this.clearAlert()
            },
            clearAssessmentData() {                
                this.data.name = ''
                this.data.description = ''
                this.data.publishedDate = ''
                this.data.dueDate = ''
                this.data.duration = ''
                this.data.hasDuration = false
                this.data.restricted = false
                this.data.random = false
                this.data.totalMark = ''
                this.data.assessmentSections = []
                this.data.removedAssessmentSections = []
                this.data.editedAssessmentSections = []
            },
            clearAssessmentSectionData() {
                bus.$emit('clearAssessmentSectionData')
            },
            goToSectionForm() {
                bus.$emit('toggleSectionForm')
                this.clearAssessmentSectionData()
            },
            addSection(section) {
                this.data.assessmentSections.push(
                    this.mapAssessmentSectionData(_.cloneDeep(section))
                )
                this.clearAssessmentSectionData()
            },
            mapAssessmentSectionData(sectionData) {
                return {
                    id: sectionData.id.length ? sectionData.id : 
                        `${Math.floor(Math.random() * 1000)}`,
                    name: sectionData.name,
                    instruction: sectionData.instruction,
                    position: sectionData.position,
                    autoMark: sectionData.autoMark,
                    hasMaxQuestions: sectionData.hasMaxQuestions,
                    random: sectionData.random,
                    maxQuestions: sectionData.maxQuestions,
                    questions: sectionData.questions,
                    removedQuestions: sectionData.removedQuestions,
                    editedQuestions: sectionData.editedQuestions,
                    answerType: sectionData.answerType,
                }
            },
            validateAssessmentData(data) {
                this.validationStatus = this.validateAssessment(
                    data
                )

                if (!this.validationStatus) {
                    return this.validationStatus
                }
                
                data.assessmentSections.forEach(section=>{
                    this.validationStatus = this.validateAssessmentSection(
                        section
                    )

                    if (!this.validationStatus) {
                        return this.validationStatus
                    }

                    section.questions.forEach(question=>{
                        this.validationStatus = this.validateAssessmentSectionQuestion(
                            question
                        )
    
                        if (!this.validationStatus) {
                            return this.validationStatus
                        }

                        question.possibleAnswers.forEach(possibleAnswer=>{

                            this.validationStatus = this.validateAssessmentSectionQuestionPossibleAnswer(
                                possibleAnswer
                            )
                        })
                    })
                })
            },
            validateAssessment(data) {
                if (!data.name.length) {
                    this.setErrorAlert({message: 'name of assessment is required.'})
                    return false
                }

                return true
            },
            validateAssessmentSection(data) {
                if (!data.name.length) {
                    this.setErrorAlert({message: `name of assessment section is required.`})
                    return false
                }

                if (!data.questions.length) {
                    this.setErrorAlert({
                        message: `assessment with name: ${data.name} requires at least a question.`,
                        lengthy: true
                    })
                    return false
                }

                return true
            },
            validateAssessmentSectionQuestion(data, assessmentSection) {
                if (!data.question.length) {
                    this.setErrorAlert({message: `${assessmentSection.name} assessment section requires it's questions to have names.`})
                    return false
                }

                if (this.isTrueOrFalseOptionAnswerType(data.answerType)) {
                    if (data.possibleAnswers.length < 2) {
                        this.setErrorAlert({
                            message: `${data.question} question requires at least two possible answers.`,
                            lengthy: true
                        })
                        return false
                    }
                } else if (this.isArrangeFlowAnswerType(data.answerType)) {
                    if (data.possibleAnswers.length < 2) {
                        this.setErrorAlert({
                            message: `${data.question} question requires at least two options to be re-ordered.`,
                            lengthy: true
                        })
                        return false
                    }
                }

                return true
            },
            validateAssessmentSectionQuestionPossibleAnswer(data, question) {
                if (!data.option.length) {
                    this.setErrorAlert({
                        message: `possible answers of ${question.question} question requires an option.`,
                        lengthy: true
                    })
                    return false
                }
            },
            isTrueOrFalseOptionAnswerType(answerType) {
                return answerType === 'true_false' || 
                    answerType === 'option'
            },
            isArrangeFlowAnswerType(answerType) {
                return answerType === 'arrange' || 
                    answerType === 'flow'
            },
            async clickedCreate() {
                this.validateAssessmentData()

                if (!this.validationStatus) {
                    return
                }

                this.loading = true
                let response,
                    data = new FormData

                data.append('name', this.data.name)
                data.append('description', this.data.description)

                if (this.edit) {
                    
                } else {

                    response = await this['dashboard/createAssessment'](data)
                }

                if (response.status) {
                    
                } else {
                    this.responseErrorAlert(response.response)
                    console.log('response :>> ', response);
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .create-asessment-modal-wrapper{
        z-index: 10005;

        .welcome-form{            
            position: relative;

            .loading{
                @include sticky-loader()
            }

            .section{
                @include form-section()
            }

            .sections{
                display: flex;
                width: 90%;
                padding: 10px;
                overflow-x: scroll;
                margin: 10px auto;
            }

            .add{
                display: flex;
                width: 100%;
                justify-content: space-between;
                padding-right: 10px;

                .plus{
                    cursor: pointer;
                    color: gray;
                }
            }

            .class-input, .number-input{
                width: 90%;
                margin: 10px auto;
                border: none;
                border-bottom: 2px solid $color-primary;
                border-radius: 0;
            }

            .number-input{
                border: none;
            }

            .other-input, .type{
                width: 90%;
                margin: 10px auto;
            }

            .type{
                display: flex;
            }

            .add-section{
                width: 90%;
                margin: 10px auto;
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

            .other-input,
            .attachments{
                width: 90%;
                margin: 10px auto;
            }

            .attachment-heading{
                font-size: 12px;
                color: gray;
                text-align: center;
            }

            .search-input{
                border: none;
                border-bottom: 2px solid $color-primary;
                background: white;
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
    }
</style>