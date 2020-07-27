<template>
    <transition name="fade-right-fast">
        <slot name="transition"></slot>
    </transition>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .fade-right-fast-enter{
        transform: translateX(50px);
        opacity: 0;
    }

    .fade-right-fast-leave-to{
        transform: translateX(50px);
        opacity: 0;
    }

    
    .fade-right-fast-enter-active,
    .fade-right-fast-leave-active{
        transition: all .2s ease-in-out;
    }
</style>