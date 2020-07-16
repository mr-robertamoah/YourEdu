<template>
    <div class="profile-bar"
        @click="goToRoute">
        <div class="profile">
            <profile-picture
                v-if="src"
            >
                <template slot="image">
                    <img :src="src" alt="profile picture">
                </template>
            </profile-picture>
        </div>
        <div class="name">
            {{name}}
        </div>
        <div class="type">
            {{type}}
        </div>
    </div>
</template>

<script>
import ProfilePicture from './ProfilePicture'
    export default {
        props: {
            src: {
                type: String,
                default: ''
            },
            name: {
                type: String,
                default: 'profile name'
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
            }
        },
        methods: {
            goToRoute() {
                let routeObject = {
                        name: this.routeName,
                        params: this.routeParams,
                }
                this.$router.push(routeObject)
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
        components: {
            ProfilePicture,
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
    }

@media screen and (max-width: 800px) {
    
    .profile-bar{
        font-size: 12px;
    }
}
</style>