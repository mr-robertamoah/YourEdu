<template>
    <div class="assessment-section-wrapper" v-if="assessmentSection">
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
        <div class="name">
            {{assessmentSection.name}}
        </div>
        <div class="instruction">
            {{assessmentSection.instruction}}
        </div>
        <div class="questions" 
            v-if="assessmentSection.questions"
        >
            <question-badge
                v-for="(question, questionIndex) in assessmentSection.questions"
                :key="questionIndex"
                :question="question"
            ></question-badge>
        </div>
        <div class="questions edited" 
            v-if="assessmentSection.editedQuestions"
        >
            <question-badge
                v-for="(question, questionIndex) in assessmentSection.editedQuestions"
                :key="questionIndex"
                :question="question"
            ></question-badge>
        </div>
        <div class="questions removed" 
            v-if="assessmentSection.removedQuestions"
        >
            <question-badge
                v-for="(question, questionIndex) in assessmentSection.removedQuestions"
                :key="questionIndex"
                :question="question"
            ></question-badge>
        </div>
    </div>
</template>

<script>
import QuestionBadge from './QuestionBadge';
    export default {
        components: {
            QuestionBadge,
        },
        props: {
            assessmentSection: {
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
                this.$emit('arrangeAssessmentSections')
            },
            clickedAssessmentSection() {
                this.$emit('editAssessmentSection', this.assessmentSection)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .assessment-section-wrapper{
        min-width: 100%;
        position: relative;
        margin: 0 10px;
        width: 100%;

        .drag{
            cursor: grab;
            position: absolute;
            right: 10px;
            font-size: 20px;
            color: gray;
        }

        .name{

        }

        .instruction{

        }

        .questions{
            display: flex;
            width: 100%;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 10px;
            
            .question{
                
            }
        }

        .questions.edited{
            
        }

        .questions.removed{
            
        }
    }
</style>