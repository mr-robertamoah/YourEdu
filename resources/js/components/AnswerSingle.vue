<template>
    <div class="answer-single-wrapper"
        @dblclick="clickedShowPostPreview"
        :class="{typeFull:answerFull}"
    >
        <div class="edit"
            @click="showOptions = computedOwner"
            v-if="computedOwner"
        >
            <font-awesome-icon
                :icon="['fa','chevron-down']"
            ></font-awesome-icon>
        </div>
        <div class="options" v-if="showOptions">
            <span @click="clickedOpion('edit')">edit</span>
            <span @click="clickedOpion('delete')">delete</span>
        </div>
        <div class="main-info">
            <div class="name">{{computedName}}</div>
            <div class="type">{{computedType}}</div>
        </div>
        <div class="alert-message" 
            v-if="alertMessage.length"
            :class="{danger:alertDanger}"
        >
            {{alertMessage}}
        </div>
        <div class="main-area">
            <div class="left-wrapper">
                <div class="top-section">
                    <div class="profile-picture">
                        <profile-picture>
                            <template slot="image">
                                <img :src="answer.url" alt="profile">
                            </template>
                        </profile-picture>
                    </div>
                    <div class="other-info">
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
                </div>
                <div class="bottom-section">
                    <div class="textarea">
                        <main-textarea
                            :value="computedAnswer"
                            :disabled="true"
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
                    <div class="profiles"
                        v-if="showProfiles"
                    >
                        <span>
                            answer as
                        </span>
                        <div :key="key" v-for="(profile,key) in computedProfiles">
                            <profile-bar
                                :name="profile.name"
                                :type="profile.params.account"
                                :smallType="true"
                                :routeParams="profile.params"
                                :navigate="false"
                                @clickedProfile="clickedProfile"
                            ></profile-bar>
                        </div>
                    </div>
                </div>
                <div class="lower-section">
                    <div class="extra-info">
                        <div class="info">
                            {{`${computedMarkings} number of people have marked this answer`}}
                        </div>
                        <div class="comment-section">
                            <div
                                @click="clickedFlag"
                            >
                                <font-awesome-icon
                                    :icon="['fa','flag']"
                                ></font-awesome-icon>
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
                            <div class="comment"
                                title="add a comment"
                                @click="clickedAddComment"
                                v-if="!showAddComment"
                            >
                                <font-awesome-icon
                                    :icon="['fa','comment']"
                                ></font-awesome-icon>
                            </div>
                        </div>
                    </div>
                    <div class="add-comment">
                        <fade-right-fast>
                            <template slot="transition" v-if="showAddComment">
                                <add-comment
                                    what="answer"
                                    :id="answer.id"
                                    :showAddComment="showAddComment"
                                    @hideAddComment="showAddComment = false"
                                    @postModalCommentCreated="postModalCommentCreated"
                                    :onPostModal="true"
                                ></add-comment>
                            </template>
                        </fade-right-fast>
                    </div>
                </div>
            </div>
            <div class="right-wrapper">
                <div class="score" v-if="showScore">
                    <answer-score
                        :hideAnswerMark="showScore = false"
                        :answerMarkScore="getScore"
                    ></answer-score>
                </div>
                <div class="marking" v-if="!computedOwner">
                    <div class="correct"
                        @click="markAnswer('correct')"
                    >correct</div>
                    <div class="partial"
                        @click="markAnswer('partial')"
                    >partial</div>
                    <div class="wrong"
                        @click="markAnswer('wrong')"
                    >wrong</div>
                </div>
            </div>
        </div>

        <!-- for deleting answer -->
        <fade-up>
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
                            buttonText="yes"
                            @click="clickedYes"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedNo"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture'
import MainTextarea from './MainTextarea'
import AnswerMark from './AnswerMark'
import PostButton from './PostButton'
import FadeRightFast from './transitions/FadeRightFast'
import AddComment from './AddComment'
import FadeUp from './transitions/FadeUp'
import { mapGetters, mapActions } from 'vuex'
import { dates, strings } from '../services/helpers'

    export default {
        props: {
            answerFull: { //means it is the main answer on the post modal
                type: Boolean,
                default: false
            },
            answer: {
                type: Object,
                default(){
                    return {}
                }
            },
        },
        components: {
            FadeUp,
            AddComment,
            FadeRightFast,
            PostButton,
            AnswerMark,
            MainTextarea,
            ProfilePicture,
        },
        data() {
            return {
                showScore: false,
                score: 0,
                showAddComment: false,
                alertMessage: '',
                alertModalMessage: '',
                alertDanger: false,
                alertSuccess: false,
                commentsNumber: 0,
                showOptions: false,
                showProfiles: false,
                //
                showSmallModal: false,
                smallModalTitle: '',
                smallModalAction: '', //track whether we are flaging or deleting
                smallModalAlerting: false,
            }
        },
        computed: {
            ...mapGetters(['getProfiles','getUser']),
            computedImageUrl() {
                return this.answer && this.answer.images ? this.answer.images[0].url : ''
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
            computedVideoUrl() {
                return this.answer && this.answer.videos ? this.answer.videos[0].url : ''
            },
            computedAudioUrl() {
                return this.answer && this.answer.audios ? this.answer.audios[0].url : ''
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
                    this.answer.avg_score : 0
            },
            computedMaximumScore(){
                return this.answer && this.answer.max_score ? 
                    this.answer.max_score : 0
            },
            computedMinimumScore(){
                return this.answer && this.answer.min_score ? 
                    this.answer.min_score : 0
            },
            computedMarkings(){
                return this.answer && this.answer.marks_number ? 
                    this.answer.marks_number : 0
            },
            computedOwner(){
                let profiles = this.getProfiles
                let profile = null

                if (profiles) {
                    
                    profile =  profiles.findIndex(el=>{
                        return this.answer.answeredby_id === el.params.accountId && 
                            this.answer.answeredby_type === el.profile
                    })

                    if (profile > -1) {
                        this.profile = this.getProfiles[profile]
                        return true
                    } else {
                        this.profile = null
                    }
                }
                return false
            },
        },
        methods: {
            ...mapActions(['profile/answerMark']),
            getScore(data){
                this.score = data
            },
            clickedNo(){
                this.showSmallModal = false
            },
            async clickedYes(){
                this.smallModalLoading = true
                let data = {
                    answerId: this.answer.id,
                }
                let response = await this['profile/deleteAnswer'](data)
                
                this.smallModalLoading = false
                this.smallModalAlerting = true
                if (response !== 'unsuccessful') {
                    this.alertSuccess = true
                    this.alertDanger = false
                    if (this.smallModalAction === 'delete') {
                        this.$emit('answerDeleteSuccess', {response,main: this.answerFull})
                        this.alertModalMessage = 'deletion successful'
                    } else if (this.smallModalAction === 'flag') {
                        this.$emit('answerFlagSuccess', {response,main: this.answerFull})
                        this.alertModalMessage = 'deletion successful'
                    }
                } else {
                    this.alertSuccess = false
                    this.alertDanger = true
                    this.alertModalMessage = 'deletion unsuccessful'
                }
                setTimeout(() => {
                    this.smallModalAlerting = false
                    this.alertModalMessage = ''
                    this.alertDanger = false
                    this.alertSuccess = false
                }, 3000);
            },
            clickedOpion(data){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','postShow')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.$emit('askCreateAccount')
                } else if (data === 'delete') {
                    this.smallModalTitle = 'are you sure you want to delete this?'
                    this.showSmallModal = true
                    this.smallModalAction = 'delete'
                    setTimeout(() => {
                        this.showSmallModal =  false
                        this.smallModalTitle = ''
                    }, 4000);
                } else if (data === 'edit') {

                }
            },
            clickedAddComment(){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','postShow')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.$emit('askCreateAccount')
                } else {
                    this.showAddComment = true
                }
            },
            clickedFlag(){
                if (!this.getUser) {
                    this.$emit('askLoginRegister','postShow')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.$emit('askCreateAccount')
                } else {
                    this.smallModalTitle = 'are you sure you want to flag this?'
                    this.showAddComment = true
                }
            },
            clickedShowPostPreview(){
                if (this.answerFull) {
                    return
                }
                this.$emit('clickedShowAnswer',{
                    data: {
                        type: this.answer,
                        typeName: 'answer',
                    }, 
                })
            },
            postModalCommentCreated(data){
                this.commentsNumber += 1
            },
            markAnswer(data) {
                if (this.getUser) {
                    if (this.getProfiles) {
                        if (data === 'correct') {
                            
                        } else if (data === 'partial') {
                            this.showScore = true 
                        } else if (data === 'wrong') {
                            
                        }
                        this.showProfiles = true
                        setTimeout(() => {
                            this.showProfiles = false
                        }, 4000);
                    } else {
                        this.$emit('askCreateAccount')
                    }
                } else {
                    this.$emit('askLoginRegister')
                }
            },
            clickedViewComments(){
                this.$emit('clickedShowAnswerComments', {
                    answer: this.answer,
                    type: 'answer'
                })
            },
            clickedProfile(who){
                this.showProfiles = false
                this.mark(who)
            },
            async mark(who){
                let data = {}
                this.loading = true

                data.account = who.account
                data.accountId = who.accountId

                let response = await this['answerMark']()

                this.loading = false
                if (response !== 'unsuccessful') {
                    this.$emit('answerMarked', response.answer)
                } else {
                    this.alertDanger = true
                    this.alertMessage = 'marking of answer failed'
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
$profile-picture-width: 70px;
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

        .edit{
            font-size: 16px;
            margin-top: -10px;
            cursor: pointer;
            text-align: end;
        }

        .options{
            font-size: 14px;
            margin: 5px;
            background-color: whitesmoke;
            width: 75px;
            position: absolute;
            right: 0;
            top: 15px;

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

            .name{
                font-size: 16px;
                font-weight: 500;
                width: 65%;
                text-align: start;
                @include text-overflow();
                text-transform: capitalize;
            }

            .type{
                font-size: 14px;
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
            font-size: 14px;
            margin: 5px 0;
        }

        .danger{
            background-color: rgba(255, 0, 0, 0.349);
            color: red;
        }

        .main-area{
            width: 100%;
            display: table;
            padding: 10px;

            .left-wrapper{
                width: 85%;
                display: table-cell;
                padding-right: 5px;
                padding: 10px;

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

                        .info{
                            font-size: 14px;
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

                    .profiles{
                        position: absolute;
                        width: 200px;
                        left: 0;
                        top: 95%;
                        z-index: 1000;
                        text-align: start;
                    }
                }

                .lower-section{

                    .extra-info{
                        width: 100%;

                        .comment-section{
                            display: inline-flex;
                            justify-content: space-between;
                            align-items: center;
                            width: 100%;

                            .comment-number{
                                font-size: 12px;
                                padding: 5px;
                                cursor: pointer;
                                background-color: whitesmoke;
                                border-radius: 10%;
                            }

                            .comment{
                                cursor: pointer;
                                font-size: 16px;
                            }
                        }
                    }
                }
            }

            .right-wrapper{
                width: 10%;
                display: table-cell;
                padding: 10px;
                border-left: 1px solid gray;

                .correct,
                .partial,
                .wrong{
                    margin-bottom: 10px;
                }
            }
        }

    }
</style>