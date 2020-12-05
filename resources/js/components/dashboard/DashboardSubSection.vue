<template>
    <div class="dashboard-sub-section-wrapper"
        :class="{full}"
    >
        <div class="top" @click="clickedIcon">
            <div class="icon">
                <font-awesome-icon 
                    :icon="['fa','plus']"
                    v-if="!full"
                ></font-awesome-icon>
                <font-awesome-icon 
                    :icon="['fa','minus']"
                    v-if="full"
                ></font-awesome-icon>
            </div>
            <div class="text">
                {{subText}}
            </div>
        </div>
        <div class="body" v-if="full">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            subText: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                full: false,
            }
        },
        methods: {
            clickedIcon() {
                this.full =  !this.full
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