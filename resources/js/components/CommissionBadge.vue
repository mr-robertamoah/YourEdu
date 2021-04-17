<template>
    <div class="commission-badge-wrapper"
        v-if="data.amount"
    >
        <div class="for">
            commission
        </div>
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
        computed: {
            computedDetails() {
                let str = ''
            
                if (this.data.percentageOwned.length) {
                    str += ` percentage owned: ${this.data.percentageOwned} \n`
                }

                return str
            }
        },
    }
</script>

<style lang="scss" scoped>

    .commission-badge-wrapper{
        max-width: 100px;
        margin-bottom: 10px;
        position: relative;
        background: $dashboard-section-second-background-color;
        border-radius: 5px;
        margin: 0 10px 10px 0;
        box-shadow: 0 0 1px grey;

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