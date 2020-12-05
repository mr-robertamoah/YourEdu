<template>
    <div class="place-description">
        <div class="main">
            <slot name="body"></slot>
        </div>
        <div class="button">
            <slot name="button"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

    .place-description{
        display: block;
        padding: 10px 5px;
        border: 1px solid rebeccapurple;
        border-radius: 5px;
        background-color: azure;
        margin: 5px;

        .main{

            .image{
                display: block;
                width: 80%;
                margin: 5px auto;
                border-radius: none;

                img{
                    display: block;
                    width: 90%;    
                    border-radius: inherit;
                    margin: 0 auto 5px;
                }

                .caption{
                    width: 100%;
                    text-align: center;
                }
            }

            .section-body{
                font-size: 16px;
                padding: 10px;
                margin: 10px auto;
            }

            .question{
                font-weight: 500;
                font-size: 16px;
            }

            .answer{
                font-style: italic;
                font-size: 16px;
                margin: 10px auto 20px;
            }

        }

        .button{
            margin: 10px;
            position: relative;

            .profiles{
                position: absolute;
            }
        }
    }

@media screen and (max-width: 800px) {
    .place-description{

        .body,
        .question,
        .answer{
            font-size: 14px;
        }
    }
}
</style>