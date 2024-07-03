<template>
    <div class="request-badge-wrapper" v-if="request">
        <div class="section">
            <div class="name">
                {{request.account.name}}
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
        <div class="section-buttons">
            <pulse-loader 
                v-if="loading"
                class="loading"
                size="10px"
                :loading="loading"
            ></pulse-loader>

            <template v-if="! loading">
                <action-button
                    @click="clickedAction"
                    text="details"
                    class="action-button"
                ></action-button>
                <div class="messages-button">
                    <action-button
                        @click="clickedAction"
                        text="messages"
                        class="action-button"
                    ></action-button>
                    <div class="counter" v-if="newMessagesCounter">
                        {{newMessagesCounter}}
                    </div>
                </div>
                <template v-if="computedIsTo">
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
            </template>
        </div>

        <div class="small-msg" v-if="dashboard">
            {{computedExtraMessage}}
        </div>

        <create-response
            :show="showCreateModal"
            @clickedSendResponse="clickedSendResponse"
        ></create-response>
    </div>
</template>

<script>
import CreateResponse from "./forms/CreateResponse.vue"
import ProfilePicture from "./profile/ProfilePicture.vue"
import ActionButton from "./ActionButton.vue"
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
            dashboard: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                loading: false,
                showCreateModal: false,
                newMessagesCounter: 0,
            }
        },
        watch: {
            request: {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        this.listenToRequestChannels()
                    }
                }
            }
        },
        computed: {
            computedHasPending() {
                return this.request.state === 'pending'
            },
            computedExtraMessage() {
                let type = 'to'

                if (this.request.isFrom) {
                    type = 'from'
                }

                return `${this.request.action} request sent ${type} ${this.request.myAccount.name}`
            },
            computedIsTo() {
                if (!this.dashboard) {
                    return true
                }

                return this.isTo
            }
        },
        methods: {
            ...mapActions([]),
            listenToRequestChannels() {
                Echo
                    .channel(`youredu.request.${this.request.id}`)
                    .listen(`.newMessage`, this.newRequestMessage)
            },
            newRequestMessage(data) {
                this.newMessagesCounter++
                this.$emit('newMessage', data)
            },
            deleteRequestMessage(data) {
                this.$emit('deleteMessage', data)
            },
            clickedAction(text) {
                if (text === 'messages') {
                    this.$emit('showMessages', this.request)
                    return
                }

                this.$emit('showDetails', this.request)
            },
            async clickedSendResponse(text) {
                this.loading = true
                this.request.response = text
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

            .loading{
                text-align: center;
                width: 100%;
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

        .section-buttons{
            position: relative;
            display: flex;
            justify-content: space-around;

            .messages-button{
                position: relative;

                .action-button{
                    margin: 0;
                }

                .counter{
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    color: $color-secondary;
                    font-size: 14px;
                }
            }
        }
    }
</style>