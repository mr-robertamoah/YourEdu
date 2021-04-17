<template>
    <div class="request-badge-wrapper" v-if="request">
        <div class="section">
            <div class="name">
                {{request.name}}
            </div>
            <div class="created-at">
                {{request.createdAt}}
            </div>
        </div>
        <div class="section">
            <profile-picture 
                class="profile-picture"
                v-if="request.account.url"
            >
                <template slot="image">
                    <img :src="request.account.url">
                </template>
            </profile-picture>
            <div class="message">
                {{request.message}}
            </div>
        </div>
        <div class="section buttons">
            <pulse-loader 
                v-if="loading"
                class="loading" 
                :loading="loading"
            ></pulse-loader>

            <template v-if="! loading">
                <action-button
                    @click="clickedAction"
                    text="details"
                    class="action-button"
                ></action-button>
                <action-button
                    @click="clickedAction"
                    text="messages"
                    class="action-button"
                ></action-button>
                <action-button
                    @click="clickedSendResponse"
                    text="accept"
                    v-if="computedHasPending"
                    class="action-button"
                ></action-button>
                <action-button
                    @click="clickedSendResponse"
                    text="decline"
                    v-if="computedHasPending"
                    class="action-button"
                ></action-button>
            </template>
        </div>

        <create-response
            :show="showCreateModal"
            @clickedSendResponse="clickedSendResponse"
        ></create-response>
    </div>
</template>

<script>
import CreateResponse from "./forms/CreateResponse"
import ProfilePicture from "./profile/ProfilePicture"
import ActionButton from "./ActionButton"
import { mapActions } from 'vuex'
    export default {
        components: {
            ActionButton,
            ProfilePicture,
            CreateResponse,
        },
        props: {
            request: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                showCreateModal: false,
                response: '',
            }
        },
        computed: {
            computedHasPending() {
                return this.request.state === 'pending'
            },
        },
        methods: {
            ...mapActions(['dashboard/sendResponse']),
            clickedAction(text) {
                if (text === 'messages') {
                    this.$emit('showMessages', this.request)
                    return
                }

                this.$emit('showDetails', this.request)
            },
            async clickedSendResponse() {
                this.request.response = this.response
                this.$emit('clickedAction', this.request)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .request-badge-wrapper{
        padding: 5px;
        max-width: 500px;
        width: 90%;
        margin: 0 auto 10px;
        box-shadow: 0 0 2px grey;
        border-radius: 10px;

        .section{
            display: flex;
            align-items: flex-start;
            flex-wrap: nowrap;
            width: 100%;
            justify-content: space-between;

            &.buttons{
                overflow-x: auto;
            }

            .profile-picture{
                min-width: 40px;
                width: 40px;
                height: 40px;
                margin-right: 10px;
            }

            .name{
                @include small-msg;
                text-align: left;
                font-size: 11px;
                text-transform: capitalize;
                font-weight: bold;
            }

            .created-at{
                @include small-msg;
                text-align: right;
                font-size: 11px
            }

            .message{
                @include small-msg;
                text-align: justify;
            }
        }
    }
</style>