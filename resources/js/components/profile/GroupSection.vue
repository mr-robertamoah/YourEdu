<template>
    <div class="group-section-wrapper">
        <div class="top">
            <div class="heading" @click.self="getMedia">
                {{heading}}
            </div>
            <post-button
                v-if="showPrivate"
                :buttonText="computedPrivate"
                @click="clickedPrivate"
            ></post-button>
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
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
import PostButton from '../PostButton.vue';

    export default {
        props: {
            heading: {
                type: String,
                default: ''
            },
            showIcon: {
                type: Boolean,
                default: false
            },
            showPrivate: {
                type: Boolean,
                default: false
            },
        },
        components: {
            PostButton,
        },
        computed: {
            computedPrivate() {
                return this.showPrivate ? `private ${this.heading}` : ''
            }
        },
        methods: {
            clickedIcon() {
                this.$emit('clickedIcon',this.heading)
            },
            clickedPrivate(){
                this.$emit('getPrivateMedia',this.heading)
            },
            getMedia(){
                this.$emit('getMedia', this.heading)
            },
        },
    }
</script>

<style lang="scss" scoped>
    
    .group-section-wrapper{
        width: 100%;
        margin: 10px 0;
        background-color: rgba(105, 105, 105,.08);
        border-left: 2px solid dimgray;

        .top{
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
            text-transform: capitalize;
            background-color: rgba(105, 105, 105,.15);
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;

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
            min-height: 30px;
            width: 100%;
            padding: 10px;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            flex-wrap: wrap;
            font-size: 12px;

            .content{
                margin-right: 5px;
                width: 100px;
                height: 100px;
                overflow-y: hidden;
                margin-bottom: 5px;
                cursor: pointer;

                img{
                    margin: auto;
                    width: inherit;
                    height: auto;
                }
            }

            video,
            audio{
                width: 120px;
            }
        }
    }
</style>