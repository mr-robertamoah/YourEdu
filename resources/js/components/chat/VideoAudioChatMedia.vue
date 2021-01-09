<template>
    <div class="video-chat-media-wrapper" :class="[type === 'other' ? 'other' : 'mine']">
        <div class="media">
            <video 
                v-if="mediaType === 'video'"
                autoplay
                muted
                ref="video"
            ></video>
            <audio 
                :src="url"
                v-if="mediaType === 'audio'"
                autoplay
                :muted="type === 'mine'"
                ref="audio"
            ></audio>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: ''
            },
            mediaType: {
                type: String,
                default: ''
            },
            urlObject: {
                type: MediaStream,
                default(){
                    return null
                }
            },
        },
        watch: {
            urlObject(newValue) {
                if (newValue) {
                    if (this.mediaType === 'audio') {
                        this.$refs.audio.srcObject = newValue
                    } else if (this.mediaType === 'video') {
                        this.$refs.video.srcObject = newValue
                    }
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

    .video-chat-media-wrapper{
        background: aquamarine;

        .media{
            height: 100%;
            width: 100%;
            background: gray;

            video{
                object-fit: contain;
                height: inherit;
                width: inherit;
            }
        }
    }

    .video-chat-media-wrapper.other{
        height: 100%;
        
        .media{
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }

    .video-chat-media-wrapper.mine{
        width: 120px;
        height: 120px;
        border-radius: 50%;
        padding: 5px;
        position: absolute;
        bottom: 10px;
        right: 10px;

        .media{
            border-radius: inherit;

            video{
                border-radius: inherit;
            }
        }
    }
</style>