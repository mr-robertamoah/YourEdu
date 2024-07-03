<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :main="false"
                :dark="true"
                :requests="false"
                @mainModalDisappear="mainModalDisappear"
                class="modal-wrapper"
            >
                <template slot="main-other">
                    <div class="media-container">
                        <div class="video-container"
                            v-if="computedImageVideo"
                        >
                            <video autoplay ref="mediavideo" 
                                @canplay="videoStreamReady"
                                @resize="videoStreamReady"
                                muted
                            >your device does not support this</video>
                        </div>
                        <div class="audio-container"
                            v-if="computedAudio"
                        >
                            <div class="recorder">
                                <div class="state">
                                    {{audioState}}
                                </div>
                                <pulse-loader 
                                    :loading="audioState === 'recording'"
                                    size="10px"
                                    color="#ffffff"
                                ></pulse-loader>
                            </div>
                        </div>

                        <file-preview
                            v-if="file"
                            :file="file"
                            :middle="true"
                            @removeFile="clickedRemoveFile"
                        ></file-preview>
                        <canvas ref="mediacanvas" class="hidden"></canvas>
                        <div class="switch"
                            @click="clickedSwitch"
                            v-if="devices.length > 1 && !file"
                            title="change camera"
                        >switch</div>
                        
                    </div>

                    <pop-up
                        :show="showPopUp"
                        :responses="['trim', 'cancel']"
                        default="cancel"
                        :message="popUpMessage"
                        @clickedResponse="clickedPopupResponse"
                        @closePopUp="closePopUp"
                    ></pop-up>

                    <trim-media
                        :file="trimMediaData.file"
                        :show="trimMediaData.show"
                        @getFile="getTrimmedFile"
                        @closeTrimMedia="getTrimmedFile('close')"
                    ></trim-media>

                    <div 
                        class="z-50 text-white absolute bottom-0 w-full text-center"
                        v-if="checking"
                    >
                        checking for duration...
                    </div>
                </template>

                <div class="buttons absolute bottom-1">
                    <div class="record-button" 
                        @click="clickedButton('record')" 
                        v-if="!file && buttonType == 'record'"
                        :class="{recording:recordState === 'recording'}"
                    ></div>
                    <div class="send-button" 
                        @click="clickedButton('send')" 
                        v-if="file && !checking && buttonType == 'send'"
                    >
                        <font-awesome-icon :icon="['fa','paper-plane']"></font-awesome-icon>
                    </div>
                </div>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>

import UploadedFiles from '../mixins/UploadedFiles.mixin.js';
import TrimMedia from './TrimMedia.vue';
import PopUp from '../mixins/PopUp.mixin.js';
import Trim from '../mixins/TrimMedia.mixin.js';
import getBlobDuration from 'get-blob-duration';
    export default {
        components: {
            
            TrimMedia,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: ''
            },
        },
        mixins: [Trim, PopUp, UploadedFiles],
        data() {
            return {
                constraints: {},
                recordState: '',
                mediaRecorder: null,
                camerasNumber: 0,
                mediaChunk: [],
                devices: [],
                previewShow: false,
                checking: false,
                buttonType: null,
                file: null,
                stream: null,
                audioState: '',
                fileRequirement: {
                    doesntHaveAppropriateSize: false,
                    doesntHaveAppropriateDuration: false,
                }
            }
        },
        watch: {
            type: {
                immediate: true,
                handler(newValue){
                    if (newValue === 'image') {
                        this.constraints = {video: {
                            width: {
                                ideal: 1280
                            },
                            height: {
                                ideal: 720
                            },
                        }, audio: false}
                    } else if (newValue === 'video') {
                        this.constraints = {video:  {
                            width: {
                                ideal: 1280
                            },
                            height: {
                                ideal: 720
                            },
                        }, audio: {
                            echoCancellation: true
                        }}
                    } else if (newValue === 'audio') {
                        this.constraints = {video: false, audio: {
                            echoCancellation: true
                        }}
                    }
                    this.getEnumeratedDevices(newValue)
                    this.getUserMedia()
                }
            }
        },
        computed: {
            computedAudio() {
                return this.file ? false : this.type === 'audio' ? true : false
            },
            computedImageVideo(){
                return this.file ? false : this.type === 'image' || this.type === 'video' ? true : false
            },
        },
        beforeDestroy () {
            this.stopStream()
            this.resetData()
        },
        methods: {
            clickedButton(type) {
                if (type === 'record') {
                    this.clickedRecord()
                    return
                }

                this.clickedSend()
            },
            getTrimmedFile(file) {
                if (file === 'close') {
                    this.clearTrimMediaData()
                    this.restart()
                    return
                }

                this.sendFile(file)
            },
            restart() {
                this.resetData()
                this.getUserMedia()
            },
            videoStreamReady(){
                if (this.type !== 'image') {
                    return
                }

                window.width = this.$refs.mediavideo.getClientRects()[0].width
                window.height = (this.$refs.mediavideo.videoHeight/this.$refs.mediavideo.videoWidth) * width

                this.$refs.mediavideo.setAttribute('width',width)
                this.$refs.mediavideo.setAttribute('height',height)
                this.$refs.mediacanvas.setAttribute('width',width)
                this.$refs.mediacanvas.setAttribute('height',height)
            },
            clickedRemoveFile(){
                this.getUserMedia()
                this.file = null
                if (this.type === 'audio') {
                    this.audioState = 'waiting to record'
                }
            },
            clickedPopupResponse(text) {
                console.log(text);
                this.closePopUp()
                
                if (text == 'trim') {
                    this.trimMediaData.file = this.file
                    this.trimMediaData.show = true
                    return
                }
                
                if (text == 'cancel') {
                    this.restart()
                    return
                }
            },
            sendFile(file) {
                this.$emit('sendFile', file)
                this.mainModalDisappear()
            },
            async clickedSend(){

                if (this.type === 'image') {
                    this.sendFile(this.file)
                    return
                }

                this.checking = true

                this.setFileRequirements(
                    await getBlobDuration(this.file)
                )

                this.checking = false

                if (this.fileRequirement.doesntHaveAppropriateDuration) {
                    this.displayPopUp('duration')
                    return
                }

                if (this.fileRequirement.doesntHaveAppropriateSize) {
                    this.displayPopUp('size')
                    return
                }

                this.sendFile(this.file)
            },
            setFileRequirements(duration) {

                this.resetFileRequirements()

                if (! ['video', 'audio'].includes(this.type)) {
                    return
                }

                duration = this.getFormattedDuration(duration)

                if (! this.hasAppropriateSize(this.file, this.type)) {
                    this.fileRequirement.doesntHaveAppropriateSize = true
                }

                if (! this.hasAppropriateDuration(duration, this.type)) {
                    this.fileRequirement.doesntHaveAppropriateDuration = true
                }
            },
            resetFileRequirements() {
                this.fileRequirement.doesntHaveAppropriateSize = false
                this.fileRequirement.doesntHaveAppropriateDuration = false
            },
            displayPopUp(type) {

                if (type === 'size') {
                    this.popUpMessage = `the size of the ${this.type} should be less than ${this.maxSize[this.type]}. trim the file or cancel to delete`
                }
                
                if (type === 'duration') {
                    this.popUpMessage = `the duration of the ${this.type} should be less than ${this.maxTime[this.type]}. trim the file or cancel to delete`
                }

                this.showPopUp = true
            },
            clickedRecord(){
                if (this.type === 'image') {
                    this.snap()
                    this.buttonType = 'send'
                    return
                }

                if (this.mediaRecorder?.state === 'recording') {
                    this.recordState = 'stop recording'
                    this.audioState = 'doneRecording'
                    this.mediaRecorder.stop()
                    return
                }

                this.previewShow = false
                this.file = null

                if (this.type === 'audio') {
                    this.audioState = 'recording'
                }

                this.recordState = 'recording'
                this.mediaRecorder.start()
            },
            clickedSwitch(){
                if (this.camerasNumber < this.devices.length -1) {
                    this.camerasNumber += 1
                } else {
                    this.camerasNumber = 0
                }
                
                if (this.type === 'audio') {
                    this.audioState = 'recording'
                }
                this.constraints.video.deviceId = this.devices[this.camerasNumber].deviceId
            },
            mainModalDisappear(){
                this.$emit('closeMediaCapture')
            },
            resetData() {
                this.file = null
                this.buttonType = ''
                this.stream = null
            },
            stopStream() {
                if(! this.stream){
                    return
                }

                this.stream.getTracks().forEach(track=>{
                    track.stop()
                })
            },
            getUserMedia(){
                navigator.mediaDevices.getUserMedia(this.constraints)
                    .then(stream=>{
                        console.log(stream);
                        
                        if (this.type === 'audio') {
                            this.audioState = 'waiting to record'
                            this.record('audio/mp3')
                            return
                        }

                        this.buttonType = 'record'

                        if (this.videoIsStreaming()) {
                            return
                        }
                        
                        this.stream = stream

                        this.$refs.mediavideo.srcObject =  stream
                        this.record('video/webm')
                    })
                    .catch(err=>{
                        console.log(err)
                    })
            },
            videoIsStreaming() {
                if (this.type === 'audio') {
                    return false
                }
                
                if (this.$refs.mediavideo?.srcObject || 
                    this.$refs.mediavideo?.src.length) {
                    return true
                }

                return false
            },
            record(type){
                this.mediaRecorder = new MediaRecorder(this.stream)

                this.mediaRecorder.onstart = ev=>{
                    
                }

                this.mediaRecorder.ondataavailable = ev=>{
                    
                    this.mediaChunk.push(ev.data)
                }

                this.mediaRecorder.onstop = ev=>{
                    this.file = new Blob(this.mediaChunk,{'type': type})
                    this.mediaChunk = []
                    this.previewShow = true

                    this.stopStream()

                    this.buttonType = 'send'
                }
            },
            snap(){
                let context = this.$refs.mediacanvas.getContext('2d')

                context.drawImage(this.$refs.mediavideo,0,0,width,height)
                
                this.$refs.mediacanvas.toBlob(blob=>{
                    this.file = blob
                },'image/png')
            },
            getEnumeratedDevices(){
                navigator.mediaDevices.enumerateDevices()
                    .then(devices=>{
                        devices.forEach(device=>{
                            let option = document.createElement("option")

                            if (device.kind.includes(this.type)) {
                                this.devices.push(device)
                            }

                            //add details to option and append to parent
                        })
                    })
            },
        },
    }
</script>

<style lang="scss" scoped>

    .modal-wrapper{

        .main-other{
            background: black;
            height: 100%;
        }

        .buttons{
            width: 100%;

            .record-button,
            .send-button{
                width: 40px;
                position: relative;
                height: 40px;
                margin: 0 auto;
                border-radius: 50%;
                background-color: gray;
                cursor: pointer;
            }
            
            .send-button{
                background-color: black;
                font-size: 20px;
                font-weight: 500;
                color: green;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .recording{
                background-color: red;
            }
        }

    }

    .media-container{
        height: 100%;
        position: relative;

        .video-container{
            width: 100%;
            height: 80%;
            margin: 10px auto;

            video{
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
        }

        .audio-container{
            width: 100%;
            height: 200px;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;

            .recorder{
                text-align: center;

                .state{
                    font-size: 18px;
                    font-weight: 500;
                    margin-bottom: 10px;
                }
            }
        }

        .switch{
            color: mintcream;
            position: absolute;
            top: 0;
            right: 0;
            z-index: 1;
            background: gray;
            border-radius: 10px;
            padding: 5px;
            cursor: pointer;
        }
    }

</style>