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
                                    @selection="stateSelection"
                                    class="other-input"
                                    placeholder="change state of class"
                                ></main-select>

                                <main-checkbox
                                    v-if="computedCreator.account !== 'school'"
                                    v-model="data.facilitate"
                                    label="will you be a faciliatator in this course?"
                                    class="class-input"
                                ></main-checkbox>
                                <main-checkbox
                                    v-if="computedShowSections"
                                    v-model="hasSections"
                                    label="does course have sections?"
                                    class="class-input"
                                ></main-checkbox>
                                <main-checkbox
                                    v-model="standAlone"
                                    label="should be a stand alone course"
                                    class="class-input"
                                ></main-checkbox>
                                <div class="attachment-heading">
                                    Note: stand alone means this course cannot be added to programs or classes from the time it is so set.
                                </div>

                                <template v-if="edit">
                                    <div class="attachment-heading"
                                        v-if="mainSections.length"
                                    >
                                        current sections
                                    </div>
                                    <div class="attachments"
                                        v-if="mainSections.length"
                                    >
                                        <item-badge
                                            v-for="(item,index) in mainSections"
                                            :key="index"
                                            :item="item"
                                            :hasRemove="true"
                                            type="courseSection"
                                            class="class-badge"
                                            @clickedItem="selectedSections"
                                            @clickedRemoveItem="removeSection(item,'main')"
                                        ></item-badge>
                                    </div>
                                    <div class="attachment-heading"
                                        v-if="removedSections.length"
                                    >
                                        sections to be removed
                                    </div>
                                    <div class="attachments danger"
                                        v-if="removedSections.length"
                                    >
                                        <item-badge
                                            v-for="(item,index) in removedSections"
                                            :key="index"
                                            :item="item"
                                            :hasRemove="true"
                                            type="courseSection"
                                            class="class-badge"
                                            @clickedRemoveItem="removeSection(item,'removed')"
                                        ></item-badge>
                                    </div>
                                </template>
                                <div class="attachment-heading"
                                    v-if="edit && sections.length"
                                >
                                    new sections
                                </div>
                                <add-section
                                    v-if="hasSections"
                                    :editable="selectedSection"
                                    @error="error"
                                    @sectionAdded="getSections"
                                    @editedSection="editedSection"
                                    class="add-section"
                                ></add-section>

                                <div class="section" v-if="computedShowOwnership">Course Ownership</div>
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

                                <div class="attachment-heading p-1"
                                    v-if="!standAlone && data.owner.account"
                                >
                                    {{computedSpecificItemType}}
                                </div>
                                <search-input
                                    class="search-input"
                                    v-if="!standAlone && data.owner.account"
                                    placeholder="search for classes and programs"
                                    @search="getSearchItemsText"
                                ></search-input>
                                <div class="class-payment course-classes-section"
                                    v-if="!standAlone && data.owner.account"
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
                                            :hasRemove="inItemsSelection(item)"
                                            class="class-badge"
                                            @clickedItem="itemSelected"
                                            @clickedRemoveItem="removeItem"
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
import TextInput from '../TextInput.vue';
import AttachmentBadge from '../AttachmentBadge.vue';
import PostAttachment from '../PostAttachment.vue';
import AutoAlert from '../AutoAlert.vue';
import PaymentTypes from '../PaymentTypes.vue';
import PriceBadge from '../PriceBadge.vue';
import SubscriptionBadge from '../SubscriptionBadge.vue';
import ItemBadge from '../dashboard/ItemBadge.vue';
import CreateDiscussion from './CreateDiscussion.vue';
import AddSection from '../dashboard/AddSection.vue';
import { mapActions, mapGetters } from 'vuex'
import {bus} from '../../app';
import DashboardCreateForm from '../../mixins/DashboardCreateForm.mixin';
    export default {
        components: {
            
            ItemBadge,
            SubscriptionBadge,
            PriceBadge,
            PaymentTypes,
            AutoAlert,
            AddSection,
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
                hasSections: false,
                sections: [],
                mainSections: [],
                removedSections: [],
                editedSections: [],
                selectedSection: null,
                standAlone: false,
            }
        },
        watch: {
            computedOwner: {
                deep: true,
                handler(newValue){
                    if (newValue && newValue.account) {
                        this.debouncedSearchItems()
                    }
                }
            },
            hasSections(newValue) {
                if (!newValue && this.edit && this.mainSections.length) {
                    this.removedSections.push(...this.mainSections)
                    this.mainSections.forEach(section=>{
                        this.editedSectionsUpdate(section, true)
                    })
                    this.mainSections = []
                }
            },
            mainSections(newValue) {
                if (newValue.length && this.edit && this.hasSections == false) {
                    this.hasSections = true
                }
            },
            standAlone(newValue) {
                if (newValue) {
                    
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
            },
            computedSpecificItemType() {
                return this.data.owner.account !== 'professional' ? 
                    `classes and programs` : `programs`
            },
            computedShowSections() {
                return true 
            }
        },
        methods: {
            ...mapActions(['dashboard/createCourse','dashboard/editCourse',
                'dashboard/getAccountSpecificItems']),
            getSections(sections) {
                this.sections = sections
            },
            closeModal() {
                this.data.owner = {name: ''}
                this.clearData()
                this.sections = []
                this.mainSections = []
                this.removedSections = []
                this.editedSections = []
                this.hasSections = false
                this.standAlone = false
                this.$emit('closeCreateCourse')
            },
            setData(data) {
                this.data.name = data.name
                this.data.courseId = data.id
                this.data.state = data.state.toLowerCase()
                this.data.description = data.description
                this.standAlone = data.standAlone
                if (!data.standAlone) {                    
                    this.data.items = []
                    this.data.mainItems = []
                    this.data.items.push(...data.items)
                    this.data.mainItems.push(...data.items)
                }
                this.data.mainAttachments = []
                this.data.mainAttachments.push(...data.courses)
                this.data.mainAttachments.push(...data.programs)
                this.data.mainAttachments.push(...data.grades)
                this.mainSections = []
                this.mainSections.push(...data.sections)
                if (data.sections.length) {
                    this.hasSections = true
                }
                this.data.mainPaymentData = []
                if (data.subscriptions) this.data.mainPaymentData.push(...data.subscriptions)
                if (data.prices) this.data.mainPaymentData.push(...data.prices)
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
                //todo hassections and sections and remove sections
            },
            inItemsSelection(data) {
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
                    return cl.id === data.id 
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
            //sections
            editedSection(data) {
                let index = this.mainSections.findIndex(section=>{
                    return section.id === data.id
                })
                if (index > -1) {
                    data.edited = true
                    this.mainSections[index] = data
                    this.editedSectionsUpdate(data)
                }
                this.selectedSection = null
            },
            selectedSections(data) {
                this.selectedSection = data
            },
            findSectionIndex(data, type) {
                let sections = type === 'main' ? this.mainSections : 
                    type === 'removed' ? this.removedSections : this.sections
                return sections.findIndex(section=>{
                    return section.id === data.id 
                })
            },
            removeSection(data, type) {
                let index = this.findSectionIndex(data, type)
                if (index > -1) {
                    if (type === 'main') {
                        this.mainSections.splice(index,1)
                        this.removedSections.push(data)
                        this.editedSectionsUpdate(data, true)
                    } else if (type === 'removed') {
                        this.removedSections.splice(index,1)
                        this.mainSections.push(data)
                        if (data.edited) {
                            this.editedSectionsUpdate(data)
                        }
                    }
                }
            },
            editedSectionsUpdate(data, del = false) {
                let index = this.editedSections.findIndex(section=>{
                    return data.id === section.id
                })
                if (!del && index === -1) {
                    this.editedSections.push(data)
                } else if (!del && index > -1) {
                    this.editedSections[index].name = data.name
                    this.editedSections[index].description = data.description
                } else if (del && index > -1) {
                    this.editedSections.splice(index,1)
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
                        items: ['classes', 'programs'],
                        search: this.searchItemsText,
                        for: 'course'
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
                data.append('standAlone', JSON.stringify(this.standAlone)) 
                data.append('paymentData', JSON.stringify(this.data.paymentData)) 
                data.append('sections', JSON.stringify(this.sections)) 
                data.append('paymentType', this.data.paymentType)

                data.append('items', JSON.stringify(this.data.items.map(cl=>{
                    return {
                        id: cl.id,
                        type: cl.type
                    }
                })))
                if (this.computedAccount.account === 'facilitator' ||
                    this.computedAccount.account === 'professional') { 
                    data.append('facilitate', JSON.stringify(this.data.facilitate))
                }
                if (this.schoolAdmin) { 
                    data.append('adminId', this.schoolAdmin.id)
                }

                data.append('account', this.computedAccount.account)
                data.append('accountId', this.computedAccount.accountId)

                if (this.edit) {
                    data.append('main', JSON.stringify(this.computedCheckMain))
                    data.append('state', this.data.state)
                    data.append('courseId', this.data.courseId)
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
                    data.append('removedSections', JSON.stringify(this.removedSections.map(section=>{
                        return {
                            id: section.id
                        }
                    })))
                    data.append('editedSections', JSON.stringify(this.editedSections))
                    
                    response = await this['dashboard/editCourse'](data)
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

                    response = await this['dashboard/createCourse'](data)
                }

                this.loading = false
                if (response.status) {
                    let action = this.edit ? 'edited' : 'created'
                    this.alertSuccess = true
                    this.alertMessage = `${this.data.name} was successfully ${action}`
                    if (this.edit) {
                        if (this.computedCheckMain) this.$emit('courseSuccessfullyEdited', response.courseResource)
                    } else {
                        this.clearData()
                    }
                    bus.$emit('updateCourse',response.course)
                } else {
                    this.issueDangerAlertForResponse(response.response)
                    console.log('response :>> ', response);
                }
            },
        },
        issueDangerAlertForResponse(response) {
            this.alertDanger = true
            if (response?.data?.message) {
                this.alertMessage = response?.data?.message
            }  else {
                this.alertMessage = `course ${this.edit ? 'editing' : 'creation'} was unsuccessful`
            }
        },
    }
</script>

<style lang="scss" scoped>
    .modal-wrapper{
        z-index: 10005;
    }

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
            border-radius: 0;
        }

        .add-section{
            width: 90%;
            margin: 10px auto;
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

        .search-input{
            border: none;
            border-bottom: 2px solid $color-primary;
            background: white;
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