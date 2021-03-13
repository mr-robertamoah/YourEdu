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
                            class="create-extracurriculum-wrapper"
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
                                <div class="section">Extracurriculum Info</div>
                                <text-input
                                    :bottomBorder="true"
                                    placeholder="extracurriculum name*"
                                    v-model="data.name"
                                    class="other-input"
                                ></text-input>
                                <text-textarea
                                    :bottomBorder="true"
                                    placeholder="description of the extracurriculum"
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
                                    label="will you be a faciliatator in this extracurriculum?"
                                    class="class-input"
                                ></main-checkbox>

                                <div class="section" v-if="computedShowOwnership">Extracurriculum Ownership</div>
                                <main-select
                                    class="other-input"
                                    v-if="computedShowOwnership"
                                    placeholder="select owner of this extracurriculum"
                                    backgroundColor='white'
                                    :objects="computedPossibleOwners"
                                    :value="data.owner.name"
                                    @selection="ownerSelection"
                                ></main-select>
                                
                                <div class="section">Extracurriculum Attachments</div>
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

                                <div class="attachment-heading">
                                    {{computedSpecificItemType}}
                                </div>
                                <search-input
                                    class="search-input"
                                    v-if="data.owner.account"
                                    :searchPlaceholder="`search for classes and programs`"
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
                                            type="class"
                                            :hasRemove="inClassesSelection(item)"
                                            class="class-badge"
                                            @clickedItem="classSelected"
                                            @clickedRemoveItem="removeClass"
                                        ></item-badge>
                                    </div>
                                    <div class="no-data" 
                                        v-if="!specificItemLoading && !computedSpecificItems.length">
                                        {{`no ${computedSpecificItemType} for this ${data.owner.account}`}}
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
                                    :radioValue="data.type"
                                    class="other-input"
                                    @paymentTypeError="error"
                                ></payment-types>

                                <div class="section" v-if="computedShowDiscussion">Discussion</div>
                                <main-checkbox
                                    v-model="data.discussion"
                                    v-if="computedShowDiscussion && 
                                        !data.discussionData.title.length"
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
import MainCheckbox from '../MainCheckbox';
import MainSelect from '../MainSelect';
import TextTextarea from '../TextTextarea';
import PostButton from '../PostButton';
import AttachmentBadge from '../AttachmentBadge';
import TextInput from '../TextInput';
import PostAttachment from '../PostAttachment';
import AutoAlert from '../AutoAlert';
import PaymentTypes from '../PaymentTypes';
import PriceBadge from '../PriceBadge';
import FeeBadge from '../FeeBadge';
import SubscriptionBadge from '../SubscriptionBadge';
import CreateDiscussion from './CreateDiscussion';
import ItemBadge from '../dashboard/ItemBadge';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import { mapActions, mapGetters } from 'vuex'
import {bus} from '../../app';
import DashboardCreateForm from '../../mixins/DashboardCreateForm.mixin';
    export default {
        components: {
            PulseLoader,
            PaymentTypes,
            SubscriptionBadge,
            FeeBadge,
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
        props: {
            
        },
        data() {
            return {
                
            }
        },
        watch: {
            computedOwner: {
                deep: true,
                handler(newValue){
                    if (newValue.account) this.debouncedSearchItems()
                }
            },
        },
        created () {
            this.title = 'create an extracurriculum'
            bus
            .$on('editExtracurriculum',(data)=>{
                this.setData(data)
            })
            .$on('extracurriculumOwnership',()=>{
                this.hasOwnership = true
                this.data.owner = this.computedCreator
            })
        },
        mixins: [DashboardCreateForm],
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','dashboard/getCurrentAccount',
                'getUser']),
            computedAttachment(){
                if (this.data.owner.account === 'school') {
                    return ['programs','grades','courses']
                }
                return ['programs','grades','courses']
            },
            computedOwner() {
                return this.data.owner
            },
            computedSpecificItemType() {
                return this.data.owner.account !== 'professional' ? 
                    `classes and programs` : `programs`
            },
        },
        methods: {
            ...mapActions(['dashboard/createExtracurriculum','dashboard/editExtracurriculum',
                'dashboard/getAccountSpecificItem']),
            closeModal() {
                this.data.owner = {name: ''}
                this.clearData()
                this.$emit('closeCreateExtracurriculum')
            },
            setData(data) {
                this.data.name = data.name
                this.data.extracurriculumId = data.id
                this.data.state = data.state?.toLowerCase()
                this.data.description = data.description
                this.data.classes = []
                this.data.mainClasses = []
                this.data.classes.push(...data.classes)
                this.data.mainClasses.push(...data.classes)
                this.data.mainAttachments = []
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
            //classes or programs
            inClassesSelection(data) {
                let index = this.findClassIndex(data)
                if (index > -1) {
                    return true
                }
                return false
            },
            classSelected(data) {
                let index = this.findClassIndex(data)
                if (index === -1) {
                    this.data.classes.push(data)
                }
            },
            findClassIndex(data) {
                return this.data.classes.findIndex(cl=>{
                    return cl.id === data.id && cl.type === data.type
                })
            },
            removeClass(data) {
                let index = this.findClassIndex(data)
                if (index > -1) {
                    this.data.classes.splice(index,1)
                }
            },
            removedClassesUpdate(data) {
                let index = this.data.removedClasses.findIndex(cl=>{
                    return data.type === cl.type && data.id === cl.id
                })
                if (index === -1) {
                    this.data.removedClasses.push(data)
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
                        item: 'class',
                        secondItem: 'program',
                        search: this.searchItemsText
                    }

                this.specificItemLoading = true
                response = await this['dashboard/getAccountSpecificItem']({
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
            async clickedCreate(){
                if (this.loading) return
                let msg = ''
                if (!this.data.name.length) {
                    msg = 'extracurriculum requires a name'
                } else {
                    if (this.edit) {
                        if (!this.data.state.length) {
                            msg = 'extracurriculum requires a state'
                        }
                    } else {
                        if ((this.computedCreator.account === 'facilitator' ||
                            this.computedCreator.account === 'professional') &&
                            this.computedPossibleOwners.length > 1 && 
                            !this.data.owner.account) {
                            msg = 'Please select the owner of this extracurriculum you are creating.'
                        } else if (this.data.type !== 'free' && 
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
                data.append('type', this.data.type)
                data.append('paymentData', JSON.stringify(this.data.paymentData))
 
                data.append('classes', JSON.stringify(this.data.classes.map(cl=>{
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

                if (this.edit) {
                    data.append('state', this.data.state)
                    data.append('main', JSON.stringify(this.computedCheckMain))
                    data.append('extracurriculumId', this.data.extracurriculumId)
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
                    this.data.mainClasses.forEach(mainCl=>{ //check if class or program has been removed
                        let index = this.data.classes.findIndex(cl=>{
                            return cl.type === mainCl.type && cl.id === mainCl.id
                        })
                        if (index === -1) {
                            this.removedClassesUpdate(mainCl)
                        }
                        console.table(this.data.removedClasses)
                    })
                    data.append('removedClasses', JSON.stringify(this.data.removedClasses.map(attachment=>{
                        return {
                            type: attachment.type,
                            id: attachment.id
                        }
                    })))
                    response = await this['dashboard/editExtracurriculum'](data)
                } else {
                    if (this.data.discussionData.title.length) {                        
                        data.append('discussionData', JSON.stringify(this.data.discussionData))
                        this.discussionFiles.forEach(file=>{
                            data.append('discussionFile[]', file)
                        })
                    }
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

                    response = await this['dashboard/createExtracurriculum'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.name} was successfully ${action}`
                    if (this.edit) {
                        if (this.computedCheckMain) this.$emit('extracurriculumSuccessfullyEdited', response.extracurriculumResource)
                    } else {
                        this.clearData()
                    }
                    bus.$emit('updateExtracurriculum',response.extracurriculum)
                } else {
                    this.responseErrorAlert(response.response)
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

    .create-extracurriculum-wrapper{
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
            border-bottom: 2px solid $color-primary;
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
            display: flex;
            justify-content: center;
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