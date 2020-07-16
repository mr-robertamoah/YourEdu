<template>
    <div>
        <div class="loading" v-if="authenticatingUser">
            loading...
        </div>
        <div class="main" v-else>
            <app-nav></app-nav>
            my dashboard
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

    export default {
        computed: {
            ...mapGetters(['authenticatingUser'])
        },
    }
</script>

<style lang="scss" scoped>

</style>