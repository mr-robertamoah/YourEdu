<template>
    <div class="attachment-item-wrapper"
        :class="{borderRadius:!hasClose}"
        @click="clickedBadge"
    >
        {{computedName}}
        <div class="close" 
            @click="clickedClose"
            v-if="hasClose"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            attachment: {
                type: Object,
                default(){
                    return {}
                }
            },
            file: {
                type: File,
                default: null
            },
            type: {
                type: String,
                default: ''
            },
            hasClose: {
                type: Boolean,
                default: false
            },
        },
        computed: {
            computedName() {
                return this.attachment.name ? this.attachment.name : 
                    this.file ? this.file.name : ''
            }
        },
        methods: {
            clickedClose() {
                this.$emit('removeAttachment',{
                    type: this.type,
                    data: this.attachment.name ? this.attachment : this.file
                })
            },
            clickedBadge(){
                this.$emit('click')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .attachment-item-wrapper{
        margin: 0 10px 10px;
        width: fit-content;
        padding: 10px;
        font-size: 14px;
        text-align: center;
        position: relative;
        background-color: mintcream;
        color: gray;
        cursor: pointer;

        .close{
            font-size: 14px;
            color: red;
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }
    }

    .borderRadius{
        border-radius: 10px;
    }
</style>