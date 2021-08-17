export default {
    computed: {
        computedFiles() {
            let files = [] 

            if (this.computedItemable?.files) {
                files.push(...this.computedItemable.files)
            }

            if (this.computedItemable?.videos) {
                files.push(...this.computedItemable.videos)
            }

            if (this.computedItemable?.audios) {
                files.push(...this.computedItemable.audios)
            }

            if (this.computedItemable?.images) {
                files.push(...this.computedItemable.images)
            }

            return files
        },
    },
}