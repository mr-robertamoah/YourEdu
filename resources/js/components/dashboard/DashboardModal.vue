<template>
    <div class="dashboard-modal-wrapper" v-if="show" @click.self="disappear">
        <div class="main-modal" 
            @click.self="disappear"
        >
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <div class="main" v-if="main">
                <slot name="main"></slot>
            </div>
            <div class="normal" v-if="normal">
                <slot name="normal"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            main: {
                type: Boolean,
                default: true
            },
            normal: {
                type: Boolean,
                default: false
            },
        },
        computed: {
            computedHeading() {
                return  
            },
            computedMain() {
                return  
            },
        },
        methods: {
            disappear() {
                this.$emit('closeDashboardModal')
            },
        },
    }
</script>

<style lang="scss" scoped>
$wrapper-background: rgba(102, 51, 153, .2);
$modal-background: aliceblue;
$modal-width: 60%;
$modal-height: 98vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .dashboard-modal-wrapper{
        position: fixed;
        background-color: $wrapper-background;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        padding: auto;
        z-index: 10000;
        overflow: scroll;

        .main-modal{
            background-color: $modal-background;
            width: $modal-width;
            height: $modal-height;
            position: relative;
            top: $modal-margin-height;
            left: $modal-margin-width;
            border-radius: 10px;
            box-shadow: 1px 1px 1px rgba(105, 105, 105,.6), 
                -1px -1px 1px rgba(105, 105, 105,.6);
            display: block;
            overflow-y: scroll;
            position: relative;
            
            .close{
                position: absolute;
                width: 20px;
                top: 0;
                right: 0;
                margin: 10px 10px 0 0;
                color: rgba(105, 105, 105,.8);
                cursor: pointer;

                &:hover{
                    color: rgba(255, 0, 0, 0.603);
                }
            }

            .main{
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 75%;

            }

            .normal{
                padding: 10px;
                margin-top: 20px;
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 70%;
$modal-margin-width: math.div(100% - $modal-width, 2);

    .dashboard-modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;
        }
    }
}


@media screen and (max-width:800px){
$modal-width: 95%;
$modal-margin-width: math.div(100% - $modal-width, 2);

    .dashboard-modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;
        }
    }
}
</style>