<template>
    <div>
        <div class="video-container">
            <video autoplay ref="video"></video>
        </div>
        <select ref="mediaselect"></select>

        <div class="record-button" 
            @click="clickedRecord" 
            :class="{recording:recordState === 'recording'}"
        ></div>

        <file-preview
            v-if="previewShow"
            :show="previewShow"
            :file="file"
        ></file-preview>
        <!-- <video ref="recordedvideo" controls class="d-none"></video> -->
    </div>
</template>

<script>
import FilePreview from './FilePreview';
    export default {
        components: {
            FilePreview,
        },
        data() {
            return {
                constraints: {audio:false,video:true},
                recordState: '',
                mediaRecorder: null,
                mediaChunk: [],
                previewShow: false,
                file: null,
            }
        },
        methods: {
            clickedRecord(){
                if (this.recordState === 'recording') {
                    this.mediaRecorder.stop()
                    this.recordState = 'stop recording'
                } else {
                    this.previewShow = false
                    this.file = null
                    this.mediaRecorder.start()
                    this.recordState = 'recording'
                }
            },
            getMedia() {
                if (navigator && navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    this.getUserMedia()
                    this.getEnumeratedDevices()
                } else {
                    console.log('no navigator');
                }
            },
            getUserMedia(){
                navigator.mediaDevices.getUserMedia(this.constraints)
                    .then(stream=>{
                        this.$refs.video.srcObject = stream
                        console.log(stream)

                        this.mediaRecorder = new MediaRecorder(stream)

                        this.mediaRecorder.ondataavailable = ev=>{
                            this.mediaChunk.push(ev.data)
                            // console.log(ev)
                        }

                        this.mediaRecorder.onstop = ev=>{
                            let blob = new Blob(this.mediaChunk,{'type': 'video/webm'})
                            this.file = blob
                            this.mediaChunk = []
                            this.previewShow = true
                            // this.$refs.recordedvideo.src = window.URL.createObjectURL(blob)
                            
                            // this.$refs.recordedvideo.width = 150
                            // this.$refs.recordedvideo.classList.remove('d-none')
                            // console.log(ev)
                        }

                        // console.log(this.mediaRecorder)
                    })
                    .catch(err=>{
                        console.log(err)
                    })
            },
            getEnumeratedDevices(){

                navigator.mediaDevices.enumerateDevices()
                    .then(devices=>{
                        devices.forEach(device=>{
                            console.log(device);
                            let option = document.createElement("option")

                            if (device.kind === 'videoinput') {
                                option.label = device.label || `${device.kind} `
                                option.value = device.id
                                console.log(this.$refs.mediaselect);
                                this.$refs.mediaselect.appendChild(option)
                            }
                        })
                        // console.log(device)
                    })
            },
        },
        created () {
            this.getMedia()
        },
    }
</script>

<style lang="scss" scoped>

    .video-container{
        width: 100%;
        height: auto;
        max-width: 350px;
        margin: 10px auto;

        video{
            width: inherit;
            height: inherit;
        }
    }

    .record-button{
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: gray;
    }

    .recording{
        background-color: red;
    }
</style>