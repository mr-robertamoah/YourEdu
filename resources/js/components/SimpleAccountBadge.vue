<template>
    <div 
        v-if="account" 
        class="simple-account-wrapper"
        @click.self="clickedAccount"
    >
        <div class="top"
            @click="clickedAccount">
            <profile-picture
                class="profile-picture"
            >
                <template slot="image">
                    <img :src="account.url">
                </template>
            </profile-picture>
            <div class="account-type">
                {{account.account}}
            </div>
        </div>
        <div class="name"
            @click="clickedAccount">
            {{account.name}}
        </div>
        <div class="details" 
            @click="clickedAccount"
            v-if="computedDetails.length"
        >
            {{computedDetails}}
        </div>
        <div class="actions" v-if="actions.length">
            <dashboard-action-button
                v-for="(action, index) in actions"
                :key="index"
                :text="action"
                @click="clickedDashboardAction"
            ></dashboard-action-button>
        </div>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture.vue';
import DashboardActionButton from './dashboard/DashboardActionButton.vue';
    export default {
        components: {
            ProfilePicture,
            DashboardActionButton,
        },
        props: {
            account: {
                type: Object,
                default() {
                    return null
                }
            },
            actions: {
                type: Array,
                default() {
                    return []
                }
            },
            detailKeys: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        computed: {
            computedDetails() {
                let details = ''
                this.detailKeys.forEach(key=>{
                    details += this.getDetailFromKey(key)
                })
                return details
            }
        },
        methods: {
            clickedAccount() {
                this.$emit('clickedAccount',this.account)
            },
            clickedDashboardAction(data) {
                this.$emit('clickedDashboardAction',{
                    account: this.account, action: data.text})
            },
            getDetailFromKey(key) {
                return key.length && 
                    this.account.hasOwnProperty(key) ?
                    `${key}: ${this.account[key]}\n` : ''
            },
        },
    }
</script>

<style lang="scss" scoped>

    .simple-account-wrapper{
        cursor: pointer;
        max-width: 150px;
        border: 1px solid gray;
        border-radius: 5px;

        .top{
            display: flex;
            width: 100%;
            padding: 5px;

            .profile-picture{
                width: 60px;
                height: 60px;
                min-width: 60px;
                margin-right: 10px;
            }

            .account-type{
                font-size: 12px;
                color: gray;
                width: 100%;
                text-align: end;
            }
        }

        .name{
            padding: 10px;
            font-size: 14px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .details{
            font-size: 12px;
            color: gray;
            padding: 5px;
            text-transform: lowercase;
        }
    }
</style>