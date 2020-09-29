<template>
    <div class="user-addons-wrapper" 
        :class="{userAddonWidth: showItems}"
        v-if="computedUser"
    >
        <div class="addon-button" @click="clickedButton">
            <font-awesome-icon :icon="['fa','ellipsis-v']"></font-awesome-icon>
        </div>
        <fade-right>
            <template slot="transition" v-if="showItems">
                <div class="main-addons-section" v-if="showItems">
                    <div class="addon-section-item"
                        :class="{itemActive: itemText === 'chat'}"
                        @click="itemText = 'chat'"
                    >chat</div>
                    <div class="addon-section-item"
                        :class="{itemActive: itemText === 'flags'}"
                        @click="itemText = 'flags'"
                    >flags</div>
                    <div class="addon-section-item"
                        :class="{itemActive: itemText === 'saves'}"
                        @click="itemText = 'saves'"
                    >saves</div>
                </div>
            </template>
        </fade-right>

        <just-fade>
            <template slot="transition" v-if="showItems && showChatModal">
                <chat-modal
                    @clickedClose="clickedClose"
                ></chat-modal>
            </template>
        </just-fade>
        <just-fade>
            <template slot="transition" v-if="showItems && showFlaggedModal">
                <flag-modal
                    :show="showFlaggedModal"
                    @clickedClose="clickedClose"
                ></flag-modal>
            </template>
        </just-fade>
        <just-fade>
            <template slot="transition" v-if="showItems && showSavedModal">
                <save-modal
                    :show="showSavedModal"
                    @clickedClose="clickedClose"
                ></save-modal>
            </template>
        </just-fade>
    </div>
</template>

<script>
import JustFade from './transitions/JustFade';
import FadeRight from './transitions/FadeRight';
import FlagModal from './FlagModal';
import ChatModal from './ChatModal';
import SaveModal from './SaveModal';
import { mapGetters } from 'vuex'
    export default {
        components: {
            SaveModal,
            ChatModal,
            FlagModal,
            FadeRight,
            JustFade,
        },
        computed: {
            ...mapGetters(['getUser','getProfiles']),
            computedUser() {
                return this.getUser && this.getProfiles ? true : false
            }
        },
        data() {
            return {
                showItems: false,
                showFlaggedModal: false,
                showSavedModal: false,
                showChatModal: false,
                itemText: '',
            }
        },
        watch: {
            itemText(newValue) {
                if (newValue === 'chat') {
                    this.showChatModal = true
                    this.showFlaggedModal = false
                    this.showSavedModal = false
                } else if (newValue === 'flags') {
                    this.showChatModal = false
                    this.showFlaggedModal = true
                    this.showSavedModal = false
                } else if (newValue === 'saves') {
                    this.showChatModal = false
                    this.showFlaggedModal = false
                    this.showSavedModal = true
                }
            }
        },
        methods: {
            clickedButton() {
                this.showItems = !this.showItems
            },
            clickedClose(data){
                console.log();
                if (data === 'saves') {
                    this.showSavedModal = false
                } else if (data === 'flags') {
                    this.showFlaggedModal = false
                } else if (data === 'chat') {
                    this.showChatModal = false
                }
                this.itemText = ''
            }
        },
    }
</script>

<style lang="scss" scoped>

    .user-addons-wrapper{
        position: fixed;
        bottom: 0;
        right: 0;
        z-index: 100000;
        display: inline-flex;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: nowrap;
        flex-direction: row-reverse;
        background: aliceblue;
        box-shadow: 0 0 3px grey;
        border-radius: 10px 0 0;
        
        .addon-button{
            font-size: 16px;
            font-weight: 500;
            color: gray;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }

        .main-addons-section{
            width: 100%;
            display: inline-flex;
            
            .addon-section-item{
                width: 30%;
                text-align: center;
                font-weight: 700;
                color: gray;
                padding: 5px;
                border-right: 2px solid gray;
                cursor: pointer;
                font-size: 12px;
                margin: 0 10px 0 0;

                &:hover{
                    box-shadow: 0 1px gray;
                    transition: all .5s ease;
                }                
            }

            .itemActive{
                box-shadow: 0 1px gray;
                transition: all .5s ease;
            }
        }
    }

    .userAddonWidth{
        width: 40%;
    }

@media screen and (max-width: 800px) {

    .userAddonWidth{
        width: 70%;
    }
}

@media screen and (max-width: 500px) {

    .userAddonWidth{
        width: 100%;
    }
}
</style>