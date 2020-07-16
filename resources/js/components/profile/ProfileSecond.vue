<template>
    <div class="second-section">
        <div class="top">
            <div class="section user" :class="{'section-active': activateUserClass}"
                @click="activateUserSection"
                @mouseover="showEdit = true"
                @mouseleave="showEdit = false"
            >
                <font-awesome-icon :icon="['fa','user-circle']"></font-awesome-icon>
                <div class="edit">
                    <fade-left>
                        <template slot="transition" v-if="showEdit">
                            <black-white-badge
                                text="edit"
                                @click="editProfile"
                            ></black-white-badge>
                        </template>
                    </fade-left>
                </div>
            </div>
            <div class="section activity" :class="{'section-active': activateActivityClass}"
                @click="activateActivitySection"
            >
                <font-awesome-icon :icon="['fa','exclamation-circle']"></font-awesome-icon>
            </div>
        </div>
        <div class="bottom">
            <div class="user" 
                v-if="activitySection || activateUser"
                @mouseover="showEdit = true"
                @mouseleave="showEdit = false"
            > 
                <div>
                    <profile-detail v-if="computedCompany">
                        <div slot="top">
                            Company
                        </div>
                        <div slot="text">
                            {{computedCompany}}
                        </div>
                    </profile-detail>
                    <profile-detail v-if="computedAbout">
                        <div slot="top">
                            About
                        </div>
                        <div slot="text">
                            {{computedAbout}}
                        </div>
                    </profile-detail>
                    <profile-detail v-if="computedInterests">
                        <div slot="top">
                            interests
                        </div>
                        <div slot="text">
                            {{computedInterests}}
                        </div>
                    </profile-detail>
                    <profile-detail v-if="computedOccupation">
                        <div slot="top">
                            occupation
                        </div>
                        <div slot="text">
                            {{computedOccupation}}
                        </div>
                    </profile-detail>
                </div>
                <group-section>
                    <template slot="body">
                        <detail-showcase></detail-showcase>
                        <detail-showcase></detail-showcase>
                        <detail-showcase></detail-showcase>
                        <detail-showcase></detail-showcase>
                    </template>
                </group-section>
            </div>
            <div class="activity" v-if="activitySection || activateActivity">
                <post-create></post-create>
                <post-show></post-show>
                <post-none></post-none>
            </div>
        </div>
    </div>
</template>

<script>
import PostButton from '../PostButton'
import Badge from '../Badge'
import DetailShowcase from './DetailShowcase'
import ProfilePicture from './ProfilePicture'
import ProfileDetail from './ProfileDetail'
import MainTextarea from '../MainTextarea'
import FadeLeft from '../transitions/FadeLeft'
import BlackWhiteBadge from '../BlackWhiteBadge'
import PostCreate from '../PostCreate'
import PostShow from '../PostShow'
import PostNone from '../PostNone'
import GroupSection from './GroupSection'
import { mapGetters } from 'vuex'

    export default {
        name: 'SecondSection',
        components: {
            Badge,
            DetailShowcase,
            PostButton,
            GroupSection,
            PostNone,
            PostShow,
            PostCreate,
            BlackWhiteBadge,
            FadeLeft,
            MainTextarea,
            ProfileDetail,
            ProfilePicture,
        },
        data() {
            return {
                activateUserClass: false,
                activateActivityClass: false,
                activateUser: false,
                activateActivity: false,
                activitySection: false,
                showEdit: false,
            }
        },
        computed: {
            ...mapGetters(['profile/getProfile','profile/getAccount',]),
            computedAbout() {
                return this['profile/getProfile'].about ? this['profile/getProfile'].about : 'nothing yet'
            },
            computedCompany() {
                return this['profile/getProfile'].company ? this['profile/getProfile'].company : 'nothing yet'
            },
            computedInterests() {
                return this['profile/getProfile'].interests ? this['profile/getProfile'].interests : 'nothing yet'
            },
            computedOccupation() {
                return this['profile/getProfile'].occupation ? this['profile/getProfile'].occupation : 'nothing yet'
            },
            computedLocation() {
                return this['profile/getProfile'].hasOwnProperty('location') &&
                    this['profile/getProfile'].location != null ? 
                    this['profile/getProfile'].location : 'nothing yet'
            },
            computedPhoneNumbers() {
                return this['profile/getProfile'].hasOwnProperty('phoneNumbers') &&
                    this['profile/getProfile'].phoneNumbers != null ? 
                    this['profile/getProfile'].phoneNumbers : []
            },
            computedSubjects() {
                return this['profile/getProfile'].hasOwnProperty('subjects') &&
                    this['profile/getProfile'].subjects != null ? 
                    this['profile/getProfile'].subjects : []
            },
            computedGrades() {
                return this['profile/getProfile'].hasOwnProperty('grades') &&
                    this['profile/getProfile'].grades != null ? 
                    this['profile/getProfile'].grades : []
            },
            computedSchools() {
                return this['profile/getProfile'].hasOwnProperty('schools') &&
                    this['profile/getProfile'].schools != null ? 
                    this['profile/getProfile'].schools : []
            },
            computedGroups() {
                return this['profile/getProfile'].hasOwnProperty('groups') &&
                    this['profile/getProfile'].groups != null ? 
                    this['profile/getProfile'].groups : []
            },
            computedClasses() {
                return this['profile/getProfile'].hasOwnProperty('classes') &&
                    this['profile/getProfile'].classes != null ? 
                    this['profile/getProfile'].classes : []
            },
            computedCurricula() {
                return this['profile/getProfile'].hasOwnProperty('curricula') &&
                    this['profile/getProfile'].curricula != null ? 
                    this['profile/getProfile'].curricula : []
            },
            computedExtracurriculums() {
                return this['profile/getProfile'].hasOwnProperty('extracurriculums') &&
                    this['profile/getProfile'].extracurriculums != null ? 
                    this['profile/getProfile'].extracurriculums : []
            },

        },
        methods: {
            editProfile(){
                this.$emit('editProfile')
            },
            activateUserSection(){
                if (window.innerHeight <= 1100) {
                    this.activateUser=true
                    this.activateUserClass=true
                    this.activateActivity=false
                    this.activateActivityClass=false
                }else{
                    this.activateUser=true
                    this.activateActivity=true
                    this.activateUserClass=true
                    this.activateActivityClass=true
                }
            },
            activateActivitySection(){
                if (window.innerHeight <= 1100) {
                    this.activateUser=false
                    this.activateUserClass=false
                    this.activateActivity=true
                    this.activateActivityClass=true
                }else{
                    this.activateUser=true
                    this.activateActivity=true
                    this.activateUserClass=true
                    this.activateActivityClass=true
                }
            },
            resizeAction(){
                if (window.innerWidth > 1100) {
                    this.activitySection=true
                }else{
                    this.activitySection=false
                    if (this.activateUser===true) {
                        this.activateUser=true
                        this.activateActivity=false
                    } else {
                        this.activateUser=false
                        this.activateActivity=true
                    }
                        
                }
            }
        },
        created () {
            if (window.innerWidth <= 1100) {
                this.activateUser=true
                this.activateUserClass=true
                this.activateActivity=false
                this.activateActivityClass=false
            } else{
                this.activitySection=true
            }

            window.addEventListener('resize',this.resizeAction)
        },
    }
</script>

<style lang="scss" scoped>

    $profile-main-color: rgba(127, 255, 212, .9);
    $profile-picture-main-width: 140px;
    $profile-picture-color: aquamarine;
    $profile-font-main: 1.5vw;
    $profile-font-increase3: $profile-font-main + ($profile-font-main * 0.333);
    $profile-font-decrease3: $profile-font-main - ($profile-font-main * 0.333);

    .second-section{
        background-color: aliceblue;
        position: relative;
        top: 10vw;
        width: 100%;
            
        .top{
            display: flex;
            justify-content: center;
            align-items: flex-end;
            text-align: center;

            .section{
                width: 50%;
                padding: 10px 0;
                cursor: pointer;
                border-bottom: 2px solid $profile-main-color;
                font-size: 20px;
                position: relative;

                .edit{
                    position: absolute;
                    width: 100%;
                    top: 2px;
                    left: 0;
                    height: 100%;
                }
            }

            .section-active{
                border-bottom: 2px solid $profile-main-color;
            }
        }

        .bottom{
            position: relative;
            min-height: 20px;
            margin: 0 20px;
            background-color: rgba(127, 255, 212, .1);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            .user,
            .activity{
                width: 50%;
                margin: 10px 0;
                min-height: 50vh;
                padding: 10px
            }
        }
    }


@media screen and (min-width:800px) and (max-width:1100px) {
    $profile-picture-main-width: 120px;
    $profile-font-main: 2.5vw;
    $profile-font-increase3: $profile-font-main + ($profile-font-main * 0.333);
    $profile-font-decrease3: $profile-font-main - ($profile-font-main * 0.333);

        
    .second-section{
        
            
        .top{
            

            .user:hover,
            .activity:hover{
                background-color: $profile-main-color;
                transition: all 1s ease
            }

            .section{
                border: none;
            }

            .section-active{
                border-bottom: 2px solid $profile-main-color;
            }
        }

        .bottom{
            display: block;
            

            .user,
            .activity{
               width: 75%;
                margin: 10px auto;
                min-height: 50vh;
                padding: 10px
            }
        }
    }
}


@media screen and (max-width: 800px){
    $profile-picture-main-width: 100px;
    $profile-font-main: 3vw;
    $profile-font-increase3: $profile-font-main + ($profile-font-main * 0.333);
    $profile-font-decrease3: $profile-font-main - ($profile-font-main * 0.333);

        
    .second-section{
        
            
        .top{
            

            .user:hover,
            .activity:hover{
                background-color: $profile-main-color;
                transition: all 1s ease
            }

            .section{
                border: none;
            }
            
            .section-active{
                border-bottom: 2px solid $profile-main-color;
            }
        }

        .bottom{
            display: block;

            .user,
            .activity{
                width: 100%;
                margin: 10px 0;
                min-height: 50vh;
                padding: 10px
            }
        }
    }
}
</style>