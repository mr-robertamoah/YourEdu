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
                        class="create-attachment-wrapper"
                    >
                        <template slot="input">
                            <div class="form-section">

                                <text-input
                                    :bottomBorder="true"
                                    :error="errorName"
                                    :placeholder="`${type} name*`"
                                    v-model="inputName"
                                ></text-input>
                            </div>
                            <slide-up-group
                                v-if="computedShowattachment"
                                class="search-section"
                            >
                                <template slot="transition">
                                    <div class="search-attachment"
                                        v-for="attachment in attachments"
                                        :key="attachment.id"
                                        @click="clickedattachment(attachment)"
                                    >
                                        <div class="attachment">{{attachment.name}}</div>
                                        <p
                                            v-if="attachment.description && attachment.description.length"
                                        >{{`description: ${attachment.description}`}}</p>
                                        <p
                                            v-if="attachment.aliases.length"
                                        >{{`aliases: ${attachment.aliases}`}}</p>
                                    </div>
                                </template>
                            </slide-up-group>
                            <p class="search-section-message"
                                v-if="computedShowattachment"
                            >click a {{type}} to add as an alias</p>
                            <div class="add-attachment"
                                @click="clickedAddattachment"
                                v-if="actionType === 'alias'"
                            >
                                add {{type}}
                            </div>
                            <div class="form-section">
                                
                                <text-textarea
                                    :bottomBorder="true"
                                    :placeholder="`description of the ${type}`"
                                    v-model="inputDescription"
                                ></text-textarea>
                            </div>
                            <div class="form-section" v-if="showRationale">
                                
                                <text-textarea
                                    :bottomBorder="true"
                                    :placeholder="`rationale for this ${type}`"
                                    v-model="inputRationale"
                                ></text-textarea>
                            </div>
                            <div class="form-section" v-if="showAliases">
                                
                                <text-array
                                    :show="true"
                                    :bottomBorder="true"
                                    :propsArray="aliasesArray"
                                    :example="type === 'course' ? 'comm skills, communication' : 'mining, mn eng.'"
                                    :placeholder="`aliases for ${type} name`"
                                    @textArrayData="textArrayData"
                                ></text-array>
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
            type: {
                type: String,
                default: 'program'
            },
        },
        components: {
            ProfileBar,
            
            WelcomeForm,
            TextTextarea,
            PostButton,
            TextArray,
            TextInput,
            MainModal,
            SlideUpGroup,
        },
        data() {
            return {
                errorName: false,
                showProfiles: false,
                loading: false,
                inputName: '',
                inputDescription: '',
                inputRationale: '',
                alertError: false,
                alertSuccess: false,
                showRationale: true,
                showAliases: true,
                alertMessage: '',
                buttonText: 'create',
                actionType: 'attachment',
                title: 'create a attachment',
                //textarray
                aliasesArray: [], //array for textarray
                temporaryInputAliases: '',
                inputAliases: '',
                aliasesCurrentPosition: null,
                aliasesArrayPositions: null,
                //
                attachmentRequestnumber: 0,
                attachments: [],
                attachment: {},
            }
        },
        watch: {
            type: {
                immediate: true,
                handler(newValue){
                    this.title = `create a ${newValue}`
                }
            },
            inputName(newValue,oldValue) {
                // console.log('new',newValue);
                if (newValue.length) {
                    this.errorName = false
                    if (newValue.trim().length && this.actionType === 'attachment') {
                        this.debouncedGetAttachments()
                    }
                    if (newValue.length < oldValue.length) {
                        this.attachmentRequestnumber = 0
                    }
                }
            },
            actionType(newValue){
                if (newValue === 'attachment') {
                    this.buttonText = 'create'
                    this.showRationale = true
                    this.showAliases = true
                    this.title = 'create a attachment'
                    if (this.inputName.length) {
                        this.getAttachments()
                    }
                } else {
                    this.buttonText = 'add alias'
                    this.showRationale = false
                    this.showAliases = false
                    this.title = `create alias of ${this.attachment.name}`
                }
            },
            attachments:{
                handler(newValue){
                    if (!newValue || !newValue.length) {
                        this.attachment = {}
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
            computedShowattachment(){
                return this.actionType === 'attachment' && this.attachments.length 
                    && this.inputName.length
            },
            computedHasNoMore(){ //check if there are more
                return this.attachmentRequestnumber === 0 ||
                    (this.attachmentRequestnumber > 0 && this.attachments.length > 0)
            },
        },
        methods: {
            ...mapActions(['profile/createProgram','profile/searchPrograms',
                'profile/createProgramAlias','profile/createCourse','profile/searchCourses',
                'profile/createCourseAlias']),
            clickedCreate(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            clickedAddattachment(){
                this.actionType = 'attachment'
            },
            clickedProfile(who){
                this.showProfiles = false
                this.createAttachment(who)
            },
            debouncedGetAttachments: _.debounce(function(){this.getAttachments()},100),
            async getAttachments(){
                if (this.computedHasNoMore) {
                    let response
                    if (this.type === 'program') {
                        response = await this['profile/searchPrograms'](this.inputName)
                    } else if (this.type === 'course') {
                        response = await this['profile/searchCourses'](this.inputName)
                    }

                    if (response.status) {
                        let attachments = response.data.programs ? response.data.programs :
                            response.data.courses ? response.data.courses : []
                        this.attachmentRequestnumber = 0
                        this.attachments = attachments.length ? 
                            attachments.map(attachment=>{
                                return {
                                    id: attachment.id,
                                    name: attachment.name,
                                    description: attachment.description,
                                    aliases: attachment.aliases ? 
                                    _.take(attachment.aliases.map(alias=>{
                                        return ` ${alias.name}`
                                    }),4).toString() : []
                                }
                            }) : []
                        
                    } else {
                        console.log(response.message)
                    }
                }
            },
            clickedattachment(attachment){
                this.actionType = 'alias'
                this.attachment = attachment
            },
            async createAttachment(who) {
                if (!this.inputName || !this.inputName.length) {
                    this.errorName = true
                    this.alertError = true
                    this.alertMessage = `please enter the ${this.actionType} name`
                    return
                }
                this.loading = true
                let data = {},
                    response = null
                data.account = who.account
                data.accountId = who.accountId
                data.name = this.inputName ? this.inputName.trim() : this.inputName
                data.description = this.inputDescription ? this.inputDescription.trim() : this.inputDescription

                if (this.actionType === 'attachment') {

                    if (who.account === 'learner' || who.account === 'parent') {
                        this.alertError = true
                        this.alertMessage = `a ${who.account} cannot create a attachment. Try creating an alias of a attachment instead or contact us.`
                        this.loading = false
                        return
                    }
                    
                    data.rationale = this.inputRationale ? this.inputRationale.trim() : this.inputRationale

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

                    if (this.type === 'program') {
                        response = await this['profile/createProgram'](data)
                    } else if (this.type === 'course') {
                        response = await this['profile/createCourse'](data)
                    }
                } else {
                    
                    if (this.type === 'program') {
                        response = await this['profile/createProgramAlias']({
                        data, attachmentId: this.attachment.id})
                    } else if (this.type === 'course') {
                        response = await this['profile/createCourseAlias']({
                        data, attachmentId: this.attachment.id})
                    }
                }

                this.loading = false
                if (response.status) {
                    this.attachmentRequestnumber = 0
                    this.alertSuccess = true
                    this.alertMessage = `successfully created ${data.name} ${this.actionType}`
                    this.$emit('attachmentCreated', {
                        attachment: response.data.program ? response.data.program :
                            response.data.course ? response.data.course : [],
                        type: this.type
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
                this.$emit('closeCreateAttachment')
            },
            clearData(){
                this.inputName = ''
                this.inputDescription = ''
                this.inputRationale = ''
                this.aliasesArray = []
                this.temporaryInputAliases = ''
                this.aliasesCurrentPosition = null
                this.aliasesArrayPositions = null
                this.inputAliases = ''
                this.actionType = 'attachment'
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

    .create-attachment-wrapper{

        .form-section{
            margin-bottom: 10px;
        }

        .search-section{
            margin-top: -10px;

            .search-attachment{
                padding: 5px;
                cursor: pointer;
                background-color: whitesmoke;

                &:hover{
                    background-color: white;
                }

                .attachment{
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

        .add-attachment{
            font-size: 14px;
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