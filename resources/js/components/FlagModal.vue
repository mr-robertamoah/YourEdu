<template>
    <addon-modal
        :show="show"
        :loading="addonLoading"
        @clickedClose="clickedClose"
        title="Get access to all your flagged accounts, posts, comments and answers. Note: You can unflag them as well"
    >
        <template slot="data">
            <div class="top-section">
                <div class="top-section-item"
                    @click="flagsType = 'all'"
                    :class="{itemActive: flagsType === 'all'}"
                >all</div>
                <div class="top-section-item"
                    @click="flagsType = 'accounts'"
                    :class="{itemActive: flagsType === 'accounts'}"
                >accounts</div>
                <div class="top-section-item"
                    @click="flagsType = 'posts'"
                    :class="{itemActive: flagsType === 'posts'}"
                >posts</div>
                <div class="top-section-item"
                    @click="flagsType = 'comments'"
                    :class="{itemActive: flagsType === 'comments'}"
                >comments</div>
                <div class="top-section-item"
                    @click="flagsType = 'answers'"
                    :class="{itemActive: flagsType === 'answers'}"
                >answers</div>
            </div>
            <div class="flags-section">
                <div class="no-flags" v-if="computedNoflags">
                    you have no flags
                </div>
                <slide-right-group>
                    <template slot="transition">
                        <div
                            v-for="(flag, index) in flags"
                            :key="index"
                        >
                            <account-badge
                                v-if="flag.hasOwnProperty('account')"
                                :account="flag"
                                :flag="true"
                                :action="true"
                                @clickedAction="clickedUnflag"
                            ></account-badge>
                            <item-show
                                v-else
                                :data="flag"
                                actionButtonText="unflag"
                                @clickedAction="clickedUnflag"
                            ></item-show>
                        </div>
                    </template>
                </slide-right-group>
                <div class="loading" v-if="flagsLoading">
                    <pulse-loader :loading="flagsLoading" size="10px"></pulse-loader>
                </div>
                <div class="show-more" v-if="showMore && !flagsLoading">
                    <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                </div>
                <div class="no-more" v-if="!computedNoflags && showNoMore && !flagsLoading">
                    no more flags...
                </div>
            </div>
        </template>
    </addon-modal>
</template>

<script>
import { mapActions } from 'vuex';
import ItemShow from './ItemShow.vue';
import AccountBadge from './dashboard/AccountBadge.vue';
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
            AccountBadge,
            ItemShow,
        },
        data() {
            return {
                flags: [],
                flagsNextPage: 1,
                flagsType: 'all',
                addonLoading: false,
                flagsLoading: false,
                showMore: false,
                showNoMore: false,
                showNoFlags: false,
            }
        },
        watch: {
            show: {
                immediate:true,
                handler(newValue){
                    if (newValue && !this.flags.length) {
                        this.showNoFlags = false
                        this.addonLoading = true
                        this.flagsLoading = true
                        this.getFlagged()
                    }
                }
            },
            flagsType(newValue){
                this.flagsNextPage = 1
                this.addonLoading = true
                this.showMore = false
                this.getFlagged()
            },
        },
        computed: {
            computedNoflags() {
                return this.showNoFlags && !this.flagsLoading && this.flagsNextPage < 2 && 
                    !this.flags.length 
            }
        },
        methods: {
            ...mapActions(['getUserFlagged','profile/deleteFlag']),
            async clickedUnflag(main){
                console.log('main :>> ', main);
                console.log('flags :>> ', this.flags);
                let response = null,
                    data = {
                        flagId: main.flag.id
                    }
                response = await this['profile/deleteFlag'](data)

                if (response.status) {
                    let flagIndex = this.flags.findIndex(flag=>{
                        if (main.item === 'comment' && flag.hasOwnProperty('commentedby_type')) {
                            return flag.id === main.itemId
                        } else if (main.item === 'post' && flag.hasOwnProperty('addedby_type')) {
                            return flag.id === main.itemId
                        } else if (main.item === 'answer' && flag.hasOwnProperty('answeredby_type')) {
                            return flag.id === main.itemId
                        } else if (flag.hasOwnProperty('account')) {
                            return flag.accountId === main.itemId && 
                                flag.account === main.item
                        }
                    })
                    if (flagIndex > -1) {
                        this.flags.splice(flagIndex,1)
                    }
                }
            },
            getMoreFlagged(){
                if (this.showMore) {
                    this.flagsLoading = true 
                    this.showMore = false
                    this.getFlagged()
                }
            },
            async getFlagged() {
                if (!this.flagsNextPage) {
                    return
                }
                let response = null,
                    data = {
                        nextPage: this.flagsNextPage,
                        type: this.flagsType
                    }
                
                response = await this.getUserFlagged(data)
                console.log(response);
                if (response !== 'unsuccessful') {
                    this.flags = this.flagsNextPage < 2 ? response.data : 
                        this.flags.push(...respnse.data)
                    if (response.next) {
                        this.flagsNextPage += 1
                        this.showMore = true
                    } else {
                        this.flagsNextPage = null
                        this.showNoMore = true
                    }
                }
                this.flagsLoading = false
                this.addonLoading = false
                this.showNoFlags = true
            },
            clickedClose(){
                this.$emit('clickedClose','flags')
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

    .flags-section{
        width: 100%;
        margin: 5px auto 10px;
        overflow-y: auto;
        width: 90%;
        height: 50vh;
        padding: 5px 10px;

        .no-flags{
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

    .flags-section{
        height: 60vh;
    }
}


@media screen and (max-width:800px){

    .flags-section{
        height: 65vh;
    }
}

// @media screen and (max-width:500px){

//     .flags-section{
//         height: 85vh;
//     }
// }
</style>