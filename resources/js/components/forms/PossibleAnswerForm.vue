<template>
    <div class="possible-answer-form"
        ref="possibleanswersform"
    > 
        <div class="main">
            <droppable-component
                @drop="movePossibleAnswer"
            >
                <div class="answers" id="answers">        
                    <possible-answer-badge
                        v-for="(possibleAnswer, possibleAnswerIndex) of possibleAnswers"
                        :key="possibleAnswerIndex"
                        :possibleAnswerIndex="possibleAnswerIndex"
                        :answerType="answerType"
                        :autoMark="autoMark"
                        :possibleAnswer="possibleAnswer"
                        :correctPossibleAnswers="correctPossibleAnswers"
                        :possibleAnswersLength="possibleAnswers.length"
                        @movePossibleAnswer="movePossibleAnswer"
                        @editPossibleAnswer="editPossibleAnswer"
                        @possibleAnswerIsCorrect="possibleAnswerIsCorrect"
                        @possibleAnswerIsWrong="possibleAnswerIsWrong"
                    ></possible-answer-badge>
                </div>
            </droppable-component>
            <div class="message" v-if="computedMessage.length">
                {{`${computedMessage} ${computedAutoMarkMessage}`}}
            </div>
            <div class="correct-buttons">
                <grey-button class="correct" 
                    v-if="computedAutoMarkMessage.length && computedSetCorrect"
                    text="set as correct"
                    @clickedAction="clickedSetCorrect"
                >
                </grey-button>
                <grey-button class="correct" 
                    v-if="correctPossibleAnswers.length"
                    :text="computedShowCorrectAnswerButtonText"
                    @clickedAction="showCorrectAnswer = !showCorrectAnswer"
                >
                </grey-button>
            </div>
            <div class="correct-order" v-if="showCorrectAnswer">
                <div class="item"
                    v-for="(possibleAnswer, index) in correctPossibleAnswers"
                    :key="index"
                    v-text="possibleAnswer.option"
                ></div>
            </div>
            <div class="possible-answers-form" 
                v-show="computedShowOptionTextInput"
            >
                <div class="section">Answer Info</div>
                <text-input
                    :bottomBorder="true"
                    :placeholder="`enter ${computedOptionPlaceholder}*`"
                    v-model="data.option"
                    class="other-input"
                    @keyupenter="addPossibleAnswer"
                ></text-input>
                <div 
                    v-if="computedHasData"
                    class="add" 
                    title="add possible answer"
                    @click="addPossibleAnswer"
                >
                    <font-awesome-icon 
                        :icon="['fa','plus']"
                        class="plus"
                    ></font-awesome-icon>
                </div>
                <droppable-component
                    @drop="removePossibleAnswer"
                    v-if="possibleAnswers.length"
                >
                    <div class="trash-area"
                        title="drop an option here to have it removed"
                    >
                        <div class="trash">
                            <font-awesome-icon 
                                :icon="['fa','trash']"
                                class="icon"
                            ></font-awesome-icon>
                            <div class="message">remove option by dropping it here</div>
                            <possible-answer-badge
                                v-for="(possibleAnswer, possibleAnswerIndex) of removedPossibleAnswers"
                                :key="possibleAnswerIndex"
                                :possibleAnswerIndex="possibleAnswerIndex"
                                :answerType="answerType"
                                :autoMark="autoMark"
                                :removed="true"
                                :possibleAnswer="possibleAnswer"
                                :possibleAnswersLength="possibleAnswers.length"
                                @movePossibleAnswer="removePossibleAnswer"
                                class="option"
                            ></possible-answer-badge>
                        </div>
                    </div>
                </droppable-component>
            </div>
        </div>

        <pop-up
            message="do you want true or false options"
            default="no"
            :show="showPopUp"
            @closePopUp="closePopUp"
            @clickedResponse="popUpResponse"
        ></pop-up>
    </div>
</template>

<script>
import PossibleAnswerBadge from '../dashboard/PossibleAnswerBadge';
import DroppableComponent from '../specials/DroppableComponent'
import TextInput from '../TextInput';
import PopUp from '../../mixins/PopUp.mixin';
import {bus} from '../../app';
import GreyButton from '../GreyButton.vue';
    export default {
        components: {
            TextInput,
            PossibleAnswerBadge,
            DroppableComponent,
            GreyButton,
        },
        props: {
            answerType: {
                type: String,
                default: ''
            },
            autoMark: {
                type: Boolean,
                default: false
            },
            correctPossibleAnswers: {
                type: Array,
                default() {
                    return []
                }
            },
            possibleAnswers: {
                type: Array,
                default() {
                    return []
                }
            },
            removedPossibleAnswers: {
                type: Array,
                default() {
                    return []
                }
            },
        },
        mixins: [PopUp],
        data() {
            return {
                data: {
                    id: '',
                    option: '',
                    position: '',
                },
                showCorrectAnswer: false,
            }
        },
        watch: {
            computedTrueOrFalse(newValue) {
                if (newValue) {
                    this.askIfTrueOrFalse()
                }
            }
        },
        mounted () {
            bus
            .$on('scrollToPossibleAnswerForm', ()=> { 
                this.scrollToForm()
            })
        },
        computed: {
            computedShowOptionTextInput() {
                return this.answerType.length && this.answerType !== 'true_false' 
            },
            computedOptionPlaceholder() {
                return this.answerType === 'option' ? this.answerType :
                    this.answerType === 'arrange' ? 'arrangeable item' :
                    this.answerType === 'flow' ? 'flowable item' : ''
            },
            computedTrueOrFalse() {
                return this.data.option.trim() === 'true' ||
                    this.data.option.trim() === 'false'
            },
            computedHasData() {
                return this.data.option.length
            },
            computedMessage() {
                return this.possibleAnswers.length < 2 ? '' :
                    (this.answerType === 'true_false' ||
                    this.answerType === 'option') ? 
                    'a selection will be made among these options' : 
                    this.answerType === 'arrange' ? 
                    'these options must be arranged vertically' :
                    this.answerType === 'flow' ? 
                    'these options must be arranged vertically with arrows' : ''
            },
            computedAutoMarkMessage() {
                if (!this.autoMark || !this.computedMessage.length) {
                    return ''
                }
                let msg = (this.answerType === 'true_false' ||
                        this.answerType === 'option') ? 
                    '. check the box of the correct answer' : 
                    (this.answerType === 'arrange' ||
                        this.answerType === 'flow') ? 
                    '. put in correct order and click the "set as correct" button. then re-order.' : ''

                msg += '\n Note: what you set as correct will be used to automatically mark answers'
            
                return msg
            },
            computedSetCorrect() {
                return this.answerType === 'arrange' || 
                    this.answerType === 'flow'
            },
            computedShowCorrectAnswerButtonText() {
                return this.showCorrectAnswer ? 'hide correct answer' : 'show correct answer'
            }
        },
        methods: {
            removePossibleAnswer(data) {
                this.$emit('removePossibleAnswer',data)
            },
            editPossibleAnswer(possibleAnswer) {
                this.setAssessmentSectionQuestionPossibleAnswer(possibleAnswer)
            },
            setAssessmentSectionQuestionPossibleAnswer(possibleAnswer) {
                this.data.id = possibleAnswer.id
                this.data.option = possibleAnswer.option
                this.data.position = possibleAnswer.position
            },
            clickedSetCorrect() {
                this.$emit('answersInCorrectAnswer')
            },
            addPossibleAnswer() {
                if (!this.computedHasData) {
                    return
                }

                if (Boolean(this.data.id)) {
                    this.$emit('updatePossibleAnswer',{
                        id: this.data.id,
                        option: this.data.option.trim(),
                        position: this.data.position,
                    })
                    this.clearPossibleAnswerData()
                    return
                }

                this.$emit('addPossibleAnswer',{
                    id: Math.ceil(Math.random() * -1000),
                    option: this.data.option.trim(),
                    position: null,
                })
                this.clearPossibleAnswerData()
            },
            clearPossibleAnswerData() {
                this.data.id = ''
                this.data.option = ''
                this.data.position = ''
            },
            scrollToForm() {
                if (this.$refs.possibleanswersform) {
                    setTimeout(() => {
                        this.$refs.possibleanswersform.scrollIntoView()                        
                    }, 100);
                }
            },
            movePossibleAnswer(data) {
                this.$emit('movePossibleAnswer', data)
            },
            possibleAnswerIsCorrect(data) {
                this.$emit('possibleAnswerIsCorrect', data)
            },
            possibleAnswerIsWrong(data) {
                this.$emit('possibleAnswerIsWrong', data)
            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin appearTransition() {
    transition-timing-function: cubic-bezier(0.86, 0, 0.07, 1);
    transition-property: all;
    transition-duration: .2s;
}
    .possible-answer-form{
        max-width: 300px;
        min-width: 250px;
        height: inherit;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 10px; 
        overflow-y: scroll;
        padding: 0px 10px 10px 0;

        .main{
            width: 100%;
            max-height: 400px;
        }

        .answers{
            width: 100%;
            padding: 10px;
            overflow-y: auto;
            max-height: 400px;
        }

        .message{
            font-size: 12px;
            color: gray;
            width: 100%;
            text-align: center;
        }

        .correct-order{

            .item{
                font-size: 12px;
                color: gray;
                text-align: center;
                margin: 5px;
                @include normal-box-shadow
            }
        }

        .possible-answers-form{
            
            .section{
                @include form-section()
            }

            .add{
                width: fit-content;
                margin: 0 0 0 auto;
                padding: 10px;
                cursor: pointer;
                color: gray;
            }
        }

        .correct-buttons{
            @include scroll-x();

            .correct{
                margin-right: 10px;
            }
        }

        .trash-area{
            display: flex;
            width: 100%;
            min-height: 100px;
            justify-content: center;
            align-items: center;
            background: $modal-background;
            margin: 10px auto;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            @include appearTransition();

            .trash{
                text-align: center;

                .icon{
                    color: red;
                }

                .message{
                    font-size: 10px;
                    margin: 10px;
                    color: gray;
                }

                .option{
                    max-width: 150px;
                }
            }
        }
    }

    @media screen and (max-width:800px) {
        
    }
</style>