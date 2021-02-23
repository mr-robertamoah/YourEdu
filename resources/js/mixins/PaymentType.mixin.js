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
            if (this.data.academicYears) {
                str += '\n' + `${this.data.academicYears.length} academic years`
            }
            if (this.data.sections) {
                str += '\n' + `${this.data.sections.length} academic year sections`
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