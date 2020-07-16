<template>
    <div class="section">
        <div class="top">
            <div>
                Robert Amoah
            </div>
            <div class="created">
                12th April, 2020
            </div>
        </div>
        <div class="body">
            <div class="media">
                <img src="YPlogo.png">
            </div>
            <div class="text-short">
                <slot name="text"></slot>
                <div>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed luctus nibh ligula, eget sollicitudin lectus finibus sit amet. Aenean et finibus leo. Nam est est, interdum ut semper in, ullamcorper laoreet felis. Sed metus lacus, porttitor sed tortor et, iaculis facilisis nulla. Mauris posuere laoreet tellus id vehicula. Nam tristique condimentum sollicitudin. Proin blandit aliquet purus id gravida. Cras vel massa a sem interdum auctor a id mauris. Sed ornare dolor nec odio tincidunt bibendum. Donec laoreet eleifend velit at posuere. Sed sodales eros ac justo rutrum, a fermentum nisi ultrices. Donec mauris libero, consectetur ut tellus vitae, faucibus condimentum nibh. Ut vehicula vel ligula vitae fermentum. In turpis quam, feugiat ac ante sit amet, gravida bibendum nisl. Nulla euismod mi et consequat semper. Pellentesque rutrum eu orci non auctor
                </div>
                <div>see more</div>
            </div>
        </div>
        <div class="creator">
            <profile-picture></profile-picture>
        </div>
        <div class="bottom">
            <div class="main">
                <div class="reaction">
                    <post-button buttonText="like"></post-button>
                    <post-button buttonText="flag"></post-button>
                </div>
                <div class="add-comment">
                    <main-textarea textPlaceholder="have a comment?"></main-textarea>
                    <post-button buttonText="enter"></post-button>
                </div>
            </div>
            <div class="comment">
                <div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProfilePicture from '../components/profile/ProfilePicture'
import PostButton from '../components/PostButton'
import MainTextarea from '../components/MainTextarea'

    export default {
        components: {
            ProfilePicture,
            MainTextarea,
            PostButton,
        },
    }
</script>

<style lang="scss" scoped>

    .section{
        min-height: 200px;
        border: 1px solid dimgrey;
        padding: 10px;
        font-size: 1.5vw;
        margin: 10px auto;
        position: relative;
        border-right: 2px solid rgb(105, 105, 105);

        .top{
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;

            .created{
                font-weight: 400;
                color: rgba(150, 150, 150, 1);
                font-size: 50%;
                text-align: end;
            }
        }

        .creator{
            width: 90px;
            height: 90px;
            background-color: antiquewhite;
            border-radius: 100%;
            top: 40px;
            left: 20px;
            position: absolute;
        }

        .body{
            width: 75%;
            margin: 0 0 10px auto;
            min-height: 50px;
            text-align: justify;
            font-size: 1.2vw;

            .media{
                width: 100%;

                img{
                    width: inherit;
                }
            }

            .text{
                width: 90%;
                margin: 0 0 10px auto;
            }

            .text-short{
                height: 90;
                margin-bottom: 30px;

                & > div:first-child{
                    width: 90%;
                    margin: 0 auto;
                    height: 100px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                & > div:nth-child(2){
                    text-align: end;
                    font-weight: 400;
                    color: rgba(150, 150, 150, 1);
                    font-size: 1vw;
                    cursor: pointer;
                }
            }
        }

        .bottom{
            min-height: 50px;
            border-top: 1px solid dimgrey;
            margin: 10px 0 0;
            width: 100%;
            

            .main{
                width: 100%;

                .reaction{
                    display: flex;
                    justify-content: flex-start;
                    align-items: center;

                    & > button{
                        margin: 10px 5px 0 0;
                    }
                }

                .add-comment{
                    width: 70%;
                    text-align: end;
                    margin: 10px 0 0 auto;

                    & > button{
                        margin: 10px 0 0 ;
                    }
                }
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
    .section{
        font-size: 2.2vw;
        .top{

            .created{
                font-size: 1.8vw;
            }
        }

        .creator{
            width: 70px;
            height: 70px;
            top: 45px;
        }

        .body{
            font-size: 2vw;

            .text{
                width: 90%;
                margin: 0 0 10px auto;
            }

            .text-short{

                & > div:nth-child(2){
                    font-size: 1.8vw;
                }
            }
        }
    }
}


@media screen and (max-width:800px){
    .section{
        font-size: 2.4vw;
        .top{

            .created{
                font-size: 2vw;
            }
        }

        .creator{
            width: 70px;
            height: 70px;
        }

        .body{
            font-size: 2.2vw;

            .media{
                width: 100%;

                img{
                    width: inherit;
                }
            }

            .text{
                width: 90%;
                margin: 0 0 10px auto;
            }

            .text-short{

                & > div:nth-child(2){
                    font-size: 2vw;
                }
            }
        }
    }
}
</style>