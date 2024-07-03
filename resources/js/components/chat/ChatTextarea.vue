<template>
    <div class="chat-textarea-wrapper"
            :class="{error:error,bottomborder:bottomBorder}"
        >
        <div class="blocked" v-if="blocked">
            sorry...this account blocked you
        </div>
        <div class="attachments" 
            v-if="!blocked"
            :class="{attachmentTypingState: typingState}"
        >
            <div class="emoji" 
                @click="clickedEmoji"
                v-if="!possibleAnswer && !typingState"
                title="add an emoji to your text"
            >
                <font-awesome-icon :icon="['fa','grin']"></font-awesome-icon>
            </div>
            <div class="attachment"
                @click="clickedAttachments"
                v-if="!possibleAnswer && !typingState"
                title="add a file"
            >
                <font-awesome-icon :icon="['fa','paperclip']"></font-awesome-icon>
            </div>
            <div class="attachment"
                @click="clickedCreateQuestion"
                v-if="!possibleAnswer && !typingState"
                title="ask a question"
            >
                <font-awesome-icon :icon="['fa','question-circle']"></font-awesome-icon>
            </div>
            <div class="attachment"
                @click="typingState = false"
                v-if="!possibleAnswer && typingState"
            >
                <font-awesome-icon :icon="['fa','arrow-circle-right']"></font-awesome-icon>
            </div>
            <div class="attachment"
                @click="clickedClearAnswer"
                v-if="possibleAnswer"
            >
                <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
            </div>
            <div class="main-attachments-wrapper"
                @click="clickedCloseAttachments"
                v-if="showAttachments || showEmoji"
            ></div>
            <v-emoji-picker 
                @select="selectEmoji"
                :showSearch="false"
                :emojiWithBorder="false"
                :emojiSize="24"
                v-if="showEmoji"
                :continuousList="true"
                class="picker"
            ></v-emoji-picker>
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
                    @click="clickedAttachment('file-image')"
                    :class="{mainAttachmentActive: attachmentText === 'file-image'}"
                    title="upload an image file"
                >
                    <font-awesome-icon :icon="['fa','file-image']"></font-awesome-icon>
                </div>
                <div class="main-attachments-icon"
                    @click="clickedAttachment('file-video')"
                    :class="{mainAttachmentActive: attachmentText === 'file-video'}"
                    title="upload a video file"
                >
                    <font-awesome-icon :icon="['fa','file-video']"></font-awesome-icon>
                </div>
                <div class="main-attachments-icon"
                    @click="clickedAttachment('file-audio')"
                    :class="{mainAttachmentActive: attachmentText === 'file-audio'}"
                    title="upload an audio file"
                >
                    <font-awesome-icon :icon="['fa','file-audio']"></font-awesome-icon>
                </div>
            </div>
            
            <input type="file" ref="inputfile" 
                @change="fileChange"
                class="hidden">
        </div>
        <textarea
            v-if="!blocked"
            :placeholder="placeholder" 
            v-model="textareaValue"
            class="form-control"
            @keyup.enter="sendMessage"
            rows="1"
            @focus="focusTextarea"
            :readonly="possibleAnswer"
        ></textarea>
        <div class="send-button"
            @click="sendMessage"
            v-if="computedSendButton"
        >
            {{answer ? 'answer' : 'send'}}
        </div>
        <!-- file preview -->
        <addon-modal
            :show="showFilePreview"
            :fullHeight="true"
            :transparent="true"
            :hasClose="false"
        >
            <template slot="data" v-if="showFilePreview"> 
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
                        v-if="!answer"
                        v-model="fileCaption">
                    <div class="buttons">
                        <div class="send" @click="clickedSendPreview">send</div>
                        <div class="cancel" @click="clickedClosePreview">cancel</div>
                    </div>
                </div>
            </template>
        </addon-modal>
        <!-- small modal -->
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    :show="showSmallModal"
                    title="Please accept to chat this account before continuing. You are seeing this because you may have blocked or declined to have a conversation"
                    @disappear="showSmallModal = false"
                >
                    <template slot="actions">
                        <div class="ok-button" @click="clickedSmallModalOk">ok</div>
                    </template>
                </small-modal>
            </template>
        </fade-up>
        <!-- media capture -->
        <media-capture
            v-if="showMediaCapture"
            :show="showMediaCapture"
            :type="mediaCaptureType"
            @closeMediaCapture="closeMediaCapture"
            @sendFile="receivedMediaCapture"
        ></media-capture>
        <!-- create post -->
        <create-post
            :showForm="showCreateQuestion"
            type="question"
            @clickedCreate="clickedCreate"
            :chat="true"
            @mainModalDisappear="closeCreateQuestion"
        ></create-post>
    </div>
</template>

<script>
// import VEmojiPicker from 'v-emoji-picker';
import FadeUp from '../transitions/FadeUp.vue';
import FilePreview from '../FilePreview.vue';
import MediaCapture from '../MediaCapture.vue';
import CreatePost from '../forms/CreatePost.vue';
    export default {
        props: {
            placeholder: {
                type: String,
                default: ''
            },
            value: {
                type: String,
                default: ''
            },
            error: {
                type: Boolean,
                default: false
            },
            answer: {
                type: Boolean,
                default: false
            },
            possibleAnswer: {
                type: Boolean,
                default: false
            },
            blocked: {
                type: Boolean,
                default: false
            },
            disabledChat: {
                type: Boolean,
                default: false
            },
            bottomBorder: {
                type: Boolean,
                default: false
            },
            inputMax: {
                type: Number,
                default: 100
            },
            hasMax: {
                type: Boolean,
                default: false
            },
        },
        components: {
            CreatePost,
            MediaCapture,
            FilePreview,
            FadeUp,
            // VEmojiPicker,
        },
        data() {
            return {
                showAttachments: false,
                attachmentText: '',
                //files
                textareaValue: '',
                fileCaption: '',
                showEmoji: false,
                showSmallModal: false,
                showFilePreview: false,
                file: null,
                showMediaCapture: false,
                showCreateQuestion: false,
                mediaCaptureType: '',
                typingState: false, //for changing classes of ui when user is typing
            }
        },
        watch: {
            value(newValue, oldValue) {
                if (this.hasMax) {
                    if (newValue.length > this.inputMax) {
                        this.$emit('input',oldValue)
                        this.textareaValue = oldValue
                        return
                    }
                }
                this.textareaValue = newValue
            },
            textareaValue(newValue){
                this.typingState = true
                setTimeout(() => {
                    this.typingState = false
                }, 3000);
                this.$emit('input',newValue)
            }
        },
        computed: {
            computedSendButton() {
                return this.blocked ? false : this.typingState ? false : true
            }
        },
        methods: {
            clickedCreate(data){
                this.textareaValue = ''
                this.$emit('sendQuestion', data)
            },
            clickedClearAnswer(){
                this.$emit('clickedClearAnswer')
            },
            clickedCreateQuestion(){
                this.showCreateQuestion = true
            },
            closeCreateQuestion(){
                this.showCreateQuestion = false
            },
            focusTextarea() {
                if (this.disabledChat) {
                    this.showSmallModal = true
                    return
                }
            },
            receivedMediaCapture(blob){
                if (blob.type.includes('image')) {
                    this.file = new File([blob],'my_picture.png',{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (blob.type.includes('video')) {
                    this.file = new File([blob],'my_video.webm',{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (blob.type.includes('audio')) {
                    this.file = new File([blob],'my_audio.mp3',{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
                this.showFilePreview = true
            },
            closeMediaCapture(){
                this.showMediaCapture = false
                this.attachmentText = ''
            },
            clickedClosePreview(){
                this.showFilePreview = false
                this.file = null
                this.fileCaption = ''
            },
            clickedSendPreview(){
                this.showFilePreview = false
                this.$emit('fileChange',{
                    file: this.file,
                    caption: this.fileCaption
                })
                this.file = null
                this.fileCaption = ''
            },
            clickedSmallModalOk(){
                this.showSmallModal = false
            },
            sendMessage(){
                if (this.disabledChat) {
                    this.showSmallModal = true
                    return
                }
                this.$emit('sendMessage')
            },
            clickedAttachments(){
                if (this.disabledChat) {
                    this.showSmallModal = true
                    return
                }
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
                this.textareaValue = this.textareaValue + e.data
            },
            clickedCloseAttachments(){
                this.showAttachments = false
                this.showEmoji = false
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
                } else if (data === 'file-image') {
                    this.$refs.inputfile.accept = 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp'
                } else if (data === 'file-audio') {
                    this.$refs.inputfile.accept = 'audio/mpeg,audio/ogg,audio/wav'
                } else if (data === 'file-video') {
                    this.$refs.inputfile.accept = 'video/webm,video/mp4,video/ogg'
                }
                
                this.$refs.inputfile.click()
            },
            fileChange(){
                this.file = this.$refs.inputfile.files[0]
                this.$refs.inputfile.value = ''
                this.showFilePreview = true
                this.attachmentText = ''
            },
        },
    }
</script>

<style lang="scss" scoped>
$border-radius: 10px;
$border-color-main: rgba(22, 233, 205, 1);
$border-color-error:rgba(201, 6, 6, 0.9);

    .chat-textarea-wrapper{
        color: dimgrey;
        width: 100%;
        display: inline-flex;
        justify-content: space-between;
        align-items: center;

        .blocked{
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: gray;
        }

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

        .attachmentTypingState{
            min-width: auto;
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

    .bottomborder{
        $border-radius: none;

        border: none;
        border-radius: $border-radius;
        border-bottom: 2px solid $border-color-main;
        
        input{
            border-radius: $border-radius;
            border-radius: 0;
        }
    }

    .error{
        border: 2px solid $border-color-error;
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