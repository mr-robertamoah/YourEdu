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
                                @canplay="videaStreamReady"
                                @resize="videaStreamReady"
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
                        <canvas ref="mediacanvas" class="d-none"></canvas>
                        <div class="switch"
                            @click="clickedSwitch"
                            v-if="devices.length > 1 && !file"
                            title="change camera"
                        >switch</div>
                        <div class="buttons">
                            <div class="record-button" 
                                @click="clickedRecord" 
                                v-if="!file"
                                :class="{recording:recordState === 'recording'}"
                            ></div>
                            <div class="send-button" 
                                @click="clickedSend" 
                                v-if="file"
                            >
                                <font-awesome-icon :icon="['fa','paper-plane']"></font-awesome-icon>
                            </div>
                        </div>
                    </div>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import PulseLoader from 'vue-spinner/src/PulseLoader';
    export default {
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
        components: {
            PulseLoader,
        },
        data() {
            return {
                constraints: {},
                recordState: '',
                mediaRecorder: null,
                camerasNumber: 0,
                mediaChunk: [],
                devices: [],
                previewShow: false,
                file: null,
                audioState: '',
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
        methods: {
            videaStreamReady(){
                if (this.type === 'image') {
                    window.width = this.$refs.mediavideo.getClientRects()[0].width
                    window.height = (this.$refs.mediavideo.videoHeight/this.$refs.mediavideo.videoWidth)*width

                    this.$refs.mediavideo.setAttribute('width',width)
                    this.$refs.mediavideo.setAttribute('height',height)
                    this.$refs.mediacanvas.setAttribute('width',width)
                    this.$refs.mediacanvas.setAttribute('height',height)
                }
            },
            clickedRemoveFile(){
                this.file = null
                if (this.type === 'audio') {
                    this.audioState = 'waiting to record'
                }
            },
            clickedSend(){
                this.$emit('sendFile', this.file)
                this.mainModalDisappear()
            },
            clickedRecord(){
                if (this.type === 'image') {
                    this.snap()
                    return
                }
                if (this.recordState === 'recording') {
                    this.recordState = 'stop recording'
                    mediaRecorder.stop()
                    if (this.type === 'audio') {
                        this.audioState = 'doneRecording'
                    }
                } else {
                    this.previewShow = false
                    this.file = null
                    if (this.type === 'audio') {
                        this.audioState = 'recording'
                    }
                    this.recordState = 'recording'
                    mediaRecorder.start()
                }
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
                if(stream){
                    stream.getTracks().forEach(track=>{
                        track.stop()
                    })
                }
                this.$emit('closeMediaCapture')
            },
            getUserMedia(){
                navigator.mediaDevices.getUserMedia(this.constraints)
                    .then(stream=>{
                        console.log(stream)
                        window.stream = stream
                        if (this.type === 'video' || this.type === 'image') {
                            this.$refs.mediavideo.srcObject =  stream
                            this.record('video/webm')
                        } else if (this.type === 'audio') {
                            this.audioState = 'waiting to record'
                            this.record('audio/mp3')
                        }
                    })
                    .catch(err=>{
                        console.log(err)
                    })
            },
            record(type){
                window.mediaRecorder = new MediaRecorder(stream)

                mediaRecorder.ondataavailable = ev=>{
                    this.mediaChunk.push(ev.data)
                }

                mediaRecorder.onstop = ev=>{
                    let blob = new Blob(this.mediaChunk,{'type': type})
                    this.file = blob
                    this.mediaChunk = []
                    this.previewShow = true
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

                            if (device.kind === 'videoinput' &&
                                (this.type === 'video' || this.type === 'image')) {
                                this.devices.push(device)
                            } else if (device.kind === 'audioinput' &&
                                this.type === 'audio') {
                                this.devices.push(device)
                            }
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

        .buttons{
            width: 100%;
            position: absolute;
            bottom: 10px;

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

</style>