export default {
    data() {
        return {
            smallModalDelete: false,
            smallModalInfo: false,
            smallModalAlerting: false,
            showSmallModal: false,
            smallModalMessage: '',
            smallModalData: null,
        }
    },
    watch: {
        showSmallModal(newValue) {
            if (! newValue) {
                return
            }

            this.clearSmallModal(false)
        }
    },
    methods: {
        issueSmallModalDeletionMessage() {
            this.smallModalMessage = `are you sure you want to delete this ${this.computedItem?.item}?`
            this.smallModalInfo = false
            this.smallModalDelete = true
            this.showSmallModal = true
        },
        clearSmallModal(immediate = true) {
            setTimeout(() => {
                this.showSmallModal = false,
                this.smallModalInfo = false
                this.smallModalDelete = false
                this.smallModalAlerting = false
                this.smallModalData = null
                this.smallModalMessage = ''
            }, immediate ? 0 : 4000);
        },
        issueCustomMessage(data) {
            this.smallModalInfo= data.type === 'info' ? true : false
            this.smallModalDelete= data.type === 'delete' ? true : false
            this.smallModalData = data.data
            this.smallModalMessage = data.message
            this.showSmallModal = true
        },
        issueSmallModalInfoMessage(data) {
            this.smallModalDelete= false
            this.smallModalInfo= true
            this.smallModalData = data.data
            this.smallModalMessage = data.message
            this.showSmallModal = true
        }
    },
}