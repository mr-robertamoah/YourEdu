<template>
    <div>
        <div class="loading" v-if="authenticatingUser || getLoading">
            loading...
        </div>
        <div v-else>
            <app-nav
                :profileAccountId="profileAccountId"
                :profileAccount="profileAccount"
            ></app-nav>
            <div class="profile-wrapper">
                <div class="switch-buttons">
                    <div class="sub" v-if="computedSwitch">
                        <post-button
                            buttonText='edit'
                            :active='true'
                            @click="editProfile"
                        ></post-button>
                    </div>
                </div>
                <profile-first
                    @editProfile="editProfilePicture"
                    :showEditProfile='true'
                ></profile-first>
                <profile-second
                    @editProfile="showEditProfile = true"
                ></profile-second>
            </div>
        </div>

        <fade-up>
            <template slot="transition" v-if="showEditProfile">
                <edit-profile
                    @closeModal="showEditProfile = false"
                    :showForm='showEditProfile'
                ></edit-profile>
            </template>
        </fade-up>
    </div>
</template>

<script>
import PostButton from '../components/PostButton'
import EditProfile from '../components/forms/EditProfile'
import FadeUp from '../components/transitions/FadeUp'
import AppNav from '../components/Nav'
import MainList from '../components/MainList'
import SmallModal from '../components/SmallModal'
import ProfileFirst from '../components/profile/ProfileFirst'
import ProfileSecond from '../components/profile/ProfileSecond'
import { mapGetters, mapActions } from "vuex";

    export default {
        components: {
            AppNav,
            FadeUp,
            EditProfile,
            PostButton,
            SmallModal,
            MainList,
            ProfileFirst,
            ProfileSecond
        },
        computed: {
            ...mapGetters(['authenticatingUser','getUser','getLoading','profile/getProfile']),
            computedItemList(){
                return ['learner', 'parent', 'facilitator','schools','professionals']
            },
            computedSwitch(){
                // return true
                return this.getUser && this.getUser.id === this['profile/getProfile'].user_id ? true : false
            },
        },
        data() {
            return {
                showList: false,
                itemList: [],
                switchButtonText: 'switch account',
                showEditProfile: false,
                profileAccountId: '',
                profileAccount: '',
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                vm.profileAccountId = to.params.accountId
                vm.profileAccount = to.params.account
                vm.getData()
            });
        },
        beforeRouteUpdate(to, from, next) {
            this.profileAccountId = to.params.accountId
            this.profileAccount = to.params.account
            this.getData()
            next();
        },
        methods: {
            editProfile(){
                this.showEditProfile = true
            },
            editProfilePicture(){
                
            },
            switchClicked(){
                if (this.switchButtonText === 'switch account') {
                    this.switchButtonText = 'stop switching'
                    this.showList = true
                } else {
                    this.switchButtonText = 'switch account'
                    this.showList = false
                }
            },
            getData() {
                let account = this.profileAccount
                let accountId = this.profileAccountId

                this.profileGet({
                    account, accountId
                })
            },
            ...mapActions(['profileGet']),
        },
    }
</script>

<style lang="scss" scoped>
    $profile-picture-main-width: 150px;

    .profile-wrapper{
        position: relative;

        .switch-buttons{
            position: absolute;
            right: 0;
            padding: 5px;
            display: block;
            z-index: 1000;
            width: 40%;
            text-align: center;
        }

        .activity {
            
            .activity-main{
                .created,
                .body .text-short > div:nth-child(2){
                    font-size: 2.2vw;
                }
            }
        }
    }

@media screen and (min-width:800px) and (max-width:1100px){

}

// ....................................//
@media screen and (max-width: 800px){
    $profile-picture-main-width: 100px;

    .profile-wrapper{

        .switch-buttons{
            width: 50%;
        }
        
        .first-section{
            
            .profile-picture {
                width: $profile-picture-main-width;
                height: $profile-picture-main-width;
            }
        }
    }

    .activity {
        
        .activity-main{
            .created,
            .body .text-short > div:nth-child(2){
                font-size: 2.2vw;
            }
        }
    }
}

@media screen and (max-width:400px) {
    .profile-picture{

        .switch-buttons{
            width: 70%;
        }
    }
}
</style>