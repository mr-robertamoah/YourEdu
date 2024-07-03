<template>
    <fade-down>
        <template slot="transition" v-if="show">
            <div class="post-attachment-wrapper" :class="{hasSelect:hasSelect}">
                <div class="close" 
                    @click="hidePostAttachment"
                    v-if="hasClose && hasSelect"
                >
                    <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                </div>
                <div class="numbers" v-if="!hasSelect">
                    {{`${attachmentsNumber} attachments`}}
                </div>
                <div class="buttons-section" 
                    ref="buttonsection"
                    v-if="!mainSearchItem.length"
                >
                    <template>
                        <div class="button"
                            :key="key"
                            v-for="(btn,key) in buttons"
                            :class="{active: searchItem === btn}"
                            @click="buttonClicked(btn)"
                        >{{btn}}</div>
                    </template>
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
                        :class="{itemActive: item === itemClass}"
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
                <div class="loading">
                    <pulse-loader
                        :loading="loading"
                        size="10px"
                    ></pulse-loader>
                </div>
                <div class="selection" v-if="hasSelect && items.length && !loading">
                    <select-input
                        :show="hasSelect"
                        :placeholder="computedPlaceholder"
                        :items="items"
                        @getMore="infiniteHandler"
                        :next="searchNextPage"
                        :special="true"
                        @clickedSelection="clickedAttachmentSelection"
                    ></select-input>
                </div>
                <div class="no-attachments" v-if="showNoAttachments && !loading">
                    there are no {{searchItem}}
                </div>
                <div class="creation" v-if="computedSearchItem.length && !loading">
                    <div class="info">
                        {{`can't find the ${computedSearchItem} you are looking for?`}}
                    </div>
                    <div class="create-button" @click="clickedCreateAttachment">
                        create
                    </div>
                </div>
                <!-- create subject -->
                <just-fade>
                    <template slot="transition" v-if="searchItem === 'subjects' 
                        && createAttachment">
                        <create-subject
                            :show="true"
                            @closeCreateSubject="closeCreateAttachment"
                            @attachmentCreated="attachmentCreated"
                        ></create-subject>
                    </template>
                </just-fade>
                <!-- create grade -->
                <just-fade>
                    <template slot="transition" v-if="searchItem === 'grades' 
                        && createAttachment">
                        <create-grade
                            :show="true"
                            @closeCreateGrade="closeCreateAttachment"
                            @attachmentCreated="attachmentCreated"
                        ></create-grade>
                    </template>
                </just-fade>
                <!-- create other types -->
                <just-fade>
                    <template slot="transition" v-if="computedCreateAttachment">
                        <create-attachment
                            :show="true"
                            :type="computedSearchItem"
                            @closeCreateAttachment="closeCreateAttachment"
                            @attachmentCreated="attachmentCreated"
                        ></create-attachment>
                    </template>
                </just-fade>
            </div>
        </template>
    </fade-down>
</template>

<script>
import SearchInput from './SearchInput.vue';
import SelectInput from './SelectInput.vue';
import TextTextarea from './TextTextarea.vue';
import FadeDown from './transitions/FadeDown.vue';
import CreateGrade from './forms/CreateGrade.vue';
import CreateSubject from './forms/CreateSubject.vue';
import CreateAttachment from './forms/CreateAttachment.vue';
import { mapActions } from 'vuex';
import { default as _ } from 'lodash';

    export default {
        components: {
            
            CreateAttachment,
            CreateSubject,
            CreateGrade,
            FadeDown,
            TextTextarea,
            SelectInput,
            SearchInput,
        },
        props: {
            buttons: {
                type: Array,
                default(){
                    return [
                        'subjects',
                        'grades',
                        'programs',
                        'courses',
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
            hasClose: {
                type: Boolean,
                default: true,
            },
            attachmentsNumber: {
                type: Number,
                default: 0,
            },
            isAttached: {
                type: Boolean,
                default: false,
            },
            mainSearchItem: {
                type: String,
                default: '',
            },
        },
        data() {
            return {
                items: [],
                item: null,
                searchItem: '',
                showLowerSection: false,
                createAttachment: false,
                showNoAttachments: false,
                showNote: false,
                searchText: '',
                searchNextPage: 1,
                inputNote: '',
                itemClass: null, //to help set class of clicked item
                loading: false,
            }
        },
        watch: {
            searchItem(newValue) {
                if (newValue.length) {
                    this.showLowerSection = true
                    this.searchText = ''
                    this.items = []
                    this.debouncedSearch()
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
            mainSearchItem:{
                immediate: true,
                handler(newValue){
                    if (newValue.length) {
                        this.searchItem = newValue
                    }
                }
            }
        },
        computed: {
            computedSearchItem() {
                return this.searchItem.slice(0,this.searchItem.length -1)
            },
            computedCreateAttachment(){
                return (this.searchItem !== 'grades' || this.searchItem !== 'subjects') 
                        && this.createAttachment
            },
            computedPlaceholder(){
                return this.mainSearchItem.length ? `select ${this.mainSearchItem}` : 
                    'select an attachment'
            },
        },
        methods: {
            ...mapActions(['profile/getGrades','profile/searchGrades',
                'profile/getSubjects','profile/searchSubjects',
                'profile/getPrograms','profile/searchPrograms',
                'profile/getCourses','profile/searchCourses']),
            closeCreateAttachment(){
                this.createAttachment = false
            },
            attachmentCreated(data){
                this.showNoAttachments = false
                this.items.unshift(data.attachment)
            },
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
            clickedCreateAttachment(){
                this.createAttachment = true
            },
            itemClicked(item) {
                this.itemClass = item
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
                if (data === this.searchItem) {
                    this.searchItem = ''
                    return
                }
                this.searchItem = data
                this.showNote = false
            },
            searchInput(data){
                this.searchText = data
                this.debouncedSearch()
            },
            debouncedSearch: _.debounce(function(){
                this.showNote = false
                this.search()
            },200),
            async search(){
                this.showNoAttachments = false
                this.loading = true

                let response = await this.getResponse()
                
                this.loading = false

                if (response.status) {
                    if (response.next) {
                        this.searchNextPage += 1
                    } else {
                        this.searchNextPage = null
                    }
                }
            },
            //create infinite scroll for searches when data grows
            async infiniteHandler(){
                
                let response = await this.getResponse()

                if (response.status) {
                    if (response.next) {
                        this.searchNextPage += 1
                    } else {
                        this.searchNextPage = null
                    }
                }
            },
            async getResponse(){
                let response
                
                if (this.searchItem === 'subjects') {
                    if (this.searchText.trim().length) {
                        
                        response = await this['profile/searchSubjects']({
                            searchText: this.searchText,
                            searchNextPage: this.searchNextPage
                        })
                    } else {
                        
                        response = await this['profile/getSubjects'](this.searchNextPage)
                    }
                } else if (this.searchItem === 'grades') {
                    if (this.searchText.trim().length) {
                        
                        response = await this['profile/searchGrades']({
                            searchText: this.searchText,
                            searchNextPage: this.searchNextPage
                        })
                    } else {
                        
                        response = await this['profile/getGrades'](this.searchNextPage)
                    }
                } else if (this.searchItem === 'programs') {
                    if (this.searchText.trim().length) {
                        
                        response = await this['profile/searchPrograms']({
                            searchText: this.searchText,
                            searchNextPage: this.searchNextPage
                        })
                    } else {
                        
                        response = await this['profile/getPrograms'](this.searchNextPage)
                    }
                } else if (this.searchItem === 'courses') {
                    if (this.searchText.trim().length) {
                        
                        response = await this['profile/searchCourses']({
                            searchText: this.searchText,
                            searchNextPage: this.searchNextPage
                        })
                    } else {
                        
                        response = await this['profile/getCourses'](this.searchNextPage)
                    }
                } else {
                    return
                }

                if (response.status) {
                    this.items.push(...response.data)
                    if (!this.items.length) this.showNoAttachments = true
                    
                    return {status: true,next: response.next}
                } else {
                    console.log('response :>> ', response);
                    return {status: false}
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
            font-size: 14px;

            .button{
                width: auto;
                margin: 0 10px 5px;
                font-size: 14px;
                background: gray;
                padding: 5px;
                border-radius: 10px;
                color: white;
                cursor: pointer;

                &:hover{
                    transition: all .5s ease;
                    background-color: green;
                }
            }

            .active{
                transition: all .5s ease;
                background-color: green;
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

            .itemActive{
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

        .no-attachments{
            font-size: 14px;
            padding: 10px;
            text-align: center;
            width: 100%;
            color: gray;
        }

        .creation{
            margin: 10px 0 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;

            .info{
                font-size: 12px;
                color: gray;
                margin-right: 10px;
            }

            .create-button{
                width: auto;
                margin: 0 10px;
                font-size: 14px;
                background: gray;
                padding: 5px;
                border-radius: 10px;
                color: white;
                cursor: pointer;

                &:hover{
                    transition: all .5s ease;
                    background-color: green;
                }
            }

            .active{
                transition: all .5s ease;
                background-color: green;
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
            cursor: pointer;
        }

        .buttons-section{
            .button{
                font-size: 12px;
            }
        }
    }
</style>