<template>
    <div class="add-section-wrapper">
        <div class="sections-preview">
             <item-badge
                v-for="(item,index) in sections"
                :key="index"
                :item="item"
                type="courseSection"
                :hasRemove="true"
                class="item-badge"
                @clickedRemoveItem="clickedRemoveSection"
            ></item-badge>
        </div>
        <div class="add-section">
            <text-input
                :bottomBorder="true"
                placeholder="section name"
                v-model="section.name"
                class="class-input"
            ></text-input>
            <text-textarea
                :bottomBorder="true"
                placeholder="section description"
                v-model="section.description"
                class="class-input"
            ></text-textarea>
            <div class="add" @click="clickedAddSection">
                {{buttonText}}
            </div>
        </div>
    </div>
</template>

<script>
import TextTextarea from '../TextTextarea.vue';
import TextInput from '../TextInput.vue';
import ItemBadge from './ItemBadge.vue';
    export default {
        components: {
            ItemBadge,
            TextInput,
            TextTextarea,
        },
        props: {
            hasRemove: {
                type: Boolean,
                default: true
            },
            editable: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                section: {
                    id: '',
                    description: '',
                    name: ''
                },
                sections: [],
                buttonText: ''
            }
        },
        watch: {
            sections(newValue) {
                this.$emit('sectionAdded',newValue)
            },
            editable: {
                immediate: true,
                handler(newValue) {
                    if (!newValue) {
                        this.buttonText = 'add section'
                    } else {
                        this.section.name = newValue.name
                        this.section.description = newValue.description
                        this.buttonText = 'edit section'
                    }
                }
            }
        },
        methods: {
            clickedAddSection() {
                if (this.section.name.length) {
                    if (this.buttonText.includes('add')) {                        
                        this.sections.push({
                            id: Math.floor(Math.random() * 100),
                            description: this.section.description,
                            name: this.section.name
                        })
                    } else {
                        this.$emit('editedSection',{
                            id: this.editable.id,
                            description: this.section.description,
                            name: this.section.name
                        })
                    }
                    this.clearData()
                } else {
                    this.$emit('error', {
                        message: 'please, a course section requires a name.'})
                }
            },
            clickedRemoveSection(section) {
                let index = this.sections.findIndex(sec=>{
                    return sec.id === section.id
                })
                if (index > -1) {
                    this.sections.splice(index,1)
                }
            },
            clearData() {
                this.section.id = ''
                this.section.description = ''
                this.section.name = ''
            },
        },
    }
</script>

<style lang="scss" scoped>

    .add-section-wrapper{

        .add-section{

            .add{
                color: gray;
                width: fit-content;
                margin: 5px auto 10px;
                padding: 5px 10px;
                border-radius: 5px;
                box-shadow: 0 0 3px grey;
                font-size: 12px;
                cursor: pointer;
            }
        }

        .sections-preview{
            position: relative;
            display: flex;
            overflow-y: auto;
            align-items: center;
        }
    }
</style>