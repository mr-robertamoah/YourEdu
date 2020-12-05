<template>
    <fade-left>
        <template slot="transition" v-if="show">
            <div class="select-input-wrapper"
                    :class="{error:error,bottomborder:bottomBorder}">
                <div class="main"
                    @click="clickedSelect"
                >
                    <div
                        v-if="placeholder.length"
                    >{{selection.length ? selection : placeholder}}</div>
                    <div class="icon">
                        <font-awesome-icon :icon="['fa','chevron-down']"></font-awesome-icon>
                    </div>
                </div>
                <just-fade>
                    <template slot="transition">
                        <div class="selection" ref="selection" 
                            v-if="showSelection"
                            :class="{special: special}"
                            infinite-wrapper
                        >
                            <template
                                v-if="!special"
                            >
                                <div
                                    class="option"
                                    v-for="item in items"
                                    :key="item.id"
                                    :value="item"
                                    @click="clickedSelection(item)"
                                    :class="{active: selection === item.name}"
                                >{{item.name}}</div>
                            </template>
                            <template
                                v-else
                            >
                                <div class="special"
                                    v-for="item in items"
                                    :key="item.id"
                                    :value="item"
                                    @click="clickedSelection(item)"
                                    :class="{specialActive: selection === item.name}"
                                    :title="item.description ? item.description : ''"
                                >
                                    <div class="top">{{item.name}}</div>
                                    <div class="bottom"
                                        v-if="item.description && 
                                        item.description.length"
                                    >{{`description: ${item.description}`}}</div>
                                </div>
                                <infinite-loader
                                    v-if="next !== 1"
                                    @infinite="infiniteHandler"
                                    force-use-infinite-wrapper
                                ></infinite-loader>
                            </template>
                        </div>
                    </template>
                </just-fade>
            </div>
        </template>
    </fade-left>
</template>

<script>
import FadeLeft from './transitions/FadeLeft';
import InfiniteLoader from 'vue-infinite-loading';

    export default {
        components: {
            FadeLeft,
            InfiniteLoader,
        },
        props: {
            items: {
                type: Array,
                default(){
                    return [
                        {id:1, name: 'good'},
                        {id:2, name: 'better'},
                        {id:3, name: 'best'},
                    ]
                }
            },
            placeholder: {
                type: String,
                default: 'select'
            },
            value: {
                type: String,
                default: ''
            },
            show: {
                type: Boolean,
                default: true
            },
            special: {
                type: Boolean,
                default: false
            },
            error: {
                type: Boolean,
                default: false
            },
            next: {
                type: Number,
                default: 1
            },
            bottomBorder: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                selection: '',
                showSelection: false,
                last: 0,
            }
        },
        watch: {
            show(newValue, oldValue) {
                if (!newValue) {
                    this.selection = ''
                }
            },
            value: {
                immediate: true,
                handler(newValue){
                    if (!newValue) {
                        this.selection = ''
                    }
                }
            },
        },
        methods: {
            clickedSelect(){
                this.showSelection = !this.showSelection
            },
            clickedSelection(item) {
                this.selection = item.name
                this.showSelection = false
                this.$emit('clickedSelection',item)
            },
            async infiniteHandler($state){
                console.log(this.next);
                if (!this.next) {
                    $state.complete()
                    // return
                } else if (this.next === this.last) {
                    // return
                } else {
                    $state.loaded()
                    this.last = this.next
                    this.$emit('getMore')
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
$border-radius: 10px;
$border-color-main: rgba(22, 233, 205, 1);
$border-color-error:rgba(201, 6, 6, 0.9);

    .select-input-wrapper{
        width: 100%;
        font-size: 14px;

        .main{
            width: 100%;
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px;
            cursor: pointer;
            border-bottom: 2px solid gray;

            &:focus,
            &:active{
                border: none;
                box-shadow: none;
            }
        }

        .selection{
            text-align: center;
            padding: 5px;
            width: 80%;
            margin: auto;
            background: aliceblue;
            overflow: auto;
            max-height: 150px;

            .option{
                padding: 5px;
                font-size: 12px;
                font-weight: 500;
                cursor: pointer;

                &:hover{
                    background: gainsboro;
                    transition: all .5s ease;
                }
            }

            .active{
                background: gainsboro;
                transition: all .5s ease;
            }

            .special{
                text-align: start;
                box-shadow: 0 0 2px;
                border-radius: 10px;
                padding: 5px;
                margin: 0 0 5px;
                cursor: pointer;

                &:hover{
                    background: ghostwhite;
                    transition: all .5s ease-in;
                }

                .top{
                    font-size: 14px;
                    text-transform: capitalize;
                    font-weight: 500;
                }

                .bottom{
                    font-size: 12px;
                    text-indent: 10px;
                    font-style: italic;
                    text-overflow: ellipsis;
                    overflow: hidden;
                    white-space: nowrap;
                }
            }

            .specialActive{
                background: ghostwhite;
                transition: all .5s ease;
            }
        }

        .special{
            background: none;
        }
    }

    .bottomborder{
        $border-radius: none;

        border: none;
        border-radius: $border-radius;
        border-bottom: 2px solid $border-color-main;
        
        input{
            border-radius: $border-radius;
            border-radius: 0;
        }
    }

    .error{
        border: 2px solid $border-color-error;
    }
    
@media screen and (max-width:800px) {
    
    .select-input-wrapper{
        select{
            font-size: 14px;
        }
    }
}

@media screen and (max-width:300px) {
    
    .select-input-wrapper{

        select{
            font-size: 12px;
        }
    }
}
</style>