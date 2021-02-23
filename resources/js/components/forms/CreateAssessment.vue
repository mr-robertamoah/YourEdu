<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                @mainModalDisappear='closeModal'
                class="create-collaboration-modal-wrapper"
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
                            
                            <div class="section">Assessment Info</div>
                            <text-input
                                :bottomBorder="true"
                                placeholder="assessment name*"
                                v-model="data.name"
                                class="other-input"
                            ></text-input>
                            <text-textarea
                                :bottomBorder="true"
                                placeholder="description of the assessment"
                                v-model="data.description"
                                class="class-input"
                            ></text-textarea>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import TextTextarea from '../TextTextarea';
import TextInput from '../TextInput';
import Alert from '../../mixins/Alert.mixin';
    export default {
        components: {
            TextInput,
            TextTextarea,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                data: {
                    name: '',
                    description: '',
                },
                title: 'create assessment',
            }
        },
        mixins: [Alert],
        methods: {
            closeModal() {
                this.clearData()
                this.$emit('closeCreateTest')
            },
            clearData() {
                this.clearAlert()
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>