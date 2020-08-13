<template>
    <div class="answer-mark-wrapper">
        <div class="upper-section">
            <input type="number" autofocus 
                :max="inputMax" 
                :min="inputMin"
                v-model="inputScore"
                class="form-control"
                @keyup="checkInput"
                ref="score"
                pattern="[0-9]*"
                inputmode="numeric"
            >
        </div>
        <div class="middle-section">
            {{scoreOver}}
        </div>
        <div class="lower-section">
            <div class="mark" @click="clickedAction('mark')">mark</div>
            <div @click="clickedAction('cancel')">cancel</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            scoreOver: {
                type: String,
                default: '100'
            },
            inputMax: {
                type: Number,
                default: 100
            },
            inputMin: {
                type: Number,
                default: 0
            },
        },
        data() {
            return {
                inputScore: 0
            }
        },
        methods: {
            checkInput() {
                if (this.$refs.score.value < this.inputMin) {
                    this.$refs.score.value = this.inputMin
                } else if (this.$refs.score.value > this.inputMax) {
                    this.$refs.score.value = this.inputMax
                }
            },
            clickedAction(data){
                if (data === 'mark') {
                    this.$emit('answerMarkScore',this.$refs.score.value)
                } else if (data === 'cancel') {
                    this.$emit('hideAnswerMark')
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .answer-mark-wrapper{
        width: 100px;
        border-radius: 10px;
        box-shadow: 0 0 3px gray;

        .upper-section,
        .lower-section{
            width: 100%;
            border-radius: inherit;
        }

        .form-control{
            &:hover,
            &:active{
                border: none;
                box-shadow: none;
            }
        }

        .upper-section{

            input{
                width: inherit;
                height: inherit;
                border:none;
                border-radius: 10px 10px 0 0;

                &:hover,
                &:active{
                    border: none;
                    box-shadow: none;
                }
            }

            input::-webkit-outer-spin-button,
            input::-webkit-outer-spin-button{
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number]{
                -moz-appearance: textfield;
            }

        }

        .middle-section{
            border-bottom: 1px solid gray;
            border-top: 1px solid gray;
            padding: 10px;
            text-align: center;
            font-weight: 500;
        }

        .lower-section{
            font-size: 12px;
            padding: 5px;
            width: 100%;

            div{
                padding: 5px;
                text-align: center;
            }

            .mark{
                color: green;
                background: none;
            }

        }
    }
</style>