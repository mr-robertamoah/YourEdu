<template>
    <div class="text-input-wrapper"
            :class="{error:error,bottomborder:bottomBorder}">
        <input :type="textInput" :placeholder="placeholder" @input="change" 
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
            textInput: {
                type: String,
                default: 'text'
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
        methods: {
            change($event) {
                this.$emit('input',$event.target.value)
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
            min-width: 90%;
            margin: 0 !important;
        }
        
        .form-control-append{
            width: 10%;
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