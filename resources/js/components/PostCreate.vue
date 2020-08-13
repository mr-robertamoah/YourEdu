<template>
    <div class="section">
        <div class="activity-post">
            <div class="clear"
                :class="{clearActive:clearActive}"
                @click="clickedClearActive"
                v-if="computedPost"
            >
                clear
            </div>
            <div class="messaging">
                <div class="loading" v-if="loading">
                    <pulse-loader :loading="loading"></pulse-loader>
                </div>
                <div class="alert-message" 
                    v-if="alertMessage.length"
                    :class="{success:alertSuccess,error:alertError}"
                >
                    {{alertMessage}}
                </div>
            </div>
            <div class="post-top">
                <div class="icons"
                    @click.prevent="clickFile('image')"
                    v-if="mainPreviewData.length === 0 "
                >
                    <font-awesome-icon
                        :icon="['fa','file-image']"
                    ></font-awesome-icon>
                </div>
                <div class="icons"
                    @click.prevent="clickFile('video')"
                    v-if="mainPreviewData.length === 0"
                >
                    <font-awesome-icon
                        :icon="['fa','file-video']"
                    ></font-awesome-icon>
                </div>
                <div class="icons"
                    @click.prevent="clickFile('audio')"
                    v-if="mainPreviewData.length === 0"
                >
                    <font-awesome-icon
                        :icon="['fa','file-audio']"
                    ></font-awesome-icon>
                </div>
                <fade-right>
                    <template slot="transition">
                        <post-button 
                            buttonText="post"
                            @click="clickedCreatePost"
                            v-if="computedPost"
                        ></post-button>
                    </template>
                </fade-right>
                <div class="profiles"
                    v-if="showProfiles"
                >
                    <span>
                        post as
                    </span>
                    <div :key="key" v-for="(profile,key) in computedProfiles">
                        <profile-bar
                            :name="profile.name"
                            :type="profile.params.account"
                            :smallType="true"
                            :routeParams="profile.params"
                            :navigate="false"
                            @clickedProfile="clickedProfile"
                        ></profile-bar>
                    </div>
                </div>
            </div>
            <div class=" post-middle">
                <div class="post-picture">
                    <profile-picture>
                        <template slot="image">
                            <img :src="computedProfileUrl">
                        </template>
                    </profile-picture>
                </div>
                <div class="post-textarea">
                    <main-textarea 
                        :textPlaceholder="textPlaceholder"
                        v-model="textareaContent"
                    ></main-textarea>
                    <just-fade>
                        <template slot="transition" v-if="showPreview">
                            <file-preview
                                :show="showPreview"
                                :file="file"
                                @removeFile="removeFile"
                            ></file-preview>
                        </template>
                    </just-fade>
                    <just-fade>
                        <template slot="transition" v-if="showMainPreview">
                            <main-preview
                                @clickedBadge="removePreview"
                                :file='computedPreviewFile'
                                :body='computedPreviewBody'
                                :options='computedPreviewOptions'
                                :hasScore='computedHasScore'
                                :scoredOver='computedScore'
                                :title='computedPreviewTitle'
                                :heading='computedPreviewHeading'
                                :hasFile='hasPreviewFile'
                            >
                            </main-preview>
                        </template>
                    </just-fade>
                </div>
                <div class="error"  v-if="showValidation">
                    <validation-error
                        :errorString="error"
                        @clearValidation="clearValidation"
                    ></validation-error>
                </div>
            </div>
            <input type="file" ref="file" 
                @change="fileChange"
                class="d-none">
            <div class="post-bottom">
                <post-button buttonText="B" 
                    @click="formType = 'book'"
                    titleText="post a book"></post-button>
                <post-button buttonText="R" 
                    @click="formType = 'riddle'"
                    titleText="post a riddle"
                ></post-button>
                <post-button buttonText="P" 
                    @click="formType = 'poem'"
                    titleText="post a poem"></post-button>
                <post-button buttonText="Q" 
                    @click="formType = 'question'"
                    titleText="post a question"></post-button>
                <post-button buttonText="A" 
                    @click="formType = 'activity'"
                    :active="true"
                    titleText="post an activity"></post-button>
            </div>
        </div>

        <just-fade>
            <template slot="transition" v-if="showModal">
                <create-post
                    :type="formType"
                    :showForm="showModal"
                    @mainModalDisappear="closeCreatePost"
                    @clickedCreate="clickedCreate"
                ></create-post>
            </template>
        </just-fade>
    </div>
</template>

<script>
import PostButton from '../components/PostButton'
import FilePreview from '../components/FilePreview'
import CreatePost from '../components/forms/CreatePost'
import JustFade from '../components/transitions/JustFade'
import FadeRight from '../components/transitions/FadeRight'
import ProfilePicture from '../components/profile/ProfilePicture'
import MainTextarea from '../components/MainTextarea'
import MainPreview from '../components/MainPreview'
import ProfileBar from '../components/profile/ProfileBar'
import ValidationError from '../components/ValidationError'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import {files} from '../services/helpers'
import {mapActions, mapGetters} from 'vuex'

    export default {
        components: {
            PulseLoader,
            ValidationError,
            ProfileBar,
            MainPreview,
            MainTextarea,
            ProfilePicture,
            FadeRight,
            JustFade,
            CreatePost,
            FilePreview,
            PostButton,
        },
        data() {
            return {
                alertMessage: '',
                alertSuccess: false,
                alertError: false,
                clearActive: false,
                loading: false,
                textareaContent: '',
                error: '',
                imageType: 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp',
                videoType: 'video/webm,video/mp4,video/ogg',
                audioType: 'audio/mpeg,audio/ogg,audio/wav',
                file: null,
                clickedButton: '',
                showValidation: false,
                showPreview: false,
                showModal: false,
                formType: '',
                files : null,
                textPlaceholder: "do you have anything in mind?",
                showMainPreview: false,
                mainPreviewData: [],
                hasPreviewFile: false,
                showProfiles: false,
                previewType: '',
                account: '',
                account_id: null,
            }
        },
        watch: {
            formType: {
                immediate: true,
                handler(newValue){ //for showing modal of and removing preview of post types
                    if (newValue && newValue != '') {
                        this.showModal = true
                        this.removeFile()
                        this.removePreview()
                    }
                }
            },
            alertMessage:{
                immediate: true,
                handler(value){
                    if (value.length) {
                        setTimeout(() => {
                            this.alertMessage = ''
                        }, 3000);
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['getProfiles', 'getActiveProfile', 
                'profile/getActiveProfile']),
            computedPost(){
                return this.textareaContent != '' || this.file || 
                    (this.mainPreviewData && this.mainPreviewData.hasOwnProperty('published')) ?
                    true : false
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedProfileUrl(){
                return this['profile/getActiveProfile'] ? 
                    this['profile/getActiveProfile'].url : 
                    this['getActiveProfile'] ?
                    this['getActiveProfile'].url : ''
            },
            computedFile() {
                return typeof this.file === File ? this.file : new File(["foo"], "foo.txt",{
                    type: 'text/plain'
                }) 
            },
            computedPreviewFile(){
                return this.mainPreviewData && this.mainPreviewData.file ?
                    this.mainPreviewData.file[0] : {}
            }, 
            computedPreviewTitle(){
                return this.mainPreviewData ?
                    this.previewType === 'book' || 
                    this.previewType === 'poem' ?
                    this.mainPreviewData.title : '' : ''
            },       
            computedPreviewHeading(){
                return this.mainPreviewData ?
                    this.previewType : ''
            },      
            computedPreviewBody(){
                if (this.mainPreviewData) {
                    if (this.previewType === 'book') {
                        return this.mainPreviewData.about
                    } else if (this.previewType === 'poem') {
                        return this.mainPreviewData.sections[0]
                    } else if (this.previewType === 'question') {
                        return this.mainPreviewData.question
                    } else if (this.previewType === 'activity') {
                        return this.mainPreviewData.description
                    } else if (this.previewType === 'riddle') {
                        return this.mainPreviewData.riddle
                    }
                }
            },
            computedScore(){
                if (this.computedHasScore) {
                    return this.mainPreviewData.score
                }
            },
            computedHasScore(){
                if (this.mainPreviewData && (this.previewType == 'question' ||
                    this.previewType === 'riddle')) {
                    return true
                }
                return false
            },
            computedPreviewOptions(){
                if (this.mainPreviewData && this.previewType == 'question' &&
                    this.mainPreviewData.hasOwnProperty('possibleAnswers')) {
                    return this.mainPreviewData.possibleAnswers
                }
            },
            computedPreviewAuthor(){ //will have to adjust this when author search and add author
                return this.mainPreviewData ?
                    this.mainPreviewData.author : ''
            }, 
        },
        methods: {
            clickedClearActive(){
                this.textareaContent = ''
                this.file = null
                this.account = ''
                this.account_id = ''
                this.removePreview()
            },
            clickedProfile(data){
                this.account_id = data.accountId
                this.account = data.account
                this.showProfiles = false
                this.createPost()
            },
            ...mapActions(['profile/createPost']),
            removePreview(){
                this.showMainPreview = false
                this.hasPreviewFile = false
                this.previewType = ''
                this.mainPreviewData = []
            },
            clickedCreate(data){
                this.mainPreviewData = data
                this.previewType = this.formType
                this.showMainPreview = true
                this.hasPreviewFile = true
                this.closeCreatePost()
            },
            closeCreatePost(){
                this.showModal = false
                this.formType= ''
            },
            removeFile(){
                this.file = null
                this.clickedButton = ''
                this.showPreview = false
            },
            clickedCreatePost(){
                if (this.computedProfiles.length > 1 && this.$route.name === "home") {
                    this.showProfiles = true
                    setTimeout(() => {
                        this.showProfiles = false
                    }, 5000);
                } else if (this.computedProfiles.length === 1 && this.$route.name === "home") {
                    this.account = this.computedProfiles[0].params.account_type
                    this.account_id = this.computedProfiles[0].params.account_id
                    this.createPost()
                } else {
                    this.account_id = null
                    this.createPost()
                }
            },
            async createPost(){
                this.loading = true
                let fileType = ''
                let formData = new FormData

                // if (!this.showPreview) {
                if (this.file) {
                    formData.append('file', this.file)
                    formData.append('fileType', files.fileType(this.file))
                } else {
                    console.log('enters')
                    if (this.previewType != '') {
                        formData.append('type', this.previewType)
                    }
                    
                    if (this.previewType === 'book') {
                        formData.append('title', this.mainPreviewData.title)
                        formData.append('author', this.mainPreviewData.author)
                        formData.append('about', this.mainPreviewData.about)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'poem') {
                        formData.append('title', this.mainPreviewData.title)
                        formData.append('author', this.mainPreviewData.author)
                        formData.append('about', this.mainPreviewData.about)
                        formData.append('sections', JSON.stringify(this.mainPreviewData.sections))
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'riddle') {
                        formData.append('author', this.mainPreviewData.author)
                        formData.append('riddle', this.mainPreviewData.riddle)
                        formData.append('score', this.mainPreviewData.score)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'question') {
                        formData.append('question', this.mainPreviewData.question)
                        formData.append('score', this.mainPreviewData.score)
                        if (this.mainPreviewData.hasOwnProperty('possibleAnswers')) {                        formData.append('riddle', this.mainPreviewData.riddle)
                            formData.append('possibleAnswers', JSON.stringify(this.mainPreviewData.possibleAnswers))
                        }
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'activity') {
                        formData.append('description', this.mainPreviewData.description)
                        formData.append('published', this.mainPreviewData.published)
                    }

                    if (this.mainPreviewData && this.mainPreviewData.file &&
                        this.mainPreviewData.file.length > 0) {
                        formData.append('previewFile', this.mainPreviewData.file[0])
                        formData.append('previewFileType', files.fileType(this.mainPreviewData.file[0]))
                    }
                }

                if (this.account_id) {
                    formData.append('account', this.account)
                    formData.append('account_id', this.account_id)
                } else {
                    formData.append('account', this.$route.params.account)
                    formData.append('account_id', this.$route.params.accountId)
                }

                formData.append('content', this.textareaContent)                
                
                let response = await this['profile/createPost'](formData)

                this.loading = false
                if (response !== 'unsuccessful') {
                    this.alertMessage = 'post created successfully'
                    this.alertSuccess = true
                    this.clickedClearActive()
                } else {
                    this.alertMessage = 'post creation failed'
                    this.alertError = true
                }
            },
            clearValidation(){
                this.error = ''
                this.showValidation = false
            },
            clickFile(data) {
                this.clickedButton = data
                this.showPreview = false

                if (data === 'image') {
                    this.$refs.file.setAttribute('accept', this.imageType)
                } else if (data === 'video') {
                    this.$refs.file.setAttribute('accept', this.videoType)
                } else if (data === 'audio') {
                    this.$refs.file.setAttribute('accept', this.audioType)
                }

                this.$refs.file.click()
            },
            fileChange(event) {
                let file = event.target.files[0]
                this.error = ''
                this.showValidation = false
                this.showPreview = true
                if (this.clickedButton === 'image') {
                    if (this.imageType.includes(file.type)) {
                        this.file = file
                    } else {
                        this.showValidation = true
                        this.error = `this file is either not an ${this.clickedButton} or has an unsupported format`
                    }
                } else if (this.clickedButton === 'video') {
                    if (this.videoType.includes(file.type)) {
                        this.file = file
                    } else {
                        this.showValidation = true
                        this.error = `this file is either not an ${this.clickedButton} or has an unsupported format`
                    }
                } else if (this.clickedButton === 'audio') {
                    if (this.audioType.includes(file.type)) {
                        this.file = file
                    } else {
                        this.showValidation = true
                        this.error = `this file is either not an ${this.clickedButton} or has an unsupported format`
                    }
                }
                
                event.target.value = ''
            },
        },
    }
</script>

<style lang="scss" scoped>
    
    .section{
        min-height: 200px;
        border: 1px solid dimgrey;
        padding: 10px;
        margin: 10px auto;
        border-right: 2px solid rgb(105, 105, 105);
        background-color: inherit;

        .clear{
            padding: 5px;
            color: gray;
            cursor: pointer;
            transition: all 1s ease;
            margin: 5px;
            width: fit-content;
            font-size: 14px;

            &:hover{
                box-shadow: 0 0 3px gray;
            }
        }

        .clearActive{
            box-shadow: 0 0 3px gray;
            transition: all 1s ease;
        }

        .messaging{
            width: 100%;
            text-align: center;
            background-color: gainsboro;
            font-size: 14px;

            .success{
                background-color: rgba(0, 128, 0, 0.328);
                color: green;
            }

            .error{
                background-color: rgba(255, 0, 0, 0.308);
                color: red;
            }
        }

        .activity-post{
            position: relative;

            .post-top{
                text-align: end;
                padding: 5px;
                color: gray;
                display: flex;
                justify-content: flex-end;
                align-items: center;
                font-size: 20px;

                .icons{
                    margin-right: 10px;
                    cursor: pointer;
                }
            }

            .post-middle{
                width: 100%;
                margin: 5px 0;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;

                .post-picture{
                    width: 90px;
                    height: 90px;
                    border-radius: 100%;
                }

                .post-textarea{
                    width: 80%;
                }
                
                .error{
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    width: 100%;
                    padding: 5px;
                }
            }

            .post-bottom{
                display: flex;
                justify-content: flex-end;
                width: 100%;
                flex-wrap: wrap;

                button{
                    margin: 10px 5px;
                }
            }

            .profiles{
                position: absolute;
                width: 200px;
                right: 0;
                top: 20px;
                text-align: justify;
                font-size: 14px;
                color: black;
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){

    .section{
        .activity-post{

            .post-middle{

                .post-picture{
                    width: 70px;
                    height: 70px;
                }
            }
        }
    }
}


@media screen and (max-width:800px){
    .section{
        .activity-post{

            .post-middle{
                .post-picture{
                    width: 70px;
                    height: 70px;
                }
            }
        }
    }
}


@media screen and (max-width:400px){
    .section{
        .activity-post{
            
            .post-middle{
                .post-picture{
                    width: 45px;
                    height: 45px;
                }

                .post-textarea{
                    width: 70%;
                }
            }
        }
    }
}
</style>