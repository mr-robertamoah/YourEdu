<template>
    <div class="add-link-wrapper">
        <div class="preview">
            <link-badge class="link-preview"
                v-for="(lnk, index) in links"
                :key="index"
                :link="lnk"
                @clickedRemoveLink="clickedRemoveLink"
            ></link-badge>
        </div>
        <div class="add-link">
            <text-input
                :bottomBorder="true"
                placeholder="link name"
                v-model="link.name"
                class="class-input"
            ></text-input>
            <text-textarea
                :bottomBorder="true"
                placeholder="link*"
                v-model="link.link"
                class="class-input"
            ></text-textarea>
            <text-textarea
                :bottomBorder="true"
                placeholder="link description"
                v-model="link.description"
                class="class-input"
            ></text-textarea>
            <div class="add" @click="clickedAddLink">
                {{buttonText}}
            </div>
        </div>
        <div class="main-preview">

        </div>
    </div>
</template>

<script>
import TextTextarea from './TextTextarea.vue';
import TextInput from './TextInput.vue';
import LinkBadge from './LinkBadge.vue';
    export default {
        components: {
            TextInput,
            TextTextarea,
            LinkBadge,
        },
        props: {
            editable: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                link: {
                    link: '',
                    id: '',
                    description: '',
                    name: ''
                },
                links: [],
                buttonText: ''
            }
        },
        watch: {
            links(newValue) {
                this.$emit('linkAdded',newValue)
            },
            editable: {
                immediate: true,
                handler(newValue) {
                    if (!newValue) {
                        this.buttonText = 'add section'
                    } else {
                        this.link.name = newValue.name
                        this.link.description = newValue.description
                        this.link.link = newValue.link
                        this.buttonText = 'edit section'
                    }
                }
            }
        },
        methods: {
            clickedAddLink() {
                if (this.link.link.length) {
                    if (this.buttonText.includes('add')) { 
                        this.links.push({
                            id: Math.floor(Math.random() * 100),
                            link: this.link.link,
                            description: this.link.description,
                            name: this.link.name
                        })
                    } else {
                        this.$emit('editedLink',{
                            id: this.editable.id,
                            description: this.link.description,
                            name: this.link.name,
                            link: this.link.link
                        })
                    }
                    this.clearData()
                } else {
                    this.$emit('linkError', 
                        {message: 'please be sure to add the actual link to the link text box.'}
                    )
                }
            },
            clickedRemoveLink(link) {
                let index = this.links.findIndex(lnk=>{
                    return lnk.id === link.id
                })
                if (index > -1) {
                    this.links.splice(index,1)
                }
            },
            clearData() {
                this.link.name = ''
                this.link.id = ''
                this.link.description = ''
                this.link.link = ''
            },
        },
    }
</script>

<style lang="scss" scoped>

    .add-link-wrapper{

        .add-link{

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

        .preview{
            display: flex;
            overflow-y: auto;
            align-items: center;
            padding: 10px;
        }

        .link-preview{

        }
    }
</style>