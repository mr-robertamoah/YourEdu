<template>
    <div class="dashboard-item-wrapper"
        :class="{'dashboard-item':showOther,active}"
        @click.self="clickedMain"
    >
        <div class="main"
            @click="clickedMain"
        >
            <div class="heading">
                {{heading}}
            </div>
            <div class="top" v-if="name.length && hasItems">
                <div class="label">name</div>
                <div class="value">{{name}}</div>
            </div>
            <div class="middle">
                <div class="info"
                    v-for="(i,index) in info"
                    :key="index"
                >{{i}}</div>
            </div>
            <div class="bottom">
                {{specialInfo}}
            </div>
        </div>
        <div class="other" v-if="hasItems && showOther">
            <search-input
                v-if="hasSearch && items.length > 5"
                @search="getSearchText"
                :placeholder="computedplaceholder"
                class="search-input"
            ></search-input>
            <template v-if="items.length">
                <div class="item"
                    v-for="(i,index) in computedItems"
                    :key="index"
                    @click="clickedItem(i)"
                >
                    <div class="section1"
                        v-if="i.sectionOne"
                    >{{i.sectionOne}}</div>
                    <div class="section2"
                        v-if="i.sectionTwo"
                    >{{i.sectionTwo}}</div>
                    <div class="section3"
                        v-if="i.sectionThree"
                    >{{i.sectionThree}}</div>
                </div>
            </template>
            <div class="no-items" v-else>
                {{`no ${heading} data`}}
            </div>
        </div>
    </div>
</template>

<script>
import SearchInput from '../SearchInput.vue';
export default {
    components: {
        SearchInput,
    },
    props: {
        hasItems: {
            type: Boolean,
            default: false
        },
        hasSearch: {
            type: Boolean,
            default: false
        },
        active: {
            type: Boolean,
            default: false
        },
        items: {
            type: Array,
            default(){
                return []
            }
        },
        info: {
            type: Array,
            default(){
                return []
            }
        },
        heading: {
            type: String,
            default: ''
        },
        specialInfo: {
            type: String,
            default: ''
        },
        name: {
            type: String,
            default: ''
        },
    },
    data() {
        return {
            showOther: false,
            searchText: '', 
        }
    },
    computed: {
        computedItems() {
            return this.hasSearch ? this.items.filter(item=>{
                return item.sectionOne && item.sectionOne.toLowerCase().includes(this.searchText.toLowerCase()) || 
                    item.sectionTwo && item.sectionTwo.toLowerCase().includes(this.searchText.toLowerCase()) || 
                    item.sectionThree && item.sectionThree.toLowerCase().includes(this.searchText.toLowerCase())
            }) : this.items
        },
        computedplaceholder(){
            return this.heading.length ? `search through ${this.heading}` : 'search for item'
        },
    },
    methods: {
        clickedMain() {
            this.showOther = !this.showOther
        },
        clickedItem(data){
            this.$emit('clickedItem',{data, heading: this.heading})
        },
        getSearchText(data){
            this.searchText = data
        },
    },
}
</script>

<style lang="scss" scoped>

    .dashboard-item-wrapper{
        width: fit-content;
        position: relative;
        box-shadow: 0 0 2px grey;
        border-radius: 10px;
        background: lightcyan;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        cursor: pointer;

        .main{
            max-width: 200px;
            padding: 10px;
            font-size: 12px;
            color: gray;
            width: 100%;

            .heading{
                @include text-overflow();
                font-size: 14px;
                text-align: center;
                border-bottom: 1px solid;                
            }

            .top{
                display: inline-flex;
                width: 100%;
                align-items: center;

                .label{
                    margin-right: 5px;
                    min-width: fit-content;
                }

                .value{
                    @include text-overflow();
                    font-size: 14px;
                }
            }

            .bottom{
                width: 100%;
                font-style: italic;
            }
        }

        .other{
            width: fit-content;
            max-width: 200px;
            padding: 5px;
            max-height: 150px;
            overflow-y: auto;
            
            .item{
                box-shadow: 0 0 2px grey;
                border-radius: 10px;
                padding: 5px;
                min-width: 150px;
                align-items: center;
                color: gray;
                font-size: 12px;
                background: mintcream;
                text-transform: lowercase;
                margin-bottom: 10px;

                .section1{
                    font-size: 12px;
                    color: black;
                    max-width: 200px;
                    text-align: center;
                    text-transform: capitalize;
                }

                .section2{
                    font-size: 12px;
                    color: gray;
                }

                .section3{
                    font-size: 10px;
                    color: gray;
                    font-style: italic;
                }
            }

            .search-input{
                width: 100%;
                background: mintcream;
                margin-bottom: 10px;
                border: none;
            }

            .no-items{
                width: 100px;
                text-align: center;
                color: whitesmoke;
                font-size: 12px;
            }
        }
    }

    .dashboard-item{
        background: green;
        min-width: fit-content !important;

        .main{
            color: white;
        }
    }
</style>