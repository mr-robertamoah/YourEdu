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
                    :success="alertSuccess"
                    :danger="alertDanger"
                    @hideAlert="hideAlert"
                ></auto-alert>
            </div>
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <div class="main-section" v-if="!loading && !alerting">
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
        </div>
    </div>
</template>

<script>
import SyncLoader from 'vue-spinner/src/SyncLoader';
import AutoAlert from './AutoAlert'

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
            title: {
                type: String,
                default: '',
            },
            message: {
                type: String,
                default: '',
            },
        },
        components: {
            AutoAlert,
            SyncLoader,
        },
        data() {
            return {
                alertSuccess:false,
                alertDanger:false,
            }
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
$modal-width: 50%;
$modal-height: 35vh;
$modal-margin-width: (100% - $modal-width)/2;
$modal-margin-height: (100vh - $modal-height)/2;

    .modal-wrapper{
        background-color: $wrapper-background;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        padding: auto;
        z-index: 10000;
        // overflow: scroll;

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

            .loading{
                margin: 20px 0 0;
                width: 100%;
                text-align: center;
            }

            .alerting{
                width: 100%;
                position: relative;
                margin: 20px 0 0;
            }
            
            .main-section{
                position: absolute;
                top: 20%;
                width: 100%;
                margin: 20px auto;

                .title{
                    font-size: 16px;
                    font-weight: 500;
                    text-align: center;
                    margin: 10px;
                }

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