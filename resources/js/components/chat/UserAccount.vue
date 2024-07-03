<template>
    <div class="user-account-wrapper"
        :class="{userAccountActive:isActive}"
    >
        <profile-picture
            class="user-account-profile"
        >
            <template slot="image">
                <img :src="account.url" alt="">
            </template>
        </profile-picture>
        <div class="other-section">
            <div class="top">
                <div class="name">{{account.name}}</div>
                <div class="account">{{account.account}}</div>
            </div>
            <div class="bottom">
                <action-button
                    @click="clickedActivate"
                    :text="activeButtonText"
                    :loading="activationLoading"
                    :background="true"
                    v-if="activeButtonText.length"
                    class="action-button"
                ></action-button>
                <div class="active"
                    v-else
                >
                    active
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProfilePicture from '../profile/ProfilePicture.vue';
import ActionButton from '../ActionButton.vue';
    export default {
        props: {
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
            isActive: {
                type: Boolean,
                default: false
            },
        },
        components: {
            ActionButton,
            ProfilePicture,
        },
        data() {
            return {
                activeButtonText: 'make actvie',
                activationLoading: false
            }
        },
        watch: {
            isActive: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.activeButtonText = ''
                    } else {
                        this.activeButtonText = 'make active'
                    }
                    this.activationLoading = false
                }
            }
        },
        methods: {
            clickedActivate() {
                this.activationLoading = true
                this.$emit('clickedActivate', this.account)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .user-account-wrapper{
        display: inline-flex;
        width: 100%;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: space-around;
        margin-bottom: 10px;
        box-shadow: 0 0 2px grey;
        border-radius: 10px;
        padding: 5px;
        cursor: pointer;

        &:hover{
            background-color: whitesmoke;
            transition: all .5s ease-in;
        }

        .user-account-profile{
            width: 50px;
            height: 50px;
        }

        .other-section{
            width: 80%;

            .top{
                width: 100%;
                display: inline-flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: nowrap;
                color: gray;

                .name{
                    text-transform: capitalize;
                    font-weight: 500;
                    font-size: 14px;
                    max-width: 65;
                    text-align: start;
                    @include text-overflow()
                }

                .account{
                    max-width: 40%;
                    font-size: 10px;
                    text-align: end;
                    @include text-overflow()
                }
            }

            .bottom{
                width: 100%;

                .action-button{
                    width: fit-content;
                    text-align: center;
                    padding: 5px;
                    border-radius: 10px;
                    color: white;
                    background: gray;
                    font-size: 10px;
                    min-width: 30px;
                    margin: 5px 0 5px auto;

                    &:hover{
                        box-shadow: 0 0 2px gray;
                    }
                }

                .active{
                    padding: 5px;
                    font-size: 10px;
                    font-variant: small-caps;
                    color: gray;
                    text-align: end;
                }
            }
        }
    }

    .userAccountActive{
        background-color: whitesmoke;
        transition: all .5s ease;
    }
</style>