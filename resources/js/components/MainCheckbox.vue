<template>
    <div class="main-checkbox-wrapper" :class="{small}">
        <input type="checkbox" :id="randomId" v-model="inputValue">
        <div class="no-icon" 
            @click="toggleValue" 
            ref="checkboxicon"
        >
            <font-awesome-icon 
                :icon="['fa','check']"
                v-if="value"
            ></font-awesome-icon>
        </div>
        <label :for="randomId">{{label}}</label>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: Boolean,
                default: false
            },
            label: {
                type: String,
                default: ''
            },
            small: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                inputValue: false,
                randomId: ''
            }
        },
        watch: {
            inputValue(newValue) {
                this.$refs.checkboxicon.classList.toggle('icon')
                this.$emit('input',newValue)
            },
            value: {
                immediate: true,
                handler(newValue){
                    this.inputValue = newValue
                }
            }
        },
        mounted () {
            this.randomId = `maincheckbox${Math.floor(Math.random() * 1000)}`
        },
        methods: {
            toggleValue() {
                this.inputValue = !this.inputValue
            }
        },
    }
</script>

<style lang="scss" scoped>
$border-color-main: rgba(22, 233, 205, 1);

    .main-checkbox-wrapper{
        display: flex;
        align-items: center;
        padding: 5px;
        cursor: pointer;

        .no-icon{
            width: 20px;
            height: 20px;
            border: 2px solid $border-color-main;
            margin-right: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            font-size: 14px;
            color: white;
        }

        .icon{
            background: $border-color-main;
            animation-name: icon;
            animation-duration: .4s;
            animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);
        }

        label{
            margin: 0;
            font-weight: 450;
            font-size: 16px;
            cursor: pointer;
            color: gray;
        }

        input[type="checkbox"]{
            display: none;
        }
    }
    
    .main-checkbox-wrapper.small{

        .no-icon{
            font-size: 9px;
            padding: 7px;
        }

        label{
            font-size: 12px;
        }
    }

@media screen and (max-width:800px) {
    
    .main-checkbox-wrapper{

        .no-icon{
            width: 15px;
            height: 15px;
            font-size: 10px;
            padding: 8px;
        }

        label{
            font-size: 14px;
        }
    }
}

    @keyframes icon {
        from{
            transform: scale(0);
            transform: rotateZ(75deg);
        }

        to{
            transform: scale(1);
            transform: rotateZ(0deg);
        }
    }
</style>