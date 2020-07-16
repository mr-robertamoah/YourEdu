<template>
    <div class="search-output">
        <div class="profile-picture" v-if="profilePicture">
            <profile-picture>
                <slot name="image"></slot>
            </profile-picture>
        </div>
        <div class="other-section">
            <div class="main">
                <slot name="main"></slot>
            </div>
            <div class="other">
                <slot name="other"></slot>
            </div>
        </div>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture'

    export default {
        components: {
            ProfilePicture,
        },
        props: {
            profilePicture: {
                type: Boolean,
                default: false
            },
        },
    }
</script>

<style lang="scss" scoped>
$first-color: aliceblue;
$second-color: rebeccapurple;

    .search-output{
        width: 95%;
        margin: 5px auto;
        min-height: 60px;
        padding: 10px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-radius: 5px;
        border: 1px solid $second-color;
        background-color: $first-color;

        .profile-picture{
            padding: 15px;
            width: 30%;
        }

        .other-section{
            width: 60%;

            .name{
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 16px;
                font-weight: 500;
                text-transform: capitalize;
            }

            .other{
                font-size: 12px;
                font-weight: 350;
            }
        }
    }

@media screen and (max-width: 800px) {
    .search-output{

        .other-section{

            .name{
                font-size: 14px;
            }

            .other{
                font-size: 11px;
            }
        }
    }
}
</style>