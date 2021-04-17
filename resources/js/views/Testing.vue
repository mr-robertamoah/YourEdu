<template>
    <work-answering-form
        :show="true"
    ></work-answering-form>
</template>

<script>
import WorkAnsweringForm from '../components/forms/WorkAnsweringForm'

    export default {
        data() {
            return {
                show: false,
                value: ''
            }
        },
        components: {
            WorkAnsweringForm,
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