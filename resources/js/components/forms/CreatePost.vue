<template>
     <main-modal v-if="showForm" 
        @mainModalDisappear='closeModal'
        :alertMessage="alertMessage"
        :alertError="alertError"
        @clearAlert="clearAlert"
     >  

        <template slot="loading" v-if="computedLoading">
            loading...
        </template>
        <template slot="main" v-else>
            <welcome-form>
                <template slot="input">
                    
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
                    <div class="form-edit" v-if="computedTitle">
                        <text-input type="text" 
                            placeholder="title*" 
                            :error="errorTitle"
                            :bottomBorder="true"
                            v-model="inputTitle"></text-input>
                    </div>
                    <div class="form-edit" v-if="computedAuthor">
                        <text-input type="text" 
                            placeholder="author"  
                            :bottomBorder="true"
                            v-model="inputAuthor"></text-input>
                    </div>
                    <div class="form-edit" v-if="computedDescription">
                        <text-textarea type="text" 
                            placeholder="description*" 
                            :error="errorDescription" 
                            :bottomBorder="true"
                            v-model="inputDescription"></text-textarea>
                    </div>
                    <div class="form-edit" v-if="computedRiddle">
                        <text-textarea type="text" 
                            placeholder="your riddle*" 
                            :error="errorRiddle" 
                            :bottomBorder="true"
                            v-model="inputRiddle"></text-textarea>
                    </div>

                    <div class="form-edit" v-if="computedAbout">
                        <text-textarea type="text" 
                            placeholder="about"  
                            :bottomBorder="true"
                            v-model="inputAbout"></text-textarea>
                    </div>
                    
                    <div class="section my-2" v-if="computedSection">
                        <div class="add" 
                            @click="pushSection"
                            title="add another section"
                        >
                            <font-awesome-icon
                                :icon="['fa','plus']"
                            ></font-awesome-icon>
                        </div>
                        <div class="minus" 
                            @click="popSection"
                            v-if="poemSectionsObject.length > 0"
                            title="remove current section"
                        >
                            <font-awesome-icon
                                :icon="['fa','minus']"
                            ></font-awesome-icon>
                        </div>
                        <div class="form-edit"
                        >
                            <text-textarea type="text"  
                                @keyup.enter="pushSection"
                                placeholder="poem section" 
                                :error="errorSection" 
                                :bottomBorder="true"
                                v-model="inputSection"></text-textarea>
                        </div>
                        <div class="lower">
                            <post-button
                                buttonText="previous"
                                :makeDisabled="disablePrevious"
                                @click="clickedPrevious"
                                title="previous section"
                            ></post-button>
                            <post-button
                                buttonText="next"
                                :makeDisabled="disableNext"
                                @click="clickedNext"
                                title="next section"
                            ></post-button>
                        </div>
                    </div>

                    <div class="form-edit" v-if="computedPublished">
                        <date-picker 
                            :flatPickrConfig="flatPickrConfig"
                            :bottomBorder="true"
                            :value="inputEditPublished"
                            :placeHolder="inputPublishedPlaceholder"
                            @datePicked="datePicked"></date-picker>
                    </div>

                    <file-input
                        :fileMax="2"
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
import TextInput from '../TextInput'
import FileInput from '../FileInput'
import {dates} from '../../services/helpers'
import MainModal from '../MainModal'
import DatePicker from '../DatePicker'
import WelcomeForm from '../welcome/WelcomeForm'
import TextTextarea from '../TextTextarea'
import MainTextarea from '../MainTextarea'
import PostButton from '../PostButton'
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
            loading: {
                type: Boolean,
                default: false
            },
        },
        components: {
            PostButton,
            MainTextarea,
            TextTextarea,
            WelcomeForm,
            DatePicker,
            MainModal,
            FileInput,
            TextInput,
        },
        data() {
            return {
                textareaContent: '',
                inputContent: null,
                flatPickrConfig: {},
                inputTitle: '',
                inputAbout: '',
                inputDescription: '',
                inputAuthor: '',
                inputPublished: '',
                inputPublishedPlaceholder: 'select a date to publish',
                inputEditPublished: '',
                inputQuestion: '',
                inputSection: '',
                inputRiddle: '',
                alertMessage: '',
                alertError: false,
                inputFile: null,
                temporarySection: '',
                errorRiddle: false,
                errorFile: false,
                errorTitle: false,
                errorQuestion: false,
                errorDescription: false, // to make inputs show error
                errorSection: false,
                poemSections : 0,
                currentPoemSections : 0, // holds the current value of the poem input section
                poemSectionsObject : [], // array of poem sections
            }
        },
        watch: {
            inputTitle(newValue) {
                this.errorTitle = false
            },
            inputRiddle(newValue) {
                this.errorRiddle = false
            },
            inputSection(newValue) {
                this.errorSection = false
                if (newValue && newValue != '' && 
                    this.currentPoemSections < this.poemSections) {
                    // this.temporarySection = newValue
                    this.poemSectionsObject[this.currentPoemSections] = newValue //editing existing section
                }
            },
            inputQuestion(newValue) {
                this.errorQuestion = false
            },
            currentPoemSections(newValue) {
                if (newValue) {
                    // this.inputSection = this.poemSectionsObject[newValue]
                }
            },
            editableData: {
                immediate: true,
                handler(newValue){
                    this.textareaContent = newValue.content
                    // this.type = newValue.typeName
                    if (newValue && newValue.type) {
                            this.inputPublishedPlaceholder = new Date(newValue.type[0].published).toDateString().slice(4)
                        if (newValue.typeName === 'book') {
                            this.inputTitle = newValue.type[0].title
                            this.inputAbout = newValue.type[0].about
                            this.inputAuthor = newValue.type[0].author
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'poem') {
                            this.inputTitle = newValue.type[0].title
                            this.inputAbout = newValue.type[0].about
                            this.poemSectionsObject = newValue.type[0].sections
                            this.poemSections = newValue.type[0].sections.length
                            this.inputAuthor = newValue.type[0].author
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'riddle') {
                            this.inputAuthor = newValue.type[0].author
                            this.inputRiddle = newValue.type[0].riddle
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'activity') {
                            this.inputDescription = newValue.type[0].description
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        } else if (newValue.typeName === 'question') {
                            this.inputQuestion = newValue.type[0].question
                            // this.inputEditPublished = new Date(newValue.type[0].published).toDateString().slice(4)
                        }
                    }
                },
                deep: true
            },
        },
        created () {
            this.flatPickrConfig = {
                minDate: 'today',
                dateFormat: 'F j, Y',
                altFormat: "F j, Y",
                maxDate: new Date().fp_incr(14)
                // defaultDate:['1990-01-01']
            }
        },
        computed: {
            computedBody(){
                return this.edit
            },
            computedRiddle(){
                return this.type === 'riddle' ? true : 
                    this.edit && this.editableData.typeName === 'riddle' ? true : false
            },
            disablePrevious(){
                return this.currentPoemSections -1  < 0 ? true : false
            },
            disableNext(){
                return this.inputSection && this.inputSection.trim() != '' ?
                    false : this.currentPoemSections +1  > this.poemSections ? true : false
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
            datePicked(data){
                this.inputPublished = data
            },
            clearAlert(){
                this.alertMessage = ''
                this.alertError = false
            },
            popSection(){

                if (this.currentPoemSections === this.poemSections) {
                    this.inputSection = ''
                } else {
                    this.poemSectionsObject.pop(this.currentPoemSections)
                    if (this.currentPoemSections - 1 >= 0) {
                        this.currentPoemSections -= 1
                    }

                    if (this.poemSections - 1 >= 0) {
                        this.poemSections -= 1
                    }
                }
                if (this.currentPoemSections === 0 && this.poemSections === 0) {
                    this.inputSection = ''
                }
            },
            pushSection(){
                if (this.inputSection && this.inputSection.trim() != '') {
                    if (this.poemSections === this.currentPoemSections) {
                        this.poemSectionsObject.push(this.inputSection)
                    } else {
                        this.poemSectionsObject[this.currentPoemSections] = this.inputSection
                    }
                    this.inputSection = ''
                    this.poemSections++
                    this.currentPoemSections++
                }else {
                    this.errorSection = true
                }
            },
            clickedPrevious(){
                if (this.currentPoemSections === this.poemSections) {
                    this.temporarySection = this.inputSection
                }
                
                this.currentPoemSections-=1
                this.inputSection = this.poemSectionsObject[this.currentPoemSections]
            },
            clickedNext(){
                if (this.currentPoemSections === this.poemSections) {
                    this.poemSections+=1
                    this.currentPoemSections+=1
                    this.poemSectionsObject.push(this.inputSection)
                } else{
                    this.currentPoemSections+=1
                }

                if (this.currentPoemSections === this.poemSections) {
                    this.inputSection = this.temporarySection
                }else {
                    this.inputSection = this.poemSectionsObject[this.currentPoemSections]
                }
                
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
                            author: this.inputAuthor,
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
                            if (this.inputSection && this.inputSection.trim() != '') {
                                sections.push(this.inputSection.trim())
                            } else {
                                sections.push(this.temporarySection)
                            }
                        }

                        data = {
                            title: this.inputTitle,
                            author: this.inputAuthor,
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
                    } else {
                        data = {
                            question: this.inputQuestion,
                            published: this.inputPublished,
                            file: this.inputFile,
                        }
                    }
                } else if (this.type === 'riddle') {
                     if (this.inputRiddle.trim() === '') {
                        this.alertError=true
                        this.alertMessage='please enter the riddle'
                        this.errorRiddle = true
                        error = true
                    } else {
                        data = {
                            author: this.inputAuthor,
                            riddle: this.inputRiddle,
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

                // if (condition) {
                    
                // }

                // console.log('data before clicked edit', data)

                if (!error) {
                    if (this.edit) {
                        data['type'] = this.editableData.typeName
                        data['postId'] = this.editableData.id
                        data['content'] = this.textareaContent
                        data['contentFile'] = this.inputContent
                        this.$emit('clickedEdit', data)
                    } else {
                        this.$emit('clickedCreate', data)
                    }
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
                this.inputFile = {}

                this.textareaContent = ''
                this.$emit('mainModalDisappear')
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>