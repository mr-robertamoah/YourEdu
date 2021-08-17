<template>
    <div v-if="answer" class="relative bg-white rounded mb-1">
        
        <question-badge
            :question="answer.question"
            :close="false"
            :backgroundClass="'bg-none'"
            class="mx-0 mb-1"
        ></question-badge>
        
        <answer-badge
            :answer="answer"
            :mark="mark"
            @clickedButton="clickedMarkButton"
        ></answer-badge>
        
        <answer-mark
            class="absolute right-0 top-0"
            :show="showMarkForm"
            :inputMax="answer.question.scoreOver"
            :inputMin="0"
            :value="mark"
            :scoreOver="computedScoreOver"
            @hideAnswerMark="showMarkForm = false"
            @answerMarkScore="getScore"
        ></answer-mark>
    </div>
</template>

<script>
import AnswerMark from '../AnswerMark.vue'
import AnswerBadge from './AnswerBadge.vue'
import QuestionBadge from './QuestionBadge.vue'
    export default {
        components: {
            AnswerMark,
            QuestionBadge,
            AnswerBadge,
        },
        props: {
            answer: {
                type: Object,
                default() {
                    return null
                }
            },
            providedMark: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                mark: {
                    score: null,
                    remark: '',
                    answerId: null
                },
                showMarkForm: false,
                loading: false,
                mode: ''
            }
        },
        watch: {
            providedMark: {
                immediate: true,
                handler(newValue) {

                    this.initiateMark()
                },
                deep: true
            }
        },
        computed: {
            computedScoreOver() {
                return String(this.answer.question.scoreOver) ?? '100'
            },
        },
        methods: {
            getScore(data) {
                this.showMarkForm = false

                if (this.mode === 'update') {
                    this.performActionOnMark({
                        type: this.mode,
                        newMarkData: data,
                        mark: this.answer.mark
                    })    
                    return
                }

                this.updateMark({
                    score: data.score,
                    remark: data.remark,
                })

                this.emitMark()
            },
            updateMark({score, remark}) {
                this.mark.score = score
                this.mark.remark = remark ?? ''
                this.mark.answerId = this.answer.id
            },
            emitMark() {
                this.$emit('marked', this.mark)
            },
            initiateMark() {
                if (! this.providedMark) {
                    this.clearMark()
                    return
                }

                this.updateMark({
                    score: this.providedMark.score,
                    remark: this.providedMark.remark,
                })
            },
            clearMark() {
                this.mark.score = null
                this.mark.remark = ''
                this.mark.answerId = null
            },
            clickedMarkButton(data) {

                this.mode = ''
                let score = 0

                if (data.type === 'delete') {
                    this.performActionOnMark({type: data.type, mark: data.answer.mark})
                    return
                }
                
                if (data.type === 'update') {
                    this.mode = 'update'
                    score = this.answer.mark.score
                }

                if (data.type === 'correct') {
                    score = this.answer.question.scoreOver
                }

                if (data.type === 'partial') {
                    score = null
                }

                this.updateMark({score})
                this.showMarkForm = true
            },
            performActionOnMark({type, mark, newMarkData}) {
                this.$emit('performActionOnMark', {type, mark, newMarkData})
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>