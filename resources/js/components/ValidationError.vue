<template>
    <div class="validation">
        <div>
            <div v-if="isString">
                {{errorString}}
            </div>
            <div v-else v-for="(error,key) in errors" :key="key">
                <div class="validation-errors">
                    {{error.toString()}}
                </div>
                <!-- <div class="validation-errors" v-else>
                    {{error}}
                </div> -->
            </div>
        </div>
        <div class="cursor-pointer" 
            @click="clearValidation"
        >
            <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            isString: {
                type: Boolean,
                default: true
            },
            errorString: {
                type: String,
                default: ''
            },
            errors: {
                type: Object,
                default: ()=>{
                    return {}
                }
            },
        },
        watch: {
            errors: {
                immediate : true,
                handler(newValue){
                    if (newValue.length > 0) {
                        this.disappearSoon()
                    }
                }
            },
            errorString: {
                immediate : true,
                handler(newValue){
                    if (newValue != '' || newValue != null) {
                        this.disappearSoon()
                    }
                }
            }
        },
        methods: {
            clearValidation() {
                this.$emit('clearValidation')
            },
            disappearSoon(){
                setTimeout(function() {
                    this.$emit('clearValidation')
                }.bind(this),5000)
            }
        },
    }
</script>

<style lang="scss" scoped>
$errorColor: rgba(201, 6, 6, 0.9);
$errorBackground: lighten($errorColor,40);

    .validation{
        width: 100%;
        padding: 5px;
        background-color: $errorBackground;
        font-weight: 700;
        color: $errorColor;
        border: 1px solid $errorColor;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 2000;
        border-radius: 10px;
        font-size: 16px;

        .validation-errors{
            padding: 5px;
            font-size: 16px;
        }

        .cursor-pointer{
            cursor: pointer;
            height: 100%;
        }
    }

@media screen and (max-width:800px) {
    
    .validation{
        font-size: 14px;
    }
}
</style>