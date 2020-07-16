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
        font-size: 1vw;
        color: rgba(105, 105, 105,.5);
    }

@media screen and (min-width: 800px) and (max-width: 1100px){
    div{
        font-size: 2vw;
    }
}

@media screen and (max-width: 800px){
    div{
        font-size: 3vw;
    }
}
</style>