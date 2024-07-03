<template>
    <div class="lesson-preview-wrapper">
        <profile-picture class="profile-picture">
            <template slot="image">
                <img :src="profileUrl" alt="profile picture">
            </template>
        </profile-picture>
        <div class="main-section">
            <div class="title" :class="{titleFull: full}">
                {{computedTitle}} 
            </div>
            <div class="check-description" 
                v-if="computedCheckDescription"
                @click="clickedCheckDescription"
            >check description</div>
            <just-fade>
                <template slot="transition" v-if="showDescription">
                    <div class="description"
                        @click="clickedCheckDescription"
                    >
                        {{computedDescription}}
                    </div>
                </template>
            </just-fade>
            <div class="resources-section">
                <div class="heading">available reources</div>
                <div class="resource-types">
                    <div class="resource-item"
                        :class="{active: resourceType === 'image'}"
                        v-if="computedImageUrl.length"
                        @click="clickedResource('image')"
                    >image</div>
                    <div class="resource-item"
                        :class="{active: resourceType === 'video'}"
                        v-if="computedVideoUrl.length"
                        @click="clickedResource('video')"
                    >video</div>
                    <div class="resource-item"
                        :class="{active: resourceType === 'audio'}"
                        v-if="computedAudioUrl.length"
                        @click="clickedResource('audio')"
                    >audio</div>
                </div>
                <just-fade>
                    <template slot="transition" v-if="showFilePreview">
                        <div class="file-preview" 
                            v-if="showFilePreview"
                            :class="{typeMediaFull:full}"
                        >
                            <img :src="computedImageUrl" 
                                v-if="computedImageUrl.length && resourceType === 'image'"
                                @click="clickedMedia(computedImageUrl,'image')"
                            >
                            <audio :src="computedAudioUrl" controls controlslist='nodownload'
                                v-if="computedAudioUrl.length && resourceType === 'audio'"
                                @click="clickedMedia(computedAudioUrl,'audio')"
                            ></audio>
                            <video :src="computedVideoUrl" controls controlslist='nodownload'
                                v-if="computedVideoUrl.length && resourceType === 'video'"
                                @click="clickedMedia(computedVideoUrl,'video')" 
                            ></video>
                        </div>
                    </template>
                </just-fade>
            </div>
            <div class="age-group" v-if="computedAgeGroup">
                {{computedAgeGroup}}
            </div>
        </div>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture.vue';
import FilePreview from './FilePreview.vue';
    export default {
        props: {
            lesson: {
                type: Object,
                default(){
                    return {}
                }
            },
            full: {
                type: Boolean,
                default: false
            },
            profileUrl: {
                type: String,
                default: ''
            },
        },
        components: {
            FilePreview,
            ProfilePicture,
        },
        data() {
            return {
                showDescription: false,
                showFilePreview: false,
                resourceType: '',
            }
        },
        computed: {
            computedCheckDescription() {
                return !this.showDescription && this.computedDescription.length
            },
            computedImageUrl() {
                return this.lesson.images ?  this.lesson.images[0].url : ''
            },
            computedVideoUrl() {
                return this.lesson.videos ?  this.lesson.videos[0].url : ''
            },
            computedAudioUrl() {
                return this.lesson.audios ?  this.lesson.audios[0].url : ''
            },
            computedAgeGroup() {
                return this.lesson.ageGroup && this.lesson.ageGroup.length ? 
                    `age group: ${this.lesson.ageGroup}` : ''
            },
            computedDescription() {
                return this.lesson.description && this.lesson.description.length ?
                    this.lesson.description : '' 
            },
            computedTitle() {
                return this.lesson.title && this.lesson.title.length ?
                    this.lesson.title : '' 
            },
        },
        methods: {
            clickedCheckDescription() {
                this.showDescription = !this.showDescription
            },
            clickedResource(data){
                if (this.resourceType === data) {
                    this.showFilePreview = !this.showFilePreview
                    this.file = null
                    this.resourceType = ''
                    return 
                }
                this.resourceType = data
                if (data === 'image') {
                    this.file = this.lesson.images[0]
                } else if (data === 'video') {
                    this.file = this.lesson.videos[0]
                } else if (data === 'audio') {
                    this.file = this.lesson.audios[0]
                }
                this.showFilePreview = true
            },
            clickedMedia(url,mediaType){
                this.$emit('clickedMedia',{url,mediaType})
            },
        },
    }
</script>

<style lang="scss" scoped>

    .lesson-preview-wrapper{
        display: flex;
        justify-content: space-between;
        width: 100%;
        flex-wrap: nowrap;
        padding: 5px;
        align-items: center;

        .profile-picture{
            width: 75px;
            height: 75px;
        }

        .main-section{
            min-width: 70%;
            max-width: 85%;

            .title{
                font-weight: 500;
                text-transform: capitalize;
                width: 100%;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                border-bottom: 1px solid gray;
            }

            .titleFull{
                text-align: center;
                text-overflow: unset;
                white-space: unset;
                overflow: unset;
            }

            .check-description{
                font-size: 14px;
                width: fit-content;
                padding: 5px;
                color: gray;
                cursor: pointer;
                margin: 5px 0;
            }

            .description{
                font-size: 14px;
                text-align: justify;
                padding: 5px;
            }

            .resources-section{

                .heading{
                    font-size: 12px;
                    color: gray;
                    text-align: center;
                }

                .resource-types{
                    display: inline-flex;
                    justify-content: space-around;
                    width: 100%;
                    font-size: 14px;
                    margin: 20px 0 10px;

                    .resource-item{
                        padding: 5px;
                        border-radius: 10px;
                        background: gray;
                        color: mintcream;
                        cursor: pointer;

                        &:hover{
                            background: green;
                            transition: all .5s ease;
                        }
                    }

                    .active{
                        background: green;
                        transition: all .5s ease;
                    }
                }

                .file-preview{
                    width: 100%;
                    height: 200px;
                    padding: 10px;

                    img,
                    video{
                        width: 100%;
                        height: 100%;
                        box-shadow: 0 0 2px gray;
                        object-fit: contain;
                        object-position: top;
                    }

                    audio{
                        width: 100%;
                    }
                }
            }

            .age-group{
                text-align: end;
                padding-right: 5px;
                font-size: 12px;
                font-weight: 400;
                font-style: italic;
            }
        }
    }

@media screen and (max-width:1100px) {
    .lesson-preview-wrapper{

        .profile-picture{
            width: 60px;
            height: 60px;
        }
    }
}

@media screen and (max-width:600px) {
    .lesson-preview-wrapper{

        .profile-picture{
            width: 60px;
            height: 60px;
        }
    }
}
</style>