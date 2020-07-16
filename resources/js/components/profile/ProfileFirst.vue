<template>
    <div class="first-section">
        <div class="profile-picture">
            <profile-picture
                @editProfile="$emit('editProfile')"
            >
                <template slot="image">
                    <img :src="computedUrl" alt="profile picture">
                </template>
            </profile-picture>
        </div>
        <div class="profile-about">
            <div class="border-style"></div>
            <div class="follows">
                <number-of>
                    followed by {{computedFollows}}
                </number-of>
                <number-of>
                    follows {{computedFollowings}}
                </number-of>
                <div class="action">
                    <special-button buttonText="follow"></special-button>
                </div>
            </div> 
            <div class="name">
                {{computedName}}
            </div>
            <div class="username">
                @{{computedUsername}}
            </div>
            <div class="as">
                <div>{{computedAccount}}</div>
                <!-- <post-button buttonText="change"></post-button> -->
            </div>
            <div class="verification">
                <div>
                    {{computedVerification}}
                </div>
            </div>   
            <div class="created-at">
                {{computedDate}}
            </div>            
        </div>
    </div>
</template>

<script>
import SpecialButton from '../SpecialButton'
import PostButton from '../PostButton'
import ProfilePicture from './ProfilePicture'
import {dates} from '../../services/helpers'
import NumberOf from '../NumberOf'
import { mapGetters } from 'vuex'

    export default {
        name: 'FirstSection',
        props: {

        },
        components: {
            SpecialButton,
            PostButton,
            ProfilePicture,
            NumberOf,
        },
        computed: {
            ...mapGetters(['profile/getProfile','profile/getAccount']),
            computedName(){
                return this['profile/getProfile'].name ? this['profile/getProfile'].name :
                    this['profile/getProfile'].owner_name ? this['profile/getProfile'].owner_name :
                    this['profile/getProfile'].user_full_name
            },
            computedUsername(){
                return this['profile/getProfile'].username
            },
            computedAccount(){
                return this['profile/getAccount']
            },
            computedFollowings(){
                return this['profile/getProfile'].followings
            },
            computedFollows(){
                return this['profile/getProfile'].follows
            },
            computedVerification(){
                return typeof this['profile/getProfile'].owner != undefined && this['profile/getProfile'].owner.verification.status === 'VERIFIED' ?
                    'verified' : 'not verified'
            },
            computedDate(){
                return dates.dateReadable(this['profile/getProfile'].created_at)
            },
            computedUrl(){
                return this['profile/getProfile'].url
            }
        },
    }
</script>

<style lang="scss" scoped>
    $profile-picture-main-width: 140px;
    $profile-picture-color: aquamarine;

    .first-section{
            
        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
            z-index: 1;
        }
        
        .profile-about {
            display: block;
            position: relative;
            background-color: inherit;
            min-height: 130px;
            width: 45%;
            margin: -50px 0 -60px 50px;
            padding: 10px;
            // border: 2px solid $profile-picture-color;
            font-size:16px;
            // border-left: none;

            .border-style{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 2px solid $profile-picture-color;
                border-radius: 0 0 50px 0;
                z-index: -1;
            }

            .follows{
                position: absolute;
                left: 100%;
                margin: -10px 0 0 75%;
                padding: 10px;
                width: 35%;
                text-align: end;
                border-top: 2px solid $profile-picture-color;
                border-right: 2px solid $profile-picture-color;

                div{
                    font-size: 14px;
                }

                .action{
                    font-weight: 700;
                    font-size: 15px;
                    text-align: center;
                    opacity: .7;
                    margin: 10px 0 0;
                }
            }

            .name{
                font-variant: small-caps;
                font-weight: 600;
                width: calc(100% - 110px);
                text-align: end;
                margin: 10px 0 0 auto;
                text-overflow: ellipsis;
                text-transform: uppercase;
                white-space: nowrap;
                overflow: hidden;
                font-size: 18px;
                font-style: italic;
            }

            .username{
                color: grey;
                width: auto;
                display: block;
                font-size: 12px;
                text-align: end;
            }

            .as{
                margin-bottom: 5px;
                font-weight: 550;
                text-transform: capitalize;
            }

            .verification{
                background-color: rgba(105, 105, 105, .2);
                padding: 5px;
                width: 80%;
                margin: 0 auto;
                text-align: center;
                border-radius: 5px;
                font-size: 14px;
            }

            .created-at{
                font-size: 12px;
                color: rgba(128, 128, 128, 0.452);
                padding: 5px;
            }
        }
    }

@media screen and (max-width:1100px) {
    $profile-picture-main-width: 120px;
        
    .first-section{
        
        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
        }

        .profile-about {
            width: 60%;
            margin: -50px 0 -40px 50px;

            .follows{
                margin: -10px 0 0 20%;
                width: 35%;

                div{
                    font-size: 12px;
                }
            }

            .name{
                width: calc(100% - 80px);
                font-size: 16px;
            }

            .username{
                font-size: 12px;
            }

            .as{
                font-size: 14px;
            }

            .verification{
                // width: 10%;
                font-size: 12px;
            }
        }
    }
}


@media screen and (max-width: 800px){
    $profile-picture-main-width: 100px;
        
    .first-section{
        
        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
        }

        .profile-about {
            font-size: 14px;
            width: 60%;
            margin: -50px 0 -25px 50px;

            .follows{
                margin: -10px 0 0 12%;
                width: 35%;

                div{
                    font-size: 11px;
                }
            }

            .name{
                width: calc(100% - 30px);
                font-size: 16px;
            }

            .username{
                font-size: 12px;
            }

            .as{
                font-size: 14px;
            }

            .verification{
                // width: 15%;
                font-size: 12px;
            }

            .created-at{
                font-size: 11px;
            }
        }
    }
}

@media screen and (max-width: 400px){
    $profile-picture-main-width: 100px;
        
    .first-section{
        
        .profile-picture {
            width: $profile-picture-main-width;
            height: $profile-picture-main-width;
        }

        .profile-about {
            font-size: 14px;
            width: 80%;
            margin: -50px auto -10px;

            .follows{
                width: 100%;
                position: relative;
                margin: -10px 0 0 5%;
                left: 0;
                border: none;

                div{
                    font-size: 11px;
                }

                .action{
                    text-align: right;
                }
            }

            .name{
                width: calc(100% - 30px);
                font-size: 16px;
            }

            .username{
                font-size: 12px;
            }

            .as{
                font-size: 14px;
            }

            .verification{
                font-size: 12px;
                width: 70%;
            }
        }
    }
}
</style>