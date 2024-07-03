<template>
    <div class="post-create-section">
        <div class="activity-post">
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
                <div class="clear"
                    :class="{clearActive:clearActive}"
                    @click="clickedClearActive"
                    v-if="computedPost"
                >
                    clear
                </div>
                <div class="post-top-main">
                    <div class="icons"
                        @click.prevent="clickFile('attachment')"
                        v-if="mainPreviewData.length === 0"
                        title="attach a subject, grade, program, etc"
                        :class="{active: uploadType === 'attachment'}"
                    >
                        <font-awesome-icon
                            :icon="['fa','paperclip']"
                        ></font-awesome-icon>
                    </div>
                    <div class="icons"
                        @click.prevent="clickFile('image')"
                        v-if="mainPreviewData.length === 0"
                        title="upload an image"
                        :class="{active: uploadType === 'image'}"
                    >
                        <font-awesome-icon
                            :icon="['fa','file-image']"
                        ></font-awesome-icon>
                    </div>
                    <div class="icons"
                        @click.prevent="clickFile('video')"
                        v-if="mainPreviewData.length === 0"
                        title="upload a video"
                        :class="{active: uploadType === 'video'}"
                    >
                        <font-awesome-icon
                            :icon="['fa','file-video']"
                        ></font-awesome-icon>
                    </div>
                    <div class="icons"
                        @click.prevent="clickFile('audio')"
                        v-if="mainPreviewData.length === 0"
                        title="upload an audio"
                        :class="{active: uploadType === 'audio'}"
                    >
                        <font-awesome-icon
                            :icon="['fa','file-audio']"
                        ></font-awesome-icon>
                    </div>
                    <div class="upload-type" 
                        v-if="uploadType !== '' && uploadType !== 'attachment'">
                        <div class="upload-item" @click="clickedUpload">
                            <div class="icon">
                                <font-awesome-icon
                                    :icon="['fa','upload']"
                                ></font-awesome-icon>
                            </div>
                            <div class="icon-text">
                                {{`upload ${uploadType} file`}}
                            </div>
                        </div>
                        <div class="upload-item" 
                            @click="clickedMediaCapture('image')"
                            v-if="uploadType === 'image'"
                        >
                            <div class="icon">
                                <font-awesome-icon
                                    :icon="['fa','camera']"
                                ></font-awesome-icon>
                            </div>
                            <div class="icon-text">
                                capture image
                            </div>
                        </div>
                        <div class="upload-item" 
                            @click="clickedMediaCapture('video')"
                            v-if="uploadType === 'video'"
                        >
                            <div class="icon">
                                <font-awesome-icon
                                    :icon="['fa','video']"
                                ></font-awesome-icon>
                            </div>
                            <div class="icon-text">
                                record video
                            </div>
                        </div>
                        <div class="upload-item" 
                            @click="clickedMediaCapture('audio')"
                            v-if="uploadType === 'audio'"
                        >
                            <div class="icon">
                                <font-awesome-icon
                                    :icon="['fa','microphone']"
                                ></font-awesome-icon>
                            </div>
                            <div class="icon-text">
                                record audio
                            </div>
                        </div>
                    </div>
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
                            :smallType="true"
                            :profile="profile"
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
                        v-if="showTextareaContent"
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
                class="hidden">
            <div class="post-bottom">
                <post-button buttonText="L" 
                    @click="formType = 'lesson'"
                    :active="formType === 'lesson'"
                    titleText="share a lesson"></post-button>
                <post-button buttonText="B" 
                    @click="formType = 'book'"
                    :active="formType === 'book'"
                    titleText="post a book"></post-button>
                <post-button buttonText="R" 
                    @click="formType = 'riddle'"
                    titleText="post a riddle"
                    :active="formType === 'riddle'"
                ></post-button>
                <post-button buttonText="P" 
                    @click="formType = 'poem'"
                    :active="formType === 'poem'"
                    titleText="post a poem"></post-button>
                <post-button buttonText="Q" 
                    @click="formType = 'question'"
                    :active="formType === 'question'"
                    titleText="post a question"></post-button>
                <post-button buttonText="A" 
                    @click="formType = 'activity'"
                    :active="formType === 'activity'"
                    titleText="post an activity"></post-button>
            </div>
            <div class="attachments-section">
                <attachment-badge
                    v-for="(attachment,index) in postAttachments"
                    :key="index"
                    :hasClose="true"
                    :attachment="attachment.data"
                    :type="attachment.type"
                    @removeAttachment="clickedRemoveAttachment"
                ></attachment-badge>
            </div>
            <!--for adding attachments -->
            <just-fade>
                <template slot="transition" v-if="showAddAttachments">
                    <post-attachment
                        :show="showAddAttachments"
                        :hasSelect="true"
                        @clickedAttachmentSelection="attachmentSelected"
                        @hidePostAttachment="hidePostAttachment"
                    ></post-attachment>
                </template>
            </just-fade>
        </div>
        <!-- creating posts types -->
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
        <!-- creating post lessons -->
        <create-lesson
            :show="showLessonModal"
            @createLessonDisappear="closeCreateLesson"
            @clickedCreate="clickedCreateLesson"
        ></create-lesson>
        <!-- capturing media -->
        <media-capture
            :show="showMediaCapture"
            v-if="showMediaCapture"
            :type="uploadType"
            @closeMediaCapture="closeMediaCapture"
            @sendFile="receiveMediaCapture"
        ></media-capture>
    </div>
</template>

<script>
import PostButton from '../components/PostButton.vue'
import FilePreview from '../components/FilePreview.vue'
import CreatePost from '../components/forms/CreatePost.vue'
import CreateLesson from '../components/forms/CreateLesson.vue'
import JustFade from '../components/transitions/JustFade.vue'
import FadeRight from '../components/transitions/FadeRight.vue'
import ProfilePicture from '../components/profile/ProfilePicture.vue'
import MainTextarea from '../components/MainTextarea.vue'
import MainPreview from '../components/MainPreview.vue'
import AttachmentBadge from '../components/AttachmentBadge.vue'
import ProfileBar from '../components/profile/ProfileBar.vue'
import ValidationError from '../components/ValidationError.vue'
import MediaCapture from '../components/MediaCapture.vue'
import PostAttachment from '../components/PostAttachment.vue'
import {files, strings} from '../services/helpers'
import {mapActions, mapGetters} from 'vuex'
import { useRoute } from 'vue-router'

    export default {
        components: {
            
            MediaCapture,
            ValidationError,
            ProfileBar,
            AttachmentBadge,
            MainPreview,
            MainTextarea,
            PostAttachment,
            ProfilePicture,
            FadeRight,
            JustFade,
            CreateLesson,
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
                postAttachments: [],
                hasPreviewFile: false,
                showProfiles: false,
                previewType: '',
                uploadType: '',
                account: '',
                accountId: null,
                showLessonModal: false,
                showPostButton: false,
                showAddAttachments: false,
                showTextareaContent: true,
                showSpecifyUpload: false,
                showMediaCapture: false,
            }
        },
        watch: {
            formType: {
                immediate: true,
                handler(newValue){ //for showing modal of and removing preview of post types
                    this.showPostButton = false
                    if (newValue === 'lesson') {
                        this.showLessonModal = true
                    } else if (newValue && newValue !== '') {
                        this.showTextareaContent = true
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
                            this.alertSuccess = false
                            this.alertError = false
                        }, 3000);
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['getProfiles', 'getActiveProfile', 
                'profile/getActiveProfile']),
            computedPost(){
                return this.textareaContent !== '' || this.file || this.showPostButton || 
                    (this.mainPreviewData && this.mainPreviewData.hasOwnProperty('published')) ?
                    true : false
            },
            computedRoute() {
                return useRoute()
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
                    this.previewType === 'lesson' || 
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
                        return this.mainPreviewData.body
                    } else if (this.previewType === 'activity') {
                        return this.mainPreviewData.description
                    } else if (this.previewType === 'riddle') {
                        return this.mainPreviewData.body
                    } else if (this.previewType === 'lesson') {
                        return strings.trim(this.mainPreviewData.description)
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
            ...mapActions(['profile/createPost']),
            attachmentSelected(data){
                let index = this.postAttachments.findIndex(attachment=>{
                    return attachment.type === data.type &&
                        attachment.data.id ===  data.data.id
                })
                if (index === -1) {
                    this.postAttachments.push(data)
                }
            },
            clickedRemoveAttachment(data){
                let index = this.postAttachments.findIndex(attachment=>{
                    return attachment.type === data.type &&
                        attachment.data.id ===  data.data.id
                })
                if (index > -1) {
                    this.postAttachments.splice(index,1)
                }
            },
            hidePostAttachment(){
                this.showAddAttachments = false
                this.uploadType = ''
            },
            clickedClearActive(){
                this.showPostButton = false
                this.showAddAttachments = false
                this.formType = ''
                this.textareaContent = ''
                this.file = null
                this.account = ''
                this.accountId = ''
                this.showPostButton = false
                this.postAttachments = []
                this.removePreview()
            },
            clickedProfile(data){
                this.accountId = data.accountId
                this.account = data.account
                this.showProfiles = false
                this.createPost()
            },
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
                if (this.computedProfiles.length > 1 && this.computedRoute.name === "home") {
                    this.showProfiles = true
                    setTimeout(() => {
                        this.showProfiles = false
                    }, 5000);
                } else if (this.computedProfiles.length === 1 && this.computedRoute.name === "home") {
                    this.account = this.computedProfiles[0].account
                    this.accountId = this.computedProfiles[0].accountId
                    this.createPost()
                } else {
                    this.accountId = null
                    this.createPost()
                }
            },
            clickedCreateLesson(data){
                this.mainPreviewData = data
                this.previewType = this.formType
                this.showMainPreview = true
                this.hasPreviewFile = true
                this.showPostButton = true
                this.textareaContent = ''
                this.showTextareaContent = false
            },
            closeCreateLesson(){
                this.showLessonModal = false
                this.formType = ''
            },
            async createPost(){
                this.loading = true
                let formData = new FormData

                if (this.file) {
                    formData.append('file', this.file)
                } else {
                    if (this.previewType !== '') {
                        formData.append('type', this.previewType)
                        if (this.mainPreviewData && this.mainPreviewData.file &&
                            this.mainPreviewData.file.length > 0 || 
                            this.previewType !== 'lesson') {
                            this.mainPreviewData.file.forEach(file=>{
                                if (file) formData.append('typeFiles[]', file)
                            })
                        }
                    } 
                    
                    if (this.previewType === 'book') {
                        formData.append('title', this.mainPreviewData.title)
                        formData.append('authorNames', this.mainPreviewData.authorNames)
                        formData.append('about', this.mainPreviewData.about)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'poem') {
                        formData.append('title', this.mainPreviewData.title)
                        formData.append('authorNames', this.mainPreviewData.authorNames)
                        formData.append('about', this.mainPreviewData.about)
                        formData.append('sections', JSON.stringify(this.mainPreviewData.sections))
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'riddle') {
                        formData.append('authorNames', this.mainPreviewData.authorNames)
                        formData.append('body', this.mainPreviewData.body)
                        formData.append('scoreOver', this.mainPreviewData.scoreOver)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'question') {
                        formData.append('body', this.mainPreviewData.body)
                        formData.append('scoreOver', this.mainPreviewData.scoreOver)
                        if (this.mainPreviewData.hasOwnProperty('possibleAnswers')) { 
                            formData.append('possibleAnswers', JSON.stringify(this.mainPreviewData.possibleAnswers))
                        }
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'activity') {
                        formData.append('description', this.mainPreviewData.description)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'lesson') {
                        formData.append('description', this.mainPreviewData.description)
                        formData.append('title', this.mainPreviewData.title)
                        formData.append('ageGroup', this.mainPreviewData.ageGroup)
                        if (this.mainPreviewData.file && this.mainPreviewData.file.length) {
                            this.mainPreviewData.file.forEach(file=>{
                                if (file) {
                                    formData.append('typeFiles[]', file)
                                }
                            })
                        }
                    }
                }

                //check if there are attachments
                if (this.postAttachments.length) {
                    formData.append('attachments', JSON.stringify(this.postAttachments.map(attachment=>{
                        return {
                            attachable: attachment.type.slice(0,attachment.type.length - 1),
                            attachableId: attachment.data.id
                        }
                    })))
                }

                if (this.accountId) {
                    formData.append('account', this.account)
                    formData.append('accountId', this.accountId)
                } else {
                    formData.append('account', this.computedRoute.params.account)
                    formData.append('accountId', this.computedRoute.params.accountId)
                }

                formData.append('content', this.textareaContent)                
                
                let response = await this['profile/createPost']({formData,
                    where: this.computedRoute.name})

                this.loading = false
                if (response !== 'unsuccessful') {
                    this.alertSuccess = true
                    this.alertMessage = 'post created successfully'
                } else {
                    this.alertError = true
                    this.alertMessage = 'post creation failed'
                }
                this.clickedClearActive()
            },
            clearValidation(){
                this.error = ''
                this.showValidation = false
            },
            clickFile(data) {
                this.clickedButton = data
                this.showPreview = false
                this.uploadType = data

                if (data === 'attachment') {
                    this.showAddAttachments = !this.showAddAttachments
                    return 
                }
                this.showSpecifyUpload = true
                setTimeout(() => {
                    this.uploadType = ''
                    this.showSpecifyUpload = false
                }, 4000);
            },
            clickedUpload(){
                if (this.uploadType === 'image') {
                    this.$refs.file.setAttribute('accept', this.imageType)
                } else if (this.uploadType === 'video') {
                    this.$refs.file.setAttribute('accept', this.videoType)
                } else if (this.uploadType === 'audio') {
                    this.$refs.file.setAttribute('accept', this.audioType)
                }

                this.$refs.file.click()
            },
            clickedMediaCapture(data){
                this.showMediaCapture = true
            },
            receiveMediaCapture(file){
                if (file.type.includes('image')) {
                    this.file = new File([file],'my_picture.png',{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('video')) {
                    this.file = new File([file],'my_video.webm',{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('audio')) {
                    this.file = new File([file],'my_audio.mp3',{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
            },
            closeMediaCapture(){
                this.uploadType = ''
                this.showMediaCapture = false
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
                
                this.uploadType = ''
                event.target.value = ''
            },
        },
    }
</script>

<style lang="scss" scoped>
    
    .post-create-section{
        min-height: 200px;
        border: 1px solid dimgrey;
        padding: 10px;
        margin: 10px auto;
        border-right: 2px solid rgb(105, 105, 105);
        background-color: inherit;

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
                justify-content: space-between;
                align-items: center;
                font-size: 20px;

                .clear{
                    padding: 5px;
                    color: gray;
                    cursor: pointer;
                    transition: all 1s ease;
                    max-width: 20%;
                    font-size: 14px;

                    &:hover{
                        box-shadow: 0 0 3px gray;
                    }
                }

                .post-top-main{
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    margin-left: auto;

                    .icons{
                        margin-right: 10px;
                        cursor: pointer;
                    }

                    .active{
                        color: green;
                    }

                    .upload-type{
                        position: absolute;
                        top: 10px;
                        z-index: 1;
                        right: 10px;
                        background: mintcream;
                        padding: 10px;
                        border-radius: 10px;
                        width: 160px;

                        .upload-item{
                            display: inline-flex;
                            justify-content: space-evenly;
                            align-items: center;
                            width: 150px;
                            font-size: 14px;
                            padding: 5px;
                            flex-wrap: wrap;
                        }
                    }
                }

            }

            .post-middle{
                width: 100%;
                margin: 5px 0;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;

                .post-picture{
                    width: 75px;
                    height: 75px;
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

            .attachments-section{
                width: 100%;
                display: flex;
                justify-content: flex-end;
                align-items: center;
                flex-wrap: wrap;
            }

            .profiles{
                position: absolute;
                width: 200px;
                right: 0;
                top: 20px;
                text-align: justify;
                font-size: 14px;
                color: black;
                z-index: 5;
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){

    .post-create-section{
        .activity-post{

            .post-middle{

                .post-picture{
                    width: 60px;
                    height: 60px;
                }
            }
        }
    }
}


@media screen and (max-width:800px){
    .post-create-section{
        .activity-post{

            .post-middle{
                .post-picture{
                    width: 60px;
                    height: 60px;
                }
            }
        }
    }
}


@media screen and (max-width:400px){
    .post-create-section{
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