<template>
    <div class="text-input-wrapper"
        :class="{error:error,bottomborder:bottomBorder}"
        :title="title"
    >
        <input :type="inputType" 
            :placeholder="placeholder" 
            @input="change" 
            :max="inputMax"
            :min="inputMin"
            class="form-control"
            :value="value"
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
            }
        },
        watch: {
            value(newValue) {
                if (this.inputType === 'number') {
                    // if (newValue > this.inputMax) {
                    //     newValue = this.inputMax
                    // } else if (newValue < this.inputMin){
                    //     newValue = this.inputMin
                    // }
                }
            }
        },
        methods: {
            change($event) {
                let value = $event.target.value
                // if (value > this.inputMax) {
                //     value =  this.inputMax
                // } else if (value < this.inputMin) {
                //     value = this.inputMin
                // }
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
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: $border-radius;
        border: 2px solid $border-color-main;
        background-color: white;

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

    .bottomborder{
        $border-radius: 0;

        border: none;
        border-radius: $border-radius;
        border-bottom: 2px solid $border-color-main;
        
        input{
            border-radius: $border-radius;
            border-radius: 0;
        }
    }

    .error{
        border-color: $border-color-error;
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