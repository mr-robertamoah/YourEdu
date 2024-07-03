<template>
    <div class="grey-button-wrapper"
        :class="{active}"
        @click="clickedAction"
    >
        {{loading ? '' : text}}
        <pulse-loader :loading="loading" :size="'6px'"></pulse-loader>
    </div>
</template>

<script>


    export default {
        props: {
            active: {
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
        components: {
            
        },
        data() {
            return {
                clicked: false
            }
        },
        methods: {
            clickedAction() {
                if (this.active) {
                    this.$emit('clickedAction', '')
                    return 
                }
                this.$emit('clickedAction', this.text)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .grey-button-wrapper{
        padding: 5px;
        font-size: 14px;
        cursor: pointer;
        width: fit-content;
        min-width: 35px;
        color: white;
        background-color: gray;
        border-radius: 10px;
        text-align: center;

        &:hover{
            transition: all 1s ease;
            background-color: green;
            box-shadow: 0 0 3px gray;
        }
    }

    .active{
        transition: all 1s ease;
        background-color: green;
        box-shadow: 0 0 3px gray;
    }

@media screen and (max-width: 800px) {
    
    .grey-button-wrapper{
        font-size: 12px;
    }
}
</style>