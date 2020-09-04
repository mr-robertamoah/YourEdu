<template>
    <div class="text-input-wrapper"
        :title="title"
    >
        <div class="main-section"
            :class="{error:error,bottomborder:bottomBorder,
            noborder:noBorder,sm:sm}"
        >
            <input type="number" 
                :placeholder="placeholder"
                v-model="inputNumber"
                @keyup="checkInput" 
                :max="inputMax"
                :min="inputMin"
                class="form-control"
                ref="muberinput"
                pattern="[0-9]*"
                inputmode="numeric"
            >
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            error: {
                type: Boolean,
                default: false
            },
            bottomBorder: {
                type: Boolean,
                default: false
            },
            noBorder: {
                type: Boolean,
                default: false
            },
            sm: {
                type: Boolean,
                default: false
            },
            noBorder: {
                type: Boolean,
                default: false
            },
            inputMax: {
                type: Number,
                default: 100
            },
            inputMin: {
                type: Number,
                default: 5
            },
            placeholder: {
                type: String,
                default: ''
            },
            value: {
                type: String,
                default: ''
            },
            title: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                inputNumber: "0"
            }
        },
        watch: {
            inputNumber(newValue){
                this.$emit('numberinput', newValue)
            },
            value:{
                immediate: true,
                handler(newValue){
                    this.inputNumber = newValue
                }
            }
        },
        methods: {
            checkInput(event) {
                if (event.target.value < this.inputMin) {
                    event.target.value = this.inputMin
                    this.$emit('numberinput',`${event.target.value}`)
                } else if (event.target.value > this.inputMax) {
                    event.target.value = this.inputMax
                    this.$emit('numberinput',`${event.target.value}`)
                }
            },
            input($event) {
                let value = $event.target.value 
                this.$emit('numberinput',`${value}`)
            },
        },
    }
</script>

<style lang="scss" scoped>
$border-radius: 10px;
$border-color-main: rgba(22, 233, 205, 1);
$border-color-error:rgba(201, 6, 6, 0.9);
$buttonColor : rgba(2, 104, 90, .6);

    .text-input-wrapper{
        width: 100%;
        background-color: white;

        .main-section{
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: $border-radius;
            border: 2px solid $border-color-main;

            input{
                border: none;
                border-radius: $border-radius;
                font-size: 16px;

                &:focus,
                &:active{
                    box-shadow: none;
                }
            }

            .form-control{
                // min-width: 90%;
                margin: 0 !important;
            }
        }

        .bottomborder,
        .noborder{
            $border-radius: 0;

            border: none;
            border-radius: $border-radius;
            border-bottom: 2px solid $border-color-main;
            
            input{
                border-radius: $border-radius;
                border-radius: 0;
            }
        }

        .noborder{
            border: none;
        }

        .sm{
            font-size: 12px;
        }

        .error{
            border-color: $border-color-error;
        }
    }

@media screen and (max-width:800px) {
    
    .text-input-wrapper{
        input{
            font-size: 14px;
        }
    }
}

@media screen and (max-width:400px) {
    
    .text-input-wrapper{

        input{
            font-size: 12px;
        }
    }
}
</style>