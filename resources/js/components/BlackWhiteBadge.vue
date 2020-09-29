<template>
    <div class="badge-wrapper" @click="clicked">
        {{text}}
        <slot name="badge"></slot>
    </div>
</template>

<script>
    export default {
        props: {
            text: {
                type: String,
                default: ''
            },
        },
        methods: {
            clicked() {
                this.$emit('click')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .badge-wrapper{
        width: auto;
        padding: 5px;
        border-radius: 1px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        position: absolute;
        font-size: 14px;
        cursor: pointer;
        z-index: 1;
    }

@media screen and (max-width: 800px) {
    
    .badge-wrapper{
        font-size: 12px;
    }
}
</style>