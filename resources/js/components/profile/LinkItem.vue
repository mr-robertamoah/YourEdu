<template>
    <fade-left-fast>
        <template slot="transition">
            <div class="link-item-wrapper">
                <div v-if="!link">
                    {{item}}
                </div>
                <a :href="item" v-else></a>
                <div class="actions" v-if="actions">
                    <div class="show"
                        @click="clickedShow"
                        :title="title"
                        v-if="unseen"
                    >
                        <font-awesome-icon :icon="['fa','eye']"></font-awesome-icon>
                    </div>
                    <div class="show"
                        @click="clickedShow"
                        :title="title"
                        v-if="seen"
                    >
                        <font-awesome-icon :icon="['fa','eye-slash']"></font-awesome-icon>
                    </div>
                    <div class="remove" 
                        @click="clickedRemove"
                        title="remove"
                    >
                        <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
                    </div>
                </div>
            </div>
        </template>
    </fade-left-fast>
</template>

<script>
import FadeLeftFast from '../transitions/FadeLeftFast.vue';
    export default {
        props: {
            link: {
                type: Boolean,
                default: false
            },
            actions: {
                type: Boolean,
                default: false
            },
            item: {
                type: String,
                default: ''
            },
            title: {
                type: String,
                default: ''
            },
            seen: {
                type: Boolean,
                default: false
            },
            unseen: {
                type: Boolean,
                default: false
            },
            id: {
                type: Number,
                default: null
            },
        },
        components: {
            FadeLeftFast,
        },
        watch: {
            seen: {
                immedialte: true,
                handler(newValue){
                    if (newValue) {
                        this.iconTitle = 'make it unseen on profile'
                    } else {
                        this.iconTitle = 'make it seen on profile'
                    }
                }
            }
        },
        data() {
            return {
                iconTitle: '',
            }
        },
        methods: {
            clickedShow() {

                this.$emit('clickedShow',{
                    id:this.id,
                    item:this.item,
                    title:this.title
                })
            },
            clickedRemove() {
                this.$emit('clickedRemove',{
                    id:this.id,
                    item:this.item,
                    title:this.title
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

    .link-item-wrapper{
        width: 100%;
        display: inline-flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;

        &:hover{
            transition: all 1s ease;
            box-shadow: 0 0 3px gray;
            background-color: aliceblue;
        }

        .link{
            width: 100%;
            margin-bottom: 5px;
            position: relative;

            a{
                text-decoration-color: rgb(105, 105, 105);
            }
        }

        .actions{
            display: inline-flex;
            justify-content: space-between;
            align-items: center;

            .show,
            .remove{
                cursor: pointer;
                color: gray;
                margin-right: 5px;
                padding: 5px;

                &:hover{
                    transition: all 1s ease;
                    box-shadow: 0 0 3px gray;
                }
            }
        }
    }
</style>