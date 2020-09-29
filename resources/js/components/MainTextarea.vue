<template>
    <div class="main-textarea-wrapper">

        <textarea :name="textName" :id="textId" class="form-control" :disabled="disabled"
            :placeholder="textPlaceholder" 
            @input="inputValue"
            :value="value"
            :style="inputStyle"
            ref="inputtextarea"
            :class="{sm:sm}"
        ></textarea>
        <div class="max-section" v-if="hasMax">
            {{`${value.length}/${inputMax}`}}
        </div>
    </div>
</template>

<script>

    export default {
        watch: {
            value() {
                this.autoResize()
            }
        },
        props: {
            textName: {
                type: String,
                default: ''
            },
            disabled: {
                type: Boolean,
                default: false
            },
            sm: {
                type: Boolean,
                default: false
            },
            value: {
                type: String,
                default: ''
            },
            hasMax: {
                type: Boolean,
                default: false
            },
            inputMax: {
                type: Number,
                default: 100
            },
            textPlaceholder: {
                type: String,
                default: ''
            },
            textId() {
                return {
                    type: String,
                    default: ''
                }
            },
        },
        data() {
            return {
                inputHeight: '0',
            }
        },
        watch: {
            value(newValue, oldValue) { //uses @input and value to implement
                if (this.hasMax) {
                    if (newValue.length > this.inputMax) {
                        this.$emit('input',oldValue)
                    }
                }
            }
        },
        computed: {
            inputStyle () {
                return {
                    'min-height': this.inputHeight
                }
            }
        },
        mounted () {
            this.autoResize()
        },
        methods: {
            inputValue($event) {
                this.$emit('input',$event.target.value)
                this.autoResize()
            },
            autoResize(){
                // console.log(this.$refs.inputtextarea)
                if (this.$refs.inputtextarea) {
                    
                    this.$refs.inputtextarea.style.height = "1px";
                    this.$refs.inputtextarea.style.height = `${this.$refs.inputtextarea.scrollHeight}px`;
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
$input-main-border-color: rgba(105, 105, 105,1);

    textarea{
        overflow: visible;
        font-size: 16px;
        border: none;
        border-radius: 0;
        background-color: inherit;
        resize: none;
        overflow: hidden;

        &:active,
        &:disabled,
        &:focus{
            box-shadow: none;
            border: none;
            background-color: inherit;
        }

        &:disabled{
            // resize: none;
            // min-height: 60px;
        }

        .sm{
            font-size: 11px;
        }

        .max-section{
            text-align: end;
            font-size: 12px;
            color: gray;
            font-weight: 500;
        }
    }


@media screen  and (max-width: 800px) and (min-width: 1100px){
    
    textarea{
        font-size: 14px;
    }
}


@media screen  and (max-width: 800px){
    
    textarea{
        font-size: 14px;
    }
}
</style>