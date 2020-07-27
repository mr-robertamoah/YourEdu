<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>
    div{
        font-size: 14px;
        color: rgba(105, 105, 105,.5);
    }

@media screen and (min-width: 800px) and (max-width: 1100px){
    div{
        font-size: 12px;
    }
}

@media screen and (max-width: 800px){
    div{
        font-size: 12px;
    }
}
</style>