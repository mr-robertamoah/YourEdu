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
                        class="d-none">
            </div>
        </template>
    </fade-left-fast>
</template>

<script>
import MainTextarea from './MainTextarea'
import PostButton from './PostButton'
import DotLoader from 'vue-spinner/src/DotLoader'
import ProfilePicture from './profile/ProfilePicture'
import FilePreview from './FilePreview'
import AutoAlert from './AutoAlert'
import JustFade from './transitions/JustFade'
import FadeLeftFast from './transitions/FadeLeftFast'
import ProfileBar from './profile/ProfileBar'
import { mapGetters, mapActions } from 'vuex'

    export default {
        components: {
            DotLoader,
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
            editableData: {
                immediate: true,
                handler(newValue, oldValue){
                    if (newValue) {
                        
                    }
                },
                deep: true
            },
            edit: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.answerText = this.editableData.body
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
                    if (this.editableData.answeredby_type.toLocaleLowerCase().includes('parent')) {
                        who['account'] = 'parent'
                    } else if (this.editableData.answeredby_type.toLocaleLowerCase().includes('learner')) {
                        who['account'] = 'learner'
                    } else if (this.editableData.answeredby_type.toLocaleLowerCase().includes('professional')) {
                        who['account'] = 'professional'
                    } else if (this.editableData.answeredby_type.toLocaleLowerCase().includes('facilitator')) {
                        who['account'] = 'facilitator'
                    }
                    who['accountId'] = this.editableData.answeredby_id
                    who['itemId'] = this.editableData.id
                } 

                this.$emit('addAnswer',{
                    file: this.file,
                    input: this.answerText,
                    who,
                })
            },
            // async clickedProfile(who){
            //     this.hideProfiles
            //     let formData = new FormData

            //     if (this.file) {
            //         formData.append('file', this.file)
            //     }

            //     if (this.commentText.length) {
            //         formData.append('body', this.commentText.trim())
            //     }

            //     formData.append('account', who.account)
            //     formData.append('accountId', who.accountId)                
                
            //     let response = null
            //     if (this.edit) {
            //         let data = {
            //             itemId: this.editableData.id,
            //         }
            //         response = await this['profile/updateComment']({data,formData})
            //     } else {
            //         let data = {
            //             onPostModal: this.onPostModal,
            //             item: this.what,
            //             itemId: this.id,
            //         }

            //         response = await this['profile/createComment']({data,formData})
            //     }
                
                
            //     if (response !== 'unsuccessful') {
            //         this.file = null
            //         this.alertSuccess = true
            //         this.alertDanger = false
            //         this.commentText = ''
            //         if (!this.edit) {
            //             this.$emit('postAddComplete','successful')
            //         }
            //         if (this.onPostModal) {
            //             this.$emit('postModalCommentCreated',response.comment)
            //         }                    
                    
            //     } else {
            //         this.alertSuccess = false
            //         this.alertDanger = true
            //         if (!this.edit) {
            //             this.$emit('postAddComplete','unsuccessful')
            //         }
            //     }
            // },
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