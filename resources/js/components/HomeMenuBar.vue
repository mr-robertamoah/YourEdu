<template>
    <div class="fixed w-full bg-whitesmoke sm:w-10/12 lg:w-4/6 flex 
        overflow-x-auto z-10 border-b lg:mx-1/6 sm:mx-1/12
        border-mainbrown shadow-sm p-2">
        <home-menu-item
            class="mx-1"
            v-for="(item, index) in items"
            :key="index"
            :item="item.text"
            :showItem="item.show"
            :active="item.text === activeItem"
            @clickedItem="clickedItem"
        ></home-menu-item>
    </div>
</template>

<script>
import HomeMenuItem from './HomeMenuItem.vue'
    export default {
        components: {
            HomeMenuItem,
        },
        props: {
            activeItem: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                items: [
                    {show: true, icon: false, text:'posts'},
                    {show: true, icon: false, text:'discussions'},
                    {show: true, icon: false, text:'assessments'},
                    {show: true, icon: false, text:'reads'},
                    {show: true, icon: false, text:'books'},
                    {show: true, icon: false, text:'poems'},
                    {show: true, icon: false, text:'riddles'},
                    {show: true, icon: false, text:'questions'},
                    {show: true, icon: false, text:'activities'},
                ]
            }
        },
        methods: {
            clickedItem(item) {
                this.$emit('clickedMenuItem', item)
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>