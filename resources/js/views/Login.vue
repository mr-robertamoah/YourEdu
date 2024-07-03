<template>
    <div class="bg-red-600">
        <app-nav></app-nav>
        
        <login-register-outline title="Login" 
            :theErrorMessage='errorMessage'
            @clear="clearErrorMessage"
        >
            <template v-slot:login-controls>
                
                <div class="form-group form-section" v-if="signinWith==='username'">
                    <label for="login-username">username *</label>
                    <text-input placeholder="your username"
                        v-model="username"
                        :error='usernameError'
                    ></text-input>
                </div>
                <div class="form-group form-section" v-else-if="signinWith==='email'">
                    <label for="login-email">email *</label>
                    <text-input placeholder='your email'
                        v-model="email"
                        :error='emailError'
                    ></text-input>
                </div>
                <div class="form-group form-section">
                    <label for="login-password">password *</label>
                    <text-input placeholder="your password"
                        :inputType="passwordType" 
                        v-model="password"
                        :error='passwordError'
                        @iconChange='passwordIconChange' 
                        :title='passwordTitle'
                        :icon='passwordIcon'
                        :prepend='true'
                    ></text-input>
                </div>

                <button class="btn login-btn my-auto"
                    @click.prevent="sendLoginDetails()"    
                >login</button>
                
                <div class="login-with">
                    <button class="btn login-type-btn" 
                        @click.prevent="setToEmail"
                        v-if="signinWith==='username'"
                        >use email</button>
                    <button class="btn login-type-btn" 
                        @click.prevent="setToUsername"
                        v-if="signinWith==='email'"
                    >use username</button>
                </div>
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

    export default {
        data() {
            return {
                signinWith: 'username',
                username: '',
                email: '',
                password: '',
                errorMessage: '',
                passwordTitle: 'show password',
                passwordType: 'password',
                emailError: false,
                usernameError: false,
                passwordError: false,
                passwordIcon: ['fa','eye']
            }
        },
        components:{
            TextInput,
            LoginRegisterOutline,
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
            },
        },
        methods:{
            ...mapActions(['login','getFollowings','getFollowers']),
            clearErrorMessage(){
                this.errorMessage = ''
            },
            clearCredentials(){
                this.username = ''
                this.email = ''
                this.password = ''
            },
            setToEmail(){
                this.signinWith = 'email'
                this.clearCredentials()
            },
            setToUsername(){
                this.signinWith = 'username'
                this.clearCredentials()
            },
            async sendLoginDetails(){
                
                if (this.signinWith ==='username' && this.username === '') {
                    this.errorMessage = 'Please enter your username in the field.'
                    this.usernameError = true
                } else if (this.signinWith ==='email' && this.email === '') {
                    this.errorMessage = 'Please enter your email in the field.'
                    this.emailError = true
                }else if (this.password ==='') {
                    this.errorMessage = 'Please enter your password in the field.'
                    this.passwordError = true
                }else if (this.username.trim().length < 8) {
                    this.errorMessage = 'Please your username is too short.'
                    this.usernameError = true
                }else if (this.password.trim().length < 8) {
                    this.errorMessage = 'Please your password is too short.'
                    this.emailError = true
                }else{
                    //only send login data based on users choice
                    let loginCredentials = {}
                    if (this.signinWith ==='username') {
                        loginCredentials = {
                            password: this.password.trim(),
                            username: this.username.trim(),
                            loginOrRegister: true
                        }
                    }
                    if (this.signinWith ==='email') {
                        loginCredentials = {
                            password: this.password.trim(),
                            email: this.email.trim(),
                            loginOrRegister: true
                        }
                    }
                    loginCredentials.redirectTo = useRoute().query.redirectTo

                    const res = await this.login(loginCredentials)

                    if (res.status && res.token) {
                        useRouter().push( useRoute().query.redirectTo || '/')
                        Echo.options.auth.headers.Authorization = `Bearer ${data.token}`
                    }

                    this.followersAndFollowings()
                }
            },
            followersAndFollowings(){
                this.getFollowers()
                setTimeout(() => {
                    this.getFollowings()
                }, 2000);
            },
            passwordIconChange(){
                if (this.passwordIcon[1] === 'eye') {
                    this.passwordIcon[1] = 'eye-slash'
                    this.passwordType = 'text'
                    this.passwordTitle = 'hide password'
                } else {
                    this.passwordIcon[1] = 'eye'
                    this.passwordType = 'password'
                    this.passwordTitle = 'show password'
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>