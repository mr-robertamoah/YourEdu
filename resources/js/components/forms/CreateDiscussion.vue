<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :main="false"
                :requests="false"
                @mainModalDisappear="mainModalDisappear"
                class="modal-wrapper"
                :alertMessage="alertMessage"
                :showAlert="alertMessage.length"
                :scrollUp="scrollUp"
            >
                <template slot="main-other">
                    <h5>Create a Discussion</h5>
                    <welcome-form class="welcome-form">
                        <template slot="input">
                            <div class="section">Discussion Info</div>
                            <div class="form-edit">
                                <text-input
                                    placeholder="discussion title"  
                                    :bottomBorder="true"
                                    :error="errorTitle"
                                    v-model="title"></text-input>
                            </div>
                            <div class="form-edit">
                                <text-textarea type="text" 
                                    placeholder="discussion preamble (an introduction to the discussion)"
                                    :bottomBorder="true"
                                    v-model="preamble"></text-textarea>
                            </div>
                            <div class="form-edit">
                                <div class="main-section">
                                    <div class="label">type:</div>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('public')"
                                        :active="type === 'public'"
                                        text="public"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('private')"
                                        :active="type === 'private'"
                                        text="private"
                                    ></grey-button>
                                </div>
                                <div class="info-section">
                                    {{type === 'public' ? 
                                        'this is the default value. it means any account of the right type can join this discussion' : 
                                        'this means you will have to send out requests to accounts'}}
                                </div>
                            </div>
                            <div class="form-edit">
                                <div class="main-section">
                                    <div class="label">restricted:</div>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('yes')"
                                        :active="restricted"
                                        text="yes"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('no')"
                                        :active="!restricted"
                                        text="no"
                                    ></grey-button>
                                </div>
                                <div class="info-section">
                                    {{restricted ? 
                                        'restricted mode means all messages sent must be accepted by you before anyone can see them' : 
                                        'no means messages sent by participants will be seen by all'}}
                                </div>
                            </div>
                            <div class="form-edit">
                                <div class="main-section">
                                    <div class="label">allowed:</div>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('all')"
                                        :active="allowed === 'all'"
                                        text="all"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('learners')"
                                        :active="allowed === 'learners'"
                                        text="learners"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('parents')"
                                        :active="allowed === 'parents'"
                                        text="parents"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('facilitators')"
                                        :active="allowed === 'facilitators'"
                                        text="facilitators"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('professionals')"
                                        :active="allowed === 'professionals'"
                                        text="professionals"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedActionButton('schools')"
                                        :active="allowed === 'schools'"
                                        text="schools"
                                    ></grey-button>
                                </div>
                                <div class="info-section">
                                    {{allowed === 'all' ? 'all account types can join discussion' : 
                                        `only ${allowed} can join this discussion`}}
                                </div>
                            </div>
                            <div class="section">Discussion Attachments</div>
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
                            <post-attachment
                                :show="true"
                                :hasSelect="true"
                                :hasClose="false"
                                @clickedAttachmentSelection="attachmentSelected"
                                class="post-attachment"
                            ></post-attachment>
                            <div class="section">Discussion Resources</div>
                            <div class="info">you can upload up to three files</div>
                            <div class="files" v-if="mediaFiles.length < 3">
                                <div class="file"
                                    @click="clickedFileType('video')"
                                    :class="{active: fileType === 'video'}"
                                >video</div>
                                <div class="file"
                                    @click="clickedFileType('audio')"
                                    :class="{active: fileType === 'audio'}"
                                >audio</div>
                                <div class="file"
                                    @click="clickedFileType('picture')"
                                    :class="{active: fileType === 'picture'}"
                                >picture</div>
                            </div>
                            <div class="actions" v-if="mediaFiles.length < 3">
                                <div class="action"
                                    @click="clickedAction('upload')"
                                    v-if="fileType.length"
                                    :title="`upload ${fileType}`"
                                >
                                    <font-awesome-icon :icon="['fa','upload']"></font-awesome-icon>
                                </div>
                                <div class="action"
                                    v-if="fileType === 'video'" 
                                    @click="clickedAction('video')"
                                    title="record a video"
                                >
                                    <font-awesome-icon :icon="['fa','video']"></font-awesome-icon>
                                </div>
                                <div class="action"
                                    v-if="fileType === 'picture'" 
                                    @click="clickedAction('camera')"
                                    title="snap a picture"
                                >
                                    <font-awesome-icon :icon="['fa','camera']"></font-awesome-icon>
                                </div>
                                <div class="action"
                                    v-if="fileType === 'audio'" 
                                    @click="clickedAction('microphone')"
                                    title="record an audio"
                                >
                                    <font-awesome-icon :icon="['fa','microphone']"></font-awesome-icon>
                                </div>
                            </div>
                            <div class="file-number-error" v-if="fileNumberError">
                                you cannot upload more than three resources
                            </div>
                            <div class="media-section" v-if="mediaFiles.length">
                                <div class="media-item"
                                    v-for="(mediaItem,index) in mediaFiles"
                                    :key="index"
                                >
                                    <div class="item-type" @click="clickedFile(mediaItem)">
                                        <font-awesome-icon :icon="['fa','image']"></font-awesome-icon>
                                    </div>
                                    <div class="item-info" @click="clickedFile(mediaItem)">
                                        {{mediaItem.name}}
                                    </div>
                                    <div class="item-clear"
                                        @click="clickedBan(mediaItem)"
                                        :title="`remove ${getFileType(mediaItem.type)}`"
                                    >
                                        <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                    </div>
                                </div>
                            </div>
                            <fade-up>
                                <template slot="transition" v-if="showFilePreview">
                                    <file-preview
                                        class="file-preview"
                                        :file="activeFile"
                                        :middle="true"
                                        @removeFile="removeFile"
                                    ></file-preview>
                                </template>
                            </fade-up>
                            <input type="file" class="d-none" 
                                @change="fileChange" 
                                ref="inputfile"
                                :accept="fileAccept"
                                multiple
                            >
                        </template>
            
                        <template slot="buttons">
                            <div class="profiles"
                                v-if="showProfiles"
                            >
                                <span>
                                    create as
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
                            <post-button 
                                :buttonText="'create'" 
                                buttonStyle='success'
                                @click="clickedCreate"
                            ></post-button>
                            <post-button 
                                :buttonText="'close preview'" 
                                buttonStyle='success'
                                v-if="activeFile"
                                @click="clickedClosePreview"
                            ></post-button>
                        </template>
                    </welcome-form>

                    <!-- media capture -->
                    <media-capture
                        v-if="showMediaCapture"
                        :show="showMediaCapture"
                        :type="mediaCaptureType"
                        @closeMediaCapture="closeMediaCapture"
                        @sendFile="receiveMediaCapture"
                    ></media-capture>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import TextInput from '../TextInput';
import TextTextarea from '../TextTextarea';
import GreyButton from '../GreyButton';
import PostButton from '../PostButton';
import PostAttachment from '../PostAttachment';
import AttachmentBadge from '../AttachmentBadge';
import FadeUp from '../transitions/FadeUp';
import MediaCapture from '../MediaCapture';
import ProfileBar from '../profile/ProfileBar';
import WelcomeForm from '.././welcome/WelcomeForm';
import { mapGetters } from 'vuex';
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
        },
        components: {
            WelcomeForm,
            ProfileBar,
            MediaCapture,
            FadeUp,
            AttachmentBadge,
            PostAttachment,
            PostButton,
            GreyButton,
            TextTextarea,
            TextInput,
        },
        data() {
            return {
                title: '',
                mediaFiles: [],
                activeFile: null,
                fileType: '',
                fileAccept: '',
                showFilePreview: false,
                errorTitle: false,
                errorFile: false,
                mediaCaptureType: '',
                showMediaCapture: false,
                fileNumberError: false,
                showProfiles: false,
                scrollUp: false,
                alertMessage: '',
                allowed: 'all',
                type: 'public',
                preamble: '',
                restricted: false,
                postAttachments: [],
            }
        },
        computed: {
            ...mapGetters(['getProfiles']),
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
        },
        watch: {
            showFilePreview(newValue) {
                if (!newValue) {
                    this.activeFile = null
                }
            },
            showProfiles(newValue) {
                if (newValue) {
                    setTimeout(() => {
                        this.showProfiles = false
                    }, 4000);
                }
            },
            title(newValue, oldValue) {
                if (newValue.length && !oldValue.length) {
                    this.errorTitle = false
                }
            },
            scrollUp(newValue) {
                if (newValue) {
                    setTimeout(() => {
                        this.scrollUp = false
                        this.alertMessage = ''
                    }, 3000);
                }
            },
        },
        methods: {
            clickedRemoveAttachment(data){
                let index = this.postAttachments.findIndex(attachment=>{
                    return attachment.type === data.type &&
                        attachment.data.id ===  data.data.id
                })
                if (index > -1) {
                    this.postAttachments.splice(index,1)
                }
            },
            attachmentSelected(data){
                let index = this.postAttachments.findIndex(attachment=>{
                    return attachment.type === data.type &&
                        attachment.data.id ===  data.data.id
                })
                if (index === -1) {
                    this.postAttachments.push(data)
                }
            },
            clickedActionButton(data){
                if (data === 'all') {
                    this.allowed = data
                } else if (data === 'learners') {
                    this.allowed = data
                } else if (data === 'parents') {
                    this.allowed = data
                } else if (data === 'facilitators') {
                    this.allowed = data
                } else if (data === 'professionals') {
                    this.allowed = data
                } else if (data === 'schools') {
                    this.allowed = data
                } else if (data === 'private') {
                    this.type = data
                } else if (data === 'public') {
                    this.type = data
                } else if (data === 'yes') {
                    this.restricted = true
                } else if (data === 'no') {
                    this.restricted = false
                }
            },
            mainModalDisappear() {
                this.$emit('createDiscussionDisappear')
            },
            clickedClosePreview(){
                this.showFilePreview = false
                this.activeFile = null
            },
            clickedBan(data){
                this.showFilePreview = false
                let index = this.mediaFiles.findIndex(file=>{
                    return file.name === data.name
                })
                if (index > -1) {
                    this.mediaFiles.splice(index,1)
                }
            },
            clickedFile(data){
                this.showFilePreview = this.activeFile === data ?
                    false : true
                this.activeFile = data
            },
            receiveMediaCapture(file){
                let time = new Date().getTime(),
                    fileName
                if (file.type.includes('image')) {
                    fileName = `my_picture${time}.png`
                    this.activeFile = this.imageFile = new File([file],fileName,{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('video')) {
                    fileName = `my_video${time}.webm`
                    this.activeFile = this.videoFile = new File([file],fileName,{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('audio')) {
                    fileName = `my_audio${time}.mp3`
                    this.activeFile = this.audioFile = new File([file],fileName,{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
                this.mediaFiles.push(this.activeFile)
                this.showFilePreview = true
            },
            closeMediaCapture(){
                this.showMediaCapture = false
            },
            clickedCreate(){
                if (!this.title.length) {
                    this.errorTitle = true
                    this.scrollUp = true
                    this.alertMessage = 'the title of the discussion is needed'
                    return 
                }
                this.showProfiles = true
            },
            clickedProfile(data){
                this.showProfiles = false
                let discussion = []
                discussion['account'] = data.account
                discussion['accountId'] = data.accountId
                discussion['title'] = this.title
                discussion['type'] = this.type
                discussion['allowed'] = this.allowed
                discussion['preamble'] = this.preamble
                discussion['restricted'] = this.restricted
                discussion['files'] = this.mediaFiles
                discussion['postAttachments'] = this.postAttachments
                this.$emit('clickedCreate',discussion)
                this.mainModalDisappear()
            },
            clickedAction(data){
                if (data === 'video') {
                    this.mediaCaptureType = 'video'
                    this.showMediaCapture = true
                } else if (data === 'camera') {
                    this.mediaCaptureType = 'image'
                    this.showMediaCapture = true
                } else if (data === 'microphone') {
                    this.mediaCaptureType = 'audio'
                    this.showMediaCapture = true
                } else if (data === 'upload') {
                    this.$refs.inputfile.value = ''
                    this.$refs.inputfile.click()
                }
            },
            clickedFileType(data){
                if (data === 'picture') {
                    this.fileAccept = 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp'
                } else if (data === 'video') {
                    this.fileAccept = 'video/webm,video/mp4,video/ogg'
                } else if (data === 'audio') {
                    this.fileAccept = 'audio/mpeg,audio/ogg,audio/wav'
                }
                this.fileType = data
            },
            getFileType(data){
                if (data.includes('image')) {
                    return 'image'
                } else if (data.includes('video')) {
                    return 'video'
                } else if (data.includes('audio')) {
                    return 'audio'
                }
            },
            fileChange(){
                if (this.$refs.inputfile.files.length + this.mediaFiles.length > 3) {
                    this.fileNumberError = true
                    setTimeout(() => {
                        this.fileNumberError = false
                    }, 4000);
                    this.$refs.inputfile.value = ''
                    return
                }
                this.activeFile = this.$refs.inputfile.files[0]
                for (let i = 0; i < this.$refs.inputfile.files.length; i++) {
                    this.mediaFiles.push(this.$refs.inputfile.files[i])
                }
                this.showFilePreview = true
            },
            removeFile(){
                this.fileType = ''
                this.showFilePreview = false
                this.clickedBan(this.activeFile)
            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin info(){
    text-align: center;
    font-size: 12px;
    color: gray;
}

    .welcome-form{
        padding: 0;

        .profiles{
            position: absolute;
            width: 200px;
            left: 0;
            bottom: 30px;
            text-align: justify;
            font-size: 14px;
            color: black;
            z-index: 5;
        }

        .form-edit{

            .main-section{
                display: flex;
                justify-content: flex-start;
                align-items: flex-start;
                flex-wrap: wrap;
                width: 100%;

                .label{
                    margin-right: 10px;
                    font-size: 14px;
                    color: gray;
                }
                
                .grey-button{
                    margin: 0 10px 10px 0;
                }
            }

            .info-section{
                @include info();
                margin-bottom: 20px;
            }
        }

        .attachments-section{
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
        }

        .post-attachment{
            margin-bottom: 20px;
        }

        .section{
            font-size: 12px;
            font-weight: 500;
            border-bottom: 1px solid gray;
        }

        .info{
            @include info();
        }

        .files{
            display: inline-flex;
            justify-content: space-around;
            width: 100%;
            font-size: 14px;
            margin: 20px 0 10px;

            .file{
                padding: 5px;
                border-radius: 10px;
                background: gray;
                color: mintcream;
                cursor: pointer;

                &:hover{
                    background: green;
                    transition: all .5s ease;
                }
            }

            .active{
                background: green;
                transition: all .5s ease;
            }
        }

        .actions{
            display: inline-flex;
            margin-bottom: 20px;

            .action{
                color: gray;
                cursor: pointer;
                padding: 5px;
                margin: 0 10px 0 0;
            }
        }

        .file-number-error{
            @include info();
            color: red;
        }

        .media-section{
            margin: 10px 0;

            .media-item{
                width: 100%;
                display: inline-flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: nowrap;
                color: gray;
                cursor: pointer;
                margin-bottom: 5px;

                .item-info{
                    width: 90%;
                    font-size: 14px;
                    margin: 0 10px;
                }

                .item-clear{

                    &:hover{
                        color: red;
                        transition: all .5s ease;
                    }
                }
            }
        }

        .file-preview{
            height: 200px;
        }
    }

    h5{
        color: gray;
        text-align: center;
        margin-top: 20px;
    }
</style>