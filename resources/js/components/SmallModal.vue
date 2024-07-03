<template>
    <div class="modal-wrapper" v-if="show" @click.self="disappear">
        <div class="main-modal">
            <div class="loading" v-if="loading">
                <sync-loader
                    :loading='loading'
                ></sync-loader>
            </div>
            <div class="alerting" v-if="alerting">
                <auto-alert
                    :message="computedAlert"
                    :success="success"
                    :danger="danger"
                    @hideAlert="hideAlert"
                ></auto-alert>
            </div>
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <div class="main-section" v-if="!main && !loading && !alerting">
                <div class="title">
                    {{title}}
                </div>
                <div class="buttons">
                    <slot name="actions"></slot>
                </div>
                <div class="other">
                    <slot name="other"></slot>
                </div>
            </div> 
            <div class="main" v-if="main">
                <div class="title">
                    {{title}}
                </div>
                <div class="other">
                    <slot name="other"></slot>
                </div>
            </div> 
        </div>
    </div>
</template>

<script>
;
import AutoAlert from './AutoAlert.vue'

    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
                required: false
            },
            loading: {
                type: Boolean,
                default: false,
            },
            alerting: {
                type: Boolean,
                default: false,
            },
            success: {
                type: Boolean,
                default: false,
            },
            danger: {
                type: Boolean,
                default: false,
            },
            title: {
                type: String,
                default: '',
            },
            message: {
                type: String,
                default: '',
            },
            main: {
                type: Boolean,
                default: false,
            },
        },
        components: {
            AutoAlert,
            
        },
        computed: {
            computedAlert() {
                return this.message
            }
        },
        methods: {
            disappear() {
                this.$emit('disappear')
            },
            hideAlert(){
                this.$emit('disappear')
            }
        },
    }
</script>

<style lang="scss" scoped>


$wrapper-background: transparent;
$modal-background: whitesmoke;
$modal-width: 35%;
$modal-height: 35vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .modal-wrapper{
        background-color: $wrapper-background;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        padding: auto;
        z-index: 10000;

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
            position: relative;
            overflow: auto;

            .loading{
                position: sticky;
                top: 10px;
                width: 100%;
                text-align: center;
            }

            .alerting{
                width: 100%;
                position: relative;
                margin: 20px 0 0;
            }

            .main-section,
            .main{

                .title{
                    font-size: 16px;
                    font-weight: 500;
                    text-align: center;
                    margin: 10px;
                }
            }
            
            .main-section{
                position: absolute;
                top: 20%;
                width: 100%;
                margin: 20px auto;

                .buttons{
                    display: flex;
                    width: 100%;
                    justify-content: space-around;
                    align-items: center;
                }

                .other{
                    display: block;
                    width: 80%;
                    margin: 10px auto;
                    padding: 5px;
                    text-align: center;

                    a{
                        background-color: rgba(127, 255, 212, .9);
                        border-radius: 10px;
                        cursor: pointer;
                        padding: 5px;
                        color: gray;
                        font-weight: 500;
                        font-size: 16px;

                        &:hover{
                            transition: all 1s ease-in-out;
                            box-shadow: 0 0 3px gray;
                        }
                    }
                }
            }

            .main{
                padding: 0px 10px 10px;

                .other{
                    text-align: center;
                    
                    .item{
                        width: fit-content;
                        padding: 5px;
                        margin: 5px auto;
                        box-shadow: 0 0 2px grey;
                        border-radius: 10px;
                        font-size: 14px;
                        background: azure;
                        cursor: pointer;

                        .main{

                        }
                        
                        .due{
                            
                        }
                        
                        .info{
                            font-size: 12px;
                            text-transform: lowercase;
                            color: gray;
                        }
                    }

                    .item.selected{
                        background: aquamarine;
                    }
                }
            }
            
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
$modal-width: 50%;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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
$modal-width: 80%;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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