<template>
    <dashboard-item-badge
        :show="true"
    ></dashboard-item-badge>
</template>

<script>
import DashboardItemBadge from '../components/dashboard/DashboardItemBadge'

    export default {
        data() {
            return {
                show: false,
                value: ''
            }
        },
        components: {
            DashboardItemBadge,
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