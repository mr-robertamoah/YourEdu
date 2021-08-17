import AutoAlert from '../components/AutoAlert.vue';
export default {
    components: {
        AutoAlert,
    },
    data() {
        return {
            alertMessage: '',
            alertDanger: false,
            alertSuccess: false,
            alertLengthy: false,
        }
    },
    methods: {
        clearAlert() {
            this.alertSuccess = false
            this.alertDanger = false
            this.alertLengthy = false
            this.alertMessage = ''
        },
        issueDangerAlert(data) {
            data.type = 'danger'

            this.issueAlert(data)
        },
        issueSuccessAlert(data) {
            data.type = 'success'

            this.issueAlert(data)
        },
        issueAlert(data) {
            this.alertDanger = data.type === 'danger' ? true : false
            this.alertSuccess = data.type === 'success' ? true : false
            this.alertLengthy = data.lengthy
            this.alertMessage = data.message
        },
        issueDangerAlertForResponse(response, message = null) {
            this.alertDanger = true
            
            if (response?.data?.message) {
                this.alertMessage = response.data.message
                return
            }
            
            if (response?.message) {
                this.alertMessage = response.message
                return
            }
            
            if (response?.response?.data?.message) {
                this.alertMessage = response.response.data.message
                return
            }

            if (message) {
                this.alertMessage = message
                return
            }

            if ('edit' in this) {
                this.alertMessage = `${this.edit ? 'editing' : 'creation'} was unsuccessful 😞`
                return
            }

            this.alertMessage = 'oops! something happened 😕. please try again later.'
        },
    },
}