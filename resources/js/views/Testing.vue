<template>
    <!-- <div class="bg-white w-screen h-screen flex justify-center items-center">
        <resizing-component :resize="resize">
            <div class="h-20 w-20 bg-black"></div>
        </resizing-component>
    </div> -->
    <lesson-board
    ></lesson-board>
</template>

<script>
import LessonBoard from '../components/LessonBoard.vue'
import ResizingComponent from '../components/specials/ResizingComponent.vue'

    export default {
        data() {
            return {
                resize: true,
                value: ''
            }
        },
        components: {
            ResizingComponent,
            LessonBoard,
        },
        methods: {
            clickedTesting(){
                this.show = true
            },
        },
    }
</script>

<style lang="scss" scoped>
    .testing{
        width: 100%;
        height: 100vh;
    }
</style>