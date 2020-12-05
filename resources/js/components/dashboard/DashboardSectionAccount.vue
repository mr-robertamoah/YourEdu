<template>
    <div class="dashboard-section-account-wrapper" v-if="account">
        <div class="name">
            {{computedName}}
            <div class="username" v-if="computedUsername">
                {{computedUsername}}
            </div>
        </div>
        <div class="detail">
            <div class="title" v-if="computedTitle.length">
                {{computedTitle}}
            </div>
            <div class="actions">
                <dashboard-action-button
                    text="activities"
                    icon=""
                    :data="account"
                    v-if="type === 'admin' || type === 'user'"
                    @click="clickedDashboardActionButton"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`edit this ${type}'s information`"
                    text="edit"
                    icon="pencil-alt"
                    :data="account"
                    v-if="type === 'admin'|| this.type === 'admin'"
                    @click="clickedDashboardActionButton"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`remove this ${type}`"
                    text="remove"
                    icon="trash"
                    :data="account"
                    v-if="type === 'admin'"
                    @click="clickedDashboardActionButton"
                ></dashboard-action-button>
                <dashboard-action-button
                    :title="`ban this ${type}`"
                    text="ban"
                    icon="ban"
                    :data="account"
                    v-if="type === 'user'"
                    @click="clickedDashboardActionButton"
                ></dashboard-action-button>
            </div>
        </div>
        <div class="other-details" v-if="computedOtherDetails.length">
            {{computedOtherDetails}}
        </div>
    </div>
</template>

<script>
import { dates } from '../../services/helpers'
import DashboardActionButton from './DashboardActionButton'
    export default {
        components: {
            DashboardActionButton,
        },
        props: {
            type: {
                type: String,
                default: ''
            },
            account: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        computed: {
            computedTitle() {
                return this.account.title ? this.account.title : '' 
            },
            computedName(){
                return this.type === 'admin' ? `${this.account.name}` :
                    this.type === 'user' ? this.account.full_name : ''
            },
            computedUsername(){
                return this.type === 'admin' || this.type === 'user' ? ` 
                    (@${this.account.username})` : ''
            },
            computedOtherDetails() {
                let msg = ''
                if (this.type === 'admin') {
                    if (this.account.level) {
                        msg = `level: ${this.account.level} `
                    }
                    if (this.account.description) {
                        msg += `description: ${this.account.description} `
                    }
                } else if (this.type === 'user') {
                    if (this.account.gender) {
                        msg = `gender: ${this.account.gender} `
                    }
                    if (this.account.age) {
                        msg += `age: ${this.account.age} `
                    }
                    msg += `joined on: ${dates.dateReadable(this.account.created_at)} `
                }

                return msg
            },
        },
        methods: {
            clickedDashboardActionButton(data) {
                this.$emit('clickedDashboardActionButton',{type: this.type, buttonData: data})
            }
        },
    }
</script>

<style lang="scss" scoped>
@mixin text-overflow(){
    text-overflow: ellipsis;
    overflow: hidden;
    width: 100%;
    white-space: nowrap;
}
    .dashboard-section-account-wrapper{

        .name{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            font-size: 14px;
            text-transform: capitalize;
            @include text-overflow();

            .username{
                font-size: 12px;
                color: gray;
                text-transform: initial;
            }
        }

        .detail{
            padding: 5px;
            border-left: 2px solid gray;
            display: flex;
            width: 100%;
            justify-content: space-between;

            .title{
                text-transform: uppercase;
                font-size: 10px;
                color: gray;
                margin-right: 10px;
            }

            .actions{
                display: flex;
                // width: 100%;
                align-items: center;
                flex-wrap: wrap;
            }
        }

        .other-details{
            font-size: 10px;
            color: gray;
            text-transform: lowercase;
            font-style: italic;
        }
    }
</style>