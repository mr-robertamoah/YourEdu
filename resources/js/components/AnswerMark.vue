<template>
    <fade-down>
        <template slot="transition" v-if="show">
            <div class="answer-mark-wrapper"
                @click.self="clickedAction('cancel')"
            >
                <div class="remark-wrapper">
                    <fade-right-fast>
                        <template slot="transition" v-if="justRemark || showRemarks">
                                <main-textarea
                                    v-model="inputRemark"
                                    textPlaceholder="add your remarks"
                                ></main-textarea>
                        </template>
                    </fade-right-fast>
                </div>
                <div class="mark-wrapper">
                    <div class="upper-section" v-if="!justRemark">
                        <input type="number" autofocus 
                            :max="inputMax" 
                            :min="inputMin"
                            v-model="inputScore"
                            class="form-control"
                            @keyup="checkInput"
                            @focus="inputScoreFocused"
                            ref="score"
                            pattern="[0-9]*"
                            inputmode="numeric"
                        >
                    </div>
                    <div class="middle-section" v-if="!justRemark">
                        {{scoreOver}}
                    </div>
                    <div class="lower-section">
                        <div class="remarks" @click="clickedAction('remarks')"
                            v-if="!justRemark"
                        >remarks</div>
                        <div class="mark" @click="clickedAction('mark')">mark</div>
                        <div class="cancel" @click="clickedAction('cancel')">cancel</div>
                    </div>
                </div>
            </div>
        </template>
    </fade-down>
</template>

<script>
import MainTextarea from './MainTextarea'
import FadeRightFast from './transitions/FadeRightFast'
import FadeDown from './transitions/FadeDown'

    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            justRemark: {
                type: Boolean,
                default: false
            },
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
        components: {
            FadeDown,
            FadeRightFast,
            MainTextarea,
        },
        data() {
            return {
                inputScore: 0,
                inputRemark: '',
                showRemarks: false
            }
        },
        methods: {
            inputScoreFocused(){
                this.$refs.score.value = ''
            },
            checkInput() {
                if (this.$refs.score.value < this.inputMin) {
                    this.$refs.score.value = this.inputScore = this.inputMin
                } else if (this.$refs.score.value > this.inputMax) {
                    this.$refs.score.value = this.inputScore = this.inputMax
                }
            },
            clickedAction(data){
                if (data === 'mark') {
                    this.$emit('answerMarkScore',{
                        score: this.inputScore,
                        remark: this.inputRemark
                    })
                } else if (data === 'cancel') {
                    this.inputScore = this.inputMin
                    this.inputRemark = ''
                    this.$emit('hideAnswerMark')
                } else if (data === 'remarks') {
                    this.showRemarks = !this.showRemarks
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .answer-mark-wrapper{
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
        background-color: transparent;
        position: relative;
        z-index: 2;
        margin-top: 5px;

        .remark-wrapper{
            width: 75%;
            background-color: white;
        }

        .mark-wrapper{
            width: 25%;
            background-color: white;

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
                padding: 5px;
                text-align: start;
                font-weight: 500;
            }

            .lower-section{
                font-size: 12px;
                width: 100%;

                div{
                    padding: 5px;
                    text-align: center;
                    cursor: pointer;
                }

                .mark{
                    color: green;
                    background: none;
                }

                .remark{
                    color: gray;
                }

                .cancel{
                    color: red;
                }

            }
        }

    }
</style>