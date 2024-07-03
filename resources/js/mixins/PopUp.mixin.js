import PopUp from '../components/specials/PopUp.vue'
export default {
    components: {
        PopUp,
    },
    data() {
        return {
            showPopUp: false,
            popUpMessage: '',
            popUpHasResponses: false,
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