<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                :long="true"
                @mainModalDisappear='closeModal'
                class="work-answering-form-wrapper"
            >
                <template slot="main">
                    <welcome-form
                        title="assessment name"
                        :titleBadge="true"
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
                                size="20px"
                                :loading="loading"
                            ></pulse-loader>
                            <template v-if="!computedHasNoData && !mainLoading">

                                <assessment-section-answering-form
                                    class="section-form"
                                    :assessment="assessment"
                                ></assessment-section-answering-form>
                            </template>
                            <template v-if="computedHasNoData && !mainLoading">
                                {{noDataMessage}}
                            </template>
                            
                        </template>

                        <template slot="buttons">
                            <post-button
                                :buttonText="buttonText"
                                buttonStyle='success'
                                @click="clickedCreate"
                                v-if="computedAnswered"
                            ></post-button>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import AssessmentSectionAnsweringForm from './AssessmentSectionAnsweringForm.vue';
import Alert from '../../mixins/Alert.mixin';
import PostButton from '../PostButton.vue';
import { mapActions, mapGetters } from 'vuex';
import { useRoute, useRouter } from 'vue-router';
    export default {
        components: {
            AssessmentSectionAnsweringForm,
            
            PostButton
        },
        mixins: [Alert],
        props: {
            show: {
                type: Boolean,
                default: true
            },
        },
        data() {
            return {
                buttonText: 'create',
                loading: false,
                mainLoading: false,
                assessment: null,
                work: null,
                noDataMessage: '',
            }
        },
        computed: {
            ...mapGetters(['getUser']),
            computedAnswered() {
                return false
            },
            computedHasNoData() {
                return this.noDataMessage.length
            },
        },
        mounted () {
            this.getWork()
        },
        methods: {
            ...mapActions(['dashboard/getWork']),
            closeModal() {
                this.$emit('closeWorkAnsweringForm')
                const router = useRouter()
                const route = useRoute()
                
                if (route.fullPath.includes('dashboard')) {
                    router.push({name: 'dashboard'})
                }
                
                if (route.fullPath.includes('profile')) {
                    router.push({name: 'profile'})
                }

                if (route.fullPath.includes('/work')) {
                    router.push({name: 'home'})
                }
            },
            async getWork() {
                const route = useRoute()

                let data,
                    response
                
                data = {
                    assessmentId: route.params.assessmentId
                }

                this.mainLoading = true
                response = await this['dashboard/getWork'](data)
                this.mainLoading = false

                if (response.status) {
                    this.assessment = response.assessment
                } else {
                    console.log('response :>> ', response);

                }
            },
            clickedCreate() {

            },
        },
    }
</script>

<style lang="scss" scoped>

    .work-answering-form-wrapper{

        .welcome-form{     
            position: relative;

            .loading{
                @include sticky-loader();
            }

            .section{
                @include form-section()
            }
        }
    }
</style>