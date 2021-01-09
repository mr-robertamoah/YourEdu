<template>
    <div class="dashboard-main-section-wrapper" v-if="mainSectionData">
        <div class="name">
            {{computedName}}
        </div>
        <div class="description" v-if="computedDescription.length">
            {{computedDescription}}
        </div>
        <div class="edit" 
            @click="clickedEditClass"
            v-if="computedClassAdmin || computedClassOwner"
        >
            <font-awesome-icon :icon="['fa','pencil-alt']"></font-awesome-icon>
        </div>
        <div class="data">
            <template v-if="computedClassAdmin || computedClassOwner">
                <div class="state" v-if="mainSectionData.state">
                    {{mainSectionData.state}}
                </div>
                <account-badge
                    v-if="mainSectionData.addedby"
                    :account="mainSectionData.addedby"
                    :dashboard="true"
                    :what="type"
                    class="account-badge"
                ></account-badge>
            </template>
            <template v-if="mainSectionData.type === 'school'">
                <div class="state" v-if="mainSectionData.role">
                    {{mainSectionData.role}}
                </div>
            </template>
        </div>
        <dashboard-sub-section
            subText="lessons"
            v-if="mainSectionData.lessons"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    <dashboard-section-account
                        class="dashboard-section-account"
                        v-for="(lesson,index) in mainSectionData.lessons"
                        :key="index"
                        :type="'lesson'"
                        :account="lesson"
                        @clickedDashboardActionButton="clickedDashboardActionButton"
                    >                                            
                    </dashboard-section-account>
                    <div class="no-data" v-if="!mainSectionData.lessons.length">
                        no lessons
                    </div>
                    <dashboard-action-button
                        class="add-another"
                        text="add lesson"
                        icon="plus"
                        :data="null"
                        v-if="facilitator"
                        @click="clickedDashboardActionButton"
                    ></dashboard-action-button>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="classes"
            v-if="mainSectionData.classes"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.classes}}
                    <div class="no-data" v-if="!mainSectionData.classes.length">
                        no classes
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="courses"
            v-if="mainSectionData.courses"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    <dashboard-section-account
                        class="dashboard-section-account"
                        v-for="(course,index) in mainSectionData.courses"
                        :key="index"
                        :type="course.data ? getAttachmentString(course.type) : account.owner ? 'owned course' : 'normal course'"
                        :account="course"
                        @clickedDashboardActionButton="clickedDashboardActionButton"
                    >                                            
                    </dashboard-section-account>
                    <div class="no-data" v-if="!mainSectionData.courses.length">
                        no courses
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="grades"
            v-if="mainSectionData.grades"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    <dashboard-section-account
                        class="dashboard-section-account"
                        v-for="(grade,index) in mainSectionData.grades"
                        :key="index"
                        :type="grade.data ? getAttachmentString(grade.type) : account.owner ? 'owned grade' : 'normal grade'"
                        :account="grade"
                        @clickedDashboardActionButton="clickedDashboardActionButton"
                    >                                            
                    </dashboard-section-account>
                    <div class="no-data" v-if="!mainSectionData.grades.length">
                        no grades
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="programs"
            v-if="mainSectionData.programs"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    <dashboard-section-account
                        class="dashboard-section-account"
                        v-for="(program,index) in mainSectionData.programs"
                        :key="index"
                        :type="program.data ? getAttachmentString(program.type) : account.owner ? 'owned program' : 'normal program'"
                        :account="program"
                        @clickedDashboardActionButton="clickedDashboardActionButton"
                    >                                            
                    </dashboard-section-account>
                    <div class="no-data" v-if="!mainSectionData.programs.length">
                        no programs
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            v-if="mainSectionData.collaboration"
            subText="collaboration"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.collaborations}}
                    <div class="no-data" v-if="!mainSectionData.collaborations.length">
                        no collaborations
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            v-if="mainSectionData.facilitators"
            subText="facilitators"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.facilitators}}
                    <div class="no-data" v-if="!mainSectionData.facilitators.length">
                        no facilitators
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="professionals"
            v-if="mainSectionData.professionals"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.professionals}}
                    <div class="no-data" v-if="!mainSectionData.professionals.length">
                        no professionals
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="learners"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.learners}}
                    <div class="no-data" v-if="!mainSectionData.learners.length">
                        no learners
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="subjects"
            v-if="mainSectionData.subjects"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.subjects}}
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="extracurriculums"
            v-if="mainSectionData.extracurriculums"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.extracurriculums}}
                    <div class="no-data" v-if="!mainSectionData.extracurriculums.length">
                        no extracurriculums
                    </div>
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="discussions"
            v-if="mainSectionData.discussions"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.discussions}}
                </div>
            </template>
        </dashboard-sub-section>
    </div>
</template>

<script>
import AccountBadge from './AccountBadge';
import DashboardSubSection from './DashboardSubSection';
import DashboardSectionAccount from './DashboardSectionAccount';
import DashboardActionButton from './DashboardActionButton';
    export default {
        components: {
            DashboardActionButton,
            DashboardSectionAccount,
            DashboardSubSection,
            AccountBadge,
        },
        props: {
            type: {
                type: String,
                default: ''
            },
            mainSectionData: {
                type: Object,
                default(){
                    return null
                }
            },
            account: {
                type: Object,
                default(){
                    return null
                }
            },
            learner: {
                type: Object,
                default(){
                    return null
                }
            },
            facilitator: {
                type: Object,
                default(){
                    return null
                }
            },
            parent: {
                type: Object,
                default(){
                    return null
                }
            },
            professional: {
                type: Object,
                default(){
                    return null
                }
            },
            admin: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        computed: {
            computedName() {
                return this.type === 'class' || this.type === 'school' ||
                     this.type === 'course' ||  this.type === 'extracurriculum' ? 
                    this.mainSectionData.name : 
                    ''
            },
            computedClassAdmin(){
                return this.type === 'class' && this.account.admin ? this.account.account : null
            },
            computedClassOwner(){
                return this.type === 'class' && this.account.owner ? this.account.account : null
            },
            computedClassLearner(){
                return this.type === 'class' && this.account.learner ? this.account.account : null
            },
            computedClassFacilitator(){
                return this.type === 'class' && this.account.facilitator ? this.account.account : null
            },
            computedDescription(){
                return this.mainSectionData.description ? 
                    `${this.type} description: ${this.mainSectionData.description}` : 
                    this.mainSectionData.about ? 
                    `about ${this.type}: ${this.mainSectionData.description}` : ''
            },
        },
        methods: {
            clickedEditClass() {
                this.$emit('clickedEditClass')
            },
            clickedDashboardActionButton(data) {
                this.$emit('clickedDashboardActionButton',data)
            },
            getAttachmentString(attachment) {
                return attachment.slice(0,attachment.length - 1)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-main-section-wrapper{
        position: relative;
        
        .no-data{
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: gray;
        }

        .edit{
            float: right;
            font-size: 18px;
            margin: 0 10px 0 0;
            color: gray;
            cursor: pointer;
        }

        .state{
            font-size: 12px;
            color: gray;
            text-transform: lowercase;
            text-align: end;
            font-style: italic;
        }

        .account-badge{
            width: 90%;
            margin: 0 auto;
            max-width: 250px;
        }

        .name{
            text-transform: capitalize;
            margin: 10px 0;
            padding: 0 10px;
            width: 100%;
            text-align: center;
            font-weight: 500;
        }

        .description{
            text-transform: lowercase;
            font-size: 12px;
            color: gray;
            text-align: center;
            width: 100%;
        }
    }
</style>