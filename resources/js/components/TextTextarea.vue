<template>
    <div class="text-textarea-wrapper">
        <div class="main"
            :class="{error:error,bottomborder:bottomBorder}"
        >
            <textarea 
                :placeholder="placeholder" 
                @input="change"
                :value="value"
                class="form-control"
                :class="{transparent: ttaClass === 'transparent'}"
            ></textarea>
        </div>
        <div class="prepend" 
            v-if="prepend.length"
            @click="clickedPrepend"
        >
            <font-awesome-icon
                :icon="['fa',prepend]"
            ></font-awesome-icon>
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
            prepend: {
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
            },
            clickedPrepend() {
                this.$emit('clickedPrepend')
            },
        },
    }
</script>

<style lang="scss" scoped>
$border-radius: 10px;
$border-color-main: rgba(22, 233, 205, 1);
$border-color-error:rgba(201, 6, 6, 0.9);

    .text-textarea-wrapper{
        display: flex;
        width: 100%;
        align-items: center;
        background-color: white;

        .prepend{
            min-width: fit-content;
            margin-left: 10px;
            padding: 10px;
        }

        .main{
            width: 100%;
        }

        textarea{
            border: none;
            border-radius: 0;
            width: 100%;
            font-size: 16px;
            width: 100%;
            font-weight: 500;
            color: black;

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
        textarea,
        textarea::placeholder{
            font-size: 14px;
        }
    }
}

@media screen and (max-width:300px) {
    
    .text-textarea-wrapper{

        textarea,
        textarea::placeholder{
            font-size: 12px;
        }
    }
}
</style>