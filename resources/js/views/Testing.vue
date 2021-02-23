<template>
    <number-input
        label="percent share"
        prepend="%"
    ></number-input>
</template>

<script>
import NumberInput from '../components/NumberInput'

    export default {
        data() {
            return {
                show: false,
                value: ''
            }
        },
        components: {
            NumberInput,
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