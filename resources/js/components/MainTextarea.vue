<template>
    <textarea :name="textName" :id="textId" class="form-control" :disabled="disabled"
        :placeholder="textPlaceholder" 
        @input="$emit('change',$event.target.value)"
        :value="value"
    ></textarea>
</template>

<script>

    export default {
        model:{
            prop: '',
            event: 'change',
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
            value: {
                type: String,
                default: ''
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

        &:active,
        &:disabled,
        &:focus{
            box-shadow: none;
            border: none;
            background-color: inherit;
        }

        &:disabled{
            resize: vertical;
            min-height: 60px;
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