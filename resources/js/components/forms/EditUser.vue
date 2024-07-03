<template> 
    <just-fade>
        <template slot="transition" v-if="showForm">
            <main-modal :show="showForm" 
                @mainModalDisappear='closeModal'
                @clearAlert='clearModalAlert'
                :loading="computedLoading"
                heading="edit user information"
                :alertMessage="modalAlertMessage"
                :requests="false"
                :mainOther="false"
                :alertError="modalAlertError"
                :alertSuccess="modalAlertSuccess"
            >   
                <template slot="loading">
                    <sync-loader :loading="computedLoading"></sync-loader>
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

                            <div class="form-edit">
                                <date-picker 
                                    :flatPickrConfig="{
                                                maxDate: 'today',
                                                dateFormat: 'F j, Y',
                                                altFormat: 'F j, Y',
                                            }"
                                    :placeholder="computedDob"
                                    @datePicked="dobPicked"
                                    :bottomBorder="true"
                                ></date-picker>
                            </div>

                            <main-list @listItemSelected='selectGender'
                                :multiple='false'
                                :value="inputGender"
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
                                select='choose your answer'
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
    </just-fade>
</template>

<script>
import DatePicker from '../DatePicker.vue'
import PostButton from '../PostButton.vue'
import MainList from '../MainList.vue'
import TextInput from '../TextInput.vue'
import { mapActions, mapGetters } from 'vuex'
import { dates } from '../../services/helpers'

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
                inputDob: null,
                mainLoading: false,
                showAnswerList: false,
                showAnswerText: false,
                secretQuestionId: null,
                possibleAnswers: [],
                modalAlertMessage: '',
                modalAlertError: false,
                modalAlertSuccess: false,
            }
        },
        components: {
            TextInput,
            
            MainList,
            PostButton,
            DatePicker,
        },
        computed: {
            computedShowSecret(){
                //have to change this part so you get secret questions even if you have not answered yours
                return this.getUser.secret_answer ? false :
                    this.getUser.hasOwnProperty('secret_answer') && 
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
            computedDob(){
                if (this.getUser) {
                    if (dates.toDate(new Date(this.getUser.created_at)) !== 
                        dates.toDate(new Date(this.getUser.dob))) {
                        return dates.dateReadable(this.getUser.dob).slice(4)
                    } 
                }
                return 'your date of birth'
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
        methods: {
            clearModalAlert(){
                this.modalAlertMessage = ''
                this.modalAlertError = false
                this.modalAlertSuccess = false
            },
            dobPicked(date){
                this.inputDob = date
            },
            closeModal(){
                this.$emit('mainModalDisappear')
            },
            getSecretQuestions(){
                this['miscellaneous/getSecret']()
            },
            async clickedCreate(){    
                this.mainLoading = true
                let error = false

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
                
                if (!this.getUser.secret_answer && this.inputDob) {
                    data['question_id'] = this.inputDob.trim()
                    if (this.inputAnswer && this.inputAnswer.length &&
                        this.inputAnswer.trim() === '') {
                        data['answer'] = this.inputAnswer.trim()
                    } else {
                        this.modalAlertMessage = 'please answer the question you selected'
                    }
                    
                }

                if (error) {
                    
                } else {
                    data = {
                        first_name: inputFirstName,
                        last_name: inputLastName,
                        other_names: inputOtherNames,
                        email: inputEmail,
                        gender: inputGender,
                    }

                    if (this.inputDob && this.inputDob !== this.computedDob) {
                        data['dob'] = this.inputDob.trim()
                    }

                    let response = await this.editUser({
                        user_id: this.getUser.id,
                        data
                    })

                    this.mainLoading = false
                    if (response === 'successful') {
                        this.modalAlertSuccess = true
                        this.modalAlertError = false
                        this.modalAlertMessage = 'update was successful'
                    } else {
                        this.modalAlertSuccess = false
                        this.modalAlertError = true
                        this.modalAlertMessage = 'update was unsuccessful'
                    }
                }
                
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
                
                console.log(a[0].possibleAnswers)
                if (a[0].possibleAnswers.length > 0) {
                    this.showAnswerList = true
                    this.showAnswerText = false

                    this.secretQuestionId = a[0].id
                    this.possibleAnswers = a[0].possibleAnswers
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