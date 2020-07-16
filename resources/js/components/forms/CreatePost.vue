<template>
     <main-modal v-if="showForm" @mainModalDisappear='closeModal'>   
        <template slot="loading" v-if="computedLoading">
            loading...
        </template>
        <template slot="main" v-else>
            <welcome-form>
                <template slot="input">
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
                            placeholder="your riddle" 
                            :error="errorRiddle" 
                            :bottomBorder="true"
                            v-model="inputRiddle"></text-textarea>
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
                    <div class="form-edit" v-if="computedAbout">
                        <text-textarea type="text" 
                            placeholder="about"  
                            :bottomBorder="true"
                            v-model="inputAbout"></text-textarea>
                    </div>

                    <div class="form-edit" v-if="computedPublished">
                        <date-picker 
                            :flatPickrConfig="flatPickrConfig"
                            :bottomBorder="true"
                            placeHolder="select a date to publish"
                            v-model="inputPublished"></date-picker>
                    </div>

                    <file-input
                        :fileMax="2"
                        :error="errorFile"
                        :bottomBorder="true"
                        @uploadedFiles="uploadedFiles"
                    ></file-input>
                </template>

                <template slot="buttons">
                    <post-button buttonText='ok' buttonStyle='success'
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
import PostButton from '../PostButton'
    export default {
        props: {
            type: {
                type: String,
                default: ''
            },
            showForm: {
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
            TextTextarea,
            WelcomeForm,
            DatePicker,
            MainModal,
            FileInput,
            TextInput,
        },
        data() {
            return {
                flatPickrConfig: {},
                inputTitle: '',
                inputAbout: '',
                inputDescription: '',
                inputAuthor: '',
                inputPublished: '',
                inputQuestion: '',
                inputSection: '',
                inputRiddle: '',
                inputFile: {},
                errorRiddle: false,
                errorFile: false,
                errorTitle: false,
                errorDescription: false, // to make inputs show error
                errorQuesiton: false,
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
            },
            inputQuestion(newValue) {
                this.errorQuesiton = false
            },
            currentPoemSections(newValue) {
                this.inputSection = this.poemSectionsObject[newValue]
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
            computedRiddle(){
                return this.type === 'riddle' ? true : false
            },
            disablePrevious(){
                return this.currentPoemSections -1  < 0 ? true : false
            },
            disableNext(){
                return this.currentPoemSections +1  > this.poemSections ? true : false
            },
            computedTitle() {
                return this.type === 'book' || this.type === 'poem' ? true : false 
            },
            computedAuthor() {
                return this.type === 'book' || this.type === 'poem' || 
                    this.type === 'riddle' ? true : false
            },
            computedAbout() {
                return this.type === 'book' || this.type === 'poem' ? true : false 
            },
            computedDescription() {
                return this.type === 'activity' ? true : false
            },
            computedPublished() {
                return true
            },
            computedSection() {
                return this.type === 'poem' ? true : false
            },
            computedQuestion() {
                return this.type === 'question' ? true : false
            },
            computedLoading(){
                return this.loading ? true : false
            },
        },
        methods: {
            pushSection(){
                if (this.inputSection.trim() != '') {
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
                    this.currentPoemSections-=1
            },
            clickedNext(){
                    this.currentPoemSections+=1
            },
            uploadedFiles(data){
                this.inputFile = data
            },
            clickedCreate(){
                let data = {}
                let error = false

                if (this.type === 'book') {
                    if (this.inputTitle.trim() === '') {
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
                        this.errorTitle = true
                        error = true
                    } else if (this.inputSection.trim() === '') {
                        this.errorSection = true
                        error = true
                    } else {
                        data = {
                            title: this.inputTitle,
                            about: this.inputAbout,
                            about: this.inputAbout,
                            published: this.inputPublished,
                            description: this.inputDescription,
                            author: this.inputAuthor,
                        }
                    }
                } else if (this.type === 'question') {
                    if (this.inputTitle.trim() === '') {
                        this.errorTitle = true
                        error = true
                    } else {
                        data = {
                            title: this.inputTitle,
                            about: this.inputAbout,
                            about: this.inputAbout,
                            published: this.inputPublished,
                            description: this.inputDescription,
                            author: this.inputAuthor,
                        }
                    }
                } else if (this.type === 'riddle') {
                     if (this.inputRiddle.trim() === '') {
                        this.errorRiddle = true
                        error = true
                    } else {
                        data = {
                            title: this.inputTitle,
                            about: this.inputAbout,
                            about: this.inputAbout,
                            published: this.inputPublished,
                            description: this.inputDescription,
                            author: this.inputAuthor,
                        }
                    }
                } else if (this.type === 'activity') {
                     if (this.inputDescription.trim() === '') {
                        this.errorDescription = true
                        error = true
                    } else if (this.inputFile.lenght < 1) {
                        this.errorFile = true
                        setTimeout(function(){
                            this.errorFile = true
                        }, 2000)
                        error = true
                    } else {

                    }
                }

                if (!error) {
                    data = {
                        title: this.inputTitle,
                        about: this.inputAbout,
                        about: this.inputAbout,
                        published: this.inputPublished,
                        description: this.inputDescription,
                        author: this.inputAuthor,
                    }

                    this.$emit('clickedCreate', data)
                }
            },
            closeModal(){
                this.$emit('mainModalDisappear')
            }
        },
    }
</script>

<style lang="scss" scoped>
$border-color-main : rgba(22, 233, 205, 1);

    .create-post-wrappper{

    }
</style>