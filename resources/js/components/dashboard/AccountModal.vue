<template>
    <dashboard-modal
        :show="show"
        @closeDashboardModal="closeDashboardModal"
        class="dashboard-modal"
    >
        <template slot="main">

        </template>
    </dashboard-modal>
</template>

<script>
import DashboardModal from './DashboardModal.vue'
    export default {
        components: {
            DashboardModal,
        },
        props: {
            account: {
                type: Object,
                default(){
                    return null
                }
            },
            action: {
                type: String,
                default: ''
            },
            show: {
                type: Boolean,
                default: false
            },
        },
        methods: {
            closeDashboardModal() {
                this.$emit('closeAccountModal')
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>