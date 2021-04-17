<template>
    <div>
        <just-fade>
            <template slot="transition" v-if="show">
                <main-modal
                    :show="show"
                    :mainOther="false"
                    :requests="false"
                    @mainModalDisappear="mainModalDisappear"
                    class="modal-wrapper"
                >
                    <template slot="main">
                        <welcome-form class="welcome-form"
                            :title="title"
                        >
                            <template slot="input">
                                <auto-alert
                                    :message="alertMessage"
                                    :success="alertSuccess"
                                    :danger="alertDanger"
                                    :sticky="true"
                                    @hideAlert="clearAlert"
                                ></auto-alert>
                                <div class="loading" v-if="loading">
                                    <pulse-loader :loading="loading"></pulse-loader>
                                </div>
                                <div class="section">Lesson Info</div>
                                <div class="form-edit">
                                    <text-input
                                        placeholder="lesson title"  
                                        :bottomBorder="true"
                                        :error="errorTitle"
                                        v-model="data.title"></text-input>
                                </div>
                                <div class="form-edit">
                                    <text-textarea type="text" 
                                        placeholder="lesson description"
                                        :bottomBorder="true"
                                        v-model="data.description"></text-textarea>
                                </div>
                                <div class="form-edit"
                                    v-if="!main"
                                >
                                    <text-input
                                        placeholder="age group"  
                                        :bottomBorder="true"
                                        v-model="data.ageGroup"></text-input>
                                </div>
                                <main-select
                                    v-if="edit"
                                    :items="['pending','accepted','declined','suspended']"
                                    :value="data.state"
                                    backgroundColor="white"
                                    @selection="stateSelection"
                                    class="other-input"
                                    placeholder="change state of class"
                                ></main-select>
                                <div class="attachment-heading">
                                    Note: setting a lesson as free or an introduction will be implemented in every course and/or class to which this lesson is added.
                                </div>
                                <main-checkbox
                                    v-model="data.intro"
                                    label="set it as an introduction"
                                    class="class-input"
                                ></main-checkbox>
                                <main-checkbox
                                    v-model="data.free"
                                    label="should be free"
                                    class="class-input"
                                ></main-checkbox>

                                <div class="section">Lesson Resources</div>
                                <div class="info" v-if="main">add links to this lesson</div>

                                <template v-if="edit">
                                    <div class="attachment-heading"
                                        v-if="mainLinks.length"
                                    >
                                        current links
                                    </div>
                                    <div class="attachments"
                                        v-if="mainLinks.length"
                                    >
                                        <link-badge
                                            v-for="(item,index) in mainLinks"
                                            :key="index"
                                            :link="item"
                                            class="class-badge"
                                            @clickedLink="selectedLinks"
                                            @clickedRemoveLink="removeLink(item,'main')"
                                        ></link-badge>
                                    </div>
                                    <div class="attachment-heading"
                                        v-if="removedLinks.length"
                                    >
                                        links to be removed
                                    </div>
                                    <div class="attachments danger"
                                        v-if="removedLinks.length"
                                    >
                                        <link-badge
                                            v-for="(item,index) in removedLinks"
                                            :key="index"
                                            :link="item"
                                            class="class-badge"
                                            @clickedRemoveLink="removeLink(item,'removed')"
                                        ></link-badge>
                                    </div>
                                </template>
                                <div class="attachment-heading"
                                    v-if="edit && links.length"
                                >
                                    new links
                                </div>
                                <add-link
                                    v-if="main"
                                    class="links-input"
                                    :editable="selectedLink"
                                    @linkAdded="getLinks"
                                    @editedLink="editedLink"
                                    @linkError="error"
                                ></add-link>
                                <files-preview-backend
                                    :mainMessage="computedMainMessage"
                                    :files="mainFiles"
                                    :checkLocalFiles="true"
                                    @error="error"
                                    :localFiles="computedFiles"
                                    @previewFiles="getPreviewFiles"
                                    :removedMessage="computedRemovedMessage"
                                ></files-preview-backend>
                                <div class="info" v-if="computedUploadMessage">
                                    {{computedUploadMessage}}
                                </div>
                                <div class="files">
                                    <div class="file"
                                        v-if="computedShowVideo"
                                        @click="clickedFileType('video')"
                                        :class="{active: fileType === 'video'}"
                                    >video</div>
                                    <div class="file"
                                        v-if="computedShowAudio"
                                        @click="clickedFileType('audio')"
                                        :class="{active: fileType === 'audio'}"
                                    >audio</div>
                                    <div class="file"
                                        v-if="computedShowImage"
                                        @click="clickedFileType('picture')"
                                        :class="{active: fileType === 'picture'}"
                                    >picture</div>
                                </div>
                                <div class="attachment-heading"
                                    v-if="computedHasLocalFile"
                                >
                                    these are the files to be uploaded
                                </div>
                                <div class="actions">
                                    <div class="action"
                                        @click="clickedAction('upload')"
                                        v-if="fileType.length"
                                        :title="`upload ${fileType}`"
                                    >
                                        <font-awesome-icon :icon="['fa','upload']"></font-awesome-icon>
                                    </div>
                                    <div class="action"
                                        v-if="fileType === 'video'" 
                                        @click="clickedAction('video')"
                                        title="record a video"
                                    >
                                        <font-awesome-icon :icon="['fa','video']"></font-awesome-icon>
                                    </div>
                                    <div class="action"
                                        v-if="fileType === 'picture'" 
                                        @click="clickedAction('camera')"
                                        title="snap a picture"
                                    >
                                        <font-awesome-icon :icon="['fa','camera']"></font-awesome-icon>
                                    </div>
                                    <div class="action"
                                        v-if="fileType === 'audio'" 
                                        @click="clickedAction('microphone')"
                                        title="record an audio"
                                    >
                                        <font-awesome-icon :icon="['fa','microphone']"></font-awesome-icon>
                                    </div>
                                </div>
                                <div class="media-section">
                                    <div class="media-item" v-if="imageFile">
                                        <div class="item-type" @click="clickedFile('image')">
                                            <font-awesome-icon :icon="['fa','image']"></font-awesome-icon>
                                        </div>
                                        <div class="item-info" @click="clickedFile('image')">
                                            {{imageFile.name}}
                                        </div>
                                        <div class="item-clear"
                                            @click="clickedBan('image')"
                                            :title="`remove ${fileType}`"
                                        >
                                            <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                        </div>
                                    </div>
                                    <div class="media-item" v-if="videoFile">
                                        <div class="item-type" @click="clickedFile('video')">
                                            <font-awesome-icon :icon="['fa','film']"></font-awesome-icon>
                                        </div>
                                        <div class="item-info" @click="clickedFile('video')">
                                            {{videoFile.name}}
                                        </div>
                                        <div class="item-clear"
                                            @click="clickedBan('video')"
                                            :title="`remove ${fileType}`"
                                        >
                                            <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                        </div>
                                    </div>
                                    <div class="media-item" v-if="audioFile">
                                        <div class="item-type" @click="clickedFile('audio')">
                                            <font-awesome-icon :icon="['fa','music']"></font-awesome-icon>
                                        </div>
                                        <div class="item-info" @click="clickedFile('audio')">
                                            {{audioFile.name}}
                                        </div>
                                        <div class="item-clear"
                                            @click="clickedBan('audio')"
                                            :title="`remove ${fileType}`"
                                        >
                                            <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                        </div>
                                    </div>
                                </div>
                                <fade-up>
                                    <template slot="transition" v-if="showFilePreview">
                                        <file-preview
                                            class="file-preview"
                                            :file="activeFile"
                                            :middle="true"
                                            @removeFile="removeFile"
                                        ></file-preview>
                                    </template>
                                </fade-up>
                                <input type="file" class="d-none" 
                                    @change="fileChange" 
                                    ref="inputfile"
                                    :accept="fileAccept"
                                >
                                <div class="section" 
                                    v-if="computedShowOwnership">Lesson Ownership</div>
                                <main-select
                                    class="other-input"
                                    v-if="computedShowOwnership"
                                    placeholder="select owner of this lesson"
                                    backgroundColor='white'
                                    :objects="computedPossibleOwners"
                                    :value="data.owner.name"
                                    @selection="ownerSelection"
                                ></main-select>
                                <div class="section">Lesson Attachments</div>
                                <template v-if="edit">
                                    <div class="attachment-heading"
                                        v-if="data.mainAttachments.length"
                                    >
                                        already attached
                                    </div>
                                    <div class="attachments"
                                        v-if="data.mainAttachments.length"
                                    >
                                        <attachment-badge
                                            v-for="(attachment,index) in data.mainAttachments"
                                            :key="index"
                                            :attachment="attachment.data"
                                            :hasClose="true"
                                            @removeAttachment="removeAttachment(attachment,'main')"
                                        ></attachment-badge>
                                    </div>
                                    <div class="attachment-heading"
                                        v-if="data.removedAttachments.length"
                                    >
                                        to be removed/unattached
                                    </div>
                                    <div class="attachments danger"
                                        v-if="data.removedAttachments.length"
                                    >
                                        <attachment-badge
                                            v-for="(attachment,index) in data.removedAttachments"
                                            :key="index"
                                            :attachment="attachment.data"
                                            :hasClose="true"
                                            @removeAttachment="removeAttachment(attachment,'removed')"
                                        ></attachment-badge>
                                    </div>
                                </template>

                                <div class="attachment-heading" 
                                    v-if="edit && data.attachments.length"
                                >
                                    new attachments
                                </div>
                                <div class="attachments"
                                    v-if="data.attachments.length"
                                >
                                    <attachment-badge
                                        v-for="(attachment,index) in data.attachments"
                                        :key="index"
                                        :attachment="attachment.data"
                                        :hasClose="true"
                                        @removeAttachment="removeAttachment"
                                    ></attachment-badge>
                                </div>
                                <post-attachment
                                    :show="true"
                                    :hasSelect="true"
                                    :mainSearchItem="data.attachmentType"
                                    :hasClose="false"
                                    @clickedAttachmentSelection="attachmentSelected"
                                    class="class-input"
                                ></post-attachment>

                                <div class="attachment-heading p-1">
                                    add lesson to courses, class' subjects and extracurriculums
                                </div>

                                <search-input
                                    class="search-input"
                                    placeholder="search for courses, extracurriculums and classes"
                                    @search="getSearchItemsText"
                                ></search-input>
                                <div class="class-payment course-classes-section">
                                    <div
                                        v-if="computedSpecificItems.length"
                                        class="class-wrapper"
                                    >
                                        <item-badge
                                            v-for="(item,index) in computedSpecificItems"
                                            :key="index"
                                            :item="item"
                                            type="class"
                                            :hasRemove="inItemSelection(item)"
                                            class="class-badge"
                                            @clickedItem="itemSelected"
                                            @clickedRemoveItem="removeItem"
                                        ></item-badge>
                                    </div>
                                    <div class="no-data" 
                                        v-if="!specificItemLoading && !computedSpecificItems.length">
                                        {{`nothing to show for this ${data.owner.account}`}}
                                    </div>
                                    <pulse-loader 
                                        :loading="specificItemLoading"
                                        size="12px"
                                        class="loading"
                                    ></pulse-loader>
                                </div>
                                <div class="get-more" 
                                    @click="getSpecificAccountItem"
                                    v-if="computedShowGetMore"
                                >
                                    get more
                                </div>
                                <!-- secondary selection of subjects or courses for classes -->
                                <div class="no-data text-center" v-if="selectedClass">
                                    {{`please select any of these ${selectedClass.structure ? selectedClass.structure : 'course sections'}`}}
                                </div>
                                <div
                                    v-if="selectedClass"
                                    class="class-wrapper"
                                >
                                    <item-badge
                                        v-for="(item,index) in selectedClass.items"
                                        :key="index"
                                        :item="item"
                                        :type="item.type"
                                        :hasRemove="inItemSelection(item)"
                                        class="class-badge"
                                        @clickedItem="itemSelected"
                                        @clickedRemoveItem="removeItem"
                                    ></item-badge>
                                </div>

                                <div class="section" v-if="computedShowLessonPayment">Payments</div>
                                <div class="attachment-heading" 
                                    v-if="data.mainPaymentData.length && computedShowLessonPayment"
                                >
                                    current payment types
                                </div>
                                <div class="attachments" 
                                    v-if="data.mainPaymentData.length && main"
                                >
                                    <div
                                        v-for="(item,index) in data.mainPaymentData"
                                        :key="index"
                                    >
                                        <price-badge
                                            v-if="item.type === 'price'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'main')"
                                            class="payment-badge"
                                        ></price-badge>
                                    </div>
                                </div>
                                
                                <div class="attachment-heading" 
                                    v-if="data.removedPaymentData.length && main"
                                >
                                    payment types to be removed
                                </div>
                                <div class="attachments danger" 
                                    v-if="data.removedPaymentData.length"
                                >
                                    <div
                                        v-for="(item,index) in data.removedPaymentData"
                                        :key="index"
                                    >
                                        <price-badge
                                            v-if="item.type === 'price'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></price-badge>
                                    </div>
                                </div>
                                <div class="attachment-heading" 
                                    v-if="edit && data.paymentData"
                                >
                                    new payment types
                                </div>
                                <payment-types
                                    v-if="!data.free && !data.intro"
                                    @paymentType="getPaymentType"
                                    :type="paymentType"
                                    :radioValue="data.paymentType"
                                    class="other-input"
                                    @paymentTypeError="error"
                                ></payment-types>

                                <div class="section" v-if="computedShowDiscussion">Discussion</div>
                                <main-checkbox
                                    v-model="data.discussion"
                                    v-if="computedShowDiscussion &&
                                        !data.discussionData.title.length"
                                    label="automatically add a discussion?"
                                    class="class-input"
                                ></main-checkbox>
                                <!-- discussion preview -->
                                <div class="discussion-preview"
                                    v-if="data.discussionData.title.length"
                                >
                                    <item-badge
                                        type="discussion"
                                        :item="data.discussionData"
                                        :hasRemove="true"
                                        @clickedRemoveItem="clearDiscussionData"
                                    ></item-badge>
                                </div>
                            </template>
                
                            <template slot="buttons">
                                <post-button 
                                    :buttonText="buttonText" 
                                    buttonStyle='success'
                                    @click="clickedCreate"
                                ></post-button>
                                <post-button 
                                    :buttonText="'close preview'" 
                                    buttonStyle='success'
                                    v-if="activeFile"
                                    @click="clickedClosePreview"
                                ></post-button>
                            </template>
                        </welcome-form>

                        <!-- media capture -->
                        <media-capture
                            v-if="showMediaCapture"
                            :show="showMediaCapture"
                            :type="mediaCaptureType"
                            @closeMediaCapture="closeMediaCapture"
                            @sendFile="receiveMediaCapture"
                        ></media-capture>
                    </template>
                </main-modal>
            </template>
        </just-fade>
        <create-discussion
            :show="data.discussion"
            v-if="data.discussion && main"
            :edit="edit"
            :auto="true"
            @clickedCreate="getDiscussionData"
            @createDiscussionDisappear="closeDiscussionModal"
        ></create-discussion>
    </div>
</template>

<script>
import TextInput from '../TextInput';
import TextTextarea from '../TextTextarea';
import PostButton from '../PostButton';
import MainCheckbox from '../MainCheckbox';
import PostAttachment from '../PostAttachment';
import MainSelect from '../MainSelect';
import AttachmentBadge from '../AttachmentBadge';
import CreateDiscussion from './CreateDiscussion';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import ItemBadge from '../dashboard/ItemBadge';
import AutoAlert from '../AutoAlert';
import AddLink from '../AddLink';
import PaymentTypes from '../PaymentTypes';
import FilesPreviewBackend from '../FilesPreviewBackend';
import FadeUp from '../transitions/FadeUp';
import MediaCapture from '../MediaCapture';
import PriceBadge from '../PriceBadge';
import LinkBadge from '../LinkBadge';
import WelcomeForm from '.././welcome/WelcomeForm';
import { bus } from '../../app';
import { mapActions, mapGetters } from 'vuex';
import DashboardCreateForm from '../../mixins/DashboardCreateForm.mixin';
import { strings } from '../../services/helpers';
    export default {
        components: {
            MainSelect,
            AttachmentBadge,
            CreateDiscussion,
            PulseLoader,
            ItemBadge,
            FilesPreviewBackend,
            PaymentTypes,
            AddLink,
            AutoAlert,
            MainCheckbox,
            PostAttachment,
            LinkBadge,
            PriceBadge,
            WelcomeForm,
            MediaCapture,
            FadeUp,
            PostButton,
            TextTextarea,
            TextInput,
        },
        props: {
            main: {
                type: Boolean,
                default: false
            },
            maxUploads: {
                type: Number,
                default: 3
            }
        },
        data() {
            return {
                imageFile: null,
                videoFile: null,
                audioFile: null,
                activeFile: null,
                fileType: '',
                fileAccept: '',
                showFilePreview: false,
                errorTitle: false,
                errorFile: false,
                mediaCaptureType: '',
                showMediaCapture: false,
                mainFiles: [],
                removedFiles: [],
                links: [],
                // hasLinks: false,
                mainLinks: [],
                removedLinks: [],
                editedLinks: [],
                selectedLink: null,
                selectedClass: null, //for class (subject) and courses (course sections)
            }
        },
        watch: {
            
        },
        watch: {
            showFilePreview(newValue) {
                if (!newValue) {
                    this.activeFile = null
                }
            },
            'data.title': {
                handler(newValue){
                    if (newValue && newValue.length) {
                        this.errorTitle = false
                    }
                },
            },
            'data.free': {
                handler(newValue){
                    if (newValue) {
                        this.data.paymentData = []
                        if (this.data.intro) {
                            this.data.intro = false
                        }
                    }
                },
            },
            'data.intro': {
                handler(newValue){
                    if (newValue && this.data.free) {
                        this.data.free = false
                    }
                },
            },
            'data.owner': {
                handler(newValue) {
                    if (newValue.account) {
                        this.debouncedSearchItems()
                    }
                }
            },
            errorFile(newValue){
                if (newValue) {
                    this.alertDanger = true
                    this.alertMessage = 'a lesson requires at least one file'
                    this.errorFile = false
                }
            },
            errorTitle(newValue){
                if (newValue) {
                    this.alertDanger = true
                    this.alertMessage = 'a lesson requires a title'
                }
            },
        },
        created () {
            this.title = 'create a lesson'
            if (this.main) {
                this.paymentType = 'one-time'
            }
            bus
            .$on('editLesson',(data)=>{
                this.setData(data)
            })
            .$on('lessonOwnership',()=>{
                this.hasOwnership = true
                this.data.owner = this.computedCreator
            })
        },
        mixins: [DashboardCreateForm],
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','dashboard/getCurrentAccount',
                'getUser']),
            computedFile() {
                return this.fileType === 'video' ? this.videoFile :
                    this.fileType === 'audio' ? this.audioFile :
                    this.fileType === 'picture' ? this.imageFile : null
            },
            computedFiles() {
                return [
                    this.videoFile, 
                    this.audioFile, 
                    this.imageFile
                ]
            },
            computedHasLocalFile() {
                return this.videoFile || this.audioFile || this.imageFile
            },
            computedShowDiscussion() {
                return !this.main ? false : this.data.hasDiscussion ? true : 
                    !this.edit ? true : false
            },
            computedShowLessonPayment() {
                return this.main && !this.data.free && !this.data.intro
            },
            computedShowVideo() {
                return !this.edit ? true : this.mainFiles.findIndex(file=>{
                    return file.type === 'video'
                }) === -1
            },
            computedShowAudio() {
                return !this.edit ? true : this.mainFiles.findIndex(file=>{
                    return file.type === 'audio'
                }) === -1
            },
            computedShowImage() {
                return !this.edit ? true : this.mainFiles.findIndex(file=>{
                    return file.type === 'image'
                }) === -1
            },
            computedUploadMessage() {
                return !this.edit ? 
                    `you can up upload ${strings.getNumberString(this.maxUploads)} different files for a lesson` :
                    this.computedUploadAllowance > 0 ? 
                    `you can upload ${strings.getNumberString(this.computedUploadAllowance)} more different files` : ''
            },
            computedUploadAllowance() { //during edit and tells number of uploads remaining
                return this.maxUploads - this.mainFiles.length
            },
            computedMainMessage() {
                return this.mainFiles.length ? 'current files' : ''
            },
            computedRemovedMessage() {
                return this.removedFiles.length ? 'files to be deleted' : ''
            },
        },
        methods: {
            ...mapActions(['dashboard/createLesson','dashboard/editLesson',
                'dashboard/getAccountSpecificItems']),
            mainModalDisappear() {
                this.data.owner = {name: ''}
                this.clearData()
                this.clearExtraData()
                this.$emit('createLessonDisappear')
            },
            getPreviewFiles(files) {
                this.removedFiles = files.removed
                this.mainFiles = files.main
            },
            clearExtraData() {
                this.specificItemDetails = []
                this.specificItemDetailsNextPage = 0
                this.specificItemLoading = false
                this.selectedClass = null
                this.selectedLink = null
                this.mainLinks = []
                this.removedLinks = []
                this.editedLinks = []
                // this.hasLinks = false
                this.mainFiles = []
                this.removedFiles = []
                this.fileType = ''
                this.imageFile = null
                this.videoFil = null
                this.audioFile = null
            },
            clickedClosePreview(){
                this.showFilePreview = false
                this.activeFile = null
            },
            getLinks(links) {
                this.links = links
            },
            getLinkError(error) {
                this.alertDanger = true
                this.alertMessage = error
            },
            setData(data) {
                this.data.title = data.title
                this.data.description = data.description
                this.data.state = data.state?.toLowerCase()
                this.data.lessonId = data.id
                this.data.items = []
                this.data.mainItems = []
                this.data.items.push(...data.items)
                this.data.mainItems.push(...this.data.items)
                this.data.mainAttachments = []
                if (data.attachments) {
                    this.data.mainAttachments.push(...data.attachments)
                } else {
                    this.data.mainAttachments.push(...data.courses)
                    this.data.mainAttachments.push(...data.programs)
                    this.data.mainAttachments.push(...data.grades)
                }
                //files
                this.mainFiles = []
                this.mainFiles.push(...data.videos)
                this.mainFiles.push(...data.images)
                this.mainFiles.push(...data.audios)
                //links
                this.mainLinks = []
                this.mainLinks.push(...data.links)
                // if (data.links.length) {
                //     this.hasLinks = true
                // }
                if (data.prices.length) {
                    this.data.mainPaymentData = []
                    this.data.mainPaymentData.push(...data.prices)
                } else {
                    this.type = 'free'
                }
                this.buttonText = 'edit'
                this.data.owner = {
                    name: data.ownedby.name,
                    account: data.ownedby.account,
                    accountId: data.ownedby.accountId,
                }
                this.checkDiscussion(data)
            },
            //courses
            inItemSelection(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            itemSelected(data) {
                console.log('data :>> ', data);
                if (data.type === 'class' ||
                    (data.type === 'course' && data.items.length > 0)) {
                    this.selectedClass = data
                    return
                } else if (this.selectedClass && !this.isInSelectedClass(data)) {
                    this.selectedClass = null
                }
                let index = this.findItemIndex(data)
                if (index === -1) {
                    this.data.items.push({
                        classId: this.selectedClass && data.type === 'subject' ? 
                            this.selectedClass.id : null,
                        id: data.id,
                        type: data.type,
                        description: data.description,
                        name: data.name
                    })
                    // this.data.items.push(data)
                }
            },
            isInSelectedClass(data){
                let index
                index = this.selectedClass.items.findIndex(item=>{
                    return item.type === data.type && 
                        item.id === data.id
                })
                if (index > -1) {
                    return true
                }
                return false
            },
            findItemIndex(data) {
                return this.data.items.findIndex(cl=>{
                    return cl.id === data.id && cl.type === data.type 
                })
            },
            removeItem(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.data.items.splice(index,1)
                }
            },
            removedItemsUpdate(data) {
                let index = this.data.removedItems.findIndex(cl=>{
                    return data.type === cl.type && data.id === cl.id &&
                        data.classId === cl.classId
                })
                if (index === -1) {
                    this.data.removedItems.push(data)
                }
            },
            //links
            editedLink(data) {
                let index = this.mainLinks.findIndex(link=>{
                    return link.id === data.id
                })
                if (index > -1) {
                    data.edited = true
                    this.mainLinks[index] = data
                    this.editedLinksUpdate(data)
                }
                this.selectedLink = null
            },
            selectedLinks(data) {
                this.selectedLink = data
            },
            findLinkIndex(data, type) {
                let links = type === 'main' ? this.mainLinks : 
                    type === 'removed' ? this.removedLinks : this.links
                return links.findIndex(link=>{
                    return link.id === data.id 
                })
            },
            removeLink(data, type) {
                let index = this.findLinkIndex(data, type)
                if (index > -1) {
                    if (type === 'main') {
                        this.mainLinks.splice(index,1)
                        this.removedLinks.push(data)
                        this.editedLinksUpdate(data, true)
                    } else if (type === 'removed') {
                        this.removedLinks.splice(index,1)
                        this.mainLinks.push(data)
                        if (data.edited) {
                            this.editedLinksUpdate(data)
                        }
                    }
                }
            },
            editedLinksUpdate(data, del = false) {
                let index = this.editedLinks.findIndex(link=>{
                    return data.id === link.id
                })
                if (!del && index === -1) {
                    this.editedLinks.push(data)
                } else if (!del && index > -1) {
                    this.editedLinks[index].name = data.name
                    this.editedLinks[index].description = data.description
                    this.editedLinks[index].link = data.link
                } else if (del && index > -1) {
                    this.editedLinks.splice(index,1)
                }
            },
            clickedBan(data){
                if (data === 'image') {
                    this.imageFile = null
                } else if (data === 'video') {
                    this.videoFile = null
                } else if (data === 'audio') {
                    this.audioFile = null
                }
                this.showFilePreview = false
            },
            clickedFile(data){
                if (data === 'image') {
                    this.activeFile = this.imageFile
                } else if (data === 'video') {
                    this.activeFile = this.videoFile
                } else if (data === 'audio') {
                    this.activeFile = this.audioFile
                }
                this.showFilePreview = !this.showFilePreview
            },
            async getSpecificAccountItem(){
                if (this.specificItemDetailsNextPage === null) {
                    return
                }
                let response,
                    data = {
                        account: this.data.owner.account,
                        accountId: this.data.owner.accountId,
                        items: ['courses', 'extracurriculums', 'classes'],
                        for: 'lesson',
                        search: this.searchItemsText
                    }

                this.specificItemLoading = true
                response = await this['dashboard/getAccountSpecificItems']({
                    data, nextPage: this.specificItemDetailsNextPage
                })
                this.specificItemLoading = false

                if (response.status) {
                    if (!this.specificItemDetailsNextPage) {
                        this.specificItemDetails = response.items
                    } else {
                        this.specificItemDetails.push(...response.items)
                    }
                    if (response.next) {
                        this.specificItemDetailsNextPage += 1
                    } else {
                        this.specificItemDetailsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            receiveMediaCapture(file){
                if (file.type.includes('image')) {
                    this.activeFile = this.imageFile = new File([file],'my_picture.png',{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('video')) {
                    this.activeFile = this.videoFile = new File([file],'my_video.webm',{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('audio')) {
                    this.activeFile = this.audioFile = new File([file],'my_audio.mp3',{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
                this.showFilePreview = true
            },
            closeMediaCapture(){
                this.showMediaCapture = false
            },
            clickedCreate(){
                if (this.loading) return
                if (!this.data.title.length) {
                    this.errorTitle = true
                    return 
                }
                if (!this.imageFile && !this.videoFile && !this.audioFile &&
                    !this.mainFiles.length > 0) {
                    this.errorFile = true
                    return 
                }
                if (this.main) {
                    this.createLesson()
                } else {
                    let lesson = []
                    lesson['title'] = this.data.title
                    lesson['description'] = this.data.description
                    lesson['ageGroup'] = this.data.ageGroup
                    lesson['file'] = this.computedFiles
                    this.$emit('clickedCreate',lesson)
                    this.clearData()
                    this.clearExtraData()
                    this.$emit('createLessonDisappear')
                }
            },
            async createLesson() {
                let msg = ''
                if (this.edit) {
                    if (!this.data.state.length) {
                        msg = 'extracurriculum requires a state'
                    }
                } else {
                    if (this.computedPossibleOwners.length > 1 && 
                        !this.data.owner.account) {
                        msg = 'Please select the owner of this lesson you are creating.'
                    } else if (this.data.paymentData !== 'free' && 
                        this.data.paymentData === null) {
                        msg = 'Please enter the required data for the payment.'                    
                    }
                }

                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return 
                }

                this.loading = true
                let response,
                    data = new FormData

                data.append('title', this.data.title)
                data.append('description', this.data.description)
                data.append('intro', JSON.stringify(this.data.intro))
                data.append('free', JSON.stringify(this.data.free))
                data.append('attachments', JSON.stringify(this.data.attachments.map(attachment=>{
                    return {
                        type: attachment.type.slice(0, attachment.type.length - 1),
                        id: attachment.data.id
                    }
                })))
                data.append('paymentType', this.data.paymentType)
                data.append('paymentData', JSON.stringify(this.data.paymentData))

                if (this.data.discussionData.title.length) {                        
                    data.append('discussionData', JSON.stringify(this.data.discussionData))
                    this.discussionFiles.forEach(file=>{
                        data.append('discussionFile[]', file)
                    })
                }
                data.append('items', JSON.stringify(this.data.items.map(cl=>{
                    return {
                        id: cl.id,
                        type: cl.type,
                        classId: cl.classId
                    }
                })))
                if (this.imageFile) {
                    data.append('files[]',this.imageFile)
                }
                if (this.videoFile) {
                    data.append('files[]',this.videoFile)
                }
                if (this.audioFile) {
                    data.append('files[]',this.audioFile)
                }
                
                data.append('paymentData', JSON.stringify(this.data.paymentData))
                data.append('account', this.computedAccount.account)
                data.append('accountId', this.computedAccount.accountId)
                data.append('owner', this.data.owner.account ? 
                    this.data.owner.account : 
                    this.computedAccount.account)
                data.append('ownerId', this.data.owner.account ? 
                    this.data.owner.accountId : 
                    this.computedAccount.accountId)
                data.append('links',JSON.stringify(this.links))
                if (this.edit) {
                    data.append('state', this.data.state)
                    data.append('lessonId', this.data.lessonId)
                    data.append('main', JSON.stringify(this.computedCheckMain))
                    data.append('removedAttachments', JSON.stringify(this.data.removedAttachments.map(attachment=>{
                        return {
                            type: attachment.type.slice(0, attachment.type.length - 1),
                            id: attachment.data.id
                        }
                    })))
                    data.append('removedPaymentData', JSON.stringify(
                        this.data.removedPaymentData.map(payment=>{
                            return {
                                type: payment.type,
                                id: payment.id
                            }
                        }
                    )))
                    this.data.mainItems.forEach(mainCl=>{ //check if class or program has been removed
                        let index = this.data.items.findIndex(cl=>{
                            return cl.type === mainCl.type && cl.id === mainCl.id &&
                                cl.classId === mainCl.classId
                        })
                        if (index === -1) {
                            this.removedItemsUpdate(mainCl)
                        }
                    })
                    data.append('removedItems', JSON.stringify(this.data.removedItems.map(attachment=>{
                        return {
                            type: attachment.type,
                            id: attachment.id,
                            classId: attachment.classId
                        }
                    })))
                    data.append('removedFiles', JSON.stringify(this.removedFiles.map(file=>{
                        return {
                            id: file.id,
                            type: file.type,
                        }
                    })))
                    data.append('removedLinks', JSON.stringify(this.removedLinks.map(link=>{
                        return {
                            id: link.id
                        }
                    })))
                    data.append('editedLinks', JSON.stringify(this.editedLinks))
                    
                    response = await this['dashboard/editLesson'](data)
                } else {

                    response = await this['dashboard/createLesson'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.title} was successfully ${action}`
                    if (this.edit) {
                        if (this.computedCheckMain) this.$emit('itemSuccessfullyEdited', response.lessonResource)
                    } else {
                        this.clearData()
                    }
                    bus.$emit('updateLesson',response.lesson)
                } else {
                    this.responseErrorAlert(response.response)
                    console.log('response :>> ', response);
                }
            },
            clickedAction(data){
                if (data === 'video') {
                    this.mediaCaptureType = 'video'
                    this.showMediaCapture = true
                } else if (data === 'camera') {
                    this.mediaCaptureType = 'image'
                    this.showMediaCapture = true
                } else if (data === 'microphone') {
                    this.mediaCaptureType = 'audio'
                    this.showMediaCapture = true
                } else if (data === 'upload') {
                    this.$refs.inputfile.value = ''
                    this.$refs.inputfile.click()
                }
            },
            clickedFileType(data){
                if (data === 'picture') {
                    this.fileAccept = 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp'
                } else if (data === 'video') {
                    this.fileAccept = 'video/webm,video/mp4,video/ogg'
                } else if (data === 'audio') {
                    this.fileAccept = 'audio/mpeg,audio/ogg,audio/wav'
                }
                this.fileType = data
            },
            fileChange(){
                if (this.fileType === 'picture') {
                    this.activeFile = this.imageFile = this.$refs.inputfile.files[0]
                } else if (this.fileType === 'video') {
                    this.activeFile = this.videoFile = this.$refs.inputfile.files[0]
                } else if (this.fileType === 'audio') {
                    this.activeFile = this.audioFile = this.$refs.inputfile.files[0]
                }
                this.showFilePreview = true
            },
            removeFile(){
                this.fileType = ''
                this.showFilePreview = false
                this.clickedBan(this.activeFile)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .modal-wrapper{
        z-index: 10005;
    }

    .welcome-form{
        position: relative;

        .loading{
            @include sticky-loader()
        }

        .section{
            @include form-section()
        }

        .info{
            text-align: center;
            font-size: 12px;
            color: gray;
        }

        .files{
            display: inline-flex;
            justify-content: space-around;
            width: 100%;
            font-size: 14px;
            margin: 20px 0 10px;

            .file{
                padding: 5px;
                border-radius: 10px;
                background: gray;
                color: mintcream;
                cursor: pointer;

                &:hover{
                    background: green;
                    transition: all .5s ease;
                }
            }

            .active{
                background: green;
                transition: all .5s ease;
            }
        }

        .actions{
            display: inline-flex;
            margin-bottom: 20px;

            .action{
                color: gray;
                cursor: pointer;
                padding: 5px;
                margin: 0 10px 0 0;
            }
        }

        .media-section{
            margin: 10px 0;

            .media-item{
                width: 100%;
                display: inline-flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: nowrap;
                color: gray;
                cursor: pointer;
                margin-bottom: 5px;

                // .item-type{

                // }

                .item-info{
                    width: 90%;
                    font-size: 14px;
                    margin: 0 10px;
                }

                .item-clear{

                    &:hover{
                        color: red;
                        transition: all .5s ease;
                    }
                }
            }
        }

        .attachment-heading{
            font-size: 12px;
            color: gray;
            text-align: center;
        }

        .attachments{
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            overflow-y: auto;
        }

        .attachments.danger{
            background: red;
            padding: 5px;
        }

        .links-input{
            width: 90%;
            margin: 10px auto;
        }

        .search-input{
            border: none;
            border-bottom: 2px solid $color-primary;
            background: white;
        }

        .class-payment{
            
            .message{
                font-size: 12px;
                color: gray;
                margin-bottom: 10px;
            }
        }

        .course-classes-section{
            min-height: 100px;
            align-items: center;

            .loading{
                text-align: center;
            }
        }

        .class-wrapper{
            display: flex;
            width: 90%;
            margin: 10px auto;
            align-items: center;
            overflow: auto;

            .class-badge{
                min-width: 150px;
            }
        }

        .no-data{
            font-size: 12px;
            color: gray;
        }

        .get-more{
            width: fit-content;
            margin: 10px auto;
            padding: 5px;
            background: cadetblue;
            color: white;
            font-size: 12px;
            border-radius: 10px;
            cursor: pointer;
        }

        .file-preview{
            height: 200px;
        }
    }

    h5{
        color: gray;
        text-align: center;
        margin-top: 20px;
    }
</style>