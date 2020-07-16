<template>
    <div class="modal-wrapper" v-if="appear" @click.self="disappear">
        <transition name="main">
            <div class="main-modal">
                <div class="close" @click="disappear">
                    <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
                </div>   
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
                required: false
            },
        },
        data() {
            return {
                appear: this.show
            }
        },
        methods: {
            disappear() {
                this.appear = false
            }
        },
    }
</script>

<style lang="scss" scoped>
$wrapper-background: transparent;
$modal-background: wheat;
$modal-width: 50%;
$modal-height: 35vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .main-enter,
    .main-leave-to{
        opacity: 0;
    }
    
    .main-enter-to,
    .main-leave{
        opacity: 1;
    }
    
    .main-enter-active,
    .main-leave-active{
        transition: all 1s ease-in-out;
    }

    .modal-wrapper{
        position: absolute;
        background-color: $wrapper-background;
        position: absolute;
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
            bottom: -100vh + $modal-height + 2.5vh;
            left: $modal-margin-width;
            border-radius: 10px;
            box-shadow: 1px 1px 1px rgba(105, 105, 105,.6), 
                -1px -1px 1px rgba(105, 105, 105,.6);
            
            .close{
                position: absolute;
                width: 20px;
                right: 0;
                margin: 10px 10px 0 0;
                color: rgba(105, 105, 105,.8);
                cursor: pointer;
            }
            
            .close:hover{
                color: rgba(255, 0, 0, 0.603);
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 60%;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            bottom: -100vh + $modal-height + 2.5vh;
            left: $modal-margin-width;
        }
    }
}


@media screen and (max-width:800px){
$modal-width: 95%;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            bottom: -100vh + $modal-height + 2.5vh;
            left: $modal-margin-width;
        }
    }
}
</style>