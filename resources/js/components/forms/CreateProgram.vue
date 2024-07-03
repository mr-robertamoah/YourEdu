<template>
    <div>
        <just-fade>
            <template slot="transition" v-if="show">
                <main-modal
                    :show="show"
                    :mainOther="false"
                    :requests="false"
                    @mainModalDisappear='closeModal'
                    class="modal-wrapper"
                >
                    <template slot="main">
                        <welcome-form
                            :title="title"
                            class="create-program-wrapper"
                        >
                            <template slot="input">
                                <auto-alert
                                    :message="alertMessage"
                                    :success="alertSuccess"
                                    :danger="alertDanger"
                                    :sticky="true"
                                    @hideAlert="clearAlert"
                                ></auto-alert>
                                <div class="loading" v-if="loading">
                                    <pulse-loader :loading="loading"></pulse-loader>
                                </div>
                                <div class="section">Program Info</div>
                                <text-input
                                    :bottomBorder="true"
                                    placeholder="program name*"
                                    v-model="data.name"
                                    class="other-input"
                                ></text-input>
                                <text-textarea
                                    :bottomBorder="true"
                                    placeholder="description of the program"
                                    v-model="data.description"
                                    class="class-input"
                                ></text-textarea>
                                <main-select
                                    v-if="edit"
                                    :items="['pending','accepted','declined','suspended']"
                                    :value="data.state"
                                    backgroundColor="white"
                                    @selection="stateSelection"
                                    class="other-input"
                                    placeholder="change state of class"
                                ></main-select>
                                <main-checkbox
                                    v-if="computedCreator.account !== 'school'"
                                    v-model="data.facilitate"
                                    label="will you be a faciliatator in this program?"
                                    class="class-input"
                                ></main-checkbox>

                                <div class="section" 
                                    v-if="computedShowOwnership">Program Ownership</div>
                                <main-select
                                    class="other-input"
                                    v-if="computedShowOwnership"
                                    placeholder="select owner of this program"
                                    backgroundColor='white'
                                    :objects="computedPossibleOwners"
                                    :value="data.owner.name"
                                    @selection="ownerSelection"
                                ></main-select>
                                
                                <div class="section">Program Attachments</div>
                                <main-select
                                    class="other-input"
                                    v-if="computedAttachment.length > 1"
                                    placeholder="please attach some of these"
                                    backgroundColor='white'
                                    :items="computedAttachment"
                                    :value="data.attachmentType"
                                    @selection="attachmentSelection"
                                ></main-select>

                                <template v-if="edit">
                                    <div class="attachment-heading"
                                        v-if="data.mainAttachments.length"
                                    >
                                        already attached
                                    </div>
                                    <div class="attachments"
                                        v-if="data.mainAttachments.length"
                                    >
                                        <attachment-badge
                                            v-for="(attachment,index) in data.mainAttachments"
                                            :key="index"
                                            :attachment="attachment.data"
                                            :hasClose="true"
                                            @removeAttachment="removeAttachment(attachment,'main')"
                                        ></attachment-badge>
                                    </div>
                                    <div class="attachment-heading"
                                        v-if="data.removedAttachments.length"
                                    >
                                        to be removed/unattached
                                    </div>
                                    <div class="attachments danger"
                                        v-if="data.removedAttachments.length"
                                    >
                                        <attachment-badge
                                            v-for="(attachment,index) in data.removedAttachments"
                                            :key="index"
                                            :attachment="attachment.data"
                                            :hasClose="true"
                                            @removeAttachment="removeAttachment(attachment,'removed')"
                                        ></attachment-badge>
                                    </div>
                                </template>

                                <div class="attachment-heading" 
                                    v-if="edit && data.attachments.length"
                                >
                                    new attachments
                                </div>
                                <div class="attachments"
                                    v-if="data.attachments.length"
                                >
                                    <attachment-badge
                                        v-for="(attachment,index) in data.attachments"
                                        :key="index"
                                        :attachment="attachment.data"
                                        :hasClose="true"
                                        @removeAttachment="removeAttachment"
                                    ></attachment-badge>
                                </div>
                                <post-attachment
                                    v-if="data.attachmentType.length"
                                    :show="true"
                                    :hasSelect="true"
                                    :mainSearchItem="data.attachmentType"
                                    :hasClose="false"
                                    @clickedAttachmentSelection="attachmentSelected"
                                    class="class-input"
                                ></post-attachment>

                                <div class="attachment-heading"
                                    v-if="data.owner.account"
                                >
                                    courses
                                </div>
                                <search-input
                                    class="search-input"
                                    placeholder="search for courses and extracurriculums"
                                    @search="getSearchItemsText"
                                ></search-input>
                                <div class="class-payment course-classes-section">
                                    <div
                                        v-if="computedSpecificItems.length"
                                        class="class-wrapper"
                                    >
                                        <item-badge
                                            v-for="(item,index) in computedSpecificItems"
                                            :key="index"
                                            :item="item"
                                            type="course"
                                            :hasRemove="inItemSelection(item)"
                                            class="class-badge"
                                            @clickedItem="itemSelected"
                                            @clickedRemoveItem="removeItem"
                                        ></item-badge>
                                    </div>
                                    <div class="no-data" 
                                        v-if="!specificItemLoading && !computedSpecificItems.length &&
                                            data.owner.account">
                                        {{`no courses for this ${data.owner.account}`}}
                                    </div>
                                    <pulse-loader 
                                        :loading="specificItemLoading"
                                        size="12px"
                                        class="loading"
                                    ></pulse-loader>
                                    <div class="get-more" 
                                        @click="getSpecificAccountItem"
                                        v-if="computedShowGetMore"
                                    >
                                        get more
                                    </div>
                                </div>
                                
                                <div class="section">Payments</div>
                                <div class="attachment-heading"
                                    v-if="data.mainPaymentData.length"
                                >
                                    current payment types
                                </div>
                                <div class="attachments"
                                    v-if="data.mainPaymentData.length"
                                >
                                    <div
                                        v-for="(item,index) in data.mainPaymentData"
                                        :key="index"
                                    >
                                        <price-badge
                                            v-if="item.type === 'price'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'main')"
                                            class="payment-badge"
                                        ></price-badge>
                                        <subscription-badge
                                            v-if="item.type === 'subscription'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'main')"
                                            class="payment-badge"
                                        ></subscription-badge>
                                    </div>
                                </div>
                                
                                <div class="attachment-heading"
                                    v-if="data.removedPaymentData.length"
                                >
                                    payment types to be removed
                                </div>
                                <div class="attachments danger"
                                    v-if="data.removedPaymentData.length"
                                >
                                    <div
                                        v-for="(item,index) in data.removedPaymentData"
                                        :key="index"
                                    >
                                        <price-badge
                                            v-if="item.type === 'price'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></price-badge>
                                        <subscription-badge
                                            v-if="item.type === 'subscription'"
                                            :data="item"
                                            @clickedRemoveData="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></subscription-badge>
                                    </div>
                                </div>
                                <div class="attachment-heading"
                                    v-if="edit && data.paymentData"
                                >
                                    new payment types
                                </div>
                                <payment-types
                                    v-if="computedShowPayment"
                                    @paymentType="getPaymentType"
                                    :type="paymentType"
                                    :radioValue="data.paymentType"
                                    class="other-input"
                                    @paymentTypeError="error"
                                ></payment-types>

                                <div class="section" v-if="!edit">Discussion</div>
                                <main-checkbox
                                    v-model="data.discussion"
                                    v-if="!edit && !data.discussionData.title.length"
                                    label="automatically add a discussion?"
                                    class="class-input"
                                ></main-checkbox>
                                <!-- discussion preview -->
                                <div class="discussion-preview"
                                    v-if="data.discussionData.title.length"
                                >
                                    <item-badge
                                        type="discussion"
                                        :item="data.discussionData"
                                        :hasRemove="true"
                                        @clickedRemoveItem="clearDiscussionData"
                                    ></item-badge>
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
        <create-discussion
            :show="data.discussion"
            v-if="data.discussion"
            :edit="edit"
            :auto="true"
            @clickedCreate="getDiscussionData"
            @createDiscussionDisappear="closeDiscussionModal"
        ></create-discussion>
    </div>
</template>

<script>
import MainCheckbox from '../MainCheckbox.vue';
import MainSelect from '../MainSelect.vue';
import TextTextarea from '../TextTextarea.vue';
import PostButton from '../PostButton.vue';
import AttachmentBadge from '../AttachmentBadge.vue';
import TextInput from '../TextInput.vue';
import PostAttachment from '../PostAttachment.vue';
import AutoAlert from '../AutoAlert.vue';
import PaymentTypes from '../PaymentTypes.vue';
import PriceBadge from '../PriceBadge.vue';
import SubscriptionBadge from '../SubscriptionBadge.vue';
import CreateDiscussion from './CreateDiscussion.vue';
import ItemBadge from '../dashboard/ItemBadge.vue';
import { mapActions, mapGetters } from 'vuex'
import {bus} from '../../app';
import DashboardCreateForm from '../../mixins/DashboardCreateForm.mixin';
    export default {
        components: {
            
            PaymentTypes,
            SubscriptionBadge,
            PriceBadge,
            ItemBadge,
            AutoAlert,
            PostAttachment,
            TextInput,
            AttachmentBadge,
            PostButton,
            TextTextarea,
            MainSelect,
            MainCheckbox,
            CreateDiscussion,
        },
        data() {
            return {
                
            }
        },
        watch: {
            'data.owner': {
                handler(newValue) {
                    if (newValue && newValue.account) {
                        this.debouncedSearchItems()
                    }
                }
            },
        },
        created () {
            this.title = 'create an program'
            bus
            .$on('editProgram',(data)=>{
                this.setData(data)
            })
            .$on('programOwnership',()=>{
                this.hasOwnership = true
                this.data.owner = this.computedCreator
            })
        },
        mixins: [DashboardCreateForm],
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','dashboard/getCurrentAccount',
                'getUser'])

        },
        methods: {
            ...mapActions(['dashboard/createProgram','dashboard/editProgram',
                'dashboard/getAccountSpecificItems']),
            closeModal() {
                this.data.owner = {name: ''}
                this.clearData()
                this.$emit('closeCreateProgram')
            },
            setData(data) {
                this.data.name = data.name
                this.data.programId = data.id
                this.data.state = data.state?.toLowerCase()
                this.data.description = data.description
                this.data.items = [] //courses and extracurriculums
                this.data.mainItems = []
                if (data.items) {
                    this.data.items.push(...data.items)
                    this.data.mainItems.push(...data.items)
                } else {
                    let items = []
                    items.push(...data.courses)
                    items.push(...data.extracurriculums)
                    this.data.items.push(...items)
                    this.data.mainItems.push(...items)
                }
                this.data.mainAttachments = [] //attachments
                if (data.attachments) {
                    this.data.mainAttachments.push(...data.attachments)
                } else {
                    this.data.mainAttachments.push(...data.courses)
                    this.data.mainAttachments.push(...data.programs)
                    this.data.mainAttachments.push(...data.grades)
                }
                this.data.mainPaymentData = []
                this.data.mainPaymentData.push(...data.subscriptions)
                this.data.mainPaymentData.push(...data.prices)
                this.data.facilitate = data.facilitators.findIndex(facilitator=>{
                    return facilitator.userId === this.getUser.id
                }) > -1
                if (!this.data.facilitate) {                    
                    this.data.facilitate = data.professionals.findIndex(professional=>{
                        return professional.userId === this.getUser.id
                    }) > -1
                }
                this.buttonText = 'edit'
                this.data.owner = {
                    name: data.ownedby.name,
                    account: data.ownedby.account,
                    accountId: data.ownedby.accountId,
                }
                this.checkDiscussion(data)
            },
            //courses
            inItemSelection(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            itemSelected(data) {
                let index = this.findItemIndex(data)
                if (index === -1) {
                    this.data.items.push(data)
                }
            },
            findItemIndex(data) {
                return this.data.items.findIndex(cl=>{
                    return cl.id === data.id && cl.type === data.type
                })
            },
            removeItem(data) {
                let index = this.findItemIndex(data)
                if (index > -1) {
                    this.data.items.splice(index,1)
                }
            },
            removedItemsUpdate(data) {
                let index = this.data.removedItems.findIndex(cl=>{
                    return data.type === cl.type && data.id === cl.id
                })
                if (index === -1) {
                    this.data.removedItems.push(data)
                }
            },
            attachmentSelection(data){
                this.data.attachmentType = data
            },
            async getSpecificAccountItem(){
                if (this.specificItemDetailsNextPage === null) {
                    return
                }
                let response,
                    data = {
                        account: this.data.owner.account,
                        accountId: this.data.owner.accountId,
                        items: ['courses', 'extracurriculums'],
                        search: this.searchItemsText,
                        for: 'program'
                    }

                this.specificItemLoading = true
                response = await this['dashboard/getAccountSpecificItems']({
                    data, nextPage: this.specificItemDetailsNextPage
                })
                this.specificItemLoading = false

                if (response.status) {
                    if (!this.specificItemDetailsNextPage) {
                        this.specificItemDetails = response.items
                    } else {
                        this.specificItemDetails.push(...response.items)
                    }
                    if (response.next) {
                        this.specificItemDetailsNextPage += 1
                    } else {
                        this.specificItemDetailsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async clickedCreate() {
                if (this.loading) return
                let msg = ''
                if (!this.data.name.length) {
                    msg = 'program requires a name'
                } else {
                    if (this.edit) {
                        if (!this.data.state.length) {
                            msg = 'program requires a state'
                        }
                    } else {
                        if ((this.computedCreator.account === 'facilitator' ||
                            this.computedCreator.account === 'professional') &&
                            this.computedPossibleOwners.length > 1 && 
                            !this.data.owner.account) {
                            msg = 'Please select the owner of this program you are creating.'
                        } else if (this.data.paymentType !== 'free' && 
                            this.data.paymentData === null) {
                            msg = 'Please enter the required data for the payment.'                    
                        }
                    }
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
                data.append('attachments', JSON.stringify(this.data.attachments.map(attachment=>{
                    return {
                        type: attachment.type.slice(0, attachment.type.length - 1),
                        id: attachment.data.id
                    }
                })))
                data.append('paymentType', this.data.paymentType)
                data.append('paymentData', JSON.stringify(this.data.paymentData))
 
                data.append('items', JSON.stringify(this.data.items.map(cl=>{
                    return {
                        id: cl.id,
                        type: cl.type,
                    }
                })))
                if (this.computedAccount.account === 'facilitator' ||
                    this.computedAccount.account === 'professional') { 
                    data.append('facilitate', JSON.stringify(this.data.facilitate))
                }
                if (this.schoolAdmin) { 
                    data.append('account', 'admin')
                    data.append('accountId', this.schoolAdmin.id)
                } else {
                    data.append('account', this.computedAccount.account)
                    data.append('accountId', this.computedAccount.accountId)
                }
                if (this.data.discussionData.title.length) {                        
                    data.append('discussionData', JSON.stringify(this.data.discussionData))
                    this.discussionFiles.forEach(file=>{
                        data.append('discussionFile[]', file)
                    })
                }

                if (this.edit) {
                    data.append('state', this.data.state)
                    data.append('main', JSON.stringify(this.computedCheckMain))
                    data.append('programId', this.data.programId)
                    data.append('removedAttachments', JSON.stringify(this.data.removedAttachments.map(attachment=>{
                        return {
                            type: attachment.type.slice(0, attachment.type.length - 1),
                            id: attachment.data.id
                        }
                    })))
                    data.append('removedPaymentData', JSON.stringify(
                        this.data.removedPaymentData.map(payment=>{
                            return {
                                type: payment.type,
                                id: payment.id
                            }
                        }
                    )))
                    this.data.mainItems.forEach(mainCl=>{ //check if class or program has been removed
                        let index = this.data.items.findIndex(cl=>{
                            return cl.type === mainCl.type && cl.id === mainCl.id
                        })
                        if (index === -1) {
                            this.removedItemsUpdate(mainCl)
                        }
                    })
                    data.append('removedItems', JSON.stringify(this.data.removedItems.map(attachment=>{
                        return {
                            type: attachment.type,
                            id: attachment.id
                        }
                    })))
                    response = await this['dashboard/editProgram'](data)
                } else {
                    if (this.computedAccount.account === 'facilitator' ||
                        this.computedAccount.account === 'professional') {                    
                        data.append('owner', this.data.owner.account ? 
                            this.data.owner.account : 
                            this.computedAccount.account)
                        data.append('ownerId', this.data.owner.account ? 
                            this.data.owner.accountId : 
                            this.computedAccount.accountId)
                    } else if (this.computedAccount.account === 'school') {                  
                        data.append('owner', this.computedAccount.account)
                        data.append('ownerId', this.computedAccount.accountId)
                    }

                    response = await this['dashboard/createProgram'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.name} was successfully ${action}`
                    if (this.edit) {
                        if (this.computedCheckMain) this.$emit('programSuccessfullyEdited', response.programResource)
                    } else {
                        this.clearData()
                    }
                    bus.$emit('updateProgram',response.program)
                } else {
                    this.issueDangerAlertForResponse(response.response)
                    console.log('response :>> ', response);
                }

            },
        },
    }
</script>

<style lang="scss" scoped>
    .modal-wrapper{
        z-index: 10005;
    }

    .create-program-wrapper{
        position: relative;

        .loading{
            @include sticky-loader()
        }

        .section{
            @include form-section()
        }

        .class-input{
            width: 90%;
            margin: 10px auto;
            border: none;
            border-radius: 0;
        }

        .class-structure{

            .main{
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                width: 100%;
            }

            .message{
                font-size: 12px;
                color: gray;
                width: 100%;
                padding: 0 5px;
            }
        }

        .other-input,
        .attachments{
            width: 90%;
            margin: 10px auto;
        }

        .search-input{
            border: none;
            border-bottom: 2px solid $color-primary;
            background: white;
        }

        .attachment-heading{
            font-size: 12px;
            color: gray;
            text-align: center;
        }

        .attachments{
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            overflow-y: auto;
        }

        .attachments.danger{
            background: red;
            padding: 5px;
        }

        .class-payment{
            
            .message{
                font-size: 12px;
                color: gray;
                margin-bottom: 10px;
            }
        }

        .course-classes-section{
            min-height: 100px;
            align-items: center;

            .class-wrapper{
                display: flex;
                width: 90%;
                margin: 10px auto;
                align-items: center;
                overflow: auto;

                .class-badge{
                    min-width: 150px;
                }
            }

            .no-data{
                font-size: 12px;
                color: gray;
            }

            .get-more{
                width: fit-content;
                margin: 10px auto;
                padding: 5px;
                background: cadetblue;
                color: white;
                font-size: 12px;
                border-radius: 10px;
                cursor: pointer;
            }

            .loading{
                text-align: center;
            }
        }
    }
</style>