<template>
    <transition name="welcome-button">
        <button class="btn place" @click="clicked" :class="{active:activeClass}">
            {{buttonText}}
        </button>
    </transition>
</template>

<script>
    export default {
        props: {
            activeClass:{
                type: Boolean,
                default: false
            },
            buttonText: {
                type: String,
                default: 'button'
            },
        },
        data() {
            return {
                active: false
            }
        },
        created() {
            
            if (this.activeClass) {
                this.active = true
            }
        },
        methods: {
            clicked() {
                this.$emit('welcomeButtonClicked')
                this.active = !this.active
            }
        },
    }
</script>

<style lang="scss" scoped>

    .welcome-button-enter{
        transform: translateY(-10);
    }
    
    .welcome-button-enter{
        transition: transform 1s ease-in-out;
    }

    .place{
        font-weight: 700;
        font-size: 18px;
        padding: 5px;
        border: 1px solid rebeccapurple;
        box-shadow: -1px -1px 1px rebeccapurple;
        border-radius: 5px;
        background-color: azure;
        margin: 10px auto;
        text-align: center;
        opacity: .7;
        display: block;
        width: 100%;

        &:hover{
            opacity: 1;
            box-shadow: 1px 1px 1px rebeccapurple;
            transition: all .5s ease-in;
        }
    }

    .active{
        opacity: 1;
        box-shadow: 1px 1px 1px rebeccapurple, -1px -1px 1px rebeccapurple;
    }
</style>