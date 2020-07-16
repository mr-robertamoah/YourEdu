<template>
    <transition name="alert">
        
    </transition>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .alert-enter{
        transform: translateX(100px);
        width: 0;
    }

    .alert-leave-to{
        transform: translateX(100px);
        width: 0;
    }
    
    .alert-enter-active,
    .alert-leave-active{
        transition: all 1s ease-out;
    }
</style>