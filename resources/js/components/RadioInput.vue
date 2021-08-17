<template>
    <div class="check-input-wrapper" :class="{checked: value === radioValue}">
        <input type="radio"
            :value="computedValue"
            @change="inputRadioMethod"
            ref="radioinput"
            :name="name"
            :id="id"
        >
        <label class="label" :for="id">{{label}}</label>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                default: ''
            },
            radioValue: {
                type: String,
                default: ''
            },
            name: {
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
                inputRadio: '',
                id: Math.floor(Math.random()*100),
            }
        },
        watch: {
            inputRadio(newValue) {
                if (newValue.length) {
                    this.$emit('input',newValue)
                    this.inputRadio = ''
                }
            },
            value: {
                immediate: true,
                handler(newValue) {
                    if (newValue === this.radioValue && this.$refs.radioinput) {
                        this.$refs.radioinput.checked = true
                    } else if (this.$refs.radioinput) {
                        this.$refs.radioinput.checked = false
                    }
                }
            }
        },
        computed: {
            computedValue() {
                return this.radioValue && this.radioValue.length ? this.radioValue : this.label 
            }
        },
        methods: {
            inputRadioMethod() {
                this.inputRadio = this.$refs.radioinput.value
                // console.log(this.inputRadio);
            }
        },
    }
</script>

<style lang="scss" scoped>
@mixin label-after() {
    border-radius: 11px;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 8px;
    left: 10px;
    content: '';
    display: block;
    background: $color-primary;
}

    .check-input-wrapper{
        position: relative;

        input[type='radio']{
            display: none;                
        }

        label{
            color: gray;
            font-weight: normal;
            font-size: 12px;
            margin: 5px;
            cursor: pointer;
            
            &::before{
                content: '';
                position: relative;
                top: 3px;
                margin: 0 5px 0 0;
                display: inline-flex;
                width: 20px;
                height: 20px;
                border-radius: 11px;
                border: 2px solid gray;
                background-color: transparent;
            }
        }

        input[type='radio']:checked + label::after{
            @include label-after()
        }

        input[type='radio']:checked + label,
        .checked.label{
            color: $color-primary;
        }

        input[type='radio']:checked + label::before{
            border-color: $color-primary;
        }
    }

    .checked{

        label{
            color: $color-primary;

            &::before{
                border-color: $color-primary;
            }

            &::after{
                @include label-after()
            }
        }
    }
</style>