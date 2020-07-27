<template>
    <div class="welcome-form">
        <div class="title">
            {{title}}
        </div>
        <div class="form-section">
           <slot name="input"></slot>
           <div class="buttons">
                <slot name="buttons"></slot>
           </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            title: {
                type: String,
                default: ''
            },
        },
        data() {
            return {

            }
        },
        components: {
        },
        methods: {

        },
    }
</script>

<style lang="scss" scoped>
$main-color: aliceblue;
$input-color: rgba(22, 233, 205, 1);


    .welcome-form{
        width: 70%;
        margin: 30px auto 10px;
        padding: 30px 10px 10px;

        .title{
            font-size: 20px;
            font-weight: 600;
            margin: 0 auto 20px;
        }

        .form-section{
            
            input,
            textarea{
                width: 90%;
                margin: 10px auto;
            }

            .form-input{
                border: none;
                border-bottom: 1px solid $input-color;


                &:active,
                &:focus{
                    box-shadow: none;
                    border: 1px solid $input-color;
                }
            }

            .buttons{
                margin: 20px auto;
                display: flex;
                justify-content: space-around;
                align-items: center;
            }

            .form-edit{
                margin: 10px auto;
            }

            .section{
                width: 100%;
                position: relative;
                display: block;

                .add{
                    position: absolute;
                    top: 0;
                    right: 0;
                    font-size: 16px;
                    color: $input-color;
                    cursor: pointer;
                }

                .minus{
                    position: absolute;
                    top: 0;
                    right: 0;
                    margin-top: 25px;
                    font-size: 16px;
                    color: red;
                    cursor: pointer;
                }

                .lower{
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
            }
        }
    }

@media screen and (max-width:800px) {
    .welcome-form{
        width: 80%;
    }
}

@media screen and (max-width:400px) {
    .welcome-form{
        
        .form-section{

            .buttons{
                margin: 20px auto;

                button{
                    margin: 10px auto;
                    display: block;
                }
            }
        }
    }
}
</style>