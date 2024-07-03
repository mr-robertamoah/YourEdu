<template>
    <div class="discussion-textarea-wrapper">
        <div class="blocked" v-if="blocked">
            sorry...you are either restricted or banned
        </div>
        <just-fade>
            <template slot="transition" v-if="!blocked && files.length">
                <div class="top">
                    <div class="file"
                        v-for="(file,index) in files"
                        :key="index"
                    >
                        <div class="file-name"
                            @click="clickedShowFile(file)"
                        >{{file.name}}</div>
                        <div class="type"
                            @click="clickedShowFile(file)"
                        >{{getMediaType(file)}}</div>
                        <div class="close" @click="clickedRemoveFile(file)">
                            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                        </div>
                    </div>
                </div>
            </template>
        </just-fade>
        <div class="bottom" v-if="!blocked">
            <div class="attachments"
                v-if="!request"
            >
                <div class="emoji" 
                    @click="clickedEmoji"
                    v-if="!typingState && !request"
                    title="add an emoji to your text"
                >
                    <font-awesome-icon :icon="['fa','grin']"></font-awesome-icon>
                </div>
                <div class="attachment"
                    @click="clickedAttachments"
                    v-if="!typingState && !request"
                    title="add a file"
                >
                    <font-awesome-icon :icon="['fa','paperclip']"></font-awesome-icon>
                </div>
                <div class="attachment"
                    @click="typingState = false"
                    v-if="typingState"
                >
                    <font-awesome-icon :icon="['fa','arrow-circle-right']"></font-awesome-icon>
                </div>
                <div class="main-attachments-wrapper"
                    @click="clickedCloseAttachments"
                    v-if="showAttachments || showEmoji"
                ></div>
                <!-- <v-emoji-picker 
                    @select="selectEmoji"
                    :showSearch="false"
                    :emojiWithBorder="false"
                    :emojiSize="24"
                    v-if="showEmoji"
                    :continuousList="true"
                    class="picker"
                ></v-emoji-picker> -->
                <div class="main-attachments"
                    v-if="showAttachments"
                >
                    <div class="main-attachments-icon"
                        @click="clickedAttachment('camera')"
                        :class="{mainAttachmentActive: attachmentText === 'camera'}"
                        title="take a picture"
                    >
                        <font-awesome-icon :icon="['fa','camera']"></font-awesome-icon>
                    </div>
                    <div class="main-attachments-icon"
                        @click="clickedAttachment('video')"
                        :class="{mainAttachmentActive: attachmentText === 'video'}"
                        title="record a video"
                    >
                        <font-awesome-icon :icon="['fa','video']"></font-awesome-icon>
                    </div>
                    <div class="main-attachments-icon"
                        @click="clickedAttachment('microphone')"
                        :class="{mainAttachmentActive: attachmentText === 'microphone'}"
                        title="record an audio"
                    >
                        <font-awesome-icon :icon="['fa','microphone']"></font-awesome-icon>
                    </div>
                    <div class="main-attachments-icon"
                        @click="clickedAttachment('upload')"
                        :class="{mainAttachmentActive: attachmentText === 'upload'}"
                        title="upload an image file"
                    >
                        <font-awesome-icon :icon="['fa','upload']"></font-awesome-icon>
                    </div>
                </div>
                
                <input type="file" ref="inputfile" 
                    @change="fileChange" multiple
                    class="hidden">
            </div>
            <textarea
                :placeholder="placeholder" 
                v-model="textareaValue"
                class="form-control"
                @keyup.enter="sendMessage"
                rows="1"
            ></textarea>
            <div class="send-button"
                @click="sendMessage"
                v-if="computedSendButton"
            >
                send
            </div>
        </div>
        <!-- file preview -->
        <addon-modal
            :show="showFilePreview"
            :fullHeight="true"
            :transparent="true"
            :hasClose="false"
        >
            <template slot="data" v-if="!blocked && showFilePreview"> 
                <div class="file-section">
                    <file-preview
                        :show="true"
                        :middle="true"
                        :showRemove="false"
                        :file="file"
                        class="file-preview-wrapper"
                    ></file-preview>

                    <input type="text" class="form-control caption" 
                        placeholder="add a caption"
                        v-model="fileCaption"
                        v-if="!showFilesPreview"
                    >
                    <div class="buttons">
                        <div class="send" @click="clickedSendPreview">
                            {{showFilesPreview ? 'ok' :'send'}}</div>
                        <div class="cancel" @click="clickedClosePreview">cancel</div>
                    </div>
                </div>
            </template>
        </addon-modal>
        
        <!-- media capture -->
        <media-capture
            v-if="!request && !blocked && showMediaCapture"
            :show="showMediaCapture"
            :type="mediaCaptureType"
            @closeMediaCapture="closeMediaCapture"
            @sendFile="receivedMediaCapture"
        ></media-capture>
    </div>
</template>

<script>
// import VEmojiPicker from 'v-emoji-picker';
import MediaCapture from './MediaCapture.vue';
import FilePreview from './FilePreview.vue';
import { files } from '../services/helpers';
    export default {
        components: {
            // VEmojiPicker,
            MediaCapture,
            FilePreview,
        },
        props: {
            blocked: {
                type: Boolean,
                default: false
            },
            request: {
                type: Boolean,
                default: false
            },
            mediaChat: {
                type: Boolean,
                default: false
            },
        },
        watch: {
            request: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.placeholder = 'have a message?'
                    }
                }
            },
            mediaChat: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.placeholder = 'have a message?'
                    }
                }
            },
            textareaValue(newValue){
                if (newValue.length && !this.typignState) {
                    this.typingState = true
                    if (this.typingClock) {
                        clearTimeout(this.typingClock)
                    }
                    this.typingClock = setTimeout(() => {
                        this.typingState = false
                    }, 3000);
                } else this.typingState = false
                this.$emit('input',newValue)
            },
            files(newValue){
                if (newValue.length > 1) {
                    this.showFilesPreview = true
                } else if (newValue.length === 1) {
                    this.file = newValue[0]
                    this.showFilePreview = true
                    this.showFilesPreview = true
                } else {
                    this.showFilePreview = false
                    this.file = null
                }
            },
        },
        data() {
            return {
                showMediaCapture: false,
                mediaCaptureType: '',
                fileCaption: '',
                showFilePreview: false,
                showFilesPreview: false,
                showEmoji: false,
                showAttachments: false,
                textareaValue: '',
                placeholder: 'have anything to contribute?',
                files: [],
                file: null,
                typingState: false,
                attachmentText: '',
                typingClock: null,
            }
        },
        computed: {
            computedSendButton() {
                return this.typingState ? false : true
            },
        },
        methods: {
            clearData(){
                this.fileCaption = ''
                this.textareaValue = ''
                this.files = []
            },
            clickedAttachment(data){
                this.attachmentText = data
                this.showAttachments = false
                this.$emit('clickedAttachment',data)
                if (data === 'camera') {
                    this.mediaCaptureType = 'image'
                    this.showMediaCapture = true
                    return
                } else if (data === 'microphone') {
                    this.mediaCaptureType = 'audio'
                    this.showMediaCapture = true
                    return
                } else if (data === 'video') {
                    this.mediaCaptureType = 'video'
                    this.showMediaCapture = true
                    return
                } else if (data === 'upload') {
                    this.$refs.inputfile.accept = 
                    'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp,audio/mpeg,audio/ogg,audio/wav,video/webm,video/mp4,video/ogg'
                }
                
                this.$refs.inputfile.value = ''
                this.$refs.inputfile.click()
            },
            clickedShowFile(data){
                this.file = data
                this.showFilePreview = true
            },
            clickedRemoveFile(data){
                let index = this.files.findIndex(file=>{
                    return file.name === data.name && file.type === data.type &&
                        file.size === data.size
                })
                if (index > -1) {
                    this.files.splice(index, 1)
                }
            },
            fileChange(){
                for (let i = 0; i < this.$refs.inputfile.files.length; i++) {
                    this.files.push(this.$refs.inputfile.files[i])
                }
                this.$emit('fileChange',{
                    files: this.files,
                    caption: this.fileCaption
                })
                this.attachmentText = ''
            },
            sendMessage(){
                if (!this.textareaValue.length && !this.files.length) {
                    return
                }
                this.$emit('sendMessage')
                this.clearData()
            },
            clickedAttachments() {
                this.showAttachments = true
                setTimeout(() => {
                    this.showAttachments = false
                }, 4000);
            },
            clickedEmoji(){
                if (this.disabledChat) {
                    this.showSmallModal = true
                    return
                }
                this.showEmoji = !this.showEmoji
            },
            selectEmoji(e){
                this.textareaValue += e.data
            },
            clickedCloseAttachments(){
                this.showEmoji = false
                this.showAttachments = false
            },
            closeMediaCapture(){
                this.showMediaCapture = false
                this.attachmentText = ''
            },
            getMediaType(data){
                return files.fileType(data)
            },
            receivedMediaCapture(blob){
                let time = new Date().getTime(),
                    fileName
                if (blob.type.includes('image')) {
                    fileName = `my_picture${time}.png`
                    this.files.push(new File([blob],fileName,{
                        type: 'image/png',
                        lastModified: new Date()
                    }))
                } else if (blob.type.includes('video')) {
                    fileName = `my_video${time}.webm`
                    this.files.push(new File([blob],fileName,{
                        type: 'video/webm',
                        lastModified: new Date()
                    }))
                } else if (blob.type.includes('audio')) {
                    fileName = `my_audio${time}.mp3`
                    this.files.push(new File([blob],fileName,{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    }))
                }
            },
            clickedClosePreview(){
                this.showFilePreview = false
                this.clickedRemoveFile(this.file)
                this.file = null
                this.fileCaption = ''
            },
            clickedSendPreview(){
                this.showFilePreview = false
                this.$emit('fileChange',{
                    files: this.files,
                    caption: this.fileCaption
                })
                this.file = null
                this.fileCaption = ''
            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin close(){
    color: gray;
    position: absolute;
    right: 3px;
    top: 3px;
    padding: 5px;
    font-size: 14px;
    cursor: pointer;

    &:hover{
        color: red;
        transition: all 1s ease-in-out;
    }
}

    .discussion-textarea-wrapper{

        .blocked{
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: gray;
        }

        .top{
            width: 100%;
            padding: 5px;
            margin: 5px 0;
            overflow-x: auto;

            .file{
                display: inline-flex;
                justify-content: space-between;
                align-items: center;
                color: gray;
                background-color: mintcream;
                width: 150px;
                font-size: 10px;
                padding: 5px;
                cursor: pointer;
                position: relative;
                margin: 0 10px 0 0;

                .file-name{
                    font-size: 11px;
                    max-width: 70%;
                    text-overflow: ellipsis;
                    overflow: hidden;
                    white-space: nowrap;
                }
                
                .file-type{
                    font-size: 10px;
                }

                .close{
                    @include close();
                    z-index: 1;
                }
            }
        }   

        .bottom{
            color: dimgrey;
            width: 100%;
            display: inline-flex;
            justify-content: space-between;
            align-items: center; 

            .attachments{
                display: inline-flex;
                padding: 5px;
                font-size: 18px;
                min-width: 100px;
                justify-content: space-around;
                position: relative;

                .emoji,
                .attachment{
                    padding: 5px;
                    cursor: pointer;
                }

                .picker{
                    position: absolute;
                    bottom: 110%;
                    left: 0;
                }

                .main-attachments-wrapper{
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    height: 100%;
                    width: 100%;
                    background: transparent;
                }

                .main-attachments{
                    position: absolute;
                    bottom: 50px;
                    right: 10px;
                    padding: 5px;
                    background: white;
                    border-radius: 10px;
                    box-shadow: 0 0 2px grey;

                    .main-attachments-icon{
                        padding: 5px 10px;
                        margin: 5px 0;
                        cursor: pointer;

                        &:hover{
                            border-top: 1px solid gray;
                            border-bottom: 1px solid gray;
                            transition: all .5s ease;
                        }
                    }

                    .mainAttachmentActive{
                        border-top: 1px solid gray;
                        border-bottom: 1px solid gray;
                        transition: all .5s ease;
                    }
                }
            }

            textarea{
                border: none;
                border-radius: 0;
                width: 100%;
                font-size: 16px;
                width: 100%;
                background: transparent;

                &:focus,
                &:active{
                    box-shadow: none;
                }
            }

            .send-button,
            .ok-button{
                margin: 0 10px;
                padding: 5px;
                border-radius: 10px;
                font-size: 14px;
                box-shadow: 0 0 2px darkslategrey;
                cursor: pointer;
            }

            .ok-button{
                margin: 10px;
            }
        }    

        .file-section{
            height: 100%;
            width: 100%;
            padding: 10px;

            .file-preview-wrapper{
                height: 75%;
                margin: 20px auto 10px;
            }

            .caption{
                background: rgba(0, 0, 0, 0.371);
                color: mintcream;
                text-align: center;
                font-size: 14px;
                padding: 5px;
                width: 100%;
                margin: 0 auto;
                border: none;
                border-bottom: 2px solid black;
                box-shadow: none;
                border-radius: 5px 5px 0 0;

                &::placeholder{
                    text-align: center;
                    color: mintcream;
                }
            }

            input:focus,
            input:active{
                border: none;
                box-shadow: none;
                border-bottom: 2px solid black;
            }

            .buttons{
                width: 100%;
                display: inline-flex;
                justify-content: space-around;
                align-items: center;
                font-size: 14px;
                color: white;
                margin-top: 10px;

                .send,
                .cancel{
                    padding: 5px;
                    border-radius: 10px;
                    box-shadow: 0 0 2px grey;
                    cursor: pointer;
                }

                .send{
                    background: green;
                }

                .cancel{
                    background: red;
                }
            }
        }
    }

@media screen and (max-width:800px) {
    
    .chat-textarea-wrapper{
        textarea,
        textarea::placeholder{
            font-size: 14px;
        }
    }
}

@media screen and (max-width:300px) {
    
    .chat-textarea-wrapper{

        textarea,
        textarea::placeholder{
            font-size: 12px;
        }
    }
}
</style>