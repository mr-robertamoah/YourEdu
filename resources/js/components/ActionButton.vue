<template>
    <div class="action-button-wrapper"
        :class="{green:green,red:red,clicked:clicked,background:background}"
        @click="clickedAction"
    >
        {{loading ? '' : text}}
        <slot name="icon" v-if="!loading"></slot>
        <pulse-loader :loading="loading" :size="'6px'"></pulse-loader>
    </div>
</template>

<script>
    export default {
        props: {
            green: {
                type: Boolean,
                default: false
            },
            background: {
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
            loading: {
                type: Boolean,
                default: false
            },
            text: {
                type: String,
                default: ''
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
                    this.$emit('click', this.text)
                    this.clicked = !this.clicked
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

    .action-button-wrapper{
        padding: 5px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 10px;
        width: fit-content;
        min-width: 40px;
        text-align: center;

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

    .background{
        background-color: honeydew;
    }

@media screen and (max-width: 800px) {
    
    .action-button-wrapper{
        font-size: 12px;
    }
}
</style>