export default {
    props: {
        maxTime: {
            type: Object,
            default() {
                return {
                    video: '00:00:30',
                    audio: '00:00:30',
                }
            }
        },
        minTime: {
            type: Object,
            default() {
                return {
                    video: '00:00:01',
                    audio: '00:00:01',
                }
            }
        },
        maxSize: {
            type: Object,
            default() {
                return {
                    video: 1 * 1024 * 1024,
                    audio: 0.5 * 1024 * 1024,
                }
            }
        },
    },
    data() {
        return {
            
        }
    },
    methods: {
        getFormattedDuration(duration) {
            if (! duration) {
                return
            }

            duration = parseFloat(duration.toFixed(2))
            
            let hours = Math.floor(duration / 3600)
            let minites = Math.floor((duration - (hours * 3600)) / 60)
            let seconds = duration - (hours * 3600 + minites * 60)

            seconds = seconds.toFixed(0)

            return `${this.formatTimeUnit(hours)}:${this.formatTimeUnit(minites)}:${this.formatTimeUnit(seconds)}`
        },
        hasAppropriateSize(file, fileType) {

            return file.size <= this.maxSize[fileType]
        },
        hasAppropriateDuration(duration, fileType) {
            console.log(duration, duration < this.maxTime[fileType]);
            return duration < this.maxTime[fileType]
        },
        formatTimeUnit(unit) {
            return unit < 10 ? `0${unit}` : unit
        },
        issueUploadedFileDangerAlert({ alertType, message, file }) {
            let fileType = this.getUploadedFileType(file)

            if (! fileType) {
                return
            }

            if (alertType === 'fileSize') {
                message = `the size of the ${fileType} should be less than ${this.maxSize[fileType]}`    
            }

            if (alertType === 'duration') {
                message = `the duration of the ${fileType} should be between ${this.maxTime[fileType]} && ${this.minTime[fileType]}`    
            }

            if ('issueDangerAlert' in this) {
                this.issueDangerAlert({message})
            }
        },
        getUploadedFileType(file) {

            if (file?.type.match('audio/*')?.length) {
                return 'audio'
            }

            if (file?.type.match('video/*')?.length) {
                return 'video'
            }

            return null
        },
    },
}