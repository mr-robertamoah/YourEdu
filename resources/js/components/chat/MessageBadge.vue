<template>
    <div class="message-badge-wrapper"
        :class="{messageBadgeRight: computedOwner}"
        @dblclick="clickedShowOptions"
    >
        <div class="deleted" v-if="computedDeletedForMe">
            <div class="icon">
                <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
            </div>
            <div class="text">message deleted</div>
        </div>
        <optional-actions
            :hasEdit="false"
            :hasDelete="computedOwner"
            :hasExtra="true"
            extraText="delete for me"
            :hasAttachment="false"
            :hasSave="false"
            :show="showOptionalActions"
            v-if="showOptionalActions && !computedDeletedForMe"
            @clickedOption="clickedOption"
            class="optional-actions"
        >
            <template slot="extraicon">
                <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
            </template>
        </optional-actions>
        <div class="created-at" v-if="!computedDeletedForMe">
            {{messageCreatedAt(message.created_at)}}
        </div>
        <div class="message-section" 
            v-if="message.message && !computedDeletedForMe"
        >
            {{message.message}}
        </div>
        <div class="media-section" v-if="computedMedia && !computedDeletedForMe">
            <div class="images-section" v-if="computedImages">
                <img :src="image.url" v-for="(image, index) in computedImages" :key="index"
                    @click="clickedMedia(image.url, 'image')"
                >
            </div>
            <div class="videos-section" v-if="computedVideos">
                <video :src="video.url" 
                    v-for="(video, index) in computedVideos" :key="index"
                    controls
                    @click="clickedMedia(video.url, 'video')"
                ></video>
            </div>
            <div class="audios-section" v-if="computedAudios">
                <audio :src="audio.url" 
                    v-for="(audio, index) in computedAudios" :key="index"
                    controls
                    @click="clickedMedia(audio.url, 'audio')"
                ></audio>
            </div>
        </div>
        <div class="chat-item-state" v-if="computedOwner && !computedDeletedForMe">
            {{message.state}}
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { dates } from '../../services/helpers'
import OptionalActions from '../OptionalActions.vue';
    export default {
        components: {
            OptionalActions,
        },
        props: {
            message: {
                type: Object,
                default(){
                    return {}
                }
            },
        },
        data() {
            return {
                showOptionalActions: false
            }
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
                return this.message.fromable.userId === this.getUser.id ? true : false
            },
            computedDeletedForMe(){
                return this.message.userDeletes && this.message.userDeletes.includes(this.getUser.id)
            },
        },
        methods: {
            messageCreatedAt(data) {
                return dates.createdAt(data)
            },
            clickedMedia(url, type){
                this.$emit('clickedMedia',{url,type})
            },
            clickedOption(data){
                this.showOptionalActions = false
                this.$emit('clickedOption',{
                    action: data,
                    type: 'message',
                    message: this.message,
                    conversationId: this.message.conversationId
                })
            },
            clickedShowOptions(){
                this.showOptionalActions = true
                setTimeout(() => {
                    this.showOptionalActions = false
                }, 4000);
            },
        },
    }
</script>

<style lang="scss" scoped>

    .message-badge-wrapper{
        width: 100%;
        font-size: 14px;
        position: relative;

        .deleted{
            width: 100%;
            font-size: 14px;
            padding: 5px;
            display: inline-flex;
            justify-content: flex-start;
            align-items: center;
            color: gray;

            .icon{
                padding: 5px;
                margin-right: 5px;
            }
        }

        .optional-actions{
            left: 0;
            z-index: 1;
        }

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
        
        .chat-item-state{
            position: absolute;
            left: -5px;
            top: 100%;
            font-size: 9px;
            color: gray;
            text-transform: lowercase;
        }
    }

    .messageBadgeRight{

        .deleted{
            justify-content: flex-end;
        }

        .created-at{
            text-align: end;
        }

        .optional-actions{
            right: 0;
            left: unset;
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
        
        .chat-item-state{
            right: -5px;
            left: unset;
        }
    }
</style>