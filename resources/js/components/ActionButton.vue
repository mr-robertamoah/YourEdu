<template>
    <div class="action-button-wrapper"
        :class="{green:green,red:red,clicked:clicked}"
        @click="clickedAction"
    >
        <slot name="icon"></slot>
    </div>
</template>

<script>
    export default {
        props: {
            green: {
                type: Boolean,
                default: false
            },
            red: {
                type: Boolean,
                default: false
            },
            prevent: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                clicked: false
            }
        },
        methods: {
            clickedAction() {
                if (!this.prevent) {
                    this.$emit('click')
                    this.clicked = !this.clicked
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

    .action-button-wrapper{
        padding: 5px;
        font-size: 16px;

        &:hover{
            transition: all 1s ease;
            box-shadow: 0 0 3px gray;
        }
    }

    .green{
        color: lighten($color: green, $amount: 10);

        &:hover{
            color: green;
        }
    }

    .red{
        color: lighten($color: red, $amount: 10);

        &:hover{
            color: red;
        }
    }

    .clicked{
        transition: all 1s ease;
        box-shadow: 0 0 3px gray;
    }

@media screen and (max-width: 800px) {
    
    .action-button-wrapper{
        font-size: 12px;
    }
}
</style>