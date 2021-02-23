<template>
    <div class="fee-badge-wrapper">
        <div class="close"
            v-if="hasClose"
            @click="clickedRemoveData"
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
    }
</script>

<style lang="scss" scoped>

    .fee-badge-wrapper{
        max-width: 100px;
        padding: 5px;
        position: relative;
        background: $dashboard-section-second-background-color;
        border-radius: 5px;
        margin: 0 10px 10px 0;
        box-shadow: 0 0 1px grey;


        .details{
            font-size: 12px;
            color: gray;
            width: 100%;
            text-align: center;
            text-transform: lowercase;
        }

        .close{
            font-size: 14px;
            color: red;
            position: absolute;
            top: 0;
            right: 0;
            padding: 5px 5px 10px 10px;
            cursor: pointer;
        }
    }
</style>