<template>
    <div class="text-input-wrapper">
        <div class="label" v-if="label.length">
            {{label}}
        </div>
        <div class="main-section"
            :class="{error,bottomBorder,
            noBorder,sm}"
        >
            <input type="number" 
                :placeholder="placeholder"
                v-model="inputNumber"
                :max="inputMax"
                :min="inputMin"
                class="form-control"
                ref="muberinput"
                pattern="[0-9]*"
                inputmode="numeric"
            >
        </div>
        <div class="prepend" v-if="prepend.length">
            {{prepend}}
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
            hasMax: {
                type: Boolean,
                default: true
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
            prepend: {
                type: String,
                default: ''
            },
            label: {
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
                if (newValue < this.inputMin) {
                    this.inputNumber = `${this.inputMin}`
                    return
                } else if (this.hasMax && newValue > this.inputMax) {
                    this.inputNumber = `${this.inputMax}`
                    return
                }
                this.$emit('numberinput', newValue)
                this.$emit('input', newValue)
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
                    this.inputNumber = `${this.inputMin}`
                    // this.$emit('numberinput',`${event.target.value}`)
                } else if (this.hasMax && event.target.value > this.inputMax) {
                    this.inputNumber = `${this.inputMax}`
                    // this.$emit('numberinput',`${event.target.value}`)
                }
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
        display: flex;
        width: 100%;
        align-items: center;
        background-color: white;

        .prepend{
            min-width: fit-content;
            margin-left: 10px;
        }

        .label{
            margin-right: 10px;
            color: gray;
            min-width: fit-content;
        }

        .main-section{
            display: flex;
            width: 100%;
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
                box-shadow: none;
                margin: 0 !important;
            }
        }

        .bottomBorder,
        .noBorder{
            $border-radius: 0;

            border: none;
            border-radius: $border-radius;
            border-bottom: 2px solid $border-color-main;
            
            input{
                border-radius: $border-radius;
                border-radius: 0;
            }
        }

        .noBorder{
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