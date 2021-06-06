<template>
    <div
        v-if="assessment"
        class="assessment-single-wrapper min-w-1/2 max-w-lg h-90vh relative"
    >
        <item-view-cover
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
                    v-if="computedCanParticipate"
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
        <div class="border-2 rounded-lg flex flex-col h-full"
            v-if="steps"
        >
            <div  
                class="p-3 flex flex-col h-full overflow-y-auto justify-around"
                v-if="steps === 1"
            >
                <div  class="flex items-center text-gray-500 text-sm w-full mb-10">
                    <div class="">
                        created by
                    </div>
                    <profile-picture class="h-5 w-5 flex-shrink-0 my-1">
                        <template slot="image">
                            <img :src="assessment.addedby.url" >
                        </template>
                    </profile-picture>
                    <div class="font-semibold text-center">
                        {{assessment.addedby.name}}
                    </div>
                </div>
                <div class="w-full bg-gray-50 p-2 mb-10">
                    <div class="w-3/4 text-lg font-black">
                        {{assessment.name}}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{assessment.description}}
                    </div>
                </div>
                <div class="w-full bg-gray-50 text-gray-500 p-2 text-sm mb-10">
                    <div v-if="assessment.duration">
                        {{`duration of ${assessment.duration} mins`}}
                    </div>
                    <div>{{`total mark of ${assessment.totalMark}`}}</div>
                    <div v-if="assessment.dueAt">
                        {{`due ${assessment.dueAt}`}}
                    </div>
                    <div>
                        {{`${assessment.assessmentSections.length} number of assessment sections`}}
                    </div>
                    <div>
                        {{`${computedQuestionsNumber} total number of questions`}}
                    </div>
                    <div v-if="assessment.worksCount">
                        {{`${assessment.worksCount} persons have taken the assessment`}}
                    </div>
                </div>
                <div class="p-2 flex w-full flex-nowrap mb-10 overflow-x-auto bg-gray-50 p-2">
                    <assessment-section-mini-badge
                        class="min-w-full mx-1 flex-grow-0"
                    ></assessment-section-mini-badge>
                    <assessment-section-mini-badge
                        class="min-w-full mx-1 flex-grow-0"
                    ></assessment-section-mini-badge>
                    <assessment-section-mini-badge
                        class="min-w-full mx-1 flex-grow-0"
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
            <div v-if="steps === 2" class="h-full w-full flex flex-col">
                <div class="flex text-gray-500 text-sm">
                    <div class="mr-1">Time remaining:</div>
                    <div>40 minutes</div>
                </div>
                <div class="shadow-sm border-b-2 w-full flex-shrink-0 bg-gray-50 h-10 flex justify-center items-center mt-1 mb-3 relative">
                    <div class="absolute left-0 top-0 text-gray-400 text-xs">current section</div>
                    <div class="text-lg font-header capitalize">name of section</div>
                </div>
                <div class="w-full px-5 mb-2 flex-shrink-0">
                    <div class="overflow-ellipsis text-center text-gray-500 text-sm">instruction Aliquip occaecat non adipisicing laborum non nisi culpa officia sunt cillum consequat.</div>
                    <div class="text-xs text-right text-gray-400">10 questions</div>
                    <div class="text-gray-400 text-right text-xs">the questions where selected randomly</div>
                </div>
                <div class="h-full max-h-3/4 flex-shrink mb-2 overflow-y-auto p-2">
                    <question-answering-badge></question-answering-badge>
                    <question-answering-badge></question-answering-badge>
                    <question-answering-badge></question-answering-badge>
                    <question-answering-badge></question-answering-badge>
                </div>
                <div class="flex-shrink-0 flex justify-around">
                    <button class="text-gray-500 p-2 border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded-sm"
                        @click="clickedSectionNavigator('previous')"
                    >previous</button>
                    <button class="text-gray-500 p-2 border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded-sm"
                        @click="clickedSectionNavigator('next')"
                    >next</button>
                </div>
            </div>
            <reaction-component
                class="flex-grow-0 flex-shrink-0"
                :comments="computedComments"
                :item="computedItem"
                :isOwner="computedIsOwner"
                :full="assessmentFull"
                :showAddComment="showAddComment"
                :showFlagReason="showFlagReason"
                :flagData="flagData"
                :likeData="likeData"
                :showProfilesText="showProfilesText"
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
            :loading="searchLoading"
            @doneRemovingParticipant="doneRemovingParticipant"
            @clickedCloseRequest="closeItemRequestSection"
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
import PulseLoader from 'vue-spinner/src/PulseLoader';
import FadeUp from './transitions/FadeUp'
import Alert from '../mixins/Alert.mixin';
import Like from '../mixins/Like.mixin';
import Flag from '../mixins/Flag.mixin';
import Save from '../mixins/Save.mixin';
import RemoveParticipant from '../mixins/RemoveParticipant.mixin';
import SmallModal from '../mixins/SmallModal.mixin'
import Comments from '../mixins/Comments.mixin'
import { mapGetters } from 'vuex'
    export default {
        components: {
            QuestionAnsweringBadge,
            AssessmentSectionMiniBadge,
            ItemViewCover,
            SpecialButton,
            FadeUp,
            ProfilePicture,
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
        },
        mixins: [Alert, Like, Flag, Save, SmallModal, Comments, RemoveParticipant],
        data() {
            return {
                steps: 0,
                assessmentFull: false,
                showProfiles: false,
                showProfilesText: '',
                showRequest: false,
                searchLoading: false,
                requestFor: '',
            }
        },
        watch: {
            showProfiles(newValue){
                if (newValue) {
                    setTimeout(() => {
                        this.showProfiles = false
                    }, 4000);
                }
            },
        },
        computed: {
            ...mapGetters([
                'getUser', 'getProfiles'
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
                return this.getUser && this.assessment.addedby.userId === this.getUser.id
            },
            computedProfiles() {
                return this.getProfiles
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
                if (! this.getProfiles) {
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
                if (!this.getUser) {
                    return null
                }

                let index = this.assessment.participants?.findIndex(participant=>{
                    return participant.userId === this.getUser.id
                })
                if (index > -1) {
                    return this.assessment.participants[index]
                }

                if (this.computedIsOwner) {
                    this.assessment.addedby
                }

                return null
            },
            computedPendingParticipant(){
                return this.getUser && 
                    this.assessment.pendingJoinParticipants.findIndex(pending=>{
                        return pending.userId === this.getUser.id
                    }) > -1
            },
            computedUserParticipant(){
                return this.computedIsOwner || (this.computedParticipant && 
                    this.computedParticipant.id) ? true : false
            },
            computedJoin(){
                return !this.computedPendingParticipant && 
                    !this.computedUserParticipant && this.computedCanJoin && 
                    this.assessment.type === 'PUBLIC'
            },
        },
        methods: {
            closeItemRequestSection() {
                this.showRequest = false
                this.steps -= 1
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
                    this.steps += 1
                    this.requestMarker()
                    return
                }

                if (text === 'invite participant') {
                    this.steps += 1
                    this.requestParticipant()
                    return
                }

                if (text === 'join markers') {
                    this.showProfilesText = 'mark assessment as'
                }

                if (text === 'want to take') {
                    this.showProfilesText = 'take assessment as'
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
                this.requestFor = type
            },
            startMarkingAssessment() {

            },
            clickedSectionNavigator(text) {

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
            async join(account) {
                let response,
                    data = {
                        account: account.account,
                        accountId: account.accountId,
                    }
                data[`${this.computedItem.item}Id`] = this.computedItem.itemId

                this.loading = true

                response = await this['profile/joinItem']({
                    computedItem: this.computedItem, 
                    item: this.computedItemable,
                    data
                })

                this.loading = false
                
                if (! response.status) {
                    this.responseErrorAlert(response, "oops 😕! something happened. please try again later")
                    return
                }

                this.alertSuccess = true

                if (this.computedItemable.type === 'PRIVATE') {
                    this.alertMessage = "you have successfully requested to participate. have fun as you wait for owners response 😏."
                    return
                }

                this.alertMessage = `you have been successfully added. you can now participate 😎.`

            },
            clickedProfile(data){
                this.showProfiles = false
                
                if (this.showProfilesAction === 'want to take') {
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