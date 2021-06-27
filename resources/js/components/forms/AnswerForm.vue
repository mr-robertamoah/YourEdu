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
        <droppable-component
            @drop="movePossibleAnswer"
            v-if="possibleAnswers.length"
        >
            <div class="" id="answers">        
                <possible-answer-badge
                    v-for="(possibleAnswer, possibleAnswerIndex) of possibleAnswers"
                    :key="possibleAnswerIndex"
                    :possibleAnswerIndex="possibleAnswerIndex"
                    :answerType="answerType"
                    :drag="computedDrag"
                    :possibleAnswer="possibleAnswer"
                    :possibleAnswersLength="possibleAnswers.length"
                    @movePossibleAnswer="movePossibleAnswer"
                    @possibleAnswerIsCorrect="possibleAnswerIsCorrect"
                    @possibleAnswerIsWrong="possibleAnswerIsWrong"
                ></possible-answer-badge>
            </div>
        </droppable-component>
    </div>
</template>

<script>
import TextTextarea from '../TextTextarea.vue'
import TextInput from '../TextInput.vue'
import PossibleAnswerBadge from '../dashboard/PossibleAnswerBadge.vue'
import DroppableComponent from '../specials/DroppableComponent.vue'
    export default {
        components: { 
            DroppableComponent,
            PossibleAnswerBadge,
            TextInput,
            TextTextarea,
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
        },
        data() {
            return {
                answer: {
                    answer: '',
                    file: null,
                    possibleAnswerIds: []
                }
            }
        },
        computed: {
            computedDrag() {
                return ['arrange', 'flow'].includes(this.computedAnswerType)
            },
            computedAnswerType() {
                return this.answerType ? this.answerType.toLowerCase() : ''
            }
        },
        methods: {
            movePossibleAnswer() {
                
            },
            possibleAnswerIsCorrect() {
                
            },
            possibleAnswerIsWrong() {
                
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>