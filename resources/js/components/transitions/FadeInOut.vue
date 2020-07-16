<template>
    <transition name='fade-in'>
        <slot name="transition"></slot>
    </transition>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .fade-in-leave-to{
        transform: scale(.2);
        opacity: 0;
    }
    
    .fade-in-enter{
        transform: scale(.5);
        opacity: 0;
    }

    .fade-in-enter-active,
    .fade-in-leave-active{
        transition: all 1s ease;
    }
</style>