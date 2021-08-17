<template>
    <div class="p-2">
        <assessment-section-information-badge
            :assessmentSection="assessmentSection"
            class="flex-shrink-0"
        ></assessment-section-information-badge>
        
        <div class="min-h-90vh flex-shrink mb-2 overflow-y-auto p-2 overflow-x-hidden">
            <answer-marking-badge
                v-for="answer in answers"
                :key="answer.id"
                :answer="answer"
                :providedMark="getSpecificMark(answer)"
                @marked="marked"
                @performActionOnMark="marked"
            ></answer-marking-badge>
        </div>
    </div>
</template>

<script>
import FilesPreviewBackend from '../FilesPreviewBackend.vue'
import AnswerMarkingBadge from './AnswerMarkingBadge.vue'
import AssessmentSectionInformationBadge from './AssessmentSectionInformationBadge.vue'
    export default {
        components: { 
            AnswerMarkingBadge,
            FilesPreviewBackend,
            AssessmentSectionInformationBadge,
        },
        props: {
            answers: {
                type: Array,
                default() {
                    return null
                }
            },
            marks: {
                type: Array,
                default() {
                    return null
                }
            },
            assessmentSection: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        computed: {

        },
        methods: {
            marked(data) {
                if (! ['update', 'delete'].includes(data.type)) {
                    data.assessmentSectionId = this.assessmentSection.id
                }
                
                this.$emit('marked', data)
            },
            getSpecificMark(answer) {
                return this.marks.find(mark=>{
                    return mark.answerId == answer.id
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>