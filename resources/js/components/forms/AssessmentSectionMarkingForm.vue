<template>
    <div class="w-full mt-4">
        <template v-if="computedNotAddedby">
            <assessment-section-marking-badge
                class="min-h-90vh flex flex-col"
                :assessmentSection="currentAssessmentSection"
                :answers="computedAnswers"
                :marks="computedMarks"
                @marked="marked"
            ></assessment-section-marking-badge>

            <navigation-component
                @clickedNavigator="clickedSectionNavigator"
                :isFirst="computedIsFirstSection"
                :isLast="computedIsLastSection"
                :buttons="computedPossiblyDone ? ['done'] : []"
            ></navigation-component>
        </template>
        <div class="text-gray-500 text-center text-sm">
            sorry, you cannot mark your own work 😏
        </div>
    </div>
</template>

<script>
import AssessmentSectionMarkingBadge from '../dashboard/AssessmentSectionMarkingBadge.vue'
import NavigationComponent from '../NavigationComponent.vue'
    export default {
        components: { 
            AssessmentSectionMarkingBadge ,
            NavigationComponent,
        },
        props: {
            marks: {
                type: Array,
                default() {
                    return null
                }
            },
            work: {
                type: Object,
                default() {
                    return null
                }
            },
            assessment: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                currentAssessmentSection: null,
                firstAssessmentSectionId: null,
                lastAssessmentSectionId: null,
            }
        },
        watch: {
            work: {
                immediate: true,
                handler(newValue) {
                    this.initiate()
                }
            }
        },
        computed: {
            computedNotAddedby() {
                if (this.work?.addedby.userId == this.getUser?.id) {
                    return true
                }

                return false
            },
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
            computedAnswers() {
                if (! this.work || ! this.currentAssessmentSection?.id) {
                    return null
                }

                return this.work.answers.filter(answer=>{
                    return answer.assessmentSectionId == this.currentAssessmentSection.id
                })
            },
            computedMarks() {
                return this.marks.filter(mark=>{
                    return mark.assessmentSectionId == this.currentAssessmentSection.id
                })
            },
            computedPossiblyDone() {
                return this.work.answers.filter(answer=>{
                    return answer.mark && answer.isMarker
                }).length === this.work.answers.length
            },
        },
        methods: {
            initiate() {
                this.firstAssessmentSectionId = this.assessment.assessmentSections[0].id
                this.lastAssessmentSectionId = this.assessment
                    .assessmentSections[this.assessment.assessmentSections.length - 1].id

                this.clickedSectionNavigator('next')
            },
            clickedSectionNavigator(text) {
                if (text === 'done') {
                    this.$emit('goToStep', 3)
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
            marked(data) {
                this.$emit('marked', data)
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>