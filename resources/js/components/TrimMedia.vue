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
                            v-if="computedVideo"
                        >
                            <video 
                                autoplay 
                                id="mediavideo"
                            >your device does not support this</video>
                        </div>
                        <div class="audio-container"
                            v-if="computedAudio"
                        >
                            <audio 
                                id="mediaaudio"
                            >your device does not support this</audio>
                        </div>
                        <div class="bar">
                            <div class="file-bar"></div>
                            <div class="trim-bar"></div>
                        </div>
                        <div class="w-full flex justify-around">
                            <div class="record-button" 
                                @click="clickedButton('trim')" 
                                v-if="!file"
                            ></div>
                            <div class="send-button" 
                                @click="clickedButton('cancel')" 
                                v-if="file"
                            ></div>
                        </div>
                    </div>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
    export default {
        props: {
            file: {
                type: Blob,
                default() {
                    return null
                }
            },
            show: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                trimmedFile: null,
                videoElement: null,
                audioElement: null,
                start: 0,
                end: null,
            }
        },
        watch: {
            file: {
                immediate: true,
                handler(newValue, oldValue) {
                    if (! newValue) {
                        return
                    }

                    if (this.computedVideo) {
                        this.videoElement = document.getElementById('mediavideo')
                    }

                    if (this.computedAudio) {
                        this.audioElement = document.getElementById('mediaaudio')
                    }
                }
            }
        },
        computed: {
            computedVideo() {
                return this.file?.type.match('video/*')?.length
            },
            computedAudio() {
                return this.file?.type.match('audio/*')?.length
            },
        },
        methods: {
            clickedButton(text) {
                if (text == 'cancel') {
                    this.emitFile()
                    this.closeTrimMedia()
                }

                if (text == 'trim') {
                    this.trimFile()
                }
            },
            mainModalDisappear() {
                this.closeTrimMedia()
            },
            closeTrimMedia() {
                this.$emit('closeTrimMedia')
            },
            emitFile() {
                this.$emit('getFile', this.trimmedFile)
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>