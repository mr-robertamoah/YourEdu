<template>
    <div class="create-alt-wrapper"
        @click="askLoginRegister"
    >
        <div>create a post</div>
    </div>
</template>

<script>
    export default {
        methods: {
            askLoginRegister() {
                this.$emit('askLoginRegister','PostCreateAlt')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .create-alt-wrapper{
        width: 100%;
        min-height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid dimgrey;
        border-right: 2px solid rgb(105, 105, 105);

        div{
            padding: 10px;
            font-size: 16px;

            &:hover{
                transition: all 1s ease;
                box-shadow: 0 0 3px gray;
            }
        }
    }
</style>