<template>
    <div 
        class="assessment-question-form"
        ref="questions"
    >
        <question-badge
            v-for="(question, questionIndex) of questions"
            :key="questionIndex"
            :question="question"
            :drag="questions.length > 1"
            @arrangeQuestions="arrangeQuestions"
            @editQuestion="editQuestion"
            @removeQuestion="removeQuestion"
        ></question-badge>
        <div class="questions-form" 
            v-show="showQuestionForm"
            ref="questionsform"
        >
            <div class="section">Question Info</div>
            <div class="main">
                <text-textarea
                    :bottomBorder="true"
                    placeholder="question*"
                    v-model="data.body"
                    class="other-input"
                ></text-textarea>
                <div class="file-status" v-if="data.files.length">
                    {{`${data.files.length} file added to question`}}
                    <font-awesome-icon
                        class="icon"
                        :icon="['fa', 'trash']"
                        title="remove file"
                        @click="clickedRemoveFiles"
                    ></font-awesome-icon>
                </div>
                <div class="files">
                <div @click="clickedFileUpload('image')" class="file">
                    <font-awesome-icon
                        :icon="['fa','image']"
                        title="snap a picture"
                    ></font-awesome-icon>
                </div>
                <div @click="clickedFileUpload('file-image')" class="file">
                    <font-awesome-icon
                        :icon="['fa','file-image']"
                        title="upload image from device"
                    ></font-awesome-icon>
                </div>
                <div @click="clickedFileUpload('video')" class="file">
                    <font-awesome-icon
                        :icon="['fa','video']"
                        title="record a video"
                    ></font-awesome-icon>
                </div>
                <div @click="clickedFileUpload('file-video')" class="file">
                    <font-awesome-icon
                        :icon="['fa','file-video']"
                        title="upload video from device"
                    ></font-awesome-icon>
                </div>
                <div @click="clickedFileUpload('microphone')" class="file">
                    <font-awesome-icon
                        :icon="['fa','microphone']"
                        title="record an audio"
                    ></font-awesome-icon>
                </div>
                <div @click="clickedFileUpload('file-audio')" class="file">
                    <font-awesome-icon
                        :icon="['fa','file-audio']"
                        title="upload audio from device"
                    ></font-awesome-icon>
                </div>
                <div @click="clickedFileUpload('file')" class="file">
                    <font-awesome-icon
                        :icon="['fa','file']"
                        title="upload file from device"
                    ></font-awesome-icon>
                </div>
                </div>
                <text-textarea
                    :bottomBorder="true"
                    placeholder="hint to answering the question"
                    v-model="data.hint"
                    class="other-input"
                ></text-textarea>
                <number-input
                    :bottomBorder="true"
                    label="score question"
                    placeholder="over"
                    v-model="data.scoreOver"
                    :hasMax="false"
                    :inputMin="1"
                    class="other-input"
                ></number-input>
                <div class="small-msg">
                    Answer type required for this question
                </div>
                <div class="type">
                    <grey-button
                        class="grey-button"
                        v-for="(answerType,index) of answerTypes"
                        :key="index"
                        :active="checkActiveAnswerType(answerType)"
                        :text="answerType"
                        @clickedAction="clickedAnswerType"
                    ></grey-button>
                </div>
            </div>

            <div class="buttons">
                <post-button
                    v-if="computedAddQuestionButton"
                    buttonText="add question"
                    buttonStyle=''
                    @click="addQuestion"
                ></post-button>
                <post-button
                    v-if="computedDoneQuestionButton"
                    buttonText="done editing question"
                    buttonStyle=''
                    @click="updateQuestion"
                ></post-button>
                <post-button
                    v-if="computedDoneQuestionButton"
                    buttonText="clear data"
                    buttonStyle=''
                    @click="clearQuestionData"
                ></post-button>
            </div>
        </div>

        <possible-answer-form
            v-show="computedShowPossibleAnswerForm"
            :answerType="data.answerType"
            :possibleAnswers="data.possibleAnswers"
            :correctPossibleAnswers="data.correctPossibleAnswers"
            :removedPossibleAnswers="data.removedPossibleAnswers"
            @itIsTrueOrFalse="itIsTrueOrFalse"
            @addPossibleAnswer="addPossibleAnswer"
            @movePossibleAnswer="movePossibleAnswer"
            @removePossibleAnswer="removePossibleAnswer"
            @updatePossibleAnswer="updatePossibleAnswer"
            @possibleAnswerIsCorrect="possibleAnswerIsCorrect"
            @possibleAnswerIsWrong="possibleAnswerIsWrong"
            @answersInCorrectAnswer="answersInCorrectAnswer"
            :autoMark="autoMark"
        ></possible-answer-form>

        <input type="file" 
            ref="inputfile" 
            class="file-input"
            @change="changedInputFile"
        >
        <media-capture
            v-if="showMediaCapture"
            :show="showMediaCapture"
            :type="mediaCaptureType"
            @closeMediaCapture="closeMediaCapture"
            @sendFile="receiveFile"
        ></media-capture>
    </div>
</template>

<script>
import TextTextarea from '../TextTextarea';
import MediaCapture from '../MediaCapture';
import TextInput from '../TextInput';
import MainCheckbox from '../MainCheckbox';
import NumberInput from '../NumberInput';
import DatePicker from '../DatePicker';
import PostButton from '../PostButton';
import GreyButton from '../GreyButton';
import PossibleAnswerForm from './PossibleAnswerForm';
import QuestionBadge from '../dashboard/QuestionBadge';
import {bus} from '../../app';
    export default {
        components: {
            TextInput,
            MediaCapture,
            TextTextarea,
            QuestionBadge,
            PossibleAnswerForm,
            GreyButton,
            PostButton,
            MainCheckbox,
            NumberInput,
            DatePicker,
        },
        props: {
            questions: {
                type: Array,
                default() {
                    return []
                }
            },
            autoMark: {
                type: Boolean,
                default: false
            },
            answerType: {
                type: String,
                default: ''
            },
            answerTypes: {
                type: Array,
                default() {
                    return []
                }
            },
            answerTypesPair: {
                type: Object,
                default() {
                    return {}
                }
            },
        },
        data() {
            return {
                data: {
                    id: '',
                    body: '',
                    hint: '',
                    position: '',
                    scoreOver: '',
                    answerType: '',
                    possibleAnswer: {
                        position: null,
                        option: '',
                        id: '',
                        correct: false
                    },
                    files: [],
                    mainFiles: [],
                    removedFiles: [],
                    correctPossibleAnswers: [],
                    possibleAnswers: [],
                    removedPossibleAnswers: [],
                    mainPossibleAnswers: [],
                    editedPossibleAnswers: [],
                },
                showQuestionForm: false,
                trueFalse: [
                    {
                        position: 1,
                        option: 'true',
                        id: ''
                    },
                    {
                        position: 2,
                        option: 'false',
                        id: ''
                    },
                ],
                showMediaCapture: false,
                mediaCaptureType: '',
            }
        },
        watch: {
            sections(newValue) {
                if (newValue && newValue.length) {
                    this.setShowQuestionFormToTrue()
                }
            },
            answerType(newValue) {
                this.data.answerType = newValue
            },
            "data.answerType"(newValue) {
                this.data.possibleAnswers = []
                if (newValue === 'true_false') {
                    this.data.possibleAnswers.push(...this.trueFalse)
                }
                if (this.computedShowPossibleAnswerForm) {
                    this.scrollToPossibleAnswerForm()
                }
                this.resetCorrectAnswer()
            },
            "data.possibleAnswers"(newValue) {
                this.resetCorrectAnswer()
            },
            showQuestionForm(newValue) {
                if (newValue) {
                    this.scrollToForm()
                }
            }
        },
        mounted () {
            bus
            .$on('clearAssessmentSectionQuestionData', ()=> { 
                this.clearAssessmentSectionQuestionData()
            })
            .$on('toggleQuestionForm', ()=> { 
                this.setShowQuestionFormToTrue()
            })
        },
        computed: {
            computedAddQuestionButton() {
                return !String(this.data.id).length && this.computedHasData &&
                    !(this.computedShowPossibleAnswerForm && !this.data.possibleAnswers.length)
            },
            computedEditQuestionButton() {
                return !this.computedAddQuestionButton && this.computedHasData
            },
            computedHasData() {
                return (this.data.body.length || this.data.files.length) && 
                    this.data.scoreOver.length
            },
            computedShowPossibleAnswerForm() {
                return this.data.answerType.length && 
                (this.data.answerType === 'true_false' ||
                    this.data.answerType === 'option' ||
                    this.data.answerType === 'arrange' ||
                    this.data.answerType === 'flow')
            },
            computedDoneQuestionButton() {
                return String(this.data.id).length && this.computedHasData &&
                    !(this.computedShowPossibleAnswerForm && !this.data.possibleAnswers.length)
            },
        },
        methods: {
            clickedRemoveFiles() {
                this.data.files = []
            },
            clearQuestionData() {
                this.clearAssessmentSectionQuestionData()
            },
            editQuestion(question) {
                this.setAssessmentSectionQuestionData(_.cloneDeep(question))
                this.scrollToForm()
            },
            arrangeQuestions() {
                bus.$emit('arrangeQuestions', this.questions)
            },
            undoQuestionRemoval(question) {
                this.$emit('undoQuestionRemoval', question)
            },
            removeQuestion(question) {
                this.$emit('removeQuestion', question)
            },
            updateQuestion() {
                this.$emit('updateQuestion', _.cloneDeep(this.data))
                this.clearAssessmentSectionQuestionData()
                this.scrollToLast()
            },
            closeMediaCapture() {
                this.showMediaCapture = false
                this.mediaCaptureType = ''
            },
            addFile(file) {
                this.data.files = []
                this.data.files.push(file)
            },
            changedInputFile() {
                this.addFile(
                    this.$refs.inputfile.files[0]
                )
                this.$refs.inputfile.value = ''
            },
            receiveFile(file) {
                this.addFile(file)
            },
            getInputFileType(data) {
                if (data.includes('image')) {
                    return 'image/*'
                }
                
                if (data.includes('video')) {
                    return 'video/*'
                }
                
                if (data.includes('audio')) {
                    return 'audio/*'
                }

                return 'application/*'
            },
            clickedFileUpload(data) {
                if (data.includes('file')) {
                    
                    this.$refs.inputfile.accept = this.getInputFileType(data)
                    this.$refs.inputfile.click()
                    return
                }

                this.mediaCaptureType = data === 'microphone' ? 
                    'audio' : data
                this.showMediaCapture = true
            },
            dropPossibleAnswer(event, possibleAnswerIndex) {
                
            },
            answersInCorrectAnswer() {
                this.data.correctPossibleAnswers = _.clone(this.data.possibleAnswers)
            },
            itIsTrueOrFalse() {
                this.data.answerType = 'true_false'
                this.scrollToPossibleAnswerForm()
            },
            setShowQuestionFormToTrue() {
                this.showQuestionForm = true
            },
            resetCorrectAnswer() {
                this.data.correctPossibleAnswers = []
            },
            updatePossibleAnswer(possibleAnswer) {
                let index = this.data.possibleAnswers.findIndex(pa=>{
                    return pa.id == possibleAnswer.id
                })

                if (index === -1) {
                    return
                }

                this.data.possibleAnswers.splice(index, 1, possibleAnswer)
            },
            removePossibleAnswer(data) {
                let fromPossibleAnswer = this.data.possibleAnswers.splice(
                    data.fromPossibleAnswerIndex,
                    1
                )[0]

                this.data.removedPossibleAnswers.push(fromPossibleAnswer)
                this.updatePossibleAnswersPositions()
            },
            updatePossibleAnswersPositions() {
                this.data.possibleAnswers.forEach(
                    function(possibleAnswer, possibleAnswerIndex){
                        possibleAnswer.position = possibleAnswerIndex + 1
                    }
                )
            },
            findCorrectPossibleAnswerIndex(data) {
                return this.data.correctPossibleAnswers.findIndex(
                    function(possibleAnswer){
                        return possibleAnswer.id === data.id &&
                            possibleAnswer.option === data.option
                    }
                )
            },
            possibleAnswerIsCorrect(correctPossibleAnswer) {
                let index = this.findCorrectPossibleAnswerIndex(correctPossibleAnswer)
                if (index === -1) {
                    this.resetCorrectAnswer()
                    this.data.correctPossibleAnswers.push(correctPossibleAnswer)
                }
            },
            possibleAnswerIsWrong(correctPossibleAnswer) {
                let index = this.findCorrectPossibleAnswerIndex(correctPossibleAnswer)
                if (index > -1) {
                    this.data.correctPossibleAnswers.splice(index, 1)
                }
            },
            movePossibleAnswer(data) {
                let fromPossibleAnswer = data.removed ?
                    this.data.removedPossibleAnswers.splice(
                        data.fromPossibleAnswerIndex,
                        1
                    )[0] :
                    this.data.possibleAnswers.splice(
                        data.fromPossibleAnswerIndex,
                        1
                    )[0]

                if (data.fromPossibleAnswerIndex + 1 ===
                    this.data.possibleAnswers.length && 
                    data.toPossibleAnswerIndex === undefined &&
                    !data.removed) {
                    return
                }

                if (data.toPossibleAnswerIndex === undefined) {
                    this.data.possibleAnswers.push(
                        fromPossibleAnswer
                    )
                } else if (data.toPossibleAnswerIndex === 0) {
                    this.data.possibleAnswers.unshift(
                        fromPossibleAnswer
                    )
                } else {
                    this.data.possibleAnswers.splice(
                        data.toPossibleAnswerIndex,
                        0,
                        fromPossibleAnswer
                    )
                }

                this.updatePossibleAnswersPositions()
            },
            clearAssessmentSectionQuestionData() {
                this.data.id = ''
                this.data.body = ''
                this.data.hint = ''
                this.data.position = ''
                this.data.scoreOver = ''
                this.data.answerType = ''
                this.data.possibleAnswers = []
                this.data.removedPossibleAnswers = []
                this.data.files = []
                this.data.removedFiles = []
                this.data.mainFiles = []
                this.data.correctPossibleAnswers = []
                this.data.editedPossibleAnswers = []
                this.data.mainPossibleAnswers = []
                this.data.possibleAnswer = {
                    position: null,
                    option: '',
                    id: '',
                    correct: false
                }
            },
            setAssessmentSectionQuestionData(question) {
                this.setShowQuestionFormToTrue()
                this.data.id = question.id
                this.data.body = question.body
                this.data.hint = question.hint
                this.data.position = question.position
                this.data.scoreOver = question.scoreOver
                this.data.answerType = question.answerType
                this.data.removedPossibleAnswers = question.removedPossibleAnswers
                this.data.correctPossibleAnswers = question.correctPossibleAnswers
                this.data.editedPossibleAnswers = question.editedPossibleAnswers
                this.data.mainPossibleAnswers = question.mainPossibleAnswers ?
                    question.mainPossibleAnswers : []
                this.data.files = question.files
                this.data.removedFiles = question.removedFiles
                this.data.mainFiles = question.mainFiles
                setTimeout(() => {
                    this.data.possibleAnswers = question.possibleAnswers                    
                }, 100);
            },
            scrollToForm() {
                if (this.$refs.questionsform) {
                    setTimeout(() => {
                        this.$refs.questionsform.scrollIntoView()                        
                    }, 100);
                }
            },
            scrollToPossibleAnswerForm() {
                bus.$emit('scrollToPossibleAnswerForm')
            },
            scrollToLast() {
                if (this.$refs.questions) {
                    this.$refs.questions.scrollBy(
                        - (this.$refs.questionsform.width + 30) ,0
                    )                    
                }
            },
            addQuestion() {
                this.$emit('addQuestion', this.data)
                this.clearAssessmentSectionQuestionData()
                this.scrollToLast()
            },
            checkActiveAnswerType(data) {
                return this.data.answerType === this.answerTypesPair[data]
            },
            clickedAnswerType(data) {
                this.data.answerType = this.answerTypesPair[data] ?? ''
            },
            optionExits(data) {
                return this.data.possibleAnswers.findIndex(
                    function(possibleAnswer){
                        return possibleAnswer.option === data.option
                    }
                ) > -1
            },
            addPossibleAnswer(data) {
                if (this.optionExits(data)) {
                    bus.$emit('assessmentError',{message: 'the option already exits.'})
                    return
                }

                this.data.possibleAnswers.push(data)
                this.updatePossibleAnswersPositions()
            },
        },
    }
</script>

<style lang="scss" scoped>

    .assessment-question-form{
        max-width: 300px;

        .section{
            @include form-section()
        }

        .add{
            display: flex;
            width: 100%;
            justify-content: space-between;
            padding-right: 10px;

            .plus{
                cursor: pointer;
                color: gray;
            }
        }

        .other-input{
            margin: 10px auto;
        }

        .main{
            padding: 10px;
            width: 100%;

            .files{
                padding: 10px;
                display: flex;
                align-items: center;
                width: 100%;
                overflow-x: auto;

                .file{
                    margin: 0 10px;
                    color: gray;
                    cursor: pointer;
                    font-size: 16px;
                }
            }

            .file-status{
                font-size: 12px;
                color: gray;
                width: 100%;
                margin-top: -10px;

                .icon{
                    float: right;
                    margin-top: 5px;
                    cursor: pointer;
                }
            }
        }

        .questions-form{
            margin: 0 10px 0 20px;
            background: wheat;
            width: 275px;
        }

        .type{
            width: 90%;
            margin: 10px auto;
            display: flex;
            overflow-x: scroll;

            .grey-button{
                margin-right: 10px;
                min-width: fit-content;
                padding: 5px 10px;
            }
        }

        .file-input{
            display: none;
        }

        .buttons{
            display: flex;
            width: 100%;
            padding-top: 0px;
            padding-bottom: 30px;
            justify-content: center;

            button{
                margin: 0 10px;
            }
        }
    }
</style>