<template>
    <div class="modal-wrapper" v-if="show" @click.self="disappear">
        <div class="main-modal">
            <div class="close" @click="disappear">
                <font-awesome-icon :icon="['fas','times']"></font-awesome-icon>
            </div>
            <template v-if="!showAlert">
                <div class="main" v-if="main">
                    <div class="media" v-if="computedImage">
                        <img :src="computedImage.url">
                    </div> 
                    <div class="media" v-if="computedVideo">
                        <video controls controlslist="nodownload" 
                            :src="computedVideo.url"></video>
                    </div> 
                    <div class="media" v-if="computedAudio">
                        <audio controls controlslist="nodownload" 
                            :src="computedAudio.url"></audio>
                    </div> 
                </div>
                <div class="media-all" v-if="!main">
                    <template
                        v-if="mediaData.mediaType.includes('image')"
                    >
                        <div class="media" 
                            v-for="media in mediaData.media"
                            :key="media.id"
                        >
                            <img :src="media.url">
                            <div class="actions" v-if="computedOwner">
                                <div class="show" title="make public"
                                    @click="clickedIcon(media.id,'change')"
                                    v-if="media.state === 'PRIVATE'"
                                >
                                    <font-awesome-icon :icon="['fa','eye']"></font-awesome-icon>
                                </div>
                                <div class="show" title="make private"
                                    @click="clickedIcon(media.id, 'change')"
                                    v-if="media.state === 'PUBLIC'"
                                >
                                    <font-awesome-icon :icon="['fa','eye-slash']"></font-awesome-icon>
                                </div>
                                <div class="delete" title="delete"
                                    @click="clickedIcon(media.id,'delete media')"
                                >
                                    <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template
                        v-if="mediaData.mediaType.includes('video')"
                    >
                        <div class="media" 
                            v-for="media in mediaData.media"
                            :key="media.id"
                        >
                            <video :src="media.url" controls controlslist="nodownload"
                            ></video>
                            <div class="actions" v-if="computedOwner">
                                <div class="show" title="make public"
                                    @click="clickedIcon(media.id,'change')"
                                    v-if="media.state === 'PRIVATE'"
                                >
                                    <font-awesome-icon :icon="['fa','eye']"></font-awesome-icon>
                                </div>
                                <div class="show" title="make private"
                                    @click="clickedIcon(media.id, 'change')"
                                    v-if="media.state === 'PUBLIC'"
                                >
                                    <font-awesome-icon :icon="['fa','eye-slash']"></font-awesome-icon>
                                </div>
                                <div class="delete" title="delete"
                                    @click="clickedIcon(media.id,'delete media')"
                                >
                                    <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template
                        v-if="mediaData.mediaType.includes('audio')"
                    >
                        <div class="media" 
                            v-for="media in mediaData.media"
                            :key="media.id"
                        >
                            <audio :src="media.url" controls controlslist="nodownload"
                            ></audio>
                            <div class="actions" v-if="computedOwner">
                                <div class="show" title="make public"
                                    @click="clickedIcon(media.id,'change')"
                                    v-if="media.state === 'PRIVATE'"
                                >
                                    <font-awesome-icon :icon="['fa','eye']"></font-awesome-icon>
                                </div>
                                <div class="show" title="make private"
                                    @click="clickedIcon(media.id, 'change')"
                                    v-if="media.state === 'PUBLIC'"
                                >
                                    <font-awesome-icon :icon="['fa','eye-slash']"></font-awesome-icon>
                                </div>
                                <div class="delete" title="delete"
                                    @click="clickedIcon(media.id,'delete media')"
                                >
                                    <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="show-more" 
                    v-if="!loading && showMore"
                    @click="showMoreMedia"
                >
                    show more
                </div>
                <div class="loading-errors" v-if="loading">
                    <slot name="loading"></slot>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import FadeRight from "./transitions/FadeRight.vue";
import { mapGetters } from 'vuex';
    export default {
        props: {
            show: {
                type: Boolean,
                default: true,
            },
            showMore: {
                type: Boolean,
                default: false,
            },
            mediaData: {
                type: Object,
                default: null,
            },
            main: {
                type: Boolean,
                default: true,
            },
            justUrl: { //to allow the media to be url and not the media object
                type: Boolean,
                default: false,
            },
            loading: {
                type: Boolean,
                default: false,
            },
            showMoreText: {
                type: String,
                default: ''
            },
            url: { //for url of the media
                type: String,
                default: ''
            },
            urlType: { //for url of the media
                type: String,
                default: ''
            },
            alertMessage: {
                type: String,
                default: ''
            },
            alertError: {
                type: Boolean,
                default: false
            },
            alertSuccess: {
                type: Boolean,
                default: false
            },
        },
        components: {
            FadeRight,
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.$emit('mainModalAppear')
                    }
                }
            },
            alertMessage: {
                immediate:true,
                handler(newValue){
                    if(this.alertMessage.length > 0){
                        setTimeout(() => {
                            this.$emit('clearAlert') 
                        }, 3000);
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['getUser','profile/getProfile']),
            showAlert() {
                if(this.alertMessage.length > 0){
                    return true
                } else {
                    return false
                }
            },
            computedImage(){
                return this.mediaData && this.mediaData.mediaType.includes('image')
                    ? this.mediaData.media : 
                    this.justUrl && this.urlType.includes('image') ? {url: this.url} : null
            },
            computedVideo(){
                return this.mediaData && this.mediaData.mediaType.includes('video')
                    ? this.mediaData.media : 
                    this.justUrl && this.urlType.includes('video') ? {url: this.url} : null
            },
            computedAudio(){
                return this.mediaData && this.mediaData.mediaType.includes('audio')
                    ? this.mediaData.media : 
                    this.justUrl && this.urlType.includes('audio') ? {url: this.url} : null
            },
            computedOwner(){
                if (this.getUser && this['profile/getProfile']) {
                    
                    return this.getUser.id === this['profile/getProfile'].user_id ? true : false
                }
                return false
            },
        },
        methods: {
            disappear() {
                this.$emit('mainModalDisappear')
            },
            showMoreMedia(){
                this.$emit('showMoreMedia')
            },
            clickedIcon(mediaId,type){
                this.$emit('clickedMediaIcon',{mediaId,type})
            },
        },
    }
</script>

<style lang="scss" scoped>


$wrapper-background: rgba(102, 51, 153, .2);
$modal-background: aliceblue;
$modal-width: 60%;
$modal-height: 70vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .modal-wrapper{
        position: fixed;
        background-color: $wrapper-background;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        padding: auto;
        z-index: 10000;
        overflow: scroll;

        .main-modal{
            background-color: $modal-background;
            width: $modal-width;
            height: $modal-height;
            position: relative;
            top: $modal-margin-height;
            left: $modal-margin-width;
            border-radius: 10px;
            box-shadow: 1px 1px 1px rgba(105, 105, 105,.6), 
                -1px -1px 1px rgba(105, 105, 105,.6);
            display: block;
            overflow-y: scroll;
            position: relative;
            padding: 10px;
            overflow-y: auto;
            
            .close{
                position: fixed;
                width: 20px;
                top: 15%;
                right: 21%;
                margin: 10px 10px 0 0;
                color: rgba(105, 105, 105,.8);
                cursor: pointer;
                font-size: 20px;

                &:hover{
                    color: rgba(255, 0, 0, 0.603);
                }
            }

            .loading-errors{
                width: 80%;
                position: relative;
                display: block;
                margin: 0 auto 20px;
                padding: 30px 0 0;
                text-align: center;
            }

            .main{
                padding: 10px;
                height: 100%;
                width: 100%;

                .media{
                    width: 90%;
                    margin: auto;
                    height: 100%;

                    img,
                    video{
                        width: 100%;
                        height: 100%;
                        object-fit: contain;
                        object-position: center;
                    }
                    
                    audio{
                        width: 100%;
                    }
                }
            }

            .media-all{
                display: flex;
                justify-content: flex-start;
                align-items: flex-start;
                flex-wrap: wrap;
                margin-top: 20px;

                .media{ 
                    width: 45%;
                    max-height: 150px;
                    overflow-y: hidden;
                    display: flex;
                    margin-bottom: 10px;

                    img,
                    video,
                    audio{
                        width: 85%;
                    }

                    .actions{
                        width: 10%;
                        padding: 5px;
                        font-size: 14px;

                        div{
                            cursor: pointer;
                        }

                        .show{
                            margin-bottom: 10px;
                        }
                    }
                }
            }

            .alert{
                width: 100%;
                text-align: center;
                padding: 10px;
                background-color: rgba(8, 170, 245, 0.486);
                color: rgba(8, 170, 245,1);
                border: 2px solid rgba(8, 170, 245,1);
                position: absolute;
                top: 45%;
            }

            .alertError{
                color: rgba(201, 6, 6, 0.9);
                background-color: rgba(201, 6, 6, 0.4);
                border: 2px solid rgba(201, 6, 6, 0.4);
            }

            .alertSuccess{
                color: rgba(0, 128, 0, 0.9);
                background-color: rgba(0, 128, 0, 0.4);
                border: 2px solid rgba(0, 128, 0, 0.4);
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
$modal-width: 70%;
$modal-height: 80vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;

            .close{
                top: 10%;
                right: 16%;
            }
        }
    }
}

@media screen and (max-width:800px){
$modal-width: 90%;
$modal-height: 90vh;
$modal-margin-width: math.div(100% - $modal-width, 2);
$modal-margin-height: math.div(100vh - $modal-height, 2);

    .modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;

            .close{
                top: 5%;
                right: 6%;
            }
        }
    }
}

@media screen and (max-width:500px){
$modal-width: 100%;
$modal-height: 100vh;
$modal-margin-width: 0;
$modal-margin-height: 0;

    .modal-wrapper{

        .main-modal{
            width: $modal-width;
            height: $modal-height;
            top: $modal-margin-height;
            left: $modal-margin-width;

            .close{
                top: 0;
                right: 1%;
            }
        }
    }
}
</style>