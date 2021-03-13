<template>
    <div class="question-badge-wrapper" v-if="question">
        <div class="drag"
            v-if="drag"
            @click="clickedDrag"
            @dragstart="clickedDrag"
            draggable
        >
            <font-awesome-icon
                :icon="['fa', 'hand-rock']"
            ></font-awesome-icon>
        </div>
        <div class="main" @click="clickedQuestion">
            <div class="body">
                {{question.body}}
            </div>
            <div class="file"
                v-if="question.files.length"
            >
                <file-preview
                    :file="question.files[0]"
                    :showRemove="false"
                ></file-preview>
            </div>
            <div class="hint">
                {{question.hint}}
            </div>
            <div class="score" v-if="question.scoreOver.length">
                {{`score over: ${question.scoreOver}`}}
            </div>
            <div class="possible-answers">
                Options:
                <possible-answer-badge
                    v-for="(possibleAnswer, index) in question.possibleAnswers"
                    :key="index"
                    :possibleAnswer="possibleAnswer"
                    :drag="false"
                    :answerType="question.answerType"
                ></possible-answer-badge>
            </div>
        </div>
    </div>
</template>

<script>
import FilePreview from '../FilePreview';
import PossibleAnswerBadge from './PossibleAnswerBadge';
    export default {
        components: {
            PossibleAnswerBadge,
            FilePreview,
        },
        props: {
            question: {
                type: Object,
                default() {
                    return null
                }
            },
            drag: {
                type: Boolean,
                default: false
            },
        },
        methods: {
            clickedDrag() {
                this.$emit('arrangeQuestions')
            },
            clickedQuestion() {
                this.$emit('editQuestion', this.question)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .question-badge-wrapper{
        min-width: 100%;
        position: relative;
        margin: 0 10px;
        display: flex;
        width: 100%;
        align-items: center;
        justify-content: center;

        .drag{
            cursor: grab;
            position: absolute;
            right: 10px;
            font-size: 20px;
            color: gray;
        }

        .main{
            background: wheat;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;

            .body{
                font-size: 14px;
                color: black;
            }

            .file{
                margin: 0 0 10px;
            }

            .hint{
                font-size: 12px;
                color: gray;
                width: 100%;
                text-align: center;
                margin: 5px;
            }

            .score{
                font-size: 12px;
                color: gray;
                width: 100%;
                text-align: end;
            }
        }
    }
</style>