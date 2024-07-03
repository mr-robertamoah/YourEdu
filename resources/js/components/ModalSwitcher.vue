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
import DiscussionModal from './DiscussionModal.vue';
import CourseModal from './CourseModal.vue';
import LessonModal from './LessonModal.vue';
import ClassModal from './ClassModal.vue';
import CatchAll from '../views/CatchAll.vue';
import { useRoute } from 'vue-router';
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
            computedRoute() {
                return useRoute()
            },
            computedDiscussion() {
                return (this.computedRoute.name === 'item' && this.computedRoute.params.item === 'discussion') ||
                    (this.itemData && this.itemData.item === 'discussion')
            },
            computedCourse() {
                return (this.computedRoute.name === 'item' && this.computedRoute.params.item === 'course') ||
                    (this.itemData && this.itemData.item === 'course')
            },
            computedLesson() {
                return (this.computedRoute.name === 'item' && this.computedRoute.params.item === 'lesson') ||
                    (this.itemData && this.itemData.item === 'lesson')
            },
            computedClass() {
                return (this.computedRoute.name === 'item' && this.computedRoute.params.item === 'class') ||
                    (this.itemData && this.itemData.item === 'class')
            },
            computedShow() {
                return this.computedRoute.name === 'item'
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>