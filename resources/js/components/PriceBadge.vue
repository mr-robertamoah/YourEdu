<template>
    <div class="price-badge-wrapper"
        v-if="price.amount"
    >
        <div class="amount">
            {{`amount: ${price.amount}`}}
        </div>
        <div class="for">
            {{price.for}}
        </div>
        <div class="close"
            v-if="hasClose"
            @click="clickedRemovePrice"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            price: {
                type: Object,
                default(){
                    return {}
                }
            },
            hasClose: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            clickedRemovePrice() {
                this.$emit('clickedRemovePrice', this.price)
            },
            clickedPrice() {
                this.$emit('clickedPrice', this.price)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .price-badge-wrapper{
        max-width: 100px;
        margin-bottom: 10px;
        position: relative;

        .amount{
            width: 100px;
            text-align: start;
            text-overflow: ellipsis;
            overflow: hidden;
            font-size: 12px;
        }

        .for{
            font-size: 10px;
            color: gray;
            width: 100%;
            text-align: end;
            font-style: italic;
        }

        .close{
            font-size: 14px;
            color: red;
            position: absolute;
            top: 0;
            right: 0;
            padding: 5px;
            cursor: pointer;
        }
    }
</style>