export default {
    props: {
        data: {
            type: Object,
            default(){
                return {}
            }
        },
        hasClose: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        computedDetails() {
            let str = `amount: ${this.data.amount}`
            if (this.data.period) {
                str += ` per ${this.data.period}`
            }
            if (this.data.description && this.data.description.length) {
                str += '\n'
                str += `description: ${this.data.description}`
            }
            return str
        }
    },
}