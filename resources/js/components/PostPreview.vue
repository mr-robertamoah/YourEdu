<template>
    <div class="post-preview-wrapper">
        <div class="author" v-if="computedAuthor.length">
            authored by {{computedAuthor}}
        </div>
        <div class="top-section">
            <div class="heading" v-if="computedHeading">
                {{computedHeading}}
            </div>
            <div class="type" v-if="computedType">
                {{computedType}}
            </div>
        </div>
        <div class="middle">
            <div class="body" v-if="computedBody">
                <main-textarea
                    :value="typeValue"
                    :disabled="true"
                ></main-textarea>
            </div>
            <div class="preview">
                <img :src="computedImageUrl" v-if="showImage">
                <audio :src="computedAudioUrl" controls controlslist='nodownload'
                    v-if="showVideo"
                ></audio>
                <video :src="computedVideoUrl" controls controlslist='nodownload'
                    v-if="showAudio"
                ></video>
            </div>
        </div>
    </div>
</template>

<script>
import {strings} from '../services/helpers'
import MainTextarea from './MainTextarea'

    export default {
        props: {
            type: {
                type: Object,
                default(){
                    return {}
                }
            },
            typeName: {
                type: String,
                default: '',
            },
        },
        components: {
            MainTextarea,
        },
        data() {
            return {
                showImage: false,
                showVideo: false,
                showAudio: false,
                typeValue:'',
            }
        },
        watch: {
            type: {
                immediate: true,
                handler(newValue, oldValue){
                    
                    this.showImage = false
                    this.showVideo = false
                    this.showAudio = false
                    if (newValue instanceof Object) {
                        if (this.type.images && this.type.images.length > 0) {
                            this.showImage = true
                        } else if (this.type.videos && this.type.videos.length > 0) {
                            this.showVideo = true
                        } else if (this.type.audios && this.type.audios.length > 0) {
                            this.showAudio = true
                        }
                    } 
                },
                deep: true
            }
        },
        computed: {
            computedImageUrl() {
                return this.type.images ?  this.type.images[0].url : ''
            },
            computedVideoUrl() {
                return this.type.videos ?  this.type.videos[0].url : ''
            },
            computedAudioUrl() {
                return this.type.audios ?  this.type.audios[0].url : ''
            },
            computedHeading() {
                return this.title ? this.title : ''
            },
            computedType() {
                return this.typeName ? this.typeName : ''                
            },
            computedAuthor(){
                 return this.type && this.type.hasOwnProperty('author') ? 
                    this.type.author : ''
            },
            computedBody() {
                if (this.type) {
                    if (this.typeName === 'book') {
                        this.typeValue = strings.content(this.type.about)
                    } else if (this.typeName === 'poem') {
                        this.typeValue =  strings.content(this.type.sections.map(el=>el.body),200,true)
                    } else if (this.typeName === 'question') {
                        this.typeValue =  strings.content(this.type.question)
                    } else if (this.typeName === 'activity') {
                        this.typeValue =  strings.content(this.type.description)
                    } else if (this.typeName === 'riddle') {
                        this.typeValue =  strings.content(this.type.riddle)
                    } else{
                        this.typeValue =  ''
                    }
                } else {
                    return false
                }
                return true          
            },
        },
    }
</script>

<style lang="scss" scoped>
@mixin text-overflow(){
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

    .post-preview-wrapper{
        position: relative;
        width: 100%;

        .author{
            text-align: end;
            border-bottom: 1px solid dimgray;
        }

        .top-section{
            width: 100%;
            display: inline-flex;
            justify-content: space-between;

            .heading{
                padding: 5px;
                min-width: 60%;
                font-size: 16px;
                font-weight: 500;
                text-align: center;
                text-transform: capitalize;
                @include text-overflow();
            }

            .type{
                padding: 5px;
                max-width: 20%;
                font-size: 14px;
                text-align: end;
                font-variant: small-caps;
                color: rgba(128, 128, 128, 0.9);
                @include text-overflow();
            }
        }

        .middle{
            width: 100%;
            position: relative;

            .body{
                width: 100%;
                font-size: 14px;
                text-align: justify;
            }

            .preview{
                width: 100%;
                padding-left: 5%;
                padding-right: 5%;

                audio,
                video{
                    width: inherit;
                    height: auto;
                }

                img{
                    width: 60%;
                    height: auto;
                }
            }                
        }
    }
</style>