<template>
    <div class="relative p-1">
        <div class="bg-wheat rounded p-1">
            <files-preview-backend
                v-if="computedFiles.length"
                :files="computedFiles"
            ></files-preview-backend>
            <div class="text-xs text-right text-gray-400">{{answer.createdAt}}</div>
            <div 
                class="font-semibold text-gray-500" 
                data-testid="answer"
                v-if="answer.answer && answer.answer.length"
            >{{answer.answer}}</div>
            <template v-if="computedPossibleAnswers.length">
                <possible-answer-badge
                    v-for="possibleAnswer in computedPossibleAnswers"
                    :key="possibleAnswer.id"
                    :possibleAnswer="possibleAnswer"
                    :drag="false"
                    :answerType="answer.question.answerType"
                    :marking="true"
                    class="p-0"
                ></possible-answer-badge>
            </template>
        </div>
        <div class="relative" v-if="computedShowEditDeleteButtons">
            <div class="text-sm font-medium text-gray-500 p-1" v-if="computedScore.length">
                {{computedScore}}
            </div>
            <div class="text-xs font-medium text-gray-400 px-4" v-if="computedRemark.length">
                {{computedRemark}}
            </div>
            <edit-delete-buttons
                @click="clickedButton"
                class="text-sm flex"
               :class="[computedScore.length ? 'absolute right-0 top-0' : 'relative ml-auto']"
            ></edit-delete-buttons>
        </div>
        <template
            v-if="answer.isMarker"
        >
            <div 
                class="flex justify-end"
                v-if="! answer.mark"
            >
                <div
                    class="text-base p-2 cursor-pointer hover:text-green-500 z-30 mr-1"
                    :class="[computedIsCorrect ? 'text-green-500' :'text-gray-500']"
                    @click="clickedButton('correct')"
                    data-testid="correct"
                >
                    <font-awesome-icon
                        :icon="['fa', 'check']"
                    ></font-awesome-icon>
                </div>
                <partial-mark
                    class="text-base p-2 cursor-pointer hover:text-green-300 z-30 mr-1"
                    :class="[computedIsPartial ? 'text-green-300' :'text-gray-500']"
                    @click.native="clickedButton('partial')"
                ></partial-mark>
                <div
                    class="text-base p-2 cursor-pointer hover:text-red-500 z-30"
                    :class="[computedIsWrong ? 'text-red-500' :'text-gray-500']"
                    @click="clickedButton('wrong')"
                    data-testid="wrong"
                >
                    <font-awesome-icon
                        :icon="['fa', 'times']"
                    ></font-awesome-icon>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import FilesPreviewBackend from '../FilesPreviewBackend.vue'
import Files from '../../mixins/Files.mixin'
import PossibleAnswerBadge from './PossibleAnswerBadge.vue'
import PartialMark from '../specials/PartialMark.vue'
import EditDeleteButtons from '../EditDeleteButtons.vue'
    export default {
        components: {
            FilesPreviewBackend,
            PossibleAnswerBadge,
            PartialMark,
            EditDeleteButtons,
        },
        mixins: [Files],
        props: {
            answer: {
                type: Object,
                default() {
                    return null
                }
            },
            mark: {
                type: Object,
                default() {
                    return null
                }
            },
            hasEditDeleteButtons: {
                type: Boolean,
                default() {
                    return false
                }
            },
        },
        data() {
            return {
                showMarkForm: false
            }
        },
        computed: {
            computedItemable() {
                return this.answer
            },
            computedIsCorrect() {
                if (!this.mark) {
                    return false
                }

                return this.mark.score == this.answer.question.scoreOver
            },
            computedIsPartial() {
                if (!this.mark) {
                    return false
                }

                return this.mark.score < this.answer.question.scoreOver &&
                    0 < this.mark.score
            },
            computedIsWrong() {
                if (!this.mark) {
                    return false
                }

                return this.mark.score == 0
            },
            computedPossibleAnswers() {
                if (! this.answer.possibleAnswerIds?.length) {
                    return []
                }

                let possibleAnswers = []

                this.answer.possibleAnswerIds.forEach(possibleAnswerId=>{
                    possibleAnswers.push(
                        this.answer.question.possibleAnswers.find(possibleAnswer=>{
                            return possibleAnswer.id == possibleAnswerId
                        })
                    )
                })

                return possibleAnswers
            },
            computedScore() {
                return this.answer.mark ? `${this.answer.mark.score}/${this.answer.scoreOver}` : ''
            },
            computedRemark() {
                return this.answer.mark?.remark ? `remarks: ${this.answer.mark.remark}` : ''
            },
            computedShowEditDeleteButtons() {
                return (this.answer?.mark && this.answer.isMarker) || this.hasEditDeleteButtons
            },
        },
        methods: {
            clickedButton(type) {
                if (type === 'edit') {
                    type = 'update'
                }

                this.$emit('clickedButton', {type, answer: this.answer})
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>