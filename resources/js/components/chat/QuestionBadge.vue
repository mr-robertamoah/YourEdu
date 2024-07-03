<template>
    <div class="question-badge-wrapper" @dblclick="clickedShowOptions">
        <div class="deleted" v-if="computedDeletedForMe">
            <div class="icon">
                <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
            </div>
            <div class="text">question deleted</div>
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
        >
            <template slot="extraicon">
                <font-awesome-icon :icon="['fa','trash']"></font-awesome-icon>
            </template>
        </optional-actions>
        <div class="top" v-if="!computedDeletedForMe">
            <div class="name" v-if="computedName.length">
                asked by {{computedOwner ? 'You' : computedName}}
            </div>
            <div class="updated-at">
                {{computedCreatedAt}}
            </div>
        </div>
        <div class="middle" v-if="!computedDeletedForMe">
            <div class="question">
                {{question.body}}
            </div>
            <div class="media" v-if="computedMedia">
                <img :src="computedImage.url"
                    v-if="computedImage"
                    @click="clickedMedia(computedImage.url,'image')"
                >
                <video :src="computedVideo.url"
                    v-if="computedVideo"
                    @click="clickedMedia(computedVideo.url,'video')"
                    controls
                ></video>
                <audio :src="computedAudio.url"
                    v-if="computedAudio"
                    @click="clickedMedia(computedAudio.url,'audio')"
                    controls
                ></audio>
            </div>
            <div class="possible-answers" v-if="computedPossibleAnswers.length">
                <div class="possible-answer"
                    v-for="(possibleAnswer, index) in computedPossibleAnswers"
                    :key="index"
                    @click="clickedPossibleAnswer(possibleAnswer)"
                >
                    {{possibleAnswer.option}}
                </div>
            </div>
            <div class="answer-section" 
                v-else
                @click="clickedAnswer"
            >
                answer
            </div>
        </div>
        <div class="bottom" v-if="question.answers.length && !computedDeletedForMe">
            <div class="answers">
                <div class="heading">answers</div>
                <answer-single
                    v-for="(answer, index) in question.answers"
                    :key="index"
                    :answer="answer"
                    :chat="true"
                    :conversationId="question.questionableId"
                    @markChatAnswer="markChatAnswer"
                    @clickedOption="clickedAnswerOption"
                ></answer-single>
            </div>
        </div>
        <div class="chat-item-state" v-if="computedOwner && !computedDeletedForMe">
            {{question.state}}
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { dates } from '../../services/helpers';
import AnswerSingle from '../AnswerSingle.vue';
import OptionalActions from '../OptionalActions.vue';
    export default {
        components: {
            OptionalActions,
            AnswerSingle,
        },
        props: {
            question: {
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
            computedImage(){
                return this.question.images && this.question.images.length ? 
                    this.question.images[0] : null
            },
            computedVideo(){
                return this.question.videos && this.question.videos.length ? this.question.videos : null
            },
            computedAudio(){
                return this.question.audios && this.question.audios.length ? this.question.audios : null
            },
            computedFile(){
                return this.question.files && this.question.files.length ? this.question.files : null
            },
            computedMedia(){
                return this.computedImage || this.computedVideo || this.computedAudio || this.computedFile
            },
            computedOwner(){
                return this.question.user_id === this.getUser.id ? true : false
            },
            computedName(){
                return this.question.name ? this.question.name.toLowerCase() : ''
            },
            computedCreatedAt(){
                return dates.createdAt(this.question.created_at)
            },
            computedPossibleAnswers(){
                return this.question.possibleAnswers
            },
            computedDeletedForMe(){
                return this.getUser.id && this.question.userDeletes && 
                    this.question.userDeletes.includes(this.getUser.id)
            }
        },
        methods: {
            messageCreatedAt(data) {
                return dates.createdAt(data)
            },
            clickedMedia(url, type){
                this.$emit('clickedMedia',{url,type})
            },
            clickedPossibleAnswer(possibleAnswer){
                this.$emit('clickedAnswer',{
                    question: this.question,
                    answer: possibleAnswer
                })
            },
            clickedAnswer(){
                this.$emit('clickedAnswer',{
                    question: this.question,
                    answer: null
                })
            },
            markChatAnswer(data){
                this.$emit('markChatAnswer', data)
            },
            clickedOption(data){
                this.showOptionalActions = false
                this.$emit('clickedOption',{
                    action: data,
                    type: 'question',
                    question: this.question,
                    conversationId: this.question.questionableId
                })
            },
            clickedAnswerOption(data){
                this.$emit('clickedOption',data)
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

@mixin message(){
    background: cornsilk;
    text-align: center;
    margin: 5px 0 5px auto;
    font-size: 14px;
    padding: 5px;
    border-radius: 10px;
}

@mixin name(){
    font-size: 12px;
    max-width: 65%;
    @include text-overflow();
}
@mixin possibleAnswer(){
    width: fit-content;
    padding: 5px;
    color: gray;
    box-shadow: 0 0 2px gray;
    border-radius: 10px;
    cursor: pointer;
    min-width: 50px;
    margin: 0;
    font-size: 12px;
}

    .question-badge-wrapper{
        max-width: 350px;
        width: 75%;
        margin: 10px auto;
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
        
        .chat-item-state{
            position: absolute;
            right: -5px;
            top: 100%;
            font-size: 9px;
            color: gray;
            text-transform: lowercase;
        }

        .top{
            display: inline-flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
            color: gray;

            .name{
                @include name();
            }

            .updated-at{
                font-size: 9px;
            }
        }

        .middle{
            width: 100%;
            text-align: center;

            .question{
                @include message();
            }

            .media{
                width: 100%;
                height: 100px;

                img,
                video{
                    width: inherit;
                    height: inherit;
                    object-fit: contain;
                }

                audio{
                    width: 100%;
                }
            }

            .possible-answers{
                margin: 5px auto; 
                display: inline-flex;
                justify-content: space-around;
                width: 100%;
                flex-wrap: wrap;

                .possible-answer{
                    @include possibleAnswer();
                }
            }

            .answer-section{
                @include possibleAnswer();
                margin: 5px 0;
            }
        }

        .bottom{
            margin: 10px auto;
            border-top: 1px solid gray;
            border-bottom: 1px solid gray;
            padding: 20px 10px;
            max-height: 400px;
            overflow-y: auto;

            .name{
                @include name();
            }

            .answers{

                .heading{
                    color: gray;
                    font-size: 12px;
                    margin: 5px 0 10px;
                    text-align: center;
                }

            }
        }
    }

@media screen and (max-width: 800px) {
    
    .question-badge-wrapper{
        max-width: 350px;
        width: 85%;
    }
}

@media screen and (max-width: 500px) {
    
    .question-badge-wrapper{
        max-width: 350px;
        width: 95%;
    }
}
</style>