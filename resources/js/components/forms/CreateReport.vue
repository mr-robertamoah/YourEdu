<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                :long="true"
                @mainModalDisappear='closeModal'
                class="create-report-modal-wrapper"
            >
                <template slot="main">
                    <welcome-form
                        :title="title"
                        class="welcome-form"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="clearAlert"
                            ></auto-alert>
                            <pulse-loader 
                                class="loading"
                                :loading="loading"></pulse-loader>
                            
                            <div class="section">Report Info</div>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>

import ItemBadge from '../dashboard/ItemBadge.vue';
import Alert from '../../mixins/Alert.mixin';
    export default {
        components: {
            
            ItemBadge,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            edit: {
                type: Boolean,
                default: false
            },
        },
        mixins: [Alert],
    }
</script>

<style lang="scss" scoped>

</style>