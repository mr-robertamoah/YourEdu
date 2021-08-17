<template>
    <div>
        <text-input
            placeholder="give a short answer to this question"
            :bottomBorder="true"
            v-model="answer.answer"
            v-if="computedAnswerType === 'short_answer'"
        ></text-input>
        <text-textarea
            placeholder="give a long answer to this question"  
            :bottomBorder="true"
            v-model="answer.answer"
            v-if="computedAnswerType === 'long_answer'"
        ></text-textarea>
        <number-input
            placeholder="give a long answer to this question"  
            :bottomBorder="true"
            v-model="answer.answer"
            v-if="computedAnswerType === 'number'"
        ></number-input>
        <droppable-component
            @drop="movePossibleAnswer"
            v-if="possibleAnswers.length"
        >
            <div class="" id="answers">        
                <possible-answer-badge
                    v-for="(possibleAnswer, possibleAnswerIndex) of answerPossibleAnswers"
                    :key="possibleAnswerIndex"
                    :possibleAnswerIndex="possibleAnswerIndex"
                    :answering="true"
                    :answerType="answerType"
                    :drag="computedDrag"
                    :possibleAnswer="possibleAnswer"
                    :possibleAnswersLength="possibleAnswers.length"
                    @movePossibleAnswer="movePossibleAnswer"
                    @possibleAnswerIsCorrect="possibleAnswerIsCorrect"
                    :correctPossibleAnswers="computedCorrectPossibleAnswers"
                ></possible-answer-badge>
            </div>
        </droppable-component>
        <div class="w-full p-2 flex justify-end" v-if="currentAnswer">
            <div
                class="text-base p-2 cursor-pointer hover:text-green-500 z-30 mr-1 text-gray-500"
                @click="clickedButton('update')"
                v-if="computedHasData"
            >
                update
            </div>
            <div
                class="text-base p-2 cursor-pointer hover:text-green-500 z-30 text-gray-500"
                @click="clickedButton('cancel')"
            >
                cancel
            </div>
        </div>
    </div>
</template>

<script>
import TextTextarea from '../TextTextarea.vue'
import TextInput from '../TextInput.vue'
import PossibleAnswerBadge from '../dashboard/PossibleAnswerBadge.vue'
import DroppableComponent from '../specials/DroppableComponent.vue'
import NumberInput from '../NumberInput.vue'
    export default {
        components: { 
            DroppableComponent,
            PossibleAnswerBadge,
            TextInput,
            TextTextarea,
            NumberInput,
        },
        props: {
            answerType: {
                type: String,
                default: null
            },
            possibleAnswers: {
                type: Array,
                default() {
                    return []
                }
            },
            providedAnswer: {
                type: Object,
                default() {
                    return null
                }
            },
            currentAnswer: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                answer: {
                    assessmentSectionId: null,
                    questionId: null,
                    answer: '',
                    file: null,
                    possibleAnswerIds: []
                },
                answerPossibleAnswers: []
            }
        },
        watch: {
            possibleAnswers: {
                immediate: true,
                handler(newValue) {

                    if (this.computedDrag) {
                        this.answerPossibleAnswers = this.arrangedPossibleAnswersByProvided()
                        return
                    }

                    this.answerPossibleAnswers = newValue
                }
            },
            providedAnswer: {
                immediate: true,
                handler(newValue) {
                    if (!newValue) {
                        this.clearAnswer()

                        return
                    }
                    
                    this.answer.answer = newValue.answer
                    this.answer.assessmentSectionId = newValue.assessmentSectionId
                    this.answer.file = newValue.file
                    this.answer.possibleAnswerIds = newValue.possibleAnswerIds
                    this.answer.questionId = newValue.questionId
                }
            },
            "answer.answer"(newValue) {
                if (this.currentAnswer) return
                
                if (newValue && this.computedTypingAnswerType) {
                    this.emitAnswer()
                }
            },
            "answer.file"(newValue) {
                if (this.currentAnswer) return

                if (newValue && this.computedFileAnswerType) {
                    this.emitAnswer()
                }
            },
        },
        computed: {
            computedDrag() {
                return ['arrange', 'flow'].includes(this.computedAnswerType)
            },
            computedSelectable() {
                return ['option', 'true_false'].includes(this.computedAnswerType)
            },
            computedTypingAnswerType() {
                return ['long_answer', 'short_answer', 'number'].includes(this.computedAnswerType)
            },
            computedFileAnswerType() {
                return ['file', 'image', 'video', 'audio'].includes(this.computedAnswerType)
            },
            computedAnswerType() {
                return this.answerType ? this.answerType.toLowerCase() : ''
            },
            computedPossibleAnswerIds() {
                return this.answerPossibleAnswers.map(possibleAnswer=> {
                    return possibleAnswer.id
                })
            },
            computedCorrectPossibleAnswers() {
                return this.providedAnswer ? 
                    this.possibleAnswers.filter(possibleAnswer=>{
                        return this.providedAnswer.possibleAnswerIds.includes(possibleAnswer.id)
                    }) : []
            },
            computedHasData() {
                return this.answer.answer.length || this.answer.possibleAnswerIds.length ||
                    this.answer.file
            },
        },
        methods: {
            movePossibleAnswer(data) {

                if (data.fromPossibleAnswerIndex + 1 ===
                    this.answerPossibleAnswers.length && 
                    data.toPossibleAnswerIndex === undefined) {
                    return
                }

                let fromPossibleAnswer = this.answerPossibleAnswers.splice(
                        data.fromPossibleAnswerIndex,
                        1
                    )[0]

                if (data.toPossibleAnswerIndex === undefined) {
                    this.answerPossibleAnswers.push(fromPossibleAnswer)
                    return
                }
                
                if (data.toPossibleAnswerIndex === 0) {
                    this.answerPossibleAnswers.unshift(fromPossibleAnswer)
                    return
                }

                this.answerPossibleAnswers.splice(data.toPossibleAnswerIndex,0,fromPossibleAnswer)

                this.$nextTick(this.addPossibleAnswer(null))
            },
            arrangedPossibleAnswersByProvided() {
                if (!this.providedAnswer || this.providedAnswer.possibleAnswerIds.length > 1) {
                    return this.possibleAnswers
                }

                let arrangedPossibleAnswers = []
                this.providedAnswer.possibleAnswerIds.forEach(possibleAnswerId=>{
                    arrangedPossibleAnswers.push(
                        this.possibleAnswers.find(possibleAnswer=>{
                            possibleAnswer.id == possibleAnswerId
                        })
                    )
                })

                return arrangedPossibleAnswers
            },
            possibleAnswerIsCorrect(data) {
                
                this.addPossibleAnswer(data)
            },
            possibleAnswerIsWrong(data) {
                this.removePossibleAnswer(data)
            },
            removePossibleAnswer(data) {
                let index = this.answer.possibleAnswers.findIndex(possibleAnswer=>{
                    return possibleAnswer.id == data.id
                })

                if (index === -1) {
                    return
                }
                
                this.answer.possibleAnswers.splice(index, 1)
                
                if (this.currentAnswer) return
                
                this.emitAnswer()
            },
            emitAnswer() {
                this.$emit('answered', this.answer)
            },
            addPossibleAnswer(data) {
                this.answer.possibleAnswerIds = []
                if (this.computedSelectable && data) {
                    this.answer.possibleAnswerIds = [data.id]
                    
                    if (this.currentAnswer) return
                
                    this.emitAnswer()
                    return
                }

                if (data) {
                    return
                }

                this.answer.possibleAnswerIds = this.answerPossibleAnswers

                if (this.currentAnswer) return
                
                this.emitAnswer()
            },
            clearAnswer() {
                this.answer.assessmentSectionId = null,
                this.answer.questionId = null,
                this.answer.answer = '',
                this.answer.file = null,
                this.answer.possibleAnswerIds = []
            },
            clickedButton(text) {
                if (text === 'update') {

                    this.$emit('answered', {type: text, ...this.answer})
                    return
                }

                this.clearAnswer()
                this.$emit('answered', {close: true})
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>