<template>
    <div class="message-badge-wrapper"
        :class="{messageBadgeRight: computedOwner}"
    >
        <div class="created-at">
            {{messageCreatedAt(message.created_at)}}
        </div>
        <div class="message-section" 
            v-if="message.message"
        >
            {{message.message}}
        </div>
        <div class="media-section" v-if="computedMedia">
            <div class="images-section" v-if="computedImages">
                <img :src="image.url" v-for="(image, index) in computedImages" :key="index">
            </div>
            <div class="video-section" v-if="computedVideos">
                <video :src="video.url" 
                    v-for="(video, index) in computedVideos" :key="index"
                    controls
                ></video>
            </div>
            <div class="audio-section" v-if="computedAudios">
                <audio :src="image.url" 
                    v-for="(audio, index) in computedAudios" :key="index"
                    controls
                ></audio>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { dates } from '../../services/helpers'
    export default {
        props: {
            message: {
                type: Object,
                default(){
                    return {}
                }
            },
        },
        computed: {
            ...mapGetters(['getUser']),
            computedImages(){
                return this.message.images && this.message.images.length ? 
                    this.message.images : null
            },
            computedVideos(){
                return this.message.videos && this.message.videos.length ? this.message.videos : null
            },
            computedAudios(){
                return this.message.audios && this.message.audios.length ? this.message.audios : null
            },
            computedFiles(){
                return this.message.files && this.message.files.length ? this.message.files : null
            },
            computedMedia(){
                return this.computedImages || this.computedVideos || this.computedAudios || this.computedFiles
            },
            computedOwner(){
                return this.message.from_user_id === this.getUser.id ? true : false
            },
        },
        methods: {
            messageCreatedAt(data) {
                return dates.createdAt(data)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .message-badge-wrapper{
        width: 100%;
        font-size: 14px;

        .created-at{
            font-size: 9px;
            color: gray;
            text-align: start;
            margin-bottom: 3px;
        }

        .message-section{
            max-width: 60%;
            border-radius: 20px;
            background: cornsilk;
            padding: 5px 10px;
            text-align: start;
            width: fit-content;
        }

        .media-section{

            img,
            video{
                object-fit: contain;
                width: 200px;
                height: 150px;
            }

            .images-section,
            .videos-section,
            .audios-section,
            .files-section{
                width: 100%;
                text-align: start;
                margin-top: 5px;
            }

            .audio-section{
                max-width: 200px;
            }
        }
    }

    .messageBadgeRight{

        .created-at{
            text-align: end;
        }

        .message-section{
            background: cornsilk;
            text-align: end;
            margin: 0 0 0 auto;
        }

        .media-section{

            img,
            video{
                object-fit: contain;
                width: 200px;
                height: 150px;
            }

            .images-section,
            .videos-section,
            .audios-section,
            .files-section{
                width: 100%;
                text-align: end;
            }

            .audio-section{
                max-width: 200px;
            }
        }
    }
</style>