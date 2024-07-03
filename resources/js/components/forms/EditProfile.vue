<template>
    <main-modal v-if="showForm" @mainModalDisappear='closeModal'
         :loading="computedLoading"
         :alertMessage="computedMsg"
         :alertError="computedMsg && computedMsg.includes('unsuccessful')"
         :alertSuccess="computedMsg && !computedMsg.includes('unsuccessful')"
         @clearAlert="clearMsg"
    >   
        <template slot="loading">
            loading...
        </template>
        <template slot="main" >
            <template
                v-if="showAlert">
                <auto-alert
                    :message="alertMsg"
                    :success="successMsg"
                    :danger="dangerMsg"
                    @hideAlert="alertDisappear"
                ></auto-alert>
            </template>
            <welcome-form>
                <template slot="input">
                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="name" 
                            :bottomBorder="true"
                            v-model="inputName"></text-input>
                    </div>

                    <div class="form-edit">
                        <text-textarea
                            placeholder="about your profile"  
                            :bottomBorder="true"
                            v-model="inputAbout"></text-textarea>
                    </div>

                    <div class="form-edit">
                        <text-textarea
                            placeholder="interests"  
                            :bottomBorder="true"
                            v-model="inputInterests"></text-textarea>
                    </div>

                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="company"  
                            :bottomBorder="true"
                            v-model="inputCompany"></text-input>
                    </div>

                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="occupation"  
                            :bottomBorder="true"
                            v-model="inputOccupation"></text-input>
                    </div>

                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="location"  
                            :bottomBorder="true"
                            v-model="inputLocation"></text-input>
                    </div>

                    <div class="form-edit">
                        <text-input type="text" 
                            placeholder="address"  
                            :bottomBorder="true"
                            v-model="inputAddress"></text-input>
                    </div>
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
import AutoAlert from '../AutoAlert.vue'
import PostButton from '../PostButton.vue'
import MainList from '../MainList.vue'
import TextInput from '../TextInput.vue'
import TextTextarea from '../TextTextarea.vue'
import { mapActions, mapGetters } from 'vuex'
import { useRoute } from 'vue-router'

    export default {
        props: {
            showForm: {
                type: Boolean,
                default: false
            },
        },
        watch: {
            showForm: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.inputName = this.inputName ? this.inputName : 
                            this['profile/getProfile'].name != '' || this['profile/getProfile'].name != null ?
                            this['profile/getProfile'].name  : 
                            this['profile/getProfile'].owner_name !='' || this['profile/getProfile'].owner_name != null ?
                            this['profile/getProfile'].owner_name : 
                            this['profile/getProfile'].user_full_name
                        this.inputCompany = this.inputCompany ? this.inputCompany : this['profile/getProfile'].company
                        this.inputAbout = this.inputAbout ? this.inputAbout : this['profile/getProfile'].about
                        this.inputInterests = this.inputInterests ? this.inputInterests : this['profile/getProfile'].interests
                        this.inputEmail = this.inputEmail ? this.inputEmail : this['profile/getProfile'].email
                        this.inputOccupation = this.inputOccupation ? this.inputOccupation : this['profile/getProfile'].occupation
                        this.inputAddress = this.inputAddress ? this.inputAddress : this['profile/getProfile'].address
                        this.inputLocation = this.inputLocation ? this.inputLocation : this['profile/getProfile'].location
                        
                        this['profile/clearMsg']()
                    }
                }
            },
        },
        data() {
            return {
                inputName: null,
                inputAbout: null,
                inputInterests: null,
                inputCompany: null,
                inputOccupation: null,
                inputLocation: null,
                inputAddress: null,
                mainLoading: false,
                showAlert: false,
                alertMsg: '',
            }
        },
        components: {
            TextTextarea,
            TextInput,
            MainList,
            PostButton,
            AutoAlert,
        },
        computed: {
            ...mapGetters(['profile/getProfile', 'profile/getMsg',]),
            computedLoading(){
                return this.mainLoading 
            },
            computedMsg(){
                return this['profile/getMsg'] ? this['profile/getMsg'] : ""
            },
            successMsg(){
                return this['profile/getMsg'] ? 
                    this['profile/getMsg'].includes('unsuccess') ? 
                    false : true : false
                     
            },
            dangerMsg(){
                return this['profile/getMsg'] ? this['profile/getMsg'].includes('unsuccess') ? 
                    true : false : false
                     
            },
        },
        methods: {
            clearMsg(){
                this['profile/clearMsg']()
            },
            alertDisappear(){
                this.showAlert = false
                this.alertMsg = ''
                this['profile/clearMsg']()
            },
            ...mapActions(['profile/updateProfile','profile/clearMsg']),
            closeModal() {
                this.$emit('closeModal')
            },
            clickedCreate() {
                this.mainLoading = true

                let inputName = this.inputName === null ? this.inputName : this.inputName.trim()
                let inputAbout = this.inputAbout === null ? this.inputAbout : this.inputAbout.trim()
                let inputInterests = this.inputInterests === null ? this.inputInterests : this.inputInterests.trim()
                let inputCompany = this.inputCompany === null ? this.inputCompany : this.inputCompany.trim()
                let inputOccupation = this.inputOccupation === null ? this.inputOccupation : this.inputCompany.trim()
                let inputLocation = this.inputLocation === null ? this.inputLocation : this.inputLocation.trim()
                let inputAddress = this.inputAddress 
                let data = {}

                data = {
                    name: inputName,
                    about: inputAbout,
                    interests: inputInterests,
                    company: inputCompany,
                    occupation: inputOccupation,
                    location: inputLocation,
                    address: inputAddress,
                    account: useRoute().params.account,
                }

                this['profile/updateProfile']({
                    profile_id: this['profile/getProfile'].id,
                    data
                })                

                this.mainLoading = false
                this.showForm = true
                this.alertMsg = this['profile/getMsg']
            },
        },
    }
</script>

<style lang="scss" scoped>

    .form-edit{
        margin: 10px auto;
    }
</style>