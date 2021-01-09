export default {
    props: {
        show: {
            type: Boolean,
            default: true
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
            data: {
                name: '',
                feeable: '',
                feeableId: '',
                ageGroup: '',
                type: 'free',
                description: '',
                grade: {},
                owner: {name: ''},
                subjects: [],
                classes: [],
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
                paymentData: null
            },
            discussionFiles: [], //for discussion
            loading: false,
            title: '',
            buttonText: 'create',
            alertMessage: '',
            alertDanger: false,
            alertSuccess: false,
            hasOwnership: false,
            paymentType: 'subscription and one-time',
            //specific items in this case classes
            specificItem: '',
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
    },
    computed: {
        computedCreator() {
            return {
                name: this['dashboard/getAccountDetails'].name,
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
                        name: school.company_name,
                        account: 'school',
                        accountId: school.id,
                    }
                }))
                return a
            }
            return []
        },
        computedAdmin(){
            if (this.computedAccount.account === 'school') {
                let index = this["dashboard/getAccountDetails"].admins.findIndex(admin=>{
                    return admin.userId === this.getUser.id
                })
                if (index > -1) {
                    return this["dashboard/getAccountDetails"].admins[index]
                }
            }
            return null
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
            return this.computedPossibleOwners.length > 1 && !this.edit && !this.hasOwnership
        },
    },
    methods: {
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
        clearData(){
            this.data.name = ''
            this.data.type = 'free'
            this.data.description = ''
            this.data.owner = {name: ''}
            this.data.classes = []
            this.data.attachments = []
            this.data.mainAttachments = []
            this.data.removedAttachments = []
            this.data.state = ''
            this.data.attachmentType = ''
            this.data.paymentData = null
            this.data.facilitate = false
            this.data.discussionData = {
                    title: '',
                    type: '',
                    preamble: '',
                    allowed: '',
                    restricted: false,
                }
            this.discussionFiles = []
            this.data.discussion = false
            this.data.feeable = ''
            this.data.feeableId = ''
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
        clickedRemovePayment(data,type) {
            let index = this.findPaymentDataIndex(data,type)
            if (index > -1) {
                if (type === 'main') {
                    this.spliceMainPaymentData(index,data)
                } else if (type === 'removed') {
                    this.spliceRemovedPaymentData(index,data)
                } else {
                    this.data.paymentData.splice(index,1)
                }
            }
        },
        findPaymentDataIndex() {
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
                return payment.data.name === data.data.name && 
                    payment.data.id === data.data.id
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
    },
    
}