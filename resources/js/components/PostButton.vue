<template>
    <button class="p-1 btn btn-size" 
        :class="{active:makeActive, danger:makeDanger, 
            success:makeSuccess, disabled: makeDisabled,colorRed:colorRed}" 
        :title="titleText"
        @click="clicked"
    >
        {{buttonText}}
        <slot v-if="!buttonText.length" name="icon"></slot>
    </button>
</template>

<script>
    export default {
        name: 'PostButton',
        props: {
            buttonText: {
                type: String,
                default: ''
            },
            buttonStyle: {
                type: String,
                default: 'normal'
            },
            titleText: {
                type: String,
                default: ''
            },
            postButtonClass: {
                type: String,
                default: ''
            },
            active: {
                type: Boolean,
                default: false
            },
            makeDisabled: {
                type: Boolean,
                default: false
            },
            // mainActive: {
            //     type: Boolean,
            //     default: false
            // },
        },
        watch: {
            postButtonClass(newValue) {
                if (newValue === 'red') {
                    this.colorRed = true
                }
            },
            active:{
                immediate:true,
                handler(newValue){
                    this.makeActive = newValue
                }
            },
        },
        data() {
            return {
                makeActive: false,
                colorRed : false,
            }
        },
        computed: {
            makeDanger() {
                return this.buttonStyle === 'danger' ? true : false
            },
            makeSuccess() {
                return this.buttonStyle === 'success' ? true : false
            },
        },
        methods: {
            clicked() {
                this.$emit('click', this.buttonText)
                
            }
        },
    }
</script>

<style lang="scss" scoped>
    $special-button-border: dimgrey;
    $special-button-background: azure;
    $success-button-background: rgba(0, 128, 0, 0.4);
    $danger-button-background: rgba(255, 0, 0, 0.4);

    button{
        border: 1px solid $special-button-border;
        border-radius: 5px;
        background-color: $special-button-background;
        min-width: 25px;
    }

    button:hover{
        box-shadow: 1px 1px 1px $special-button-border, -1px -1px 1px $special-button-border;
        transition: all .5s ease;
    }
    
    .danger{
        background-color: $danger-button-background;
        color: white;
    }
    
    .success{
        background-color: $success-button-background;
        color: white;
    }
    
    .active{
        background: lightgreen;
        box-shadow: 1px 1px 1px $special-button-border, -1px -1px 1px $special-button-border;
    }
    
    .disabled{
        pointer-events: none;
    }

    .btn-size{
        font-size: 14px;
    }

    .colorRed{

    }

@media screen and (max-width: 800px){
    .btn-size{
        font-size: 11px;
    }
}
</style>