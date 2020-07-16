<template>
    <transition name='fade'>
        <slot name="transition"></slot>
    </transition>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .fade-leave-to{
        opacity: 0;
        transform: scale(.4);
    }
    
    .fade-enter{
        opacity: 0;
        transform: scale(.4);
    }

    .fade-enter-active,
    .fade-leave-active{
        transition: all .5s ease;
    }
</style>