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
            
            if (this.data.feeables?.length) {
                str += '\n'
                let type = this.data.feeables[0].type.includes('ection') ?
                    'academic year sections' : 'academic years'
                str += `${this.data.feeables.length} ${type}`
            }
            return str
        }
    },
    methods: {
        clickedRemoveData() {
            this.$emit('clickedRemoveData',this.data)
        },
        clickedData() {
            this.$emit('clickedData',this.data)
        },
    },
}