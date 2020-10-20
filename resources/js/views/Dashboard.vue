<template>
    <div>
        <div class="loading" v-if="authenticatingUser">
            <pulse-loader :loading="loading"></pulse-loader>
        </div>
        <div class="main" v-else>
            <app-nav></app-nav>
            <side-bar></side-bar>
            <main-section></main-section>
        </div>
    </div>
</template>

<script>
import SideBar from '../components/dashboard/SideBar';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import MainSection from '../components/dashboard/MainSection';
import { mapGetters } from "vuex";

    export default {
        components: {
            MainSection,
            PulseLoader,
            SideBar,
        },
        data() {
            return {
                loading: false
            }
        },
        computed: {
            ...mapGetters(['authenticatingUser']),
        },
    }
</script>

<style lang="scss" scoped>

</style>