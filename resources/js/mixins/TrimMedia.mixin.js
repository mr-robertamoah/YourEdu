export default {
    data() {
        return {
            trimMediaData: {
                show: false,
                file: null
            },
        }
    },
    methods: {
        clearTrimMediaData() {
            this.trimMediaData.show = false
            this.trimMediaData.file = null
        }
    },
}