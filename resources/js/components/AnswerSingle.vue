<template>
    <div class="answer-single-wrapper"
        @dblclick.self="clickedShowAnswerSingle"
        :class="{answerFull:answerFull}"
    >
        <div>
            <div class="score">
                <fade-right>
                    <template slot="transition" v-if="showScore">
                        <answer-mark
                            :show="showScore"
                            @hideAnswerMark="showScore = false"
                            @answerMarkScore="getScore"
                            :inputMax="computedScoreOver"
                            :scoreOver="`${computedScoreOver}`"
                            :justRemark="justRemark"
                        ></answer-mark>
                    </template>
                </fade-right>
            </div>
            <div class="edit"
                @click="clickedShowOptions"
                v-if="computedEdit"
            >
                <font-awesome-icon
                    :icon="['fa','chevron-down']"
                ></font-awesome-icon>
            </div>
            <div class="options" v-if="showOptions && computedSaves">
                <optional-actions
                    :show="showOptions"
                    :showEdit="!answer.marks.length"
                    :hasSave="!chat && !computedOwner"
                    :isSaved="isSaved"
                    :hasAttachment="!chat"
                    :hasEdit="!chat && computedOwner"
                    :hasDelete="computedOwner"
                    @clickedOption="clickedOption"
                ></optional-actions>
            </div>
            <div class="main-info">
                <div class="name"
                    @click="clickedProfilePicture"
                >{{computedName}}</div>
                <div class="type">{{computedType}}</div>
            </div>
            <div class="loading" v-if="loading">
                <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
            </div>
            <div class="alert-message" 
                v-if="alertMessage.length"
                :class="{danger:alertDanger,success: alertSuccess}"
            >
                {{alertMessage}}
            </div>
            <div class="main-area">
                <div class="left-wrapper">
                    <div class="top-section">
                        <div class="profile-picture" v-if="!chat">
                            <profile-picture
                                @click="clickedProfilePicture"
                                
                            >
                                <template slot="image">
                                    <img :src="answer.url" alt="profile">
                                </template>
                            </profile-picture>
                        </div>
                        <div class="other-info" @click="clickedShowRemarks">
                            <div class="info">{{computedCreatedAt}}</div>
                            <div class="info">
                                {{`${computedAverageScore} - average`}}
                            </div>
                            <div class="info" v-if="answerFull">
                                {{`${computedMaximumScore} - maximum`}}
                            </div>
                            <div class="info" v-if="answerFull">
                                {{`${computedMinimumScore} - minimum`}}
                            </div>
                        </div>
                        <remarks-badge
                            v-if="showRemarks"
                            :show="showRemarks"
                            :answerId="answer.id"
                            @clickedCloseRemark="clickedCloseRemark"
                        ></remarks-badge>
                    </div>
                    <div class="bottom-section">
                        <div class="textarea">
                            <main-textarea
                                :value="computedAnswer"
                                :disabled="true"
                                :sm="!answerFull"
                            ></main-textarea>
                        </div>
                        <div class="media">
                            <template v-if="computedImageUrl.length">
                                <img src="" alt="media">
                            </template>
                            <template v-if="computedVideoUrl.length">
                                <video src="">
                                    media cannot play
                                </video>
                            </template>
                            <template v-if="computedAudioUrl.length">
                                <audio src=""></audio>
                            </template>
                        </div>
                    </div>
                    <div class="lower-section" v-if="!chat">
                        <div class="extra-info">
                            <div class="info" v-if="computedMarkings">
                                {{`${computedMarkings} accounts marked this answer`}}
                            </div>
                            <div class="reaction-section"
                                :class="{reactionReason:showFlagReason}"
                            >
                                <div
                                    class="flag"
                                    :class="{flagged:isFlagged}"
                                    @click="clickedFlag"
                                    v-if="computedFlags && !computedOwner"
                                    :title="flagTitle"
                                >
                                    <font-awesome-icon
                                        :icon="['fa','flag']"
                                    ></font-awesome-icon>
                                </div>
                                <div class="reason">
                                    <flag-reason
                                        :show="showFlagReason"
                                        @continueFlagProcess="continueFlagProcess"
                                        @reasonGiven="reasonGiven"
                                        @cancelFlagProcess="cancelFlagProcess"
                                    ></flag-reason>
                                </div>
                                <div class="comment-number" 
                                    v-if="computedCommentNumber"
                                    @click="clickedViewComments"
                                    :title="commentTitle"
                                >
                                    {{`${commentsNumber}`}} <font-awesome-icon
                                        :icon="['fa','comment-alt']"
                                    ></font-awesome-icon>
                                </div>
                                <div class="like-section">
                                    <div class="like-number">{{likes}}</div>
                                    <div class="like"
                                        @click="clickedLike"
                                        v-if="computedLikes"
                                        :class="{liked:isLiked}"
                                        :title="likeTitle"
                                    >
                                        <font-awesome-icon
                                            :icon="['fa','thumbs-up']"
                                        ></font-awesome-icon>
                                    </div>
                                </div>
                                <div class="comment"
                                    title="add a comment"
                                    @click="clickedAddComment"
                                    v-if="!showAddComment"
                                    :class="{success:commentSuccess,fail:commentFail}"
                                >
                                    <font-awesome-icon
                                        :icon="['fa','comment']"
                                    ></font-awesome-icon>
                                </div>
                            </div>
                        </div>
                        <div class="add-comment" v-if="!chat">
                            <fade-right-fast>
                                <template slot="transition" v-if="showAddComment">
                                    <add-comment
                                        what="answer"
                                        :id="answer.id"
                                        :showAddComment="showAddComment"
                                        @hideAddComment="showAddComment = false"
                                        @postAddComplete="postAddComplete"
                                        @postModalCommentCreated="postModalCommentCreated"
                                        :onPostModal="true"
                                    ></add-comment>
                                </template>
                            </fade-right-fast>
                        </div>
                        <div class="edit-anser" v-if="!chat">
                            <fade-right-fast>
                                <template slot="transition" v-if="editAnswer">
                                    <add-answer
                                        :answerEditable="answer"
                                        v-if="!possibleAnswers.length"
                                        :edit='editAnswer'
                                        @addAnswer="addAnswer"
                                ></add-answer>
                                    <main-list
                                        v-else
                                        :edit='editAnswer'
                                        :itemList='possibleAnswers'
                                        buttonText='save'
                                        select='edit your answer'
                                        :selectedItem='answer.answer.trim()'
                                        :itemId='answer.id'
                                        :ownerId='profile.accountId'
                                        :ownerType='profile.account'
                                        @clickedListButton="clickedEditListButton"
                                        @listItemSelected="editListItemSelected"
                                    ></main-list>
                                </template>
                            </fade-right-fast>
                        </div>
                    </div>
                </div>
                <div class="right-wrapper" v-if="computedShouldMark">
                    <div class="marking" v-if="computedMarks">
                        <div class="correct"
                            @click="markAnswer('correct')"
                            :class="{checkActive:checkActive,grayed:isMarked}"
                        >
                            <font-awesome-icon
                                :icon="['fa','check']"
                            ></font-awesome-icon>
                        </div>
                        <div class="partial"
                            @click="markAnswer('partial')"
                            :class="{checkDoubleActive:checkDoubleActive,grayed:isMarked}"
                        >
                            <font-awesome-icon
                                :icon="['fa','check-double']"
                            ></font-awesome-icon>
                        </div>
                        <div class="wrong"
                            @click="markAnswer('wrong')"
                            :class="{timesActive:timesActive,grayed:isMarked}"
                        >
                            <font-awesome-icon
                                :icon="['fa','times']"
                            ></font-awesome-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="answer-single-profiles"
            v-if="!chat && showProfiles"
        >
            <span>
                {{showProfilesText}}
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

        <!-- for deleting answer -->
        <fade-up v-if="!chat">
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    :title="smallModalTitle"
                    :show="showSmallModal"
                    :loading="smallModalLoading"
                    :message="alertModalMessage"
                    :success="alertSuccess"
                    :danger="alertDanger"
                    :alerting="smallModalAlerting"
                    @disappear="showSmallModal = false"
                >
                    <template slot="actions">
                        <post-button
                            buttonText="ok"
                            @click="clickedInfoOk"
                            v-if="smallModalInfo"
                        ></post-button>
                        <post-button
                            buttonText="yes"
                            @click="clickedYes"
                            v-if="!smallModalInfo"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedNo"
                            v-if="!smallModalInfo"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture.vue'
import ProfileBar from './profile/ProfileBar.vue'
import MainTextarea from './MainTextarea.vue'
import AnswerMark from './AnswerMark.vue'
import PostButton from './PostButton.vue'
import FlagReason from './FlagReason.vue'
import MainList from './MainList.vue'
import FadeRightFast from './transitions/FadeRightFast.vue'
import FadeRight from './transitions/FadeRight.vue'
import AddComment from './AddComment.vue'
import OptionalActions from './OptionalActions.vue'
import RemarksBadge from './RemarksBadge.vue'
import FadeUp from './transitions/FadeUp.vue'
import { mapGetters, mapActions } from 'vuex'
import { dates, strings } from '../services/helpers'
import { useRoute, useRouter } from 'vue-router'

    export default {
        props: {
            answerFull: { //means it is the main answer on the post modal
                type: Boolean,
                default: false
            },
            chat: { //when in the chat section
                type: Boolean,
                default: false
            },
            disabled: { //when being view by admin
                type: Boolean,
                default: false
            },
            answer: {
                type: Object,
                default(){
                    return {}
                }
            },
            conversationId: {
                type: Number,
                default: null
            },
            possibleAnswers: {
                type: Array,
                default(){
                    return []
                }
            },
        },
        components: {
            FadeUp,
            AddComment,
            FadeRight,
            RemarksBadge,
            OptionalActions,
            FadeRightFast,
            MainList,
            FlagReason,
            PostButton,
            AnswerMark,
            MainTextarea,
            ProfileBar,
            ProfilePicture,
        },
        data() {
            return {
                showRemarks: false,
                profile: null,
                showAddComment: false,
                alertMessage: '',
                alertModalMessage: '',
                alertDanger: false,
                alertSuccess: false,
                commentsNumber: 0,
                showOptions: false,
                showProfiles: false,
                commentSuccess: false,
                commentFail: false,
                //
                showSmallModal: false,
                smallModalTitle: '',
                smallModalAction: '', //track whether we are flaging or deleting
                smallModalAlerting: false,
                smallModalLoading: false,
                smallModalInfo: '',
                justRemark: false,
                smallModalData: null, // this holds the who for the flag process
                //
                showProfilesText: '',
                showProfilesAction: '',
                // likes
                myLike: null,
                likes: 0,
                isLiked: false,
                likeTitle: '',
                showScore: false,
                score: 0,
                state: '',
                remark: '',
                //mark
                checkActive:false,
                checkDoubleActive:false,
                timesActive:false,
                myMark: null,
                isMarked: false,
                markTitle: '',
                //edit
                // showEdit: false,
                editAnswer: false,
                file: null,
                answerText: '',
                possibleAnswerId: null,
                loading: false,
                //flag
                showFlagReason: false,//it also pushes reaction section down to show flag reason
                flagReason: '',
                isFlagged: false,
                myFlag: null,
                flagTitle: '',
                //save
                isSaved: false,
                mySave: null,
                saves: 0,
            }
        },
        watch: {
            showOptions(newValue) {
                if (newValue) {
                    // setTimeout(() => {
                    //     this.showOptions = false
                    // }, 4000);
                }
            },
            isLiked(newValue){
                if (newValue) {
                    this.likeTitle = 'unlike this answer'
                } else {
                    this.likeTitle = 'like this answer'
                }
            },
            isFlagged(newValue){
                if (newValue) {
                    this.flagTitle = 'unflag this answer'
                } else {
                    this.flagTitle = 'flag this answer'
                }
            },
            likes(newValue){
                if (!newValue) {
                    this.myLike = null
                    this.isLiked = false
                }
            },
            saves(newValue){
                if (!newValue) {
                    this.mySave = null
                    this.isSaved = false
                }
            },
        },
        computed: {
            ...mapGetters(['getProfiles','getUser']),
            computedImageUrl() {
                return this.answer && this.answer.images && this.answer.images.length ? 
                    this.answer.images[0].url : ''
            },
            computedFlags(){ //check flagging
                if (this.getUser) {
                    if (this.answer && this.answer.hasOwnProperty('flags')){
                        let flags = this.answer.flags
                        let index = null
                        index = flags.findIndex(flag=>{
                                return flag.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.myFlag = flags[index]
                            this.isFlagged = true
                        }
                    }
                }
                return true
            },
            computedMarks(){ //determine if i have marked
                if (this.getUser) {
                    if (this.answer && this.answer.hasOwnProperty('marks')){
                        let marks = this.answer.marks
                        let index = null
                        index = marks.findIndex(mark=>{
                                return mark.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.myMark = marks[index]
                            this.isMarked = true
                        }
                    }
                }
                return true
            },
            computedSaves(){
                if (this.getUser) {
                    if (this.answer && this.answer.hasOwnProperty('saves')){
                        let saves = this.answer.saves
                        this.saves = this.answer.saves.length
                        let index = null
                        index = saves.findIndex(save=>{
                                return save.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.mySave = saves[index]
                            this.isSaved = true
                        }
                    }
                }
                return true
            },
            computedCommentNumber(){
                this.commentsNumber = this.answer && this.answer.comments_number ?
                    this.answer.comments_number : 0

                if (this.commentsNumber > 0) {
                    this.commentTitle = 'click to view comments'
                } else {
                    this.commentTitle = ''
                }
                if (this.answerFull) {
                    return false
                } else {
                    return true
                }
            },
            computedEdit(){
                if (this.chat) {
                    return this.computedOwner
                } else {
                    return this.computedProfiles.length
                }
            },
            computedVideoUrl() {
                return this.answer && this.answer.videos && this.answer.videos.length ? 
                    this.answer.videos[0].url : ''
            },
            computedAudioUrl() {
                return this.answer && this.answer.audios && this.answer.audios.length ? 
                    this.answer.audios[0].url : ''
            },
            computedCreatedAt(){
                return dates.createdAt(this.answer.created_at)
            },
            computedName(){
                return this.answer && this.answer.answeredby_name ? 
                    this.answer.answeredby_name : ''
            },
            computedType(){
                return this.answer && this.answer.answeredby_type ? 
                    strings.getAccount(this.answer.answeredby_type) : ''
            },
            computedAnswer(){
                return this.answer && this.answer.answer ? this.answer.answer : ''
            },
            computedAverageScore(){
                return this.answer && this.answer.avg_score ? 
                    Number(this.answer.avg_score).toFixed(2) : 0
            },
            computedMaximumScore(){
                return this.answer && this.answer.max_score ? 
                    this.answer.max_score.toFixed(2) : 0
            },
            computedMinimumScore(){
                return this.answer && this.answer.min_score ? 
                    this.answer.min_score.toFixed(2) : 0
            },
            computedScoreOver(){
                return this.answer && this.answer.scoreOver ? 
                    `${this.answer.scoreOver}` : 10
            },
            computedMarkings(){ //for the total marks issued
                return this.answer && this.answer.marks ? 
                    this.answer.marks.length : 0
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedLikes(){
                //do not show like if any of your profiles has liked the item
                if (this.getUser) {
                    if (this.answer && this.answer.hasOwnProperty('likes')){
                        let likes = this.answer.likes
                        this.likes = this.answer.likes.length
                        let index = null
                        index = likes.findIndex(like=>{
                                return like.user_id === this.getUser.id
                            })
                        if (index > -1) {
                            this.myLike = likes[index]
                            this.isLiked = true
                        }
                    }
                }
                return true
            },
            computedAnswerOwnerAccount(){
                let answerOwner = this.answer ? {
                    account: strings.getAccount(this.answer.answeredby_type),
                    accountId: `${this.answer.answeredby_id}`
                } : null

                return answerOwner
            },
            computedShouldMark(){
                if (!this.computedOwner) {
                    return this.chat && this.myMark && this.myMark.user_id ? false : true
                }
                return false
            },
            computedOwner(){
                let profiles = this.getProfiles
                let profile = null

                if (profiles) {
                    
                    profile =  profiles.findIndex(el=>{
                        return this.answer.answeredby_id === el.accountId && 
                            this.answer.answeredby_type === el.profile
                    })

                    if (profile > -1) {
                        this.profile = this.getProfiles[profile]
                        this.$emit('isAnswerOwner',this.profile) //user cannot answer a question more than once
                        return true
                    }
                }
                return false
            },
        },
        methods: {
            ...mapActions(['profile/createMark','profile/deleteLike','profile/createLike',
                'profile/updateAnswer','profile/deleteAnswer','profile/createFlag',
                'profile/deleteFlag','profile/deleteSave','profile/createSave']),
            clickedShowRemarks(){
                if (this.answer.marks.length) {
                    this.showRemarks = true
                }
            },
            clickedShowOptions(){
                if (this.disabled) {
                    return
                }
                this.showOptions = !this.showOptions
            },
            clickedCloseRemark(){
                this.showRemarks = false
            },
            getScore(data){
                if (this.state === 'PARTIAL') {
                    this.score = data.score
                } else if (this.state === 'WRONG') {
                    this.score = 0
                } else if (this.state === 'CORRECT') {
                    this.score = this.computedScoreOver
                }
                this.remark = data.remark
                this.showScore = false
                
                if (this.chat) {
                    this.$emit('markChatAnswer',{
                        answerId: this.answer.id,
                        questionId: this.answer.answerable_id,
                        score: this.score,
                        state: this.state,
                        remark: this.remark,
                        scoreOver: this.computedScoreOver
                    })
                    return
                }
                this.profilesAppear()
            },
            clickedProfilePicture(){
                const route = useRoute()
                const router = useRouter()

                if (route.name !== 'profile' &&
                    this.computedAnswerOwnerAccount) {
                    router.push({
                        name: 'profile',
                        params: {
                            account: this.computedAnswerOwnerAccount.account,
                            accountId: this.computedAnswerOwnerAccount.accountId,
                        }
                    })
                } else if (this.computedAnswerOwnerAccount) {
                    if (route.params.account !== this.computedAnswerOwnerAccount.account &&
                        route.params.accountId !== this.computedAnswerOwnerAccount.accountId) {
                        router.push({
                        name: 'profile',
                        params: {
                            account: this.computedAnswerOwnerAccount.account,
                            accountId: this.computedAnswerOwnerAccount.accountId,
                        }
                    })
                    }
                }
            },
            profilesAppear(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
            reasonGiven(data){
                this.showFlagReason = false
                this.flagReason = data
                this.profilesAppear()
            },
            continueFlagProcess(){
                this.flagReason = null
                this.showFlagReason = false
                this.profilesAppear()
            },
            cancelFlagProcess(){
                this.flagReason = ''
                this.showFlagReason = false
            },
            async updateAnswer(who){
                let formData = new FormData

                if (this.file) {
                    formData.append('file', this.file)
                }

                if (this.answerText.length) {
                    formData.append('answer', this.answerText.trim())
                }

                if (this.possibleAnswerId) {
                    formData.append('possible_answer_id', this.possibleAnswerId)
                }

                formData.append('account', who.account)
                formData.append('accountId', who.accountId)                
                
                let response = null
                
                response = await this['profile/updateAnswer']({answerId: who.itemid,formData})
                
                if (response.status) {
                    this.file = null
                    this.alertSuccess = true
                    this.alertDanger = false
                    this.answerText = ''
                    this.editAnswer = false

                    this.$emit('updateAnswerSuccessful',{
                        answer: response.answer,
                        main: this.answerFull
                    })
                    
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                }
            },
            addAnswer(addAnswerData){
                this.file = addAnswerData.file
                this.answerText = addAnswerData.input
                this.possibleAnswerId = null
                this.updateAnswer(addAnswerData.who)
            },
            clickedEditListButton(who){
                this.updateAnswer(who)
            },
            editListItemSelected(item){
                this.answerText = item.option
                this.possibleAnswerId = item.id
            },
            async clickedLike(){
                if (this.disabled) {
                    return
                }
                if (!this.getUser) {
                    this.$emit('askLoginRegister','PostSingle')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.$emit('askCreateAccount')
                } else {
                    if (this.isLiked) {
                        this.likes -= 1
                        this.isLiked = false
                        
                        if (this.myLike && this.myLike.hasOwnProperty('id')) {
                            let newData = {
                                likeId: this.myLike.id,
                                item: 'answer',
                                itemId: this.answer.id,
                            }

                            newData.where = useRoute().name
                            let response = await this['profile/deleteLike'](newData)
                            if (response === 'unsuccessful') {
                                this.isLiked = true
                                this.likes += 1
                            } else {
                                newData.main = this.answerFull
                                this.$emit('answerUnlikeSuccessful',newData)
                            }
                        } else {
                            this.likes += 1
                            this.isLiked = true
                        }
                    } else {
                        console.log('its is not liked', this.isLiked);
                        this.showProfilesText = 'like as'
                        this.showProfilesAction = 'like'
                        this.showProfiles = true
                        setTimeout(() => {
                            this.showProfiles = false
                        }, 4000);
                    }
                }
            },
            clickedNo(){
                this.clearSmallModal()
            },
            async clickedYes(){
                if (this.smallModalAction === 'flag') {
                    this.flag(this.smallModalData)
                    return
                }
                this.smallModalLoading = true
                let data = {
                    answerId: this.answer.id,
                }
                let response = await this['profile/deleteAnswer'](data)
                
                this.smallModalLoading = false
                this.smallModalAlerting = true
                if (response.status) {
                    this.alertSuccess = true
                    this.alertDanger = false
                    this.alertModalMessage = 'deletion successful'
                    this.$emit('deleteAnswerSuccess', {
                        answerId: data.answerId,
                        main: this.answerFull
                    })
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                    this.alertModalMessage = 'deletion unsuccessful'
                }
                setTimeout(() => {
                    this.clearSmallModal()
                }, 3000);
            },
            clickedOption(data){
                this.showOptions = false
                if (!this.getUser) {
                    this.$emit('askLoginRegister','PostSingle')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.$emit('askCreateAccount')
                } else if (data === 'save') {
                    if (this.isSaved) {
                        this.save(null)
                        return
                    }
                    this.showProfilesText = 'save as'
                    this.showProfilesAction = 'save'
                    this.profilesAppear()
                } else if (data === 'delete') {
                    if (this.chat) {
                        this.$emit('clickedOption',{
                            action: 'delete',
                            type: 'answer',
                            answer: this.answer,
                            conversationId: this.conversationId
                        })
                        return
                    }
                    this.smallModalTitle = 'are you sure you want to delete this?'
                    this.showSmallModal = true
                    this.smallModalAction = 'delete'
                    setTimeout(() => {
                        this.showSmallModal =  false
                        this.smallModalTitle = ''
                    }, 4000);
                } else if (data === 'edit') {
                    this.editAnswer = true
                }
            },
            clickedAddComment(){
                if (this.disabled) {
                    return
                }
                if (!this.getUser) {
                    this.$emit('askLoginRegister','PostSingle')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.$emit('askCreateAccount')
                } else {
                    this.showAddComment = true
                }
            },
            clickedFlag(){
                if (this.disabled) {
                    return
                }
                if (this.isFlagged) {
                    this.flag(null)
                    return
                }
                this.showProfilesText = 'flag as'
                this.showProfilesAction = 'flag'
                if (!this.getUser) {
                    this.$emit('askLoginRegister','PostSingle')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.$emit('askCreateAccount')
                } else {
                    this.showFlagReason = true
                }
            },
            clickedShowAnswerSingle(){
                if (this.answerFull) {
                    return
                }
                this.$emit('clickedShowAnswerComments',{
                    data: {
                        type: this.answer,
                        typeName: 'answer'
                    },
                    type: 'answer'
                })
            },
            postAddComplete(data){
                if (data === 'successful') {
                    this.showAddComment = false
                    this.commentSuccess = true
                    setTimeout(() => {
                        this.commentSuccess = false
                    }, 2000);
                } else {
                    this.commentFail = true
                    setTimeout(() => {
                        this.commentFail = false
                    }, 2000);
                }
            },
            postModalCommentCreated(data){
                this.$emit('answerCommentSuccessful',data)
            },
            clearSmallModal(){
                this.smallModalInfo = false
                this.smallModalTitle = ''
                this.showSmallModal = false
                this.alertSuccess = false
                this.alertDanger = false
                this.smallModalAlerting = false
                this.smallModalAction = ''
            },
            markAnswer(data) {
                if (this.disabled) {
                    return
                }
                if (this.isMarked) {
                    return
                }
                if (this.getUser) {
                    this.checkActive = false
                    this.checkDoubleActive = false
                    this.timesActive = false
                    if (this.getProfiles) {
                        if (this.isMarked) {
                            this.smallModalInfo = true
                            this.smallModalTitle = 'you have alreadey marked this with an account'
                            this.showSmallModal = true
                            setTimeout(() => {
                                this.clearSmallModal()
                            }, 3000);
                            return
                        }
                        this.showProfilesText = 'mark as'
                        this.showProfilesAction = 'mark'
                        if (data === 'correct') {
                            this.checkActive = false
                            this.state = 'CORRECT'
                            this.score = this.computedScoreOver
                            this.justRemark = true
                        } else if (data === 'partial') {
                            this.checkDoubleActive = false
                            this.justRemark = false
                            this.state = 'PARTIAL'
                        } else if (data === 'wrong') {
                            this.timesActive = false
                            this.state = "WRONG"
                            this.justRemark = true
                            this.score = 0
                        }
                        this.showScore = true
                    } else {
                        this.$emit('askCreateAccount')
                    }
                } else {
                    this.$emit('askLoginRegister')
                }
            },
            clickedViewComments(){
                if (!this.commentsNumber) {
                    return 
                }
                this.$emit('clickedShowAnswerComments', {
                    data: {
                        type: this.answer,
                        typeName: 'answer'
                    },
                    type: 'answer'
                })
            },
            clickedProfile(who){
                this.showProfiles = false
                if (this.showProfilesAction === 'mark') {
                    this.mark(who)
                } else if (this.showProfilesAction === 'save') {
                    this.save(who)
                } else if (this.showProfilesAction === 'flag') {
                    this.smallModalTitle = 'are you sure you want to flag this?'
                    this.smallModalAction = 'flag'
                    this.showSmallModal = true
                    this.smallModalData = who
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                } else if (this.showProfilesAction === 'like') {
                    this.like(who)
                }
            },
            async save(who){
                this.showProfiles = false
                this.loading = true
                let data = {
                    item: 'answer',
                    itemId: this.answer.id,
                    owner: this.answer.answeredby_type,
                    ownerId: this.answer.answeredby_id,
                },
                    response = null,
                    state = ''

                data.where = useRoute().name
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    state = 'saving'
                    response = await this['profile/createSave'](data)
                } else {
                    data.saveId = this.mySave.id
                    state = 'unsaving'
                    response = await this['profile/deleteSave'](data)
                }

                this.loading = false
                if (response.status) {
                    if (who) {
                        this.saves += 1
                        this.$emit('answerSaveSuccessful',{ //emit to post modal
                            itemId: this.answer.id,
                            save: response.save,
                            main : this.answerFull
                        })
                    } else {
                        this.saves -= 1
                        this.$emit('answerUnsaveSuccessful',{ //emit to post modal
                            itemId: this.answer.id,
                            saveId: data.saveId,
                            main : this.answerFull
                        })
                    }
                    this.isSaved = !this.isSaved
                    this.alertSuccess = true
                    this.alertMessage = `${state} successful`
                } else {
                    this.alertDanger = true
                    this.alertMessage = `${state} unsuccessful`
                }
                setTimeout(() => {
                    this.alertSuccess = false
                    this.alertDanger = false
                    this.alertMessage = ''
                }, 3000);
            },
            async like(who){
                this.isLiked = true
                this.likes += 1
                let data = {
                    item: 'answer',
                    itemId: this.answer.id,
                    account: who.account,
                    accountId: who.accountId,
                }

                data.where = useRoute().name
                let response = await this['profile/createLike'](data)

                if (response === 'unsuccessful') {
                    this.isLiked = false
                    this.likes -= 1
                } else {
                    this.$emit('answerLikeSuccessful',{
                        itemId: this.answer.id,
                        like: response,
                        main : this.answerFull
                    })
                }
            },
            async flag(who){
                this.loading = true
                let data = {}
                data.where = useRoute().name
                let response = null
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    data.item = 'answer'
                    data.itemId = this.answer.id
                    data.reason = this.flagReason

                    response = await this['profile/createFlag'](data)
                } else {
                    data.flagId = this.myFlag.id

                    response = await this['profile/deleteFlag'](data)
                }

                this.loading =false
                if (response.status) {
                    this.alertSuccess = true
                    if (this.isFlagged) {
                        this.isFlagged = false
                        this.$emit('answerUnflaggedSuccess', {
                            flag: response.flag,
                            answerId: this.answer.id
                        })
                    } else {
                        this.alertModalMessage = 'successfully flagged'
                        this.$emit('deleteAnswerSuccess', {
                            answerId: this.answer.id,
                            main: this.answerFull
                        })
                    }
                    this.smallModalAlerting = true
                } else {
                    this.alertDanger = true
                    this.alertModalMessage = 'flagging successful'
                }
                this.flagReason = ''
                this.smallModalData = null
                setTimeout(() => {
                    this.clearSmallModal()
                }, 3000);
            },
            async mark(who){
                let data = {}
                this.loading = true

                data.account = who.account
                data.accountId = who.accountId
                data.item = 'answer'
                data.itemId = this.answer.id
                data.remark = this.remark
                data.state = this.state
                data.score = this.score
                data.scoreOver = this.computedScoreOver

                let response = await this['profile/createMark'](data)

                this.loading = false
                if (response.status) {
                    this.$emit('answerMarkedSuccessful', {
                        mark: response.data.mark,
                        answerId: data.itemId,
                        avg_score: response.data.avg_score,
                        max_score: response.data.max_score,
                        min_score: response.data.min_score,
                        main: this.answerFull
                    })
                } else {
                    this.alertDanger = true
                    this.alertMessage = response.message
                    setTimeout(() => {
                        this.alertMessage = ''
                        this.alertDanger = false
                    }, 3000);
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
$profile-picture-width: 50px;
@mixin text-overflow(){
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

    .answer-single-wrapper{
        width: 100%;
        box-shadow: 0 0 1px gray;
        border-radius: 0 10px 10px 0;
        border-left: 2px solid gray;
        position: relative;
        margin: 0 0 20px;
        font-size: 14px;

        .loading{
            width: 100%;
            text-align: center;
        }

        .score{
            position: absolute;
            top: 5px;
            right: 0;
            width: 300px;
            max-width: 100%;
        }

        .edit{
            font-size: 16px;
            margin-top: -10px;
            cursor: pointer;
            text-align: end;
            z-index: 3;
        }

        .options{
            font-size: 14px;
            margin: 5px;
            background-color: whitesmoke;
            width: 75px;
            position: absolute;
            right: 0;
            top: 15px;
            z-index: 3;

            span{
                padding: 5px;
                cursor: pointer;
                display: block;
                width: 100%;
                text-align: center;

                &:hover{
                    box-shadow: 0 0 3px dimgray;
                }
            }
        }

        .main-info{
            width: 100%;
            padding: 5px;
            border-bottom: 1px solid gray;
            display: inline-flex;

            .name{
                font-size: 12px;
                font-weight: 500;
                width: 65%;
                text-align: start;
                @include text-overflow();
                text-transform: capitalize;
            }

            .type{
                font-size: 10px;
                width: 35%;
                text-align: end;
                @include text-overflow();
                text-transform: capitalize;
            }
        }

        .alert-message{
            width: 100%;
            text-align: center;
            padding: 5px;
            font-size: 12px;
            margin: 5px 0;
        }

        .danger{
            background-color: rgba(255, 0, 0, 0.349);
            color: red;
        }

        .main-area{
            width: 100%;
            display: table;
            position: relative;

            .left-wrapper{
                width: 85%;
                display: table-cell;
                padding-right: 5px;
                padding-left: 5px;

                .top-section{
                    display: flex;
                    justify-content: space-between;
                    align-content: flex-start;
                    margin-bottom: 5px;
                    
                    .profile-picture{
                        width: $profile-picture-width;
                        height: $profile-picture-width;
                    }

                    .other-info{
                        max-width: 50%;
                        text-align: end;
                        font-size: 8px;
                        margin-left: auto;
                        cursor: pointer;

                        .info{
                            border-bottom: 1px solid gray;
                        }
                    }
                }

                .bottom-section{
                    display: table;
                    width: 100%;

                    .textarea{
                        width: 70%;
                        display: table-cell;
                    }

                    .media{
                        width: 30%;
                        display: table-cell;
                    }
                }

                .lower-section{

                    .extra-info{
                        width: 100%;

                        .info{
                            font-size: 11px;
                            color: gray;
                        }

                        .reaction-section{
                            display: inline-flex;
                            justify-content: space-between;
                            align-items: center;
                            width: 100%;
                            font-size: 14px;
                            position: relative;

                            .comment-number{
                                font-size: 12px;
                                padding: 5px;
                                cursor: pointer;
                                background-color: whitesmoke;
                                border-radius: 10%;
                            }

                            .comment,
                            .flag{
                                cursor: pointer;
                                padding: 5px;
                            }

                            .flagged{
                                color: red;
                            }

                            .reason{
                                position: absolute;
                                top: 30px;
                                width: 100%;
                            }

                            .like-section{
                                display: inline-flex;

                                .like-number{
                                    font-size: 12px;
                                    padding: 5px;
                                    background-color: whitesmoke;
                                    border-radius: 10%;
                                }

                                .like{
                                    cursor: pointer;
                                    padding: 5px;
                                }
                            }

                            .liked{
                                color: green;
                            }

                            .success{
                                color: green;
                                box-shadow: 0 0 3px gray;
                            }

                            .fail{
                                color: red;
                                box-shadow: 0 0 3px gray;
                            }
                        }

                        .reactionReason{
                            margin-bottom: 80px;
                            transition: all .4s ease;
                            z-index: 5;
                        }
                    }

                    .add-comment{
                        margin-bottom: 20px;
                        transition: all .5s ease;
                    }
                }
            }

            .right-wrapper{
                width: 15%;
                padding: 0 10px;
                border-left: 1px solid gray;
                position: relative;
                top: 0;

                .marking{
                    position: relative;
                }

                .correct,
                .partial,
                .wrong{
                    font-size: 14px;
                    padding: 5px;
                    margin: 5px auto;
                    cursor: pointer;
                    text-align: center;

                    // &:hover{
                    //     box-shadow: 0 0 2px;
                    //     transition: all .5s ease;
                    // }
                }

                // .checkActive,
                // .checkDoubleActive,
                // .timesActive{
                //     box-shadow: 0 0 2px;
                //     transition: all .5s ease;
                // }

                .timesActive{
                    color: red;
                }

                .checkActive{
                    color: green;
                }

                .checkDoubleActive{
                    color: gold;
                }

                .grayed{
                    color: rgba(128, 128, 128, 0.849);
                }
            }
        }

        .profiles{
            font-size: 14px;
            position: absolute;
            width: 200px;
            left: 0;
            top: 95%;
            z-index: 1000;
            text-align: start;
        }

    }

    .answerFull{
        width: 85%;
        position: relative;
        margin: 10px auto 20px;

        .main-info{

            .name{
                font-size: 16px;
            }

            .type{
                font-size: 14px;
            }
        }

        .main-area{
            padding: 10px;

            .left-wrapper{
                padding: 10px;

                .top-section{
                    margin-bottom: 5px;
                    
                    .profile-picture{
                        $profile-picture-width: 70px;
                        width: $profile-picture-width;
                        height: $profile-picture-width;
                    }

                    .other-info{
                        font-size: 11px;
                    }
                }
            }

            .right-wrapper{

                .correct,
                .partial,
                .wrong{
                    font-size: 16px;
                }
            }
        }
    }
</style>