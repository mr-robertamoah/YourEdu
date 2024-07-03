<template>
    <div class="assessment-section-badge-wrapper" 
        v-if="assessmentSection"
        @dblclick="dbclickedAssessmentSection"
    >
        <div class="other">
            <font-awesome-icon
                class="close"
                :icon="['fa', 'times']"
                @click="clickedClose"
            ></font-awesome-icon>
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
        </div>
        <div class="name">
            {{assessmentSection.name}}
        </div>
        <div class="instruction">
            {{assessmentSection.instruction}}
        </div>
        <div class="question" v-if="assessmentSection.questions.length">
            Questions:
        </div>
        <div class="questions" 
            v-if="assessmentSection.questions.length"
        >
            <question-badge
                v-for="(question, questionIndex) in assessmentSection.questions"
                :key="questionIndex"
                :question="question"
                :close="false"
            ></question-badge>
        </div>
    </div>
</template>

<script>
import QuestionBadge from './QuestionBadge.vue';
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
            removed: {
                type: Boolean,
                default: false
            },
        },
        methods: {
            clickedDrag() {
                this.$emit('arrangeAssessmentSections')
            },
            dbclickedAssessmentSection() {
                this.$emit('editAssessmentSection', this.assessmentSection)
            },
            clickedClose() {
                if (this.removed) {
                    this.$emit('undoAssessmentSectionRemoval', this.assessmentSection)
                    return
                }
                this.$emit('removeAssessmentSection', this.assessmentSection)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .assessment-section-badge-wrapper{
        min-width: 100%;
        position: relative;
        margin: 0 10px; 
        background: white;
        padding: 20px;
        max-height: 450px;
        border-radius: 10px;
        overflow-y: auto;
        cursor: pointer;

        .other{
            position: absolute;
            right: 10px;
            top: 5px;

            .drag{
                cursor: grab;
                font-size: 20px;
                color: gray;
            }

            .close{
                font-size: 30px;
                color: gray;
                padding: 5px;
                cursor: pointer;

                &:hover{
                    color: red;
                }
            }
        }

        .name{
            font-size: 14px;
            color: black;
        }

        .instruction{
            font-size: 12px;
            color: gray;
            width: 100%;
            text-align: center;
            margin: 5px;
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