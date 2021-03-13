<template>
    <div 
        class="assessment-section-form"
        ref="sections"
    >
        <assessment-section-badge
            v-for="(section, sectionIndex) of assessmentSections"
            :key="sectionIndex"
            :assessmentSection="section"
            :drag="assessmentSections.length > 1"
            @arrangeAssessmentSections="arrangeAssessmentSections"
            @editAssessmentSection="editAssessmentSection"
        ></assessment-section-badge>
        <div class="sections-form" 
            v-show="showSectionForm"
            ref="sectionsform"
        >
            <div class="section">Assessment Section Info</div>
            <div class="main">
                <text-input
                    :bottomBorder="true"
                    placeholder="assessment section name*"
                    v-model="data.name"
                    class="other-input"
                ></text-input>
                <text-textarea
                    :bottomBorder="true"
                    placeholder="instructions for this assessment section"
                    v-model="data.instruction"
                    class="other-input"
                ></text-textarea>
                <main-checkbox
                    v-model="data.random"
                    label="randomize questions"
                    title="check if you this section to have randomized questions"
                    class="other-input"
                ></main-checkbox>
                <main-checkbox
                    v-model="data.autoMark"
                    label="automatically mark questions with optional/possible answers"
                    title="check if you want questions with optional answers to be automatically marked"
                    class="other-input"
                ></main-checkbox>
                <main-checkbox
                    v-model="data.hasMaxQuestions"
                    label="section has max number of questions"
                    title="check if you want the section to have questions less than what is available"
                    class="other-input"
                ></main-checkbox>
                <number-input
                    :bottomBorder="true"
                    label="duration"
                    placeholder="duration"
                    prepend="in minutes"
                    v-model="data.duration"
                    :hasMax="false"
                    class="number-input"
                ></number-input>
                <number-input
                    v-if="data.hasMaxQuestions"
                    :bottomBorder="true"
                    placeholder="number of questions to be made available"
                    v-model="data.maxQuestions"
                    :inputMax="data.questions.length"
                    class="number-input"
                ></number-input>
                <main-select
                    class="other-input"
                    placeholder="set default answer type for this section"
                    backgroundColor='white'
                    :items="computedAnswerTypes"
                    v-model="data.answerType"
                ></main-select>
            </div>
            
            <div class="section add">
                Assessment Section Questions
                <font-awesome-icon :icon="['fa','plus']"
                    title="add new section"
                    class="plus"
                    @click="goToSectionQuestionForm"
                ></font-awesome-icon>
            </div>
            <question-form
                class="sections"
                @addQuestion="addQuestion"
                :questions="data.questions"
                :answerType="data.answerType"
                :autoMark="data.autoMark"
                :answerTypes="computedAnswerTypes"
                :answerTypesPair="computedAnswerTypesPair"
            ></question-form>

            <div class="buttons">
                <post-button
                    v-if="computedAddSectionButton"
                    buttonText="add section"
                    buttonStyle=''
                    @click="addSection"
                ></post-button>
            </div>
        </div>
    </div>
</template>

<script>
import TextTextarea from '../TextTextarea';
import QuestionForm from './QuestionForm';
import TextInput from '../TextInput';
import PostButton from '../PostButton';
import MainCheckbox from '../MainCheckbox';
import MainSelect from '../MainSelect';
import AssessmentSectionBadge from '../dashboard/AssessmentSectionBadge';
import NumberInput from '../NumberInput';
import {bus} from '../../app';
    export default {
        components: {
            QuestionForm,
            TextTextarea,
            TextInput,
            PostButton,
            MainSelect,
            MainCheckbox,
            AssessmentSectionBadge,
            NumberInput
        },
        props: {
            assessmentSections: {
                type: Array,
                default() {
                    return []
                }
            },
        },
        data() {
            return {
                data: {
                    id: '',
                    name: '',
                    instruction: '',
                    position: '',
                    duration: '',
                    autoMark: false,
                    hasMaxQuestions: false,
                    random: false,
                    maxQuestions: '',
                    questions: [],
                    removedQuestions: [],
                    editedQuestions: [],
                    answerType: '',
                    question: null
                },
                showSectionForm: false,
            }
        },
        watch: {
            sections(newValue) {
                if (newValue && newValue.length) {
                    this.toggleSectionFormMethod()
                }
            },
            showSectionForm(newValue) {
                if (newValue) {
                    this.scrollToSections()
                    this.scrollToForm()
                }
            }
        },
        mounted () {
            bus
            .$on('clearAssessmentSectionData', ()=> { 
                this.clearAssessmentSectionData()
            })
            .$on('toggleSectionForm', ()=> { 
                this.toggleSectionFormMethod()
            })
            .$on('arrangedQuestions', questions=>{
                console.log(questions);
            })
        },
        computed: {
            computedAddSectionButton() {
                return !this.data.id.length && this.computedHasData
            },
            computedEditSectionButton() {
                return this.data.id.length && this.computedHasData
            },
            computedHasData() {
                return this.data.name.length && this.data.questions.length
            },
            computedAnswerTypes() {
                return [
                    'true/false',
                    'option',
                    'short answer',
                    'long answer',
                    'number',
                    'flow',
                    'arrange',
                    'image',
                    'video',
                    'audio',
                    'file',
                ]
            },
            computedAnswerTypesPair() {
                return {
                    'true/false': 'true_false',
                    'option': 'option',
                    'short answer': 'short_answer',
                    'long answer': 'long_answer',
                    'number': 'number',
                    'flow': 'flow',
                    'arrange': 'arrange',
                    'image': 'image',
                    'video': 'video',
                    'audio': 'audio',
                    'file': 'file',
                }
            },
        },
        methods: {
            editAssessmentSection(assessmentSection) {
                this.setAssessmentSectionData(assessmentSection)
                this.scrollToForm()
            },
            arrangeAssessmentSections() {
                bus.$emit('arrangeAssessmentSections', this.assessmentSections)
            },
            updateQuestionsPositions() {
                this.data.questions.forEach(
                    function(question, questionIndex){
                        question.position = questionIndex + 1
                    }
                )
            },
            toggleSectionFormMethod() {
                this.showSectionForm = !this.showSectionForm
            },
            clearAssessmentSectionData() {
                this.data.id = ''
                this.data.name = ''
                this.data.instruction = ''
                this.data.position = ''
                this.data.autoMark = false
                this.data.hasMaxQuestions = false
                this.data.random = false
                this.data.maxQuestions = ''
                this.data.questions = []
                this.data.removedQuestions = []
                this.data.editedQuestions = []
                this.data.answerType = ''
                this.clearAssessmentSectionQuestionData()
            },
            clearAssessmentSectionQuestionData() {
                bus.$emit('clearAssessmentSectionQuestionData')
            },
            addQuestion(question) {
                this.data.questions.push(
                    this.mapAssessmentSectionQuestionData(_.cloneDeep(question))
                )
            },
            mapAssessmentSectionQuestionData(questionData) {
                return {
                    id: questionData.id.length ? questionData.id : 
                        `${Math.floor(Math.random() * 1000)}`,
                    body: questionData.body,
                    hint: questionData.hint,
                    position: questionData.position,
                    scoreOver: questionData.scoreOver,
                    answerType: questionData.answerType,
                    files: questionData.files,
                    removedFiles: questionData.removedFiles,
                    possibleAnswers: questionData.possibleAnswers,
                    removedPossibleAnswers: questionData.removedPossibleAnswers,
                    editedPossibleAnswers: questionData.editedPossibleAnswers,
                    correctPossibleAnswers: questionData.correctPossibleAnswers,
                }
            },
            updateAssessmentSectionData(data) {
                this.data.id = data.id
                this.data.name = data.name
                this.data.instruction = data.instruction
                this.data.position = data.position
                this.data.autoMark = data.autoMark
                this.data.hasMaxQuestions = data.maxQuestions.length ?
                    data.hasMaxQuestions : false
                this.data.random = data.random
                this.data.maxQuestions = data.maxQuestions
                this.data.questions = data.questions
                this.data.removedQuestions = data.removedQuestions
                this.data.editedQuestions = data.editedQuestions
                this.data.answerType = data.answerType
            },
            goToSectionQuestionForm() {
                bus.$emit('toggleQuestionForm')
                this.clearAssessmentSectionQuestionData()
            },
            scrollToForm() {
                if (this.$refs.sectionsform) {
                    setTimeout(() => {
                        this.$refs.sectionsform.scrollIntoView()                        
                    }, 100);
                }
            },
            scrollToSections() {
                if (this.$refs.sections) {
                    setTimeout(() => {
                        this.$refs.sections.scrollIntoView()                        
                    }, 100);
                }
            },
            scrollToLast() {
                if (this.$refs.sections) {
                    this.$refs.sections.scrollTo(
                        - (this.$refs.sectionsform.width + 20),0
                    )
                }
            },
            addSection() {
                this.$emit('addSection', this.data)
                this.clearAssessmentSectionData()
                this.scrollToLast()
            },
            
        },
    }
</script>

<style lang="scss" scoped>

    .assessment-section-form{
        max-width: 350px;

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

        .sections{
            display: flex;
            width: 90%;
            padding: 10px;
            overflow: hidden;
            overflow-x: scroll;
            margin: 10px auto;
        }

        .main{
            padding: 10px;
            width: 98%;

            .other-input,
            .number-input{
                margin: 10px auto;
            }
        }

        .sections-form{
            background: white;
            margin: 0 10px 0 20px;
        }

        .buttons{
            display: flex;
            width: 100%;
            padding-top: 40px;
            padding-bottom: 20px;
            justify-content: center;
        }
    }
</style>