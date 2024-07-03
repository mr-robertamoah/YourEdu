<template>
    <div class="post-preview-wrapper"
        @dblclick="clickedShowPostPreview"
        :class="{typeMediaFull:typeMediaFull}"
    >
        <div class="author">
            <div class="answers" 
                v-if="computedMustAnswer"
            >
                <div class="text">
                    answers
                </div>
                <div class="numbers">
                    {{computedAnswers}}
                </div>
            </div>
            <div :class="{pushRight:!computedMustAnswer}">
                authored by {{computedAuthor}}
            </div>
        </div>
        <div class="top-section">
            <div class="heading">
                {{computedHeading}}
            </div>
            <div class="type">
                {{computedType}}
            </div>
        </div>
        <div class="middle">
            <div class="body" v-if="computedBody"
                @dblclick="clickedShowPostPreview"
            >
                <main-textarea
                    :value="typeValue"
                    :disabled="true"
                ></main-textarea>
                <div class="possible-answers" v-if="computedPossibleAnswers"
                    @click="clickedShowPostPreview">
                    <div class="answer"
                        :key="key"
                        v-for="(answer,key) in type.possibleAnswers"
                    >
                        {{answer.option}}
                    </div>
                </div>
            </div>
            <div class="preview" 
                :class="{typeMediaFull:typeMediaFull}"
                @dblclick="clickedShowPostPreview"
            >
                <img :src="computedImageUrl" v-if="showImage"
                    @click="clickedMedia(computedImageUrl,'image')"
                >
                <audio :src="computedAudioUrl" controlslist='nodownload'
                    v-if="showVideo"
                    @click="clickedMedia(computedAudioUrl,'audio')"
                ></audio>
                <video :src="computedVideoUrl" controlslist='nodownload'
                    v-if="showAudio"
                    @click="clickedMedia(computedVideoUrl,'video')"
                ></video>
            </div>
        </div>
        <div class="bottom" v-if="showButton && typeMediaFull">
            <post-button
                buttonText="answer"
                @click="clickedAnswer"
            ></post-button>
        </div>
    </div>
</template>

<script>
import {strings} from '../services/helpers'
import MainTextarea from './MainTextarea.vue'
import PostButton from './PostButton.vue'

    export default {
        props: {
            type: {
                type: Object,
                default(){
                    return {}
                }
            },
            post: {
                type: Object,
                default(){
                    return {}
                }
            },
            typeName: {
                type: String,
                default: '',
            },
            typeMediaFull: { //this is true when in post modal
                type: Boolean,
                default: false
            },
            showButton: {
                type: Boolean,
                default: false
            },
            disabled: {
                type: Boolean,
                default: false
            },
        },
        components: {
            PostButton,
            MainTextarea,
        },
        data() {
            return {
                showImage: false,
                showVideo: false,
                showAudio: false,
                typeValue:'', //main value shown for each type
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
            computedMustAnswer(){
                if (this.typeName === 'riddle' || this.typeName === 'question') {
                    return true
                }
                return false
            },
            computedAnswers(){
                if (this.computedMustAnswer) {
                    return this.type.answers_number
                } else {
                    return ''
                }
            },
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
                return this.type.title && this.type.title.length ? this.type.title : ''
            },
            computedType() {
                return this.typeName ? this.typeName : ''                
            },
            computedAuthor(){
                 return this.type && this.type.hasOwnProperty('author') ? 
                    this.type.author : ''
            },
            computedPossibleAnswers(){
                if (this.type.hasOwnProperty('possible_answers') &&
                    this.type.possibleAnswers.length) {
                    return this.disabled ? true : !this.typeMediaFull ? false : true
                }
                return false
            },
            computedBody() {
                if (this.type) {
                    if (this.typeName === 'book') {
                        if (this.type.about && this.type.about.length) {
                            this.typeValue = this.typeMediaFull ? this.type.about : strings.trim(this.type.about)
                        } else {
                            return false
                        }
                    } else if (this.typeName === 'poem') {
                        if (this.type.sections && this.type.sections.length) {
                            let mappedString = strings.arrayToNewLineStrings(this.type.sections.map(el=>el.body))
                            this.typeValue =  this.typeMediaFull ? mappedString : strings.trim(mappedString,200,true)
                        } else {
                            return false
                        }
                    } else if (this.typeName === 'question') {
                        if (this.type.body && this.type.body.length) {
                            this.typeValue = this.typeMediaFull ? this.type.body :  strings.trim(this.type.body)
                        } else {
                            return false
                        }
                    } else if (this.typeName === 'activity') {
                        if (this.type.description && this.type.description.length) {
                            this.typeValue =  this.typeMediaFull ? this.type.description : strings.trim(this.type.description)
                        } else {
                            return false
                        }
                    } else if (this.typeName === 'riddle') {
                        if (this.type.body && this.type.body.length) {
                            this.typeValue =  this.typeMediaFull ? this.type.body : strings.trim(this.type.body)
                        } else {
                            return false
                        }
                    } else{
                        this.typeValue =  ''
                    }
                } else {
                    return false
                }
                return true          
            },
        },
        methods: {
            clickedAnswer(){
                this.$emit('clickedAnswer')
            },
            clickedMedia(url,mediaType){
                this.$emit('clickedMedia',{url,mediaType})
            },
            clickedShowPostPreview(){
                if (this.typeMediaFull || this.disabled) {
                    return 
                }
                this.$emit('clickedShowPostPreview',{
                    data: {
                        type: this.type,
                        typeName: this.typeName,
                        postId: this.post.id,
                        owner: {
                            account: strings.getAccount(this.post.addedby_type),
                            accountId: this.post.addedby_id
                        }
                    }, 
                    type: 'posttype'
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

    .post-preview-wrapper{
        position: relative;
        width: 100%;

        .author{
            text-align: end;
            border-bottom: 1px solid dimgray;
            font-size: small;
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;

            .answers{
                display: inline-flex;
                align-items: flex-end;
                position: relative;

                .text{
                    font-size: 14px;
                }

                .numbers{
                    color: gray;
                    border-radius: 50%;
                    padding: 2px;
                    font-size: 10px;
                    background-color: rgba(128, 128, 128, 0.157);
                    position: absolute;
                    left: 100%;
                    bottom: 0;
                    margin-bottom: 5px;
                }
            }

            .pushRight{
                margin-left: auto;
            }
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

                .possible-answers{
                    max-width: 60%;
                    min-width: 30%;
                    text-align: center;
                    margin-left: auto;
                    margin-right: 10px;
                    margin-bottom: 5px;
                    background-color: gainsboro;

                    .answer{
                        font-size: 12px;
                        padding: 5px;
                        color: gray;
                        font-weight: 450;
                        cursor: pointer;
                        @include text-overflow()
                    }
                }
            }

            .preview{
                width: 100%;
                padding-left: 10px;
                padding-right: 10px;
                height: 120px;

                audio{
                    width: inherit;
                    height: auto;
                }

                img,
                video{
                    width: inherit;
                    height: inherit;
                    object-fit: contain;
                    object-position: top;
                }
            }  

            .typeMediaFull{
                height: 200px;
                overflow: visible;

                audio{
                    width: inherit;
                    height: auto;
                }

                img,
                video{
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    object-position: top;
                }
            }              
        }

        .bottom{
            text-align: center;
            margin-top: 10px;
        }
    }

    .typeMediaFull{

        .author{
            padding: 5px;
        }
    }
</style>