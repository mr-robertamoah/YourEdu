<template>
    <fade-right>
        <template slot="transition" v-if="computedState.length">
            <div class="state-badge" :class="{danger: computedDanger}">
                {{computedState}}
            </div>
        </template>
    </fade-right>
</template>

<script>
import FadeRight from '../transitions/FadeRight.vue';
    export default {
        components: {
            FadeRight,
        },
        props: {
            state: {
                type: String,
                default: ''
            }
        },
        computed: {
            computedState() {
                return this.state ? this.state : '' 
            },
            computedDanger() {
                return this.computedState === 'DELETED' || this.computedState === 'DECLINED'
                    ? true : false
            },
        },
    }
</script>

<style lang="scss" scoped>


    .state-badge{
        background: green;
        color: white;
        padding: 5px;
        font-size: 14px;
        text-transform: lowercase;
        width: fit-content;
        border-radius: 5px;
        float: right;
        min-width: 100px;
        text-align: center;

        &.danger{
            background: red;
        }
    }
</style>