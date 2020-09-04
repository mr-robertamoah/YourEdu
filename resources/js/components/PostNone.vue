<template>
    <div class="post-none-wrapper activity-no">
        <div class="loading" v-if="loading">
            <pulse-loader :loading="loading" :size="'10px'"></pulse-loader>
        </div>
        <div class="top">
            you cannot see any activities now because you have flagged this account...
        </div>
        <div class="bottom">
            <div>to see activities of this account,</div> 
            <div class="action"
                @click="clickedUnflag"
                :class="{actionActive:actionActive}"
            >unflag</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            loading: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                actionActive: false
            }
        },
        methods: {
            clickedUnflag() {
                this.actionActive = true
                this.$emit('clickedUnflag')
            }
        },
    }
</script>

<style lang="scss" scoped>
    .post-none-wrapper{
        min-height: 200px;
        border: 1px solid dimgrey;
        padding: 10px;
        min-height: 100px;
        text-align: center;
        margin: 10px auto;
        border-right: 2px solid rgb(105, 105, 105);

        .loading{
            width: 100%;
            margin: 5px;
            text-align: center;
        }

        .top{
            width: 80%;
            margin: 10px auto;
            font-weight: 450;
        }

        .bottom{
            font-size: 14px;

            .action{
                background-color: whitesmoke;
                color: gray;
                font-size: 12px;

                &:hover{
                    box-shadow: 0 0 3px gray;
                    transition: all .5s ease;
                }
            }
            
            .actionActive{
                box-shadow: 0 0 3px gray;
                transition: all .5s ease;
            }
        }
    }
</style>