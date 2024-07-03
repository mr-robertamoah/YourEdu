<template>
    <draggable-component
        :dataTransfer="{
                fromPossibleAnswerIndex: possibleAnswerIndex,
                removed
            }"
        :drag="drag"
    >
        <droppable-component
            @drop="movePossibleAnswer"
            :dataTransfer="{
                toPossibleAnswerIndex: possibleAnswerIndex
            }"
        >
            <div class="possible-answer-badge-wrapper" 
                v-if="possibleAnswer"
                :class="{
                    correct: possibleAnswer.correct, 
                    removed,
                    'no-drag': !drag
                }"
                @dblclick="editPossibleAnswer"
            >
                <div 
                    class="option"
                    :class="[nodeClassHas('p-') ? '' : 'p-2.5 pl-0']"
                    v-if="computedOption"
                    @click="clickedUpdateCorrect"
                >
                    <div class="list-identifier" v-if="!removed && !marking">
                        {{computedPosition}}
                    </div>
                    <div class="item">
                        {{possibleAnswer.option}}
                    </div>
                    <main-checkbox
                        v-if="computedCanUpdateCorrect"
                        :small="true"
                        v-model="correct"
                        label=""
                        title="check if this is the correct answer"
                        class="checkbox"
                    ></main-checkbox>
                </div>
                <div 
                    class="true-false"
                    :class="[nodeClassHas('p-') ? '' : 'p-2.5 pl-0']"
                    v-if="computedTrueOrFalse"
                    @click="clickedUpdateCorrect"
                >
                    <div class="list-identifier" v-if="!marking"></div>
                    <div class="item">
                        {{possibleAnswer.option}}
                    </div>
                    <main-checkbox
                        v-if="computedCanUpdateCorrect"
                        :small="true"
                        v-model="correct"
                        label=""
                        title="check if this is the correct answer"
                        class="checkbox"
                    ></main-checkbox>
                </div>
                <div 
                    class="arrange p-2.5" 
                    v-if="computedArrange"
                >
                    <div class="list-identifier" v-if="!removed"></div>
                    <div class="item">
                        {{possibleAnswer.option}}
                    </div>
                </div>
                <div class="flow" v-if="computedFlow">
                    <div class="item">
                        {{possibleAnswer.option}}
                    </div>
                    <div 
                        class="list-identifier"
                        v-if="(possibleAnswer.position !== possibleAnswersLength)
                            && !removed"
                    ></div>
                </div>
            </div>
        </droppable-component>
    </draggable-component>
</template>

<script>
import DraggableComponent from '../specials/DraggableComponent.vue'
import DroppableComponent from '../specials/DroppableComponent.vue'
import MainCheckbox from '../MainCheckbox.vue'
import { strings } from '../../services/helpers'
    export default {
        components: {
            DroppableComponent,
            DraggableComponent,
            MainCheckbox,
        },
        props: {
            possibleAnswer: {
                type: Object,
                default() {
                    return null
                }
            },
            correctPossibleAnswers: {
                type: Array,
                default() {
                    return []
                }
            },
            autoMark: {
                type: Boolean,
                default: false
            },
            answering: {
                type: Boolean,
                default: false
            },
            marking: {
                type: Boolean,
                default: false
            },
            drag: {
                type: Boolean,
                default: true
            },
            removed: {
                type: Boolean,
                default: false
            },
            answerType: {
                type: String,
                default: ''
            },
            possibleAnswerIndex: {
                type: String | Number,
                default: null
            },
            possibleAnswersLength: {
                type: String | Number,
                default: null
            },
        },
        data() {
            return {
                correct: false
            }
        },
        watch: {
            correct(newValue) {
                if (newValue) {
                    this.$emit('possibleAnswerIsCorrect', this.possibleAnswer)
                } else {
                    this.$emit('possibleAnswerIsWrong', this.possibleAnswer)
                }
            },
            correctPossibleAnswers: {
                immediate: true,
                handler(newValue) {
                    if (!(this.computedTrueOrFalse || this.computedOption)) {
                        return
                    }

                    if (!newValue.length) {
                        return
                    }

                    if (this.isCorrectTrueOrFalse(newValue) ||
                        this.isCorrectOption(newValue)) {
                        this.correct = true
                        return
                    }

                    this.correct = false
                }
            }
        },
        computed: {
            computedTrueOrFalse() {
                return this.answerType.toLowerCase().includes('true')
            },
            computedOption() {
                return this.answerType.toLowerCase().includes('option')
            },
            computedArrange() {
                return this.answerType.toLowerCase().includes('arrange')
            },
            computedFlow() {
                return this.answerType.toLowerCase().includes('flow')
            },
            computedPosition() {
                return strings.getNumberLetter(this.possibleAnswer.position)
            },
            computedCanUpdateCorrect() {
                return (this.autoMark && !this.removed) || this.answering
            },
            computedClasses() {
                return this.$vnode.data.staticClass ? this.$vnode.data.staticClass : ''
            },
        },
        methods: {
            movePossibleAnswer(data) {
                this.$emit('movePossibleAnswer', data)
            },
            editPossibleAnswer() {
                this.$emit('editPossibleAnswer', this.possibleAnswer)
            },
            isCorrectTrueOrFalse(data) {
                return this.computedTrueOrFalse && 
                    data[0].option === this.possibleAnswer.option
            },
            isCorrectOption(data) {
                return this.computedOption && 
                    data[0].id === this.possibleAnswer.id
            },
            clickedUpdateCorrect() {
                if (! this.answering) {
                    return
                }
                
                this.correct = !this.correct
            },
            nodeClassHas(text) {
                return this.computedClasses.includes(text)
            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin colorTransition($duration) {
    transition-timing-function: cubic-bezier(0.86, 0, 0.07, 1);
    transition-property: color, background-color;
    transition-duration: $duration;
}

    .possible-answer-badge-wrapper{
        cursor: pointer;
        padding: 0 5px;
        border-radius: 10px;
        margin: 5px auto;

        .true-false{
            display: flex;
            width: fit-content;
            align-items: center;
            width: 100%;

            .list-identifier{
                width: 10px;
                height: 10px;
                border-radius: 100%;
                background: gray;
                margin-right: 10px;
                position: relative;
            }

            .checkbox{
                margin-left: auto;
                margin-right: -10px;
            }
        }

        .option{
            display: flex;
            width: fit-content;
            align-items: center;
            width: 100%;

            .list-identifier{
                color: gray;
                margin-right: 10px;
                position: relative;
            }

            .item{
                min-width: fit-content;
            }

            .checkbox{
                margin-left: auto;
                margin-right: -10px;
            }
        }

        .arrange{
            display: flex;
            align-items: center;

            .list-identifier{
                width: 10px;
                margin-right: 5px;
                height: 2px;
                background: black;
                position: relative;

                &::before, &::after{
                    content: '';
                    width: 12px;
                    height: 2px;
                    background: gray;
                    position: absolute;
                    left: 0;
                }

                &::before{
                    transform: rotateZ(15deg);
                    top: -5px;
                }

                &::after{
                    top: 5px;
                    transform: rotateZ(-15deg);
                }
            }
            
        }

        .flow{
            display: block;
            text-align: center;

            .list-identifier{
                width: 2px;
                margin-right: 5px;
                height: 30px;
                background: black;
                position: relative;
                margin: 0 auto;

                &::before, &::after{
                    content: '';
                    width: 12px;
                    height: 2px;
                    background: gray;
                    position: absolute;
                    bottom: 1px;
                    background: $color-secondary;
                }

                &::before{
                    transform: rotateZ(55deg);
                    left: -8px;
                }

                &::after{
                    transform: rotateZ(125deg);
                    left: -2px;
                }
            }
            
        }
    }

    .possible-answer-badge-wrapper:hover{
        background: $modal-background;
        @include colorTransition(.4s);
    }

    .possible-answer-badge-wrapper.no-drag:hover{
        background: inherit;
    }

    .possible-answer-badge-wrapper.removed{
        @include normal-box-shadow;

        .option, .true-false, .flow, .arrange{
            display: flex;
            justify-content: center;
        }
    }

    .possible-answer-badge-wrapper.correct{
        background: darkgreen;
        color: white;
        @include colorTransition(.8s);

        .option{

            .list-identifier{
                color: white;
            }
        }
    }
</style>