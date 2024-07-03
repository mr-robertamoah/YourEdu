<template>
    <fade-left-fast>
        <template slot="transition"
            v-if="showAddAnswer">
            <div class="add-comment-wrapper"
            >
                <div class="hide" @click="hideAddAnswer">...</div>
                <div class="top"
                    v-if="!edit"
                    @click="hideProfiles">
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
                    @click.self="hideProfiles">
                    <div class="add-comment-profile" 
                        v-if="computedProfileUrl.length"
                        @click="hideProfiles">
                        <profile-picture>
                            <template slot="image">
                                <img :src="computedProfileUrl">
                            </template>
                        </profile-picture>
                    </div>
                    <div class="add-comment">
                        <dot-loader
                            :loading="computedCAnswering"
                        ></dot-loader>
                        <main-textarea 
                            @click="hideProfiles"
                            textPlaceholder="have an answer?"
                            v-model="answerText"
                            v-if="!computedAnswering"
                        ></main-textarea>
                        <post-button 
                            :buttonText="buttonText"
                            @click="addAnswer"
                            v-if="computedPost"
                        ></post-button>
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
                        class="hidden">
            </div>
        </template>
    </fade-left-fast>
</template>

<script>
import MainTextarea from './MainTextarea.vue'
import PostButton from './PostButton.vue'
import ProfilePicture from './profile/ProfilePicture.vue'
import FilePreview from './FilePreview.vue'
import AutoAlert from './AutoAlert.vue'
import JustFade from './transitions/JustFade.vue'
import FadeLeftFast from './transitions/FadeLeftFast.vue'
import ProfileBar from './profile/ProfileBar.vue'
import { mapGetters, mapActions } from 'vuex'
import { strings } from '../services/helpers'

    export default {
        components: {
            ProfileBar,
            FadeLeftFast,
            JustFade,
            AutoAlert,
            FilePreview,
            ProfilePicture,
            PostButton,
            MainTextarea,
        },
        props: {
            edit: { //for now, you cant add media when editing
                type: Boolean,
                default: false
            },
            answerEditable: {
                type: Object,
                default(){
                    return {}
                }
            },
            id: {
                type: Number,
                default: 0
            },
            showAddAnswer:{
                type: Boolean,
                default:false
            }
        },
        data() {
            return {
                answerText: '',
                error: '',
                showProfiles: false,
                showPreview: false,
                clickedButton: '',
                imageType: 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp',
                videoType: 'video/webm,video/mp4,video/ogg',
                audioType: 'audio/mpeg,audio/ogg,audio/wav',
                file: null,
                buttonText: 'add',
            }
        },
        watch: {
            showAddAnswer: {
                immediate: true,
                handler(newValue){
                    if (!newValue) {
                        this.answerText = ''
                    }
                }
            },
            answerEditable: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        
                    }
                },
                deep: true
            },
            edit: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.answerText = this.answerEditable.answer
                        this.buttonText = 'save'
                    } else {
                        this.answerText = ''
                        this.buttonText = 'add'
                    }
                },
            }
        },
        computed: {
            ...mapGetters(['getProfiles','getActiveProfile', 'profile/getCommentingStatus'
                ,'profile/getActiveProfile', 'profile/getMsg']),
            computedAnswering(){
                return this['profile/getCommentingStatus'] &&  this.answerText && this.answerText.length ?
                    true : false
            },
            computedProfileUrl(){
                return this['profile/getActiveProfile'] ? 
                    this['profile/getActiveProfile'].url : 
                    this['getActiveProfile'] ?
                    this['getActiveProfile'].url : ''
            },
            computedShowIcons(){
                return this.answerText && this.answerText.length && !this.file ? true : false
            },
            computedPost(){
                return (this.file || (this.answerText && this.answerText.length)) && 
                    !this.computedAnswering ? true : false
            }
        },
        methods: {
            hideProfiles(){
                this.$emit('hideProfiles')
            },
            hideAddAnswer(){
                this.$emit('hideAddAnswer')
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
            addAnswer() {
                let who = {}
                if (this.edit) {
                    who['account'] = strings.getAccount(this.editableData.answeredby_type)
                    who['accountId'] = this.editableData.answeredby_id
                    who['itemId'] = this.editableData.id
                }

                this.$emit('addAnswer',{
                    file: this.file,
                    input: this.answerText,
                    who,
                })
            },
            ...mapActions(['profile/createComment','profile/updateComment']),
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
            }
        }
        
    }
</style>