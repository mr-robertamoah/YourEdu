<template>
    <div class="detail-wrapper">
        <div class="top">
            this is the name of the course
        </div>
        <div class="middle">
            <div class="item">
                created by
                <div>this facilitator</div>
                <slot name="item1"></slot>
            </div>
            <div class="item">
                <slot name="item2"></slot>
            </div>
        </div>
        <div class="bottom">
            last update on
            <div>1st January, 2020</div>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .detail-wrapper{
        border-left: 1px solid rgb(105, 105, 105);
        background-color: rgba(105, 105, 105,.08);
        margin: 10px 0;
        padding: 10px;
        position: relative;
        cursor: pointer;
        width: 30%;

        & :hover::after{
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background-color: rgba(187, 14, 187, 0.085);
            transition: all 1s ease-in-out;
        }

        .top{
            width: 70%;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .middle{
            margin-bottom: 10px;
            font-size: 12px;
            font-weight: 500;
            display: block;
            .item{
                margin-bottom: 5px;
            }
        }

        .bottom{
            font-size: 10px;
            color: rgba(105, 105, 105,.7);
            display: flex;
            justify-content:space-between;
            align-items: center;
        }
    }

@media screen  and (max-width: 800px){
    .detail-wrapper{
        
        width: 50%;
    }
}
</style>