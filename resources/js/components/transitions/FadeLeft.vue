<template>
    <transition name="fade-left">
        <slot name="transition"></slot>
    </transition>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .fade-left-enter{
        transform: translateX(-20px);
        opacity: 0;
    }

    .fade-left-leave-to{
        transform: translateX(-20px);
        opacity: 0;
    }

    
    .fade-left-enter-active,
    .fade-left-leave-active{
        transition: all 1s ease-in-out;
    }
</style>