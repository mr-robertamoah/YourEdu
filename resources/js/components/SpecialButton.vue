<template>
    <button class="btn btn-size min-w-content max-h-content" :title="titleText"
        @click="onClick"
    >
        {{buttonText}}
        <slot  name="loader"></slot>
    </button>
</template>

<script>
    export default {
        props: {
            buttonText: {
                type: String,
                default: 'click'
            },
            titleText: {
                type: String,
                default: ''
            },
        },
        methods: {
            onClick() {
                this.$emit('click')
            }
        },
    }
</script>

<style lang="scss" scoped>
    $special-button-color: azure;
    $special-button-background: azure;
    $special-button-border: rebeccapurple;

    button{
        border: 1px solid $special-button-border;
        box-shadow: -0.5px -0.5px 0.5px $special-button-border;
        border-radius: 5px;
        background-color: $special-button-background;
    }

    button:hover{
        box-shadow: 1px 1px 1px $special-button-border, -1px -1px 1px $special-button-border;
        transition: all .5s ease;
    }

    .btn-size{
        font-size: 16px;
    }

@media screen and (max-width: 800px){
    .btn-size{
        font-size: 13px;
    }
}
</style>