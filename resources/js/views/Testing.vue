<template>
    <video-audio-chat
    ></video-audio-chat>
</template>

<script>
import VideoAudioChat from '../components/chat/VideoAudioChat'

    export default {
        data() {
            return {
                show: false,
                value: ''
            }
        },
        components: {
            VideoAudioChat,
        },
        methods: {
            clickedTesting(){
                this.show = true
            },
        },
    }
</script>

<style lang="scss" scoped>
    .testing{
        width: 100%;
        height: 100vh;
    }
</style>