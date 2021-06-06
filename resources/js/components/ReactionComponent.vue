<template>
    <div class="reaction-component">
        <div class="reaction" 
            :class="{classes: classes.length}"
        >
            <post-button 
                :class="{'z-40': isAdult}"
                :titleText="flagData.flagTitle"
                v-if="!isOwner"
                @click="clickedFlag"
                :postButtonClass="flagData.flagRed"
            >
                <template slot="icon">
                    <font-awesome-icon
                        :icon="['fa','flag']"
                    ></font-awesome-icon>
                </template>
            </post-button>
            <div class="reason">
                <flag-reason
                    :show="showFlagReason"
                    :hasBackground="true"
                    @continueFlagProcess="continueFlagProcess"
                    @reasonGiven="reasonGiven"
                    @cancelFlagProcess="cancelFlagProcess"
                ></flag-reason>
            </div>
            <div class="like">
                <number-of>
                    {{computedLikesText}}
                </number-of>
                <div class="others" 
                    :class="{classes: classes.length}"
                >
                    <div class="like-post"
                        @click="clickedLike"
                        :class="{liked:likeData.isLiked}"
                        :title="likeData.likeTitle"
                    >
                        <font-awesome-icon
                            :icon="['fa','thumbs-up']"
                        ></font-awesome-icon>
                    </div>
                    <div class="profiles"
                        v-if="showProfiles"
                    >
                        <span>
                            {{showProfilesText}}
                        </span>
                        <div :key="key" v-for="(profile,key) in profiles">
                            <profile-bar
                                :smallType="true"
                                :profile="profile"
                                :navigate="false"
                                @clickedProfile="clickedProfile"
                            ></profile-bar>
                        </div>
                    </div>
                    <div class="comment"
                        title="add a comment"
                        @click="clickedAddComment"
                        v-if="!showAddComment"
                    >
                        <font-awesome-icon
                            :icon="['fa','comment']"
                        ></font-awesome-icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="add-comment">
            <add-comment
                :what="item"
                :onPostModal="full"
                :showAddComment="showAddComment"
                @hideAddComment="hideAddComment"
                @postAddComplete="postAddComplete"
                @postModalCommentCreated="postModalCommentCreated"
            ></add-comment>
        </div>
        <div class="comment-section"
            @dblclick.self="clickedShowPostComments">
            <template v-if="!full && comments">
                <comment-single
                    v-for="comment in comments"
                    :key="comment.id" 
                    :comment="comment"
                    :simple="true"
                    @askLoginRegister="askLoginRegister"
                    @clickedMedia="clickedMedia"
                    @clickedShowPostComments="clickedShowPostComments"
                ></comment-single>
            </template>
        </div>
    </div>
</template>

<script>
import AddComment from './AddComment';
import FlagReason from './FlagReason';
import PostButton from './PostButton';
import NumberOf from './NumberOf';
import ProfileBar from './profile/ProfileBar';
import { mapGetters } from 'vuex';
    export default {
        components: {
            AddComment,
            FlagReason,
            PostButton,
            NumberOf,
            ProfileBar,
        },
        props: {
            item: {
                type: Object,
                default() {
                    return null
                }
            },
            flagData: {
                type: Object,
                default() {
                    return null
                }
            },
            likeData: {
                type: Object,
                default() {
                    return null
                }
            },
            profiles: {
                type: Array,
                default() {
                    return []
                }
            },
            comments: {
                type: Array,
                default() {
                    return []
                }
            },
            full: {
                type: Boolean,
                default: false
            },
            showProfiles: {
                type: Boolean,
                default: false
            },
            classes: {
                type: String,
                default: ''
            },
            showProfilesText: {
                type: String,
                default: false
            },
            showAddComment: {
                type: Boolean,
                default: false
            },
            showFlagReason: {
                type: Boolean,
                default: false
            },
            isOwner: {
                type: Boolean,
                default: false
            },
        },
        computed: {
            ...mapGetters([
                'isAdult'
            ]),
            computedLikesText() {
                return this.likeData.likes === 1 ? `${this.likeData.likes} like` : `${this.likeData.likes} likes`
            }
        },
        methods: {
            clickedShowPostComments(data) {
                this.$emit('clickedShowPostComments', data)
            },
            clickedMedia(data) {
                this.$emit('clickedMedia', data)
            },
            askLoginRegister(data) {
                this.$emit('askLoginRegister', data)
            },
            postModalCommentCreated(data) {
                this.$emit('postModalCommentCreated', data)
            },
            postAddComplete(data) {
                this.$emit('postAddComplete', data)
            },
            hideAddComment(data) {
                this.$emit('hideAddComment', data)
            },
            clickedProfile(data) {
                this.$emit('clickedProfile', data)
            },
            clickedAddComment(data) {
                this.$emit('clickedAddComment', data)
            },
            clickedLike(data) {
                this.$emit('clickedLike', data)
            },
            cancelFlagProcess(data) {
                this.$emit('cancelFlagProcess', data)
            },
            reasonGiven(data) {
                this.$emit('reasonGiven', data)
            },
            continueFlagProcess(data) {
                this.$emit('continueFlagProcess', data)
            },
            clickedFlag(data) {
                this.$emit('clickedFlag', data)
            },
        },
    }
</script>

<style lang="scss" scoped>
    

    .reaction-component{
        width: 100%;
        padding-top: 5px;

        .reaction{
            display: flex;
            justify-content: space-between;
            align-items: center; 
            position: relative;

            .reason{
                position: absolute;
                top: 100%;
                z-index: 3;
            }

            .like{
                display: inline-flex;
                align-items: center;
                justify-content: space-between;
                width: 50%;  
                margin-left: auto;

                .others{
                    display: inline-flex;
                    justify-content: flex-end;
                    align-items: center;
                    position: relative;

                    .like-post{
                        margin-right: 10px;
                        padding: 5px;
                        font-size: 16px;
                        cursor: pointer;
                    }

                    .liked{
                        color: green;
                    }   

                    .profiles{
                        position: absolute;
                        width: 200px;
                        right: 0;
                        z-index: 1000;
                        text-align: start;
                        top: 15px;

                        span{
                            font-size: 12px;
                            font-weight: 500;
                        }
                    }

                    .comment{
                        cursor: pointer;
                        font-size: 16px;
                    }
                }

                .unset{
                    position: unset;
                }

                .profiles-down{
                    bottom: 0;
                }
            }
        }

        .unset{
            position: unset;
        }

        .add-comment{
            width: 75%;
            position: relative;
            margin: 10px 0 0 auto;
        }

        .comment-section{
            width: 85%;
            margin: 5px 0 0 auto;
        }
    }
</style>