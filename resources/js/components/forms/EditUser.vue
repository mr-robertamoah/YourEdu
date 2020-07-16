<template> 
    <main-modal v-if="showForm" @mainModalDisappear='closeModal'>   
        <template slot="loading" v-if="computedLoading">
            loading...
        </template>
        <template slot="main" >
            <welcome-form>
                <template slot="input">
                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="first name" 
                            :bottomBorder="true"
                            v-model="inputFirstName"></text-input>
                    </div>
                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="last name"  
                            :bottomBorder="true"
                            v-model="inputLastName"></text-input>
                    </div>
                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="other names"  
                            :bottomBorder="true"
                            v-model="inputOtherNames"></text-input>
                    </div>
                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="email"  
                            :bottomBorder="true"
                            v-model="inputEmail"></text-input>
                    </div>

                    <main-list @listItemSelected='selectGender'
                        :multiple='false'
                        :selectedItem="inputGender"
                        select='choose your gender'
                        :itemList="['male','female']"
                    ></main-list>

                    <main-list @listItemSelected='selectSecret'
                        v-if="computedShowSecret"
                        :multiple='false'
                        :loading='loading'
                        select='choose a secret question to answer'
                        :itemList="secretQuestions"
                    ></main-list>

                    <div class="form-edit" v-if="showAnswerText">
                        <text-input type="text" 
                            placeholder="your answer"  
                            :bottomBorder="true"
                            v-model="inputAnswer"></text-input>
                    </div>

                    <main-list @listItemSelected='selectAnswer'
                        :multiple='false'
                        v-if="showAnswerList"
                        select='choose a your answer'
                        :itemList="possibleAnswers"
                    ></main-list>
                </template>
                <template slot="buttons">
                    <post-button buttonText='save' buttonStyle='success'
                        @click="clickedCreate"
                    ></post-button>
                </template>
            </welcome-form>
        </template>
    </main-modal>
</template>

<script>
import WelcomeForm from '../welcome/WelcomeForm'
import MainModal from '../MainModal'
import PostButton from '../PostButton'
import MainList from '../MainList'
import TextInput from '../TextInput'
import { mapActions, mapGetters } from 'vuex'

    export default {
        props: {
            showForm: {
                type: Boolean,
                default: false
            },
            fireAction: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                inputEmail: null,
                inputFirstName: null,
                inputLastName: null,
                inputGender: null,
                inputOtherNames: null,
                inputAnswer: null,
                mainLoading: false,
                showAnswerList: false,
                mainLoading: false,
                showAnswerText: false,
                secretQuestionId: null,
                possibleAnswers: [],
            }
        },
        components: {
            WelcomeForm,
            TextInput,
            MainList,
            PostButton,
            MainModal,
        },
        computed: {
            computedShowSecret(){
                return this.getUser.hasOwnProperty('secret_answer') && 
                    !this.getUser.secret_answer && 
                    this['miscellaneous/getSecretQuestions'].length > 0 ? 
                    true : false
            },
            secretQuestions(){
                return this['miscellaneous/getSecretQuestions']
            },
            computedLoading(){
                return this['miscellaneous/getLoadingContent'] ? 
                    this['miscellaneous/getLoadingContent'] : this.mainLoading
            },
            loading(){
                return this['miscellaneous/getLoadingContent']
            },
            ...mapGetters(['miscellaneous/getLoadingContent',
                'miscellaneous/getSecretQuestions',
                'miscellaneous/getSecretQuestionAnswers',
                'getUser'
            ])
        },
        watch: {
            fireAction(newValue) {
                if (newValue) {
                    this.getSecretQuestions()
                }
            },
            showForm(newValue){
                this.inputFirstName = this.inputFirstName ? this.inputFirstName : this.getUser.first_name
                this.inputLastName = this.inputLastName ? this.inputLastName : this.getUser.last_name
                this.inputOtherNames = this.inputOtherNames ? this.inputOtherNames : this.getUser.other_names
                this.inputGender = this.inputGender ? this.inputGender : this.getUser.gender
                this.inputEmail = this.inputEmail ? this.inputEmail : this.getUser.email
            },
        },
        beforeUpdate () {

        },
        methods: {
            closeModal(){
                this.$emit('mainModalDisappear')
            },
            getSecretQuestions(){
                this['miscellaneous/getSecret']()
            },
            clickedCreate(){    
                this.mainLoading = true

                let inputFirstName = this.inputFirstName === null ? this.inputFirstName : this.inputFirstName.trim()
                let inputLastName = this.inputLastName === null ? this.inputLastName : this.inputLastName.trim()
                let inputOtherNames = this.inputOtherNames === null ? this.inputOtherNames : this.inputOtherNames.trim()
                let inputEmail = this.inputemail === null ? this.inputemail : this.inputEmail.trim()
                let inputGender = null
                let inputAnswer =  null
                let inputQuestionId = null
                let data = {}
                
                if (this.inputGender) {
                    inputGender = this.inputGender === 'male'? 'MALE' : 'FEMALE'
                }
                
                if (this.getUser.secret_answer) {
                    inputAnswer = this.inputAnswer.trim()
                    inputQuestionId = this.inputQuestionId
                }

                data = {
                    first_name: inputFirstName,
                    last_name: inputLastName,
                    other_names: inputOtherNames,
                    email: inputEmail,
                    gender: inputGender,
                    answer: inputAnswer,
                    question_id: inputQuestionId,
                }

                this.editUser({
                    user_id: this.getUser.id,
                    data
                })

                this.mainLoading = false
            },
            selectAnswer(item){
                this.inputAnswer = item
            },
            selectGender(item){
                this.inputGender = item
            },
            selectSecret(item){
                this.inputAnswer = ''
                let qa = this['miscellaneous/getSecretQuestionAnswers']
                let a = []
                a = qa.filter(function(el){
                    return el.question === item
                })
                
                console.log(a[0].possible_answers)
                if (a[0].possible_answers.length > 0) {
                    this.showAnswerList = true
                    this.showAnswerText = false

                    this.secretQuestionId = a[0].id
                    this.possibleAnswers = a[0].possible_answers
                } else {
                    this.showAnswerText = true
                    this.showAnswerList = false
                }
            },
            ...mapActions(['miscellaneous/getSecret','editUser'])
        },
    }
</script>

<style lang="scss" scoped>

</style>