<template>
    <div v-show="file">
        <video 
            id="videocheck"
            class="hidden"
        ></video>
        <audio 
            id="audiocheck"
            class="hidden"
        ></audio>
    </div>
</template>

<script>
    export default {
        props: {
            file: {
                default() {
                    return null
                }
            },
        },
        watch: {
            file: {
                immediate: true,
                handler(newValue) {
                    if (! newValue) {
                        return
                    }

                    if (newValue.type.includes('video')) {
                        this.setUp('video')
                    }

                    if (newValue.type.includes('audio')) {
                        this.setUp('audio')
                    }
                }
            }
        },
        methods: {
            async setUp(type) {
                let fileReader = new FileReader

                this.element = document.getElementById(`${type}check`)

                this.element.addEventListener('loadeddata', await this.trySendDetails())
                this.element.addEventListener('durationchange', this.sendDetails())

                fileReader.onload = (event) => {
                    this.element.src = event.target.result
                }
                
                fileReader.readAsDataURL(this.file)
            },
            sendDetails() {
                this.$emit('sendFileDetails', {
                    duration: this.element.duration,
                    type: 'durationchange'
                })
            },
            async trySendDetails() {
                while (this.element.duration === Infinity) {
                    await new Promise(resolve=> setTimeout(resolve, 1000))
                    this.element.currentTime = 1e101
                }

                this.$emit('sendFileDetails', {
                    duration: this.element.duration,
                    type: 'loadeddata'
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>