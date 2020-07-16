<template>
    <div class="group-section-wrapper">
        <div class="top">
            {{heading}}
        </div>
        <div class="bottom">
            <slot name="body">

            </slot>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            heading: {
                type: String,
                default: ''
            },
        },
    }
</script>

<style lang="scss" scoped>
    
    .group-section-wrapper{
        width: 100%;
        margin: 10px 0;
        background-color: rgba(105, 105, 105,.08);

        .top{
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
            text-transform: capitalize;
            background-color: rgba(105, 105, 105,.15);
        }

        .bottom{
            min-height: 100px;
            width: 90%;
            margin: 10px auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;

            
        }
    }
</style>