<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                @mainModalDisappear='closeModal'
                class="arranging-modal-wrapper"
            >
                <template slot="main">
                    <div class="title">
                        {{`arrange ${type}`}}
                    </div>
                    <droppable-component
                        @drop="move"
                    >
                        <div class="arrangables">
                            <arrangable-badge
                                v-for="(arrangable, index) in data"
                                :key="index"
                                :arrangable="arrangable"
                                :index="index"
                                @move="move"
                            ></arrangable-badge>
                        </div>
                    </droppable-component>
                    <div class="buttons">
                        <post-button
                            :buttonText="`done arranging`"
                            @click="clickedDone"
                        ></post-button>
                    </div>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import { bus } from '../../app';
import DroppableComponent from '../specials/DroppableComponent';
import PostButton from '../PostButton';
import ArrangableBadge from './ArrangableBadge';
import { strings } from '../../services/helpers';
    export default {
        components: {
            ArrangableBadge,
            PostButton,
            DroppableComponent,
        },
        props: {
            data: {
                type: Array,
                default() {
                    return []
                }
            },
            show: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                
            }
        },
        methods: {
            updatePositions() {
                this.data.forEach(
                    function(dataItem, dataItemIndex){
                        dataItem.position = dataItemIndex + 1
                    }
                )
            },
            move(data) {

                if (data.fromIndex + 1 ===
                    this.data.length && 
                    data.toIndex === undefined &&
                    !data.removed) {
                    return
                }
                
                let from = this.data.splice(data.fromIndex,1)[0]

                if (data.toIndex === undefined) {
                    this.data.push(from)
                } else if (data.toIndex === 0) {
                    this.data.unshift(from)
                } else {
                    this.data.splice(data.toIndex, 0, from)
                }

                this.updatePositions()
            },
            clickedDone() {
                
                this.closeModal()
            },
            closeModal() {
                this.$emit('closeArrangingModal')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .arranging-modal-wrapper{

        .title{
            margin: 30px 10px 10px;
            text-align: center;
            text-transform: capitalize;
            font-weight: 550;
        }

        .arrangables{
            padding: 10px;
            width: 100%;
            max-width: 500px;
            overflow-y: auto;
            max-height: 500px;
        }

        .buttons{
            margin: 20px 0;
            width: 100%;
            text-align: center;
        }
    }
</style>