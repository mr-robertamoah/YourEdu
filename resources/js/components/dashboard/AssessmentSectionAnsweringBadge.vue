<template>
    <div 
        class="assessment-section-answering-badge"
        :class="{'flex justify-center items-center h-full w-full': !computedTimingShow}"
    >
        <template v-if="computedTimingShow">
            <div 
                class="flex p-0.5 w-content justify-end pr-1 text-sm"
                :class="[timingItemHasFewTimeLeft ? 'bg-red-700 text-gray-200' : 'text-gray-500']"
                v-if="timingItemTimeLeft"
            >
                <div>{{timingItemTimeLeft}}</div>
            </div>

            <template v-if="assessmentSection">

                <assessment-section-information-badge
                    :assessmentSection="assessmentSection"
                ></assessment-section-information-badge>

                <div class="h-full max-h-3/4 flex-shrink mb-2 overflow-y-auto p-2">
                    <question-answering-badge
                        v-for="(question, index) in assessmentSection.questions"
                        :question="question"
                        :key="index"
                        @answered="answered"
                        :answer="getProvidedAnswer(question)"
                        :questionAnswer="getQuestionAnswer(question)"
                    ></question-answering-badge>
                </div>
            </template>
            
            <div v-if="!assessmentSection"
                class="text-gray-500 font-semibold text-sm px-2"
            >
                {{`sorry 😕, no section yet`}}
            </div>
        </template>
        <div v-if="timingItemLocked"
            class="text-gray-500 font-semibold text-sm px-2"
        >
            {{`sorry 😕, you have no time left for answering this assessment section with name: ${assessmentSection.name}`}}
        </div>
        <div v-if="timingItemWait"
            class="p-2 rounded-sm text-gray-500 font-semibold text-sm bg-yellow-400"
        >
            ✋ wait for a while...
        </div>
        
        <pop-up
            :show="showPopUp"
            :responses="['continue', 'cancel']"
            default="continue"
            message="this assessment section has a duration. once you click continue, a timer will start and you will have to finish before the timer completes"
            @clickedResponse="clickedPopupResponse"
            @closePopUp="closePopUp"
        ></pop-up>
    </div>
</template>

<script>
import QuestionAnsweringBadge from './QuestionAnsweringBadge.vue';
import Timing from '../../mixins/Timing.mixin';
import PopUp from '../../mixins/PopUp.mixin';
import AssessmentSectionInformationBadge from './AssessmentSectionInformationBadge.vue';
    export default {
        components: {
            QuestionAnsweringBadge,
            AssessmentSectionInformationBadge,
        },
        props: {
            assessmentSection: {
                type: Object,
                default() {
                    return null
                }
            },
            computedAccount: {
                type: Object,
                default() {
                    return null
                }
            },
            assessmentId: {
                default: null
            },
            answers: {
                type: Array,
                default() {
                    return []
                }
            },
            sectionAnswers: {
                type: Array,
                default() {
                    return []
                }
            },
        },
        mixins: [Timing, PopUp],
        watch: {
            assessmentSection: {
                immediate: true,
                handler(newValue, oldValue) {
                    this.initiate()
                }
            }
        },
        computed: {
            computedItem() {
                return {
                    itemId: this.assessmentSection.id,
                    item: 'assessmentSection'
                }
            },
            computedItemable() {
                return this.assessmentSection ? {
                    ...this.assessmentSection,
                    assessmentId: this.assessmentId
                } : {}
            },
        },
        methods: {
            answered(data) {
                data.assessmentSectionId = this.assessmentSection.id
                this.$emit('answered', data)
            },
            initiate() {
                if (this.assessmentSection.initiated) {
                    return
                }

                if (!this.assessmentSection.duration) {
                    this.timingItemWait = false
                    return
                }

                this.timingItemWait = true

                if (this.assessmentSection.timer) {
                    this.startTimer()
                    return
                }

                this.showPopUp = true

                this.$emit('initiated', this.assessmentSection)
            },
            clickedPopupResponse(data) {
                if (data === 'continue') {
                    this.startTimer()
                }

                if (data === 'cancel') {
                    
                }

                this.showPopUp = false
            },
            getQuestionAnswer(question) {
                return this.sectionAnswers.find(answer=>{
                    return answer.question.id === question.id
                })
            },
            getProvidedAnswer(question) {
                return this.answers.find(answer=>{
                    return answer.questionId == question.id &&
                        answer.assessmentSectionId == this.assessmentSection.id
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

    .assessment-section-answering-badge{

        .main-section{
            width: 100%;
            background: aquamarine;
            color: gray;
            font-size: 14px;
            padding: 5px;
            margin: 0 0 10px;
            text-transform: capitalize;
        }

        .instruction{
            background: white;
            padding: 5px;
            text-align: center;
        }
    }
</style>