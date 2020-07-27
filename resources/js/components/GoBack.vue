<template>
    <div class="back" title="go back"
        @click="goBack"
    >
        <font-awesome-icon :icon="['fas','chevron-left']"></font-awesome-icon>
    </div>
</template>

<script>
    export default {
        methods: {
            goBack() {
                this.$router.go(-1)
            }
        },
    }
</script>

<style lang="scss" scoped>
    .back{
        font-size: 20px;
        padding: 5px;

        &:hover{
            color: green;
            box-shadow: 0 0 2px gray;
        }
    }
</style>