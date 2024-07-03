<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                @mainModalDisappear='closeModal'
                class="create-collaboration-modal-wrapper"
            >
                <template slot="main">
                    <welcome-form
                        :title="title"
                        class="welcome-form"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="clearAlert"
                            ></auto-alert>
                            <pulse-loader 
                                class="loading"
                                :loading="loading"></pulse-loader>
                            
                            <div class="section">Collaboration Info</div>
                            <text-input
                                :bottomBorder="true"
                                placeholder="collaboration name*"
                                v-model="data.name"
                                class="other-input"
                            ></text-input>
                            <text-textarea
                                :bottomBorder="true"
                                placeholder="description of the collaboration"
                                v-model="data.description"
                                class="class-input"
                            ></text-textarea>
                            <div class="type">
                                <radio-input
                                    label="free"
                                    radioValue="free"
                                    v-model="data.type"
                                ></radio-input>
                                <radio-input
                                    label="paid"
                                    radioValue="paid"
                                    v-model="data.type"
                                ></radio-input>
                            </div>
                            <div class="note" v-if="computedTypeNote.length">
                                {{computedTypeNote}}
                            </div>
                            <main-checkbox
                                v-model="data.collaborate"
                                v-if="computedShowCollaborate"
                                :label="`this ${computedAccount.account} account of yours a collaborator?`"
                                class="class-input"
                            ></main-checkbox>
                            <number-input
                                v-if="data.collaborate"
                                :bottomBorder="true"
                                :placeholder="`${computedAccount.name}'s share`"
                                v-model="data.accountShare"
                                :inputMax="maxAllowableShare"
                                :inputMin="0"
                                class="number-input"
                                label="percent share"
                                prepend="%"
                            ></number-input>
                            
                            <div class="section">Adding Other Collaborators</div>
                            <search-input
                                class="search-input"
                                placeholder="search accounts"
                                @search="getSearchText"
                            ></search-input>

                            <div class="search-types">
                                <grey-button
                                    class="grey-button"
                                    text="all"
                                    @clickedAction="clickedSearchType"
                                    :active="searchType === 'all'"
                                ></grey-button>
                                <grey-button
                                    class="grey-button"
                                    text="facilitators"
                                    @clickedAction="clickedSearchType"
                                    :active="searchType === 'facilitators'"
                                ></grey-button>
                                <grey-button
                                    class="grey-button"
                                    text="professionals"
                                    @clickedAction="clickedSearchType"
                                    :active="searchType === 'professionals'"
                                ></grey-button>
                            </div>

                            <div class="accounts" v-if="accounts">
                                <pulse-loader
                                    v-if="searchNextPage === 1"
                                    :loading="searchLoading"
                                ></pulse-loader>
                                <template
                                    v-if="!searchLoading"
                                >
                                    <simple-account-badge
                                        v-for="(account,index) in accounts"
                                        :key="index"
                                        :account="account"
                                        class="mr-3"
                                        @clickedAccount="getSelectedAccount"
                                    ></simple-account-badge>
                                </template>
                                <Infinite-Loading
                                    v-if="computedShowInfiniteLoader"
                                    @infinte="infiniteHandler"
                                ></Infinite-Loading>
                                <div class="no-data"
                                    v-if="computedShowNoData"
                                >
                                    no accounts for this search
                                </div>
                            </div>
                            <number-input
                                v-if="selectedAccount"
                                :bottomBorder="true"
                                :placeholder="`${selectedAccount.name}'s share`"
                                v-model="data.collaboratorShare"
                                :inputMax="maxAllowableShare"
                                :inputMin="0"
                                class="number-input"
                                label="percent share"
                                prepend="%"
                            ></number-input>

                            <grey-button
                                v-if="collaborators.length"
                                text="view collaborators"
                                @clickedAction="clickedViewCollaborators"
                                :active="viewCollaborators"
                            ></grey-button>
                            <div class="accounts" v-if="viewCollaborators">
                                <simple-account-badge
                                    v-for="(account,index) in collaborators"
                                    :key="index"
                                    :account="account"
                                    :detailKeys="['share']"
                                    class="mr-3"
                                    :actions="['remove']"
                                    @clickedAccount="clickedCollaborator"
                                    @clickedDashboardAction="clickedDashboardAction"
                                ></simple-account-badge>
                            </div>
                        </template>
                        <template slot="buttons">
                            <post-button
                                :buttonText="buttonText"
                                buttonStyle='success'
                                @click="clickedCreate"
                            ></post-button>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import SearchInput from '../SearchInput.vue';
import MainCheckbox from '../MainCheckbox.vue';
import NumberInput from '../NumberInput.vue';
import TextTextarea from '../TextTextarea.vue';
import TextInput from '../TextInput.vue';
import GreyButton from '../GreyButton.vue';
import PostButton from '../PostButton.vue';
import RadioInput from '../RadioInput.vue';
import SimpleAccountBadge from '../SimpleAccountBadge.vue';
import { mapActions, mapGetters } from 'vuex';
import Alert from '../../mixins/Alert.mixin';
import { default as _ } from 'lodash';
import { bus } from '../../app';
    export default {
        components: {
            SimpleAccountBadge,
            RadioInput,
            PostButton,
            GreyButton,
            TextInput,
            TextTextarea,
            NumberInput,
            MainCheckbox,
            SearchInput,
            
            
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            edit: {
                type: Boolean,
                default: false
            },
            editable: {
                type: Object,
                default(){
                    return null
                }
            },
            schoolAdmin: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        data() {
            return {
                loading: false,
                title: 'create collaboration',
                data: {
                    name: '',
                    description: '',
                    type: '',
                    collaborate: false,
                    accountShare: '',
                    collaboratorShare: '',
                },
                accounts: [],
                collaborators: [],
                removedCollaborators: [],
                editedCollaborators: [],
                mainCollaborators: [],
                selectedAccount: null,
                searchText: '',
                searchType: 'all',
                searchNextPage: 1,
                searchLoading: false,
                buttonText: 'create',
                viewCollaborators: false,
                maxAllowableShare: 100,
            }
        },
        watch: {
            "data.collaborate"(newValue) {
                if (newValue) {
                    this.addCollaborationAccount(this.computedAccount)
                } else {
                    this.removeCollaborationAccount(this.computedAccount)
                }
            },
            "data.accountShare"(newValue) {
                if (this.data.collaborate) {
                    this.updateCollaboratorShare(this.computedAccount,newValue)
                }
            },
            "data.collaboratorShare"(newValue) {
                if (this.selectedAccount) {
                    this.updateCollaboratorShare(this.selectedAccount,newValue)
                }
            },
            searchText(newValue,oldValue){
                this.getCollaborators()
            },
            searchType(newValue,oldValue){
                this.getCollaborators()
            },
            selectedAccount(newValue,oldValue){
                if (newValue) {
                    this.addCollaborationAccount(newValue)
                }
            },
            show: {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        this.getCollaborators()
                    }
                }
            }
        },
        created () {
            bus
            .$on('editCollaboration',(data)=>{
                this.setData(data)
                setTimeout(function(){
                    if (this.computedAccountCollaboratorIndex > -1) {
                        this.data.collaborate = true
                    }
                }.bind(this),200)
            })
        },
        mixins: [Alert],
        computed: {
            ...mapGetters(['dashboard/getAccountDetails']),
            computedAccount() {
                return this['dashboard/getAccountDetails'] ?
                    this['dashboard/getAccountDetails'] : null
            },
            computedSearching() {
                return !this.accounts.length ? 
                    this.searchLoading : false
            },
            computedShowNoData() {
                return !this.searchLoading && this.searchNextPage !== 1 &&
                    !this.accounts.length
            },
            computedShowInfiniteLoader() {
                return !this.searchLoading && this.searchNextPage &&
                    this.searchNextPage > 1
            },
            computedAccountCollaboratorIndex() {
                return this.collaborators.findIndex(account=>{
                    return account.accountId === this.computedAccount.accountId &&
                        account.account === this.computedAccount.account
                })
            },
            computedShowCollaborate() {
                return this.computedAccount && 
                    this.isValidCollaborator(this.computedAccount)
            },
            computedTypeNote() {
                return this.data.type === 'free' ? 
                    'this means collaborators will not be receiving any commissions' : 
                    this.data.type === 'paid' ?
                    'this means collaborators will be receiving commissions based on their respective percentage share' 
                    : ''
            },
            computedTotalCollaboratorsShares() {
                return this.collaborators.reduce(function(sum,account){
                    return sum + account.share
                },0)
            },
            computedMaxAllowableShare() {
                return 100 - this.computedTotalCollaboratorsShares
            }
        },
        methods: {
            ...mapActions(['profile/getProfileAccounts',
                'dashboard/createCollaboration','dashboard/editCollaboration']),
            stateSelection(data) {
                this.data.state = data
            },
            clickedSearchType(data) {
                this.searchType = data
            },
            getSelectedAccount(account) {
                this.selectedAccount = account
            },
            clickedCollaborator(account) {
                if (this.isComputedAccount(account)) {
                    this.data.accountShare = `${account.share}`
                } else {
                    this.selectedAccount = account
                    this.data.collaboratorShare = `${account.share}`
                }
            },
            setMaxAllowableShare() {
                this.maxAllowableShare = this.computedMaxAllowableShare
            },
            isValidCollaborator(account) {
                if (account.account && 
                    (account.account === 'facilitator' ||
                    account.account === 'professional')) {
                    return true
                }
                return false
            },
            isComputedAccount(account) {
                return account.account === this.computedAccount.account &&
                    account.accountId === this.computedAccount.accountId
            },
            isSelectedAccount(account) {
                return this.selectedAccount &&
                    account.account === this.selectedAccount.account &&
                    account.accountId === this.selectedAccount.accountId
            },
            clickedViewCollaborators() {
                this.viewCollaborators = !this.viewCollaborators
            },
            async getCollaborators() {
                this.searchNextPage = 1
                let response = await this.search()

                this.accounts = response.accounts

                if (response.next) {
                    this.searchNextPage += 1
                } else {
                    this.searchNextPage = null
                }
            },
            async search() {
                let data = {
                    account: this.computedAccount.account,
                    accountId: this.computedAccount.accountId,
                    search: this.searchText,
                    searchType: this.searchType,
                    for: 'collaboration'
                },
                    response
                
                this.searchLoading = true

                response = await this['profile/getProfileAccounts']({
                    data, nextPage: this.searchNextPage
                })

                this.searchLoading = false

                if (response.status) {
                    return {
                        accounts: response.accounts,
                        next: response.next
                    }
                } else {
                    return {
                        accounts: [],
                        next: null
                    }
                }
            },
            async infiniteHandler($state) {
                if (!this.searchNextPage) {
                    $state.complete()
                    return
                }
                let response = await this.search()

                this.accounts.push(...response.accounts)

                if (response.next) {
                    this.searchNextPage += 1
                    $state.loaded()
                } else {
                    this.searchNextPage = null
                    $stae.complete()
                }

            },
            closeModal() {
                this.clearData()
                this.$emit('closeCreateCollaboration')
            },
            setData(data) {
                this.buttonText = 'edit'
                this.title = 'edit collaboration'
                this.data.name = data.name
                this.data.collaborationId = data.id
                this.data.description = data.description
                this.data.type = data.type.toLowerCase()
                this.collaborators = []
                this.mainCollaborators = []
                this.collaborators.push(...data.collaborators)
                this.mainCollaborators.push(..._.cloneDeep(data.collaborators))
                data.collaborators.forEach(collaborator=>{
                    if (this.isComputedAccount(collaborator)) {
                        this.data.accountShare = `${collaborator.share}`
                    }
                })
            },
            clearData() {
                this.data.name = ''
                this.data.description = ''
                this.data.type = ''
                this.data.collaborate = false
                this.collaborators = []
                this.mainCollaborators = []
                this.data.accountShare = ''
                this.data.collaboratorShare = ''
                this.selectedAccount = null
                this.searchText = ''
                this.searchType = 'all'
                this.searchNextPage = 1
                this.searchLoading = false
                this.title = 'create collaboration'
                this.buttonText = 'create'
                this.viewCollaborators = false
                this.clearAlert()
            },
            clearExtraData() {
                this.removedCollaborators = []
                this.editedCollaborators = []
            },
            getSearchText(data) {
                this.searchText = data
            },
            clickedDashboardAction(data) {
                if (data.action === 'remove') {
                    this.removeCollaborationAccount(data.account)
                    if (this.isComputedAccount(data.account)) {
                        this.data.collaborate = false
                    }
                    if (this.isSelectedAccount(data.account)) {
                        this.selectedAccount = null
                    }
                }
            },
            addCollaborationAccount(account) {
                let index = this.findCollaborationAccountIndex(account)
                if (index === -1) {
                    this.collaborators.unshift({
                        name: account.name,
                        url: account.url,
                        account: account.account,
                        accountId: account.accountId,
                        share: 0,
                    })
                    if (!this.isComputedAccount(account)) {
                        this.data.collaboratorShare = ''
                    }
                }
            },
            updateCollaboratorShare(account, share) {
                let index = this.findCollaborationAccountIndex(account)
                if (index > -1) {
                    this.collaborators[index]
                        .share = share.length ? Number.parseFloat(share) : 0
                    this.setMaxAllowableShare()
                }
            },
            removeCollaborationAccount(account) {
                let index = this.findCollaborationAccountIndex(account)
                if (index > -1) {
                    this.collaborators.splice(index,1)
                    if (this.isComputedAccount(account)) {
                        this.data.accountShare = ''
                    }
                    this.setMaxAllowableShare()
                }
            },
            findCollaborationAccountIndex(account) {
                return this.collaborators.findIndex(ca=>{
                    return ca.account === account.account && 
                        ca.accountId === account.accountId
                })
            },
            removedCollaboratorsUpdate(account) {
                this.removedCollaborators.push(account)
            },
            editedCollaboratorsUpdate(account) {
                this.editedCollaborators.push(account)
            },
            hasExceededShareAllowance() {
                return this.computedTotalCollaboratorsShares > 100
            },
            async clickedCreate() {
                if (this.loading) return
                let msg = ''
                if (!this.data.name.length) {
                    msg = 'collaboration requires a name'
                } else if (!this.data.type.length) {
                    msg = 'collaboration requires a type (free/paid)'
                } else if (this.hasExceededShareAllowance()) {
                    msg = 'please make sure shares do not exceed 100'
                }

                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return 
                }

                this.loading = true
                let response,
                    data = new FormData
                    
                data.append('name', this.data.name)
                data.append('description', this.data.description)
                data.append('type', this.data.type)
                data.append('account', this.computedAccount.account)
                data.append('accountId', this.computedAccount.accountId)
                if (this.schoolAdmin) { 
                    data.append('adminId', this.schoolAdmin.id)
                }

                if (this.edit) {
                    data.append('collaborationId', this.data.collaborationId)
                    let collaborators = _.cloneDeep(this.collaborators)
                    this.mainCollaborators.forEach(mainCollabo=>{
                        let index = this.findCollaborationAccountIndex(mainCollabo)
                        if (index === -1) {
                            this.removedCollaboratorsUpdate(mainCollabo)
                        } else {
                            collaborators.splice(index,1)
                        }
                        index = this.collaborators.findIndex(collabo=>{
                            return collabo.accountId === mainCollabo.accountId &&
                                collabo.account === mainCollabo.account &&
                                collabo.share != mainCollabo.share
                        })
                        if (index > -1) {
                            this.editedCollaboratorsUpdate(this.collaborators[index])
                        }
                    })
                    data.append('removedCollaborators', JSON.stringify(
                        this.removedCollaborators.map(account=>{
                            return {
                                account: account.account,
                                accountId: account.accountId
                            }
                        })
                    ))
                    data.append('editedCollaborators', JSON.stringify(
                        this.editedCollaborators.map(account=>{
                            return {
                                account: account.account,
                                accountId: account.accountId,
                                share: account.share,
                            }
                        })
                    ))
                    data.append('collaborators', JSON.stringify(
                        collaborators.map(account=>{
                            return {
                                account: account.account,
                                accountId: account.accountId,
                                share: account.share,
                            }
                        })
                    ))
                    
                    response = await this['dashboard/editCollaboration'](data)
                } else {    
                    data.append('collaborators', JSON.stringify(
                        this.collaborators.map(account=>{
                            return {
                                account: account.account,
                                accountId: account.accountId,
                                share: account.share,
                            }
                        })
                    ))

                    response = await this['dashboard/createCollaboration'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.name} was successfully ${action}`
                    if (!this.edit) {
                        this.clearData()
                    }
                } else {
                    this.issueDangerAlertForResponse(response.response)
                    console.log('response :>> ', response);
                }
                this.clearExtraData()
            },
        },
    }
</script>

<style lang="scss" scoped>

    .create-collaboration-modal-wrapper{
        z-index: 10005;

        .welcome-form{
            position: relative;

            .loading{
                @include sticky-loader()
            }

            .section{
                @include form-section()
            }

            .type{
                width: 90%;
                margin: 10px auto;
                display: flex;
            }

            .note{
                text-align: center;
                font-size: 12px;
                color: gray;
            }

            .search-input{
                border: none;
                border-bottom: 2px solid $color-primary;
                background: white;
            }

            .class-input, .number-input{
                width: 90%;
                margin: 10px auto;
                border: none;
                border-radius: 0;
            }

            .other-input{
                width: 90%;
                margin: 10px auto;
            }

            .number-input{
                border: none;
            }

            .search-types{
                display: flex;
                width: 90%;
                margin: 10px auto;
                justify-content: center;

                .grey-button{
                    margin-right: 10px;
                }
            }

            .accounts{
                display: flex;
                width: 90%;
                margin: 10px auto;
                align-content: center;
                flex-wrap: nowrap;
                overflow-x: auto;

                .no-data{
                    width: 100%;
                    text-align: center;
                    font-size: 12px;
                    color: gray;
                    margin: 20px auto;
                }

                .v-spinner{
                    text-align: center;
                    width: 100%;
                }
            }
        }
    }
</style>