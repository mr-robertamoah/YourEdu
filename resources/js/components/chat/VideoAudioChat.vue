<template>
    <addon-modal
        :show="true"
        :fullHeight="true"
        :hasClose="false"
    >
        <template slot="data">
            <div class="video-audio-chat-wrapper">
                <div class="media-section" :class="{reduced: showChat}">
                    <chat-media
                        type="other"
                        :urlObject="stream"
                        mediaType="video"
                    ></chat-media>
                    <chat-media
                        type="mine"
                        :urlObject="stream"
                        mediaType="video"
                    ></chat-media>
                    <div class="buttons-section">
                        <div class="display-button" 
                            @click="clickedButton('display')"
                        >
                            <font-awesome-icon :icon="['fa','ellipsis-v']"></font-awesome-icon>
                        </div>
                        <div class="buttons" v-if="displayButtons">
                            <div class="button" 
                                title="do not let the other user see/hear me"
                                @click="clickedButton('pause')"
                                v-if="!callState.pause"
                            >
                                <font-awesome-icon 
                                    :icon="['fa','pause']"
                                ></font-awesome-icon>
                            </div>
                            <div class="button" 
                                title="let other user see/hear me"
                                @click="clickedButton('pause')"
                                v-if="callState.pause"
                            >
                                <font-awesome-icon 
                                    :icon="['fa','play']"
                                ></font-awesome-icon>
                            </div>
                            <div class="button" 
                                title="mute other user"
                                @click="clickedButton('mute')"
                                v-if="!callState.mute"
                            >
                                <font-awesome-icon 
                                    :icon="['fa','microphone-alt-slash']"
                                ></font-awesome-icon>
                            </div>
                            <div class="button" 
                                title="unmute other user"
                                @click="clickedButton('mute')"
                                v-if="callState.mute"
                            >
                                <font-awesome-icon 
                                    :icon="['fa','microphone']"
                                ></font-awesome-icon>
                            </div>
                            <div class="button close" 
                                title="end call"
                                @click="clickedButton('end')"
                            >
                                <font-awesome-icon 
                                    :icon="['fa','times']"
                                ></font-awesome-icon>
                            </div>
                        </div>
                    </div>
                    <div class="switcher"
                        @click="clickedButton('switcher')"
                    >
                        <font-awesome-icon 
                            :icon="['fa','chevron-down']"
                            v-if="showChat"
                        ></font-awesome-icon>
                        <font-awesome-icon 
                            :icon="['fa','chevron-up']"
                            v-if="!showChat"
                        ></font-awesome-icon>
                    </div>
                </div>
                <div class="chat-section" v-if="showChat">
                    <div class="chat-area">
                        <template
                            v-if="chats.length"
                        >
                            <message-badge

                            ></message-badge>
                        </template>
                        <div class="no-data">
                            no chats yet
                        </div>
                    </div>
                    <discussion-textarea
                        class="discussion-textarea"
                        :mediaChat="true"
                    ></discussion-textarea>
                </div>
            </div>
        </template>
    </addon-modal>
</template>

<script>
import VideoAudioChatMedia from './VideoAudioChatMedia.vue';
import DiscussionTextarea from '../DiscussionTextarea.vue';
import MessageBadge from './MessageBadge.vue';
    export default {
        components: {
            MessageBadge,
            DiscussionTextarea,
            'chat-media': VideoAudioChatMedia,
        },
        data() {
            return {
                mediaConstraints: {
                    audio: true,
                    video: true
                },
                callState: {
                    pause: false,
                    mute: false
                },
                stream: null,
                displayButtons: false,
                showChat: false,
                chats: []
            }
        },
        watch: {
            displayButtons(newValue) {
                if (newValue) {
                    setTimeout(() => {
                        this.displayButtons = false
                    }, 5000);
                }
            },
            callState: {
                deep: true,
                handler(newValue, oldValue){
                    if (newValue.pause !== oldValue.pause) {
                        
                    }
                    if (newValue.mute !== oldValue.mute) {
                        
                    }
                }
            }
        },
        created () {
            this.getUserMedia();
        },
        computed: {
            computedStreamUrl() {
                return this.stream
            }
        },
        methods: {
            getUserMedia() {
                navigator.mediaDevices.getUserMedia(this.mediaConstraints)
                    .then((stream) => {
                        console.log(stream);
                        this.stream = stream
                    })
                    .catch((e) => {
                        console.log(e);
                    })
            },
            clickedButton(data){
                if (data === 'display') {
                    this.displayButtons = !this.displayButtons
                } else if (data === 'pause') {
                    this.callState.pause = !this.callState.pause
                } else if (data === 'mute') {
                    this.callState.mute = !this.callState.mute
                } else if (data === 'switcher') {
                    this.showChat = !this.showChat
                } else if (data === 'end') {
                    this.endCall()
                }
            },
            endCall(){

            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin button() {
    padding: 5px;
    font-size: 20px;
    color: white;
    cursor: pointer;
}

    .video-audio-chat-wrapper{
        height: 100%;
        overflow: hidden;
        
        .media-section{
            height: inherit;
            position: relative;
        }

        .media-section.reduced{
            height: 50%;
        }

        .chat-section{
            padding: 10px;
            display: flex;
            flex-direction: column;
            height: 50%;
            align-items: center;
            flex-wrap: nowrap;

            .chat-area{
                height: 100%;
                width: 100%;
                background: azure;
                border-radius: 5px;
                overflow-y: auto;

                .no-data{
                    width: inherit;
                    height: inherit;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    color: gray;
                }
            }

            .discussion-textarea{
                width: 100%;
            }
        }

        .buttons-section{
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px;
            text-align: center;

            .display-button{
                @include button();
            }

            .buttons{
                padding: 5px;
                border: 1px solid gray;
                text-align: center;

                .button{
                    @include button();
                    font-size: 14px;
                    margin: 5px 0;
                }

                .button.close{
                    color: red;
                    float: none;
                    opacity: 1;
                }
            }
        }

        .switcher{
            position: absolute;
            bottom: 10px;
            left: 20px;
            @include button();
        }
    }
</style>