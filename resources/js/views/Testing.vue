<template>
    <div class="testing" @click="clickedTesting">
        <create-discussion
            :show="show"
        ></create-discussion>
    </div>
</template>

<script>
import CreateDiscussion from '../components/forms/CreateDiscussion'

    export default {
        data() {
            return {
                show: false,
                value: ''
            }
        },
        components: {
            CreateDiscussion,
        },
        methods: {
            clickedTesting(){
                this.show = true
            },
        },
    }
</script>

<style lang="scss" scoped>
    .testing{
        width: 100%;
        height: 100vh;
    }
</style>