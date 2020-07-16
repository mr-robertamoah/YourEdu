<template>
    <transition name="fade-right">
        <slot name="transition"></slot>
    </transition>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .fade-right-enter{
        transform: translateX(50px);
        opacity: 0;
    }

    .fade-right-leave-to{
        transform: translateX(50px);
        opacity: 0;
    }

    
    .fade-right-enter-active,
    .fade-right-leave-active{
        transition: all .5s ease-in-out;
    }
</style>