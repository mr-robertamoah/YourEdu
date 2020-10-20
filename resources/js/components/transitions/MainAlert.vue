<template>
    <transition name="main-alert">
        <div class="main-alert-wrapper"
            :class="{messageText:text.length && isMessage}"
        >
            <div class="close" @click="clickedRemoveAlert">
                <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
            </div>
            <div class="message" v-if="isMessage && text.length">
                {{text}}
            </div>
            <discussion-badge
                v-if="isMessage && !text.length"
                :message="message"
                :alert="true"
            ></discussion-badge>
            <account-badge
                v-if="isAccount"
                :alert="true"
                :account="account"
                class="account-badge"
            ></account-badge>
        </div>
    </transition>
</template>

<script>
import AccountBadge from '../dashboard/AccountBadge';
import DiscussionBadge from '../DiscussionBadge';
    export default {
        components: {
            DiscussionBadge,
            AccountBadge,
        },
        props: {
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
            message: {
                type: Object,
                default(){
                    return {}
                }
            },
            isAccount: {
                type: Boolean,
                default: false
            },
            isMessage: {
                type: Boolean,
                default: false
            },
            show: {
                type: Boolean,
                default: false
            },
            text: {
                type: String,
                default: ''
            },
            id: {
                type: Number,
                default: 0
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        setTimeout(() => {
                            this.removeAlert()
                        }, 4000);
                    }
                }
            }
        },
        methods: {
            clickedRemoveAlert(){
                this.removeAlert()
            },
            removeAlert() {
                this.$emit('removeAlert',this.id)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .main-alert-enter{
        transform: translateX(100px);
        width: 0;
    }

    .main-alert-leave-to{
        transform: translateX(100px);
        width: 0;
    }
    
    .main-alert-enter-active,
    .main-alert-leave-active{
        transition: all 1s ease-out;
    }

    .main-alert-wrapper{
        width: 100%;
        position: relative;

        .close{
            position: absolute;
            z-index: 1;
            top: 5px;
            right: 5px;
            padding: 5px;
            font-size: 14px;
            cursor: pointer;
            color: gray;
            border-radius: 10px;
            background: white;

            &:hover{
                color: red;
                transition: color .5s ease;
            }
        }

    }

    .messageText{
        border-radius: 10px;
        box-shadow: 0 0 2px gray;
        background: rgb(240,248,255);
        padding: 10px;

        .message{
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: gray;
        }
    }
</style>