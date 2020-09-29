<template>
    <div class="other-user-account" 
        v-if="account.name"
        @click="clickedOtherUserAccount"
        :title="online ? 'user is online' : 'user is offline'"
    >
        <div class="online-alert" v-if="online"></div>
        <profile-picture
            class="profile-picture"
        >
            <template slot="image">
                <img :src="account.url">
            </template>
        </profile-picture>
        <div class="other-section">
            <div class="top">
                <div class="name">{{account.name}}</div>
                <div class="account">{{account.account}}</div>
            </div>
            <div class="bottom" v-if="!friend">
                <div class="type">{{account.type === 'follower' ? 'follows' :'followed by'}}</div>
                <div class="account-details">
                    <div class="name">{{account.myName}}</div>
                    <div class="account">{{`(${account.myAccount})`}}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProfilePicture from '../profile/ProfilePicture';
    export default {
        props: {
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
            friend: {
                type: Boolean,
                default: false
            },
            online: {
                type: Boolean,
                default: false
            },
        },
        components: {
            ProfilePicture,
        },
        methods: {
            clickedOtherUserAccount() {
                this.$emit('clickedOtherUserAccount',this.account)
            }
        },
    }
</script>

<style lang="scss" scoped>
@mixin text-overflow(){
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}   

    .other-user-account{
        width: 100%;
        display: inline-flex;
        padding: 5px;
        border-radius: 10px;
        cursor: pointer;
        position: relative;

        &:hover{
            box-shadow: 0 0 2px gray;
            transition: all .5s ease;
        }

        .online-alert{
            position: absolute;
            bottom: 80%;
            left: -10px;
            background: green;
            width: 12px;
            height: 12px;
            border-radius: 10px;
            border: 2px solid aquamarine;
        }

        .profile-picture{
            height: 40px;
            width: 40px;
        }

        .other-section{
            width: 80%;
            margin-left: 10px;

            .top{
                width: 100%;
                display: inline-flex;
                align-items: center;
                justify-content: space-between;
                color: gray;

                .name{
                    text-align: start;
                    width: 60%;
                    font-size: 12px;
                    font-weight: 500;
                    text-transform: capitalize;
                    @include text-overflow()
                }

                .account{
                    font-size: 10px;
                }
            }

            .bottom{
                display: flex;
                justify-content: space-between;
                align-items: center;
                
                .type{
                    width: 40%;
                    font-size: 10px;
                    text-align: start;
                    @include text-overflow()
                }
                
                .account-details{
                    display: inline-flex;
                    align-items: center;
                    justify-content: flex-end;
                    width: 60%;
                
                    .name{
                        font-size: 11px;
                        margin-right: 2px;
                        @include text-overflow();
                    }
                    
                    .account{
                        @include text-overflow();
                        font-size: 10px;
                    }
                }
            }
        }
    }
</style>