<template>
    <div class="item-badge-wrapper" 
        v-if="item"
        @click.self="clickedItem"
        :class="{active: hasRemove}"
    >
        <div class="created"
            @click="clickedItem"
        >
            {{computedCreatedAt}}
        </div>
        <div class="body" v-if="computedItem.length"
            @click="clickedItem"
        >
            {{computedItem}}
        </div>
        <div class="description" v-if="computedDescription && computedDescription.length"
            @click="clickedItem"
        >
            {{computedDescription}}
        </div>
        <div class="details" v-if="computedDetails.length"
            @click="clickedItem"
        >
            {{computedDetails}}
        </div>
        <div class="close" 
            @click="clickedRemoveItem"
            v-if="hasRemove"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
    </div>
</template>

<script>
import { dates, strings } from '../../services/helpers'
    export default {
        props: {
            item: {
                type: Object,
                default(){
                    return null
                }
            },
            type: {
                type: String,
                default: ''
            },
            hasRemove: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            computedItem() {
                return !this.item ? null : this.type === 'class' ||
                    this.type === 'course' || this.type === 'subject' ||
                    this.type === 'program' || this.type === 'year' ||
                    this.type === 'section' || this.item.name ? 
                    this.item.name : this.type === 'discussion' ? 
                    this.item.title : ''
            },
            computedDescription() {
                let str = ''
                if (this.type === 'class' && this.item.description){
                    str = this.item.description
                } else if (this.type === 'discussion' && this.item.preamble) {
                    str = this.item.preamble
                } else if (this.item.description) {
                    str = this.item.description
                }
                return strings.content(str,30)
            },
            computedDetails() {
                return !this.item ? null : this.type === 'class' || this.type === 'course' ? 
                    `${this.item.learners} learners, ${this.item.lessons} lessons` : 
                    this.type === 'discussion' ? 
                    `type: ${this.item.type}, allowed: ${this.item.allowed}` : 
                    this.type === 'subject' && this.item.rationale ? 
                    `rationale: ${strings.content(this.item.rationale)}` : 
                    this.type === 'program' ? `${this.item.courses} courses` : 
                    this.type === 'year' || this.type === 'section' ? this.computedDates : ''
            },
            computedCreatedAt() {
                return this.item && this.item.createdAt ? 
                    dates.dateReadable(this.item.createdAt) : null
            },
            computedDates() { //start and end dates
                let str = ''
                if (this.item.startDate) {
                    str += `start date: ${dates.dateReadable(this.item.startDate)}`
                }
                if (this.item.endDate) {
                    if (str.length) str += '\n'
                    str += `end date: ${dates.dateReadable(this.item.endDate)}`
                }
                return str
            },
        },
        methods: {
            clickedItem() {
                this.$emit('clickedItem', this.item)
            },
            clickedRemoveItem() {
                this.$emit('clickedRemoveItem', this.item)
            },
            getDateReadable(date){
                return dates.dateReadable(date)
            }
        }
    }
</script>

<style lang="scss" scoped>
@mixin small-text(){
    font-size: 10px;
    color: gray;
    width: 100%;
    text-align: end;
}

    .item-badge-wrapper{
        margin: 0 10px 10px;
        width: fit-content;
        padding: 10px;
        font-size: 14px;
        position: relative;
        background-color: mintcream;
        color: gray;
        cursor: pointer;
        border-radius: 5px;
        min-width: 100px;
        max-width: 45%;

        .close{
            @include small-close()
        }
        
        .created{
            @include small-text()
        }

        .body{
            color: black;
            text-align: center;
            text-transform: capitalize;
            font-size: 13px;
        }

        .description{
            font-size: 12px;
            font-style: italic;
            text-transform: lowercase;
        }

        .details{
            font-size: 10px;

        }
    }

    .item-badge-wrapper.active{
        background: antiquewhite;
    }
</style>