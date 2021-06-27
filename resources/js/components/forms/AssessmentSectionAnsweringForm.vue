<template>
    <div class="assessment-section-answering-form">
        <assessment-section-answering-badge
            class="h-full"
            :assessmentSection="currentAssessmentSection"
        ></assessment-section-answering-badge>

        <div v-if="! currentAssessmentSection">
            sorry😞, there is no section for this assessment
        </div>

        <div class="flex-shrink-0 flex justify-around">
            <button 
                class="text-gray-500 disabled:bg-gray-800 disabled:text-gray-200 p-2 border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded"
                @click="clickedSectionNavigator('previous')"
                :disabled="firstAssessmentSectionId === currentAssessmentSection.id"
            >previous</button>
            <button 
                class="text-gray-500 disabled:bg-gray-800 disabled:text-gray-200 p-2 border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded"
                @click="clickedSectionNavigator('next')"
                :disabled="lastAssessmentSectionId === currentAssessmentSection.id"
            >next</button>
        </div>
    </div>
</template>

<script>
import AssessmentSectionAnsweringBadge from '../dashboard/AssessmentSectionAnsweringBadge';
    export default {
        components: {
            AssessmentSectionAnsweringBadge,
        },
        props: {
            assessment: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        watch: {
            assessment: {
                immediate: true,
                handler(newValue, oldValue) {
                    if (! newValue) {
                        return
                    }
                    
                    if (! newValue.assessmentSections.length) {
                        return
                    }

                    this.currentAssessmentSection = newValue.assessmentSections[0]
                    this.firstAssessmentSectionId = newValue.assessmentSections[0].id
                    this.lastAssessmentSectionId = newValue
                        .assessmentSections[newValue.assessmentSections.length - 1].id
                }
            }
        },
        data() {
            return {
                currentAssessmentSection: null,
                firstAssessmentSectionId: null,
                lastAssessmentSectionId: null,
            }
        },
        computed: {
            computedCurrentAssessmentSectionIndex() {
                return this.assessment.assessmentSections.indexOf(
                    this.currentAssessmentSection
                )
            }
        },
        methods: {
            clickedSectionNavigator(text) {
                if (text === 'next') {
                    this.goToNext()
                    return
                }

                this.goToPrevious()
            },
            goTo(number) {
                this.currentAssessmentSection = this.assessment
                    .assessmentSections[this.computedCurrentAssessmentSectionIndex + number]
            },
            goToNext() {
                this.goTo(1)
            },
            goToPrevious() {
                this.goTo(-1)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .assessment-section-answering-form{
        

        .main-section{
            width: 100%;
            background: aquamarine;
            color: gray;
            font-size: 14px;
            padding: 5px;
            margin: 0 0 10px;
        }

        .assessment-sections{

            .assessment-section{

            }
        }
    }
</style>