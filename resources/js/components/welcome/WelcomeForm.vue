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
            width: 100%;
            font-weight: 600;
            margin: 0 auto 20px;
            text-transform: capitalize;
            text-align: center;
        }

        .form-section{
            
            input,
            textarea{
                width: 90%;
                margin: 10px auto;
            }

            .upload{
                width: auto;
                padding: 5px;
                cursor: pointer;
                text-align: end;
                border-bottom: 1px solid $input-color;
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
                position: relative;
            }

            .form-edit{
                margin: 10px auto;
            }

            .ask-answers{
                width: 100%;
                display: inline-flex;
                align-items: center;
                font-size: 12px;
                margin-bottom: 10px;

                .no{
                    color: red;
                    padding: 5px;
                    font-size: 12px;
                    margin-left: 5px;
                    cursor: pointer;

                    &:hover{
                        box-shadow: 0 0 3px gray;
                        transition: all 1s ease;
                    }
                }

                .yes{
                    color: green;
                    box-shadow: 0 0 3px gray;
                    transition: all 1s ease;
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