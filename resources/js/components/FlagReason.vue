<template>
    <fade-right>
        <template slot="transition">
            <div class="flag-reason-wrapper"
                v-if="show"
                :class="{hasBackground:hasBackground}"
            >
                <div class="reason-top">
                    <span>any reason?</span>
                    <div class="reason-yes"
                        :class="{reasonYes:reasonYes}"
                        @click="clickedReason('yes')"
                    >yes</div>
                    <div class="reason-no"
                        :class="{reasonNo:reasonNo}"
                        @click="clickedReason('no')"
                    >no</div>
                    <div class="action"
                        @click="clickedAction(actionText)"
                        :class="{reasonReady:reasonReady,reasonActive:reasonActive}"
                    >
                        {{actionText}}
                    </div>
                </div>
                <div class="reason-bottom">
                    <fade-left-fast>
                        <template slot="transition" v-if="showInputReason">
                            <main-textarea
                                placeholder="your reason"
                                v-model="inputReason"
                            ></main-textarea>
                        </template>
                    </fade-left-fast>
                </div>
            </div>
        </template>
    </fade-right>
</template>

<script>
import MainTextarea from './MainTextarea.vue';
import FadeLeftFast from './transitions/FadeLeftFast.vue';
import FadeRight from './transitions/FadeRight.vue';

    export default {
        props: {
            show: {
                type: Boolean,
                default: true
            },
            hasBackground: {
                type: Boolean,
                default: false
            },
        },
        components: {
            FadeRight,
            FadeLeftFast,
            MainTextarea,
        },
        data() {
            return {
                actionText: 'cancel',
                inputReason: '',
                showInputReason: false,
                reasonYes: false,
                reasonNo: false,
                reasonActive: false,
                reasonReady: false,
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(value){
                    if (!value) {
                        this.inputReason = ''
                        this.reasonYes = false
                        this.actionText = 'cancel'
                        this.showInputReason = false
                        this.reasonYes = false
                        this.reasonNo = false
                        this.reasonActive = false
                        this.reasonReady = false
                    }
                }
            },
            inputReason(value){
                if (value.length) {
                    this.actionText = 'give'
                } else {
                    this.actionText = 'cancel'
                }
            }
        },
        computed: {
            computedReasonAction() {
                return this.data 
            }
        },
        methods: {
            clickedAction(data) {
                this.reasonActive = true
                this.reasonReady = false
                if (data === 'cancel') {
                    this.$emit('cancelFlagProcess')
                } else if (data === 'give') {
                    this.$emit('reasonGiven', this.inputReason)
                }
            },
            clickedReason(data) {
                this.reasonYes = false
                this.reasonNo = false
                if (data === 'yes') {
                    this.reasonYes = true
                    this.showInputReason = true
                    this.reasonReady = true
                } else if (data === 'no') {
                    this.reasonNo = true
                    this.$emit('continueFlagProcess')
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .flag-reason-wrapper{
        width: 100%;
        margin-top: 5px;
        
        .reason-top{
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            margin-bottom: 5px;

            div{
                font-size: 14px;
                padding: 3px;
                margin-left: 10px;
                cursor: pointer;
                border-radius: 5px;

                &:hover{
                    box-shadow: 0 0 2px;
                    transition: all .5s ease;
                }
            }

            .reasonActive,
            .reasonYes,
            .reasonNo{
                box-shadow: 0 0 2px;
                transition: all .5s ease;
            }

            .reasonYes{
                color: green;
            }

            .reasonReady{
                border-radius: 0;
                border-bottom: 2px solid green;
            }
        }

        .reason-bottom{
            background-color: white;
        }
    }

    .hasBackground{
        background-color: aliceblue;
    }
</style>