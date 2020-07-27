<template>
    <div class="section">
        <div class="activity-post">
            <div class="post-top">
                <div class="icons"
                    @click.prevent="clickFile('image')"
                    v-if="mainPreviewData.length === 0 "
                >
                    <font-awesome-icon
                        :icon="['fa','file-image']"
                    ></font-awesome-icon>
                </div>
                <div class="icons"
                    @click.prevent="clickFile('video')"
                    v-if="mainPreviewData.length === 0"
                >
                    <font-awesome-icon
                        :icon="['fa','file-video']"
                    ></font-awesome-icon>
                </div>
                <div class="icons"
                    @click.prevent="clickFile('audio')"
                    v-if="mainPreviewData.length === 0"
                >
                    <font-awesome-icon
                        :icon="['fa','file-audio']"
                    ></font-awesome-icon>
                </div>
                <fade-right>
                    <template slot="transition">
                        <post-button 
                            buttonText="post"
                            @click="createPost"
                            v-if="computedPost"
                        ></post-button>
                    </template>
                </fade-right>
            </div>
            <div class="profiles"
                v-if="showProfiles"
            >
                <div :key="key" v-for="(profile,key) in computedProfiles">
                    <profile-bar
                        :name="profile.name"
                        :type="profile.params.account_type"
                        :smallType="true"
                        :routeParams="profile.params"
                        :navigate="false"
                        @clickedProfile="clickedProfile"
                    ></profile-bar>
                </div>
                <!-- <div>
                    <profile-bar
                        :name="profile.name"
                        :type="profile.name"
                        :smallType="true"
                    ></profile-bar>
                    <profile-bar
                        name="john arhin"
                        type="professional"
                        :smallType="true"
                    ></profile-bar>
                </div> -->
            </div>
            <div class=" post-middle">
                <div class="post-picture">
                    <profile-picture>
                        <template slot="image">
                            <img :src="computedProfileUrl">
                        </template>
                    </profile-picture>
                </div>
                <div class="post-textarea">
                    <main-textarea 
                        :textPlaceholder="textPlaceholder"
                        v-model="textareaContent"
                    ></main-textarea>
                    <just-fade>
                        <template slot="transition" v-if="showPreview">
                            <file-preview
                                :show="showPreview"
                                :file="file"
                                @removeFile="removeFile"
                            ></file-preview>
                        </template>
                    </just-fade>
                    <just-fade>
                        <template slot="transition" v-if="showMainPreview">
                            <main-preview
                                @clickedBadge="removePreview"
                                :file='computedPreviewFile'
                                :body='computedPreviewBody'
                                :title='computedPreviewTitle'
                                :heading='computedPreviewHeading'
                                :hasFile='hasPreviewFile'
                            >
                            </main-preview>
                        </template>
                    </just-fade>
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
                    titleText="post a book"></post-button>
                <post-button buttonText="R" 
                    @click="formType = 'riddle'"
                    titleText="post a riddle"
                ></post-button>
                <post-button buttonText="P" 
                    @click="formType = 'poem'"
                    titleText="post a poem"></post-button>
                <post-button buttonText="Q" 
                    @click="formType = 'question'"
                    titleText="post a question"></post-button>
                <post-button buttonText="A" 
                    @click="formType = 'activity'"
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
import FadeRight from '../components/transitions/FadeRight'
import ProfilePicture from '../components/profile/ProfilePicture'
import MainTextarea from '../components/MainTextarea'
import MainPreview from '../components/MainPreview'
import ProfileBar from '../components/profile/ProfileBar'
import ValidationError from '../components/ValidationError'
import {files} from '../services/helpers'
import {mapActions, mapGetters} from 'vuex'

    export default {
        components: {
            ValidationError,
            ProfileBar,
            MainPreview,
            MainTextarea,
            ProfilePicture,
            FadeRight,
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
                formType: '',
                files : null,
                textPlaceholder: "do you have anything in mind?",
                showMainPreview: false,
                mainPreviewData: [],
                hasPreviewFile: false,
                showProfiles: false,
                previewType: '',
                account: '',
                account_id: null,
            }
        },
        watch: {
            formType: {
                immediate: true,
                handler(newValue){ //for showing modal of and removing preview of post types
                    if (newValue && newValue != '') {
                        this.showModal = true
                        this.removeFile()
                        this.removePreview()
                    }
                }
            },
        },
        computed: {
            ...mapGetters(['getProfiles', 'getActiveProfile', 
                'profile/getActiveProfile']),
            computedPost(){
                return this.textareaContent != '' || this.file || 
                    (this.mainPreviewData && this.mainPreviewData.hasOwnProperty('published')) ?
                    true : false
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
                // let profilesArray = []
                // let computedArray = []
                // if (this.getUser) {
                //     profilesArray = this['getUser'].owned_profiles
                // } else {
                //     return null
                // }

                // if (profilesArray) {
                //     computedArray = profilesArray.map(el=>{
                //         return {
                //             name: el.profile_name ? el.profile_name : 'no name',
                //             params: {
                //                 account: el.account_type,
                //                 accountId: el.account_id,
                //             }
                //         }
                //     })

                //     return computedArray
                // } else {
                    
                // }
            },
            computedProfileUrl(){
                return this['profile/getActiveProfile'] ? 
                    this['profile/getActiveProfile'].url : 
                    this['getActiveProfile'] ?
                    this['getActiveProfile'].url : ''
            },
            computedFile() {
                return typeof this.file === File ? this.file : new File(["foo"], "foo.txt",{
                    type: 'text/plain'
                }) 
            },
            computedPreviewFile(){
                return this.mainPreviewData && this.mainPreviewData.file ?
                    this.mainPreviewData.file[0] : {}
            }, 
            computedPreviewTitle(){
                return this.mainPreviewData ?
                    this.previewType === 'book' || 
                    this.previewType === 'poem' ?
                    this.mainPreviewData.title : '' : ''
            },       
            computedPreviewHeading(){
                return this.mainPreviewData ?
                    this.previewType : ''
            },      
            computedPreviewBody(){
                if (this.mainPreviewData) {
                    if (this.previewType === 'book') {
                        return this.mainPreviewData.about
                    } else if (this.previewType === 'poem') {
                        return this.mainPreviewData.sections[0]
                    } else if (this.previewType === 'question') {
                        return this.mainPreviewData.question
                    } else if (this.previewType === 'activity') {
                        return this.mainPreviewData.description
                    } else if (this.previewType === 'riddle') {
                        return this.mainPreviewData.riddle
                    }
                }
            },    
            computedPreviewAuthor(){ //will have to adjust this when author search and add author
                return this.mainPreviewData ?
                    this.mainPreviewData.author : ''
            }, 
        },
        methods: {
            clickedProfile(data){
                this.account_id = data.account_id
                this.account = data.account
                this.showProfiles = false
                this.createPost()
            },
            ...mapActions(['profile/createPost']),
            removePreview(){
                this.showMainPreview = false
                this.hasPreviewFile = false
                this.previewType = ''
                this.mainPreviewData = []
            },
            clickedCreate(data){
                this.mainPreviewData = data
                this.previewType = this.formType
                this.showMainPreview = true
                this.hasPreviewFile = true
                this.closeCreatePost()
            },
            closeCreatePost(){
                this.showModal = false
                this.formType= ''
            },
            removeFile(){
                this.file = null
                this.clickedButton = ''
                this.showPreview = false
            },
            post(){
                if (this.computedProfiles.length > 1 && this.$route.name === "home") {
                    this.showProfiles = true
                } else {
                    this.account = this.computedProfiles[0].params.account_type
                    this.account_id = this.computedProfiles[0].params.account_id
                    this.createPost()
                }
            },
            createPost(){
                let fileType = ''
                let formData = new FormData

                // if (!this.showPreview) {
                if (this.file) {
                    formData.append('file', this.file)
                    formData.append('fileType', files.fileType(this.file))
                } else {
                    console.log('enters')
                    if (this.previewType != '') {
                        formData.append('type', this.previewType)
                    }
                    
                    if (this.previewType === 'book') {
                        formData.append('title', this.mainPreviewData.title)
                        formData.append('author', this.mainPreviewData.author)
                        formData.append('about', this.mainPreviewData.about)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'poem') {
                        formData.append('title', this.mainPreviewData.title)
                        formData.append('author', this.mainPreviewData.author)
                        formData.append('about', this.mainPreviewData.about)
                        formData.append('sections', JSON.stringify(this.mainPreviewData.sections))
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'riddle') {
                        formData.append('author', this.mainPreviewData.author)
                        formData.append('riddle', this.mainPreviewData.riddle)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'question') {
                        formData.append('question', this.mainPreviewData.question)
                        formData.append('published', this.mainPreviewData.published)
                    } else if (this.previewType === 'activity') {
                        formData.append('description', this.mainPreviewData.description)
                        formData.append('published', this.mainPreviewData.published)
                    }

                    if (this.mainPreviewData && this.mainPreviewData.file &&
                        this.mainPreviewData.file.length > 0) {
                        formData.append('previewFile', this.mainPreviewData.file[0])
                        formData.append('previewFileType', files.fileType(this.mainPreviewData.file[0]))
                    }
                }

                if (this.account_id) {
                    formData.append('account', this.account)
                    formData.append('account_id', this.account_id)
                } else {
                    formData.append('account', this.$route.params.account)
                    formData.append('account_id', this.$route.params.accountId)
                }

                formData.append('content', this.textareaContent)                
                
                this['profile/createPost'](formData)

                this.removePreview()
                this.textareaContent = ''
                this.file = null
                this.account = ''
                this.account_id = ''
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
        background-color: inherit;

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

            .profiles{
                position: absolute;
                width: 200px;
                right: 0;
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