<template>
    <div>
        <div v-if="! question">sorry 😞, there is no question</div>
        <template v-if="question">
            <div class="mb-2 text-gray-600 font-semibold">{{question.body}}</div>
            <main-checkbox
                v-if="question.hint && question.length"
                :label="'show hint'"
                v-model="showHint"
            ></main-checkbox>
            <div 
                class="text-sm text-gray-500 w-11/12 mt-1 mx-auto"
                v-if="showHint"
            >{{question.hint}}</div>
            <answer-form
                v-if="show"
                :answerType="question.answerType"
                :possibleAnswers="question.possibleAnswers"
                :providedAnswer="answer"
                :currentAnswer="questionAnswer"
                @answered="answered"
            ></answer-form>
            <answer-badge
                v-else
                :answer="questionAnswer"
                :hasEditDeleteButtons="true"
                @clickedButton="clickedButton"
            ></answer-badge>
                
        </template>
    </div>
</template>

<script>
import AnswerForm from '../forms/AnswerForm.vue'
import MainCheckbox from '../MainCheckbox.vue'
import AnswerBadge from './AnswerBadge.vue'
    export default {
        components: {
            AnswerForm,
            MainCheckbox,
            AnswerBadge,
        },
        props: {
            question: {
                type: Object,
                default() {
                    return null
                }
            },
            answer: {
                type: Object,
                default() {
                    return null
                }
            },
            questionAnswer: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                showHint: false,
                show: true,
                mode: ''
            }
        },
        watch: {
            questionAnswer: {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        this.show = false
                    }

                    if (!newValue) {
                        this.show = true
                    }
                }
            }
        },
        computed: {
            
        },
        methods: {
            answered(data) {
                if (! ['update', 'delete'].includes(this.mode)) {
                    data.questionId = this.question.id
                }

                if (data.close) {
                    this.show = false
                    return
                }

                if (this.mode === 'update' || data.type === 'update') {
                    data = {
                        type: data.type.length ? data.type : this.mode,
                        answer: this.questionAnswer,
                        newAnswerData: data
                    }
                }

                this.$emit('answered', data)
                this.mode = ''
            },
            clickedButton(data) {
                if (! this.questionAnswer) {
                    return
                }

                if (data === 'update') {
                    this.show = false
                    return
                }

                this.mode = data.type
                if (data.type === 'update') {
                    this.show = true
                    return
                }

                this.answered({
                    type: data.type,
                    answer: this.questionAnswer
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>