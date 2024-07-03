<template>
    <div class="text-input-wrapper"
            :class="{bottomborder:bottomBorder}">
        <flat-pickr class="form-control"
            v-model="datePicked"
            :config="flatPickrConfig"
            :placeholder="placeholder"
        >
        </flat-pickr>
    </div>
</template>

<script>
    export default {
        components: {
            
        },
        props: {
            flatPickrConfig: {
                type: Object,
                default (){
                    return {
                        maxDate: 'today',
                        dateFormat: 'F j, Y',
                        altFormat: 'F j, Y',
                    }
                }
            },
            bottomBorder: {
                type: Boolean,
                default: false
            },
            value: {
                type: String,
                default: ''
            },
            placeholder: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                datePicked: ''
            }
        },
        watch: {
            datePicked(newValue) {
                this.$emit('datePicked', newValue)
                this.$emit('input', newValue)
            },
            value:{
                immediate: true,
                handler(newValue){
                    if (this.datePicked !== newValue) {
                        this.datePicked = newValue
                    }
                }
            },
            placeholder:{
                immediate: true,
                handler(newValue){
                    // if (new Date(newValue).toString() != 'Invalid Date') {
                    //     this.datePicked = newValue
                    // } else {
                    //     this.datePicked = ''
                    // }                    
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