<template>
    <div class="file-wrapper">
        <div class="input-wrapper" 
            :class="{'input-error':error,bottomborder:bottomBorder}">
            <input type="text" readonly v-if="inputType"
                :placeholder="inputPlaceholder" 
                class="form-control input-text">
            <div @click.prevent="clickInput" v-else
                class="input-text-small">
                <font-awesome-icon :icon="inputIcon"></font-awesome-icon>
            </div>
            <div class="input-prepend" 
                v-if="hasExtraButtons" :title="disableButton ? '' : 'clear files'"
                @click.prevent="clearFiles" 
                :class="{disable:disableButton}" >
                <font-awesome-icon :icon="['fas','ban']"></font-awesome-icon>
            </div>
            <div class="input-prepend" title="add files"
                v-if="hasExtraButtons"
                @click.prevent="clickInput">
                <font-awesome-icon :icon="['fas','upload']"></font-awesome-icon>
            </div>
            <div class="input-prepend" title="add files"
                v-if="hasExtraButtons"
                @click="clickedCapture">
                <font-awesome-icon :icon="['fas','camera-retro']"></font-awesome-icon>
            </div>
            <div class="file-capture" v-if="showFileCapture">
                <div class="capture"
                    title="capture an image"
                    @click="clickedFileCapture('image')"
                >
                    <font-awesome-icon :icon="['fas','camera']"></font-awesome-icon>
                </div>
                <div class="capture"
                    title="record a video"
                    @click="clickedFileCapture('video')"
                >
                    <font-awesome-icon :icon="['fas','video']"></font-awesome-icon>
                </div>
                <div class="capture"
                    title="record an audio"
                    @click="clickedFileCapture('audio')"
                >
                    <font-awesome-icon :icon="['fas','microphone']"></font-awesome-icon>
                </div>
            </div>
        </div>
        <input type="file" ref="fileInput" :multiple='inputMultiple' 
            @change="inputFiles" class="hidden">

        <div class="file-number-wrapper" v-if="fileMax > 1">
            <div class="file-number">{{fileNumber}}</div>
        </div>

        <post-button :buttonText="previewButton" 
            v-if="this.files.length"
            @click="showPreview"></post-button>
        <div class="preview" v-if="previewFiles.length">
            <div class="preview-wrapper">
                <div :key="key" class="preview-file"
                    v-for="(file,key) in previewFiles">
                    <div class="delete-icon" @click="removeFile(file,key)">
                        <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
                    </div>
                    <img  
                        v-if="file.type.includes('image')"
                        :ref="`file${key}`">
                    <video controls controlslist="nodownload"
                        v-else-if="file.type.includes('video')"
                        :ref="`file${key}`">
                    </video>
                    <audio controls controlslist="nodownload"
                        v-else-if="file.type.includes('audio')"
                        :ref="`file${key}`">
                    </audio>
                    <div class="default-preview"
                        :ref="`file${key}`"
                        v-else>
                        <div class="name">
                            {{file.name}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- media capture -->
        <media-capture
            v-if="showMediaCapture"
            :show="showMediaCapture"
            :type="mediaCaptureType"
            @closeMediaCapture="closeMediaCapture"
            @sendFile="receivedMediaCapture"
        ></media-capture>
    </div>
</template>

<script>
import PostButton from './PostButton.vue'
import MediaCapture from './MediaCapture.vue'

    export default {
        components: {
            MediaCapture,
            PostButton,
        },
        props: {
            fileTypeStart: {
                type: String,
                default: 'any'
            },
            fileNameEnd: {
                type: String,
                default: 'any'
            },
            inputMultiple: {
                type: Boolean,
                default: false
            },
            inputSize: {
                type: String,
                default: 'long'
            },
            hasPreview: {
                type: Boolean,
                default: true
            },
            inputIcon: {
                type: Array,
                default: null
            },
            fileMax: {
                type: Number,
                default: 5
            },
            hasExtraButtons: {
                type: Boolean,
                default: true
            },
            bottomBorder: {
                type: Boolean,
                default: false,
            }
        },
        data() {
            return {
                files: [],
                fileTypes: [],
                previewFiles: [],
                errorMessage: '',
                error: false,
                fileNames: '',
                previewButton: 'preview',
                fileNamesArray: [],
                imageType: 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp',
                videoType: 'video/webm,video/mp4,video/ogg',
                audioType: 'audio/mpeg,audio/ogg,audio/wav',
                showFileCapture: false,
                showMediaCapture: false,
                mediaCaptureType: '',
            }
        },
        computed: {
            fileNumber(){
                return this.files.length > 1 ? `${this.files.length} files` :
                    this.files.length=== 1 ? `${this.files.length} file` :
                    `0 files`
            },
            disableButton(){
                if (this.files.length > 0) {
                    return false
                } else {
                    return true
                }
            },
            inputPlaceholder() {
                return this.errorMessage ? this.errorMessage : 
                        this.fileNames ? this.fileNames : 
                        this.fileTypeStart != 'any' ? `choose a ${this.fileTypeStart} file` :
                        this.fileNameEnd != 'any' ? `choose a ${this.fileNameEnd} file` : 
                        'no file selected'
            },
            inputType(){
                if (this.inputSize==='long') {
                    return true
                } else {
                    return false
                }
            }
        },
        watch: {
            errorMessage(newValue, oldValue) {
                if (newValue != oldValue) {
                    this.error = !this.error
                    this.files = []
                }
            },
            files: {
                handler(newValue, oldValue){
                    // if (oldValue.length > newValue.length) {
                    //     // this.previewInput(this.files)
                    // }
                    this.$emit('uploadedFiles',newValue)
                },
                deep: true,
                immediate: true,
            },
        },
        methods: {
            clickedCapture(){
                this.showFileCapture = true
                setTimeout(() => {
                    this.showFileCapture = false
                }, 4000);
            },
            clickedFileCapture(data){
                this.mediaCaptureType = data
                this.showMediaCapture = true
            },
            receivedMediaCapture(blob){
                let file,
                    fileName,
                    time = new Date
                    time = time.getTime()
                if (blob.type.includes('image')) {
                    fileName = `my_picture${time}.png`
                    file = new File([blob],fileName,{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (blob.type.includes('video')) {
                    fileName = `my_video${time}.webm`
                    file = new File([blob],fileName,{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (blob.type.includes('audio')) {
                    fileName = `my_audio${time}.mp3`
                    file = new File([blob],fileName,{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
                // this.files.push(file)
                // this.previewFiles.push(file)
                // this.fileNamesArray.push(fileName)
                // this.fileNames = this.fileNamesArray.join(', ')
                this.previewInput([file])
            },
            closeMediaCapture(){
                this.mediaCaptureType = ''
                this.showMediaCapture = false
            },
            showPreview(){
                this.previewFiles = []
                if (this.previewButton === 'preview') {
                    this.previewFiles = this.files
                    this.previewButton = 'close preview'
                    this.readFiles(this.files)
                } else{
                    this.previewButton = 'preview'
                }
            },
            clearFiles(){
                this.files = []
                this.previewFiles = []
                this.fileNamesArray = []
                this.fileNames = ''
                this.previewButton = 'preview'

            },
            removeFile(file,key){
                this.files = []
                // this.previewButton = 'preview'
                this.previewFiles.splice(key,1)
                this.files =  this.previewFiles
                // console.log('preview files',this.previewFiles)
                this.fileNamesArray.splice(key,1)
                this.fileTypes.splice(key,1)
                this.fileNames = this.fileNamesArray.join(', ')
                // this.previewFiles = this.files
                this.readFiles(this.files)
            },
            clickInput(){
                this.errorMessage = ''
                this.previewButton = 'preview'
                this.previewFiles = []
                this.$refs.fileInput.click()
            },
            inputFiles(event) {
                let files = event.target.files
                this.previewInput(files)
                event.target.value = ''
            },
            previewInput(filesArray){
                let files = filesArray

                if (this.hasPreview && files.length > 0) {
                    
                    for (let i=0;i<files.length; i++) {

                        let fileTypeStart = this.fileTypeStart
                        let fileNameEnd = this.fileNameEnd
                        let fileTypeStartLength = this.fileTypeStart.length
                        let fileNameEndLength = this.fileNameEnd.length
                        let fileType = files[i].type.slice(0,4)

                        if (fileTypeStart != 'any') {
                            if (files[i].type.slice(0,Math.abs(fileTypeStartLength)) != fileTypeStart) {
                                if (fileTypeStart === 'video') {
                                    this.errorMessage = 'only video files are required'
                                    return null
                                } else if (fileTypeStart === 'image') {
                                    this.errorMessage = 'only image files are required'
                                    return null
                                }  else if (fileTypeStart === 'audio') {
                                    this.errorMessage = 'only audio files are required'
                                    return null
                                } else if (fileTypeStart === 'application') {
                                    this.errorMessage = 'only pdf and docx files are required'
                                    return null
                                } else if (fileTypeStart === 'text') {
                                    this.errorMessage = 'only text files are required'
                                    return null
                                }
                            }
                        }

                        if (fileNameEnd != 'any') {
                            if (files[i].type.slice(-Math.abs(fileNameEndLength)) 
                                != fileNameEnd) {
                                if (fileNameEnd === 'mp4') {
                                    this.errorMessage = 'wrong video format'
                                    return null
                                } else if (fileNameEnd === 'mp3') {
                                    this.errorMessage = 'wrong audio format'
                                    return null
                                } else if (fileNameEnd === 'png' ||
                                            fileNameEnd === 'jpg' ||
                                            fileNameEnd === 'jpeg' ||
                                            fileNameEnd === 'bmp' ||
                                            fileNameEnd === 'gif') {
                                    this.errorMessage = 'wrong file format'
                                    return null
                                } else if (fileNameEnd === 'docx' ||
                                            fileNameEnd === 'doc' ||
                                            fileNameEnd === 'pdf' ||
                                            fileNameEnd === 'pdt') {
                                    this.errorMessage = 'wrong file format'
                                    return null
                                } else if (fileNameEnd === 'text') {
                                    this.errorMessage = 'should be a .txt file'
                                    return null
                                }
                            }
                        }

                        if (this.files.length >= this.fileMax) {
                            this.error= true
                            this.errorMessage='you cannot add anymore files'
                            return null
                        } else {
                            this.fileNamesArray.push(files[i].name)
                            this.files.push(files[i])
                            this.fileTypes.push(fileType)
                        }

                    }
                    
                    this.fileNames = this.fileNamesArray.join(', ')
                    this.$emit('fileInputChange', this.files, this.fileTypes)
                }
            },
            readFiles(files){

                for (let i = 0; i < files.length; i++) {
                    
                    let reader = new FileReader

                    reader.addEventListener('load', function(){
                        
                            this.$refs[`file${i}`][0].src = ''
                            this.$refs[`file${i}`][0].src = reader.result
                    }.bind(this),false)
                    
                    reader.readAsDataURL(files[i])
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
$border-color-main : rgba(22, 233, 205, 1);
    .file-wrapper{
        width: 100%;

        .input-wrapper{
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border: 1px solid $border-color-main;
            background-color: white;
            position: relative;
            
            .input-text{
                // min-width: 80%;
                border: none;
                font-size: 16px;
                box-shadow: none;
                cursor: pointer;

                &:active,
                &:focus,
                &:read-only{
                    border: none;
                    box-shadow: none;
                }
            }

            .input-text-small{
                min-width: 80%;
                max-width: 50px;
            }
            
            .input-prepend{
                color: rgba(22, 233, 205, 1);
                padding: 5px 10px 5px 5px;
                cursor: pointer;
            }

            .file-capture{
                position: absolute;
                display: inline-flex;
                justify-content: space-around;
                align-items: center;
                width: 100px;
                top: 120%;
                border-radius: 10px;
                padding: 10px;
                background: inherit;

                .capture{
                    padding: 5px;
                    cursor: pointer;
                }
            }

            .disable{
                background-color: rgba(105, 105, 105, .1);
                cursor: not-allowed;
            }
        }

        .bottomborder{
            $border-radius: 0;

            border: none;
            border-radius: $border-radius;
            border-bottom: 2px solid $border-color-main;
            
            input{
                border-radius: $border-radius;
                border-radius: 0;
            }
        }

        .input-error{
            border: 1px solid rgba(255, 0, 0, 0.65);
            
            .input-prepend{
                color: rgba(255, 0, 0, 0.65);
            }
        }

        .preview{
            width: 100%;
            background-color: rgba(105, 105, 105, 0.085);

            .preview-wrapper{
                width: 95%;
                margin: 5px auto;
                background-color: rgba(105, 105, 105, 0.3);
                display: flex;
                justify-content: space-around;
                align-items: center;
                flex-wrap: wrap;
                padding: 5px;

                .preview-file{
                    width: 45%;
                    min-height: 20px;
                    height: auto;
                    background-color: rgba(105, 105, 105, 0.3);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    position: relative;

                    audio,
                    video,
                    img,
                    .default-preview{
                        width: 100%;
                    }
                    
                    .default-preview{
                        height: 50px;
                        font-size: 12px;
                        display: flex;
                        justify-content: center;
                        align-items: center;

                        div{
                            overflow: hidden;
                            white-space: nowrap;
                            text-overflow: ellipsis;
                        }
                    }

                    .delete-icon{
                        position: absolute;
                        top: 0;
                        right: 0;
                        visibility: hidden;
                        font-weight: 500;
                        font-size: 10px;
                        opacity: 0;
                        color: rgba(22, 233, 205, 1);
                        padding: 10px;
                        cursor: pointer;
                        background-color: lighten($color: #000000, $amount: 50);
                        z-index: 100000;
                    }

                    &:hover{

                        .delete-icon{
                            visibility: visible;
                            opacity: 1;
                            transition: all .5s ease-in-out;
                        }
                    }
                }
            }
        }

        .file-number-wrapper{
            width: 100%;
            background-color: rgba(105, 105, 105, .083);
            display: flex;
            justify-content: flex-end;
            align-items: center;

            .file-number{
                color: rgb(105, 105, 105);
                font-size: 12px;
                font-weight: 500;
                min-height: 15px;
            }
        }
    }

@media screen and (max-width: 800px){
    .file-wrapper{

        .input-wrapper{

            .input-text{
                font-size: 14px;
            }
        }
    }
}
</style>