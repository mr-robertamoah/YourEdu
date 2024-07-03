<template>
    <div class="dashboard-action-wrapper" 
        v-if="text.length || icon.length"
        :title="title"
        @click="clickedDashboardAction"
    >
        <div class="icon" v-if="!loading && icon.length">
            <font-awesome-icon :icon="['fa',icon]"></font-awesome-icon>
        </div>
        <div class="text" v-if="!loading">
            {{text}}
        </div>
        <pulse-loader
            v-if="loading"
            :loading="loading"
            size="8px"
        ></pulse-loader>
    </div>
</template>

<script>

    export default {
        components: {
            
        },
        props: {
            hasLoading: {
                type: Boolean,
                default: false
            },
            text: {
                type: String,
                default: ''
            },
            icon: {
                type: String,
                default: ''
            },
            title: {
                type: String,
                default: ''
            },
            data: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        data() {
            return {
                loading: false,
            }
        },
        methods: {
            clickedDashboardAction() {
                if (this.hasLoading) {
                    this.loading = true
                }
                this.$emit('click',{
                    data: this.data, text: this.text, icon: this.icon
                })
            }
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-action-wrapper{
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        background: cornflowerblue;
        padding: 5px;
        color: mintcream;
        border-radius: 5px;
        margin: 5px auto;
        cursor: pointer;
        justify-content: center;
        max-width: 300px;
        min-width: 80px;

        .icon{
            font-size: 16px;
            margin-right: 5px;
        }

        .text{
            font-size: 14px;
        }
    }

@media screen and (max-width:800px) {
    
    .dashboard-action-wrapper{

        .icon{
            font-size: 14px;
        }

        .text{
            font-size: 12px;
        }
    }
}
</style>