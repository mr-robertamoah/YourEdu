<template>
    <div class="text-textarea-wrapper"
            :class="{error:error,bottomborder:bottomBorder}">
        <textarea type="text" :placeholder="placeholder" 
            @input="change"
            :value="value"
            class="form-control"
            :class="{transparent: ttaClass === 'transparent'}"
        ></textarea>
    </div>
</template>

<script>
    export default {
        props: {
            placeholder: {
                type: String,
                default: ''
            },
            value: {
                type: String,
                default: ''
            },
            ttaClass: {
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
            inputMax: {
                type: Number,
                default: 100
            },
            hasMax: {
                type: Boolean,
                default: false
            },
        },
        watch: {
            value(newValue, oldValue) {
                if (this.hasMax) {
                    if (newValue.length > this.inputMax) {
                        this.$emit('input',oldValue)
                    }
                }
            }
        },
        data() {
            return {
                
            }
        },
        methods: {
            change($event) {
                this.$emit('input',$event.target.value)
            }
        },
    }
</script>

<style lang="scss" scoped>
$border-radius: 10px;
$border-color-main: rgba(22, 233, 205, 1);
$border-color-error:rgba(201, 6, 6, 0.9);

    .text-textarea-wrapper{
        width: auto;

        textarea{
            border: none;
            border-radius: 0;
            width: 100%;
            font-size: 16px;
            width: 100%;

            &:focus,
            &:active{
                box-shadow: none;
            }
        }

        .transparent{
            background-color: transparent;
        }
    }

    .bottomborder{
        $border-radius: none;

        border: none;
        border-radius: $border-radius;
        border-bottom: 2px solid $border-color-main;
        
        input{
            border-radius: $border-radius;
            border-radius: 0;
        }
    }

    .error{
        border: 2px solid $border-color-error;
    }

@media screen and (max-width:800px) {
    
    .text-textarea-wrapper{
        textarea{
            font-size: 14px;
        }
    }
}

@media screen and (max-width:300px) {
    
    .text-textarea-wrapper{

        textarea{
            font-size: 12px;
        }
    }
}
</style>