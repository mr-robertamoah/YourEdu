<template>
    <div class="discount-badge-wrapper">
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
import { strings } from '../services/helpers';
    export default {
        mixins: [paymentTypeMixin],
        computed: {
            computedDetails() {
                let str = `name: ${strings.trim(this.data.name, 10)} \n`
            
                if (this.data.percentage.length) {
                    str += ` percentage: ${this.data.percentage} \n`
                }
                
                if (this.data.discountedPrice.length) {
                    str += ` discounted price: ${this.data.discountedPrice} \n`
                }

                if (this.data.expiresAt.length) {
                    str += `expires at: ${this.data.expiresAt}`
                }

                return str
            }
        },
    }
</script>

<style lang="scss" scoped>

    .discount-badge-wrapper{
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