<template>
    <div class="wrapper">
        <div class="top">
            <slot name="top"></slot>
            <div class="icon" 
                @click="clickedIcon"
                v-if="showIcon"
            >
                <font-awesome-icon
                    :icon="['fa','plus']"
                ></font-awesome-icon>
            </div>
        </div>
        <div class="bottom">
            <div class="text">
                <slot name="text"></slot>
                <slot name="link"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            showIcon: {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: ''
            },
        },
        methods: {
            clickedIcon() {
                this.$emit('clickedIcon', this.title)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .wrapper{
        width: 100%;
        border-left: 2px solid rgb(105, 105, 105);
        background-color: rgba(105, 105, 105,.08);

        .top{
            width: 100%;
            font-size: 16px;
            border-bottom: 1px solid dimgray;
            font-weight: 600;
            text-transform: capitalize;
            padding: 10px;
            display: inline-flex;
            justify-content: space-between;
            align-items: center;

            .icon{
                font-size: 16px;
                padding: 5px;

                &:hover{
                    color: green;
                    box-shadow: 0 0 3px gray;
                    transition: all 1s ease ;
                }
            }
        }

        .bottom{
            width: 90%;
            margin: 10px auto;
            font-size: 14px;
            padding: 10px;
            min-height: 50px;
            position: relative;
        }
    }

@media screen and (max-width: 800px){
    .wrapper{

        .top{
            font-size: 14px;
        }

        .bottom{
            font-size: 12px;
        }
    }
}
</style>