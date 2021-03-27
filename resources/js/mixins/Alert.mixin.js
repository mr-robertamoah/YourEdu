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
        setErrorAlert(data) {
            this.alertDanger = true
            this.alertLengthy = data.lengthy
            this.alertMessage = data.message
        },
        responseErrorAlert(response) {
            this.alertDanger = true
            if (response?.data?.message) {
                this.alertMessage = response?.data?.message
            }  else {
                this.alertMessage = `${this.edit ? 'editing' : 'creation'} was unsuccessful 😞`
            }
        },
    },
}