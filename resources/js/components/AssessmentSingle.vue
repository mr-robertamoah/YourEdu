<template>
    <div
        v-if="assessment"
        class="assessment-single-wrapper min-w-1/2 min-h-90vh relative"
    >
        <div
            class="text-base p-2 cursor-pointer text-gray-500 absolute z-30 top-1 right-1"
            v-if="steps"
            @click="goBack"
        >
            <font-awesome-icon
                :icon="['fa', 'long-arrow-alt-left']"
            ></font-awesome-icon>
        </div>
        <template>
            <auto-alert
                class="absolute w-full text-center top-1/2"
                :message="alertMessage"
                :success="alertSuccess"
                :danger="alertDanger"
                :sticky="true"
                @hideAlert="clearAlert"
            ></auto-alert>
            <pulse-loader 
                class="absolute w-full text-center top-1/2 z-30"
                :loading="loading" 
                :size="'12px'"
            ></pulse-loader>
        </template>
        <item-view-cover
            class="min-h-90vh"
            v-if="steps === 0"
            :data="computedCoverData"
            additionalText="still open"
        >
            <template slot="buttons">
                <special-button 
                    buttonText="take it"
                    class="p-1 ml-5"
                    @click="clickedButton('take it')"
                    v-if="computedParticipant"
                ></special-button>
                <special-button 
                    buttonText="mark"
                    class="p-1 ml-5"
                    @click="clickedButton('mark')"
                    v-if="computedMarker"
                ></special-button>
                <special-button 
                    buttonText="want to take"
                    class="p-1 ml-5"
                    @click="clickedButton('want to take')"
                    v-if="computedCanJoin"
                ></special-button>
                <special-button 
                    buttonText="join markers"
                    class="p-1 ml-5"
                    @click="clickedButton('join markers')"
                    v-if="computedMarkables.length"
                ></special-button>
                <special-button 
                    buttonText="invite participant"
                    class="p-1 ml-5"
                    @click="clickedButton('invite participant')"
                    v-if="computedIsOwner"
                ></special-button>
                <special-button 
                    buttonText="invite marker"
                    class="p-1 ml-5"
                    @click="clickedButton('invite marker')"
                    v-if="computedIsOwner"
                ></special-button>
                <special-button 
                    buttonText="view work"
                    class="p-1 ml-5"
                    @click="clickedButton('view work')"
                    v-if="computedHasAnswered"
                ></special-button>
            </template>
        </item-view-cover>
        <div 
            :class="[showProfiles ? '' : 'border-2 rounded-lg flex flex-col h-full']"
            v-if="steps || showProfiles"
        >
            <div  
                class="p-3 flex flex-col h-90vh overflow-x-hidden overflow-y-auto justify-around"
                v-if="steps === 1"
            >
                <div  class="flex items-center text-gray-500 text-sm w-full mb-5">
                    <div class="">
                        created by
                    </div>
                    <profile-picture 
                        class="flex-shrink-0 my-1 mx-1"
                        classes="h-7 w-7"
                    >
                        <template slot="image">
                            <img :src="assessment.addedby.url" >
                        </template>
                    </profile-picture>
                    <div class="font-semibold text-center">
                        {{assessment.addedby.name}}
                    </div>
                </div>
                <div class="w-full bg-gray-50 p-2 mb-10">
                    <div class="mx-1 text-lg font-bold">
                        {{assessment.name}}
                    </div>
                    <div class="mx-3 text-sm text-gray-500">
                        {{assessment.description}}
                    </div>
                </div>
                <div class="w-full bg-gray-50 text-gray-500 p-2 text-sm mb-3">
                    <div v-if="assessment.duration">
                        {{`duration of ${assessment.duration} mins`}}
                    </div>
                    <div>{{`total mark of ${assessment.totalMark}`}}</div>
                    <div v-if="assessment.dueAt">
                        {{`due ${assessment.dueAt}`}}
                    </div>
                    <div>
                        {{`${assessment.assessmentSections.length} assessment sections`}}
                    </div>
                    <div>
                        {{`${computedNumberOfQuestions} total questions`}}
                    </div>
                    <div v-if="assessment.worksCount">
                        {{`${assessment.worksCount} persons have taken the assessment`}}
                    </div>
                </div>
                <div class="relative px-1 py-5 h-content max-h-1/3 flex w-full flex-nowrap mb-2 overflow-x-auto bg-gray-50 p-2">
                    <span class="absolute text-gray-500 text-sm top-0">assessment sections</span>
                    <assessment-section-mini-badge
                        class="min-w-full mx-1 flex-grow-0"
                        v-for="(assessmentSection, index) in assessment.assessmentSections"
                        :key="index"
                        :assessmentSection="assessmentSection"
                    ></assessment-section-mini-badge>
                </div>
                <div class="flex justify-end">
                    <special-button 
                        buttonText="continue"
                        class="p-1 ml-5"
                        @click="clickedButton('continue')"
                        v-if="assessment.timer || computedHasAnswered"
                    ></special-button>
                    <special-button
                        v-else
                        buttonText="start"
                        class="p-1 ml-5"
                        @click="clickedButton('start')"
                    ></special-button>
                </div>
            </div>
            <div v-if="steps === 2" 
                class="p-2 h-90vh w-full flex flex-col"
                :class="{'justify-center items-center': !computedTimingShow}"
            >
                <template v-if="computedTimingShow">
                    <div 
                        class="flex text-gray-500 text-sm p-0.5 w-content mt-5" 
                        v-if="timingItemTimeLeft"
                        :class="[timingItemHasFewTimeLeft ? 'bg-red-700 text-gray-200' : 'text-gray-500']"
                    >
                        <div class="mr-1">Time remaining:</div>
                        <div>{{timingItemTimeLeft}}</div>
                    </div>
                    <assessment-section-answering-form
                        class="h-full mt-4"
                        :assessment="assessment"
                        :work="work"
                        :answers="answers"
                        :numberOfQuestions="computedNumberOfQuestions"
                        @initiated="initiatedAssessmentSection"
                        :computedAccount="computedAccount"
                        @goToStep="handleGoToStep"
                        @answered="answered"
                    ></assessment-section-answering-form>
                    <special-button 
                        buttonText="submit answers"
                        class="p-1 left-0 top-0 ml-0.5 mt-0.5 absolute"
                        @click="clickedButton('submit answers')"
                        v-if="answers.length"
                    ></special-button>
                </template>
                <div v-if="timingItemLocked"
                    class="p-2 rounded-sm text-gray-500 font-semibold text-sm"
                >
                    sorry 😕, you have either finished this assessment, its due, or there is no more time for answering
                </div>
                <div v-if="timingItemWait"
                    class="p-2 rounded-sm text-gray-500 font-semibold text-sm bg-yellow-400"
                >
                    ✋ wait for a while...
                </div>
            </div>
            <div class="w-full h-full min-h-90vh" v-if="steps === 3">
                <assessment-details
                    :assessment="assessment"
                    class="mt-4"
                    @clickedButton="clickedButton"
                ></assessment-details>
            </div>
            <div class="w-full h-full flex flex-col justify-center items-center min-h-90vh" v-if="steps === 4">
                <special-button 
                    buttonText="submit marks"
                    class="p-1 left-0 top-0 ml-0.5 mt-0.5 absolute"
                    @click="clickedButton('submit marks')"
                    v-if="computedShowSubmitMarks"
                ></special-button>
                <assessment-section-marking-form
                    class="mt-5"
                    v-if="work"
                    :work="work"
                    :assessment="assessment"
                    :marks="marks"
                    @marked="marked"
                    @goToStep="handleGoToStep"
                ></assessment-section-marking-form>
                <div v-else class="">getting the work...</div>
            </div>
            <reaction-component
                v-if="steps < 2"
                class="flex-grow-0 flex-shrink-0 px-2"
                :comments="computedComments"
                :item="computedItem"
                :isOwner="computedIsOwner"
                :full="assessmentFull"
                :showAddComment="showAddComment"
                :showFlagReason="showFlagReason"
                :flagData="flagData"
                :likeData="likeData"
                :showProfilesText="showProfilesText"
                :classes="showOnlyProfiles ? 'absolute bottom-8' : ''"
                :showOnlyProfiles="showOnlyProfiles"
                :showProfiles="showProfiles"
                :profiles="computedProfiles"
                @hideAddComment="showAddComment = false"
                @postAddComplete="postAddComplete"
                @askLoginRegister="askLoginRegister"
                @clickedMedia="clickedMedia"
                @clickedProfile="clickedProfile"
                @clickedLike="clickedLike"
                @clickedAddComment="clickedAddComment"
                @cancelFlagProcess="cancelFlagProcess"
                @reasonGiven="reasonGiven"
                @clickedFlag="clickedFlag"
                @continueFlagProcess="continueFlagProcess"
                @clickedShowPostComments="clickedShowPostComments"
            ></reaction-component>
        </div>

       <!--  request section -->
        <item-request-section
            :show="showRequest"
            :computedItem="computedItem"
            :hasAllowed="false"
            :loading="loading"
            :for="joinOrInvitationType"
            :removedParticipant="removedParticipant"
            @doneRemovingParticipant="doneRemovingParticipant"
            @clickedCloseRequest="closeItemRequestSection"
            @clickedParticpantAction="clickedParticpantAction"
        ></item-request-section>

        <!-- small modal for alerts -->
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    :title="smallModalMessage"
                    :show="showSmallModal"
                    :message="alertMessage"
                    :success="alertSuccess"
                    :danger="alertDanger"
                    :loading="loading"
                    :alerting="smallModalAlerting"
                    @disappear="clearSmallModal"
                >
                    <template slot="actions">
                        <post-button
                            buttonText="ok"
                            @click="clickedSmallModalButton"
                            v-if="smallModalInfo"
                        ></post-button>
                        <post-button
                            buttonText="yes"
                            @click="clickedSmallModalButton"
                            buttonStyle='danger'
                            v-if="smallModalDelete"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedSmallModalButton"
                            buttonStyle='success'
                            v-if="smallModalDelete"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>

        <flag-cover 
            v-if="flagData.myFlag"
            :item="computedItem.item"
        ></flag-cover>

        <pop-up
            :show="showPopUp"
            :responses="['continue', 'cancel']"
            default="continue"
            message="this assessment has a duration. once you click continue, a timer will start and you will have to finish before the timer completes"
            @clickedResponse="clickedPopupResponse"
            @closePopUp="closePopUp"
        ></pop-up>
    </div>
</template>

<script>
import { default as _ } from 'lodash';
import SpecialButton from './SpecialButton.vue'
import ItemViewCover from './ItemViewCover.vue'
import ProfilePicture from './profile/ProfilePicture.vue';
import AssessmentSectionMiniBadge from './dashboard/AssessmentSectionMiniBadge.vue'
import QuestionAnsweringBadge from './dashboard/QuestionAnsweringBadge.vue'
import ItemRequestSection from './ItemRequestSection.vue';
import FadeUp from './transitions/FadeUp.vue'
import Alert from '../mixins/Alert.mixin';
import Like from '../mixins/Like.mixin';
import Flag from '../mixins/Flag.mixin';
import Save from '../mixins/Save.mixin';
import Profiles from '../mixins/Profiles.mixin';
import Timing from '../mixins/Timing.mixin';
import PopUp from '../mixins/PopUp.mixin';
import Participation from '../mixins/Participation.mixin';
import SmallModal from '../mixins/SmallModal.mixin'
import Storage from '../mixins/Storage.mixin'
import Comments from '../mixins/Comments.mixin'
import { mapGetters, mapActions } from 'vuex'
import AssessmentSectionAnsweringForm from './forms/AssessmentSectionAnsweringForm.vue'
import AssessmentSectionMarkingForm from './forms/AssessmentSectionMarkingForm.vue'
import AssessmentDetails from './AssessmentDetails.vue'
import PostButton from './PostButton.vue';
    export default {
        components: {
            QuestionAnsweringBadge,
            AssessmentSectionMiniBadge,
            ItemViewCover,
            SpecialButton,
            FadeUp,
            ProfilePicture,
            AssessmentSectionAnsweringForm,
            AssessmentSectionMarkingForm,
            ItemRequestSection,
            AssessmentDetails,
            PostButton,
        },
        props: {
            assessment: {
                type: Object,
                default() {
                    return null
                }
            },
            schoolAdmin: {
                type: Object,
                default(){
                    return null
                },
            },
        },
        mixins: [
            Alert, Like, Flag, Save, Storage, Comments, Participation,
            Profiles, Timing, PopUp, SmallModal
        ],
        data() {
            return {
                steps: 0,
                assessmentFull: false,
                showRequest: false,
                loading: false,
                work: null,
                answers: [],
                marks: [],
                viewHistory: [0],
                answeringOrMarkingType: 'all'
            }
        },
        watch: {
            steps(newValue){
                if (newValue === 0) {
                    this.clearViewHistory()
                }
            },
            "assessment.likes": {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        
                        this.likeData.likes = newValue.length
                    }
                }
            },
            "assessment.saves": {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        
                        this.saveData.saves = newValue.length
                    }
                }
            },
        },
        mounted () {
            this.setStorage()
            this.setMyFlag()
            this.setMyLike()
            this.setMySave()
            this.listen()
            this.listenForComments()
            this.listenForLikes()
            this.listenForFlags()
            this.listenForSaves()
            this.listenForParticipation()
        },
        beforeDestroy () {
            this.unsetStorage()
        },
        computed: {
            ...mapGetters([
                'getUser'
            ]),
            computedCoverData() {
                return {
                    name: this.assessment.name,
                    description: this.assessment.description ? this.assessment.description : '',
                    type: 'assessment'
                }
            },
            computedNumberOfQuestions() {
                return this.assessment.assessmentSections.reduce(
                    function(sum, section) {
                        return sum + section.questions.length
                    }, 0
                )
            },
            computedNumberOfQuestionsToAnswer() {
                return this.computedNumberOfQuestions - (this.computedWorkAnswers.length)
            },
            computedItem() {
                return {
                    itemId: this.assessment.id,
                    item: 'assessment'
                }
            },
            computedItemable() {
                return this.assessment ? this.assessment : {}
            },
            computedIsOwner() {
                return this.assessment.addedby.userId === this.getUser?.id
            },
            computedAccount() {
                return this.computedIsOwner ? this.assessment.addedby : 
                    this.computedParticipant ? this.computedParticipant : null
            },
            computedRestricted() {
                return !this.computedParticipant ? false : 
                    this.computedParticipant.state === 'RESTRICTED' || 
                    this.computedParticipant.state === 'BANNED'
            },
            computedBanned() {
                return !this.computedParticipant ? false : 
                    this.computedParticipant.state === 'BANNED'
            },
            computedParticipantsInfo(){
                return this.computedParticipantsNumber === 1 ? 
                    `${this.computedParticipantsNumber} participant` :
                    `${this.computedParticipantsNumber} participants`
            },
            computedParticipantsNumber(){
                return this.assessment.participants.length + 1
            },
            computedHasAnswered() {
                return this.assessment.answeredbyUserIds.findIndex(userId=>{
                    return userId == this.getUser.id
                }) > -1
            },
            computedCanParticipate() {
                if (this.computedParticipant || this.computedHasAnswered) {
                    return false
                }

                if (this.computedIsOwner) {
                    return false
                }

                if (this.getProfiles) {
                    return true
                }

                return false
            },
            computedMarkables() {
                if (! this.getProfiles) {
                    return []
                }
                
                if (this.computedMarker) {
                    return []
                }
                
                let profiles = this.getProfiles.filter(profile=>{
                    return ['facilitator', 'professional'].includes(profile.account)
                })
                
                if (profiles.length) {
                    return profiles
                }

                if (this.computedIsOwner) {
                    return [this.assessment.addedby]
                }

                return []
            },
            computedMarker() {
                if (! this.getUser) {
                    return null
                }
                
                let index = this.assessment.markers?.findIndex(participant=>{
                    return participant.userId === this.getUser.id
                })
                if (index > -1) {
                    return this.assessment.markers[index]
                }

                return null
            },
            computedParticipant(){
                if (this.computedIsOwner) {
                    return this.assessment.addedby
                }

                if (! this.getUser) {
                    return null
                }

                let index = this.assessment.participants?.findIndex(participant=>{
                    return participant.userId === this.getUser.id
                })
                if (index > -1) {
                    return this.assessment.participants[index]
                }

                return null
            },
            computedAnswersWithoutFiles() {
                return this.answers.map(answer=> this.mappedItem(answer))
            },
            computedUserParticipant(){
                return this.computedIsOwner || this.computedParticipant ? true : false
            },
            computedCanJoin(){
                return !this.computedPendingParticipant && 
                    !this.computedUserParticipant && 
                    !this.computedHasAnswered &&
                    this.assessment.type === 'PUBLIC'
            },
            computedUnSentAnswers() {
                return this.answeringOrMarkingType === 'all' ? [] :
                    this.answers.filter(answer=>{
                        return  ! answer.isSent
                    })
            },
            computedWorkAnswers() {
                return this.work ? this.work.answers : []
            },
            computedWorkAnswersWithoutMark() {
                return this.computedWorkAnswers.filter(answer=>!answer.mark)
            },
            computedShowSubmitMarks() {
                return this.computedWorkAnswersWithoutMark.length && this.marks.length
            },
            computedItemableStorageKey() {
                return `${this.computedItem.item}${this.computedItem.itemId}`
            },
        },
        methods: {
            ...mapActions([
                'home/removeAssessment','home/replaceAssessment',
                'profile/removeAssessment','profile/replaceAssessment',
                'dashboard/removeAssessment','dashboard/replaceAssessment',
                'dashboard/deleteAssessment', 'dashboard/answerAssessment',
                'dashboard/getWork', 'dashboard/markAssessment',
                'profile/deleteMark', 'profile/updateMark',
                'profile/deleteAnswer', 'profile/updateAnswer',
            ]),
            listen() {
                const route = useRoute()
                
                Echo.channel(`youredu.assessment.${this.assessment.id}`)
                    .listen('.updateAssessment', data=>{
                        this[`${route.name}/replaceAssessment`](data.assessment)
                    })
                    .listen('.deleteAssessment', data=>{
                        this[`${route.name}/removeAssessment`](data)
                    })
            },
            goBack() {
                this.goToPreviousView()
                this.removeLastView()
            },
            goToPreviousView() {
                let index = this.viewHistory.length - 2

                if (index < 0) {
                    index = 0
                }

                this.goToStep(this.viewHistory[index], false)
            },
            removeLastView() {
                this.viewHistory.pop()
            },
            clearViewHistory() {
                this.viewHistory = [0]
            },
            addStepToViewHistory() {
                this.viewHistory.push(this.steps)
            },
            handleGoToStep(number) {
                if (number == 0) {
                    this.answerAssessment({done: true})
                }
                
                this.removeLastView()
                this.goToStep(number)
            },
            setStorage() {
                this.getStorageData()
            },
            unsetStorage() {

                if (this.filterNotSent(this.answers).length) {
                    this.setStorageData('answers')
                }

                if (this.filterNotSent(this.marks).length) {
                    this.setStorageData('marks')
                }
            },
            filterNotSent(items) {
                return items.filter(item=>! item.isSent)
            },
            getStorageDataObject() {
                let data = this.getItem(this.computedItemableStorageKey)
                
                if (! data) {
                    data = {}
                }

                return data
            },
            getStorageData() {
                let data = this.getStorageDataObject()

                if (data.answers) {
                    this.answers = data.answers
                }

                if (data.marks) {
                    this.marks = data.marks
                }
            },
            setStorageData(what) {
                let data = this.getStorageDataObject()

                if (what === 'answers') {
                    data.answers = this.answers
                }

                if (what === 'marks') {
                    data.marks = this.marks
                }
                
                this.setItem(this.computedItemableStorageKey, data)
            },
            deletePartOfStorageData(what) {
                let data = this.getStorageDataObject()

                if (what === 'answers') {
                    delete data.answers
                }

                if (what === 'marks') {
                    delete data.marks
                }
                
                this.setItem(this.computedItemableStorageKey, data)
            },
            removeStorageData() {
                this.removeItem(this.computedItemableStorageKey)
            },
            initiatedAssessmentSection(section) {
                let index = this.assessment.assessmentSections.findIndex(assessmentSection=>{
                    return section.id === assessmentSection.id
                })

                if (index === -1) {
                    return
                }

                this.assessment.assessmentSections[index].initiated = true
            },
            handleFewMinutesMore(data) {
                this.issueDangerAlert({
                    message: `less than five minites left`,
                })
            },
            handleNoTimeLeft(data) {
                if (data?.item === 'assessmentSection') {
                    return
                }

                this.noTimeLeftHandled = true

                if (this.computedHasAnswered) {
                    return
                }

                if (this.answeringOrMarkingType != 'all') {
                    this.computedUnSentAnswers.forEach(answer=>{
                        this.sendAnswer(answer)
                    })
                }

                if (this.answeringOrMarkingType == 'all') {
                    this.answerAssessment({done: false, dontCheck: true})
                }

                this.answerAssessment({done: true})
            },
            answered(data) {
                if (! ['update', 'delete'].includes(data.type)) {

                    this.addAnswer(data)

                    this.storeItems('answers')

                    this.sendAnswer(data)

                    return
                }

                if (data.type === 'update') {
                    this.updateAnswer(data)
                    return
                }
                
                this.showProfilesAction = 'delete answer'
                this.issueCustomMessage({
                    message:'are you sure you want to delete this answer?',
                    data,
                    type: 'delete'
                })
            },
            addAnswer(data) {
                let index = this.answers.findIndex(answer=> {
                    return answer.questionId == data.questionId
                })

                if (index > -1) {

                    this.answers.splice(index, 1, this.cloned(data))
                    return
                }

                this.answers.push(this.cloned(data))
            },
            storeItems(type) {
                if (this.answeringOrMarkingType !== 'all') {
                    return
                }
                
                this.setStorageData(type)
            },
            marked(data) {
                if (! ['update', 'delete'].includes(data.type)) {

                    this.addMark(data)
    
                    this.storeItems('marks')
    
                    this.sendMark(data)

                    return
                }

                if (data.type === 'update') {
                    this.updateAnswerMark(data)
                    return
                }
                
                this.showProfilesAction = 'delete mark'
                this.issueCustomMessage({
                    message:'are you sure you want to delete this mark?',
                    data,
                    type: 'delete'
                })
            },
            findIndexOfAnswer(fn) {
                return this.work.answers.findIndex(answer => fn(answer))
            },
            spliceMark(mark, replace = false) {

                let index = this.findIndexOfAnswer(
                    (answer) => answer.mark?.id == mark.id
                )

                if (replace) {
                    
                    this.work.answers[index].mark = mark
                    return
                }

                this.work.answers[index].mark = null
            },
            spliceAnswer(answer, replace = false) {

                this.spliceItem({
                    items: this.work.answers,
                    item: answer,
                    replace,
                    index: this.findIndexOfAnswer(
                        (ans) => ans.id == answer?.id
                    )
                })
            },
            spliceItem({items, item, index, replace}) {

                if (index === -1) return

                if (replace) {
                    
                    items.splice(index, 1, item)
                    return
                }

                items.splice(index, 1)
            },
            async updateAnswerMark(data) {
                this.loading = true

                let response = await this['profile/updateMark']({
                    markId: data.mark.id, 
                    data: data.newMarkData
                })

                this.loading = false

                if (! response.status) {
                    this.issueDangerAlertForResponse(response)
                    return
                }

                this.spliceMark(response.mark, true)
                this.removeAssessmentItem(data.newMarkData, 'marks')
            },
            async deleteAnswerMark(data) {
                this.loading = true

                let response = await this['profile/deleteMark']({markId: data.mark.id})

                this.loading = false

                if (! response.status) {
                    this.issueDangerAlertForResponse(response, 'oops 😕! something happened. please try again later')
                    return
                }

                this.spliceMark(data.mark)
                this.removeAssessmentItem(data.newMarkData, 'marks')
            },
            async updateAnswer(data) {
                this.loading = true

                let formData = new FormData

                formData.append('account', this.computedParticipant.account)
                formData.append('accountId', this.computedParticipant.accountId)
                formData.append('item', 'question')
                formData.append('itemId', data.newAnswerData.questionId)

                formData.append('answer', data.newAnswerData.answer)
                if (data.newAnswerData.file) {
                    formData.append('files[]', data.newAnswerData.file)
                }
                formData.append('possibleAnswerIds', JSON.stringify(data.newAnswerData.possibleAnswerIds))

                let response = await this['profile/updateAnswer']({
                    answerId: data.answer.id, 
                    formData
                })

                this.loading = false

                if (! response.status) {
                    this.issueDangerAlertForResponse(response, 'oops 😕! something happened. please try again later')
                    return
                }

                this.spliceAnswer(response.answer, true)
                this.removeAssessmentItem(data.newAnswerData)
            },
            async deleteAnswer(data) {
                this.loading = true

                let response = await this['profile/deleteAnswer']({
                    answerId: data.answer.id,
                    data: {
                        account: this.work.addedby.account,
                        accountId: this.work.addedby.accountId,
                        item: 'question',
                        itemId: data.answer.question.id,
                    }
                })

                this.loading = false

                if (! response.status) {
                    this.issueDangerAlertForResponse(response)
                    return
                }

                this.spliceAnswer(data.answer)
                this.removeAssessmentItem(data.newAnswerData)
            },
            addMark(data) {
                let index = this.marks.findIndex(mark=> {
                    return mark.answerId == data.answerId
                })

                if (index > -1) {

                    this.marks.splice(index, 1, this.cloned(data))
                    return
                }

                this.marks.push(this.cloned(data))
            },
            sendMark(mark) {
                if (this.answeringOrMarkingType === 'all') {
                    return
                }

                this.markAssessment({mark, done: false, dontCheck: false})
            },
            cloned(data) {
                return _.clone(data)
            },
            sendAnswer(answer) {
                if(this.answeringOrMarkingType === 'all') {
                    return
                }

                this.answerAssessment({answer, done: false, dontCheck: false})
            },
            mappedItem(item, type = 'answer') {
                let data = {}

                if (type == 'answer') {
                    data.questionId = item.questionId

                    if (item.answer) {
                        data['answer'] = item.answer
                    }

                    if (item.possibleAnswerIds?.length) {
                        data['possibleAnswerIds'] = item.possibleAnswerIds
                    }
                }

                if (type == 'mark') {
                    data.answerId = item.answerId
                    data.score = item.score
                    data.remark = item.remark
                }

                return data
            },
            async answerAssessment({answer, done, dontCheck}) {
                this.loading = true
                let response,
                    formData = new FormData

                formData.append('type', this.answeringOrMarkingType)
                formData.append('account', this.computedParticipant.account)
                formData.append('accountId', this.computedParticipant.accountId)

                if (this.answeringOrMarkingType == 'all' && !done) {

                    if (this.computedNumberOfQuestionsToAnswer > this.answers.length && !dontCheck) {
                        this.issueDangerAlert({message: 'you have not finished answering your questions'})
                        this.loading = false
                        return
                    }

                    formData.append('answers', JSON.stringify(this.computedAnswersWithoutFiles))

                    this.answers.forEach(answer=>{

                        if (answer.file) {
                            formData.append(`answerFile${answer.questionId}`, answer.file)
                        }
                    })
                }

                if (this.answeringOrMarkingType != 'all' && answer && !done) {
                    formData.append('answer', JSON.stringify(this.mappedItem(answer)))

                    if (answer.file) {
                        formData.append(`answerFile${answer.questionId}`, answer.file)
                    }
                }

                response = await this['dashboard/answerAssessment']({
                    assessmentId: this.assessment.id,
                    userId: this.getUser.id,
                    formData,
                    done: done ? done : false,
                    addUserId: done || this.answeringOrMarkingType === 'all'
                })

                this.loading = false

                if (! response.status) {
                    this.issueDangerAlertForResponse(response)
                    return
                }

                this.updateAnswerOrMarkToSent(answer)
                
                this.alertSuccess = true

                if (this.answeringOrMarkingType !== 'all') {
                    this.alertMessage = `answer sent`
                    this.updateWork(response.answer)
                    return
                }

                if (done) {
                    this.alertMessage = 'yaay... you are done answering this assessment 😎'
                }

                if (!done) {
                    this.alertMessage = `you have successfully sent your answers for this assessment`
                }

                this.work = response.work

                this.goToStep(0, false)
                this.clearItems()
            },
            updateWork(answer) {
                if (! this.work) {
                    return
                }

                let index = this.work.answers.findIndex(ans => answer.id === ans.id)

                if (index === -1) {
                    return
                }

                this.work.answers.splice(index, 1, answer)
            },
            async markAssessment({mark, done, dontCheck}) {
                this.loading = true
                let response,
                    formData = new FormData

                formData.append('type', this.answeringOrMarkingType)
                formData.append('account', this.computedAccount.account)
                formData.append('accountId', this.computedAccount.accountId)
                formData.append('workId', this.work.id)

                if (this.answeringOrMarkingType == 'all' && !done) {
                    formData.append('userId', this.work.addedby.userId)

                    if (this.computedWorkAnswersWithoutMark.length > this.marks.length && !dontCheck) {
                        this.issueDangerAlert({message: 'you have not finished marking your answers'})
                        this.loading = false
                        return
                    }

                    formData.append('marks', JSON.stringify(this.marks.map(mark => this.mappedItem(mark, 'mark'))))
                }

                if (this.answeringOrMarkingType != 'all' && mark && !done) {
                    formData.append('mark', JSON.stringify(this.mappedItem(mark, 'mark')))
                }

                response = await this['dashboard/markAssessment']({
                    assessmentId: this.assessment.id,
                    formData,
                    done
                })

                this.loading = false

                if (! response.status) {
                    this.issueDangerAlertForResponse(response)
                    return
                }
                
                this.alertSuccess = true

                if (done) {
                    this.alertMessage = 'yaay... you are done marking this work 😎'
                    this.clearItems('marks')
                    return
                }

                this.updateAnswerOrMarkToSent(mark, 'mark')

                if (this.answeringOrMarkingType !== 'all') {
                    this.alertMessage = `mark sent`
                    return
                }

                this.alertMessage = `you have successfully sent the marks for this work`
                this.work = response.work
                this.clearItems('marks')
            },
            clearItems(type = 'answers') {
                if (! this[type]) {
                    return
                }

                this[type] = []

                this.deletePartOfStorageData(type)
            },
            goToStep(number, addToView = true) {
                this.steps = number

                if (addToView) {
                    this.addStepToViewHistory()
                }
            },
            updateAnswerOrMarkToSent(item, type = 'answer') {
                if (!item) {
                    return
                }

                let property = type === 'answer' ? 'questionId' : 'answerId'

                this[`${type}s`].forEach(markableItem => {
                    if (item[property] == markableItem[property]) {
                        markableItem.isSent = true
                    }
                })
            },
            closeItemRequestSection() {
                this.showRequest = false
                this.joinOrInvitationType = ''
            },
            clickedButton(data) {
                if (this.loading) {
                    return
                }

                if (typeof data !== 'string' && data.text === 'mark work') {
                    this.markWork(data.account)
                    return
                }

                if (data === 'take it') {
                    this.takeAssessment()
                    return
                }

                if (data === 'start') {
                    this.checkPopup()
                    return
                }

                if (data === 'continue') {
                    this.startAssessment(true)
                    return
                }

                if (data === 'mark') {
                    this.startMarkingAssessment()
                    return
                }

                if (data === 'invite marker') {
                    this.requestMarker()
                    return
                }

                if (data === 'view work') {
                    // this.startAssessment(true)
                    this.viewWork()
                    return
                }

                if (data === 'invite participant') {
                    this.requestParticipant()
                    return
                }

                if (data === 'submit answers') {
                    this.answerAssessment({done: false})
                    return
                }

                if (data === 'submit marks') {
                    this.markAssessment({done: false})
                    return
                }

                if (data === 'join markers') {
                    this.showProfilesText = 'mark assessment as'
                    this.showOnlyProfiles = true
                }

                if (data === 'want to take') {
                    this.showProfilesText = 'take assessment as'
                    this.showOnlyProfiles = true
                }

                this.showProfilesAction = data
                this.showProfiles = true
            },
            markWork(account) {
                if (this.isAddedbyForWork(account)) {
                    this.goToStep(4)
                    return
                }

                this.getWork(account)
            },
            viewWork() {
                this.getWork(this.computedAccount, true)
            },
            isAddedbyForWork(account) {

                if (! this.work?.userId) {
                    return false
                }

                if (account.userId == this.work.addedby.userId) {
                    return true
                }

                return false
            },
            setWork(work) {

                this.work = work
                this.goToStep(4)
            },
            async getWork(account, marks = false) {
                this.loading = true

                let response,
                    data = {
                        account: account.account,
                        accountId: account.accountId,
                        userId: account.userId,
                    }

                if (marks) {
                    data.marks = JSON.stringify(marks)
                }

                response = await this['dashboard/getWork']({
                    data,
                    assessmentId: this.assessment.id
                })
                
                this.loading = false

                if (response.status) {
                    this.setWork(response.work)
                    return
                }

                this.issueDangerAlertForResponse(response,'oops! something happened 😕')
                this.goToStep(0)
            },
            removeAssessmentItem(item, type = 'answers') {
                if (!item) {
                    return
                }
                
                let index = -1

                if (type === 'answers') {

                    index = this.answers.findIndex(answer=>{
                        return answer.questionId === item.questionId
                    })
                }

                if (type === 'marks') {

                    index = this.marks.findIndex(mark=>{
                        return mark.answerId === item.answerId
                    })
                }

                if (index == -1) {
                    return
                }

                this[type].splice(index,1)
            },
            requestParticipant() {
                this.displayRequestSection('participant')
            },
            requestMarker() {
                this.displayRequestSection('marker')
            },
            displayRequestSection(type) {
                this.showRequest = true
                this.joinOrInvitationType = type
            },
            startMarkingAssessment() {
                this.goToStep(3)
            },
            takeAssessment() {
                this.goToStep(1)
            },
            async startAssessment(getWork = false) {
                if (getWork) {
                    await this.getWork(this.computedAccount)
                }
                this.startTimer()
                this.goToStep(2)
            },
            clickedPopupResponse(data) {
                if (data === 'continue') {
                    this.startAssessment()
                }

                this.showPopUp = false
            },
            checkPopup() {
                if (this.assessment.timer) {
                    this.startAssessment()
                    return
                }

                if (this.assessment.duration) {
                    this.showPopUp = true
                    return
                }

                this.startAssessment()
            },
            clickedMedia() {

            },
            clickedShowPostComments() {
                this.$emit('clickedShowPostComments',{item: this.post, type:'assessment'})
            },
            askLoginRegister(data){
                this.$emit('askLoginRegister',data)
            },
            postAddComplete(data){
                if (data === 'successful') {
                    this.showAddComment = false
                    this.alertSuccess = true
                    this.alertMessage = 'comment created successfully 😎'
                    return
                }

                this.alertDanger = true
                this.alertMessage = 'comment creation failed 😞'
            },
            clickedAddComment(){
                if(this.computedBanned) return

                if (!this.getUser) {
                    this.$emit('askLoginRegister','discussionsingle')
                    return
                }
                
                if (!this.getProfiles || !this.getProfiles.length) {
                    this.issueSmallModalInfoMessage({
                        message: 'you must have an account (eg. learner, parent, etc) before you can comment.'
                    })
                    return
                }
                
                this.showAddComment = true
            },
            clickedProfile(data){
                this.showProfiles = false
                
                if (this.showProfilesAction === 'want to take') {
                    this.join(data)
                    return
                }
                
                if (this.showProfilesAction === 'join markers') {
                    this.joinOrInvitationType = 'marker'
                    this.join(data)
                    return
                }
                
                if (this.showProfilesAction === 'like') {
                    this.like(data)
                    return
                }
                
                if (this.showProfilesAction === 'save') {
                    this.save(data)
                    return
                }
                
                if (this.showProfilesAction === 'flag') {
                    this.issueCustomMessage({
                        message:'are you sure you want to flag this?',
                        data,
                        type: 'delete'
                    })
                    return
                }
                
                if (this.showProfilesAction === 'attach') {
                    this.attach(data)
                }
            },
            clickedSmallModalButton(data){
                if (data === 'ok') {
                    return
                }
                
                if (data === 'no') {
                    this.otherUserAccountLoading = false //incase this is for leaving or removing participants
                    this.clearSmallModal()
                    return
                }
                
                if (this.showProfilesAction === 'delete') {
                    this.deleteDiscussion()
                    return
                }
                
                if (this.showProfilesAction === 'delete answer') {
                    this.deleteAnswer(this.smallModalData)
                    return
                }
                
                if (this.showProfilesAction === 'delete mark') {
                    this.deleteAnswerMark(this.smallModalData)
                    return
                }
                
                if (this.showProfilesAction === 'participant') {
                    this.deleteDiscussionParticipant(this.smallModalData)
                    return
                }
                
                if (this.showProfilesAction === 'flag') {
                    this.flag(this.smallModalData)
                }
                
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>