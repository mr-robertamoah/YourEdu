<template>
    <div class="assessment-section-answering-form pb-2 flex flex-col justify-center">
        <assessment-section-answering-badge
            class="h-full flex-shrink"
            :assessmentSection="currentAssessmentSection"
            :answers="answers"
            :sectionAnswers="computedWorkAnswers"
            :computedAccount="computedAccount"
            :assessmentId="assessment.id"
            @answered="answered"
            @initiated="initiatedAssessmentSection"
            @clickedResponse="clickedPopupResponse"
            @noTimeLeft="noTimeLeft"
            @fewMinutesMore="fewMinutesMore"
        ></assessment-section-answering-badge>

        <navigation-component
            @clickedNavigator="clickedSectionNavigator"
            :isFirst="computedIsFirstSection"
            :isLast="computedIsLastSection"
            :buttons="computedPossiblyDone ? ['done'] : []"
        ></navigation-component>
    </div>
</template>

<script>
import AssessmentSectionAnsweringBadge from '../dashboard/AssessmentSectionAnsweringBadge.vue';
import NavigationComponent from '../NavigationComponent.vue';
    export default {
        components: {
            AssessmentSectionAnsweringBadge,
            NavigationComponent,
        },
        props: {
            assessment: {
                type: Object,
                default() {
                    return null
                }
            },
            numberOfQuestions: {
                type: Number,
                default: 0
            },
            work: {
                type: Object,
                default() {
                    return null
                }
            },
            computedAccount: {
                type: Object,
                default() {
                    return null
                }
            },
            answers: {
                type: Array,
                default() {
                    return []
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

                    this.initiate()
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
            },
            computedIsFirstSection() {
                return this.firstAssessmentSectionId === this.currentAssessmentSection?.id
            },
            computedIsLastSection() {
                return this.lastAssessmentSectionId === this.currentAssessmentSection?.id
            },
            computedWorkAnswers() {
                return this.work ? 
                    this.work.answers.filter(answer=>{
                        return answer.assessmentSectionId == this.currentAssessmentSection?.id
                    }) :
                    []
            },
            computedPossiblyDone() {
                return this.work.answers.filter(answer=>{
                    return !! answer
                }).length === this.numberOfQuestions
            },
        },
        methods: {
            initiate() {
                this.firstAssessmentSectionId = this.assessment.assessmentSections[0].id
                this.lastAssessmentSectionId = this.assessment
                    .assessmentSections[this.assessment.assessmentSections.length - 1].id

                this.clickedSectionNavigator('next')
            },
            initiatedAssessmentSection(section) {
                this.$emit('initiated', section)
            },
            clickedSectionNavigator(text) {
                if (text === 'done') {
                    this.$emit('goToStep', 0)
                    return
                }

                if (text === 'previous') {
                    this.goToPrevious()
                    return
                }

                this.goToNext()
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
            answered(data) {
                this.$emit('answered', data)
            },
            fewMinutesMore(data) {
                this.$emit('fewMinutesMore', data)
            },
            noTimeLeft(data) {
                this.$emit('noTimeLeft', data)
            },
            clickedPopupResponse(data) {
                this.$emit('clickedResponse', data)
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
    }
</style>