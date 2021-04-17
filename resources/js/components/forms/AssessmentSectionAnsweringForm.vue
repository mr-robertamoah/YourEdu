<template>
    <div class="assessment-section-answering-form">
        <div class="timimg">
            <div class="assessment-timimg time">
                <div class="item">
                    Assessment:
                </div>
                <div class="item-time">
                    12 min
                </div>
            </div>
            <div class="assessment-section-timimg time">
                <div class="item">
                    Current Assessment Section
                </div>
                <div class="item-time">
                    6 min
                </div>
            </div>
        </div>

        <div class="assessment-sections">
            <assessment-section-answering-badge
                class="assessment-section"
                v-for="assessmentSection in assessment.asseessmentSections"
                :key="assessmentSection.id"
                :assessmentSection="assessmentSection"
            ></assessment-section-answering-badge>
        </div>

        <div class="links">

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