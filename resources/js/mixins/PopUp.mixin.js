import PopUp from '../components/specials/PopUp'
export default {
    components: {
        PopUp,
    },
    data() {
        return {
            showPopUp: false,
        }
    },
    methods: {
        askIfTrueOrFalse() {
            this.showPopUp = true
        },
        popUpResponse(data) {
            if (data === 'yes') {
                this.$emit('itIsTrueOrFalse')
            }
        },
        closePopUp() {
            this.showPopUp = false
        },
    },
}