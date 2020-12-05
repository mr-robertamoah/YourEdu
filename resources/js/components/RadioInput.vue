<template>
    <div class="check-input-wrapper">
        <input type="radio"
            :value="radioValue"
            @change="inputRadioMethod"
            ref="radioinput"
            :name="name"
            :id="id"
        >
        <label :for="id">{{label}}</label>
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
                this.$emit('input',newValue)
            },
            value(newValue) {
                if (newValue === this.inputRadio) {
                    this.$refs.radioinput.checked = true
                }
            }
        },
        methods: {
            inputRadioMethod() {
                this.inputRadio = this.$refs.radioinput.value

            }
        },
    }
</script>

<style lang="scss" scoped>
$background-color-main: rgba(22, 233, 205, 1);

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
            border-radius: 11px;
            width: 12px;
            height: 12px;
            position: absolute;
            top: 12px;
            left: 9px;
            content: '';
            display: block;
            background: $background-color-main;
        }

        input[type='radio']:checked + label{
            color: $background-color-main;
        }

        input[type='radio']:checked + label::before{
            border-color: $background-color-main;
        }
    }
</style>