<template>
    <div class="text-input-wrapper"
        :title="title"
    >
        <div class="main-section"
            :class="{error:error,bottomborder:bottomBorder,
            noborder:noBorder,sm:sm}"
        >
            <input :type="inputType" 
                :placeholder="placeholder" 
                @input="change" 
                @keyup="checkInput" 
                :max="inputMax"
                :min="inputMin"
                class="form-control"
                :value="value"
                v-model="inputValue"
                :pattern="pattern"
                :inputmode="inputmode"
            >
            <div class="form-control-append eye-control" :title="title"
                v-if="prepend"
                @click="iconChange">
                <font-awesome-icon
                    :icon="icon"
                    @uploadedFiles="inputFiles"
                >
                </font-awesome-icon>
            </div>
        </div>
        <div class="max-section" v-if="hasMax">
            {{`${inputValue.length}/${inputMax}`}}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            placeholder: {
                type: String,
                default: ''
            },
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
            hasMax: {
                type: Boolean,
                default: false
            },
            inputType: {
                type: String,
                default: 'text'
            },
            inputMax: {
                type: Number,
                default: 100
            },
            inputMin: {
                type: Number,
                default: 5
            },
            value: {
                type: String,
                default: ''
            },
            title: {
                type: String,
                default: ''
            },
            prepend: {
                type: Boolean,
                default: false
            },
            icon: {
                type: Array,
                default: ()=>{
                    return []
                }
            },
        },
        data() {
            return {
                inputFiles: [],
                inputValue: '',
                inputmode: '',
                pattern: '',
            }
        },
        watch: {
            inputValue(newValue,oldValue) {
                if (this.inputType === 'text' && this.hasMax) {
                    if (newValue.length > this.inputMax) {
                        this.inputValue = oldValue
                    }
                }
            },
        },
        methods: {
            checkInput(event) {
                if (this.inputType === 'number') {
                    if (event.target.value < this.inputMin) {
                        event.target.value = this.inputMin
                    } else if (event.target.value > this.inputMax) {
                        event.target.value = this.inputMax
                    }
                }
            },
            change($event) {
                let value = $event.target.value 
                this.$emit('input',`${value}`)
            },
            iconChange() {
                this.$emit('iconChange')
            }
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
            
            .form-control-append{
                width: 10%;
                margin-right: 5px;
                display: flex;
                justify-content: center;
                align-items: center;
                color: $buttonColor;
                border-radius: $border-radius;
                cursor: pointer;
            }
        }

        .max-section{
            text-align: end;
            font-size: 12px;
            color: gray;
            font-weight: 500;
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