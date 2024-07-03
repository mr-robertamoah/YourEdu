<template>
    <div class="side-bar-wrapper" :class="{'bar-small':barSmall}" >
        <div class="icon" @click="switchIcon" :title="iconTitle">
            <font-awesome-icon :icon="icons"></font-awesome-icon>
        </div>
        <div class="username" 
            v-if="computedUserName.length"
            @click="clickedUsername($event)"
        >
            {{`@${computedUserName}`}}</div>
        <div class="main-item">
            <div class="main-text">Profile</div>
            <div class="main-icon">
                <font-awesome-icon :icon="['fas','users']"></font-awesome-icon>
            </div>
        </div>
        <div class="main">
            <div class="item">
                <div class="item-text">
                    learner
                </div>
                <div class="item-icon">
                    (L)
                </div>
                <div class="item-sub"> 
                    <div class="no-item" v-if="!isLearner">
                        <div class="info">no learner account</div>
                        <div class="button"
                            @click="clickedCreate('learner')"
                        >create</div>
                    </div>
                    <profile-bar
                        v-else
                        :smallType="true"
                        :justName="true"
                        :profile="computedLearner.params"
                        :navigate="false"
                        @clickedProfile="clickedProfile"
                    ></profile-bar>
                </div>
            </div>
            <div class="item">
                <div class="item-text">
                    facilitator
                </div>
                <div class="item-icon">
                    (F)
                </div>
                <div class="item-sub">
                    <div class="no-item" v-if="!isFacilitator">
                        <div class="info">no facilitator account</div>
                        <div class="button"
                            @click="clickedCreate('facilitator')"
                        >create</div>
                    </div>
                    <profile-bar
                        v-else
                        :smallType="true"
                        :justName="true"
                        :profile="computedFacilitator"
                        :navigate="false"
                        @clickedProfile="clickedProfile"
                    ></profile-bar>
                </div>
            </div>
            <div class="item">
                <div class="item-text">
                    Parent
                </div>
                <div class="item-icon">
                    (P)
                </div>
                <div class="item-sub">
                    <div class="no-item" v-if="!isParent">
                        <div class="info">no parent account</div>
                        <div class="button"
                            @click="clickedCreate('parent')"
                        >create</div>
                    </div>
                    <profile-bar
                        v-else
                        :smallType="true"
                        :justName="true"
                        :profile="computedParent"
                        :navigate="false"
                        @clickedProfile="clickedProfile"
                    ></profile-bar>
                </div>
            </div>
            <div class="item">
                <div class="item-text">
                    Professionals
                </div>
                <div class="item-icon">
                    (Pros)
                </div>
                <div class="item-sub">
                    <div class="no-item" v-if="computedProfessionals.length < 3">
                        <div class="info">
                            {{computedProfessionals.length ? `own another professional account` : 'no professional accounts'}}
                        </div>
                        <div class="button"
                            @click="clickedCreate('professional')"
                        >create</div>
                    </div>
                    <template v-if="computedProfessionals.length">
                        <profile-bar
                            v-for="professional in computedProfessionals"                            
                            :key="professional.accountId"
                            :smallType="true"
                            :justName="true"
                            :profile="professional"
                            :navigate="false"
                            @clickedProfile="clickedProfile"
                        ></profile-bar>
                    </template>
                </div>
            </div>
            <div class="item">
                <div class="item-text">
                    Schools
                </div>
                <div class="item-icon">
                    (S)
                </div>
                <div class="item-sub">
                    <div class="no-item" v-if="computedSchools.length < 3">
                        <div class="info">
                            {{computedSchools.length ? `own another school` : 'no owned schools'}}
                        </div>
                        <div class="button"
                            @click="clickedCreate('school')"
                        >create</div>
                    </div>
                    <template v-if="computedSchools.length">
                        <profile-bar
                            v-for="school in computedSchools"                            
                            :key="school.accountId"
                            :smallType="true"
                            :justName="true"
                            :profile="school"
                            :navigate="false"
                            @clickedProfile="clickedProfile"
                        ></profile-bar>
                    </template>
                </div>
            </div>
            <div class="item" v-if="computedAdmin">
                <div class="item-text">
                    Admins
                </div>
                <div class="item-icon">
                    (A)
                </div>
                <div class="item-sub">
                    <profile-bar      
                        :name="computedAdmin.name"
                        :type="'admin'"
                        :smallType="true"
                        :justName="true"
                        :routeParams="{account: 'admin', accountId: computedAdmin.id}"
                        :navigate="false"
                        @clickedProfile="clickedProfile"
                    ></profile-bar>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProfileBar from '../profile/ProfileBar.vue'
import { mapGetters } from 'vuex'
    export default {
        components: {
            ProfileBar,
        },
        props: {
            minWidth: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                icons: ['fas','bars'],
                barSmall: true,
                iconTitle: 'click to expand'
            }
        },
        watch: {
            barSmall(newValue) {
                this.$emit('barSmall',newValue)
            },
            minWidth(newValue) {
                if (newValue) {
                    this.switchIcon()
                }
            },
        },
        computed: {
            ...mapGetters(['getUser','getProfiles','isLearner','isFacilitator','isParent',
                'professionalsCount','schoolsCount']),
            computedUserName() {
                return this.getUser ? this.getUser.username : '' 
            },
            computedLearner(){
                if (this.getProfiles) {
                    return this.getProfiles.filter(profile=>{
                        return profile.account === 'learner'
                    })[0]
                }
                return null
            },
            computedParent(){
                if (this.getProfiles) {
                    return this.getProfiles.filter(profile=>{
                        return profile.account === 'parent'
                    })[0]
                }
                return null
            },
            computedFacilitator(){
                if (this.getProfiles) {
                    return this.getProfiles.filter(profile=>{
                        return profile.account === 'facilitator'
                    })[0]
                }
                return null
            },
            computedProfessionals(){
                if (this.getProfiles) {
                    return this.getProfiles.filter(profile=>{
                        return profile.account === 'professional'
                    })
                }
                return null
            },
            computedSchools(){
                if (this.getProfiles) {
                    return this.getProfiles.filter(profile=>{
                        return profile.account === 'school'
                    })
                }
                return null
            },
            computedAdmin(){
                if (this.getUser && this.getUser.admin) {
                    return this.getUser.admin
                }
                return null
            },
        },
        methods: {
            switchIcon() {
                if (this.icons[1] === 'bars') {
                    this.icons  = ['fas','times']
                    this.barSmall = false
                    this.iconTitle = 'click to collapse'
                } else if (this.icons[1] === 'times') {
                    this.icons  = ['fas','bars']
                    this.iconTitle = 'click to expand'
                    this.barSmall = true
                }
            },
            clickedProfile(data){
                if (document.getElementsByClassName('active').length) {                    
                    document.getElementsByClassName('active')[0].classList.remove('active')
                }
                this.$emit('sidebarSelection',{type:'account',account:data})
            },
            clickedUsername(event){
                if (!event.target.classList.contains('active')) {
                    event.target.classList.add('active')
                }
                this.$emit('sidebarSelection',{type:'user'})
            },
            clickedCreate(data){
                this.$emit('clickedCreate',data)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .side-bar-wrapper{
        position: fixed;
        width: 200px;
        background-color:$color-primary;
        height: 100vh;
        z-index: 100;
        transition: width 1s ease-in-out;
        top: 0;
        left: 0;
        color: gray;

        .icon{
            padding: 10px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            margin: 0 auto;
            color: rgba(105, 105, 105, .7);
            text-align: center;
            border-bottom: 2px solid rgba(105, 105, 105, 1);

            & :hover {
                color: rgba(105, 105, 105, 1);
                transition: all 1s ease-in-out;
            }
        }

        .username{
            @include text-overflow();
            font-size: 12px;
            text-transform: lowercase;
            padding: 5px;
            cursor: pointer;
            text-align: center;
            margin: 20px 0 10px;
            padding: 5px;
        }

        .active{
            color: whitesmoke;
            background:$background-color-main-light;
        }

        .main-item{
            display: inline-flex;
            padding: 5px;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            color: whitesmoke;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            
            .main-text{
                @include text-overflow();
                display: block;
                visibility: visible;
                text-align: center;
            }

            .main-icon{
                display: none;
                visibility: hidden;
                transition: all 1s ease-in-out;
            }
        }

        .main{
            padding: 10px;
            font-size: 16px;
            font-weight: 500;

            .item{
                display: flex;
                justify-content: space-around;
                align-items: center;
                margin: 0 auto;
                width: 100%;
                padding: 10px;
                cursor: pointer;
                position: relative;

                &:hover{
                    background-color:$color-primary;
                    box-shadow: 0 0 2px rgba(105, 105, 105, .8);
                    transition: all .5s ease-in-out;                    

                    .item-sub{
                        min-width: 150px;
                        opacity: 1;
                        visibility: visible;
                        transition: all .5s ease-in-out;
                    }
                }

                .item-text{
                    display: block;
                    visibility: visible;
                    opacity: 1;
                    transition: all .5s ease-in-out;
                    transition-delay: .5s;
                }

                .item-icon{
                    display: block;
                    visibility: visible;
                }

                .item-sub{
                    position: absolute;
                    width: 0;
                    top: 0;
                    left: 100%;
                    margin: 0;
                    opacity: 0;
                    visibility: hidden;
                    background-color: transparent;

                    .no-item{
                        font-size: 14px;

                        .info{
                            background: azure;
                            text-align: center;
                            width: fit-content;
                            padding: 5px;
                        }

                        .button{
                            background: azure;
                            width: fit-content;
                            margin: 10px auto;
                            padding: 5px;
                            border-radius: 10px;
                            box-shadow: 0 0 2px grey;
                        }
                    }

                    .sub-item{
                        width: auto;
                        display: flex;
                        justify-content: space-around;
                        align-items: center;
                        padding: 10px;
                        background-color: $color-primary;
                        margin: 0 0 5px 5px;
                        border-radius: 10px;

                        &:hover{
                            box-shadow: 0 0 2px rgba(105, 105, 105, .6);
                        }
                    }
                }
            }
        }
    }

    .bar-small{
        width: 50px;
        transition: all 1s ease-in-out;
        position: fixed;

        .main-item{
            justify-content: center;   

            .main-text{
                display: none;
                visibility: hidden;
            }         

            .main-icon{
                display: block;
                visibility: visible;
            }
        }

        .main{
            padding: 5px;

            .item{

                .item-text{
                    display: none;
                    visibility: hidden;
                    opacity: 0;
                }

                .item-sub{
                    min-height: 80px;
                }
            }
            
        }
    }


@media screen and (max-width:800px){
    
    .side-bar-wrapper{

        .main-item{
            font-size: 16px;
        }

        .main{
            font-size: 14px;

            .item{
                display: flex;

                &:hover{     
                    box-shadow: none;               
                    background-color: inherit;
                    display: block;
                    text-align: center;
                    transition: all 1s ease-in-out;

                    .item-text{
                        display: none;      
                    }

                    .item-sub{
                        transition: height 1s ease-in-out;
                        padding: 10px;
                        width: 100%;
                        min-width: 0;
                        position: relative;
                        top: 100%;
                        left: 0;
                        margin: 10px auto;
                        height: auto;

                        .sub-item{
                            margin: 5px auto;
                            width: 100%;
                            padding: 5px;
                        }
                    }
                }

                .item-text,
                .item-icon{
                    margin: 5px;
                }

                .item-sub{
                    transition: none;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 0;
                    height: 0;
                    background-color: $background-color-main-light;

                    .sub-item{
                        position: relative;
                    }
                }
            }
        }
    }

    .bar-small{

        .main{
            font-size: 14px;

            .item{
                display: flex;

                &:hover{ 
                    background-color:$color-primary;
                    box-shadow: 0 0 2px rgba(105, 105, 105, .8);
                    transition: all .5s ease-in-out;
                    

                    .item-sub{
                        min-width: 150px;
                        position: absolute;
                        width: 0;
                        top: 0;
                        left: 100%;
                        opacity: 1;
                        visibility: visible;
                        transition: all .5s ease-in-out;
                        position: absolute;
                        margin: 0;
                        padding: 0;
                        

                        .sub-item{
                            width: auto;
                            display: flex;
                            justify-content: space-around;
                            align-items: center;
                            padding: 10px;
                        }
                        
                    }
                    
                }

                .item-text,
                .item-icon{
                    margin: 5px;
                }

                .item-text{                  
                    transition: all .5s ease-in-out;
                    transition-delay: .5s;
                }

                .item-sub{
                    margin: 0;
                    opacity: 0;
                    visibility: hidden;
                    background-color: transparent;
                }
            }
        }
    }
}
</style>