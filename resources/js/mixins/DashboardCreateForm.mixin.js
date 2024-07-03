import { default as _ } from 'lodash';
import SearchInput from '../components/SearchInput.vue';
export default {
    components: {
        SearchInput,
    },
    props: {
        show: {
            type: Boolean,
            default: true
        },
        edit: {
            type: Boolean,
            default: false
        },
        mainSection: {
            type: String,
            default: ''
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
            data: {
                name: '',
                title: '',
                ageGroup: '',
                free: false,
                intro: false,
                paymentType: 'free',
                description: '',
                grade: {},
                owner: {name: ''},
                items: [], //courses and subjects 
                mainItems: [],
                removedItems: [],
                maximum: '',
                structure: '',
                state: '',
                facilitate: false,
                discussion: false,
                discussionData: {
                    title: '',
                    type: '',
                    preamble: '',
                    allowed: '',
                    restricted: false,
                },
                attachmentType: '',
                attachments: [],
                mainAttachments: [],
                removedAttachments: [],
                mainPaymentData: [],
                removedPaymentData: [],
                paymentData: null, //but an array
                hasDiscussion: false,
            },
            discussionFiles: [], //for discussion
            loading: false,
            title: '',
            buttonText: 'create',
            alertMessage: '',
            alertDanger: false,
            alertSuccess: false,
            alertLengthy: false,
            hasOwnership: false,
            paymentType: 'subscription and one-time',
            searchItemsText: '',
            //specific items in this case classes
            specificItemLoading: false,
            specificItemDetailsNextPage: 0,
            specificItemDetails: [], //this is for specific item of an account like class details
        }
    },
    watch: {
        edit: {
            immediate: true,
            handler(newValue){
                if (newValue && this.editable) {
                    this.setData(this.editable)
                }
            }
        },
        searchItemsText(newValue) {
            this.debouncedSearchItems()
        }
    },
    computed: {
        computedCreator() {
            return {
                name: this['dashboard/getAccountDetails'].name,
                hasFreeResources: this['dashboard/getAccountDetails'].hasFreeResources,
                account: this.computedAccount.account,
                accountId: this.computedAccount.accountId,
            }
        },
        computedPossibleOwners() {
            if (this.computedAccount.account === 'facilitator' ||
                this.computedAccount.account === 'professional') {
                let a = []
                a.push(this.computedCreator)
                a.push(...this['dashboard/getAccountDetails'].schools.map(school=>{
                    return {
                        name: school.name,
                        account: 'school',
                        accountId: school.id,
                        hasFreeResources: school.hasFreeResources
                    }
                }))
                return a
            }
            return []
        },
        computedAccount(){
            return this["dashboard/getCurrentAccount"]
        },
        computedPayment(){
            if (this.computedAccount.account === 'school' ||
                this.computedAccount.account === 'professional' ||
                this.computedAccount.account === 'facilitator' ||
                this.data.owner.account === 'school' ||
                this.data.owner.account === 'professional' ||
                this.data.owner.account === 'facilitator') {
                return true
            } else {
                return false
            }
        },
        computedAttachment(){
            if (this.data.owner.account === 'school') {
                return ['programs','grades','courses']
            }
            return ['programs','grades','courses']
        },
        computedSpecificItems() {
            return this.specificItemDetails
        },
        computedShowOwnership() {
            return this.computedPossibleOwners.length > 1 && !this.edit
        },
        computedShowGetMore() {
            return this.specificItemDetailsNextPage && this.specificItemDetailsNextPage > 1 &&
                !this.specificItemLoading
        },
        computedShowPayment() {
            return this.computedPayment
            // return !this.edit ? this.computedPayment && !edit : !this.data.mainPaymentData.length
        },
        computedCheckMain() {
            return this.mainSection && this.mainSection.length ? true : false
        },
        computedShowDiscussion() {
            return !this.edit ? true : this.data.hasDiscussion ? true : false
        },
    },
    methods: {
        checkDiscussion(data) {
            if ((data.discussions.constructor === Array && data.discussions.length > 0) ||
                data.discussions > 0) {
                this.data.hasDiscussion = true
            } else {
                this.data.hasDiscussion = false
            } 
        },
        initiateGetItems() {
            this.specificItemDetails = []
            this.specificItemDetailsNextPage = 1
            this.getSpecificAccountItem()
        },
        debouncedSearchItems: _.debounce(
            function() {
                this.initiateGetItems()
            }, 200
        ),
        issueDangerAlertForResponse(response) {
            this.alertDanger = true
            if (response?.data?.message) {
                this.alertMessage = response?.data?.message
            }  else {
                this.alertMessage = `${this.edit ? 'editing' : 'creation'} was unsuccessful`
            }
        },
        error(data) {
            this.alertDanger = true
            this.alertLengthy = data.lengthy
            this.alertMessage = data.message
        },
        getSearchItemsText(search) {
            this.searchItemsText = search
        },
        stateSelection(data){
            this.data.state = data
        },
        clearAlert(){
            this.alertMessage = ''
            this.alertDanger = false
            this.alertSuccess = false
        },
        getDiscussionData(data) {
            this.data.discussionData.title = data.title
            this.data.discussionData.preamble = data.preamble
            this.data.discussionData.type = data.type
            this.data.discussionData.restricted = data.restricted
            this.data.discussionData.allowed = data.allowed
            this.discussionFiles = data.files
        },
        closeDiscussionModal() {
            this.data.discussion = false
        },
        async clearData(){
            this.data.name = ''
            this.data.paymentType = 'free'
            this.data.description = ''
            // this.data.owner = {name: ''}
            this.data.items = []
            this.data.removedItems = []
            this.data.mainItems = []
            this.data.attachments = []
            this.data.mainAttachments = []
            this.data.removedAttachments = []
            this.data.state = ''
            this.data.attachmentType = ''
            this.data.paymentData = null
            this.data.mainPaymentData = []
            this.data.removedPaymentData = []
            this.data.facilitate = false
            this.clearDiscussionData()
            this.discussionFiles = []
            this.grades = []
            this.grade = {}
            this.data.discussion = false
            this.hasMaxLearners = false
            this.buttonText = 'create'
            this.specificItemLoading = false
            if (this.title.includes('lesson')) {
                this.data.title = ''
                this.data.ageGroup = ''
                this.data.intro = false
                this.data.free = false
                this.links = []
                this.errorTitle = false
                this.errorFile = false
            }
            if (this.data.account) {
                this.specificItemDetails = []
                this.specificItemDetailsNextPage = 0
            }
            this.$forceUpdate()
        },
        //attachments
        attachmentSelected(data) {
            let index = this.findAttachmentIndex(data)
            if (index === -1) {
                this.data.attachments.push(data)
            }
        },
        findAttachmentIndex(data,type) {
            let attachments = []
            if (type === 'main') {
                attachments.push(...this.data.mainAttachments)
            } else if (type === 'removed') {
                attachments.push(...this.data.removedAttachments)
            } else {
                attachments.push(...this.data.attachments)
                attachments.push(...this.data.mainAttachments)
                attachments.push(...this.data.removedAttachments)
            }
            return attachments.findIndex(attachment=>{
                return attachment.data.name === data.data.name && 
                    attachment.data.description === data.data.description && 
                    attachment.data.id === data.data.id
            })
        },
        removeAttachment(data,type) {
            let index = this.findAttachmentIndex(data,type)
            if (index > -1) {
                if (type === 'main') {
                    this.spliceMainAttachment(index,data)
                } else if (type === 'removed') {
                    this.spliceRemovedAttachment(index,data)
                } else {
                    this.data.attachments.splice(index,1)
                }
            }
        },
        spliceMainAttachment(index,data) {
            this.data.mainAttachments.splice(index,1)
            this.data.removedAttachments.push(data)
        },
        spliceRemovedAttachment(index,data) {
            this.data.removedAttachments.splice(index,1)
            this.data.mainAttachments.push(data)
        },
        //payment
        getPaymentType(data){
            if (this.data.mainPaymentData.length &&
                this.data.mainPaymentData[0].type !== data.type) {
                this.data.removedPaymentData.push(...this.data.mainPaymentData)
                this.data.mainPaymentData = []
            }
            this.data.paymentType = data.type
            this.data.paymentData = data.data
        },
        clickedRemovePayment(data,type) {
            let index = this.findPaymentDataIndex(data,type)
            if (index > -1) {
                if (type === 'main') {
                    this.spliceMainPaymentData(index,data)
                } else if (type === 'removed') {
                    if (this.data.paymentData && this.data.paymentData.length &&
                        this.data.removedPaymentData.length && 
                        this.data.paymentType !== this.data.removedPaymentData[0].type) {
                            return
                        }
                        this.spliceRemovedPaymentData(index,data)
                } else {
                    this.data.paymentData.splice(index,1)
                }
            }
        },
        findPaymentDataIndex(data,type) {
            let payments = []
            if (type === 'main') {
                payments.push(...this.data.mainPaymentData)
            } else if (type === 'removed') {
                payments.push(...this.data.removedPaymentData)
            } else {
                payments.push(...this.data.paymentData)
                payments.push(...this.data.mainPaymentData)
                payments.push(...this.data.removedPaymentData)
            }
            return payments.findIndex(payment=>{
                return payment.name === data.name && 
                    payment.id === data.id
            })
        },
        spliceMainPaymentData(index,data) {
            this.data.mainPaymentData.splice(index,1)
            this.data.removedPaymentData.push(data)
        },
        spliceRemovedPaymentData(index,data) {
            this.data.removedPaymentData.splice(index,1)
            this.data.mainPaymentData.push(data)
        },
        ownerSelection(data){
            this.data.owner = data
            this.data.paymentData = null
            this.data.paymentType = 'free'
        },
        clearDiscussionData(data) {
            this.data.discussionData = {
                    title: '',
                    type: '',
                    preamble: '',
                    allowed: '',
                    restricted: false,
                }
        },
        getDiscussionData(data) {
            this.data.discussionData.title = data.title
            this.data.discussionData.preamble = data.preamble
            this.data.discussionData.type = data.type
            this.data.discussionData.restricted = data.restricted
            this.data.discussionData.allowed = data.allowed
            this.discussionFiles = data.files
        },
        closeDiscussionModal() {
            this.data.discussion = false
        },
    },
    
}