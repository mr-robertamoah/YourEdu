<template>
    <div>
        <div class="loading" v-if="authenticatingUser">
            loading..
        </div>
        <div v-else>
            <app-nav></app-nav>
            <div class="welcome-wrapper">
                <div class="welcome-message">
                    <div class="first-section" v-if="newCreation">
                        yay! welcome
                        <div class="name">
                            {{ getUserUsername ? getUserUsername : '@newuser'}}
                        </div>
                    </div>
                    <div class="second-section" 
                        @mouseover="showEditBadge = true"
                        @mouseleave="showEditBadge = false"
                    >
                        <fade-left>
                            <template slot="transition">
                                <black-white-badge
                                    text="edit"
                                    v-if="showEditBadge"
                                    @click="editUser"
                                ></black-white-badge>
                            </template>
                        </fade-left>
                        <div class="name">
                            {{ getUser ? getUser.full_name : 'new user'}}
                        </div>
                        we hope you do enjoy this new experience of social education
                        <div class="special">
                            Note: Everything on this page deals the creation of your personal accounts. If you want more power
                            to do other things, then visit the dashboard
                        </div>
                    </div>
                </div>
                <div class="welcome-body">
                    <div class="welcome-places">
                        <div class="places-heading">
                            the locations you should know about
                        </div>
                        
                        <welcome-button @welcomeButtonClicked="home=!home" :activeClass="home" buttonText='home'>
                        </welcome-button>
                        <fade-in-out>
                            <template slot="transition">
                                <place-description v-if="home">
                                    <div slot="body" class="section-body">
                                        this is where the entire community of
                                        
                                        <div class="image">
                                            <img src="YPlogo.png">
                                            <span class="caption">learners</span>
                                        </div> 
                                        <div class="image">
                                            <img src="YPlogo.png">
                                            <span class="caption">facilitators</span>
                                        </div>
                                        
                                        <div class="image">
                                            <img src="YPlogo.png">
                                            <span class="caption">schools</span>
                                        </div> 
                                        <div class="image">
                                            <img src="YPlogo.png">
                                            <span class="caption">educational professionals</span>
                                        </div> will socially interact, "educationally..."
                                    </div>
                                </place-description>
                            </template>
                        </fade-in-out>
                            
                        <welcome-button @welcomeButtonClicked="dashboard=!dashboard" :activeClass="dashboard" buttonText='dashboard'>
                        </welcome-button>
                        <fade-in-out>
                            <template slot="transition">
                                <place-description v-if="dashboard">
                                    <div slot="body">
                                        <div class="section-body">
                                            this section is so personal to you.
                                            this is where you will get to add or access private information.
                                            you will only be able to access one
                                        </div>
                                    </div>
                                    <div slot="button">
                                        <post-button buttonText='my profile'></post-button>
                                    </div>
                                </place-description>
                            </template>
                        </fade-in-out>

                        
                        <welcome-button @welcomeButtonClicked="profile=!profile" :activeClass="profile" buttonText='profile'>
                        </welcome-button>
                        <fade-in-out>
                            <template slot="transition">
                                <place-description v-if="profile" :info='info'>
                                    <div slot="body">
                                        <div class="section-body">
                                            this is where you will get to show the world who you are and what your contributions are
                                            to this new community.

                                            note:'you can see your profile in entirety or as specific user types such as Learner,
                                            Facilitator, Professional, etc. People who will visit your profile will only be seeing the 
                                            profile of the specific user type in which they are interested'
                                        </div>
                                    </div>
                                    <div slot="button">
                                        <post-button buttonText='my dashboard'></post-button>
                                    </div>
                                    <template slot="info">
                                        
                                    </template>
                                </place-description>
                            </template>
                        </fade-in-out>

                        <div class="edit-user">
                            <post-button buttonText='edit user'
                                @click="editUser"
                            ></post-button>
                        </div>
                    </div>
                    <div class="welcome-who">
                        <div class="who-heading">
                            your role in this new community
                        </div>
                        <div class="create-section">
                            <div class="title">
                                creation of the various community members
                            </div>
                                <place-description>
                                    <template slot="body">
                                        <div class="question">
                                            who will I be?
                                        </div>
                                        <div class="answer">
                                            {{who}}
                                        </div>
                                        <div class="question">
                                            what can I do?
                                        </div>
                                        <div class="answer">
                                            {{what}}
                                        </div>
                                    </template>
                                    <div slot="button">
                                        <post-button :buttonText="become" @click="becomeClicked"></post-button>
                                    </div>
                                </place-description>
                        </div>
                        <div class="users">
                            <welcome-button 
                                v-if="!isLearner"
                                @welcomeButtonClicked="learner = !learner" :activeClass="learner" buttonText='learner'>
                            </welcome-button>
                            <welcome-button 
                                v-if="!isParent"
                                @welcomeButtonClicked="parent = !parent" :activeClass="parent" buttonText='parent'>
                            </welcome-button>
                            <welcome-button
                                v-if="!isFacilitator"
                                @welcomeButtonClicked="facilitator = !facilitator" :activeClass="facilitator" buttonText='facilitator'>
                            </welcome-button>
                            <welcome-button @welcomeButtonClicked="school = !school" :activeClass="school" buttonText='school'>
                            </welcome-button>
                            <welcome-button 
                                @welcomeButtonClicked="professional = !professional" :activeClass="professional" buttonText='professional'>
                            </welcome-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <fade-up>
            <template slot="transition">
                <main-modal :show='showModal' 
                    @mainModalAppear='modalAppear'
                    @mainModalDisappear='modalDisappear'>
                    <template slot='loading' v-if="modalLoading">
                        loading...
                    </template>
                    <template slot="main">
                        <welcome-form :title="title" v-if="!editUserForm">
                            <template slot="input">
                                <input type="text" class="form-control form-input" placeholder="name*" 
                                v-model="inputName">

                                <textarea class="form-control form-input" placeholder="description" 
                                    v-if="description" v-model="inputDescription"></textarea>

                                <main-list v-if="list" @listItemSelected='selection'
                                    :multiple='multiple'
                                    :itemList='itemList'
                                ></main-list>

                                <input type="text" class="form-control form-input" placeholder="other" 
                                    v-if="other" v-model="inputOther">
                            </template>
                            <template slot="buttons">
                                <post-button buttonText='create' buttonStyle='success'
                                    @click="clickedCreate"
                                ></post-button>
                            </template>
                        </welcome-form >

                        <!-- <welcome-form>

                        </welcome-form> -->
                    </template>
                </main-modal>
            </template>
        </fade-up>

        <edit-user
            :fireAction='editUserForm'
            :showForm="editUserForm"
            @mainModalDisappear='editUserForm = false'
        ></edit-user>
    </div>
</template>

<script>
import PlaceDescription from '../components/welcome/PlaceDescription'
import FadeInOut from '../components/transitions/FadeInOut'
import FadeUp from '../components/transitions/FadeUp'
import FadeLeft from '../components/transitions/FadeLeft'
import WelcomeButton from '../components/welcome/WelcomeButton'
import PostButton from '../components/PostButton'
import MainModal from '../components/MainModal'
import WelcomeForm from '../components/welcome/WelcomeForm'
import EditUser from '../components/forms/EditUser'
import MainList from '../components/MainList'
import BlackWhiteBadge from '../components/BlackWhiteBadge'
import { mapGetters } from 'vuex'
import { dates } from "../services/helpers";

    export default {
        components: {
            WelcomeButton,
            EditUser,
            FadeUp,
            FadeInOut,
            FadeLeft,
            PlaceDescription,
            PostButton,
            WelcomeForm,
            MainModal,
            BlackWhiteBadge,
            MainList,
        },
        data() {
            return {
                professionalRole: [
                    {name:'nanny',title:''},
                    {name:'trainer',title:''},
                    {name:'counselor',title:''},
                    {name:'other',title:''}
                ],
                schoolRole: [
                    {name:'traditional',title:''},
                    {name:'virtual',title:''}
                ],
                itemList: [],
                info: '',
                showModal: false,
                home: false,
                dashboard: false,
                profile: false,
                learner: false,
                professional: false,
                school: false,
                facilitator: false,
                parent: false,
                inputName: '',
                inputOther: '',
                inputDescription: '',
                who: '',
                what: '',
                title: '',
                formType: '',
                description: false,
                list: false,
                multiple: false,
                editUserForm: false,
                other: false,
                become: 'become learner',
                showEditBadge: false,
                modalLoading: false // for modal loading effect
            }
        },
        watch: {
            learner(newValue, oldValue) {
                if (newValue) {
                    this.parent = false
                    this.facilitator = false
                    this.school = false
                    this.professional = false
                    this.who = 'learner'
                    this.what = 'learner'
                    this.become = 'become learner'
                    this.info = ''
                }
            },
            parent(newValue, oldValue) {
                 if (newValue) {
                    this.learner = false
                    this.facilitator = false
                    this.school = false
                    this.professional = false
                    this.what = 'parent'
                    this.who = 'parent'
                    this.become = 'become a parent'
                    this.info = ''
                }
            },
            facilitator(newValue, oldValue) {
                 if (newValue) {
                    this.parent = false
                    this.learner = false
                    this.school = false
                    this.professional = false
                    this.what = 'facilitator'
                    this.who = 'facilitator'
                    this.become = 'become a facilitator'
                    this.info = ''
                }
            },
            school(newValue, oldValue) {
                 if (newValue) {
                    this.itemList = this.schoolRole
                    this.list = true
                    this.parent = false
                    this.facilitator = false
                    this.learner = false
                    this.professional = false
                    this.what = 'school'
                    this.who = 'school'
                    this.become = this.hasSchools ? 'create another school': 'own school'
                    this.info = this.hasSchools ? 'You already own school account(s). Note: You can only own a majority of three': ''
                }
            },
            professional(newValue, oldValue) {
                 if (newValue) {
                    this.itemList = this.professionalRole
                    this.list = true
                    this.description = true
                    this.parent = false
                    this.facilitator = false
                    this.school = false
                    this.learner = false
                    this.what = 'professional'
                    this.become = this.hasProfessionals ? 'create another professional': 'become a professional'
                    this.info = this.hasProfessionals ? 'You already have professional account(s). Note: You can only own a majority of three': ''
                }
            },
        },
        created () {
            this.learner = true
        },
        methods: {
            editUser(){
                this.editUserForm = true
            },
            clickedCreate(){

            },
            modalAppear(){
                // this.showModal = true
            },
            modalDisappear(){
                this.showModal=false
                this.inputName = ''
                this.inputOther = ''
                this.inputDescription = ''
                this.editUserForm = false
            },
            becomeClicked(buttonText){
                if (this.learner) {
                    this.formType = 'learner'
                } else if (this.parent) {
                    this.formType = 'parent'
                } else if (this.facilitator) {
                    this.formType = 'facilitator'
                } else if (this.school) {
                    this.formType = 'school'
                } else if (this.professional) {
                    this.formType = 'professional'
                }
                this.showModal = true
            },
            selection(data){
                console.log(data.name)
            },
        },
        computed: {
            ...mapGetters(['getUserUsername', 'getUser', 'getUserAge','isSuperadmin','isGroupadmin','isClassadmin','isSchooladmin',
                'isLearner', 'isParent', 'isFacilitator', 'hasProfessionals', 'hasSchools','authenticatingUser'
            ]),
            newCreation(){
                let today = new Date()
                if (this.getUser) {
                    let createdAt = new Date(this.getUser.created_at)
                    return dates.dateDiff(dates.toDate(createdAt),dates.toDate(today)) === 0 ? true : false
                }
                return false
            }
        },
    }
</script>

<style lang="scss" scoped>

$welcome-main-color: rebeccapurple;

    .welcome-wrapper{
        background-color: aliceblue;

        .welcome-message{
            display: block;
            margin: 10px auto 5%;
            text-align: center;
            position: relative;

            
            .first-section{
                background-color: aqua;
                min-height: 40px;
                width: 50%;
                margin: 10px auto;
                border-radius: 5px;
                text-align: center;
                font-size: 20px;
                position: relative;

                .name{
                    font-weight: 900;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    font-size: 16px;
                }
            }
            

            .second-section{
                background-color: aqua;
                min-height: 80px;
                width: 60%;
                margin: 10px auto;
                border-radius: 5px;
                padding: 0 10px;
                font-size: 16px;
                position: relative;

                .name{
                    width: 60%;
                    padding: 10px;
                    text-transform: capitalize;
                    font-weight: 500;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    margin: 0 auto;
                }

                .special{
                    width: 90%;
                    font-size: 14px;
                    font-style: italic;
                    text-align: justify;
                    padding: 10px;
                    margin: 10px auto;
                }
            }
        }

        .welcome-body{
            display: flex;
            justify-content: space-around;
            margin: 2% 0;

            .welcome-places,
            .welcome-who{
                display: block;
                max-width: 30%;
                text-align: center;
                font-size: 16px;

                .places-heading,
                .who-heading{
                    display: block;
                    color: $welcome-main-color;
                    font-weight: 800;
                    text-shadow: 0.5px 0.5px 0.5px aqua;
                    font-size: 18px;
                    font-variant: small-caps;
                    border-top: 2px solid $welcome-main-color;
                    border-left: 2px solid $welcome-main-color;
                    margin-bottom: 10px;
                    padding: 5px;
                }
            }

            .welcome-places{

                .edit-user{
                    width: 80%;
                    height: 100px;
                    margin: 10px auto;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            }

            .welcome-who{
                display: block;
                min-width: 60%;

                .who-heading{
                    text-align: right;
                    border-left: 0;
                    border-right: 2px solid $welcome-main-color;
                    padding-bottom: 10%;
                }

                .create-section{
                    min-height: 200px;
                    width: 90%;
                    margin: 10px auto;

                    .title{
                        font-weight: 600;
                        border-bottom: 1px solid $welcome-main-color;
                        margin: 10px 0 20px;
                    }
                }

                .users{
                    margin: 20px auto 10px;
                    width: 60%;
                }
            }
        }
    }


/* ........................................... */

@media screen and (max-width:800px){
    .welcome-wrapper {

        .welcome-message{
            font-size: 18px;

            .first-section{
                width: 80%;
            }

            .second-section{
                width: 90%;
            }
        }
       
        .welcome-body{
            margin: 2% auto;
            display: block;

            .welcome-places{
                max-width: 80%;
                font-size: 14px;
                margin: 20px auto 40px;

                .places-heading{
                    font-size: 16px;
                }
            }

            .welcome-who{
                font-size: 14px;
                max-width: 80%;
                margin: 0 auto 40px;

                .who-heading{
                    font-size: 16px;
                    border-right: 2px solid $welcome-main-color;
                    border-left: 0;
                }
            }

            .users{
                margin: 20px auto 10px;
                width: 60%;
            }
        }
    }
}

</style>