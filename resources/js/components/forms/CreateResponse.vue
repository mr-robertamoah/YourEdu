<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                :long="true"
                @mainModalDisappear='closeModal'
                class="create-asessment-modal-wrapper"
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

                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import Alert from '../../mixins/Alert.mixin';
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                loading: false
            }
        },
        mixins: [Alert],
        methods: {
            closeModal() {
                
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>