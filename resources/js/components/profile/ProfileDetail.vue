<template>
    <div class="wrapper">
        <div class="top">
            <slot name="top"></slot>
        </div>
        <div class="bottom">
            <div class="text">
                <slot name="text"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .wrapper{
        width: 100%;
        border-left: 2px solid rgb(105, 105, 105);
        background-color: rgba(105, 105, 105,.08);

        .top{
            width: auto;
            font-size: 16px;
            border-bottom: 1px solid dimgray;
            font-weight: 600;
            text-transform: capitalize;
            padding: 10px;
        }

        .bottom{
            width: 90%;
            margin: 10px auto;
            font-size: 14px;
            padding: 10px;
            min-height: 50px;
        }
    }

@media screen and (max-width: 800px){
    .wrapper{

        .top{
            font-size: 14px;
        }

        .bottom{
            font-size: 12px;
        }
    }
}
</style>