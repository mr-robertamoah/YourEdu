<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                :long="true"
                @mainModalDisappear='closeModal'
                class="create-asessment-modal-wrapper"
            >
                <template slot="main">
                    <welcome-form
                        :title="title"
                        class="welcome-form"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="clearAlert"
                            ></auto-alert>
                            <pulse-loader 
                                class="loading"
                                :loading="loading"></pulse-loader>
                            
                            <div class="section">Assessment Info</div>
                            <text-input
                                :bottomBorder="true"
                                placeholder="assessment name*"
                                v-model="data.name"
                                class="other-input"
                            ></text-input>
                            <text-textarea
                                :bottomBorder="true"
                                placeholder="description of the assessment"
                                v-model="data.description"
                                class="other-input"
                            ></text-textarea>
                            <main-checkbox
                                v-model="data.random"
                                label="has randomized questions"
                                title="check if you want all assessment sections to have randomized questions"
                                class="other-input"
                            ></main-checkbox>
                            <main-checkbox
                                v-model="data.hasDuration"
                                label="has duration"
                                title="check if assessment should be taken for a duration"
                                class="other-input"
                            ></main-checkbox>
                            <number-input
                                v-if="data.hasDuration"
                                :bottomBorder="true"
                                label="duration"
                                placeholder="duration"
                                prepend="in minutes"
                                v-model="data.duration"
                                :hasMax="false"
                                class="number-input"
                            ></number-input>
                            <number-input
                                :bottomBorder="true"
                                placeholder="score assessment over"
                                v-model="data.totalMark"
                                :hasMax="false"
                                :hasMin="false"
                                :inputMax="computedTotalMark"
                                class="number-input"
                            ></number-input>
                            <date-picker
                                :bottomBorder="true"
                                v-model="data.publishedAt"
                                class="other-input"
                                placeholder="date to show this assessment"
                                :flatPickrConfig="{
                                    dateFormat: 'F j, Y H:i',
                                    enableTime: true,
                                    minDate: computedMinimumPublishedAt
                                }"
                            ></date-picker>
                            <date-picker
                                v-if="data.publishedAt.length"
                                :bottomBorder="true"
                                v-model="data.dueAt"
                                class="other-input"
                                placeholder="due date for assessment"
                                :flatPickrConfig="{
                                    dateFormat: 'F j, Y H:i',
                                    enableTime: true,
                                    minDate: computedMinimumDueAt
                                }"
                            ></date-picker>
                            <div class="type" v-if="!main">
                                <radio-input
                                    v-model="data.type"
                                    radioValue="public"
                                    label="public"
                                ></radio-input>
                                <radio-input
                                    v-model="data.type"
                                    radioValue="private"
                                    label="private"
                                ></radio-input>
                            </div>
                            <div class="small-msg">
                                choose public if you want assessment to be taken or marked by only accounts invited or whose requests you have accepted."
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

                            <div class="section" v-if="computedHasDueAt && !edit">Discussion</div>
                            <main-checkbox
                                v-model="data.discussion"
                                v-if="computedHasDueAt && !edit && !data.discussionData.title.length"
                                label="automatically add a discussion?"
                                class="class-input"
                            ></main-checkbox>

                            <div class="section" v-if="!main">Assessment Attachments</div>
                            <div class="attachments-section" v-if="!main && data.attachments.length">
                                <attachment-badge
                                    v-for="(attachment,index) in data.attachments"
                                    :key="index"
                                    :hasClose="true"
                                    :attachment="attachment.data"
                                    :type="attachment.type"
                                    @removeAttachment="clickedRemoveAttachment"
                                ></attachment-badge>
                            </div>
                            <div class="msg" v-if="edit && data.removedAttachments.length">attachments to be removed</div>
                            <div class="attachments-section bg-red-400" v-if="!main && edit && data.removedAttachments.length">
                                <attachment-badge
                                    v-for="(attachment,index) in data.removedAttachments"
                                    :key="index"
                                    :hasClose="true"
                                    :attachment="attachment.data"
                                    :type="attachment.type"
                                    @removeAttachment="clickedUndoRemoveAttachment"
                                ></attachment-badge>
                            </div>
                            <post-attachment
                                :show="true"
                                :hasSelect="true"
                                :hasClose="false"
                                v-if="!main"
                                @clickedAttachmentSelection="attachmentSelected"
                                class="post-attachment"
                            ></post-attachment>
                            <div class="section" v-if="main">Assessment Attachments</div>
                            <div class="no-data" v-if="data.attachedItems.length">
                                this assessment is attached to these
                            </div>
                            <div
                                v-if="data.attachedItems.length"
                                class="class-wrapper"
                            >
                                <item-badge
                                    v-for="(item,index) in data.attachedItems"
                                    :key="index"
                                    :item="item"
                                    type="course"
                                    :hasRemove="true"
                                    class="class-badge"
                                    @clickedRemoveItem="removeItem"
                                ></item-badge>
                            </div>
                            <search-input
                                v-if="main"
                                class="search-input"
                                placeholder="search for courses/extracurriculums/classes/programs/lessons"
                                @search="getSearchItemsText"
                            ></search-input>
                            <div class="items-section" v-if="main">
                                <pulse-loader 
                                    :loading="specificItemsLoading"
                                    size="12px"
                                    class="loading"
                                ></pulse-loader>
                                <div
                                    v-if="computedSpecificItems.length"
                                    class="class-wrapper"
                                >
                                    <item-badge
                                        v-for="(item,index) in computedSpecificItems"
                                        :key="index"
                                        :item="item"
                                        type="course"
                                        :hasRemove="inItemSelection(item)"
                                        class="class-badge"
                                        @clickedItem="itemSelected"
                                        @clickedRemoveItem="removeItem"
                                    ></item-badge>
                                </div>
                                <div class="search-items">
                                    <grey-button
                                        v-for="(text, index) in specificItemsTexts"
                                        :key="index"
                                        :text="text"
                                        @clickedAction="clickedSpecificItemsText"
                                        :active="selectedSpecificItemsText[0] === text"
                                    ></grey-button>
                                </div>
                                <div class="no-data" 
                                    v-if="!specificItemsLoading && !computedSpecificItems.length">
                                    {{computedNoItem}}
                                </div>
                                <div class="get-more" 
                                    @click="getSpecificAccountItems"
                                    v-if="computedShowGetMore"
                                >
                                    get more
                                </div>
                            </div>

                            <div class="section add" ref="addsectionheading">
                                Assessment Sections
                                <font-awesome-icon :icon="['fa','plus']"
                                    title="add new section"
                                    class="plus"
                                    @click="goToSectionForm"
                                ></font-awesome-icon>
                            </div>

                            <assessment-section-form
                                class="sections"
                                @addSection="addSection"
                                @updateAssessmentSection="updateAssessmentSection"
                                @removeAssessmentSection="removeAssessmentSection"
                                :assessmentSections="data.assessmentSections"
                            ></assessment-section-form>
                            <div class="small-msg" v-if="data.removedAssessmentSections.length">
                                these assessment sections have been deleted
                            </div>
                            <div class="sections red" v-if="data.removedAssessmentSections.length">
                                <assessment-section-badge
                                    v-for="(section, sectionIndex) of data.removedAssessmentSections"
                                    :key="sectionIndex"
                                    :assessmentSection="section"
                                    :removed="true"
                                    @undoAssessmentSectionRemoval="undoAssessmentSectionRemoval"
                                ></assessment-section-badge>
                            </div>
                        </template>
                        <template slot="buttons">
                            <div class="profiles"
                                v-if="showProfiles"
                            >
                                <span>
                                    create as
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
                            <post-button
                                v-if="computedShowPostButton"
                                :buttonText="buttonText"
                                buttonStyle='success'
                                @click="clickedCreate"
                            ></post-button>
                        </template>
                    </welcome-form>

                    <arranging-modal
                        :data="arrangingModalData"
                        :type="arrangingModalType"
                        :show="arrangingModalShow"
                        @closeArrangingModal="closeArrangingModal"
                    ></arranging-modal>
                    
                    <create-discussion
                        :show="data.discussion"
                        v-if="data.discussion && computedHasDueAt"
                        :edit="edit"
                        :auto="true"
                        @clickedCreate="getDiscussionData"
                        @createDiscussionDisappear="closeDiscussionModal"
                    ></create-discussion>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import RadioInput from '../RadioInput';
import AssessmentSectionForm from './AssessmentSectionForm';
import TextTextarea from '../TextTextarea';
import TextInput from '../TextInput';
import PostButton from '../PostButton';
import GreyButton from '../GreyButton';
import MainCheckbox from '../MainCheckbox';
import MainSelect from '../MainSelect';
import NumberInput from '../NumberInput';
import ItemBadge from '../dashboard/ItemBadge';
import SearchInput from '../SearchInput';
import DatePicker from '../DatePicker';
import AssessmentSectionBadge from '../dashboard/AssessmentSectionBadge';
import ArrangingModal from '../dashboard/ArrangingModal';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import Alert from '../../mixins/Alert.mixin';
import { mapActions, mapGetters } from 'vuex';
import {bus} from '../../app';
import { strings } from '../../services/helpers';
import CreateDiscussion from './CreateDiscussion';
import ProfileBar from '../profile/ProfileBar';
import PostAttachment from '../PostAttachment';
import AttachmentBadge from '../AttachmentBadge';
import CreateItemAttachments from '../../mixins/CreateItemAttachments.mixin';
    export default {
        components: {
            PostAttachment,
            AttachmentBadge,
            CreateDiscussion,
            GreyButton,
            SearchInput,
            ItemBadge,
            NumberInput,
            DatePicker,
            ArrangingModal,
            AssessmentSectionBadge,
            MainSelect,
            MainCheckbox,
            PostButton,
            AssessmentSectionForm,
            RadioInput,
            TextInput,
            TextTextarea,
            PulseLoader,
            ProfileBar
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            main: {
                type: Boolean,
                default: false
            },
            edit: {
                type: Boolean,
                default: false
            },
            mainSection: {
                type: String,
                default: ''
            },
            editable: {
                type: Object,
                default(){
                    return null
                }
            },
            schoolAdmin: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        mixins: [CreateItemAttachments],
        data() {
            return {
                data: {
                    name: '',
                    description: '',
                    publishedAt: '',
                    state: '',
                    type: 'private',
                    dueAt: '',
                    duration: '',
                    hasDuration: false,
                    random: false,
                    discussion: false,
                    hasDiscussion: false,
                    discussionFiles: [],
                    totalMark: '',
                    mainAssessmentSections: [],
                    assessmentSections: [],
                    removedAssessmentSections: [],
                    editedAssessmentSections: [],
                    mainAttachedItems: [],
                    attachedItems: [],
                    unattachedItems: [],
                    paymentData: [],
                    removedPaymentData: [],
                    mainAttachments: [],
                    attachments: [],
                    removedAttachments: [],
                },
                showProfiles: false,
                title: 'create assessment',
                validationStatus: false,
                loading: false,
                buttonText: 'create',
                specificItemsNextPage: 1,
                specificItemsLoading: false,
                specificItems: [],
                specificItemsTexts: [
                    'lessons', 'classes', 'subjects', 'courses', 'course sections', 'extracurriculums'
                ],
                selectedSpecificItemsText: ['none'],
                searchItemsText: "",
                arrangingModalType: '',
                arrangingModalData: [],
                arrangingModalShow: false,
                validationItemLength: 15,
                formData: null,
            }
        },
        watch: {
            searchItemsText(newValue) {
                if (this.selectedSpecificItemsText.includes('none')) {
                    this.selectedSpecificItemsText = ['lessons']
                }
                this.debounceGetSpecificAccountItems()
            },
            selectedSpecificItemsText(newValue) {
                this.debounceGetSpecificAccountItems()
            },
        },
        mixins: [Alert],
        mounted () {
            bus
            .$on('assessmentError',(data)=>{
                this.issueDangerAlert(data)
            })
            .$on('editAssessment',(data)=>{
                this.setData(data)
            })
            .$on('arrangeQuestions', (questions)=>{
                this.arrangingModalSetUp(questions, 'questions')
            })
            .$on('arrangeAssessmentSections', (assessmentSections)=>{
                this.arrangingModalSetUp(assessmentSections, 'assessmentSections')
            })
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','getUser','getProfiles']),
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedTotalMark() {
                return Number.parseInt(this.data.totalMark) > 5 ?
                    Number.parseInt(this.data.totalMark) : 100
            },
            computedAccount() {
                return this['dashboard/getAccountDetails']
            },
            computedProfiles() {
                return this.getUser?.profiles
            },
            computedShowPostButton() {
                return this.computedHasData
            },
            computedHasData() {
                return this.data.assessmentSections.length &&
                    this.data.name.length
            },
            computedSpecificItems() {
                return this.specificItems
            },
            computedPayment(){
                if (this.computedAccount.account === 'school' ||
                    this.computedAccount.account === 'professional' ||
                    this.computedAccount.account === 'facilitator') {
                    return true
                } else {
                    return false
                }
            },
            computedShowGetMore() {
                return this.specificItemsNextPage && this.specificItemsNextPage > 1 &&
                    !this.specificItemsLoading
            },
            computedNoItem() {
                return this.loading ? '' : 
                    this.selectedSpecificItemsText.includes('none') ?
                    `search for courses, course sections, extracurriculums, classes, programs, lessons to which to add this assessment` :
                    `no ${this.selectedSpecificItemsText[0]} for this ${this.computedAccount.account} account`
            },
            computedHasDueAt() {
                return this.data.dueAt && this.data.dueAt.length
            },
            computedAnswerTypesPairInverse() {
                return {
                    'true_false': 'true/false',
                    'option': 'option',
                    'short_answer': 'short answer',
                    'long_answer': 'long answer',
                    'number': 'number',
                    'flow': 'flow',
                    'arrange': 'arrange',
                    'image': 'image',
                    'video': 'video',
                    'audio': 'audio',
                    'file': 'file',
                }
            },
            computedMinimumDueAt() {
                let publishedAt = new Date(this.data.publishedAt)
                return this.data.publishedAt ? 
                    new Date(publishedAt.getFullYear(), publishedAt.getMonth(), publishedAt.getDate() + 1) : 
                    'today'
            },
            computedMinimumPublishedAt() {
                let publishedAt = new Date(this.data.publishedAt)
                return this.data.publishedAt ? 
                    new Date(publishedAt.getFullYear(), publishedAt.getMonth(), publishedAt.getDate()) : 
                    'today'
            },
        },
        methods: {
            ...mapActions(['dashboard/getAccountSpecificItems',
                'dashboard/createAssessment', 'dashboard/editAssessment'
            ]),
            findAssessmentSection(data, section) {
                return data.findIndex(assessmentSection=>{
                    return assessmentSection.id === section.id &&
                        assessmentSection.name === section.name
                })
            },
            updateAssessmentSectionsPositions() {
                this.data.assessmentSections.forEach(
                    function(assessmentSection, assessmentSectionIndex){
                        assessmentSection.position = assessmentSectionIndex + 1
                    }
                )
            },
            undoAssessmentSectionRemoval(assessmentSection) {
                let index = this.findAssessmentSection(this.data.removedAssessmentSections, assessmentSection)

                if (index === -1) {
                    return
                }

                this.data.removedAssessmentSections.splice(index, 1)
                this.data.assessmentSections.push(assessmentSection)
                this.updateAssessmentSectionsPositions()
            },
            removeAssessmentSection(assessmentSection) {
                let index = this.findAssessmentSection(this.data.assessmentSections, assessmentSection)

                if (index === -1) {
                    return
                }

                this.data.assessmentSections.splice(index, 1)
                this.data.removedAssessmentSections.push(assessmentSection)
                this.updateAssessmentSectionsPositions()
            },
            getDiscussionData(data) {
                this.data.discussionData.title = data.title
                this.data.discussionData.preamble = data.preamble
                this.data.discussionData.type = data.type
                this.data.discussionData.restricted = true
                this.data.discussionData.allowed = 'all'
                this.data.discussionFiles = data.files
            },
            closeDiscussionModal() {
                this.data.discussion = false
            },
            stateSelection(data){
                this.data.state = data
            },
            checkDiscussion(data) {
                if ((data.discussions.constructor === Array && data.discussions.length > 0) ||
                    data.discussions > 0) {
                    this.data.hasDiscussion = true
                } else {
                    this.data.hasDiscussion = false
                } 
            },
            setData(data) {
                this.data.name = data.name
                this.data.assessmentId = data.id
                this.data.state = data.state?.toLowerCase()
                this.data.publishedAt = data.publishedAt
                this.data.type = data.type
                this.data.dueAt = data.dueAt
                if (data.duration) {
                    this.data.hasDuration = true
                    this.data.duration = `${data.duration}`
                }
                this.data.random = data.random
                if (data.totalMark) {
                    this.data.totalMark = `${data.totalMark}`
                }

                this.data.attachedItems = _.cloneDeep(data.items)
                this.data.mainAttachedItems = _.cloneDeep(data.items)

                this.data.attachments = _.cloneDeep(data.items)
                this.data.mainAttachments = _.cloneDeep(data.items)

                this.data.mainAssessmentSections = []
                this.data.mainAssessmentSections = _.cloneDeep(
                    data.assessmentSections.map(assessmentSection=>{
                        let questions = assessmentSection.questions.map(question=>{
                            let files = []
                            if (question.images) {
                                files.push(...question.images)
                            }
                            if (question.audios) {
                                files.push(...question.audios)
                            }
                            if (question.videos) {
                                files.push(...question.videos)
                            }
                            if (question.files) {
                                files.push(...question.files)
                            }
                            return {
                                id: question.id,
                                body: question.body,
                                hint: question.hint,
                                position: question.position ?
                                    `${question.position}` : "",
                                scoreOver: question.scoreOver ?
                                    `${question.scoreOver}` : "",
                                answerType: this.setAnswerTypeUsingPairIndex(question.answerType),
                                files: _.cloneDeep(files),
                                mainFiles: _.cloneDeep(files),
                                removedFiles: [],
                                correctPossibleAnswers: question.correctPossibleAnswers,
                                possibleAnswers: _.cloneDeep(question.possibleAnswers),
                                mainPossibleAnswers: _.cloneDeep(question.possibleAnswers),
                                editedPossibleAnswers: [],
                                removedPossibleAnswers: [],
                            }
                        })
                        return {
                            id: assessmentSection.id,
                            name: assessmentSection.name,
                            instruction: assessmentSection.instruction,
                            position: assessmentSection.position,
                            duration: assessmentSection.duration ?
                                `${assessmentSection.duration}` : "",
                            answerType: this.setAnswerTypeUsingPairIndex(assessmentSection.answerType),
                            autoMark: assessmentSection.autoMark,
                            random: assessmentSection.random,
                            maxQuestions: assessmentSection.maxQuestions ?
                                `${assessmentSection.maxQuestions}` : "",
                            questions: _.cloneDeep(questions),
                            mainQuestions: _.cloneDeep(questions),
                            editedQuestions:[],
                            removedQuestions:[],
                        }
                    })
                )
                this.data.assessmentSections = []
                this.data.assessmentSections = _.cloneDeep(this.data.mainAssessmentSections)

                this.data.mainPaymentData = []
                if (data.prices) this.data.mainPaymentData.push(...data.prices)

                this.buttonText = 'edit'
                this.data.addedby = {
                    name: data.addedby.name,
                    account: data.addedby.account,
                    accountId: data.addedby.accountId,
                }

                this.checkDiscussion(data)
            },
            setAnswerTypeUsingPairIndex(answerType) {
                if (!answerType) {
                    return ''
                }

                answerType = answerType.toLowerCase()
                if (!this.computedAnswerTypesPairInverse.hasOwnProperty(answerType)) {
                    return ''
                }

                return this.computedAnswerTypesPairInverse[answerType]
            },
            arrangingModalSetUp(data, type) {
                this.arrangingModalData = data
                this.arrangingModalType = type
                this.arrangingModalShow = true
            },
            closeArrangingModal() {
                this.arrangingModalShow = false
                this.arrangingModalData = []
                this.arrangingModalType = ''
            },
            debounceGetSpecificAccountItems: _.debounce(
                function() {
                    this.initiateGetItems()
                    this.getSpecificAccountItems()
                }, 200
            ),
            initiateGetItems() {
                this.specificItems = []
                this.specificItemsNextPage = 1
            },
            getSearchItemsText(search) {
                this.searchItemsText = search
            },
            inItemSelection(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            itemSelected(data) {
                let index = this.findItemIndex(data)
                if (index === -1) {
                    this.data.attachedItems.push(data)
                }
            },
            findItemIndex(data) {
                return this.data.attachedItems.findIndex(cl=>{
                    return cl.id === data.id && cl.type === data.type
                })
            },
            removeItem(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.data.attachedItems.splice(index,1)
                }
            },
            removedItemsUpdate(data) {
                let index = this.data.unattachedItems.findIndex(cl=>{
                    return data.type === cl.type && data.id === cl.id
                })
                if (index === -1) {
                    this.data.unattachedItems.push(data)
                }
            },
            clickedSpecificItemsText(data) {
                if (data === '') {
                    data = 'none'
                }
                this.selectedSpecificItemsText = [data]
            },
            async getSpecificAccountItems(){
                if (this.specificItemsNextPage === null) {
                    return
                }
                let response,
                    data = {
                        account: this.computedAccount.account,
                        accountId: this.computedAccount.accountId,
                        items: this.selectedSpecificItemsText,
                        search: this.searchItemsText,
                        for: "assessment"
                    }

                this.specificItemsLoading = true
                response = await this['dashboard/getAccountSpecificItems']({
                    data, nextPage: this.specificItemsNextPage
                })
                this.specificItemsLoading = false

                if (response.status) {
                    if (!this.specificItemsNextPage) {
                        this.specificItems = response.items
                    } else {
                        this.specificItems.push(...response.items)
                    }
                    if (response.next) {
                        this.specificItemsNextPage += 1
                    } else {
                        this.specificItemsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            closeModal() {
                this.clearData()
                this.$emit('closeCreateAssessment')
            },
            clearData() {
                this.clearAssessmentData()
                this.clearAlert()
            },
            clearAssessmentData() {                
                this.data.name = ''
                this.data.description = ''
                this.data.publishedAt = ''
                this.data.dueAt = ''
                this.data.duration = ''
                this.data.hasDuration = false
                this.data.random = false
                this.data.totalMark = ''
                this.data.mainAttachedItems = []
                this.data.attachedItems = []
                this.data.unattachedItems = []
                this.data.mainAttachments = []
                this.data.attachments = []
                this.data.removedAttachments = []
                this.data.assessmentSections = []
                this.data.removedAssessmentSections = []
                this.data.mainAssessmentSections = []
                this.data.editedAssessmentSections = []
            },
            clearAssessmentSectionData() {
                bus.$emit('clearAssessmentSectionData')
            },
            goToSectionForm() {
                bus.$emit('toggleSectionForm')
                this.clearAssessmentSectionData()
            },
            updateAssessmentSection(section) {
                let index = this.data.assessmentSections.findIndex(assessmentSection=>{
                    return assessmentSection.id === section.id
                })

                if (index === -1) {
                    return
                }

                this.data.assessmentSections.splice(index,1,section)
            },
            addSection(section) {
                this.data.assessmentSections.push(
                    this.mapAssessmentSectionData(_.cloneDeep(section))
                )
                this.updateAssessmentSectionsPositions()
                this.clearAssessmentSectionData()
                this.$refs.addsectionheading.scrollIntoView()
            },
            mapAssessmentSectionData(sectionData) {
                return {
                    id: sectionData.id.length ? sectionData.id : 
                        `${Math.floor(Math.random() * -1000)}`,
                    name: sectionData.name,
                    instruction: sectionData.instruction,
                    position: sectionData.position,
                    autoMark: sectionData.autoMark,
                    hasMaxQuestions: sectionData.hasMaxQuestions,
                    random: sectionData.random,
                    maxQuestions: sectionData.maxQuestions,
                    questions: sectionData.questions,
                    removedQuestions: sectionData.removedQuestions,
                    editedQuestions: sectionData.editedQuestions,
                    mainQuestions: sectionData.mainQuestions ?
                        sectionData.mainQuestions : [],
                    answerType: sectionData.answerType,
                }
            },
            validateAssessmentData(data) {
                this.validationStatus = this.validateAssessment(data)
                if (!this.validationStatus) {
                    return this.validationStatus
                }
                
                data.assessmentSections.forEach(assessmentSection=>{
                    this.validationStatus = this.validateAssessmentSection(assessmentSection)

                    if (!this.validationStatus) {
                        return this.validationStatus
                    }

                    assessmentSection.questions.forEach(question=>{
                        this.validationStatus = this.validateAssessmentSectionQuestion(question, assessmentSection)
    
                        if (!this.validationStatus) {
                            return this.validationStatus
                        }

                        question.possibleAnswers.forEach(possibleAnswer=>{

                            this.validationStatus = this.validateAssessmentSectionQuestionPossibleAnswer(possibleAnswer, question)

                            if (!this.validationStatus) {
                                return this.validationStatus
                            }
                        })
                    })
                })
            },
            validateAssessment(data) {
                if (!data.name.length) {
                    this.issueDangerAlert({message: 'name of assessment is required.'})
                    return false
                }

                return true
            },
            validateAssessmentSection(data) {
                if (!data.name.length) {
                    this.issueDangerAlert({message: `name of assessment section is required. please check your assessment sections.`})
                    return false
                }

                if (!data.questions.length) {
                    this.issueDangerAlert({
                        message: `assessment with name: ${strings.trim(data.name, this.validationItemLength)} requires at least a question.`,
                        lengthy: true
                    })
                    return false
                }

                return true
            },
            validateAssessmentSectionQuestion(data, assessmentSection) {
                if (!data.body.length && !data.files.length) {
                    this.issueDangerAlert({message: `${strings.trim(assessmentSection.name, this.validationItemLength)} assessment section requires it's questions to have a body or file.`})
                    return false
                }

                if (this.isTrueOrFalseOptionAnswerType(data.answerType)) {
                    if (data.possibleAnswers.length < 2) {
                        this.issueDangerAlert({
                            message: `${strings.trim(data.body, this.validationItemLength)} question requires at least two possible answers.`,
                            lengthy: true
                        })
                        return false
                    }
                } else if (this.isArrangeFlowAnswerType(data.answerType)) {
                    if (data.possibleAnswers.length < 2) {
                        this.issueDangerAlert({
                            message: `${strings.trim(data.body, this.validationItemLength)} question requires at least two options to be re-ordered.`,
                            lengthy: true
                        })
                        return false
                    }
                }

                return true
            },
            validateAssessmentSectionQuestionPossibleAnswer(data, question) {
                if (!data.option.length) {
                    this.issueDangerAlert({
                        message: `possible answers of ${strings.trim(question.body, this.validationItemLength)} question requires an option.`,
                        lengthy: true
                    })
                    return false
                }
                return true
            },
            isTrueOrFalseOptionAnswerType(answerType) {
                return answerType === 'true_false' || 
                    answerType === 'option'
            },
            isArrangeFlowAnswerType(answerType) {
                return answerType === 'arrange' || 
                    answerType === 'flow'
            },
            mapFinalAssessmentSection(assessmentSection, edit = true) {
                console.log('before',assessmentSection);
                assessmentSection.assessmentSectionId = edit ? assessmentSection.id : null
                assessmentSection.removedQuestions = edit ? assessmentSection.removedQuestions.map(question=>{
                    return {
                        questionId: question.id
                    }
                }) : []
                assessmentSection.mainQuestions.forEach(mainQuestion=>{
                    assessmentSection.questions.forEach((question, questionIndex)=>{
                        
                        if (mainQuestion.id == question.id) {
                            
                            if (!_.isEqual(mainQuestion, question)) {
                                assessmentSection.editedQuestions.push(
                                    this.mapFinalAssessmentSectionQuestion(question)
                                )
                            }
                            assessmentSection.questions.splice(questionIndex, 1)
                        }
                    })
                })

                if (assessmentSection.mainQuestions) {
                    delete assessmentSection.mainQuestions
                }
                
                assessmentSection.questions.map(question=>{
                    return this.mapFinalAssessmentSectionQuestion(question, false)
                })

                console.log('after', assessmentSection);
                return assessmentSection
            },
            mapFinalAssessmentSectionQuestion(question, edit = true) {
                question.removedPossibleAnswers = edit ? question.removedPossibleAnswers.map(possibleAnswer=>{
                    return {
                        possibleAnswerId: possibleAnswer.id
                    }
                }) : []
                question.questionId = edit ? question.id : null
                question.mainPossibleAnswers.forEach(mainPossibleAnswer=>{
                    question.possibleAnswers.forEach((possibleAnswer, possibleAnswerIndex)=>{
                        
                        if (mainPossibleAnswer.id == possibleAnswer.id) {
                            
                            if (!_.isEqual(mainPossibleAnswer, possibleAnswer)) {
                                question.editedPossibleAnswers.push(
                                    this.mapFinalAssessmentSectionQuestionPossibleAnswer(possibleAnswer)
                                )
                            }
                            question.possibleAnswers.splice(possibleAnswerIndex, 1)
                        }
                    })
                })

                question.possibleAnswers.map(possibleAnswer=>{
                    return this.mapFinalAssessmentSectionQuestionPossibleAnswer(possibleAnswer, false)
                })

                question.mainFiles.forEach(mainFile=>{
                    question.files.forEach((file, fileIndex)=>{
                        
                        if (_.isEqual(mainFile, file)) {
                            question.files.splice(fileIndex, 1)
                        }
                    })
                })
                
                question.removedFiles = edit ? question.removedFiles.map(file=>{
                    return {
                        id: file.id,
                        type: file.type
                    }
                }) : []

                
                if (question.mainPossibleAnswers) {
                    delete question.mainPossibleAnswers
                }

                if (question.mainFiles) {
                    delete question.mainFiles
                }

                question.files.forEach(file=>{
                    this.formData.append(`question${question.id}[]`, file)
                })
                    
                return question
            },
            mapFinalAssessmentSectionQuestionPossibleAnswer(possibleAnswer, edit = true) {
                possibleAnswer.possibleAnswerId = edit ? possibleAnswer.id : null
                
                return possibleAnswer
            },
            clickedProfile(data) {
                
                this.formData.append('account', data.account)
                this.formData.append('accountId', data.accountId)

                this.$emit('clickedCreate', this.formData)
                this.closeModal()
            },
            async createOrEditAssessment() {
                this.loading = true
                let response

                this.setAssessmentFormData()

                if (this.edit) {
                    response = await this['dashboard/editAssessment'](this.formData)
                } else {
                    response = await this['dashboard/createAssessment'](this.formData)
                }

                this.loading = false
                
                if (! response.status) {
                    this.issueDangerAlertForResponse(response.response)
                    console.log('response :>> ', response);
                    return
                }

                let action = this.edit ? 'edited' : 'created'
                this.alertSuccess = true
                this.alertMessage = `${this.data.name} was successfully ${action} 😊`
                if (!this.edit) {
                    this.clearData()
                }
            },
            setAssessmentFormData() {
                this.formData = new FormData

                if (this.main) {
                    this.formData.append('account', this.computedAccount.account)
                    this.formData.append('accountId', this.computedAccount.accountId)
                }

                this.formData.append('name', this.data.name)
                this.formData.append('description', this.data.description)
                if (this.schoolAdmin) {
                    this.formData.append('adminId', this.schoolAdmin.id)
                }
                this.formData.append('paymentData', JSON.stringify(this.data.paymentData)) 
                this.formData.append('main', JSON.stringify(this.main)) 
                this.formData.append('social', JSON.stringify(this.main ? false : true)) 
                this.formData.append('dueAt', this.data.dueAt)
                this.formData.append('publishedAt', this.data.publishedAt)
                this.formData.append('totalMark', JSON.stringify(this.data.totalMark)) 
                this.formData.append('duration', JSON.stringify(this.data.duration))
                this.formData.append('attachments', JSON.stringify(this.getOnlyNewAttachments()))
                this.formData.append('type', this.data.type)
                let assessmentSections = _.cloneDeep(this.data.assessmentSections)

                if (this.edit) {
                    this.formData.append('assessmentId', this.data.assessmentId)
                    this.formData.append('removedAttachments', JSON.stringify(this.data.removedAttachments))
                    this.data.editedAssessmentSections = []
                    this.data.mainAssessmentSections.forEach(mainSection=>{
                        assessmentSections.forEach((section, sectionIndex)=>{
                            
                            if (mainSection.id == section.id) {
                                if (!_.isEqual(mainSection, section)) {
                                    this.data.editedAssessmentSections.push(
                                        this.mapFinalAssessmentSection(section)
                                    )
                                }
                                assessmentSections.splice(sectionIndex, 1)
                            }
                        })
                    })
                    this.formData.append('editedAssessmentSections', JSON.stringify(this.data.editedAssessmentSections))
                    this.formData.append('assessmentSections', JSON.stringify(assessmentSections.map(assessmentSection=>{
                        return this.mapFinalAssessmentSection(assessmentSection, false)
                    })))
                    this.formData.append('removedAssessmentSections', JSON.stringify(
                        this.data.removedAssessmentSections.map(assessmentSection=>{
                            return {
                                assessmentSectionId: assessmentSection.id
                            }
                        }
                    )))
                    let attachedItems = _.cloneDeep(this.data.attachedItems)
                    this.data.mainAttachedItems.forEach(mainItem=>{
                        let index = this.data.attachedItems.findIndex(item=>{
                            return item.type === mainItem.type && item.id === mainItem.id
                        })
                        if (index === -1) {
                            this.removedItemsUpdate(mainItem)
                        }
                        if (index > -1) {
                            attachedItems.splice(index, 1)
                        }
                    })
                    
                    this.formData.append('attachedItems', JSON.stringify(attachedItems.map(item=>{
                        return {
                            itemId: item.id,
                            item: item.type
                        }
                    })))
                    this.formData.append('unattachedItems', JSON.stringify(this.data.unattachedItems.map(attachment=>{
                        return {
                            type: attachment.type,
                            id: attachment.id
                        }
                    })))
                    this.formData.append('removedPaymentData', JSON.stringify(
                        this.data.removedPaymentData.map(payment=>{
                            return {
                                type: payment.type,
                                id: payment.id
                            }
                        }
                    )))

                    return
                }
                    
                this.formData.append('assessmentSections', JSON.stringify(
                    assessmentSections.map(assessmentSection=>{
                        return this.mapFinalAssessmentSection(assessmentSection, false)
                    })
                ))
                this.formData.append('attachedItems', JSON.stringify(this.data.attachedItems.map(item=>{
                    return {
                        itemId: item.id,
                        item: item.type
                    }
                })))
            },
            clickedCreate() {
                this.validateAssessmentData(this.data)

                if (! this.validationStatus) {
                    this.formData = null
                    return
                }

                
                if (this.main) {
                    this.createOrEditAssessment()
                    return
                }
                
                this.setAssessmentFormData()
                this.showProfiles = true
            },
        },
    }
</script>

<style lang="scss" scoped>

    .create-asessment-modal-wrapper{
        z-index: 10005;

        .welcome-form{            
            position: relative;

            .profiles{
                position: absolute;
                width: 200px;
                left: 0;
                bottom: 30px;
                text-align: justify;
                font-size: 14px;
                color: black;
                z-index: 5;
            }

            .loading{
                @include sticky-loader();
                top: 49%;
            }

            .section{
                @include form-section()
            }

            .sections{
                display: flex;
                width: 90%;
                padding: 10px;
                overflow-x: scroll;
                margin: 10px auto;
                align-items: center;
                justify-content: space-between;

                &.red{
                    background: $red-04;
                }
            }

            .search-input{
                border: none;
                border-bottom: 2px solid $color-primary;
                background: white;
            }

            .add{
                display: flex;
                width: 100%;
                justify-content: space-between;
                padding-right: 10px;

                .plus{
                    cursor: pointer;
                    color: gray;
                }
            }

            .class-input, .number-input{
                width: 90%;
                margin: 10px auto;
                border: none;
                border-bottom: 2px solid $color-primary;
                border-radius: 0;
            }

            .number-input{
                border: none;
            }

            .other-input, .type{
                width: 90%;
                margin: 10px auto;
            }

            .type{
                display: flex;
            }

            .add-section{
                width: 90%;
                margin: 10px auto;
            }

            .class-structure{

                .main{
                    display: flex;
                    flex-wrap: wrap;
                    align-items: center;
                    width: 100%;
                }

                .message{
                    font-size: 12px;
                    color: gray;
                    width: 100%;
                    padding: 0 5px;
                }
            }

            .other-input,
            .attachments{
                width: 90%;
                margin: 10px auto;
            }

            .attachment-heading{
                font-size: 12px;
                color: gray;
                text-align: center;
            }

            .search-input{
                border: none;
                border-bottom: 2px solid $color-primary;
                background: white;
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

            .class-payment{
                
                .message{
                    font-size: 12px;
                    color: gray;
                    margin-bottom: 10px;
                }
            }

            .items-section{
                min-height: 100px;
                align-items: center;

                .loading{
                    text-align: center;
                }

                .search-items{
                    display: flex;
                    margin: 10px;
                    width: 100%;
                    overflow-x: auto;

                    .grey-button-wrapper{
                        margin: 0 10px;
                        min-width: fit-content;
                        padding: 5px 10px;
                    }
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
                @include small-msg()
            }

        }
    }
</style>