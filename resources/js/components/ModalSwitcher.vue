<template>
    <div v-if="computedShow">
        <discussion-modal
            :show="computedDiscussion"
            v-if="computedDiscussion"
            :itemData="itemData"
        ></discussion-modal>
        <class-modal
            :show="computedClass"
            v-else-if="computedClass"
            :itemData="itemData"
        ></class-modal>
        <lesson-modal
            :show="computedLesson"
            v-else-if="computedLesson"
            :itemData="itemData"
        ></lesson-modal>
        <course-modal
            :show="computedCourse"
            v-else-if="computedCourse"
            :itemData="itemData"
        ></course-modal>
        <catch-all
            v-else
        ></catch-all>
    </div>
</template>

<script>
import DiscussionModal from './DiscussionModal';
import CourseModal from './CourseModal';
import LessonModal from './LessonModal';
import ClassModal from './ClassModal';
import CatchAll from '../views/CatchAll';
    export default {
        components: {
            CatchAll,
            ClassModal,
            LessonModal,
            CourseModal,
            DiscussionModal,
        },
        props: {
            itemData: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        computed: {
            computedDiscussion() {
                return (this.$route.name === 'item' && this.$route.params.item === 'discussion') ||
                    (this.itemData && this.itemData.item === 'discussion')
            },
            computedCourse() {
                return (this.$route.name === 'item' && this.$route.params.item === 'course') ||
                    (this.itemData && this.itemData.item === 'course')
            },
            computedLesson() {
                return (this.$route.name === 'item' && this.$route.params.item === 'lesson') ||
                    (this.itemData && this.itemData.item === 'lesson')
            },
            computedClass() {
                return (this.$route.name === 'item' && this.$route.params.item === 'class') ||
                    (this.itemData && this.itemData.item === 'class')
            },
            computedShow() {
                return this.$route.name === 'item'
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>