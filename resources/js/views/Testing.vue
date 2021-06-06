<template>
    <!-- <work-answering-form
        :show="true"
    ></work-answering-form> -->
    <assessment-single>
        
    </assessment-single>
</template>

<script>
import WorkAnsweringForm from '../components/forms/WorkAnsweringForm'
import AssessmentSingle from '../components/AssessmentSingle'

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