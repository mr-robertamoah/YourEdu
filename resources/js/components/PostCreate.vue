<template>
    <div class="section">
        <div class="activity-post">
            <div class="post-top">
                <div class="icons"
                    @click.prevent="clickFile('image')"
                >
                    <font-awesome-icon
                        :icon="['fa','file-image']"
                    ></font-awesome-icon>
                </div>
                <div class="icons"
                    @click.prevent="clickFile('video')"
                >
                    <font-awesome-icon
                        :icon="['fa','file-video']"
                    ></font-awesome-icon>
                </div>
                <div class="icons"
                    @click.prevent="clickFile('audio')"
                >
                    <font-awesome-icon
                        :icon="['fa','file-audio']"
                    ></font-awesome-icon>
                </div>
                <post-button buttonText="post"
                    @click.prevent="post"
                ></post-button>
            </div>
            <div class=" post-middle">
                <div class="post-picture">
                    <profile-picture

                    ></profile-picture>
                </div>
                <div class="post-textarea">
                    <main-textarea 
                        :textPlaceholder="textPlaceholder"
                        v-model="textareaContent"
                    ></main-textarea>
                    <file-preview
                        :show="showPreview"
                        :file="file"
                        @removeFile="removeFile"
                    ></file-preview>
                    <div class="other-preview">
                        
                    </div>
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
                class="d-none">
            <div class="post-bottom">
                <post-button buttonText="B" 
                    @click="formType = 'book'"
                    :mainActive="activeBook"
                    titleText="post a book"></post-button>
                <post-button buttonText="R" 
                    @click="formType = 'riddle'"
                    :mainActive="activeRiddle"
                    titleText="post a riddle"
                ></post-button>
                <post-button buttonText="P" 
                    @click="formType = 'poem'"
                    :mainActive="activePoem"
                    titleText="post a poem"></post-button>
                <post-button buttonText="Q" 
                    @click="formType = 'question'"
                    :mainActive="activeQuestion"
                    titleText="post a question"></post-button>
                <post-button buttonText="A" 
                    @click="formType = 'activity'"
                    :mainActive="activeActivity"
                    :active="true"
                    titleText="post an activity"></post-button>
            </div>
        </div>

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
    </div>
</template>

<script>
import PostButton from '../components/PostButton'
import FilePreview from '../components/FilePreview'
import CreatePost from '../components/forms/CreatePost'
import JustFade from '../components/transitions/JustFade'
import ProfilePicture from '../components/profile/ProfilePicture'
import MainTextarea from '../components/MainTextarea'
import ValidationError from '../components/ValidationError'

    export default {
        components: {
            ValidationError,
            MainTextarea,
            ProfilePicture,
            JustFade,
            CreatePost,
            FilePreview,
            PostButton,
        },
        data() {
            return {
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
                activeBook: false,
                activeActivity: false,
                activeQuestion: false,
                activePoem: false,
                activeRiddle: false,
                formType: '',
                files : null,
                textPlaceholder: "do you have anything in mind?",
            }
        },
        watch: {
            formType(newValue) {
                if (newValue === 'book') { 
                    // this.activeBook = true
                    // this.activeRiddle = false
                    // this.activeQuestion = false
                    // this.activeActivity = false
                    this.activePoem = false
                } else if (newValue === 'riddle') {
                    // this.activeBook = false
                    // this.activeRiddle = true
                    // this.activeQuestion = false
                    // this.activeActivity = false
                    // this.activePoem = false
                } else if (newValue === 'poem') {
                    // this.activeBook = false
                    // this.activeRiddle = false
                    // this.activeQuestion = false
                    // this.activeActivity = false
                    // this.activePoem = true
                } else if (newValue === 'riddle') {
                    // this.activeBook = false
                    // this.activeRiddle = true
                    // this.activeQuestion = false
                    // this.activeActivity = false
                    // this.activePoem = false
                } else if (newValue === 'activity') {
                    // this.activeBook = false
                    // this.activeRiddle = false
                    // this.activeQuestion = false
                    // this.activeActivity = true
                    // this.activePoem = false
                }
                this.showModal = true
            }
        },
        computed: {
            computedFile() {
                return typeof this.file === File ? this.file : new File(["foo"], "foo.txt",{
                    type: 'text/plain'
                }) 
            }
        },
        methods: {
            clickedCreate(data){
                console.log(data)
                this.closeCreatePost()
            },
            uploadedFiles(data){
                this.files = data
            },
            closeCreatePost(){
                this.showModal = false
            },
            removeFile(){
                this.file = null
                this.clickedButton = ''
            },
            post(){
                let fileType = ''
                if (this.file) {
                    fileType = this.clickedbutton
                }
                
                if (this.videoType.includes(this.file.type)) {
                    fileType = 'video'
                } else if (this.audioType.includes(this.file.type)) {
                    fileType = 'audio'
                }

                
            },
            clearValidation(){
                this.error = ''
                this.showValidation = false
            },
            clickFile(data) {
                this.clickedButton = data
                this.showPreview = false

                if (data === 'image') {
                    this.$refs.file.setAttribute('accept', this.imageType)
                } else if (data === 'video') {
                    this.$refs.file.setAttribute('accept', this.videoType)
                } else if (data === 'audio') {
                    this.$refs.file.setAttribute('accept', this.audioType)
                }

                this.$refs.file.click()
            },
            fileChange(event) {
                let file = event.target.files[0]
                this.error = ''
                this.showValidation = false
                this.showPreview = true
                // console.log('file', file)
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
                
                event.target.value = ''
            },
        },
    }
</script>

<style lang="scss" scoped>
    
    .section{
        min-height: 200px;
        border: 1px solid dimgrey;
        padding: 10px;
        margin: 10px auto;
        border-right: 2px solid rgb(105, 105, 105);

        .activity-post{
            position: relative;

            .post-top{
                text-align: end;
                padding: 5px;
                color: gray;
                display: flex;
                justify-content: flex-end;
                align-items: center;
                font-size: 20px;

                .icons{
                    margin-right: 10px;
                    cursor: pointer;
                }
            }

            .post-middle{
                width: 100%;
                margin: 5px 0;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;

                .post-picture{
                    width: 90px;
                    height: 90px;
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

                button{
                    margin: 10px 5px;
                }
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){

    .section{
        .activity-post{

            .post-middle{

                .post-picture{
                    width: 70px;
                    height: 70px;
                }
            }
        }
    }
}


@media screen and (max-width:800px){
    .section{
        .activity-post{

            .post-middle{
                .post-picture{
                    width: 70px;
                    height: 70px;
                }
            }
        }
    }
}


@media screen and (max-width:400px){
    .section{
        .activity-post{
            
            .post-middle{
                .post-picture{
                    width: 60px;
                    height: 60px;
                }

                .post-textarea{
                    width: 70%;
                }
            }
        }
    }
}
</style>