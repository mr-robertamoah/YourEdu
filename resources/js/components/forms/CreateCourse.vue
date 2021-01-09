<template>
    <div>
        <just-fade>
            <template slot="transition" v-if="show">
                <main-modal
                    :show="show"
                    :mainOther="false"
                    :requests="false"
                    @mainModalDisappear='closeModal'
                >
                    <template slot="main">
                        <welcome-form
                            :title="title"
                            class="create-course-wrapper"
                        >
                            <template slot="input">
                                <auto-alert
                                    :message="alertMessage"
                                    :success="alertSuccess"
                                    :danger="alertDanger"
                                    :sticky="true"
                                    @hideAlert="clearAlert"
                                ></auto-alert>
                                <div class="loading">
                                    <pulse-loader :loading="loading"></pulse-loader>
                                </div>
                                <div class="section">Course Info</div>
                                <text-input
                                    :bottomBorder="true"
                                    placeholder="course name*"
                                    v-model="data.name"
                                    class="other-input"
                                ></text-input>
                                <text-textarea
                                    :bottomBorder="true"
                                    placeholder="description of the course"
                                    v-model="data.description"
                                    class="class-input"
                                ></text-textarea>

                                <main-select
                                    v-if="edit"
                                    :items="['pending','accepted','declined','suspended']"
                                    :value="data.state"
                                    backgroundColor="white"
                                    @selection="courseStateSelection"
                                    class="other-input"
                                    placeholder="change state of class"
                                ></main-select>

                                <main-checkbox
                                    v-if="computedCreator.account !== 'school'"
                                    v-model="data.facilitate"
                                    label="will you be a faciliatator in this course?"
                                    class="class-input"
                                ></main-checkbox>
                                
                                <div class="section" 
                                    v-if="computedShowOwnership">Course Ownership</div>
                                <main-select
                                    class="other-input"
                                    v-if="computedShowOwnership"
                                    placeholder="select owner of this course"
                                    backgroundColor='white'
                                    :objects="computedPossibleOwners"
                                    :value="data.owner.name"
                                    @selection="ownerSelection"
                                ></main-select>
                                
                                <div class="section">Course Attachments</div>
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

                                <div class="class-payment course-classes-section" 
                                    v-if="computedAccount.account === 'school' ||
                                        data.owner.account === 'school'"
                                >
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
                                    <pulse-loader 
                                        :loading="specificItemLoading"
                                        size="12px"
                                        class="loading"
                                    ></pulse-loader>
                                    <div class="get-more" 
                                        @click="getSpecificAccountItem"
                                        v-if="specificItemDetailsNextPage"
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
                                <div class="attachments" v-if="data.mainPaymentData.length">
                                    <div
                                        v-for="(item,index) in data.mainPaymentData"
                                        :key="index"
                                    >
                                        <price-badge
                                            v-if="item.type === 'price'"
                                            :data="item"
                                            @clickedRemovePrice="clickedRemovePayment(item,'main')"
                                            class="payment-badge"
                                        ></price-badge>
                                        <subscription-badge
                                            v-if="item.type === 'subscription'"
                                            :data="item"
                                            @clickedRemoveSubscription="clickedRemovePayment(item,'main')"
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
                                            @clickedRemovePrice="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></price-badge>
                                        <subscription-badge
                                            v-if="item.type === 'subscription'"
                                            :data="item"
                                            @clickedRemoveSubscription="clickedRemovePayment(item,'removed')"
                                            class="payment-badge"
                                        ></subscription-badge>
                                    </div>
                                </div>
                                <div class="attachment-heading" 
                                    v-if="edit && data.paymentData.length"
                                >
                                    new payment types
                                </div>
                                <payment-types
                                    v-if="computedPayment && !edit"
                                    @paymentType="getPaymentType"
                                    :type="paymentType"
                                    :radioValue="data.type"
                                    class="other-input"
                                ></payment-types>
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
import TextInput from '../TextInput';
import AttachmentBadge from '../AttachmentBadge';
import PostAttachment from '../PostAttachment';
import AutoAlert from '../AutoAlert';
import PaymentTypes from '../PaymentTypes';
import PriceBadge from '../PriceBadge';
import FeeBadge from '../FeeBadge';
import SubscriptionBadge from '../SubscriptionBadge';
import ItemBadge from '../dashboard/ItemBadge';
import CreateDiscussion from './CreateDiscussion';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import { mapActions, mapGetters } from 'vuex'
import {bus} from '../../app';
import DashboardCreateForm from '../../mixins/DashboardCreateForm.mixin';
    export default {
        components: {
            PulseLoader,
            ItemBadge,
            SubscriptionBadge,
            FeeBadge,
            PriceBadge,
            PaymentTypes,
            AutoAlert,
            CreateDiscussion,
            PostAttachment,
            TextInput,
            AttachmentBadge,
            PostButton,
            TextTextarea,
            MainSelect,
            MainCheckbox,
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
                handler(newValue, oldValue){
                    if (newValue.account === 'school') {
                        this.specificItemDetails = []
                        this.specificItem = ''
                        this.specificItemDetailsNextPage = 0
                        this.specificItem = 'class'
                        this.getSpecificAccountItem()
                    }
                }
            },
        },
        created () {
            this.title = 'create a course'
            bus
            .$on('editCourse',(data)=>{
                this.setData(data)
            })
            .$on('courseOwnership',()=>{
                this.hasOwnership = true
                this.data.owner = this.computedCreator
            })
        },
        mixins: [DashboardCreateForm],
        computed: {
            ...mapGetters(['dashboard/getAccountDetails','dashboard/getCurrentAccount',
                'getUser']),
            computedOwner() {
                return this.data.owner
            }
        },
        methods: {
            ...mapActions(['dashboard/createCourse','dashboard/editCourse',
                'dashboard/getAccountSpecificItem']),
            closeModal() {
                this.clearData()
                this.$emit('closeCreateCourse')
            },
            setData(data) {
                this.data.name = data.name
                this.data.courseId = data.id
                this.data.state = data.state.toLowerCase()
                this.data.description = data.description
                this.data.mainAttachments.push(...data.courses)
                this.data.mainAttachments.push(...data.programs)
                this.data.mainAttachments.push(...data.grades)
                if (data.subscriptions) this.data.mainPaymentData.push(...data.subscriptions)
                if (data.prices) this.data.mainPaymentData.push(...data.prices)
                if (data.fees) this.data.mainPaymentData.push(...data.fees)
                this.data.facilitate = data.facilitators.findIndex(facilitator=>{
                    return facilitator.userId === this.getUser.id
                }) > -1
                this.buttonText = 'edit'
            },
            //classes
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
                    return cl.id === data.id 
                })
            },
            removeClass(data) {
                let index = this.findClassIndex(data)
                if (index > -1) {
                    this.data.classes.splice(index,1)
                }
            },
            courseStateSelection(data){
                this.data.state = data
            },
            ownerSelection(data){
                this.data.owner = data
                this.data.paymentData = null
                this.data.type = 'free'
            },
            attachmentSelection(data){
                this.data.attachmentType = data
            },
            //payment
            getPaymentType(data){
                this.data.type = data.type
                this.data.paymentData = data.data
            },
            async getSpecificAccountItem(){
                if (this.specificItemDetailsNextPage === null) {
                    return
                }
                let response,
                    data = {
                        account: this.data.owner.account,
                        accountId: this.data.owner.accountId,
                        item: this.specificItem
                    }

                this.specificItemLoading = true
                response = await this['dashboard/getAccountSpecificItem']({
                    data, nextPage: this.specificItemDetailsNextPage
                })
                this.specificItemLoading = false

                if (response.status) {
                    this.specificItemDetails.push(...response.items)
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
                    msg = 'course requires a name'
                } else {
                    if (this.edit) {
                        if (!this.data.state.length) {
                            msg = 'course requires a state'
                        }
                    } else {
                        if ((this.computedCreator.account === 'facilitator' ||
                            this.computedCreator.account === 'professional') &&
                            this.computedPossibleOwners.length > 1 && 
                            !this.data.owner.account) {
                            msg = 'Please select the owner of this course you are creating.'
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

                if (!this.edit && this.data.owner.account === 'school') {  
                    data.append('classes', JSON.stringify(this.data.classes.map(cl=>{
                        return {
                            id: cl.id
                        }
                    })))
                }

                if (this.edit) {
                    if (this.computedAdmin) { 
                        data.append('account', 'admin')
                        data.append('accountId', this.computedAdmin.id)
                    } else {
                        data.append('account', this.computedAccount.account)
                        data.append('accountId', this.computedAccount.accountId)
                    }

                    if (this.computedAccount.account === 'facilitator' ||
                        this.computedAccount.account === 'professional') { 
                        data.append('facilitate', JSON.stringify(this.data.facilitate))
                    }
                    data.append('state', this.data.state)
                    data.append('courseId', this.data.courseId)
                    data.append('removedAttachments', JSON.stringify(this.data.removedAttachments.map(attachment=>{
                        return {
                            type: attachment.type.slice(0, attachment.type.length - 1),
                            id: attachment.data.id
                        }
                    })))
                    response = await this['dashboard/editCourse'](data)
                } else {
                    if (this.data.discussionData.title.length) {                        
                        data.append('discussionData', JSON.stringify(this.data.discussionData))
                        this.discussionFiles.forEach(file=>{
                            data.append('discussionFile[]', file)
                        })
                    }
                    data.append('type', this.data.type)
                    data.append('paymentData', JSON.stringify(this.data.paymentData))
                    if (this.computedAccount.account === 'facilitator' ||
                        this.computedAccount.account === 'professional') {                    
                        data.append('owner', this.data.owner.account ? 
                            this.data.owner.account : 
                            this.computedAccount.account)
                        data.append('ownerId', this.data.owner.account ? 
                            this.data.owner.accountId : 
                            this.computedAccount.accountId)
                        data.append('account', this.computedAccount.account)
                        data.append('accountId', this.computedAccount.accountId)
                        data.append('facilitate', JSON.stringify(this.data.facilitate))
                    } else if (this.computedAccount.account === 'school') {                  
                        data.append('owner', this.computedAccount.account)
                        data.append('ownerId', this.computedAccount.accountId)
                        if (this.computedAccount.owner) {                        
                            data.append('account', this.computedAccount.account)
                            data.append('accountId', this.computedAccount.accountId)
                        } else if (this.computedAdmin) {
                            data.append('account', 'admin')
                            data.append('accountId', this.computedAdmin.id)
                        }
                    }

                    response = await this['dashboard/createCourse'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.name} was successfully ${action}`
                    if (this.edit) {
                        this.$emit('courseSuccessfullyEdited', response.courseResource)
                    }
                    this.clearData()
                } else {
                    let action = this.edit ? 'editing' : 'creation'
                    this.alertDanger = true
                    this.alertMessage = `course ${action} was unsuccessful`
                    console.log('response :>> ', response);
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .create-course-wrapper{
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
            border-bottom: 2px solid $background-color-main;
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

        .attachment-heading{
            font-size: 12px;
            color: gray;
            text-align: center;
        }

        .attachments{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
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

            .class-wrapper{
                display: flex;
                flex-wrap: wrap;
                width: 90%;
                margin: 10px auto;
                align-items: center;
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