<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div class="options-wrapper">
                <div class="option" v-if="hasSave">
                    <font-awesome-icon :icon="['fa','bookmark']"></font-awesome-icon>
                    <span @click="clickedOption('save')">
                        {{isSaved ? 'unsave' : 'save'}}
                    </span>
                </div>
                <div class="option" v-if="hasAttachment">
                    <font-awesome-icon :icon="['fa','paperclip']"></font-awesome-icon>
                    <span @click="clickedOption('attach')">
                        attach
                    </span>
                </div>
                <div class="option" v-if="hasEdit && showEdit">
                    <font-awesome-icon :icon="['fa','pen']"></font-awesome-icon>
                    <span @click="clickedOption('edit')">edit</span>
                </div>
                <div class="option" v-if="hasDelete">
                    <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
                    <span @click="clickedOption('delete')">delete</span>
                </div>
                <div class="option" v-if="hasExtra">
                    <slot name="extraicon"></slot>
                    <span @click="clickedOption(extraText)">{{extraText}}</span>
                </div>
            </div>
        </template>
    </just-fade>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            hasSave: {
                type: Boolean,
                default: true
            },
            hasAttachment: {
                type: Boolean,
                default: true
            },
            isSaved: {
                type: Boolean,
                default: false
            },
            hasEdit: {
                type: Boolean,
                default: true
            },
            hasDelete: {
                type: Boolean,
                default: true
            },
            hasExtra: {
                type: Boolean,
                default: false
            },
            extraText: {
                type: String,
                default: ''
            },
            showEdit: { //need to find the use of this
                type: Boolean,
                default: true
            },
        },
        components: {
            
        },
        methods: {
            clickedOption(data) {
                this.$emit('clickedOption', data)
            }
        },
    }
</script>

<style lang="scss" scoped>
    

    .options-wrapper{
        font-size: 12px;
        margin: 5px;
        background-color: whitesmoke;
        width: 100px;
        position: absolute;
        right: 0;
        top: 15px;

        .option{
            cursor: pointer;
            padding: 5px; 
            display: inline-flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;

            &:hover{
                box-shadow: 0 0 3px dimgray;
            }

        }
    }
</style>