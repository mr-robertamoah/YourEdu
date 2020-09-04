<template>
    <transition-group tag="div" class="slide-up-wrapper" name='slide-up' appear>
        <slot name="transition"></slot>
    </transition-group>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .slide-up-leave-to{
        opacity: 0;
        transform: translateY(10px);
    }
    
    .slide-up-enter{
        opacity: 0;
        transform: translateY(20px);
    }

    .slide-up-enter-active,
    .slide-up-leave-active,
    .slide-up-move{
        transition: all .5s ease;
    }
</style>