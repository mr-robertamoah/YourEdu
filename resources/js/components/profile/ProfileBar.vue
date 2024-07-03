<template>
    <div class="profile-bar"
        @click.self="goToRoute"
        :class="{small:smallType,max:maxType,'just-name':justName}"
    >
        <div class="profile"
            v-if="!justName"
            @click="goToRoute">
            <profile-picture
                v-if="profile.url.length > 0"
            >
                <template slot="image">
                    <img :src="profile.url" alt="profile picture">
                </template>
            </profile-picture>
        </div>
        <div class="name"
            @click="goToRoute">
            {{profile.name}}
        </div>
        <div class="type"
            @click="goToRoute"
            v-if="!justName"
        >
            {{profile.account}}
        </div>
        <div class="actions" v-if="actions || !justName">
            <div class="loading" v-if="loading">
                <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
            </div>
        </div>
    </div>
</template>

<script>
import { useRouter } from 'vue-router';
import ProfilePicture from './ProfilePicture.vue'


    export default {
        props: {
            greenActionTitle: {
                type: String,
                default: ''
            },
            redActionTitle: {
                type: String,
                default: ''
            },
            id: {
                type: Number,
                default: null
            },
            smallType: {
                type: Boolean,
                default: false
            },
            justName: {
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
            routeName: {
                type: String,
                default: 'profile'
            },
            profile: {
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
            ProfilePicture,
            
        },
        methods: {
            goToRoute() {
                if (this.navigate) {
                    const router = useRouter()

                    let routeObject = {
                            name: this.routeName,
                            params: {
                                account: this.profile.account, 
                                accountId: `${this.profile.accountId}`
                            },
                    }
                    router.push(routeObject)
                } else {
                    this.$emit('clickedProfile',{
                        account: this.profile.account, 
                        accountId: this.profile.accountId,
                        extraData: this.extraData
                    })
                }
                this.$emit('clickedProfileBar')
            }
        },
        computed: {
            
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

    .profile-bar.just-name{

        .name{
            width: 100%;
            max-width: unset;
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