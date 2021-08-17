<template>
    <button 
        class="transition-colors text-gray-500 disabled:bg-gray-200 disabled:text-gray-400 p-2 
            hover:text-whitesmoke border-b cursor-pointer hover:shadow-sm hover:bg-gray-800 rounded
            disabled:cursor-not-allowed"
        :disabled="disabled"
    >
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            disabled: {
                type: Boolean,
                default: false
            },
            text: {
                type: String,
                default: ''
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>