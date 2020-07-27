<template>
    <fade-left>
        <template slot="transition"
            v-if="showAddComment">
            <div class="add-comment-wrapper"
            >
                <div class="hide" @click="hideAddComment">...</div>
                <div class="top"
                    v-if="!edit"
                    @click="showProfiles = false">
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
                </div>
                <div class="middle"
                    @click.self="showProfiles = false">
                    <auto-alert
                        :message="alertMessage"
                        :success="alertSuccess"
                        :danger="alertDanger"
                        @hideAlert="hideAutoAlert"
                    ></auto-alert>
                    <div class="add-comment-profile" v-if="computedProfileUrl.length"
                    @click="showProfiles = false">
                        <profile-picture>
                            <template slot="image">
                                <img :src="computedProfileUrl">
                            </template>
                        </profile-picture>
                    </div>
                    <div class="add-comment">
                        <dot-loader
                            :loading="computedCommenting"
                        ></dot-loader>
                        <main-textarea 
                            @click="showProfiles = false"
                            textPlaceholder="have a comment?"
                            v-model="commentText"
                            v-if="!computedCommenting"
                        ></main-textarea>
                        <post-button 
                            buttonText="add"
                            @click="addComment"
                            v-if="(file || commentText.length) && !computedCommenting"
                        ></post-button>
                        <div class="profiles"
                            v-if="showProfiles"
                        >
                            <span>
                                comment as
                            </span>
                            <div :key="key" v-for="(profile,key) in computedProfiles">
                                <profile-bar
                                    :name="profile.name"
                                    :type="profile.params.account"
                                    :smallType="true"
                                    :routeParams="profile.params"
                                    :navigate="false"
                                    @clickedProfile="clickedProfile"
                                ></profile-bar>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lower">
                    <just-fade>
                        <template slot="transition" v-if="showPreview">
                            <file-preview
                                :show="showPreview"
                                :file="file"
                                @removeFile="removeFile"
                            ></file-preview>
                        </template>
                    </just-fade>
                </div>
                    <input type="file" ref="file" 
                        @change="fileChange"
                        class="d-none">
            </div>
        </template>
    </fade-left>
</template>

<script>
import MainTextarea from './MainTextarea'
import PostButton from './PostButton'
import DotLoader from 'vue-spinner/src/DotLoader'
import ProfilePicture from './profile/ProfilePicture'
import FilePreview from './FilePreview'
import AutoAlert from './AutoAlert'
import JustFade from './transitions/JustFade'
import FadeLeft from './transitions/FadeLeft'
import ProfileBar from './profile/ProfileBar'
import { mapGetters, mapActions } from 'vuex'

    export default {
        components: {
            DotLoader,
            ProfileBar,
            FadeLeft,
            JustFade,
            AutoAlert,
            FilePreview,
            ProfilePicture,
            PostButton,
            MainTextarea,
        },
        props: {
            what: {
                type: String,
                default: ''
            },
            edit: {
                type: Boolean,
                default: false
            },
            editableData: {
                type: Object,
                default(){
                    return {}
                }
            },
            id: {
                type: Number,
                default: 0
            },
            showAddComment:{
                type: Boolean,
                default:false
            }
        },
        data() {
            return {
                commentText: '',
                error: '',
                showProfiles: false,
                showPreview: false,
                clickedButton: '',
                imageType: 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp',
                videoType: 'video/webm,video/mp4,video/ogg',
                audioType: 'audio/mpeg,audio/ogg,audio/wav',
                file: null,
                alertMessage: '',
                alertSuccess: false,
                alertDanger: false,
            }
        },
        watch: {
            editableData: {
                immediate: true,
                handler(newValue, oldValue){
                    if (newValue) {
                        
                    }
                },
                deep: true
            }
        },
        computed: {
            ...mapGetters(['getProfiles','getActiveProfile', 'profile/getCommentingStatus'
                ,'profile/getActiveProfile', 'profile/getMsg']),
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedCommenting(){
                return this['profile/getCommentingStatus'] && this.commentText.length ?
                    true : false
            },
            computedProfileUrl(){
                return this['profile/getActiveProfile'] ? 
                    this['profile/getActiveProfile'].url : 
                    this['getActiveProfile'] ?
                    this['getActiveProfile'].url : ''
            },
            computedShowIcons(){
                return this.commentText.length && !this.file ? true : false
            },
            computedShowComponent(){
                return what.length && id > 0 ? true : false
            },
        },
        methods: {
            hideAutoAlert(){
                this.hideAddComment()
            },
            hideAddComment(){
                this.$emit('hideAddComment')
            },
            removeFile(){
                this.file = null
                this.clickedButton = ''
                this.showPreview = false
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
            addComment() {
                if (this.commentText.length) {
                    this.showProfiles = true

                    setTimeout(() => {
                        this.showProfiles = false
                    }, 4000);
                }
            },
            async clickedProfile(who){
                this.showProfiles = false
                let formData = new FormData

                if (this.file) {
                    formData.append('file', this.file)
                }

                if (this.commentText.length) {
                    formData.append('body', this.commentText.trim())
                }
                formData.append('account', who.account)
                formData.append('accountId', who.accountId)
                
                let data = {
                    item: this.what,
                    itemId: this.id,
                }
                let response = await this['profile/createComment']({data,formData})
                
                if (response === 'successful') {
                    this.file = null
                    this.alertSuccess = true
                    this.alertDanger = false
                    this.alertMessage = this['profile/getMsg']
                    this.commentText = ''
                    this.$emit('postAddComplete','successful')
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                    this.alertMessage = this['profile/getMsg']
                    this.$emit('postAddComplete','unsuccessful')
                }
            },
            ...mapActions(['profile/createComment']),
        },
    }
</script>

<style lang="scss" scoped>

    .add-comment-wrapper{
        width: 100%;

        .hide{
            width: 100%;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-align: end;
        }

        .top{
            margin: 5px 0;
            display: inline-flex;
            width: 100%;
            justify-content: flex-end;

            .icons{
                padding: 5px;
                font-size: 16px;
                cursor: pointer;
            }
        }

        .middle{
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            .add-comment-profile{
                width: 45px;
                height: 45px;
                margin-left: auto;
            }

            .add-comment{
                width: 80%;
                max-width: 300px;
                text-align: end;
                margin: 0 0 0 auto;

                & > button{
                    margin: 10px 0 0 ;
                }

                .profiles{
                    position: absolute;
                    width: 200px;
                    right: 0;
                    z-index: 1000;
                    text-align: start;

                    span{
                        font-size: 12px;
                        font-weight: 500;
                    }
                }
            }
        }
        
    }
</style>