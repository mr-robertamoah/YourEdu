<template>
    <div class="academic-year-section-wrapper" 
        v-if="section.name"
        @click="clickedSection"
    >
        <div class="name">{{section.name}}</div>
        <div class="dates"
            v-if="computedStartDate.length && computedEndDate.length"
        >
            from {{computedStartDate}} to {{computedEndDate}}
        </div>
        <div class="close"
            v-if="hasClose"
            @click="clickedRemoveSection"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
        <div class="promotion"
            v-if="section.promotion"
        >
            promotional
        </div>
    </div>
</template>

<script>
import { dates } from '../../services/helpers'
    export default {
        props: {
            section: {
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
            computedStartDate() {
                return this.hasClose ? this.section.startDate : 
                    dates.dateReadable(this.section.startDate)
            },
            computedEndDate() {
                return this.hasClose ? this.section.endDate : 
                    dates.dateReadable(this.section.endDate)
            },
        },
        methods: {
            clickedRemoveSection() {
                this.$emit('clickedRemoveSection',this.section)
            },
            clickedSection() {
                this.$emit('clickedSection',this.section)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .academic-year-section-wrapper{
        font-size: 14px;
        padding: 5px;
        background: mintcream;
        cursor: pointer;
        border-radius: 5px;
        box-shadow: 0 0 2px grey;
        position: relative;
        margin: 0 10px 10px 0;

        .name{
            text-align: center;
        }

        .dates{
            color: gray;
            font-size: 12px;
        }

        .close{
            font-size: 14px;
            color: red;
            position: absolute;
            top: 0;
            right: 0;
            padding: 5px;
        }

        .promotion{
            color: green;
            font-size: 10px;
            text-align: end;
        }
    }
</style>