<template>
    <fade-down>
        <template slot="transition" v-if="show">
            <div class="post-attachment-wrapper" :class="{hasSelect:hasSelect}">
                <div class="close" 
                    @click="hidePostAttachment"
                    v-if="hasSelect"
                >
                    <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                </div>
                <div class="numbers" v-if="!hasSelect">
                    {{`${attachmentsNumber} attachments`}}
                </div>
                <div class="buttons-section" ref="buttonsection">
                    <div class="button"
                        :key="key"
                        v-for="(btn,key) in btns"
                        @click="buttonClicked(btn)"
                    >{{btn}}</div>
                </div>
                <div class="search-section" v-if="showLowerSection">
                    <search-input
                        @search="searchInput"
                        :background="false"
                        :value="searchText"
                        paClass="gray"
                    ></search-input>
                </div>
                <div class="preview-section" ref="previewsection"
                    v-if="showLowerSection && !hasSelect"
                >
                    <div class="item"
                        v-for="(item,key) in items"
                        :key="key"
                        @click="itemClicked(item)"
                    >
                        {{item.name ? item.name : item}}
                    </div>
                </div>
                <div class="note-section" v-if="showNote && !hasSelect">
                    <text-textarea
                        v-model="inputNote"
                        :hasMax="true"
                        ttaClass="transparent"
                        placeholder="what should we note?"
                    ></text-textarea>
                    <div class="add-attachment" @click="clickedAttach">
                        attach
                    </div>
                </div>
                <div class="attachment-section" v-if="isAttached && !hasSelect">
                    your attachments
                    <div class="attachment"
                        v-for="attachment in attachments"
                        :key="attachment.id"
                    >
                        <div class="name">{{attachment.with}}</div>
                        <div>{{attachment.with_type}}</div>
                        <div class="close" @click="clickedUnattach(attachment)"
                            title="remove attachment"
                        >
                            <font-awesome-icon
                                :icon="['fa','times']"
                            ></font-awesome-icon>
                        </div>
                    </div>
                </div>
                <div class="selection" v-if="hasSelect && items.length">
                    <select-input
                        :show="hasSelect"
                        placeholder="select an attachment"
                        :items="items"
                        :special="true"
                        @clickedSelection="clickedAttachmentSelection"
                    ></select-input>
                </div>
            </div>
        </template>
    </fade-down>
</template>

<script>
import SearchInput from './SearchInput';
import SelectInput from './SelectInput';
import TextTextarea from './TextTextarea';
import FadeDown from './transitions/FadeDown';
import _ from 'lodash';
import { mapActions } from 'vuex';

    export default {
        props: {
            btns: {
                type: Array,
                default(){
                    return [
                        'subjects',
                        'grades',
                    ]
                }
            },
            attachments: {
                type: Array,
                default(){
                    return []
                }
            },
            show: {
                type: Boolean,
                default: false,
            },
            hasSelect: {
                type: Boolean,
                default: false,
            },
            attachmentsNumber: {
                type: Number,
                default: 0,
            },
            isAttached: {
                type: Boolean,
                default: false,
            },
        },
        components: {
            FadeDown,
            TextTextarea,
            SelectInput,
            SearchInput,
        },
        data() {
            return {
                items: [],
                item: null,
                searchItem: '',
                showLowerSection: false,
                showNote: false,
                searchText: '',
                inputNote: '',
            }
        },
        watch: {
            searchItem(newValue) {
                if (newValue.length) {
                    this.showLowerSection = true
                } else {
                    this.showLowerSection = false
                }
            },
            show(newValue){
                if (!newValue) {
                    this.items = []
                    this.item = null
                    this.searchItem = ''
                    this.showLowerSection = false
                    this.searchText = ''
                    this.inputNote = ''
                }
            },
        },
        methods: {
            ...mapActions(['profile/getGrades','profile/searchGrades',
                'profile/getSubjects','profile/searchSubjects']),
            clickedUnattach(attachment){
                this.$emit('clickedUnattach', attachment)
            },
            hidePostAttachment(){
                this.$emit('hidePostAttachment')
            },
            clickedAttachmentSelection(data) {
                this.$emit('clickedAttachmentSelection',{type: this.searchItem, data})
            },
            clickedAttach(){
                let data = this.item

                data.note = this.inputNote.trim()
                
                this.$emit('itemClicked',data)
            },
            itemClicked(item) {
                for (let i = 0; i < this.$refs.previewsection.children.length; i++) {
                    const child = this.$refs.previewsection.children[i]

                    if (child.classList.contains('active')) {
                        child.classList.remove('active')
                    }

                    if (child.innerText === item) {
                        child.classList.add('active')
                    }
                }

                let data = {}
                data.itemId = item.id
                if (this.searchItem === 'subjects') {
                    data.item = 'subject'
                } else if (this.searchItem === 'grades') {
                    data.item = 'grade'
                }
                this.item = data
                this.showNote = true
            },
            buttonClicked(data) {
                for (let i = 0; i < this.$refs.buttonsection.children.length; i++) {
                    const child = this.$refs.buttonsection.children[i]

                    if (child.classList.contains('active')) {
                        child.classList.remove('active')
                    }

                    if (child.innerText === data) {
                        child.classList.add('active')
                    }
                }
                this.searchItem = data
                this.showNote = false
                this.searchText = ''
                this.items = []
                this.debouncedSearch()
            },
            searchInput(data){
                this.searchText = data
                this.debouncedSearch()
            },
            debouncedSearch: _.debounce(function(){
                this.showNote = false
                this.search()
            },100),
            async search(){

                let response = null
                if (this.searchItem === 'subjects') {
                    if (this.searchText.trim().length) {
                        
                        response = await this['profile/searchSubjects'](this.searchText)
                    } else {
                        
                        response = await this['profile/getSubjects']()
                    }
                } else if (this.searchItem === 'grades') {
                    if (this.searchText.trim().length) {
                        
                        response = await this['profile/searchGrades'](this.searchText)
                    } else {
                        
                        response = await this['profile/getGrades']()
                    }
                }

                if (response.status) {
                    this.items = this.searchItem === 'subjects' ? response.data.subjects :
                        this.searchItem === 'grades' ? response.data.grades : []
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .post-attachment-wrapper{
        width: 90%;
        margin: 10px auto 0;
        background: mintcream;
        border-left: 2px solid gray;
        box-shadow: 0 0 2px grey;
        border-radius: 0 10px 10px 0; 
        padding: 10px;

        .numbers{
            font-size: 12px;
            color: gray;
            text-align: start;
        }

        .buttons-section{
            width: 90%;
            margin: 5px auto 0;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;

            .button{
                width: auto;
                margin: 0 10px;
                font-size: 14px;
                background: rgba(128,128,128,.75);
                padding: 5px;
                border-radius: 15%;
                color: aliceblue;
                cursor: pointer;

                &:hover{
                    transition: all .5s ease;
                    color: whitesmoke;
                    background-color: gray;
                }
            }

            .active{
                transition: all .5s ease;
                color: whitesmoke;
                background-color: gray;
            }
        }

        .note-section{
            width: 80%;
            margin: 5px auto;

            .add-attachment{
                color: green;
                padding: 5px;
                box-shadow: 0 0 2px grey;
                width: fit-content;
                margin: 5px 0 5px auto;
                cursor: pointer;
                font-size: 12px;
            }
        }

        .preview-section{
            font-size: 12px;
            padding: 10px;
            max-height: 400px;
            margin: 0 auto 10px;
            width: 70%;
            overflow-y: auto;
            // border: 1px solid gray;
            border-top: none;
            display: flex;
            justify-content: flex-start;
            align-items: center;

            .item{
                padding: 5px;
                font-weight: 450;
                text-align: center;
                box-shadow: 0 0 1px gray;
                border-radius: 5px;
                margin-right: 10px;
                cursor: pointer;

                &:hover{
                    background-color: whitesmoke;
                    box-shadow: 0 0 2px gray;
                }
            }

            .active{
                background-color: whitesmoke;
                box-shadow: 0 0 2px gray;
            }
        }

        .attachment-section{
            width: 85%;
            margin: 5px auto;
            color: gray;
            font-size: 14px;
            text-align: center;

            .attachment{
                display: inline-flex;
                justify-content: space-around;
                width: 100%;
                align-items: center;
                box-shadow: 0 0 2px;
                border-radius: 5px;
                padding: 5px;
                font-size: 12px;
                color: black;
                margin-top: 5px;

                .name{
                    font-size: 14px;
                    font-weight: 500;
                    text-transform: capitalize;
                }

                .close{
                    color: red;
                    cursor: pointer;
                    padding: 5px;
                    font-size: 12px;
                }
            }
        }
    }

    .hasSelect{
        width: 100%;
        margin: 10px 0 0;
        position: relative;

        .close{
            font-size: 14px;
            color: red;
            position: absolute;
            right: 10px;
            top: 10px;
            padding: 5px;
        }

        .buttons-section{
            .button{
                font-size: 12px;
            }
        }
    }
</style>