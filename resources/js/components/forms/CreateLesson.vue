<template>
    <just-fade>
        <template slot="transition">
            <main-modal
                :show="show"
                :main="false"
                :requests="false"
                @mainModalDisappear="mainModalDisappear"
                class="modal-wrapper"
                :alertMessage="alertMessage"
                :showAlert="alertMessage.length"
            >
                <template slot="main-other">
                    <h5>Create a Lesson</h5>
                    <welcome-form class="welcome-form">
                        <template slot="input">
                            <div class="section">Lesson Info</div>
                            <div class="form-edit">
                                <text-input
                                    placeholder="lesson title"  
                                    :bottomBorder="true"
                                    :error="errorTitle"
                                    v-model="lessonData.title"></text-input>
                            </div>
                            <div class="form-edit">
                                <text-textarea type="text" 
                                    placeholder="lesson description"
                                    :bottomBorder="true"
                                    v-model="lessonData.description"></text-textarea>
                            </div>
                            <div class="form-edit">
                                <text-input
                                    placeholder="age group"  
                                    :bottomBorder="true"
                                    v-model="lessonData.title"></text-input>
                            </div>
                            <div class="section">Resources</div>
                            <div class="info">you can up upload three different files for a lesson</div>
                            <div class="files">
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
                            <div class="actions">
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
                            <div class="media-section">
                                <div class="media-item" v-if="imageFile">
                                    <div class="item-type" @click="clickedFile('image')">
                                        <font-awesome-icon :icon="['fa','image']"></font-awesome-icon>
                                    </div>
                                    <div class="item-info" @click="clickedFile('image')">
                                        {{imageFile.name}}
                                    </div>
                                    <div class="item-clear"
                                        @click="clickedBan('image')"
                                        :title="`remove ${fileType}`"
                                    >
                                        <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                    </div>
                                </div>
                                <div class="media-item" v-if="videoFile">
                                    <div class="item-type" @click="clickedFile('video')">
                                        <font-awesome-icon :icon="['fa','film']"></font-awesome-icon>
                                    </div>
                                    <div class="item-info" @click="clickedFile('video')">
                                        {{videoFile.name}}
                                    </div>
                                    <div class="item-clear"
                                        @click="clickedBan('video')"
                                        :title="`remove ${fileType}`"
                                    >
                                        <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                    </div>
                                </div>
                                <div class="media-item" v-if="audioFile">
                                    <div class="item-type" @click="clickedFile('audio')">
                                        <font-awesome-icon :icon="['fa','music']"></font-awesome-icon>
                                    </div>
                                    <div class="item-info" @click="clickedFile('audio')">
                                        {{audioFile.name}}
                                    </div>
                                    <div class="item-clear"
                                        @click="clickedBan('audio')"
                                        :title="`remove ${fileType}`"
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
                            >
                        </template>
            
                        <template slot="buttons">
                            <post-button 
                                :buttonText="'ok'" 
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
import PostButton from '../PostButton';
import FadeUp from '../transitions/FadeUp';
import MediaCapture from '../MediaCapture';
import WelcomeForm from '.././welcome/WelcomeForm';
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
        },
        components: {
            WelcomeForm,
            MediaCapture,
            FadeUp,
            PostButton,
            TextTextarea,
            TextInput,
        },
        data() {
            return {
                lessonData: {
                    ageGroup: '',
                    title: '',
                    description: '',
                },
                imageFile: null,
                videoFile: null,
                audioFile: null,
                activeFile: null,
                fileType: '',
                fileAccept: '',
                showFilePreview: false,
                errorTitle: false,
                errorFile: false,
                mediaCaptureType: '',
                showMediaCapture: false,
                alertMessage: '',
            }
        },
        computed: {
            computedFile() {
                return this.fileType === 'video' ? this.videoFile :
                    this.fileType === 'audio' ? this.audioFile :
                    this.fileType === 'picture' ? this.imageFile : null
            }
        },
        watch: {
            showFilePreview(newValue) {
                if (!newValue) {
                    this.activeFile = null
                }
            },
            lessonData: {
                handler(newValue){
                    if (newValue.title.length) {
                        this.errorTitle = false
                    }
                },
                deep: true
            },
            errorFile(newValue){
                if (newValue) {
                    this.alertMessage = 'a lesson requires at least one file'
                    setTimeout(() => {
                        this.alertMessage = ''
                    }, 4000);
                }
            },
        },
        methods: {
            mainModalDisappear() {
                this.$emit('createLessonDisappear')
            },
            clickedClosePreview(){
                this.showFilePreview = false
                this.activeFile = null
            },
            clickedBan(data){
                if (data === 'image') {
                    this.imageFile = null
                } else if (data === 'video') {
                    this.videoFile = null
                } else if (data === 'audio') {
                    this.audioFile = null
                }
                this.showFilePreview = false
            },
            clickedFile(data){
                if (data === 'image') {
                    this.activeFile = this.imageFile
                } else if (data === 'video') {
                    this.activeFile = this.videoFile
                } else if (data === 'audio') {
                    this.activeFile = this.audioFile
                }
                this.showFilePreview = !this.showFilePreview
            },
            receiveMediaCapture(file){
                if (file.type.includes('image')) {
                    this.activeFile = this.imageFile = new File([file],'my_picture.png',{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('video')) {
                    this.activeFile = this.videoFile = new File([file],'my_video.webm',{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('audio')) {
                    this.activeFile = this.audioFile = new File([file],'my_audio.mp3',{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
                this.showFilePreview = true
            },
            closeMediaCapture(){
                this.showMediaCapture = false
            },
            clickedCreate(){
                if (!this.lessonData.title.length) {
                    this.errorTitle = true
                    return 
                }
                if (!this.imageFile || !this.videoFile || !this.audioFile) {
                    this.errorFile = true
                    return 
                }
                let lesson = []
                lesson['title'] = this.lessonData.title
                lesson['description'] = this.lessonData.description
                lesson['ageGroup'] = this.lessonData.ageGroup
                lesson['file'] = [
                    this.imageFile,
                    this.videoFile,
                    this.audioFile,
                ]
                this.$emit('clickedCreate',lesson)
                this.$emit('createLessonDisappear')
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
            fileChange(){
                if (this.fileType === 'picture') {
                    this.activeFile = this.imageFile = this.$refs.inputfile.files[0]
                } else if (this.fileType === 'video') {
                    this.activeFile = this.videoFile = this.$refs.inputfile.files[0]
                } else if (this.fileType === 'audio') {
                    this.activeFile = this.audioFile = this.$refs.inputfile.files[0]
                }
                this.showFilePreview = true
            },
            removeFile(){
                this.fileType = ''
                this.showFilePreview = false
                this.imageFile = null
                this.videoFile = null
                this.audioFile = null
            },
        },
    }
</script>

<style lang="scss" scoped>

    .welcome-form{
        padding: 0;

        .section{
            font-size: 12px;
            font-weight: 500;
            border-bottom: 1px solid gray;
        }

        .info{
            text-align: center;
            font-size: 12px;
            color: gray;
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

                // .item-type{

                // }

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
    }

    h5{
        color: gray;
        text-align: center;
        margin-top: 20px;
    }
</style>