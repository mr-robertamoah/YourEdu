<template>
    <div class="account-info-wrapper"
        @click="clickedAccount"
    >
        <profile-picture
            class="profile-picture"
            v-if="url.length"
        >
            <template slot="image">
                <img :src="url">
            </template>
        </profile-picture>
        <div class="name">
            {{name}}
        </div>
        <div class="type">
            {{type}}
        </div>
    </div>
</template>

<script>
import ProfilePicture from '../profile/ProfilePicture.vue';
    export default {
        components: {
            ProfilePicture,
        },
        props: {
            name: {
                type: String,
                default: ''
            },
            url: {
                type: String,
                default: ''
            },
            type: {
                type: String,
                default: ''
            },
            id: {
                type: Number,
                default: null
            },
        },
        methods: {
            clickedAccount() {
                this.$emit('click')
                this.$emit('clickedAccount',{
                    name: this.name,
                    id: this.id, 
                    type: this.type,
                })
            }
        },
    }
</script>

<style lang="scss" scoped>

    .account-info-wrapper{
        display: inline-flex;
        width: 100%;
        justify-content: space-around;
        align-items: center;
        padding: 5px;
        border-radius: 10px;
        box-shadow: 0 0 2px black;
        cursor: pointer;
        max-width: 350px;
        margin: 5px auto;

        &:hover{
            background: azure;
            transition: all .5s linear;
        }

        .profile-picture{
            min-width: 30px;
            width: 30px;
            height: 30px;
            margin-right: 5px;
        }

        .name{
            @include text-overflow();
            text-transform: capitalize;
        }

        .name,
        .type{
            width: fit-content;
        }

        .type{
            color: gray;
            font-size: 12px;
            text-transform: lowercase;
        }
    }
</style>