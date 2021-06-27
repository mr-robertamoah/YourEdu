<template>
    <the-board></the-board>
</template>

<script>
import WorkAnsweringForm from '../components/forms/WorkAnsweringForm'
import AssessmentSingle from '../components/AssessmentSingle'
import TheBoard from '../components/TheBoard.vue'

    export default {
        data() {
            return {
                show: false,
                value: ''
            }
        },
        components: {
            WorkAnsweringForm,
            AssessmentSingle,
            TheBoard,
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