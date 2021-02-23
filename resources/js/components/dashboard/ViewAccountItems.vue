<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                @mainModalDisappear="mainModalDisappear"
                class="view-account-items"
            >
                <template slot="main">
                    <div class="account" v-if="computedAccount">
                        <profile-picture class="profile-picture">
                            <template slot="image">
                                <img :src="computedAccount.url">
                            </template>
                        </profile-picture>
                        <div class="account-details">
                            <div class="name">
                                {{computedAccount.name}}
                            </div>
                            <div class="account-type">
                                {{computedAccount.account}}
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        {{item}}
                    </div>
                    <welcome-form class="welcome-form"
                        title=""
                    >
                        <template slot="input">
                            
                            <search-input
                                class="search-input"
                                :searchPlaceholder="`search for ${item}`"
                                @search="getSearchText"
                            ></search-input>

                            <div class="items">
                                <pulse-loader
                                    :loading="computedFirstLoad"
                                ></pulse-loader>

                                <dashboard-section-account
                                    v-for="(item,index) in searchItems"
                                    :key="index"
                                    class="dashboard-section-account"
                                    :type="computedType"
                                    :account="item"
                                    @clickedDashboardActionButton="clickedDashboardActionButton"
                                ></dashboard-section-account>

                                <div class="no-items"
                                    v-if="computedNoItems"
                                >
                                    {{`oops... no ${item}`}}
                                </div>
                                <infinite-loader
                                    v-if="computedNextPage"
                                    @infinite="infiniteHandler"
                                ></infinite-loader>
                            </div>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import ProfilePicture from '../profile/ProfilePicture';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import InfiniteLoader from 'vue-infinite-loading';
import DashboardSectionAccount from './DashboardSectionAccount';
import SearchInput from '../SearchInput';
import { mapActions, mapGetters } from 'vuex'
import { bus } from '../../app';
    export default {
        components: {
            SearchInput,
            DashboardSectionAccount,
            PulseLoader,
            ProfilePicture,
            InfiniteLoader,
        },
        props: {
            show: {
                type: Boolean,
                default: true
            },
            item: {
                type: String,
                default: 'lessons'
            }
        },
        data() {
            return {
                searchText: '',
                nextPage: 1,
                searchItems: [],
                loading: false,
            }
        },
        watch: {
            searchText(newValue) {
                if (newValue && newValue.length) {
                    if (this.searchItems.length && 
                        this.computedItem === this.searchItems[0].type) {
                        return
                    }
                    this.debouncedGetItems()
                }
            },
            show: {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        this.debouncedGetItems()
                    }
                }
            },
            computedItem(newValue) {
                if (newValue) {
                    this.listenForEvents()
                }
            }
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails']),
            computedAccount() {
                // return this['dashboard/getAccountDetails']
                return this['dashboard/getAccountDetails']
            },
            computedFirstLoad() {
                return this.loading && this.nextPage === 1
            },
            computedNextPage() {
                return this.nextPage && this.nextPage !== 1
            },
            computedNoItems() {
                return !this.loading && this.searchItems.length === 0 && 
                    (this.nextPage > 1 || !this.nextPage)
            },
            computedType() {
                return this.item.includes('owned') ? `owned ${this.computedItem}` :
                    this.computedItem
            },
            computedItemText() {                
                return this.item.includes('owned') ? this.item.slice(6) : this.item
            },
            computedItem() {
                let trimNumber = 1
                if (this.computedItemText === 'classes') {
                    trimNumber = 2
                }
                return this.computedItemText
                    .slice(0, this.computedItemText.length - trimNumber)
            },
        },
        methods: {
            ...mapActions(['dashboard/getAccountItems']),
            mainModalDisappear() {
                this.$emit('closeViewAccountItems')
            },
            listenForEvents() {
                bus
                .$on(this.getEvent('update'),(data)=>{
                    console.log(data,'from view account items');
                    this.updateItem(data)
                })
                .$on(this.getEvent('delete'),(data)=>{
                    this.deleteItem(data)
                })
            },
            getEvent(action) {
                return `${action}${this.computedItem.charAt(0).toUpperCase()}${this.computedItem.slice(1)}`
            },
            updateItem(data) {
                let index = this.findItemIndex(data.id)
                if (index > -1) {
                    this.searchItems.splice(index,1,data)
                }
            },
            deleteItem(data) {                
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.searchItems.splice(index,1)
                }
            },
            findItemIndex(id) {
                return this.searchItems.findIndex(item=>{
                    return item.id === id
                })
            },
            clearData() {
                this.searchText = ''
            },
            getSearchText(data) {
                this.searchText = data
            },
            debouncedGetItems: _.debounce(function() {
                this.getItems()
            },200),
            async getItems() {
                this.searchItems = []
                this.nextPage = 1
                this.searchItems = await this.actualGetItems()
            },
            async infiniteHandler($state) {

                this.searchItems.push(...await this.actualGetItems())

                if (this.nextPage) {
                    $state.loaded()
                } else {
                    $state.complete()
                }
            },
            clickedDashboardActionButton(data) {
                if (data.buttonData.text.includes('delete')) {
                    this.$emit('clickedDashboardActionButton',data)
                    return 
                }
                if (data.buttonData.text.includes('view')) {
                    let event = data.type.includes('lesson') ? 'viewLesson' : 'viewDashboardItem'
                    bus.$emit(event,{
                        type: this.computedItem,
                        id: data.buttonData.data.id,
                        name: data.buttonData.data.name,
                    })
                    return 
                }
                bus.$emit(
                    this.getEventName(data.buttonData.text),
                    data.buttonData.data
                )
            },
            getEventName(action) {
                return `${action}${this.computedItem.charAt(0).toUpperCase()}${this.computedItem.slice(1)}`
            },
            async actualGetItems() {
                if (this.loading || !this.nextPage) {
                    return
                }
                this.loading = true
                let response,
                    data = {
                        account: this.computedAccount.account,
                        accountId: this.computedAccount.accountId,
                        item: this.computedItemText,
                        search: this.searchText
                    }

                response = await this['dashboard/getAccountItems']({
                    data, nextPage: this.nextPage
                })

                this.loading = false

                if (response.status) {
                    if (response.next) {
                        this.nextPage += 1
                    } else {
                        this.nextPage = null
                    }
                    return response.items
                } else {
                    console.log('response :>> ', response);
                    return [];
                }

            }
        },
    }
</script>

<style lang="scss" scoped>

    .view-account-items{
        
        .main{

            .account{
                margin: 20px auto 10px;
                width: 100%;
                display: flex;
                padding: 0 10px 10px;
                border-bottom: 2px solid gray;

                .profile-picture{
                    width: 60px;
                    height: 60px;
                    min-width: 60px;
                    margin-right: 10px;
                }

                .account-details{
                    width: 100%;

                    .name{
                        font-size: 14px;
                        text-transform: capitalize;
                        font-weight: 600;
                    }

                    .account-type{
                        font-size: 12px;
                        color: gray;
                    }
                }
            }

            .item{
                @include main-section-headings(14px);
                text-align: start;
            }

            .welcome-form{
                margin-top: 0;

                .search-input{
                    border: none;
                    border-bottom: 2px solid $color-main;
                    background: inherit;
                    width: 100%;
                }

                .items{
                    width: 100%;
                    padding: 10px;
                    max-height: 600px;

                    .v-spinner{
                        text-align: center;
                        margin: 30px 0;
                    }

                    .no-items{
                        color: gray;
                        font-size: 12px;
                        text-align: center;
                        margin: 30px 0;
                    }

                    .dashboard-section-account{
                        margin-bottom: 10px;                        
                    }
                }
            }
        }
    }
</style>