<template>
    <div class="modal-wrapper" v-if="show" @click.self="disappear">
        <div class="close" @click="disappear" v-if="!showGoBack">
            <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
        </div>
        <div class="navigation"
            v-if="showGoBack" @click="goBack"
        >
            <go-back></go-back>
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
                <div class="alert"
                    :class="{alertError:alertError,alertSuccess:alertSuccess}"
                >
                    {{alertMessage}}
                </div>
            </template>
        </fade-right>
    </div>
</template>

<script>
import FadeRight from "./transitions/FadeRight.vue";
import GoBack from "./GoBack.vue";

    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
            },
            showGoBack: {
                type: Boolean,
                default: false,
            },
            loading: {
                type: Boolean,
                default: false,
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
            GoBack,
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
            alertMessage: {
                immediate:true,
                handler(newValue){
                    if(this.alertMessage.length > 0){
                        setTimeout(() => {
                            this.$emit('clearAlert') 
                        }, 2000);
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
            }
        },
        methods: {
            disappear() {
                this.$emit('mainModalDisappear')
            },
            goBack(){
                this.$emit('goBack')
            }
        },
    }
</script>

<style lang="scss" scoped>


$wrapper-background: whitesmoke;
$modal-background: aliceblue;
$modal-width: 60%;
$modal-height: 70vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .modal-wrapper{
        position: fixed;
        background-color: $wrapper-background;
        top: 5vh;
        left: 20vw;
        width: 60vw;
        height: 90vh;
        padding: 10px;
        z-index: 10000;
        overflow: scroll;
            
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

        .navigation{
            position: absolute;
            width: 20px;
            top: 0;
            right: 0;
            margin: 10px 10px 0 0;
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
        }

        .alert{
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: rgba(8, 170, 245, 0.486);
            color: rgba(8, 170, 245,1);
            border: 2px solid rgba(8, 170, 245,1);
            position: absolute;
            top: 45%;
        }

        .alertError{
            color: rgba(201, 6, 6, 0.9);
            background-color: rgba(201, 6, 6, 0.4);
            border: 2px solid rgba(201, 6, 6, 0.4);
        }

        .alertSuccess{
            color: rgba(0, 128, 0, 0.9);
            background-color: rgba(0, 128, 0, 0.4);
            border: 2px solid rgba(0, 128, 0, 0.4);
        }
    }

@media screen and (max-width: 800px) {
    
    .modal-wrapper{
        top: 5vh;
        left: 5vw;
        width: 90vw;
        height: 90vh;

        .main{
            display: block;
            width: 100%;
        }
    }
}

@media screen and (max-width: 600px) {
    
    .modal-wrapper{
        top: 0;
        left: 0;
        width: 96vw;
        height: 100vh;

        .main{
            display: block;
            width: 100%;
        }
    }
}
</style>