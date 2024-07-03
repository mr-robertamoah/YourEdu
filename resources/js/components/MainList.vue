<template>
    <div class="list-wrapper">
        <div v-if="loading" class="loading">
            <pulse-loader :loading="loading"></pulse-loader>
        </div>
        <div v-else>
            <div class="select" v-if="selectable">
                {{select ? select : 'select from list'}}
            </div>
            <div ref='list' v-if="selectable">
                <div class="list-item" v-for="(item,key) in itemList" 
                    @click="clicked($event, item, key)"
                    :key="key"
                    :title="item.title ? item.title : item.option ? 
                        `select ${item.option}` :''"
                    :class="{active:makeActive(item)}"
                >
                    {{item.name ? item.name : item.option ? item.option : item}}
                </div>
            </div>
            <template v-else>
                <div class="list-item" v-for="(item,key) in itemList" 
                    :key="key"
                >
                    {{item.name ? item.name : item.option ? item.option : item}}
                </div>
            </template>
            <div class="list-button" 
                v-if="showListButton"
                @click="clickedListButton"
            >
                {{buttonText}}
            </div>
        </div>
    </div>
</template>

<script>


    export default {
        props: {
            itemList: {
                type: Array,
                default(){
                    return [
                        {name:'example 1'},
                        {name: 'example 2', title:'this is for example 2'}
                    ]
                }
            },
            ownerType: {
                type: String,
                default: ''
            },
            ownerId: {
                type: Number,
                default: null
            },
            itemId: {
                type: Number,
                default: null
            },
            select: {
                type: String,
                default: ''
            },
            selectedItem: {
                type: String,
                default: ''
            },
            loading: {
                type: Boolean,
                default: false
            },
            edit: {
                type: Boolean,
                default: false
            },
            buttonText: {
                type: String,
                default: ''
            },
            multiple: {
                type: Boolean,
                default: false
            },
            selectable: { //for making the items unselectable
                type: Boolean,
                default: true
            }
        },
        components: {
            
        },
        data() {
            return {
                active : false,
                items : [],
                item : '',
                showListButton: false,
            }
        },
        methods: {
            clickedListButton(){
                if (!this.item.hasOwnProperty('id') && 
                    !this.item.hasOwnProperty('option')) {
                    return
                }
                let who = {}
                if (this.edit) {
                    who['account'] = this.ownerType
                    who['accountId'] = this.ownerId
                    who['itemId'] = this.itemId
                } 
                this.$emit('clickedListButton',who)
            },
            makeActive(item) { //used to make an item look selected
                return item === this.selectedItem ||
                    item.name === this.selectedItem ||
                    item.option === this.selectedItem ?
                    true : false 
            },
            clicked($event, item) {
                this.showListButton = false
                this.active = !this.active
                let list =  this.$refs.list
                if (!this.multiple) {
                    for (let i = 0; i < list.children.length; i++) {
                        if (list.children[i] !== $event.target) {
                            list.children[i].classList.remove('active')
                        }
                    }

                    if ($event.target.classList.contains('active')) {
                        $event.target.classList.remove('active')
                        this.item = ''
                    } else {
                        $event.target.classList.add('active')
                        this.item = item
                    }
                    
                    this.showListButton = this.buttonText.length ? true : false
                    this.$emit('listItemSelected', this.item)
                } else{
                    if ( $event.target.classList.contains('active')) {
                        $event.target.classList.remove('active')
                        this.items.pop(item)
                    } else {
                        $event.target.classList.add('active')
                        this.items.push(item)
                    }
                    this.$emit('listItemSelected', this.items)
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
$first-color: aliceblue;
$second-color: rgba(22, 233, 205, 1);
$third-color: rgba(102, 51, 153, .2);

    .list-wrapper{
        width: 90%;
        padding: 10px;
        background-color: $first-color;
        margin: 10px auto;
        box-shadow: 1px 1px 1px aliceblue, -1px -1px 1px aliceblue,;

        .select{
            text-align: center;
            margin: 10px;
            font-weight: 450;
            font-size: 14px;
        }

        .loading{
            text-align: center;
            padding: 10px;
            width: 100%;
            vertical-align: middle;
        }

        .list-item{
            width: 90%;
            margin: 5px auto;
            padding: 5px;
            border: 1px solid $second-color;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background-color: white;

            &:hover{
                box-shadow: 0 0 2px $second-color;
            }
        }

        .active{
            border: none;
            box-shadow: 1px 1px 1px $second-color, 
                -1px -1px 1px $second-color, 
                0 0 3px gray;
            background-color: gainsboro;
            transition: all .5s ease;
        }

        .list-button{
            font-size: 15px;
            color: gray;
            width: fit-content;
            margin: 10px auto;
            transition: all 1s ease;
            cursor: pointer;
            padding: 5px;

            &:hover{
                color: black;
                background-color: gainsboro;
                box-shadow: 0 0 3px gray;
            }
        }

        .listActive{
            color: black;
            background-color: gainsboro;
            box-shadow: 0 0 3px gray;
        }
    }

@media screen and (max-width:800px) {
    .list-wrapper{
        
        .list-item{
            font-size: 14px;
        }
    }
}
</style>