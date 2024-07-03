<template>
    <div>
        <app-nav></app-nav>

        <login-register-outline title="Register"
            :theErrorMessage='errorMessage' 
            @clear="clearErrorMessage"
        >
            <template v-slot:login-controls>
                <div class="section-one" 
                    v-show="registrationSection===null || registrationSection===1">
                    <div class="form-group form-section text">
                        <label for="login-username">username *</label>
                        <text-input placeholder="your username"
                            v-model="username"
                            :error='usernameError'
                        ></text-input>
                    </div>
                    <div class="form-group form-section">
                        <label for="login-email">email</label>
                        <text-input placeholder="your email"
                            v-model="email"
                            :error='emailError'
                        ></text-input>
                    </div>
                    <div class="form-group form-section">
                        <label for="login-password">password *</label>
                        <text-input placeholder="your password"
                            :textInput="passwordType" 
                            v-model="password"
                            :error='passwordError'
                            @iconChange='passwordIconChange' 
                            :title='passwordTitle'
                            :icon='passwordIcon'
                            :prepend='true'
                        ></text-input>
                    </div>
                    <div class="form-group form-section">
                        <label for="login-confirmation">password confirmation *</label>
                        <text-input placeholder="your password confirmation"
                            :textInput="passwordConfirmationType" 
                            v-model="passwordConfirmation"
                            :error='passwordConfirmationError'
                            @iconChange='passwordIconChange' 
                            :title='passwordConfirmationTitle'
                            :icon='passwordIcon'
                            :prepend='true'
                        ></text-input>
                    </div>
                </div>
                <div class="section-two" v-show="registrationSection===2">
                    <div class="form-group form-section">
                        <label for="login-first-name">first name</label>
                        <text-input placeholder="your first name"
                            v-model="firstName"
                        ></text-input>
                    </div>
                    <div class="form-group form-section">
                        <label for="login-last-name">last name</label>
                        <text-input placeholder="your last name"
                            v-model="lastName"
                        ></text-input>
                    </div>
                    <div class="form-group form-section">
                        <label for="login-other-names">other names</label>
                        <text-input placeholder="your other names"
                            v-model="otherNames"
                        ></text-input>
                    </div>
                    <div class="form-group form-section register-datepicker-section">
                        <label for="register-datepicker">date of birth</label>
                        <flat-pickr id="register-datepicker" :config="config"
                            v-model="dob" class=" form-control mb-2"></flat-pickr>
                    </div>
                    <button class="btn login-btn mb-3" 
                        @click.prevent="sendRegistrationDetails">register</button>
                </div>
                <button class="btn login-btn" 
                    @click.prevent="nextSection">{{sectionButtonText}}</button>
            </template>
            <template v-slot:title-icon>
                <font-awesome-icon :icon="['fas','sign-in-alt']"/>
            </template>
        </login-register-outline>
        
    </div>
</template>

<script>
import { useRoute, useRouter } from 'vue-router';
import LoginRegisterOutline from '../components/LoginRegisterOutline.vue'
import TextInput from '../components/TextInput.vue'
import { mapActions, mapGetters } from "vuex";
// import 'flatpickr/dist/flatpickr.css';

    export default {
        data() {
            return {
                username:'',
                email:'',
                password:'',
                passwordConfirmation:'',
                firstName:'',
                lastName:'',
                otherNames:'',
                registrationSection: null,
                sectionButtonText:'next',
                dob:'',
                errorMessage:'',
                config:{},
                passwordTitle: 'show password',
                passwordConfirmationTitle: 'show password',
                passwordType: 'password',
                passwordConfirmationType: 'password',
                emailError: false,
                usernameError: false,
                passwordError: false,
                passwordConfirmationError: false,
                passwordIcon: ['fa','eye'],
            }
        },
        components:{
            LoginRegisterOutline, 
            TextInput,
        },
        watch: {
            username(){
                this.usernameError = false
            },
            email(){
                this.emailError = false
            },
            password(){
                this.passwordError = false
                this.passwordConfirmationError = false
            },
        },
        created(){
            this.config = {
                maxDate: 'today',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: "F j, Y",
                defaultDate:['1990-01-01']
            }
        },
        methods:{
            clearErrorMessage(){
                this.errorMessage = ''
            },
            clearCredentials(){
                this.username = ''
                this.email = ''
                this.password = ''
                this.passwordConfirmation = ''
                this.firstName = ''
                this.lastName = ''
                this.otherNames = ''
                this.dod=''
            },
            nextSection(){
                let registrationSection = this.registrationSection
                this.errorMessage = ''
                if (registrationSection === null || registrationSection === 1) {
                    
                    if (this.username === '') {
                        this.errorMessage = 'Please, enter your username in the field.'
                        this.usernameError = true
                    } else if(this.username.length < 8){
                        this.errorMessage = 'Please, your username should have at least 8 characters.'
                        this.usernameError = true
                    }else if(this.password === ""){
                        this.errorMessage = 'Please, enter your password in the field.'
                        this.passwordError = true
                    }else if(this.passwordConfirmation === ""){
                        this.errorMessage = 'Please, confirm your password in the field.'
                        this.passwordConfirmationError = true
                    }else if(this.password !== this.passwordConfirmation){
                        this.errorMessage = 'Please, password confirmation must match password.'
                        this.passwordError = true
                        this.passwordConfirmationError = true
                    }else{
                        this.registrationSection = 2
                        this.sectionButtonText = 'previous'
                    }
                    
                } else if (registrationSection === 2){
                    this.registrationSection = 1
                    this.sectionButtonText = 'next'
                }
            },
            async sendRegistrationDetails(){

                let registrationCredentials = {
                    username: this.username.trim(),
                    email: this.email.trim(),
                    password: this.password.trim(),
                    password_confirmation: this.passwordConfirmation.trim(),
                    first_name: this.firstName.trim(),
                    last_name: this.lastName.trim(),
                    other_names: this.otherNames.trim(),
                    dob: this.dob.trim(),
                }
                
                let response = await this.register(registrationCredentials)

                if (!response.status) return

                if (response.token) useRouter().push( useRoute().query.redirectTo || '/welcome')

                this.clearCredentials()
            },
            ...mapActions(['register']),
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
            }
        },
    }
</script>

<style lang="scss">
$sectionMainBackground: rgba(22, 233, 205, 0.65);

    .form-control:disabled, .form-control[readonly] {
        border: 2px solid rgba(22, 233, 205, 1);
        border-radius: 10px;
        background-color: white;
        opacity: 1;
    }
</style>