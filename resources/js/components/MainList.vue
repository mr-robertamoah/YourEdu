<template>
    <div class="list-wrapper"
            >
        <div v-if="loading" class="loading">
            loading items...
        </div>
        <div v-else>
            <div class="select">
                {{select ? select : 'select from list'}}
            </div>
            <div ref='list' v-if="selectable">
                <div class="list-item" v-for="(item,key) in itemList" 
                    @click="clicked($event, item, key)"
                    :key="key"
                    :title="item.title ? item.title : ''"
                    :class="{active:makeActive(item)}"
                >
                    {{item.name ? item.name : item.option ? item.option : item}}
                </div>
            </div>
            <template v-else>
                <div class="list-item" v-for="(item,key) in itemList" 
                    :key="key"
                    :title="item.title ? item.title : ''"
                    :class="{active:makeActive(item)}"
                >
                    {{item.name ? item.name : item}}
                </div>
            </template>
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
            multiple: {
                type: Boolean,
                default: false
            },
            selectable: { //for making the items unselectable
                type: Boolean,
                default: true
            }
        },
        computed: {

        },
        data() {
            return {
                active : false,
                items : [],
                item : '',
            }
        },
        methods: {
            makeActive(item) { //used to make an item look selected
                return item === this.selectedItem ||
                    item.name === this.selectedItem ?
                    true : false 
            },
            clicked($event, item) {
                this.active = !this.active
                let list =  this.$refs.list
                if (!this.multiple) {
                    for (let i = 0; i < list.children.length; i++) {
                        if (list.children[i].classList.contains('active')) {
                            list.children[i].classList.remove('active')
                        }
                    }
                    $event.target.classList.add('active')
                    this.item = item
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
            text-align: start;
            margin: 10px;
            font-weight: 450;
        }

        .loading{
            text-align: center;
            padding: 10px;
            width: 100%;
            vertical-align: middle;
        }

        .list-item{
            width: 90%;
            margin: 10px;
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
            box-shadow: 2px 2px 1px $second-color, -2px -2px 1px $second-color, 
                1px 1px 2px $third-color, -1px -1px 2px $third-color;
            // transition: all .5s ease;
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