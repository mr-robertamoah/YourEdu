<template>
    <div class="subscription-badge-wrapper" 
        v-if="subscription.name"
    >
        <div class="name">{{subscription.name}}</div>
        <div class="for">
            {{subscription.for}}
        </div>
        <div class="close"
            v-if="hasClose"
            @click="clickedRemoveSubscription"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
        <div class="details" v-if="computedDetails.length">
            {{computedDetails}}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            subscription: {
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
        computed: {
            computedDetails() {
                return `amount: ${this.subscription.amount} per ${this.subscription.period}` 
            }
        },
        methods: {
            clickedRemoveSubscription() {
                this.$emit('clickedRemoveSubscription',this.subscription)
            },
            clickedSubscription() {
                this.$emit('clickedSubscription',this.subscription)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .subscription-badge-wrapper{
        max-width: 100px;
        padding: 5px;
        position: relative;

        .name{
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
            font-size: 14px;
            color: gray;
            width: 100%;
            text-align: center;
        }
    }
</style>