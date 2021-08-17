<template>
    <button 
        class="btn min-w-content max-h-content" 
        :class="{'btn-size': ! computedHasFontSizeClasses}"
        :title="titleText"
        @click="onClick"
    >
        {{buttonText}}
        <slot  name="loader"></slot>
    </button>
</template>

<script>
    export default {
        props: {
            buttonText: {
                type: String,
                default: 'click'
            },
            titleText: {
                type: String,
                default: ''
            },
        },
        computed: {
            computedClasses() {
                return this.$vnode.data.staticClass ? this.$vnode.data.staticClass : ''
            },
            computedHasFontSizeClasses() {
                if (! this.computedClasses.length) {
                    return false
                }

                let classes = ['text-xs', 'text-sm', 'text-base']

                classes.forEach(cl=>{
                    if (this.computedClasses.includes(cl)) {
                        return true
                    }
                })

                return false
            }
        },
        methods: {
            onClick() {
                this.$emit('click')
            }
        },
    }
</script>

<style lang="scss" scoped>
    $special-button-color: azure;
    $special-button-background: azure;
    $special-button-border: rebeccapurple;

    button{
        border: 1px solid $special-button-border;
        box-shadow: -0.5px -0.5px 0.5px $special-button-border;
        border-radius: 5px;
        background-color: $special-button-background;
    }

    button:hover{
        box-shadow: 1px 1px 1px $special-button-border, -1px -1px 1px $special-button-border;
        transition: all .5s ease;
    }

    .btn-size{
        font-size: 16px;
    }

@media screen and (max-width: 800px){
    .btn-size{
        font-size: 13px;
    }
}
</style>