<template>
    <div class="check-input-wrapper">
        <input type="radio"
            :value="value"
            @change="change"
            v-model="inputCheck"
            ref="checkinput"
        >
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                inputCheck: ''
            }
        },
        methods: {
            change() {
                this.$emit('change',this.$refs.checkinput.value)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .check-input-wrapper{

    }
</style>