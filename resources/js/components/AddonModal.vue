<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div class="addon-modal-wrapper"
                @click.self="clickedClose"
            >
                <div class="addon-modal-main"
                    :class="{transparent:transparent}"
                >
                    <div class="close"
                        @click="clickedClose"
                        v-if="hasClose"
                    >
                        <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                    </div>
                    <div class="title" v-if="title.length">
                        {{title}}
                    </div>
                    <div class="loading" v-if="loading">
                        <pulse-loader :loading="loading"></pulse-loader>
                    </div>
                    <div class="addon-data" 
                        v-if="!loading"
                        :class="{fullHeight:fullHeight}"
                    >
                        <slot name="data"></slot>
                    </div>
                </div>
            </div>
        </template>
    </just-fade>
</template>

<script>
import JustFade from './transitions/JustFade.vue';
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            fullHeight: {
                type: Boolean,
                default: false
            },
            transparent: {
                type: Boolean,
                default: false
            },
            hasClose: {
                type: Boolean,
                default: true
            },
            loading: {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: ''
            },
        },
        components: {
            JustFade,
        },
        methods: {
            clickedClose() {
                this.$emit('clickedClose')
            }
        },
    }
</script>

<style lang="scss" scoped>

$wrapper-background: transparent;
$modal-background: aliceblue;
$modal-width: 60%;
$modal-height: 90vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .addon-modal-wrapper{
        position: fixed;
        background-color: $wrapper-background;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: 10000;

        .addon-modal-main{
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
            overflow: hidden;
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

            .title{
                width: 90%;
                text-align: center;
                font-size: 14px;
                font-weight: 500;
                color: gray;
                padding: 10px;
                margin: 0 auto;
            }

            .addon-data{
                padding: 10px;
            }

            .fullHeight{
                height: 100%;
            }

            .loading{
                text-align: center;
            }
        }

        .transparent{
            background: rgba(0, 0, 0, 0.2);
        }

    }

@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 70%;
$modal-height: 90vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .addon-modal-wrapper{

        .addon-modal-main{
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
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: 0;

    .addon-modal-wrapper{

        .addon-modal-main{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;
        }
        
        .title{
            width: 80%;
        }

        .close{
            right: 10px;
        }
    }
}

@media screen and (max-width:500px){
$modal-width: 100%;
$modal-height: 90vh;
$modal-margin-width: 0;
$modal-margin-height: 0;

    .addon-modal-wrapper{

        .addon-modal-main{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;
        }

        .close{
            right: 10px;
            font-size: 18px;
        }
    }
}
</style>