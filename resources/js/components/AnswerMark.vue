<template>
    <fade-down>
        <template slot="transition" v-if="show">
            <div class="answer-mark-wrapper z-30"
                @click.self="clickedAction('cancel')"
                :class="{'relative': ! computedClasses.includes('absolute')}"
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
                        <input type="number" 
                            autofocus 
                            :max="inputMax" 
                            :min="inputMin"
                            v-model="inputScore"
                            class="form-control"
                            ref="score"
                            pattern="[0-9]*"
                            inputmode="numeric"
                        >
                    </div>
                    <div class="middle-section text-center" v-if="!justRemark">
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
import MainTextarea from './MainTextarea.vue'
import FadeRightFast from './transitions/FadeRightFast.vue'
import FadeDown from './transitions/FadeDown.vue'

    export default {
        components: {
            FadeDown,
            FadeRightFast,
            MainTextarea,
        },
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
            value: {
                type: Object,
                default() {
                    return null
                }
            },
        },
        data() {
            return {
                inputScore: 0,
                inputRemark: '',
                showRemarks: false
            }
        },
        watch: {
            value: {
                immediate: true,
                handler(newValue) {
                    if (! newValue) {
                        return
                    }

                    this.inputScore = newValue.score
                    this.inputRemark = newValue.remark
                },
                deep: true
            },
            inputScore(newValue) {
                this.checkInput()
            },
        },
        computed: {
            computedClasses() {
                return this.$vnode.data.staticClass ? this.$vnode.data.staticClass : ''
            },
        },
        methods: {
            inputScoreFocused(){
                this.$refs.score.value = ''
            },
            checkInput() {
                if (this.inputScore < this.inputMin) {
                    this.inputScore = this.inputMin
                    return
                }
                
                if (this.inputScore > this.inputMax) {
                    this.inputScore = this.inputMax
                }
            },
            emitMark({score, remark}) {
                this.$emit('answerMarkScore',{
                    score: Number(score),
                    remark
                })
            },
            clickedAction(data){
                if (data === 'mark') {
                    this.emitMark({
                        score: Number(this.inputScore),
                        remark: this.inputRemark
                    })

                    return
                }
                
                if (data === 'cancel') {
                    this.inputScore = null
                    this.inputRemark = ''

                    this.emitMark({
                        score: null, 
                        remark: '',
                    })
                    
                    this.$emit('hideAnswerMark')
                    return
                }
                
                this.showRemarks = !this.showRemarks
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