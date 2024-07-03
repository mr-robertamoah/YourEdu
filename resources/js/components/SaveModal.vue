<template>
    <addon-modal
        :show="show"
        :loading="addonLoading"
        @clickedClose="clickedClose"
        title="Get access to all your saved posts, comments and answers. Note: You can unsave them as well"
    >
        <template slot="data">
            <div class="top-section">
                <div class="top-section-item"
                    @click="savesType = 'all'"
                    :class="{itemActive: savesType === 'all'}"
                >all</div>
                <div class="top-section-item"
                    @click="savesType = 'posts'"
                    :class="{itemActive: savesType === 'posts'}"
                >posts</div>
                <div class="top-section-item"
                    @click="savesType = 'comments'"
                    :class="{itemActive: savesType === 'comments'}"
                >comments</div>
                <div class="top-section-item"
                    @click="savesType = 'answers'"
                    :class="{itemActive: savesType === 'answers'}"
                >answers</div>
            </div>
            <div class="saves-section">
                <div class="no-saves" v-if="computedNoSaves">
                    you have no saves
                </div>
                <slide-right-group>
                    <template slot="transition">
                        <div
                            v-for="(save, index) in saves"
                            :key="index"
                        >
                            <item-show
                                :data="save"
                                actionButtonText="unsave"
                                @clickedAction="clickedUnsave"
                            ></item-show>
                        </div>
                    </template>
                </slide-right-group>
                <div class="loading" v-if="savesLoading">
                    <pulse-loader :loading="savesLoading" size="10px"></pulse-loader>
                </div>
                <div class="show-more" v-if="showMore && !savesLoading">
                    <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                </div>
                <div class="no-more" v-if="!computedNoSaves && showNoMore && !savesLoading">
                    no more saves...
                </div>
            </div>
        </template>
    </addon-modal>
</template>

<script>
import { mapActions } from 'vuex';
import ItemShow from './ItemShow.vue';
import SlideRightGroup from './transitions/SlideRightGroup.vue';
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
        },
        components: {
            
            SlideRightGroup,
            ItemShow,
        },
        data() {
            return {
                saves: [],
                savesNextPage: 1,
                savesType: 'all',
                addonLoading: false,
                savesLoading: false,
                showMore: false,
                showNoMore: false,
                showNoSaves: false,
            }
        },
        watch: {
            show: {
                immediate:true,
                handler(newValue){
                    if (newValue && !this.saves.length) {
                        this.addonLoading = true
                        this.savesLoading = true
                        this.showNoSaves = false
                        this.getSaved()
                    }
                }
            },
            savesType(newValue){
                this.savesNextPage = 1
                this.addonLoading = true
                this.showMore = false
                this.getSaved()
            },
        },
        computed: {
            computedNoSaves() {
                return this.showNoSaves && !this.savesLoading && this.savesNextPage < 2 && 
                    !this.saves.length 
            }
        },
        methods: {
            ...mapActions(['getUserSaved','profile/deleteSave']),
            async clickedUnsave(main){
                let response = null,
                    data = {
                        saveId: main.save.id
                    }
                response = await this['profile/deleteSave'](data)

                if (response.status) {
                    let saveIndex = this.saves.findIndex(save=>{
                        if (main.item === 'comment' && save.hasOwnProperty('commentedby_type')) {
                            return save.id === main.itemId
                        } else if (main.item === 'post' && save.hasOwnProperty('addedby_type')) {
                            return save.id === main.itemId
                        } else if (main.item === 'answer' && save.hasOwnProperty('answeredby_type')) {
                            return save.id === main.itemId
                        }
                    })
                    if (saveIndex > -1) {
                        this.saves.splice(saveIndex,1)
                    }
                }
            },
            getMoreSaved(){
                if (this.showMore) {
                    this.showMore = false
                    this.savesLoading = true
                    this.getSaved()
                }
            },
            async getSaved() {
                if (!this.savesNextPage) {
                    return
                }
                let response = null,
                    data = {
                        nextPage: this.savesNextPage,
                        type: this.savesType
                    }
                
                response = await this.getUserSaved(data)
                console.log(response);
                if (response !== 'unsuccessful') {
                    this.saves = this.savesNextPage < 2 ? response.data : 
                        this.saves.push(...respnse.data)
                    if (response.next) {
                        this.savesNextPage += 1
                        this.showMore = true
                    } else {
                        this.savesNextPage = null
                        this.showNoMore = true
                    }
                }
                this.savesLoading = false
                this.addonLoading = false
                this.showNoSaves = true
            },
            clickedClose(){
                this.$emit('clickedClose','saves')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .top-section{
        display: inline-flex;
        align-items: center;
        margin: 5px auto 10px;
        justify-content: center;
        width: 90%;
        flex-wrap: nowrap;

        .top-section-item{
            padding: 5px;
            text-align: center;
            color: white;
            background: gray;
            cursor: pointer;
            box-shadow: 0 0 2px grey;
            border-radius: 10px;
            min-width: 30px;
            font-size: 12px;
            margin-right: 10px;

            &:hover{
                box-shadow: 0 0 3px grey;
                background: green;
                transition: all .5s ease;
            }
        }

        .itemActive{
            box-shadow: 0 0 3px grey;
            background: green;
            transition: all .5s ease;
        }
    }

    .saves-section{
        width: 100%;
        margin: 5px auto 10px;
        overflow-y: auto;
        width: 90%;
        height: 50vh;
        padding: 5px 10px;

        .no-saves{
            font-size: 12px;
            font-weight: 450;
            width: 100%;
            text-align: center;
            margin: auto;
        }
        
        .show-more,
        .no-more{
            width: fit-content;
            text-align: center;
            padding: 5px;
            margin: 5px auto;
            font-size: 12px;
        }

        .show-more{
            font-size: 20px;
        }

        .loading{
            text-align: center;
        }
    }

@media screen and (min-width:800px) and (max-width:1100px){

    .saves-section{
        height: 60vh;
    }
}


@media screen and (max-width:800px){

    .saves-section{
        height: 65vh;
    }
}

// @media screen and (max-width:500px){

//     .saves-section{
//         height: 85vh;
//     }
// }
</style>