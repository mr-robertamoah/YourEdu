<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div class="options-wrapper" :class="{horizontal}">
                <div class="option" v-if="hasSave && !hasOthers">
                    <font-awesome-icon :icon="['fa','bookmark']"></font-awesome-icon>
                    <span @click="clickedOption('save')">
                        {{isSaved ? 'unsave' : 'save'}}
                    </span>
                </div>
                <div class="option" v-if="hasAttachment && !hasOthers">
                    <font-awesome-icon :icon="['fa','paperclip']"></font-awesome-icon>
                    <span @click="clickedOption('attach')">
                        attach
                    </span>
                </div>
                <div class="option" v-if="hasEdit && showEdit && !hasOthers">
                    <font-awesome-icon :icon="['fa','pen']"></font-awesome-icon>
                    <span @click="clickedOption('edit')">edit</span>
                </div>
                <div class="option" v-if="hasDelete && !hasOthers">
                    <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
                    <span @click="clickedOption('delete')">delete</span>
                </div>
                <div class="option" v-if="hasExtra && !hasOthers">
                    <slot name="extraicon"></slot>
                    <span @click="clickedOption(extraText)">{{extraText}}</span>
                </div>
                <template v-if="hasOthers">
                    <div class="option"
                        v-for="(other,index) in others"
                        :key="index"
                    >
                        <font-awesome-icon
                            v-if="other.icon"
                            :icon="other.icon"></font-awesome-icon>
                        <span @click="clickedOption(other)">
                            {{other.nanme ? other.name : other}}
                        </span>
                    </div>
                </template>
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
            horizontal: {
                type: Boolean,
                default: false
            },
            hasDelete: {
                type: Boolean,
                default: true
            },
            hasOthers: {
                type: Boolean,
                default: false
            },
            others: {
                type: Array,
                default(){
                    return []
                }
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
        z-index: 1;

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

        &.horizontal{
            width: fit-content;
            max-width: 300px;
            overflow-x: hidden;
            overflow-y: auto;
            max-height: 100px;
            padding: 5px 10px;
        }
    }

    .horizontal{

        .option{
            width: fit-content;
        }
    }
</style>