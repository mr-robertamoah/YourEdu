<template>
    <div
        v-if="assessment"
        class="assessment-single-wrapper min-w-1/2 min-h-90vh relative"
    >
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
                class="absolute w-full text-center top-1/2"
                :loading="loading" 
                :size="'10px'"
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
                        class="flex-shrink-0 my-1"
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
                        {{`${computedQuestionsNumber} total questions`}}
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
                        buttonText="start"
                        class="p-1 ml-5"
                        @click="clickedButton('start')"
                    ></special-button>
                </div>
            </div>
            <div v-if="steps === 2" class="h-90vh w-full flex flex-col">
                <div class="flex text-gray-500 text-sm">
                    <div class="mr-1">Time remaining:</div>
                    <div>40 minutes</div>
                </div>
                <assessment-section-answering-form
                    class="h-full"
                    :assessment="assessment"
                ></assessment-section-answering-form>
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
    </div>
</template>

<script>
import SpecialButton from './SpecialButton'
import ItemViewCover from './ItemViewCover'
import ProfilePicture from './profile/ProfilePicture';
import AssessmentSectionMiniBadge from './dashboard/AssessmentSectionMiniBadge'
import QuestionAnsweringBadge from './dashboard/QuestionAnsweringBadge'
import ItemRequestSection from './ItemRequestSection';
import AssessmentSectionAnsweringForm from './forms/AssessmentSectionAnsweringForm';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import FadeUp from './transitions/FadeUp'
import Alert from '../mixins/Alert.mixin';
import Like from '../mixins/Like.mixin';
import Flag from '../mixins/Flag.mixin';
import Save from '../mixins/Save.mixin';
import Profiles from '../mixins/Profiles.mixin';
import Participation from '../mixins/Participation.mixin';
import SmallModal from '../mixins/SmallModal.mixin'
import Comments from '../mixins/Comments.mixin'
import { mapGetters, mapActions } from 'vuex'
    export default {
        components: {
            QuestionAnsweringBadge,
            AssessmentSectionMiniBadge,
            ItemViewCover,
            SpecialButton,
            FadeUp,
            ProfilePicture,
            AssessmentSectionAnsweringForm,
            ItemRequestSection,
            PulseLoader
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
            Alert, Like, Flag, Save, SmallModal, Comments, Participation,
            Profiles
        ],
        data() {
            return {
                steps: 0,
                assessmentFull: false,
                showRequest: false,
                loading: false,
            }
        },
        watch: {
            computedItemable(newValue){
                if (newValue) {
                    
                    // this.setMyFlag()
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
            computedQuestionsNumber() {
                return this.assessment.assessmentSections.reduce(
                    function(sum, section) {
                        return sum + section.questions.length
                    }, 0
                )
            },
            computedItem() {
                return {
                    itemId: this.assessment.id,
                    item: 'assessment'
                }
            },
            computedItemable() {
                return this.assessment
            },
            computedIsOwner() {
                return this.assessment.addedby.userId === this.getUser?.id
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
            computedCanParticipate() {
                if (this.computedParticipant) {
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
                    this.assessment.addedby
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
            computedUserParticipant(){
                return this.computedIsOwner || (this.computedParticipant && 
                    this.computedParticipant.id) ? true : false
            },
            computedCanJoin(){
                return !this.computedPendingParticipant && 
                    !this.computedUserParticipant && 
                    this.assessment.type === 'PUBLIC'
            },
        },
        methods: {
            ...mapActions([
                'home/removeAssessment','home/replaceAssessment',
                'profile/removeAssessment','profile/replaceAssessment',
                'dashboard/removeAssessment','dashboard/replaceAssessment',
                'dashboard/deleteAssessment', 'dashboard/updateAssessment'
            ]),
            listen() {
                
                Echo.channel(`youredu.assessment.${this.assessment.id}`)
                    .listen('.updateAssessment', data=>{
                        this[`${this.$route.name}/replaceAssessment`](data.assessment)
                    })
                    .listen('.deleteAssessment', data=>{
                        this[`${this.$route.name}/removeAssessment`](data)
                    })
            },
            closeItemRequestSection() {
                this.showRequest = false
                this.joinOrInvitationType = ''
            },
            clickedButton(text) {
                if (text === 'take it') {
                    this.takeAssessment()
                    return
                }

                if (text === 'start') {
                    this.startAssessment()
                    return
                }

                if (text === 'mark') {
                    this.startMarkingAssessment()
                    return
                }

                if (text === 'invite marker') {
                    this.requestMarker()
                    return
                }

                if (text === 'invite participant') {
                    this.requestParticipant()
                    return
                }

                if (text === 'join markers') {
                    this.showProfilesText = 'mark assessment as'
                    this.showOnlyProfiles = true
                }

                if (text === 'want to take') {
                    this.showProfilesText = 'take assessment as'
                    this.showOnlyProfiles = true
                }

                this.showProfilesAction = text
                this.showProfiles = true
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

            },
            takeAssessment() {
                this.steps = 1
            },
            startAssessment() {
                this.steps = 2
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