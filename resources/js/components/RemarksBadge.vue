<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div class="remarks-badge-wrapper">
                <div class="close" @click="clickedCloseRemark">
                    <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                </div>
                <div class="mark-section"
                    v-for="(mark,index) in marks"
                    :key="index"
                >
                    <div class="top">
                        <div class="name">{{mark.markedby_name}}</div>
                        <div class="type">{{getMarkType(mark.markedby_type)}}</div>
                    </div>
                    <div class="bottom">
                        <div class="remark">{{`remark: ${mark.remark}`}}</div>
                        <div class="mark">{{`${mark.score}/${mark.scoreOver}`}}</div>
                    </div>
                </div>
                <div class="loading">
                    <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
                </div>
                <div class="show-more" 
                    v-if="!loading && computedShowMore"
                    @click="getMarks"
                >
                    <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                </div>
            </div>
        </template>
    </just-fade>
</template>

<script>
import { mapActions } from 'vuex'
import { strings } from '../services/helpers'

    export default {
        components: {
            
        },
        props: {
            answerId: {
                type: Number,
                default: 0
            },
            show: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                loading: false,
                marks: [],
                nextPage: 1
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.getMarks()
                    } else {
                        this.marks = []
                    }
                }
            }
        },
        computed: {
            computedShowMore(){
                return this.nextPage && this.nextPage > 1
            },
        },
        methods: {
            ...mapActions(['profile/getAnswerMarks']),
            getMarkType(markedby_type) {
                return strings.getAccount(markedby_type)
            },
            async getMarks() {
                this.loading = true
                let response

                response = await this['profile/getAnswerMarks']({
                    answerId: this.answerId,
                    nextPage: this.nextPage,
                })
                
                if (response.status) {
                    this.marks.push(...response.data)
                } else {
                    console.log('response :>> ', response);
                }
                
                this.loading = false 
            },
            clickedCloseRemark(){
                this.$emit('clickedCloseRemark')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .remarks-badge-wrapper{
        position: absolute;
        width: 100%;
        top: -10px;
        left: 0;
        max-height: 300px;
        overflow-y: auto;
        padding: 20px 5px 10px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 2px grey;

        .close{
            color: red;
            font-size: 14px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .mark-section{
            box-shadow: 0 0 2px grey;
            border-radius: 10px;
            margin-top: 10px;

            .top{
                width: 100%;
                display: inline-flex;
                justify-content: space-around;
                color: gray;
                align-items: center;

                .name{
                    font-size: 12px;
                    width: 65%;
                }

                .type{
                    font-size: 9px;
                }
            }

            .bottom{
                display: table;
                width: 100%;
                padding: 5px;
                font-size: 12px;

                .remark{
                    display: table-cell;
                    text-align: start;
                }

                .mark{
                    display: table-cell;
                    width: 20px;
                }
            }

            .show-more{
                font-size: 20px;
                width: fit-content;
                padding: 5px;
                cursor: pointer;
                margin: 10px auto;
            }
        }
    }
</style>