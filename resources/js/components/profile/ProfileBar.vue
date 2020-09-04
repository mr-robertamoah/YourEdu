<template>
    <div class="profile-bar"
        @click.self="goToRoute"
        :class="{small:smallType,max:maxType}"
    >
        <div class="profile"
            @click="goToRoute">
            <profile-picture
                v-if="src.length > 0"
            >
                <template slot="image">
                    <img :src="src" alt="profile picture">
                </template>
            </profile-picture>
        </div>
        <div class="name"
            @click="goToRoute">
            {{name}}
        </div>
        <div class="type"
            @click="goToRoute">
            {{type}}
        </div>
        <div class="actions" v-if="actions">
            <div class="loading" v-if="loading">
                <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
            </div>
            <action-button
                @click="clickedAccept"
                :green="true"
                :title="greenActionTitle"
                v-if="!loading"
            >
                <template slot="icon">
                    <font-awesome-icon
                        :icon="['fa','check']"
                    ></font-awesome-icon>
                </template>
            </action-button>
            <action-button
                @click="clickedDecline"
                :red="true"
                :title="redActionTitle"
                v-if="!loading"
            >
                <template slot="icon">
                    <font-awesome-icon
                        :icon="['fa','times']"
                    ></font-awesome-icon>
                </template>
            </action-button>
        </div>
    </div>
</template>

<script>
import ProfilePicture from './ProfilePicture'
import ActionButton from '../ActionButton'
import PulseLoader from 'vue-spinner/src/PulseLoader'

    export default {
        props: {
            src: {
                type: String,
                default: ''
            },
            greenActionTitle: {
                type: String,
                default: ''
            },
            redActionTitle: {
                type: String,
                default: ''
            },
            name: {
                type: String,
                default: 'profile name'
            },
            id: {
                type: Number,
                default: null
            },
            smallType: {
                type: Boolean,
                default: false
            },
            loading: {
                type: Boolean,
                default: false
            },
            maxType: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: 'account type'
            },
            routeName: {
                type: String,
                default: 'profile'
            },
            routeParams: {
                type: Object,
                default(){
                    return {}
                }
            },
            extraData: {
                type: Object,
                default(){
                    return null
                }
            },
            navigate: {
                type: Boolean,
                default: true
            },
            actions: {
                type: Boolean,
                default: false
            },
        },
        components: {
            ActionButton,
            ProfilePicture,
            PulseLoader,
        },
        methods: {
            clickedDecline(){
                this.$emit('clickedAction',{
                    account: this.routeParams.account, 
                    accountId: this.routeParams.accountId,
                    requestId: this.id,
                    action: 'decline'
                })
                // this.$emit('clickedProfileBar')
            },
            clickedAccept(){
                this.$emit('clickedAction',{
                    account: this.routeParams.account, 
                    accountId: this.routeParams.accountId,
                    requestId: this.id,
                    action: 'accept'
                })
                // this.$emit('clickedProfileBar')
            },
            goToRoute() {
                if (this.navigate) {
                    let routeObject = {
                            name: this.routeName,
                            params: {
                                account: this.routeParams.account, 
                                accountId: `${this.routeParams.accountId}`
                            },
                    }
                    this.$router.push(routeObject)
                } else {
                    this.$emit('clickedProfile',{
                        account: this.routeParams.account, 
                        accountId: this.routeParams.accountId,
                        extraData: this.extraData
                    })
                }
                this.$emit('clickedProfileBar')
            }
        },
        computed: {
            computedRoute() {
                return {
                    name: this.routeName, 
                    params: this.routeParams 
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

    .profile-bar{   
        margin-bottom: 5px;
        width: 80%;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        box-shadow: 0 0 2px grey;
        background-color: aliceblue;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;

        &:hover{
            background-color: lighten($color: aliceblue, $amount: 40);
        }

        .name{
            max-width: 40%;
            text-align: center;
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-transform: capitalize;
            font-weight: 500;
        }

        .profile{
            width: 30px;
            height: 30px;
        }

        .type{
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 30%;
            text-align: center;
            white-space: nowrap;
            text-transform: capitalize;
        }

        .actions{
            width: 0;
        }
    }

    .small{
        padding: 5px;
        width: 100%;

        .profile{
            width: 0;
            height: 0;
        }
        
        .name{
            font-size: 11px;
            width: 60%;
        }

        .type{
            font-size: 10px;
            width: 35%;
        }

        .actions{
            width: 0;
        }
    }

    .max{
        padding: 5px;
        width: 100%;

        .profile{
            width: 0;
            height: 0;
        }
        
        .name{
            font-size: 11px;
            width: 60%;
        }

        .type{
            font-size: 10px;
            width: 35%;
        }

        .actions{
            min-width: 15%;
            display: inline-flex;
            justify-content: space-between;
        }
    }

@media screen and (max-width: 800px) {
    
    .profile-bar{
        font-size: 12px;
    }
}
</style>