<template>
    <transition name="fade-left-fast">
        <slot name="transition"></slot>
    </transition>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .fade-left-fast-enter{
        transform: translateX(-20px);
        opacity: 0;
    }

    .fade-left-fast-leave-to{
        transform: translateX(-20px);
        opacity: 0;
    }

    
    .fade-left-fast-enter-active,
    .fade-left-fast-leave-active{
        transition: all .3s ease-in-out;
    }
</style>