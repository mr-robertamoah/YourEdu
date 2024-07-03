<template>
    <dashboard-modal
        :show="show"
        @closeDashboardModal="closeDashboardModal"
        class="dashboard-modal"
    >
        <template slot="main">
            <div class="loading" v-if="loading">
                <pulse-loader :loading="loading"></pulse-loader>
            </div>
            <div class="title" v-if="!loading">account</div>
            <div class="main-info" v-if="!loading">
                <profile-picture class="profile-picture">
                    <template slot="image">
                        <img :src="accountDetails.url">
                    </template>
                </profile-picture>
                <div class="profile">
                    <div class="name">
                        {{accountDetails.name}}
                    </div>
                    <div class="type">
                        {{accountDetails.account}}
                    </div>
                </div>
            </div>
            <div class="title" v-if="!loading">stats</div>
            <div class="other-info" v-if="!loading">
                <div class="info-item">
                    {{`has got ${accountDetails.posts_count} posts`}}
                </div>
                <div class="info-item">
                    {{`has got ${accountDetails.discussions_count} discussions`}}
                </div>
                <div class="info-item">
                    {{`has got ${accountDetails.likes} likes`}}
                </div>
                <div class="info-item">
                    {{`has got ${accountDetails.points} points`}}
                </div>
            </div>
            <div class="main-section">
                <dashboard-sub-section
                    subText="wards"
                >
                    <template slot="body">

                    </template>
                </dashboard-sub-section>
            </div>
        </template>
    </dashboard-modal>
</template>

<script>
import { mapActions } from 'vuex'
import DashboardModal from './DashboardModal.vue'
import ProfilePicture from '../profile/ProfilePicture.vue'

    export default {
        components: { 
            
            ProfilePicture,
            DashboardModal,
        },
        props: {
            ward: {
                type: Object,
                default(){
                    return null
                }
            },
            show: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                loading: false,
                accountDetails: null
            }
        },
        watch: {
            show: {
                immediate: true,
                handler(newValue){
                    if (newValue) {
                        this.getSpecificAccountDetails()
                    }
                }
            }
        },
        methods: {
            ...mapActions(['dashboard/getSpecificAccountDetails',]),
            async getSpecificAccountDetails(account){
                let response,
                    data = {
                        account:'learner',
                        accountId: this.ward.accountId,
                    }
                this.loading = true

                response = await this['dashboard/getSpecificAccountDetails'](data)

                this.loading = false
                if (response.status) {
                    this.accountDetails = response.accountDetails
                } else {
                    console.log('response :>> ', response);
                }
            },
            closeDashboardModal(){
                this.$emit('closeWardModal')
            },
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-modal{
        padding: 20px 10px;

        .main-info{
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;

            .name{
                @include text-overflow();
                width: fit-content;
            }

            .type{
                font-size: 12px;
            }
        }

        .other-info{
            width: fit-content;
            margin: 0 auto;
            font-size: 14px;
            color: gray;
            font-style: italic;

            .info-item{

            }
        }

        .title{
            width: 100%;
            font-size: 12px;
            color: gray;
            text-align: end;
        }
    }
</style>