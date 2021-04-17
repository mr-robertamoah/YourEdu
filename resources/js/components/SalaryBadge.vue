<template>
    <div class="salary-badge-wrapper">
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

</style>