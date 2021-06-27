<template>
    <div>
        <div v-if="! question">sorry 😞, there is no question</div>
        <template v-if="question">
            <div class="">{{question.body}}</div>
            <answer-form
                :answerType="question.answerType"
                :possibleAnswers="question.possibleAnswers"
            ></answer-form>
        </template>
    </div>
</template>

<script>
import AnswerForm from '../forms/AnswerForm'
    export default {
        components: {
            AnswerForm,
        },
        props: {
            question: {
                type: Object,
                default() {
                    return null
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>