<template>
    <div class="item-show-wrapper" v-if="data.name">
        <div class="top">
            <div class="name">{{data.name}}</div>
            <div class="account-type">{{computedAccountType}}</div>
        </div>
        <div class="lower">

            <div class="profile-picture">
                <profile-picture>
                    <template slot="image">
                        <img :src="data.url" >
                    </template>
                </profile-picture>
            </div>
            <div class="text">
                {{message(data)}}
            </div>
        </div>
        <div class="bottom"
            v-if="actionButtonText.length"
        >
            <div class="item-type">{{computedItemType}}</div>
            <action-button
                @click="clickedAction"
                :text="actionButtonText"
                :loading="actionLoading"
                :background="true"
                class="action-button"
            ></action-button>
        </div>
    </div>
</template>

<script>
import { strings } from '../services/helpers'
import ProfilePicture from './profile/ProfilePicture.vue'
import ActionButton from './ActionButton.vue'
import { mapGetters } from 'vuex'
    export default {
        props: {
            data: {
                type: Object,
                default() {
                    return {}
                }
            },
            actionButtonText: {
                type: String,
                default: ''
            },
        },
        components: {
            ActionButton,
            ProfilePicture,
        },
        data() {
            return {
                actionLoading: false
            }
        },
        computed: {
            ...mapGetters(['getUser']),
            computedItemType() {
                return this.data.hasOwnProperty('addedby_type') ? 
                    'post' : 
                    this.data.hasOwnProperty('answeredby_type') ? 
                    'answer' : 
                    this.data.hasOwnProperty('commentedby_type') ? 
                    'comment' : ''
            },
            computedAccountType() {
                return this.data.hasOwnProperty('addedby_type') ? 
                    strings.getAccount(this.data.addedby_type) : 
                    this.data.hasOwnProperty('answeredby_type') ? 
                    strings.getAccount(this.data.answeredby_type) : 
                    this.data.hasOwnProperty('commentedby_type') ? 
                    strings.getAccount(this.data.commentedby_type) : ''
            },
        },
        methods: {
            shortenText(text){
                return strings.trim(text, 200)
            },
            message(data){
                return data.poem ? this.shortenText(data.poem) : 
                    data.body ? this.shortenText(data.body) :
                    data.comment ? this.shortenText(data.comment) :
                    data.content ? this.shortenText(data.content) :
                    data.answer ? this.shortenText(data.answer) : ''
            },
            clickedAction(){
                this.actionLoading=true
                let data = {
                    item: this.computedItemType,
                    itemId: this.data.id
                }
                if (this.data.flags) {
                    data.flag = this.data.flags.filter(flag=>{
                        return flag.user_id === this.getUser.id
                    })[0]
                } else if (this.data.saves) {
                    data.save = this.data.saves.filter(save=>{
                        return save.user_id === this.getUser.id
                    })[0]
                }
                this.$emit('clickedAction', data)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .item-show-wrapper{

        .top{
            display: inline-flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;

            .name{
                font-size: 12px;
                font-weight: 500;
                text-transform: capitalize;
            }

            .account-type{
                font-size: 10px;
            }
        }

        .lower{
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            .profile-picture{
                width: 30px;
                height: 30px;
            }

            .text{
                width: 90%;
                font-size: 14px;
                background: rgb(128 128 128 / .2);
                padding: 10px;
                border-radius: 10px;
            }
        }

        .bottom{
            width: 100%;
            text-align: end;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px;

            .item-type{
                font-size: 10px;
                text-transform: lowercase;
            }

            .action-button{
                width: fit-content;
                text-align: center;
                padding: 5px;
                border-radius: 10px;
                color: white;
                background: gray;
                font-size: 10px;
                min-width: 30px;
                margin: 5px 0;

                &:hover{
                    box-shadow: 0 0 2px gray;
                }
            }
        }
    }
</style>