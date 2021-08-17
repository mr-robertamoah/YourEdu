<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div class="pop-up-wrapper" 
                :class="type"
                @click.self="clickedDefault"
            >
                <div class="main">
                    <slot></slot>
                    <div class="message" v-if="message.length">
                        {{message}}
                    </div>
                    <div class="responses" v-if="hasResponses">
                        <div class="response"
                            v-for="(response,index) in responses"
                            :key="index"
                            v-text="response"
                            @click="clickedResponse(response)"
                        ></div>
                    </div>
                </div>
            </div>
        </template>
    </just-fade>
</template>

<script>
    export default {
        props: {
            responses: {
                type: Array,
                default() {
                    return ['yes','no']
                }
            },
            message: {
                type: String,
                default: ''
            },
            default: {
                type: String,
                default: ''
            },
            show: {
                type: Boolean,
                default: false
            },
            hasResponses: {
                type: Boolean,
                default: true
            },
            type: {
                type: String,
                default: 'mini'
            },
        },
        watch: {
            show(newValue) {
                if (newValue && this.hasResponses) {
                    setTimeout(() => {
                        this.clickedDefault()
                    }, 50000);
                }
            }
        },
        methods: {
            clickedResponse(response) {
                this.$emit('clickedResponse', response)
                this.closePopUp()
            },
            clickedDefault() {
                if (!this.show) {
                    return
                }
                
                this.$emit('clickedResponse', this.default)
                this.closePopUp()
            },
            closePopUp(response) {
                this.$emit('closePopUp')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .pop-up-wrapper{
        z-index: 20000;
        position: fixed;
        background: transparent;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;

        .main{
            max-width: 500px;
            width: 90%;
            min-height: 100px;
            position: relative;
            padding: 30px 20px 20px;
            background: $modal-background;
            border-radius: 10px;
            box-shadow: 1px 1px 1px grey, -1px -1px 1px grey;

            .message{
                font-size: 14px;
                color: gray;
                width: 100%;
                text-align: center;
                margin-bottom: 15px;
            }

            .responses{
                width: 90%;
                margin: 0 auto;
                display: flex;
                justify-content: center;

                .response{
                    padding: 5px 10px;
                    cursor: pointer;
                    color: mintcream;
                    background: $color-secondary;
                    margin: 0 10px;
                    border-radius: 10px;
                    min-width: 50px;
                    text-align: center;
                }
            }
        }
    }

    .pop-up-wrapper.mini{

        .main{
            max-width: 350px;
        }
    }
</style>