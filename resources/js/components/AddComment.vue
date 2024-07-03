<template>
    <fade-left-fast>
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
                            :buttonText="buttonText"
                            @click="addComment"
                            v-if="computedPost"
                        ></post-button>
                        <div class="profiles"
                            v-if="showProfiles"
                        >
                            <span>
                                comment as
                            </span>
                            <div :key="key" v-for="(profile,key) in computedProfiles">
                                <profile-bar
                                    :smallType="true"
                                    :profile="profile"
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
import Profiles from '../mixins/Profiles.mixin';
import { mapGetters, mapActions } from 'vuex'
import { strings } from '../services/helpers'

    export default {
        components: {
            FadeLeftFast,
            JustFade,
            AutoAlert,
            FilePreview,
            ProfilePicture,
            PostButton,
            MainTextarea,
        },
        props: {
            what: {
                type: Object,
                default() {
                    return {}
                }
            },
            edit: {
                type: Boolean,
                default: false
            },
            onPostModal: { // true means its on the post modal
                type: Boolean,
                default: false
            },
            editableData: {
                type: Object,
                default(){
                    return {}
                }
            },
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
            schoolAdmin: {
                type: Object,
                default(){
                    return null
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
        mixins: [Profiles],
        data() {
            return {
                commentText: '',
                error: '',
                showPreview: false,
                clickedButton: '',
                imageType: 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp',
                videoType: 'video/webm,video/mp4,video/ogg',
                audioType: 'audio/mpeg,audio/ogg,audio/wav',
                file: null,
                alertMessage: '',
                alertSuccess: false,
                alertDanger: false,
                buttonText: 'add',
            }
        },
        watch: {
            showAddComment: {
                immediate: true,
                handler(newValue){
                    if (!newValue) {
                        this.commentText = ''
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
                        this.commentText = this.editableData.body
                        this.buttonText = 'save'
                    } else {
                        this.commentText = ''
                        this.buttonText = 'add'
                    }
                },
            }
        },
        computed: {
            ...mapGetters(['getActiveProfile', 'profile/getCommentingStatus'
                ,'profile/getActiveProfile', 'profile/getMsg']),
            computedCommenting(){
                return this['profile/getCommentingStatus'] &&  this.commentText && this.commentText.length ?
                    true : false
            },
            computedProfileUrl(){
                return this.account && this.account.url ? this.account.url :
                    this['profile/getActiveProfile'] ? 
                    this['profile/getActiveProfile'].url : 
                    this['getActiveProfile'] ?
                    this['getActiveProfile'].url : ''
            },
            computedShowIcons(){
                return this.commentText && this.commentText.length && !this.file ? true : false
            },
            computedPost(){
                return (this.file || (this.commentText && this.commentText.length)) && 
                    !this.computedCommenting ? true : false
            }
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
                if (this.edit) {
                    let who = {}
                    who['account'] = this.editableData.commentedby.account
                    who['accountId'] = this.editableData.commentedby.accountId
                    if (who.account === 'school') {                        
                        who['admin'] = this.schoolAdmin
                    }

                    this.clickedProfile(who)
                    return
                }

                if (! this.commentText.length) {
                    return    
                }

                if (this.account?.account) {
                    this.clickedProfile(this.account)
                    return
                }

                this.showProfiles = true
            },
            async clickedProfile(who){
                this.showProfiles = false
                let formData = new FormData,
                    data = {}

                if (this.file) {
                    formData.append('file', this.file)
                }

                if (this.commentText.length) {
                    formData.append('body', this.commentText.trim())
                }

                formData.append('account', who.account)
                formData.append('accountId', who.accountId) 
                if (this.schoolAdmin || who.admin) {
                    formData.append('adminId', this.schoolAdmin ? 
                        this.schoolAdmin.id : who.admin.id) 
                }              
                
                const route = useRoute()
                
                let response = null
                if (this.edit) {
                    data = {
                        commmentId: this.editableData.id,
                        where: route.name,
                        item: strings.getAccount(this.editableData.commentable_type),
                        itemId: this.editableData.commentable_id
                    }
                    
                    response = await this['profile/updateComment']({data,formData})
                } else {
                    data = {
                        onPostModal: this.onPostModal,
                        where: route.name,
                    }
                    formData.append('item', this.what.item),
                    formData.append('itemId', this.what.itemId),

                    response = await this['profile/createComment']({data,formData})
                }
                
                if (response !== 'unsuccessful') {
                    this.file = null
                    this.alertSuccess = true
                    this.alertDanger = false
                    // this.alertMessage = this['profile/getMsg']
                    this.commentText = ''
                    if (data.where === "dashboard") {
                        this.$emit('dashboardCommentCreated', response.comment)
                    } else if (!this.edit) {
                        this.$emit('postAddComplete','successful')
                        if (this.onPostModal) {
                            this.$emit('postModalCommentCreated',response.comment)
                        }                    
                    } else {
                        this.$emit('postModalCommentEdited',response.comment)
                    }
                    
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                    // this.alertMessage = this['profile/getMsg']
                    if (!this.edit) {
                        this.$emit('postAddComplete','unsuccessful')
                    }
                }
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