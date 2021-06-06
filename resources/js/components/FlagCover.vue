<template>
    <div class="absolute bg-gray-800 flex flex-col h-full items-center justify-center opacity-90 px-10 right-0 top-0 w-full">
        <div class="font-extrabold text-2xl text-red-200">
            {{computedMainText}}
        </div>
        <div class="font-black mt-10 text-center text-red-900 text-sm">
            you have either flagged this or it has been flagged by any of your parents if you have one on the platform
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            item: {
                type: String,
                default: ''
            },
        },
        computed: {
            computedMainText() {
                return `${this.item} is flagged` 
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>