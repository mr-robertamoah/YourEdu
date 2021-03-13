<template>
    <div
        :draggable="drag"
        @dragover.prevent
        @dragenter.prevent
        @dragstart.self.stop="dragStart($event)"
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
            drag: {
                type: Boolean,
                default: true
            },
            effectAllowed: {
                type: String,
                default: 'move'
            },
            dropEffect: {
                type: String,
                default: 'move'
            },
        },
        methods: {
            dragStart(event) {
                if (!this.drag) {
                    return
                }
                event.dataTransfer.effectAllowed = this.effectAllowed
                event.dataTransfer.dropEffect = this.dropEffect

                event.dataTransfer.setData('data', JSON.stringify(this.dataTransfer))
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>