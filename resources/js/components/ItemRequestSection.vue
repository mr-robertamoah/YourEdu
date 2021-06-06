<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div class="item-request-section">
                <div class="close" @click="clickedCloseRequest">
                    <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                </div>
                <div class="title">{{computedTitle}}</div>
                <div class="body">
                    <search-input
                        class="search-section"
                        placeholder="search whom to invite?"
                        @search="receivedParticipantsSearchText"
                    ></search-input>
                    <div class="search-types" v-if="hasAllowed">
                        <grey-button
                            class="grey-button"
                            @clickedAction="clickedSearchType('profiles')"
                            :active="searchType === 'profiles'"
                            text="all"
                            v-if="allowed === 'ALL'"
                        ></grey-button>
                        <grey-button
                            class="grey-button"
                            @clickedAction="clickedSearchType('learners')"
                            :active="searchType === 'learners'"
                            text="learners"
                            v-if="allowed === 'ALL' || allowed === 'LEARNERS'"
                        ></grey-button>
                        <grey-button
                            class="grey-button"
                            @clickedAction="clickedSearchType('parents')"
                            :active="searchType === 'parents'"
                            text="parents"
                            v-if="allowed === 'ALL' || allowed === 'PARENTS'"
                        ></grey-button>
                        <grey-button
                            class="grey-button"
                            @clickedAction="clickedSearchType('facilitators')"
                            :active="searchType === 'facilitators'"
                            text="facilitators"
                            v-if="allowed === 'ALL' || allowed === 'FACILITATORS'"
                        ></grey-button>
                        <grey-button
                            class="grey-button"
                            @clickedAction="clickedSearchType('professionals')"
                            :active="searchType === 'professionals'"
                            text="professionals"
                            v-if="allowed === 'ALL' || allowed === 'PROFESSIONALS'"
                        ></grey-button>
                        <grey-button
                            class="grey-button"
                            @clickedAction="clickedSearchType('schools')"
                            :active="searchType === 'schools'"
                            text="schools"
                            v-if="allowed === 'ALL' || allowed === 'SCHOOLS'"
                        ></grey-button>
                    </div>
                    <div class="accounts-section">
                        <div class="no-participants" v-if="!searchLoading && noSearchParticipants">
                            no search results
                        </div>
                        <participant-badge
                            v-for="(participant,index) in searchParticipants"
                            :key="index"
                            :account="participant"
                            class="participant-badge"
                            :invite="true"
                            :inviting="loading"
                            @clickedAction="clickedParticpantAction"
                        ></participant-badge>
                        <div class="loading" v-if="searchLoading">
                            <pulse-loader :loading="searchLoading" size="10px"></pulse-loader>
                        </div>
                        <div class="show-more"
                            v-if="!searchLoading && showMoreSearchParticipants"
                            @click="search"
                        >
                            show more
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </just-fade>
</template>

<script>
import SearchInput from './SearchInput';
import GreyButton from './GreyButton';
import ParticipantBadge from './discussion/ParticipantBadge';
import { mapActions } from 'vuex';
    export default {
        components: {
            SearchInput,
            GreyButton,
            ParticipantBadge,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            loading: {
                type: Boolean,
                default: false
            },
            allowed: {
                type: String,
                default: ''
            },
            hasAllowed: {
                type: Boolean,
                default: false
            },
            removedParticipant: {
                type: Object,
                default() {
                    return null
                }
            },
            computedItem: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                searchType: 'profiles',
                searchLoading: false,
                searchText: '',
                searchParticipants: [],
                searchNextPage: 1,
                noSearchParticipants: true,
                showMoreSearchParticipants: false,
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (! newValue) {
                        this.searchParticipants = []
                        return
                    }
                    
                    this.setSearchTypeBasedOnAllowed()
                }
            },
            searchType(newValue){
                this.searhInvitableParticipants()
            },
            searchText(newValue){
                if (newValue.length) {
                    this.searhInvitableParticipants()
                } else {
                    this.searchParticipants = []
                }
            },
            removedParticipant(newValue) {
                if (newValue) {
                    this.removeSearchParticipant(newValue.userId)
                }
            },
            searchParticipants(newValue){
                if (newValue.length) {
                    this.noSearchParticipants = false
                } else {
                    this.noSearchParticipants = true
                    this.showMoreSearchParticipants = false
                }
            },
            searchNextPage(newValue){
                if (newValue === 1 && ! this.searchParticipants.length) {
                    this.noSearchParticipants = true
                    return
                }

                if (newValue === 1) {
                    this.noSearchParticipants = false
                    return
                }
                
                if (! newValue) {
                    this.showMoreSearchParticipants = false
                    return
                }

                if (newValue > 1) {
                    this.showMoreSearchParticipants = true
                    return
                }
            },
        },
        computed: {
            computedTitle() {
                return `invite accounts to join this ${this.computedItem.item}` 
            }
        },
        methods: {
            ...mapActions([
                'profile/discussionSearch', 'profile/itemSearch'
            ]),
            clickedCloseRequest() {
                this.$emit('clickedCloseRequest')
            },
            searhInvitableParticipants: _.debounce(function() {
                this.searchNextPage = 1
                this.search()
            }, 400),
            setSearchTypeBasedOnAllowed() {
                if (! this.hasAllowed) {
                    this.setSearchType('profiles')
                    return
                }

                if (this.allowed === 'ALL') {
                    this.setSearchType('profiles')
                    return
                }
                
                this.setSearchType(this.allowed.toLowerCase())
            },
            clickedSearchType(data){
                this.setSearchType(data)
            },
            setSearchType(data){
                this.searchType = data
            },
            receivedParticipantsSearchText(data){
                this.searchText = data
            },
            removeSearchParticipant(userId){
                this.searchParticipants = this.searchParticipants.filter(participant=>{
                    return participant.userId !== userId
                })

                this.$emit('doneRemovingParticipant')
            },
            async startSearch() {
                this.searchParticipants = await this.search()
            },
            async infiniteHandler($state) {
                this.searchParticipants.push(...await this.search())

                if (this.searchNextPage) {
                    $state.loaded()
                    return
                }

                $state.complete()
            },
            async search(){
                if (this.searchNextPage === null || !this.searchText.length) {
                    return 
                }
                this.searchLoading = true
                let response = null,
                    params = {
                        search: this.searchText,
                        searchType: this.searchType,
                    },
                    data = {}

                data.nextPage = this.searchNextPage
                data.item = this.computedItem.item
                data.params = params
                data.params[`${this.computedItem.item}Id`] = this.computedItem.itemId,

                response = await this[`profile/itemSearch`](data)

                this.searchLoading = false
                if (! response.status) {
                    return []
                }

                if (response.next) {
                    this.searchNextPage += 1
                    return response.data
                }

                this.searchNextPage = null
                return response.data
            },
        },
    }
</script>

<style lang="scss" scoped>


@mixin show-more(){
    padding: 5px;
    border-radius: 10px;
    margin: 5px auto;
    width: fit-content;
    background: white;
    font-size: 11px;
    box-shadow: 0 0 2px green;
    cursor: pointer;
    color: green;
    font-weight: 600;
}
    
    .item-request-section{
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: mintcream;
        border-radius: 10px;
        box-shadow: 0 0 2px grey;

        .close, 
        .pencil{
            color: gray;
            position: absolute;
            right: 10px;
            top: 10px;
            padding: 5px;
            font-size: 14px;
            cursor: pointer;

            &:hover{
                color: red;
                transition: all 1s ease-in-out;
            }
        }

        .pencil{
            right: 40px;
            top: 5px;

            &:hover{
                color: green;
            }
        }
        
        .title{
            width: 100%;
            text-align: center;
            margin: 20px 0 0;
            color: gray;
            text-transform: capitalize;
        }

        .body{
            padding: 10px;
            height: 88%;
        }
    }

    .item-request-section{

        .body{

            .search-section{
                margin-bottom: 10px;
            }

            .search-types{
                width: 100%;
                display: inline-flex;
                justify-content: space-around;
                align-items: center;
                flex-wrap: wrap;

                .grey-button{
                    margin-bottom: 10px;
                }
            }

            .accounts-section{
                padding: 10px;
                overflow-y: auto;
                max-height: 400px;
                width: 100%;
                margin-top: 20px;

                .no-participants{
                    width: 100%;
                    height: 100px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    color: gray;
                    font-size: 14px;
                }

                .participant-badge{
                    margin-bottom: 10px;
                }

                .loading{
                    width: 100%;
                    text-align: center;
                }

                .show-more{
                    @include show-more();
                }
            }
        }
    }
</style>