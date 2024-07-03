<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                :long="true"
                @mainModalDisappear='closeModal'
                class="request-details-modal"
            >
                <template slot="main">
                    <welcome-form
                        class="welcome-form"
                    >
                        <template slot="input">
                            <div class="small-msg no-data" v-if="computedNoDetails">
                                no details here...
                            </div>
                            <template v-if="!computedNoDetails">
                                <div class="small-msg" v-if="details.salaries">
                                    salaries attached to this request
                                </div>
                                <div class="section" v-if="details.salaries">
                                    <salary-badge
                                        v-for="(salary, index) in details.salaries"
                                        :key="index"
                                        :data="salary"
                                    ></salary-badge>
                                </div>
                                <div class="small-msg" v-if="details.commissions">
                                    commissions attached to this request
                                </div>
                                <div class="section" v-if="details.commissions">
                                    <commission-badge
                                        v-for="(commission, index) in details.commissions"
                                        :key="index"
                                        :data="commission"
                                    ></commission-badge>
                                </div>
                                <div class="small-msg" v-if="details.discounts">
                                    discounts attached to this request
                                </div>
                                <div class="section" v-if="details.discounts">
                                    <discount-badge
                                        v-for="(discount, index) in details.discounts"
                                        :key="index"
                                        :data="discount"
                                    ></discount-badge>
                                </div>
                                <div class="small-msg" v-if="details.fees">
                                    fees attached to this request
                                </div>
                                <div class="section" v-if="details.fees">
                                    <fee-badge
                                        v-for="(fee, index) in details.fees"
                                        :key="index"
                                        :data="fee"
                                    ></fee-badge>
                                </div>
                                <div class="small-msg" v-if="details.videos">
                                    videos attached to this request
                                </div>
                                <div class="section" v-if="details.videos">
                                    <video
                                        v-for="(video, index) in details.videos"
                                        :key="index"
                                        :src="video.url"
                                        controls
                                    ></video>
                                </div>
                                <div class="small-msg" v-if="details.audios">
                                    audios attached to this request
                                </div>
                                <div class="section" v-if="details.audios">
                                    <audio
                                        v-for="(audio, index) in details.audios"
                                        :key="index"
                                        :src="audio.url"
                                        controls
                                    ></audio>
                                </div>
                                <div class="small-msg" v-if="details.images">
                                    images attached to this request
                                </div>
                                <div class="section" v-if="details.images">
                                    <img
                                        v-for="(image, index) in details.images"
                                        :key="index"
                                        :src="image.url"
                                    />
                                </div>
                                <div class="small-msg" v-if="details.files">
                                    files attached to this request
                                </div>
                                <div class="section" v-if="details.files">
                                    <div
                                        v-for="(file, index) in details.files"
                                        :key="index"
                                    >
                                        <a :href="file.url" :download="file.name ? file.name : ''"></a>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import DiscountBadge from './DiscountBadge.vue';
import FeeBadge from './FeeBadge.vue';
import CommissionBadge from './CommissionBadge.vue';
import SalaryBadge from './SalaryBadge.vue';
    export default {
        components: {
            SalaryBadge,
            CommissionBadge,
            FeeBadge,
            DiscountBadge,
        },
        props: {
            details: {
                type: Object,
                default() {
                    return null
                }
            },
            show: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            computedNoDetails() {
                return Boolean(this.details)
            }
        },
        methods: {
            closeModal() {
                this.$emit('closeDetailsModal')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .request-details-modal{

        .no-data{
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>