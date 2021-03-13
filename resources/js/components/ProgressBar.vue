<template>
    <div class="progress-wrapper">
        <div class="progress-bar progress-bar-animated progress-bar-striped" 
            role="progressbar"
            :style="{width:computedProgress}"
        >
            {{(progress < 100) ? computedProgress : 'completed'}}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            progress: {
                type: Number,
                default: 0
            },
        },
        computed: {
            computedProgress() {
                return `${Math.round(this.progress)}%` 
            }
        },
    }
</script>

<style lang="scss" scoped>

    .progress-wrapper{
        width: 100%;
        border: 1px solid rgba(105, 105, 105, .6);


        .progress-bar{
            background-color: $color-primary;
        }
    }
</style>