<template>
    <draggable-component
        v-if="arrangable"
        :dataTransfer="{
                fromIndex: index
            }"
    >
        <droppable-component
            @drop="move"
            :dataTransfer="{
                toIndex: index
            }"
        >
            <div class="arrangable-badge">
                <div class="name">
                    {{computedName}}
                </div>
                <div class="detail">
                    {{computedDetail}}
                </div>
            </div>
        </droppable-component>
    </draggable-component>
</template>

<script>
import DraggableComponent from '../specials/DraggableComponent.vue';
import DroppableComponent from '../specials/DroppableComponent.vue';
    export default {
        components: {
            DroppableComponent,
            DraggableComponent,
        },
        props: {
            arrangable: {
                type: Object,
                default() {
                    return null
                }
            },
            index: {
                type: Number,
                default: null
            }
        },
        computed: {
            computedName() {
                return this.arrangable.name ? this.arrangable.name :
                    this.arrangable.body ? this.arrangable.body : ''
            },
            computedDetail() {
                return this.arrangable.instruction ? this.arrangable.instruction :
                    this.arrangable.hint ? this.arrangable.hint : ''
            },
        },
        methods: {
            move(data) {
                console.log(data);
                this.$emit('move', data)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .arrangable-badge{
        padding: 10px;
        background: white;
        background: linear-gradient(
            to right bottom,
            rgba(41, 235, 209, .25),
            wheat
        );
        backdrop-filter: blur(2px);
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: move;

        .name{
            font-size: 14px;
            width: 90%;
            margin: 0 auto 10px;
            text-align: center;
        }

        .detail{
            font-size: 12px;
            color: gray;
            padding: 5px;
        }
    }
</style>