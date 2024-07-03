<template>
    <div class="activity-badge-wrapper" v-if="activity">
        <div class="created">
            {{getDateReadable(activity.createdAt)}}
        </div>
        <div class="main">
            <div class="resource" v-if="computedResource">
                <template v-if="computedResource.type === 'image'">
                    <img :src="computedResource.url">
                </template>
                <template v-if="computedResource.type === 'video'">
                    <video 
                        controls
                        :src="computedResource.url"></video>
                </template>
                <template v-if="computedResource.type === 'audio'">
                    <audio 
                        controls
                        :src="computedResource.url"></audio>
                </template>
            </div>
            <div class="message">
                {{computedMessage}}
            </div>
        </div>
        <div class="actions">
            <dashboard-action-button
                :title="`view this ${activity.activity}`"
                :text="`view ${activity.activity}`"
                icon=""
                :data="{item: activity.activity, itemId: activity.activityId}"
                v-if="activity.activity !== 'like'"
                @click="clickedDashboardActionButton"
            ></dashboard-action-button>
            <dashboard-action-button
                v-if="activity.item"
                :title="`view this ${activity.item}`"
                :text="`view ${activity.item}`"
                icon=""
                :data="{item: activity.item, itemId: activity.itemId}"
                @click="clickedDashboardActionButton"
            ></dashboard-action-button>
        </div>
        <div class="bottom">
            <div class="item">
                {{`${activity.activity} activity`}}
            </div>
        </div>
    </div>
</template>

<script>
import { dates } from '../../services/helpers';
import DashboardActionButton from './DashboardActionButton.vue';
    export default {
        components: {
            DashboardActionButton,
        },
        props: {
            activity: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        computed: {
            computedMessage() {
                let what = this.activity.activity === 'like' ? `liked a ${this.activity.item}` :
                    this.activity.activity === 'comment' ? `commented: ${this.activity.comment}` :
                    this.activity.activity === 'activity' ? 'posted an activity' :
                    this.activity.activity === 'question' ? 'posted a question' :
                    this.activity.activity === 'answer' ? `answered a ${this.activity.item}` :
                    this.activity.activity === 'poem' ? `posted a ${this.activity.item}` :
                    this.activity.activity === 'riddle' ? `posted a ${this.activity.item}` :
                    this.activity.activity === 'post' ? `posted a ${this.activity.content}` :
                    this.activity.activity === 'lesson' ? `posted a ${this.activity.item}` :
                    this.activity.activity === 'discussion' ? `raised a discussion with title: ${this.activity.title}` : ''
                
                return what 
            },
            computedResource() {
                if (this.activity.images && this.activity.images.length) {
                    return {
                        url: this.activity.images[0].url,
                        type: 'image',
                    }
                } else if (this.activity.videos && this.activity.videos.length) {
                    return {
                        url: this.activity.videos[0].url,
                        type: 'video',
                    }
                } else if (this.activity.audios && this.activity.audios.length) {
                    return {
                        url: this.activity.audios[0].url,
                        type: 'audio',
                    }
                }
                return null
            },
        },
        methods: {
            clickedDashboardActionButton(data) {
                console.log(data);
                // this.$router.push({
                //     name: data.data.item,
                //     params:{itemId: data.data.itemId}
                // })
                this.$emit('viewItem',data.data)
            },
            getDateReadable(date){
                return dates.dateReadable(date)
            }
        },
    }
</script>

<style lang="scss" scoped>

@mixin small-text(){
    font-size: 10px;
    color: gray;
    width: 100%;
    text-align: end;
}

    .activity-badge-wrapper{
        border-top: 1px solid gray;
        padding: 5px;
        
        .created{
            @include small-text()
        }

        .main{
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;

            .resource{
                width: 30%;
                min-width: 50px;
                min-height: 50px;
                margin-right: 5px;

                img, video{
                    object-fit: contain;
                    width: 100%;
                    height: 100%;
                }
            }

            .message{
                font-size: 14px;
                color: gray;
            }
        }

        .actions{
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: flex-end;
        }

        .bottom{

            .item{
                @include small-text()
            }
        }
    }
</style>