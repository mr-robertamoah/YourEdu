<template>
    <div class="dashboard-wrapper"> 
        <div class="loading" v-if="authenticatingUser">
            <pulse-loader :loading="authenticatingUser"></pulse-loader>
        </div>
        <div class="main" v-else>
            <app-nav></app-nav>
            <side-bar
                :minWidth="sideBarMinWidth"
                @barSmall="barSmallChange"
                @sidebarSelection="sidebarSelection"
                @clickedCreate="clickedCreate"
            ></side-bar>
            <main-section
                :barSmall="barSmall"
                :type="dashboardType"
                :account="dashboardAccount"
                :activeButton="mainActiveButton"
                @clickedAccount="sidebarSelection"
                @clickedMainSection="clickedMainSection"
                @clickedPostButton="clickedMainPostButton"
                @accountModal="openAccountModal"
            ></main-section>
        </div>

        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    @disappear="showSmallModal = false"
                    :show="showSmallModal"
                    class="small-modal"
                    :main="smallModalMain"
                    :title="smallModalTitle"
                >
                    <template slot="actions" v-if="!smallModalMain">
                        <post-button
                            buttonText="ok"
                            buttonStyle="success"
                            @click="clickedSmallModalButton"
                        ></post-button>
                    </template>
                    <template slot="other" v-if="smallModalMain">
                        <div class="item"
                            @click="clickedAccountType('learner')"
                            v-if="!isLearner"
                        >learner</div>
                        <div class="item"
                            @click="clickedAccountType('parent')"
                            v-if="!isParent"
                        >parent</div>
                        <div class="item"
                            @click="clickedAccountType('facilitator')"
                            v-if="!isFacilitator"
                        >facilitator</div>
                        <div class="item"
                            v-if="professionalsCount < 3"
                            @click="clickedAccountType('professional')"
                        >professional</div>
                        <div class="item"
                            @click="clickedAccountType('school')"
                            v-if="schoolsCount < 3"
                        >school</div>
                    </template>
                </small-modal>
            </template>
        </fade-up>

        <create-account
            :type="accountType"
            v-if="showCreateAccount"
            :show="showCreateAccount"
            :creator="computedCreator"
            @closeCreateAccount='closeCreateAccount'
        ></create-account>
        <create-academic-year
            :school="computedSchool"
            v-if="showCreateModal === 'academicYear'"
            :show="showCreateModal === 'academicYear'"
            @closeCreateAcademicYear="closeCreateSomething('academicYear')"
        ></create-academic-year>
        <edit-user
            :fireAction='editUserForm'
            :showForm="editUserForm"
            v-if="editUserForm"
            @mainModalDisappear='closeEditUser'
        ></edit-user>
        <account-modal
            :show="showAccountModal"
            v-if="showAccountModal"
            :account="accountModalData.account"
            :action="accountModalData.action"
            @closeAccountModal="showAccountModal = false"
        ></account-modal>

        <router-view></router-view>
    </div>
</template>

<script>
import SideBar from '../components/dashboard/SideBar.vue';
import PostButton from '../components/PostButton.vue';
import FadeUp from '../components/transitions/FadeUp.vue';
import EditUser from '../components/forms/EditUser.vue';
import CreateAccount from '../components/forms/CreateAccount.vue';
import CreateAcademicYear from '../components/forms/CreateAcademicYear.vue';
import CreateClass from '../components/forms/CreateClass.vue';
import CreateCourse from '../components/forms/CreateCourse.vue';

import MainSection from '../components/dashboard/MainSection.vue';
import AccountModal from '../components/dashboard/AccountModal.vue';
import { mapGetters } from "vuex";
import CreateExtracurriculum from '../components/forms/CreateExtracurriculum.vue';
import CreateLesson from '../components/forms/CreateLesson.vue';

    export default {
        components: {
            AccountModal,
            MainSection,
            
            CreateCourse,
            CreateClass,
            CreateAcademicYear,
            CreateAccount,
            EditUser,
            FadeUp,
            PostButton,
            SideBar,
            CreateExtracurriculum,
            CreateLesson,
        },
        data() {
            return {
                loading: false,
                sideBarMinWidth: false,
                showCreateAccount: false,
                showSmallModal: false,
                editUserForm: false,
                barSmall: true,
                smallModalMain: false,
                smallModalData: '',
                accountType: '',
                dashboardType: '',
                smallModalTitle: '',
                mainActiveButton: '',
                dashboardAccount: null,
                showAccountModal: false,
                accountModalData: null,
                showCreateExtracurriculumModal: false,
                showCreateModal: '',
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                
            });
        },
        watch: {
            showSmallModal(newValue) {
                if (!newValue) {
                    this.mainActiveButton = 'info'
                }
            }
        },
        computed: {
            ...mapGetters(['getUser','authenticatingUser','isLearner','isFacilitator',
                'isParent','professionalsCount','schoolsCount',
                'dashboard/getAccountDetails']),
            computedCreator(){
                return this.dashboardAccount ? {
                    account: this.dashboardAccount.account,
                    accountId: this.dashboardAccount.accountId
                } : {
                    account: 'user',
                    accountId: this.getUser.id
                }
            },
            computedSchool(){
                return this["dashboard/getAccountDetails"].account === 'school' ?
                    this["dashboard/getAccountDetails"] : null
            }
        },
        methods: {
            openAccountModal(data){
                this.accountModalData = data
                this.showAccountModal = true
            },
            barSmallChange(data) {
                this.barSmall = data
                this.sideBarMinWidth = false
            },
            clickedSmallModalButton(){
                if (this.dashboardType !== 'user') {
                    this.showCreateAccount = true
                }
                this.showSmallModal = false
            },
            clickedMainSection(){
                this.sideBarMinWidth = true
            },
            clickedCreate(data){
                this.dashboardAccount = null
                this.accountType = data
                this.showCreateAccount = true
            },
            clickedAccountType(data){
                this.accountType = data
                this.showSmallModal = false
                this.showCreateAccount = true
            },  
            clickedMainPostButton(data){
                if (data.data === 'edit user info') {
                    this.editUserForm = true
                } else if (data.data === 'create account') {
                    if (this.isLearner && this.isParent && this.isFacilitator && 
                        this.professionalsCount >= 3 &&
                        this.schoolsCount >= 3) {
                        
                        this.smallModalMain = false
                        this.smallModalTitle = 'you already have the maximum number of accounts'
                    } else {
                        this.smallModalMain = true
                        this.smallModalTitle = 'which account do you want to create?'
                    }
                    this.showSmallModal = true
                } else if (data.data === 'create user/account for others') {
                    this.smallModalTitle = 'create a new user and/or user account (learner,parent) for someone.'
                    this.showSmallModal = true
                } else if (data.data === 'user info') {
                    this.dashboardAccount = null
                    this.dashboardType = data.type
                } else if (data.data === 'create academic year') {
                    this.showCreateModal = 'academicYear'
                } else if (data.data === 'create class') {
                    this.showCreateModal = 'class'
                } else if (data.data === 'create course') {
                    this.showCreateModal = 'course'
                } else if (data.data === 'create lesson') {
                    this.showCreateModal = 'lesson'
                } else if (data.data === 'create extracurriculum') {
                    this.showCreateExtracurriculumModal = true
                }
                
            },
            sidebarSelection(data){
                this.dashboardType = data.type
                if (data.account) {
                    this.dashboardAccount = data.account
                } else {
                    this.dashboardAccount = null
                }
            },
            closeCreateSomething(data){
                if (data === 'class') {
                    this.mainActiveButton = 'info'
                } else if (data === 'course') {

                } else if (data === 'academicYear') {
                    
                } else if (data === 'lesson') {
                    
                } else if (data === 'extracurriculum') {
                    this.showCreateExtracurriculumModal = false
                }
                this.showCreateModal = ''
            },
            closeSmallModal(){
                this.showSmallModal = false
                this.mainActiveButton = 'info'
            },
            closeEditUser(){
                this.editUserForm = false
                this.mainActiveButton = 'info'
            },
            closeCreateAccount(){
                this.showCreateAccount = false
                this.mainActiveButton = 'info'
            },
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-wrapper{
        overflow-x: hidden;
        overflow-y: auto;

        .loading{
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }

    // .small-modal{

    //     .main{

    //         .item{
    //             width: fit-content;
    //             padding: 5px;
    //             margin: 5px auto;
    //             box-shadow: 0 0 2px grey;
    //             border-radius: 10px;
    //             font-size: 14px;
    //             background: azure;
    //             cursor: pointer;
    //         }
    //     }
    // }
</style>