<template>
     <main-modal v-if="showForm" 
        @mainModalDisappear='closeModal'
        :alertMessage="alertMessage"
        :alertError="alertError"
        :loading="computedLoading"
        @clearAlert="clearAlert"
        :request="false"
     >  
        <template slot="loading" v-if="computedLoading">
            <pulse-loader :loading="computedLoading"></pulse-loader>
        </template>
        <template slot="main" v-else>
            <welcome-form>
                <template slot="input">
                    
                    <div class="form-edit" v-if="computedAuthor">
                        <text-input
                            placeholder="author names"  
                            :bottomBorder="true"
                            v-model="inputAuthorNames"></text-input>
                    </div>
                    <div class="form-edit" v-if="computedBody">
                        <main-textarea type="text" 
                            placeholder="what do you have in mind*" 
                            v-model="textareaContent"></main-textarea>
                    </div>
                    <div class="form-edit" v-if="computedQuestion">
                        <text-textarea type="text" 
                            placeholder="question*" 
                            :error="errorQuestion"
                            :bottomBorder="true"
                            v-model="inputQuestion"></text-textarea>
                    </div>
                    <div class="form-edit" v-if="computedRiddle">
                        <text-textarea type="text" 
                            placeholder="your riddle*" 
                            :error="errorRiddle" 
                            :bottomBorder="true"
                            v-model="inputRiddle"></text-textarea>
                    </div>
                    <div class="form-edit" v-if="computedRiddle || computedQuestion">
                        <number-input
                            title = 'answers scored over...'
                            placeholder="answers scored over*" 
                            :error="errorScore"
                            :bottomBorder="true"
                            @numberinput="getInputScore"
                            :value="inputScore"></number-input>
                    </div>
                    <div class="ask-answers" v-if="computedRiddle || computedQuestion">
                       max value is 100 and min value is 5
                    </div>
                    <div class="ask-answers" v-if="computedQuestion">
                        does it have possible answers? <div class="no"
                            :class="{yes:!answerYes}"
                            @click="clickedAnswerYes"
                        >{{answerYes ? 'no' : 'yes'}}</div>
                    </div>
                    <template v-if="showPossibleAnswers">
                        <text-array
                            :show="showPossibleAnswers"
                            :error="errorAnswers"
                            :propsArray="answersArray"
                            :edit="editAnswersArray"
                            placeholder="possible answers"
                            @textArrayData="answerArrayData"
                        ></text-array>
                    </template>
                    <div class="form-edit" v-if="computedTitle">
                        <text-input
                            placeholder="title*" 
                            :error="errorTitle"
                            :bottomBorder="true"
                            v-model="inputTitle"></text-input>
                    </div>
                    <div class="form-edit" v-if="computedDescription">
                        <text-textarea type="text" 
                            placeholder="description*" 
                            :error="errorDescription" 
                            :bottomBorder="true"
                            v-model="inputDescription"></text-textarea>
                    </div>

                    <div class="form-edit" v-if="computedAbout">
                        <text-textarea type="text" 
                            placeholder="about"  
                            :bottomBorder="true"
                            v-model="inputAbout"></text-textarea>
                    </div>
                    <template v-if="computedSection">
                        <text-array
                            :show="computedSection"
                            :error="errorSection"
                            :propsArray="poemSectionsObject"
                            :edit="editPoemSections"
                            placeholder="poem section"
                            @textArrayData="poemArrayData"
                        ></text-array>
                    </template>
                    <div class="form-edit" v-if="computedPublished">
                        <date-picker 
                            :flatPickrConfig="flatPickrConfig"
                            :bottomBorder="true"
                            :value="inputEditPublished"
                            :placeholder="inputPublishedPlaceholder"
                            @datePicked="datePicked"></date-picker>
                    </div>

                    <file-input
                        :fileMax="fileMax"
                        :error="errorFile"
                        :bottomBorder="true"
                        @uploadedFiles="uploadedFiles"
                    ></file-input>
                </template>

                <template slot="buttons">
                    <post-button 
                        :buttonText="edit ? 'save' : 'ok'" 
                        buttonStyle='success'
                        @click="clickedCreate"
                    ></post-button>
                </template>
            </welcome-form>
        </template>
    </main-modal>
</template>

<script>
import TextInput from '../TextInput.vue'
import NumberInput from '../NumberInput.vue'
import FileInput from '../FileInput.vue'
import DatePicker from '../DatePicker.vue'
import TextTextarea from '../TextTextarea.vue'
import TextArray from '../TextArray.vue'
import MainTextarea from '../MainTextarea.vue'
import PostButton from '../PostButton.vue'


    export default {
        props: {
            type: {
                type: String,
                default: ''
            },
            editableData: {
                type: Object,
                default: null
            },
            showForm: {
                type: Boolean,
                default: false
            },
            edit: {
                type: Boolean,
                default: false
            },
            fileMax: {
                type: Number,
                default: 1
            },
            chat: {
                type: Boolean,
                default: false
            },
            loading: {
                type: Boolean,
                default: false
            },
        },
        components: {
            
            PostButton,
            MainTextarea,
            TextArray,
            TextTextarea,
            DatePicker,
            FileInput,
            NumberInput,
            TextInput,
        },
        data() {
            return {
                textareaContent: '',
                inputContent: null,
                flatPickrConfig: {
                    minDate: 'today',
                    dateFormat: 'F j, Y H:i',
                    enableTime: true,
                    maxDate: new Date().fp_incr(14)
                },
                inputTitle: '',
                inputAbout: '',
                inputDescription: '',
                inputAuthorNames: '',
                inputPublished: '',
                inputPublishedPlaceholder: 'select a date to publish',
                inputEditPublished: '',
                inputQuestion: '',
                inputSection: '',
                inputRiddle: '',
                alertMessage: '',
                inputScore: '',
                alertError: false,
                inputFile: null,
                temporarySection: '',
                errorRiddle: false,
                errorFile: false,
                errorTitle: false,
                errorQuestion: false,
                errorScore: false,
                errorDescription: false, // to make inputs show error
                //for poem sections
                errorSection: false,
                poemSections : 0,
                currentPoemSections : 0, // holds the current value of the poem input section
                poemSectionsObject : [], // array of poem sections
                editPoemSections: false,
                //for possible answers
                errorAnswers: false,
                inputAnswer: '',
                temporaryInputAnswer: '',
                answerArrayPositions : 0,
                answerArrayCurrentPosition : 0, // holds the current value of the poem input section
                answersArray : [], // array of possible answers
                editAnswersArray: false,
                answerYes: false,
                showPossibleAnswers: false,
            }
        },
        watch: {
            inputTitle(newValue) {
                this.errorTitle = false
            },
            inputRiddle(newValue) {
                this.errorRiddle = false
            },
            inputQuestion(newValue) {
                this.errorQuestion = false
            },
            editableData: {
                immediate: true,
                handler(newValue){
                    if (!this.edit) {
                        return
                    }
                    this.textareaContent = newValue.content
                    // this.type = newValue.typeName
                    if (newValue && newValue.type) {
                            this.inputPublishedPlaceholder = new Date(newValue.type[0].published).toDateString().slice(4)
                        if (newValue.typeName === 'book') {
                            this.inputTitle = newValue.type[0].title
                            this.inputAbout = newValue.type[0].about
                            this.inputAuthorNames = newValue.type[0].authorNames
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'poem') {
                            this.inputTitle = newValue.type[0].title
                            this.inputAbout = newValue.type[0].about
                            this.poemSectionsObject = newValue.type[0].sections
                            this.editPoemSections = this.edit ? true : false
                            this.poemSections = newValue.type[0].sections.length - 1
                            this.inputAuthorNames = newValue.type[0].authorNames
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'riddle') {
                            this.inputAuthorNames = newValue.type[0].authorNames
                            this.inputRiddle = newValue.type[0].body
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'activity') {
                            this.inputDescription = newValue.type[0].description
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'question') {
                            this.inputQuestion = newValue.type[0].body
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else {

                        }
                    }
                },
                deep: true
            },
        },
        computed: {
            computedBody(){
                return this.edit
            },
            computedRiddle(){
                return this.type === 'riddle' ? true : 
                    this.edit && this.editableData.typeName === 'riddle' ? true : false
            },
            computedTitle() {
                return this.type === 'book' || this.type === 'poem' ? true : 
                    this.edit && (this.editableData.typeName === 'book' ||
                    this.editableData.typeName === 'poem')  ? true : false 
            },
            computedAuthor() {
                return this.type === 'book' || this.type === 'poem' || 
                    this.type === 'riddle' ? true : 
                    this.edit && (this.editableData.typeName === 'book' ||
                    this.editableData.typeName === 'poem' || 
                    this.editableData.typeName === 'riddle')   ? true : false
            },
            computedAbout() {
                return this.type === 'book' || this.type === 'poem' ? true : 
                    this.edit && (this.editableData.typeName === 'book' ||
                    this.editableData.typeName === 'poem')   ? true : false 
            },
            computedDescription() {
                return this.type === 'activity' ? true : 
                    this.edit && this.editableData.typeName === 'activity' ? true : false
            },
            computedPublished() {
                if (this.chat || (this.edit && this.editableData.type === null)) {
                    return false
                }
                return true
            },
            computedSection() {
                return this.type === 'poem' ? true : 
                    this.edit && this.editableData.typeName === 'poem' ? true : false
            },
            computedQuestion() {
                return this.type === 'question' ? true : 
                    this.edit && this.editableData.typeName === 'question' ? true : false
            },
            computedLoading(){
                return this.loading ? true : false
            },
        },
        methods: {
            getInputScore(value){
                this.inputScore = value
            },
            clickedAnswerYes(){
                this.answerYes = !this.answerYes
                if (this.answerYes) {
                    this.showPossibleAnswers = true
                } else {
                    this.showPossibleAnswers = false
                }
            },
            poemArrayData(data){
                this.poemSectionsObject = data.inputArray
                this.temporarySection = data.temporaryInput
                this.currentPoemSections = data.currentPosition
                this.inputSection = data.input
                this.poemSections = data.positions
            },
            answerArrayData(data){
                this.answersArray = data.inputArray
                this.temporaryInputAnswer = data.temporaryInput
                this.answerArrayCurrentPosition = data.currentPosition
                this.inputAnswer = data.input
                this.answerArrayPositions = data.positions
            },
            datePicked(data){
                this.inputPublished = data
            },
            clearAlert(){
                this.alertMessage = ''
                this.alertError = false
            },
            uploadedFiles(data){
                this.inputFile = data
            },
            clickedCreate(){
                let data = {}
                let error = false

                if (this.type === 'book') {
                    if (this.inputTitle.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter the title of book'
                        this.errorTitle = true
                        error = true
                    } else {
                        data = {
                            title: this.inputTitle,
                            authorNames: this.inputAuthorNames,
                            about: this.inputAbout,
                            published: this.inputPublished,
                            file: this.inputFile,
                        }
                    }
                } else if (this.type === 'poem') {
                    if (this.inputTitle.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter the title of peom'
                        this.errorTitle = true
                        error = true
                    } else if (this.poemSectionsObject.length === 0 && 
                        this.inputSection.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter at least one section of the poem'
                        this.errorSection = true
                        error = true
                    } else {
                        let sections = this.poemSectionsObject
                        if (this.poemSections === this.currentPoemSections) {
                            if (this.inputSection && this.inputSection.trim() !== '') {
                                sections.push(this.inputSection.trim())
                            } else {
                                sections.push(this.temporarySection.trim())
                            }
                        } else {
                            if (this.temporarySection && this.temporarySection.trim() !== '') {
                                sections.push(this.temporarySection.trim())
                            }
                        }
                        data = {
                            title: this.inputTitle,
                            authorNames: this.inputAuthorNames,
                            about: this.inputAbout,
                            sections: sections,
                            published: this.inputPublished,
                            file: this.inputFile,
                        }
                    }
                } else if (this.type === 'question') {
                    if (this.inputQuestion.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter the question'
                        this.errorQuestion = true
                        error = true
                    } else if (this.answerYes && this.answersArray.length < 2  &&
                        this.inputAnswer === '') {
                        this.alertError=true
                        this.alertMessage='please enter at least 2 possible answers'
                        this.errorAnswers = true
                        error = true
                    } else if (Number(this.inputScore) < 5) {
                        this.alertError=true
                        this.alertMessage='please enter number greater than 5'
                        this.errorScore = true
                        error = true
                    } else if (this.inputScore.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter number for scoring answers'
                        this.errorScore = true
                        error = true
                    } else {
                        data = {
                            body: this.inputQuestion,
                            scoreOver: this.inputScore,
                            published: this.inputPublished,
                            file: this.inputFile,
                        }
                        if (this.answerYes) {
                            let sections = this.answersArray
                            if (this.answerArrayPositions === this.answerArrayCurrentPosition) {
                                if (this.inputAnswer && this.inputAnswer.trim() !== '') {
                                    sections.push(this.inputAnswer.trim())
                                } else {
                                    sections.push(this.temporaryInputAnswer.trim())
                                }
                            } else {
                                if (this.temporaryInputAnswer && this.temporaryInputAnswer.trim() !== '') {
                                    sections.push(this.temporaryInputAnswer.trim())
                                }
                            }
                            data.possibleAnswers = sections
                        }
                    }

                } else if (this.type === 'riddle') {
                     if (this.inputRiddle.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter the riddle'
                        this.errorRiddle = true
                        error = true
                    } else if (Number(this.inputScore) < 5) {
                        this.alertError=true
                        this.alertMessage='please enter number greater than 5'
                        this.errorScore = true
                        error = true
                    } else if (this.inputScore.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter number for scoring answers'
                        this.errorScore = true
                        error = true
                    } else {
                        data = {
                            authorNames: this.inputAuthorNames,
                            body: this.inputRiddle,
                            scoreOver: this.inputScore,
                            published: this.inputPublished,
                            file: this.inputFile,
                        }
                    }
                } else if (this.type === 'activity') {
                     if (this.inputDescription.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter a description for this activity'
                        this.errorDescription = true
                        error = true
                    } else if (this.inputFile.length < 1) {
                        this.alertError=true
                        this.alertMessage='please select a file for this activity'
                        this.errorFile = true
                        setTimeout(function(){
                            this.errorFile = true
                        }, 2000)
                        error = true
                    } else {
                        data = {
                            published: this.inputPublished,
                            description: this.inputDescription,
                            file: this.inputFile,
                        }
                    }
                }

                if (!error) {
                    if (this.edit) {
                        data['type'] = this.editableData.typeName
                        data['postId'] = this.editableData.id
                        data['content'] = this.textareaContent
                        data['contentFile'] = this.inputFile
                        this.$emit('clickedEdit', data)
                    } else {
                        this.$emit('clickedCreate', data)
                    }
                    this.closeModal()
                }
            },
            closeModal(){
                this.inputSection = []
                this.inputAbout = ''
                this.inputDescription = ''
                this.inputDescription = ''
                this.inputTitle = ''
                this.inputPublished = ''
                this.inputQuestion = ''
                this.inputRiddle = ''
                this.inputScore = '',
                this.inputFile = {}

                this.textareaContent = ''
                this.$emit('mainModalDisappear')
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>