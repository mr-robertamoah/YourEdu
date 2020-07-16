<template>
    <div class="wrapper">
        <div class="top">
            <div class="profile-picture">
                <profile-picture></profile-picture>
            </div>
            <div class="comment-body">
                <div class="text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </div>
                <div class="media">

                </div>
            </div>
        </div>
        <div class="bottom">
            <post-button buttonText="like"></post-button>
            <number-of>
                1000 likes
            </number-of>
            
            <post-button buttonText="comment"></post-button>
        </div>
    </div>
</template>

<script>
import PostButton from '../components/PostButton'
import ProfilePicture from '../components/profile/ProfilePicture'
import NumberOf from '../components/NumberOf'

    export default {
        components: {
            NumberOf,
            ProfilePicture,
            PostButton,
        },
    }
</script>

<style lang="scss" scoped>
$profile-picture-width: 70px;
$comment-font-size: 16px;

    .wrapper{
        display: block;
        position: relative;
        border-left: 2px solid dimgrey;
        margin-top: 10px;
        background-color: rgba(105, 105, 105,.08);

        .top{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;

            .profile-picture{
                width: $profile-picture-width;
                height: $profile-picture-width;
            }

            .comment-body{
                min-width: 50%;
                max-width: 75%;
                text-align: justify;
                margin: 0 auto 0 auto;

                .text{
                    width: 100%;
                    font-size: $comment-font-size;
                }

                .media{
                    width: 90%;
                    margin: 5px 0 10px auto;
                }
            }
        }

        .bottom{
            display: flex;
            justify-content: center;
            align-items: center;
            border-right: 1px solid dimgrey;
            border-bottom: 1px solid dimgrey;
            margin: 5px 0 10px;

            & > div{
                margin: 10px 0 10px 10px;
            }

            & > button{
                margin: 10px 0 10px 5px;
            }
        }
    }

@media screen and (min-width: 800px) and (max-width: 1100px){
    $profile-picture-width: 60px;
    $comment-font-size: 16px;

    .wrapper{
        
        .top{

            .profile-picture{
                width: $profile-picture-width;
                height: $profile-picture-width;
            }

            .comment-body{

                .text{
                    font-size: $comment-font-size;
                }
            }
        }
    }

}

@media screen and (max-width: 800px){
    $profile-picture-width: 50px;
    $comment-font-size: 14px;

    .wrapper{
        
        .top{

            .profile-picture{
                width: $profile-picture-width;
                height: $profile-picture-width;
            }

            .comment-body{

                .text{
                    font-size: $comment-font-size;
                }
            }
        }
    }
}
</style>