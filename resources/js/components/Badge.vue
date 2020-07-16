<template>
    <router-link :to="link">
        <div class="badge">
            {{text}}
        </div>
    </router-link>
</template>

<script>
    export default {
        props: {
            link: {
                type: String,
                default: '#'
            },
            text: {
                type: String,
                default: ''
            },
        },
    }
</script>

<style lang="scss" scoped>
$main-color: rgba(105, 105, 105, 1);

    .badge{
        width: auto;
        padding: 5px;
        background-color: lighten($color: dimgray, $amount: 50);
        color: darken($color: $main-color, $amount: 20);
        text-align: center;
        vertical-align: middle;
    }

    .badge:hover{
        box-shadow: 0 0 2px rgba(105, 105, 105, 1);
        transition: all is ease-in-out;
    }
</style>