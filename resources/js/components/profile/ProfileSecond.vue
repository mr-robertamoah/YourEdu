<template>
    <div class="second-section">
        <div class="top">
            <div class="section user" :class="{'section-active': activateUserClass}"
                @click="activateUserSection"
                @mouseover="showEdit = true"
                @mouseleave="showEdit = false"
            >
                <font-awesome-icon :icon="['fa','user-circle']"></font-awesome-icon>
                <div class="edit">
                    <fade-left>
                        <template slot="transition" v-if="showEdit">
                            <black-white-badge
                                text="edit"
                                @click="editProfile"
                            ></black-white-badge>
                        </template>
                    </fade-left>
                </div>
            </div>
            <div class="section activity" :class="{'section-active': activateActivityClass}"
                @click="activateActivitySection"
            >
                <font-awesome-icon :icon="['fa','exclamation-circle']"></font-awesome-icon>
            </div>
        </div>
        <div class="bottom">
            <div class="user" 
                v-if="activitySection || activateUser"
                @mouseover="showEdit = true"
                @mouseleave="showEdit = false"
            > 
                <div>
                    <group-section heading="images" 
                        @clickedIcon="clickedIcon"
                        @getMedia="showMediaModalPublic"
                        @getPrivateMedia="showMediaModalPrivate"
                        :showIcon="true"
                        :showPrivate="computedOwner"
                    >
                        <template slot="body" v-if="computedImages">
                            <div class="content"
                                :key="image.id"
                                @click="clickedProfileMedia(image,'image')"
                                v-for="image in computedImages"
                            >
                                <img 
                                    :src="image.url" 
                                >
                            </div>
                        </template>
                        <template slot="body" v-if="!computedImages">
                            no images
                        </template>
                    </group-section>
                    <group-section heading="videos" 
                        @clickedIcon="clickedIcon"
                        @getMedia="showMediaModalPublic"
                        @getPrivateMedia="showMediaModalPrivate"
                        :showIcon="true"
                        :showPrivate="computedOwner"
                    >
                        <template slot="body" v-if="computedVideos">
                            <video
                                v-for="video in computedVideos"
                                :key="video.id"
                                :src="video.url"
                                @click="clickedProfileMedia(video,'video')"
                                controlslist="nodownload"
                            ></video>
                        </template>
                        <template slot="body" v-if="!computedVideos">
                            no videos
                        </template>
                    </group-section>
                    <group-section heading="audios" 
                        @clickedIcon="clickedIcon"
                        @getMedia="showMediaModalPublic"
                        @getPrivateMedia="showMediaModalPrivate"
                        :showIcon="true"
                        :showPrivate="computedOwner"
                    >
                        <template slot="body" v-if="computedAudios">
                            <audio 
                                v-for="audio in computedAudios"
                                :key="audio.id"
                                :src="audio.url" 
                                @click="clickedProfileMedia(audio,'audio')"
                                controlslist="nodownload"
                            ></audio>
                        </template>
                        <template slot="body" v-if="!computedAudios">
                            no audios
                        </template>
                    </group-section>
                    <profile-detail v-if="computedAbout">
                        <div slot="top">
                            About
                        </div>
                        <div slot="text">
                            {{computedAbout}}
                        </div>
                    </profile-detail>
                    <profile-detail v-if="computedInterests">
                        <div slot="top">
                            interests
                        </div>
                        <div slot="text">
                            {{computedInterests}}
                        </div>
                    </profile-detail>
                    <profile-detail v-if="computedPhoneNumbers"
                        :showIcon="true"
                        :title="'phones'"
                        @clickedIcon="clickedIcon"
                    >
                        <div slot="top">
                            phone numbers
                        </div>
                        <template slot="link">
                            <link-item
                                v-for="(phone) in computedPhoneNumbers"
                                :key="phone.id"
                                :id="phone.id"
                                :title="'phone'"
                                :seen="phone.show"
                                :unseen="!phone.show"
                                :item="phone.phone_number"
                                :actions="true"
                                @clickedShow="clickedShow"
                                @clickedRemove="clickedRemove"
                            ></link-item>
                        </template>
                    </profile-detail>
                    <profile-detail v-if="computedSocials"
                        :showIcon="true"
                        :title="'socials'"
                        @clickedIcon="clickedIcon"
                    >
                        <div slot="top">
                            links to media accounts
                        </div>
                        <template slot="link">
                            <link-item
                                v-for="(social) in computedSocials"
                                :key="social.id"
                                :id="social.id"
                                :title="'social'"
                                :seen="social.show"
                                :unseen="!social.show"
                                :item="social.url"
                                :actions="true"
                                @clickedShow="clickedShow"
                                @clickedRemove="clickedRemove"
                            ></link-item>
                        </template>
                    </profile-detail>
                    <profile-detail v-if="computedEmails"
                        :showIcon="true"
                        :title="'emails'"
                        @clickedIcon="clickedIcon"
                    >
                        <div slot="top">
                            emails
                        </div>
                        <template slot="link">
                            <link-item
                                v-for="(email) in computedEmails"
                                :key="email.id"
                                :id="email.id"
                                :title="'email'"
                                :seen="email.show"
                                :unseen="!email.show"
                                :item="email.email"
                                :actions="true"
                                @clickedShow="clickedShow"
                                @clickedRemove="clickedRemove"
                            ></link-item>
                        </template>
                    </profile-detail>
                    <profile-detail v-if="computedInterests">
                        <div slot="top">
                            interests
                        </div>
                        <div slot="text">
                            {{computedInterests}}
                        </div>
                    </profile-detail>
                    <profile-detail v-if="computedCompany">
                        <div slot="top">
                            Company
                        </div>
                        <div slot="text">
                            {{computedCompany}}
                        </div>
                    </profile-detail>
                    <profile-detail v-if="computedOccupation">
                        <div slot="top">
                            occupation
                        </div>
                        <div slot="text">
                            {{computedOccupation}}
                        </div>
                    </profile-detail>
                </div>
                <group-section>
                    <template slot="body">
                        <detail-showcase></detail-showcase>
                        <detail-showcase></detail-showcase>
                        <detail-showcase></detail-showcase>
                        <detail-showcase></detail-showcase>
                    </template>
                </group-section>
            </div>
            <div class="activity" 
                v-if="activitySection || activateActivity"
                
            >
                <div class="spinner">
                    <sync-loader 
                        :loading="computedPostCreating"
                    ></sync-loader>
                </div>
                <div class="extras-section">
                    <post-button buttonText="discussion" 
                        @click="clickedShowDiscussion"
                        titleText="have something to discuss?"></post-button>
                </div>
                <div class="loading" v-if="discussionLoading">
                    <pulse-loader :loading="discussionLoading" :size="'10px'"></pulse-loader>
                </div>
                <div class="alert" 
                    v-if="modalAlertMessage.length"
                    :class="{success:modalAlertSuccess, danger:modalAlertDanger}"
                >
                    {{modalAlertMessage}}
                </div>
                <post-create
                    v-if="computedPostCreate"
                ></post-create>
                <template v-if="!isFlagged && computedPosts">
                    <template
                        v-for="post in computedPosts"
                    >
                        <post-single
                            :key="`post.${post.id}`"
                            v-if="post.isPost"
                            :post="post"
                            @askLoginRegister="askLoginRegister"
                            @clickedMedia="clickedMedia"
                            @clickedShowPostComments="clickedShowPostComments"
                            @clickedShowPostPreview="clickedShowPostPreview"
                        ></post-single>
                        <discussion-single
                            v-if="post.isDiscussion"
                            :key="`discussion.${post.id}`"
                            :discussion="post"
                            @askLoginRegister="askLoginRegister"
                        ></discussion-single>
                    </template>
                </template>
                <post-none v-if="isFlagged"
                    :loading="unflagLoading"
                    @clickedUnflag="clickedUnflag"
                ></post-none>
                <div class="none" v-if="complete">
                    no more posts
                </div>
            </div>
        </div>
        <just-fade>
            <template slot="transition" v-if="showModal"
                @mainModalDisappear="modalDisappear"
            >
                <main-modal
                    @mainModalDisappear="showModal=false"
                    :alertMessage="modalAlertMessage"
                    :alertError="modalAlertError"
                    :alertSuccess="modalAlertSuccess"
                    @clearAlert="clearAlert"
                    :loading="modalLoading"
                >
                    <template slot="loading">
                        <sync-loader :loading="modalLoading"></sync-loader>
                    </template>
                    <template slot="main">
                        <welcome-form>
                            <template slot="input">
                                <div class="profile-section">
                                    <text-input
                                        v-model="inputText"
                                        :type="inputType"
                                        :placeholder="inputPlaceholder"
                                        :bottomBorder="true"
                                    ></text-input>
                                    <div class="option-section">
                                        <span>show on profile?</span>
                                        <div class="option" @click="clickedYes"
                                            :class="{yes:yes}"
                                        >
                                            yes
                                        </div>
                                        <div class="option" @click="clickedNo"
                                            :class="{no:no}"
                                        >
                                            no
                                        </div>
                                    </div>
                                    <div class="option-info">
                                        it will be shown on your profile by default
                                    </div>
                                </div>
                            </template>
                            <template slot="buttons">
                                <post-button
                                    buttonText="add"
                                    @click="clickedAdd"
                                ></post-button>
                            </template>
                        </welcome-form>
                    </template>
                </main-modal>
            </template>
        </just-fade>
        <just-fade>
            <template slot="transition" 
                v-if="showFileModal"
            >
                <main-modal
                    @mainModalDisappear="fileModalDisappear"
                    :alertMessage="modalAlertMessage"
                    :alertError="modalAlertError"
                    :alertSuccess="modalAlertSuccess"
                    @clearAlert="clearAlert"
                    :loading="modalLoading"
                >
                    <template slot="loading">
                        <sync-loader :loading="modalLoading"></sync-loader>
                    </template>
                    <template slot="main">
                        <welcome-form>
                            <template slot="input">
                                <file-preview
                                    :show="showMediaPreview"
                                    :file="file"
                                    @removeFile="removeMedia"
                                ></file-preview>
                                <div class="upload" 
                                    @click="clickedAddFile"
                                    :title="addMediaTitle"
                                >
                                    <font-awesome-icon
                                        :icon="addMediaIcon"
                                    ></font-awesome-icon>
                                </div>
                                <div class="profile-section">
                                    <div class="option-section">
                                        <span>show on profile?</span>
                                        <div class="option" @click="clickedYes"
                                            :class="{yes:yes}"
                                        >
                                            yes
                                        </div>
                                        <div class="option" @click="clickedNo"
                                            :class="{no:no}"
                                        >
                                            no
                                        </div>
                                    </div>
                                    <div class="option-info">
                                        it will be shown on your profile by default
                                    </div>
                                </div>
                                <input type="file" ref="inputfile" class="hidden"
                                    @change="inputFileChange">
                            </template>
                            <template slot="buttons" v-if="showMediaAdd">
                                <post-button
                                    buttonText="add"
                                    @click="clickedAddMedia"
                                ></post-button>
                            </template>
                        </welcome-form>
                    </template>
                </main-modal>
            </template>
        </just-fade>
        <just-fade>
            <template slot="transition" v-if="showMediaModal">
                <media-modal
                    @mainModalDisappear="mediaModalDisappear"
                    @mainModalAppear="mediaModalAppear"
                    :mediaData="mediaData"
                    :show="showMediaModal"
                    :loading="mediaLoading"
                    :showMore="showMediaMore"
                    :main="showMediaMain"
                    @showMoreMedia="showMoreMedia"
                    @clickedMediaIcon="clickedMediaIcon"
                >
                    <template slot="loading">
                        <sync-loader :loading="mediaLoading"></sync-loader>
                    </template>
                </media-modal>
            </template>
        </just-fade>
        
        <!-- create discussion -->
        <create-discussion
            v-if="showCreateDiscussion"
            :show="showCreateDiscussion"
            @createDiscussionDisappear="clickedCloseDiscussion"
            @clickedCreate="clickedCreateDiscussion"
        ></create-discussion>
    </div>
</template>

<script>
import PostButton from '../PostButton.vue'
import Badge from '../Badge.vue'
import DetailShowcase from './DetailShowcase.vue'
import ProfilePicture from './ProfilePicture.vue'
import ProfileDetail from './ProfileDetail.vue'
import MainTextarea from '../MainTextarea.vue'
import FadeLeft from '../transitions/FadeLeft.vue'
import BlackWhiteBadge from '../BlackWhiteBadge.vue'
import PostCreate from '../PostCreate.vue'
import PostSingle from '../PostSingle.vue'
import LinkItem from '../profile/LinkItem.vue'
import FilePreview from '../FilePreview.vue'
import TextInput from '../TextInput.vue'
import DiscussionSingle from '../DiscussionSingle.vue'
import PostNone from '../PostNone.vue'
import GroupSection from './GroupSection.vue'
import { mapGetters, mapActions } from 'vuex'
import { useRoute } from 'vue-router'

    export default {
        name: 'SecondSection',
        components: {
            Badge,
            DetailShowcase,
            PostButton,
            GroupSection,
            PostNone,
            DiscussionSingle,
            TextInput,
            FilePreview,
            LinkItem,
            PostSingle,
            
            PostCreate,
            BlackWhiteBadge,
            FadeLeft,
            MainTextarea,
            ProfileDetail,
            ProfilePicture,
        },
        props: {
            account: {
                type: String,
                default: ''
            },
            isFlagged: {
                type: Boolean,
                default: false
            },
            unflagLoading: {
                type: Boolean,
                default: false
            },
            accountId: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                activateUserClass: false,
                activateActivityClass: false,
                activateUser: false,
                activateActivity: false,
                activitySection: false,
                showEdit: false,
                complete: false,
                showModal: false,
                yes: false,
                no: false,
                inputText: '',
                inputType: 'text',
                addType: '',
                inputPlaceholder: '',
                modalLoading: false,
                nextPage: 1,
                //add media
                showFileModal: false,
                showMediaPreview: false,
                addMediaIcon: ['fa','file-image'],
                file: null,
                mediaAcceptType: '',
                showMediaAdd: true,
                addMediaTitle: '',
                //alert
                modalAlertMessage: '',
                modalAlertError: false,
                modalAlertSuccess: false,
                mediaData: {
                    media: [],
                    mediaType: ''
                },
                mediaLoading: false,
                showMediaModal: false,
                showMediaMain: false,
                showMediaMore: false,
                showMoreText: '',
                getMediaType: 'public',
                mediaType: '',
                //discussion
                showCreateDiscussion: false,
                discussionLoading: false,
            }
        },
        computed: {
            computedOwner(){
                if (this.getUserId && this['profile/getProfile']) {
                    
                    return this.getUserId === this['profile/getProfile'].user_id ? true : false
                }
                return false
            },
            ...mapGetters(['profile/getProfile','profile/getAccount','getUserId',
                'profile/getMsg','profile/getPostingStatus','profile/profilePosts',
                'profile/getLoadingStatus','profile/getPostsDone','profile/getNextPage',
                'profile/getStateMedia','profile/getMoreMedia']),
            computedImages(){
                return this['profile/getProfile'] ? this['profile/getProfile'].images : null 
            },
            computedVideos(){
                return this['profile/getProfile'] ? this['profile/getProfile'].videos : null 
            },
            computedAudios(){
                return this['profile/getProfile'] ? this['profile/getProfile'].audios : null 
            },
            computedEmails(){
                return this['profile/getProfile'] ? this['profile/getProfile'].owner.emails : null 
            },
            computedSocials(){
                return this['profile/getProfile'] ? this['profile/getProfile'].socials : null
            },
            computedPosts(){
                return this['profile/profilePosts'] && this['profile/profilePosts'].length > 0 ? 
                    this['profile/profilePosts'] : null
            },
            computedRoute() {
                return useRoute()
            },
            computedAbout() {
                return this['profile/getProfile'] && this['profile/getProfile'].about ?
                    this['profile/getProfile'].about : 'nothing yet'
            },
            computedCompany() {
                return this['profile/getProfile'] && this['profile/getProfile'].company ? 
                    this['profile/getProfile'].company : 'nothing yet'
            },
            computedInterests() {
                return this['profile/getProfile'] && this['profile/getProfile'].interests ?
                    this['profile/getProfile'].interests : 'nothing yet'
            },
            computedOccupation() {
                return this['profile/getProfile'] && this['profile/getProfile'].occupation ?
                    this['profile/getProfile'].occupation : 'nothing yet'
            },
            computedLocation() {
                return this['profile/getProfile'] && this['profile/getProfile'].hasOwnProperty('location') &&
                    this['profile/getProfile'].location != null ? 
                    this['profile/getProfile'].location : 'nothing yet'
            },
            computedPhoneNumbers() {
                return this['profile/getProfile'] && this['profile/getProfile'].hasOwnProperty('phoneNumbers') ? 
                    this['profile/getProfile'].phoneNumbers : []
            },
            computedSubjects() {
                return this['profile/getProfile'].hasOwnProperty('subjects') &&
                    this['profile/getProfile'].subjects != null ? 
                    this['profile/getProfile'].subjects : []
            },
            computedGrades() {
                return this['profile/getProfile'].hasOwnProperty('grades') &&
                    this['profile/getProfile'].grades != null ? 
                    this['profile/getProfile'].grades : []
            },
            computedSchools() {
                return this['profile/getProfile'].hasOwnProperty('schools') &&
                    this['profile/getProfile'].schools != null ? 
                    this['profile/getProfile'].schools : []
            },
            computedGroups() {
                return this['profile/getProfile'].hasOwnProperty('groups') &&
                    this['profile/getProfile'].groups != null ? 
                    this['profile/getProfile'].groups : []
            },
            computedClasses() {
                return this['profile/getProfile'].hasOwnProperty('classes') &&
                    this['profile/getProfile'].classes != null ? 
                    this['profile/getProfile'].classes : []
            },
            computedCurricula() {
                return this['profile/getProfile'].hasOwnProperty('curricula') &&
                    this['profile/getProfile'].curricula != null ? 
                    this['profile/getProfile'].curricula : []
            },
            computedExtracurriculums() {
                return this['profile/getProfile'].hasOwnProperty('extracurriculums') &&
                    this['profile/getProfile'].extracurriculums != null ? 
                    this['profile/getProfile'].extracurriculums : []
            },
            computedPostCreate(){
                return this['profile/getPostingStatus'] ? false : 
                    this.getUserId &&  this.getUserId === this['profile/getProfile'].user_id ?
                    true : false
            },
            computedPostCreating(){
                return this['profile/getPostingStatus']
            },
        },
        methods: {
            ...mapActions(['profile/getProfilePosts','profile/addInfo','profile/markInfo',
                'profile/removeInfo','profile/uploadFile','profile/getMedia',
                'profile/getPrivateMedia','profile/changeMedia','profile/deleteMedia']),
            clickedShowDiscussion(){
                this.showCreateDiscussion = true
            },
            clickedCloseDiscussion(){
                this.showCreateDiscussion = false
            },
            async clickedCreateDiscussion(data){
                let response,
                    formData = new FormData,
                    where = this.computedRoute.name

                this.discussionLoading = true

                formData.append('account', data.account)
                formData.append('accountId', data.accountId)
                formData.append('title', data.title)
                formData.append('type', data.type)
                formData.append('allowed', data.allowed)
                formData.append('restricted', JSON.stringify(data.restricted))
                formData.append('preamble', data.preamble)
                data.files.forEach(file=>{
                    formData.append('file[]', file)
                })
                if (data.postAttachments.length) {
                    formData.append('attachments', JSON.stringify(data.postAttachments.map(attachment=>{
                        return {
                            attachable: attachment.type.slice(0,attachment.type.length - 1),
                            attachableId: attachment.data.id
                        }
                    })))
                }

                response = await this['profile/createDiscussion']({formData,where})

                this.discussionLoading = false
                if (response.status) {
                    this.modalAlertSuccess = true
                    this.modalAlertMessage = 'discussion created successfully'
                } else {
                    console.log('response :>> ', response);
                    this.modalAlertError = true
                    this.modalAlertMessage = 'discussion creation failed'
                }
                this.clearAlert()
            },
            clickedUnflag(){
                this.$emit('clickedUnflag')
            },
            clickedShowPostComments(data){
                this.$emit('clickedShowPostComments',data)
            },
            clickedShowPostPreview(data){
                this.$emit('clickedShowPostPreview',data)
            },
            async clickedMediaIcon(iconData){
                let data = {
                    account: this.computedRoute.params.account,
                    accountId: this.computedRoute.params.accountId,
                    media: this.mediaType,
                    mediaId: iconData.mediaId
                }
                let response = null
                if (iconData.type === 'change') {
                    response = await this['profile/changeMedia'](data)
                } else if (iconData.type === 'delete') {
                    response = await this['profile/deleteMedia'](data)
                }

                if (response !== 'unsuccessful') {
                    let mediaIndex = this.mediaData.media.findIndex(media=>{
                        return media.id === iconData.mediaId
                    })
                    if (mediaIndex > -1) {
                        this.mediaData.media.splice(mediaIndex,1)
                    }
                }
            },
            showMediaModalPrivate(data){ //method to indicate its private and get modal up 
                this.getMediaType = 'private'
                this.showMediaModal = true
                this.mediaType = data
                this.mediaData.mediaType = data
            },
            showMediaModalPublic(data){
                this.getMediaType = 'public'
                this.showMediaModal = true
                this.mediaType = data
                this.mediaData.mediaType = data
            },
            mediaModalAppear(){
                if (this.getMediaType === 'public') {
                    this.getMedia(this.mediaType)
                } else if (this.getMediaType === 'private') {
                    this.getPrivateMedia(this.mediaType)
                }
            },
            showMoreMedia(){
                if (this['profile/getMoreMedia']) {
                    if (this.getMediaType === 'public') {
                        this.getMedia(this.mediaType)
                    } else if (this.getMediaType === 'private') {
                        this.getPrivateMedia(this.mediaType)
                    }
                }
            },
            async getMedia(mediaType){
                this.getMediaType = 'public'
                this.mediaLoading = true
                let data = {
                    account: this.computedRoute.params.account,
                    accountId: this.computedRoute.params.accountId,
                    nextPage: this.nextPage
                }
                if (mediaType.includes('image')) {
                    data['media'] = 'images'
                } else if (mediaType.includes('audio')) {
                    data['media'] = 'videos'
                } else if (mediaType.includes('video')) {
                    data['media'] = 'audios'
                }

                let response = await this['profile/getMedia'](data)

                this.mediaLoading = false
                if (response !== 'unsuccessful' && response.data.length) {
                    console.log(response.data)
                    response.data.forEach(media=>{
                        this.mediaData.media.push(media)
                    })
                }
                if (response.links.next) {
                    this.nextPage += 1
                    this.showMoreMedia = true
                } else {
                    this.showMoreMedia = false
                }
            },
            async getPrivateMedia(mediaType){
                this.getMediaType = 'private'
                this.mediaLoading = true
                let data = {
                    account: this.computedRoute.params.account,
                    accountId: this.computedRoute.params.accountId,
                    nextPage: this.nextPage
                }
                if (mediaType.includes('image')) {
                    data['media'] = 'images'
                } else if (mediaType.includes('audio')) {
                    data['media'] = 'videos'
                } else if (mediaType.includes('video')) {
                    data['media'] = 'audios'
                }

                let response = await this['profile/getPrivateMedia'](data)

                this.mediaLoading = false
                if (response !== 'unsuccessful' && response.data.length) {
                    console.log(response.data)
                    response.data.forEach(media=>{
                        this.mediaData.media.push(media)
                    })
                }
                if (response.links.next) {
                    this.nextPage += 1
                    this.showMoreMedia = true
                } else {
                    this.showMoreMedia = false
                }
            },
            clickedProfileMedia(media,mediaType){
                this.clickedMedia({media,mediaType})
            },
            clickedMedia(data){
                this.$emit('clickedMedia',data)
            },
            async clickedShow(clickedData){
                let data = {}

                if (clickedData.title === 'email') {
                    data['item'] = 'email'
                } else if (clickedData.title === 'social') {
                    data['item'] = 'social'
                } else if (clickedData.title === 'phone') {
                    data['item'] = 'phone'
                }
                data['id'] = clickedData.id

                let response = await this['profile/markInfo'](data)

                if (response === 'successful') {
                    
                }

            },
            async clickedRemove(clickedData){
                let data = {}

                if (clickedData.title === 'email') {
                    data['item'] = 'email'
                } else if (clickedData.title === 'social') {
                    data['item'] = 'social'
                } else if (clickedData.title === 'phone') {
                    data['item'] = 'phone'
                }
                data['id'] = clickedData.id

                let response = await this['profile/removeInfo'](data)

                if (response === 'successful') {
                    
                }
            },
            clearAlert(){
                this.modalAlertSuccess = false
                this.modalAlertError = false
                this.modalAlertMessage = ''
            },
            clickedAddFile(){ 
                this.showMediaPreview = false
                this.$refs.inputfile.setAttribute('accept', this.mediaAcceptType)
                this.$refs.inputfile.click()
            },
            mediaModalDisappear(){
                this.showMediaModal = false
                this.mediaData.media = []
                this.mediaData.mediaType = ''
                this.nextPage = 1
            },
            fileModalDisappear(){
                this.showFileModal=false
                this.removeMedia()
            },
            removeMedia(){
                this.file = null
            },
            async clickedAddMedia(){
                if (this.file) {
                    this.modalLoading = true
                    this.showMediaAdd = false
                    let formData = new FormData
                    let profileId = this['profile/getProfile'].id

                    formData.append('file', this.file)
                    formData.append('account', this.computedRoute.params.account)
                    formData.append('accountId', this.computedRoute.params.accountId)

                    if (this.no) {
                        formData.append('show','PRIVATE')
                    } else {
                        formData.append('show','PUBLIC')
                    }

                    let data = {
                        profileId, formData
                    }
                    let response = await this['profile/uploadFile'](data)

                    this.modalLoading = false
                    if (response === 'successful') {
                        this.modalAlertSuccess = true
                        this.modalAlertMessage = `successfully uploaded of ${this.addType}`
                    } else {
                        this.modalAlertError = true
                        this.modalAlertMessage = `upload of ${this.addType} was unsuccessful`
                        this.showMediaAdd = true
                    }
                } else {
                    this.modalAlertError = true
                    this.modalAlertMessage = this.addType !== 'video' ?
                        `please select an ${this.addType} first` : 'please select a video first'
                }
            },
            async clickedAdd(){
                if (this.inputText && this.inputText !== '') {
                    this.modalLoading = true
                    let data = {}

                    if (this.addType === 'email') {
                        data['email'] = this.inputText.trim()
                    } else if (this.addType === 'social') {
                        data['social'] = this.inputText.trim()
                    } else if (this.addType === 'phone') {
                        data['phone'] = this.inputText.trim()
                    }

                    if (this.no) {
                        data['show'] = false
                    } else {
                        data['show'] = true
                    }

                    let mainData = {
                        profile_id: this['profile/getProfile'].id,
                        data
                    }

                    let response = await this['profile/addInfo'](mainData)

                    this.modalLoading = false
                    if (response === 'successful') {
                        this.modalAlertSuccess = true
                        this.modalAlertError = false
                        this.modalAlertMessage = this.addType === 'email' ? 
                            'email was successfully added' : 
                            this.addType === 'social' ? 'social medai link was successfully added' : 
                            this.addType === 'phone' ? 'phone number was successfully added' : ''
                    } else{
                        this.modalAlertSuccess = false
                        this.modalAlertError = true

                        if (response.hasOwnProperty('email')) {
                            this.modalAlertMessage = response.email.toString()
                        } else if (response.hasOwnProperty('phone')) {
                            this.modalAlertMessage = response.phone.toString()
                        } else if (response.hasOwnProperty('url')) {
                            this.modalAlertMessage = response.phone.toString()
                        }
                        
                    }

                } else {
                    this.modalAlertError = true
                    this.modalAlertMessage = this.addType === 'email' ? 
                        'Please enter an email.' : 'Please enter a link to your social media account.'
                }
            },
            inputFileChange(event){
                let file = event.target.files[0]
                this.showMediaPreview = true
                this.file = file
            },
            clickedYes(){
                this.no = false
                this.yes = true
            },
            clickedNo(){
                this.yes = false
                this.no = true
            },
            clickedIcon(data){
                if (data === 'emails') {
                    this.inputPlaceholder = 'your email'
                    this.addType = 'email'
                    this.inputType = 'email'
                    this.showModal = true
                } else if (data === 'socials') {
                    this.inputPlaceholder = 'your social media url'
                    this.addType = 'social'
                    this.inputType = 'text'
                    this.showModal = true
                } else if (data === 'phones') {
                    this.inputPlaceholder = 'your phone number'
                    this.addType = 'phone'
                    this.inputType = 'text'
                    this.showModal = true
                } else if (data === 'images') {
                    this.inputPlaceholder = 'your phone number'
                    this.addType = 'image'
                    this.addMediaTitle = 'add an image'
                    this.addMediaIcon = ['fa','file-image']
                    this.mediaAcceptType = 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp'
                    this.showFileModal = true
                } else if (data === 'videos') {
                    this.inputPlaceholder = 'your phone number'
                    this.addType = 'video'
                    this.addMediaTitle = 'add a video'
                    this.addMediaIcon = ['fa','file-video']
                    this.mediaAcceptType = 'video/webm,video/mp4,video/ogg'
                    this.showFileModal = true
                } else if (data === 'audios') {
                    this.inputPlaceholder = 'your phone number'
                    this.addType = 'audio'
                    this.addMediaTitle = 'add an audio'
                    this.addMediaIcon = ['fa','file-audio']
                    this.mediaAcceptType = 'audio/mpeg,audio/ogg,audio/wav'
                    this.showFileModal = true
                }
            },
            modalDisappear(){
                this.showModal = false
            },
            askLoginRegister(){
                this.$emit('askLoginRegister','profileSsecond')
            },
            editProfile(){
                this.$emit('editProfile')
            },
            activateUserSection(){
                if (window.innerHeight <= 1100) {
                    this.activateUser=true
                    this.activateUserClass=true
                    this.activateActivity=false
                    this.activateActivityClass=false
                }else{
                    this.activateUser=true
                    this.activateActivity=true
                    this.activateUserClass=true
                    this.activateActivityClass=true
                }
            },
            activateActivitySection(){
                if (window.innerHeight <= 1100) {
                    this.activateUser=false
                    this.activateUserClass=false
                    this.activateActivity=true
                    this.activateActivityClass=true
                }else{
                    this.activateUser=true
                    this.activateActivity=true
                    this.activateUserClass=true
                    this.activateActivityClass=true
                }
            },
            resizeAction(){
                if (window.innerWidth > 1100) {
                    this.activitySection=true
                }else{
                    this.activitySection=false
                    if (this.activateUser===true) {
                        this.activateUser=true
                        this.activateActivity=false
                    } else {
                        this.activateUser=false
                        this.activateActivity=true
                    }
                        
                }
            },
        },
        created () {
            if (window.innerWidth <= 1100) {
                this.activateUser=true
                this.activateUserClass=true
                this.activateActivity=false
                this.activateActivityClass=false
            } else{
                this.activitySection=true
            }

            window.addEventListener('resize',this.resizeAction)
        },
    }
</script>

<style lang="scss" scoped>

    $profile-main-color: rgba(127, 255, 212, .9);
    $profile-picture-main-width: 140px;
    $profile-picture-color: $color-primary;
    $profile-font-main: 1.5vw;
    $profile-font-increase3: $profile-font-main + ($profile-font-main * 0.333);
    $profile-font-decrease3: $profile-font-main - ($profile-font-main * 0.333);

    .second-section{
        background-color: aliceblue;
        top: 10vw;
        width: 100%;
            
        .top{
            display: flex;
            justify-content: center;
            align-items: flex-end;
            text-align: center;

            .section{
                width: 50%;
                padding: 10px 0;
                cursor: pointer;
                border-bottom: 2px solid $profile-main-color;
                font-size: 20px;
                position: relative;

                .edit{
                    position: absolute;
                    width: 100%;
                    top: 2px;
                    left: 0;
                    height: 100%;
                }
            }

            .section-active{
                border-bottom: 2px solid $profile-main-color;
            }
        }

        .bottom{
            position: relative;
            min-height: 20px;
            margin: 0 20px;
            background-color: rgba(127, 255, 212, .1);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            .user,
            .activity{
                width: 50%;
                margin: 10px 0;
                min-height: 50vh;
                padding: 10px
            }

            .activity{
                
                .spinner{
                    text-align: center;
                }

                .extras-section{
                    width: 100%;
                    display: inline-flex;
                    justify-content: space-around;
                    flex-wrap: wrap;
                    align-items: center;
                }

                .loading,
                .alert{
                    text-align: center;
                    width: 100%;
                    font-size: 14px;
                }

                .alert{
                    color: white;
                }

                .success{
                    background-color: green;
                }
            }
        }

        .profile-section{
            display: inline-block;
            width: 100%;

            .option-section{
                font-size: 14px;
                display: inline-flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                margin-top: 5px;

                .option{
                    font-size: 14px;
                    padding: 5px;
                    cursor: pointer;
                    margin-left: 5px;

                    &:hover{
                        box-shadow: 0 0 3px gray;
                        transition: all 1s ease;
                    }
                }

                .yes{
                    color: green;
                    box-shadow: 0 0 3px gray;
                }

                .no{
                    color: red;
                    box-shadow: 0 0 3px gray;
                }
            }

            .option-info{
                font-size: 12px;
                color: gray;
                font-weight: 400;
                width: 100%;
                text-align: start;
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px) {
    $profile-picture-main-width: 120px;
    $profile-font-main: 2.5vw;
    $profile-font-increase3: $profile-font-main + ($profile-font-main * 0.333);
    $profile-font-decrease3: $profile-font-main - ($profile-font-main * 0.333);

        
    .second-section{
            
        .top{

            .user:hover,
            .activity:hover{
                background-color: $profile-main-color;
                transition: all 1s ease
            }

            .section{
                border: none;
            }

            .section-active{
                border-bottom: 2px solid $profile-main-color;
            }
        }

        .bottom{
            display: block;
            

            .user,
            .activity{
               width: 75%;
                margin: 10px auto;
                min-height: 50vh;
                padding: 10px
            }
        }
    }
}


@media screen and (max-width: 800px){
    $profile-picture-main-width: 100px;
    $profile-font-main: 3vw;
    $profile-font-increase3: $profile-font-main + ($profile-font-main * 0.333);
    $profile-font-decrease3: $profile-font-main - ($profile-font-main * 0.333);

        
    .second-section{
            
        .top{

            .user:hover,
            .activity:hover{
                background-color: $profile-main-color;
                transition: all 1s ease
            }

            .section{
                border: none;
            }
            
            .section-active{
                border-bottom: 2px solid $profile-main-color;
            }
        }

        .bottom{
            display: block;

            .user,
            .activity{
                width: 100%;
                margin: 10px 0;
                min-height: 50vh;
                padding: 10px
            }
        }

        .profile-section{

            .option-section{

                .option{
                    font-size: 12px;
                }
            }
        }
    }
}
</style>