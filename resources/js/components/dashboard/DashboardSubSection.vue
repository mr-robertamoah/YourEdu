<template>
    <div class="dashboard-sub-section-wrapper"
        :class="{full}"
    >
        <div class="top" @click.self="clickedIcon">
            <div class="icon"
                @click="clickedIcon"
            >
                <font-awesome-icon 
                    :icon="['fa','plus']"
                    v-if="!full"
                ></font-awesome-icon>
                <font-awesome-icon 
                    :icon="['fa','minus']"
                    v-if="full"
                ></font-awesome-icon>
            </div>
            <div class="text"
                @click="clickedIcon"
            >
                {{subText}}
            </div>
            <dashboard-action-button
                text="view more"
                icon=""
                v-if="computedShowViewMore"
                @click="clickedDashboardActionButton"
                class="action-button"
            ></dashboard-action-button>
        </div>
        <div class="body" v-if="full">
            <slot name="body"></slot>
        </div>
        <div class="bottom" v-if="full">
            <slot name="bottom"></slot>
        </div>
    </div>
</template>

<script>
import DashboardActionButton from './DashboardActionButton.vue';
    export default {
        components: {
            DashboardActionButton,
        },
        props: {
            subText: {
                type: String,
                default: ''
            },
            inactive: {
                type: Boolean,
                default: false
            },
            hasViewMore: {
                type: Boolean,
                default: true
            },
            viewLength: { //minimum which determines if the button to view more should be available
                type: Number,
                default: 0
            },
            itemsLength: {
                type: Number,
                default: 1
            },
        },
        data() {
            return {
                full: false,
            }
        },
        computed: {
            computedShowViewMore() {
                return this.hasViewMore && this.full && this.itemsLength > this.viewLength
            }
        },
        methods: {
            clickedIcon() {
                if (!this.inactive) {
                    this.full =  !this.full
                }
            },
            clickedDashboardActionButton(data) {
                this.$emit('clickedDashboardActionButton',{
                    type: data.text,
                    subText: this.subText
                })
            }
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-sub-section-wrapper{
        padding: 10px;
        margin: 0 0 10px;
        transition: height .5s linear;
        background: transparent;

        .top{
            cursor: pointer;
            display: flex;
            align-items: center;
            color: gray;

            .icon{
                font-size: 18px;
                margin-right: 10px;
            }

            .text{
                font-size: 16px;
                text-transform: capitalize;
            }
        }

        .body{
            max-width: 600px;
            margin: 0 auto;
            padding: 10px;
            max-height: 500px;
            overflow-y: auto;
        }

        .bottom{
            margin-top: 10px;
        }
    }

    .dashboard-sub-section-wrapper.full{
        min-height: 100px;
        background: mintcream;
        
        .top{
            color: black;
        }
    }

@media screen and (max-width:800px) {
    
    .dashboard-sub-section-wrapper{

        .top{

            .action-button{
                margin: 0 5px 0 auto;
            }

            .icon{
                font-size: 16px;
            }

            .text{
                font-size: 14px;
            }
        }
    }
}
</style>