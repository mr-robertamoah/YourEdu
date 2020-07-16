<template>
    <div class="modal-wrapper" v-if="show" @click.self="disappear">
        <div class="main-modal">
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <template v-if="!showAlert">
                <div class="loading-errors" v-if="loading">
                    <slot name="loading"></slot>
                    <slot name="errors"></slot>
                </div>
                <div class="main" v-else>
                    <slot name="main"></slot>
                </div>
            </template>
            <fade-right>
                <template slot="transition"  v-if="showAlert">
                    <div class="alert">
                        {{alertMessage}}
                    </div>
                </template>
            </fade-right>
        </div>
    </div>
</template>

<script>
import FadeRight from "./transitions/FadeRight";
    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
            },
            loading: {
                type: Boolean,
                default: false,
            },
            alertMessage: {
                type: String,
                default: ''
            },
        },
        components: {
            FadeRight,
        },
        data() {
            return {

            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.$emit('mainModalAppear')
                    }
                }
            }
        },
        computed: {
            showAlert() {
                if(this.alertMessage,length){
                    setTimeout(() => {
                        this.alertMessage=''
                    }, 2000);
                    return true
                } else {
                    return false
                }
            }
        },
        methods: {
            disappear() {
                this.$emit('mainModalDisappear')
            }
        },
    }
</script>

<style lang="scss" scoped>
$wrapper-background: rgba(102, 51, 153, .2);
$modal-background: aliceblue;
$modal-width: 60%;
$modal-height: 70vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .modal-wrapper{
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

            .loading-errors{
                width: 80%;
                position: relative;
                display: block;
                margin: 0 auto 20px;
                padding: 30px 0 0;
                text-align: center;
            }

            .main{
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 75%;
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 70%;
$modal-height: 80vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;
        }
    }
}


@media screen and (max-width:800px){
$modal-width: 90%;
$modal-height: 90vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;
        }
    }
}
</style>