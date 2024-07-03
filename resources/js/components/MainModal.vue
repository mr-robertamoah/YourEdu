<template>
    <div class="modal-wrapper" 
        v-if="show" 
        @click.self="disappear"
        :class="{long}"
    >
        <div class="main-modal" 
            :class="{dark}" 
            @click.self="clickedMain"
            ref="mainmodal"
        >
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <template>
                <div class="heading">
                    {{heading}}
                    <slot name="heading" v-if="!computedHeading"></slot>
                </div>
                <div class="loading-errors" v-if="loading">
                    <slot name="loading"></slot>
                    <slot name="errors"></slot>
                </div>
                <div class="main" v-if="computedMain">
                    <slot name="main"></slot>
                </div>
                <div class="main-other" v-if="computedMainOther">
                    <slot name="main-other"></slot>
                </div>
                <div class="requests" 
                    v-if="!loading && requests"
                    :class="{alone: requestsAlone}"
                >
                    <slot name="requests"></slot>
                </div>
            
                <slot></slot>
            </template>
            <fade-right>
                <template slot="transition"  v-if="showAlert">
                    <div class="alert-wrapper">
                        <div class="alert"
                            :class="{alertError:alertError,alertSuccess:alertSuccess}"
                        >
                            {{alertMessage}}
                        </div>
                    </div>
                </template>
            </fade-right>
        </div>
    </div>
</template>

<script>
import FadeRight from "./transitions/FadeRight.vue";
    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
            },
            main: {
                type: Boolean,
                default: true,
            },
            mainOther: {
                type: Boolean,
                default: true,
            },
            long: {
                type: Boolean,
                default: false,
            },
            dark: {
                type: Boolean,
                default: false,
            },
            requests: {
                type: Boolean,
                default: true,
            },
            requestsAlone: {
                type: Boolean,
                default: true,
            },
            loading: {
                type: Boolean,
                default: false,
            },
            scrollUp: {
                type: Boolean,
                default: false,
            },
            heading: {
                type: String,
                default: ''
            },
            alertMessage: {
                type: String,
                default: ''
            },
            alertError: {
                type: Boolean,
                default: false
            },
            alertSuccess: {
                type: Boolean,
                default: false
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
            },
            scrollUp(newValue){
                if (newValue) {
                    this.$refs.mainmodal.scrollTo(0,0)
                }
            },
            alertMessage: {
                immediate:true,
                handler(newValue){
                    if(this.alertMessage.length > 0){
                        setTimeout(() => {
                            this.$emit('clearAlert') 
                        }, 4000);
                    }
                }
            }
        },
        computed: {
            showAlert() {
                if(this.alertMessage.length > 0){
                    return true
                } else {
                    return false
                }
            },
            computedMain(){
                return this.loading ? false :
                    this.main ? true : false
            },
            computedMainOther(){
                return this.loading ? false :
                    !this.mainOther ? false : true
            },
            computedHeading(){
                return this.heading.length > 0 ? true : false
            },
        },
        methods: {
            disappear() {
                this.$emit('mainModalDisappear')
            },
            clickedMain(){
                this.$emit('clickedMain')
            },
        },
    }
</script>

<style lang="scss" scoped>


$wrapper-background: rgba(102, 51, 153, .2);
$modal-width: 60%;
$modal-height: 70vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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
            position: relative;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;
            border-radius: 10px;
            box-shadow: 1px 1px 1px rgba(105, 105, 105,.6), 
                -1px -1px 1px rgba(105, 105, 105,.6);
            display: block;
            overflow-y: scroll;
            position: relative;
            
            .close{
                position: sticky;
                top: 10px;
                margin: 10px 10px 0 0;
                color: rgba(105, 105, 105,.8);
                cursor: pointer;
                text-align: end;
                padding: 0 10px 0 0;
                z-index: 1000;

                &:hover{
                    color: rgba(255, 0, 0, 0.603);
                }
            }

            .heading{
                width: 100%;
                text-align: center;
                font-size: 18px;
                font-weight: 500;
                text-transform: capitalize;
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
                // display: flex;
                // justify-content: center;
                // align-items: center;
                min-height: 75%;
            }

            .alert-wrapper{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: inherit;

                .alert{
                    position: relative;
                    width: 100%;
                    text-align: center;
                    padding: 10px;
                    background-color: rgba(8, 170, 245, 0.486);
                    color: white;
                    border: 2px solid rgba(8, 170, 245,1);
                    position: absolute;
                    top: 45%;
                }

                .alertError{
                    // color: rgba(201, 6, 6, 0.9);
                    background-color: rgba(201, 6, 6, 0.4);
                    border: 2px solid rgba(201, 6, 6, 0.4);
                }

                .alertSuccess{
                    // color: rgba(0, 128, 0, 0.9);
                    background-color: rgba(0, 128, 0, 0.4);
                    border: 2px solid rgba(0, 128, 0, 0.4);
                }
            }
        }

        .main-other,
        .requests{
            display: block;
            padding: 10px;
            padding-top: 50px;

            .show-more{
                color: gray;
                background-color: azure;
                width: 50%;
                margin: 10px auto;
                text-align: center;
                padding: 5px;
                border-radius: 10px;
            }
        }

        .dark{
            display: block;
            background: black;
        }

        .requests{ 
            max-width: 400px;
            margin: 0 auto;
            padding: 10px 10px 40px;

            &.alone{
                padding: 40px 10px 50px;
            }
        }
    }

    .modal-wrapper.long{
        $modal-height: 100vh;
        $modal-margin-height: math.div(100vh - $modal-height, 2);

        .main-modal{    
            height: $modal-height;
            top: $modal-margin-height;
        }
    }

@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 70%;
$modal-height: 80vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

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