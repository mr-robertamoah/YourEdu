<template>
    <button class="btn btn-size" 
        :class="{active:makeActive, danger:makeDanger, 
            success:makeSuccess, disabled: makeDisabled}" 
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
            active: {
                type: Boolean,
                default: false
            },
            makeDisabled: {
                type: Boolean,
                default: false
            },
            mainActive: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                makeActive: false
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
                if (this.active) {
                    this.makeActive = this.mainActive ? false : !this.makeActive
                }
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
    }

    button:hover{
        box-shadow: 1px 1px 1px $special-button-border, -1px -1px 1px $special-button-border;
        transition: all .5s ease;
    }
    
    .danger{
        background-color: $danger-button-background;
    }
    
    .success{
        background-color: $success-button-background;
    }
    
    .active{
        box-shadow: 1px 1px 1px $special-button-border, -1px -1px 1px $special-button-border;
    }
    
    .disabled{
        pointer-events: none;
    }

    .btn-size{
        font-size: 14px;
    }

@media screen and (max-width: 800px){
    .btn-size{
        font-size: 11px;
    }
}
</style>