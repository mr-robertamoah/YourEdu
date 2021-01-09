<template>
    <div class="price-badge-wrapper"
        v-if="data.amount"
    >
        <div class="for">
            {{data.for}}
        </div>
        <div class="close"
            v-if="hasClose"
            @click="clickedRemovePrice"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
        <div class="details" v-if="computedDetails.length">
            {{computedDetails}}
        </div>
    </div>
</template>

<script>
import paymentTypeMixin from '../mixins/PaymentType.mixin';
    export default {
        mixins: [paymentTypeMixin],
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
        background: $dashboard-section-second-background-color;
        border-radius: 5px;
        margin: 0 10px 10px 0;
        box-shadow: 0 0 1px grey;

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

        .details{
            font-size: 12px;
            color: gray;
            width: 100%;
            text-align: center;
            text-transform: lowercase;
        }
    }
</style>