<template>
    <div
        @dragover.prevent
        @dragenter.prevent
        @drop.stop="drop($event)"
    >
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            dataTransfer: {
                type: Object,
                default() {
                    return {}
                }
            },
        },
        methods: {
            drop(event) {
                if (!event.dataTransfer.getData('data')) {
                    return
                }
                let data = {
                    ...JSON.parse(event.dataTransfer.getData('data')),
                    ...this.dataTransfer
                }
                this.$emit('drop', data)
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>