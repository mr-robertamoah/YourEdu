<template>
    <div class="dashboard-section"
        :class="{dashboardShift:!barSmall}"
        @click="clickedMainSection"
    >
        <div class="dashboard-header">
            <div class="youredu">YourEdu</div>
            <div class="user" v-if="computedUserName.length">
                <div class="username"
                    @click="clickedUsername"
                >{{`@${computedUserName}`}}</div>
                <div class="dropdown-icon"
                    @click="clickedHeaderDropdown"
                >
                    <font-awesome-icon :icon="['fa','chevron-down']"></font-awesome-icon>
                </div>
                <div class="dropdown-section" v-if="showHeaderDropdown">
                    <div class="item">
                        name
                    </div>
                    <div class="item">
                        logout
                    </div>
                </div>
            </div>
        </div>
        <div class="top-section mt-8" 
            v-if="!mainSection.length"
            :class="{full}"
        >
            <div class="heading">
                {{`welcome ${getUser.fullName} to your dashboard.`}}
            </div>
            <div class="title" v-if="type === 'user'">
                user information
            </div>
            <div class="title" v-if="type === 'account'">
                account information
            </div>
        </div>
        <div class="middle-section" 
            v-if="!mainSection.length"
            :class="{full}"
        >
            <div class="main-title">
                {{type.length ? 'what you can do' : 'nothing to do?'}}
            </div>
            <div class="loading" v-if="loading">
                <pulse-loader :loading="loading"></pulse-loader>
            </div>
            <div class="action-buttons" 
                v-if="!loading && (type === 'account' || type === 'user')"
            >
                <post-button
                    :buttonText="`${type} info`"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="type === 'user'"
                    :active="activePostButton === 'info'"
                ></post-button>
                <post-button
                    buttonText="edit user info"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="type === 'user'"
                    :active="activePostButton === 'edit user info'"
                ></post-button>
                <post-button
                    buttonText="create account"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="type === 'user'"
                    :active="activePostButton === 'create account'"
                ></post-button>
                <post-button
                    buttonText="join school"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && account.account === 'learner'"
                    :active="activePostButton === 'join school'"
                ></post-button>
                <post-button
                    buttonText="join class"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && account.account === 'learner'"
                    :active="activePostButton === 'join class'"
                ></post-button>
                <post-button
                    buttonText="join program"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && account.account !== 'school'"
                    :active="activePostButton === 'join program'"
                ></post-button>
                <post-button
                    buttonText="make lesson"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="type === 'account'"
                    :active="activePostButton === 'make lesson'"
                ></post-button>
                <post-button
                    buttonText="join extracurriculum"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && account.account === 'learner'"
                    :active="activePostButton === 'join extracurriculum'"
                ></post-button>
                <post-button
                    buttonText="take course"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && account.account !== 'school'"
                    :active="activePostButton === 'take course'"
                ></post-button>
                <post-button
                    buttonText="join discussion"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="type === 'account'"
                    :active="activePostButton === 'join discussion'"
                ></post-button>
                <post-button
                    buttonText="create course"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && (account.account === 'facilitator' || 
                        account.account === 'professional' || account.account === 'school')"
                    :active="activePostButton === 'create course'"
                ></post-button>
                <post-button
                    buttonText="create class"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && (account.account === 'facilitator' || 
                        account.account === 'school')"
                    :active="activePostButton === 'create class'"
                ></post-button>
                <post-button
                    buttonText="create academic year"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && account.account === 'school'"
                    :active="activePostButton === 'create academic year'"
                ></post-button>
                <post-button
                    buttonText="create extracurriculum"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && (account.account === 'facilitator' || 
                        account.account === 'professional' || account.account === 'school')"
                    :active="activePostButton === 'create extracurriculum'"
                ></post-button>
                <post-button
                    buttonText="create user/account for others"
                    @click="clickedPostButton"
                    class="post-button"
                    v-if="account && (account.account === 'facilitator' || 
                        account.account === 'school')"
                    :active="activePostButton === 'create user/account for others'"
                ></post-button>
            </div>
        </div>
        <div class="middle-section" 
            v-if="type === 'account' && account.account !== 'admin' && !mainSection.length"
            :class="{full}"
        >
            <div class="main-title">
                selections
            </div>
            <div class="loading" v-if="loading">
                <pulse-loader :loading="loading"></pulse-loader>
            </div>
            <div class="selection-badges" v-if="!loading">
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    :hasSearch="true"
                    heading="schools"
                    :items="computedSchools"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account !== 'school'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    :hasSearch="true"
                    heading="classes"
                    :items="computedClasses"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account === 'facilitator' || account.account === 'professional' ||
                        account.account === 'learner'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    :hasSearch="true"
                    heading="extracurriculum"
                    :items="computedExtracurriculums"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account === 'learner'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    :hasSearch="true"
                    heading="courses"
                    :items="computedCourses"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account !== 'school'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    :hasSearch="true"
                    heading="programs"
                    :items="computedPrograms"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account === 'learner'"
                ></dashboard-item-badge>
            </div>
        </div>
        <div class="bottom-section" 
            v-if="!mainSection.length"
            :class="{'mt-8': full}"
        >
            <div class="info" v-if="!loading && !type.length">
                Yaay...get active by clicking any of your community accounts
            </div>
            <div class="main-title" v-if="computedBottomTitle.length">
                {{computedBottomTitle}}

                <div class="icon" @click="full = !full">
                    <font-awesome-icon 
                        v-if="full"
                        :icon="['fa','chevron-down']"></font-awesome-icon>
                    <font-awesome-icon 
                        v-if="!full"
                        :icon="['fa','chevron-up']"></font-awesome-icon>
                </div>
                <action-button 
                    class="view-requests" 
                    text="requests"
                    @click="clickedAction"
                    v-if="account && account.account === 'school'"
                >
                </action-button>
                <action-button 
                    class="view-requests" 
                    text="send request"
                    @click="clickedAction"
                    v-if="account && account.account !== 'school' && 
                        account.account !== 'admin'"
                >
                </action-button>
                <optional-actions
                    v-if="showOptionalActions"
                    :show="showOptionalActions"
                    :hasOthers="true"
                    :others="['view requests','send request']"
                    @clickedOption="clickedOptionalAction"
                    class="optional-actions"
                ></optional-actions>
            </div>
            <div class="loading" v-if="loading">
                <pulse-loader :loading="loading"></pulse-loader>
            </div>
            <div class="user-info" v-if="type === 'user'">
                <div class="body">
                    <div class="info-item">
                        <div class="label">first name:</div>
                        <div class="value">
                            {{getUser.first_name}}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">last name:</div>
                        <div class="value">
                            {{getUser.last_name}}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">other names:</div>
                        <div class="value">
                            {{getUser.other_names}}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">email:</div>
                        <div class="value">
                            {{getUser.email}}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">gender:</div>
                        <div class="value">
                            {{getUser.gender}}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">dob:</div>
                        <div class="value">
                            {{computedDob}}
                        </div>
                    </div>
                </div>
                <div class="accounts">
                    <div class="title">
                        these are your user accounts
                    </div>
                    <account-info class="account-info"
                        v-for="account in getProfiles"
                        :key="account.account + account.accountId"
                        @click="clickedAccount(account)"
                        :name="account.name"
                        :type="account.account"
                    >
                    </account-info>
                </div>
            </div>
            <div class="account-info" 
                v-if="!loading && type === 'account'"
            >
                <div class="top">
                    <profile-picture
                        class="profile-picture"
                        v-if="account.account !== 'admin'"
                    >
                        <template slot="image">
                            <img :src="computedAccountDetails.url">
                        </template>
                    </profile-picture>
                    <div class="other">                        
                        <div class="name">
                            {{computedAccountDetails.name}}
                        </div>
                        <div class="type">
                            {{computedAccountDetails.account}}
                        </div>
                    </div>
                </div>
                <div class="middle">
                    <div class="section">
                        <template v-if="account.account === 'parent'">
                            <dashboard-sub-section
                                subText="wards"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>name</th>
                                                    <th>type</th>
                                                    <th>date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(ward,index) in computedAccountDetailsWards"
                                                    :key="index"
                                                    @click="clickedWard(ward)"
                                                >
                                                    <th>{{ward.name}}</th>
                                                    <th>{{ward.role}}</th>
                                                    <th>{{getReadableDate(ward.parentingCreatedAt)}}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="no-ward" v-if="!computedAccountDetailsWards || !computedAccountDetailsWards.length">
                                            {{`has no other ${showSelection}`}}
                                        </div>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                        </template>
                        <template v-if="account.account === 'school'">
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="admins"
                                v-if="computedCurrentAccount.account === 'school'"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(admin,index) in computedSchoolAdmins"
                                            :key="index"
                                            type="admin"
                                            :account="admin"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolAdmins || !computedSchoolAdmins.length">
                                            no admins
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add admin"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="grades"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(grade,index) in computedSchoolGrades"
                                            :key="index"
                                            type="grade"
                                            :account="grade"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolGrades || !computedSchoolGrades.length">
                                            no grades
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add grade"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="subjects"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(subject,index) in computedSchoolSubjects"
                                            :key="index"
                                            type="subject"
                                            :account="subject"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolSubjects || !computedSchoolSubjects.length">
                                            no subjects
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add subject"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned programs"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(program,index) in computedOwnedPrograms"
                                            :key="index"
                                            type="owned program"
                                            :account="program"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedPrograms || !computedOwnedPrograms.length">
                                            no programs
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add program"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned classes"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(ownedClass,index) in computedOwnedClasses"
                                            :key="index"
                                            type="owned class"
                                            :account="ownedClass"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedClasses || !computedOwnedClasses.length">
                                            no classes
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add class"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned courses"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(ownedCourse,index) in computedOwnedCourses"
                                            :key="index"
                                            type="owned course"
                                            :account="ownedCourse"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedCourses || !computedOwnedCourses.length">
                                            no courses
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add course"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned extracurriculums"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(ownedExtracurriculum,index) in computedOwnedExtracurriculums"
                                            :key="index"
                                            type="owned extracurriculum"
                                            :account="ownedExtracurriculum"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedExtracurriculums || !computedOwnedExtracurriculums.length">
                                            no extracurriculums
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add extracurriculum"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="facilitators"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(facilitator,index) in computedSchoolFacilitators"
                                            :key="index"
                                            type="facilitator"
                                            :account="facilitator"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolFacilitators || !computedSchoolFacilitators.length">
                                            no facilitators
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add facilitator"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="professionals"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(professional,index) in computedSchoolProfessionals"
                                            :key="index"
                                            type="professional"
                                            :account="professional"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolProfessionals || !computedSchoolProfessionals.length">
                                            no professionals
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add professional"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="learners"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(learner,index) in computedSchoolLearners"
                                            :key="index"
                                            type="learner"
                                            :account="learner"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolLearners || !computedSchoolLearners.length">
                                            no learners
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add learner"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="parents"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(parent,index) in computedSchoolParents"
                                            :key="index"
                                            type="parent"
                                            :account="parent"
                                            :admin="computedSchoolAuthority"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolParents || !computedSchoolParents.length">
                                            no parents
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add parent"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="collaborations"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(collaboration,index) in computedCollaborations"
                                            :key="index"
                                            type="collaboration"
                                            :account="collaboration"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedCollaborations || !computedCollaborations.length">
                                            no collaborations
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add collaboration"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                        </template>
                        <template v-if="account.account === 'admin'">
                            <dashboard-sub-section
                                subText="admins"
                                v-if="computedAccountDetails.role === 'SUPERADMIN'"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(admin,index) in computedAdmins"
                                            :key="index"
                                            type="youredu admin"
                                            :account="admin"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedAdmins || !computedAdmins.length">
                                            no admins
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add admin"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                subText="users"
                            >
                                <template slot="body">
                                    <div class="sub-main long">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(user,index) in computedUsers"
                                            :key="index"
                                            type="user"
                                            :account="user"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedUsers || !computedUsers.length">
                                            no users
                                        </div>

                                        <infinite-loader
                                            v-if="usersNextPage !== 1"
                                            @infinite="usersInfiniteLoader"
                                        ></infinite-loader>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                subText="accounts"
                                :inactive="!accountsLoading && accountsNextPage === 1"
                                infinite-wrapper
                            >
                                <template slot="body">
                                    <div class="sub-main long">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(account,index) in computedAccounts"
                                            :key="index"
                                            type="account"
                                            :account="account"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedAccounts || !computedAccounts.length">
                                            no accounts
                                        </div>

                                        <infinite-loader
                                            v-if="accountsNextPage !== 1"
                                            @infinite="accountsInfiniteLoader"
                                            force-use-infinite-wrapper
                                        ></infinite-loader>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                        </template>
                        <template v-if="account.account === 'learner'">
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton" 
                                subText="lessons"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(lesson,index) in computedAccountLessons"
                                            :key="index"
                                            type="lesson"
                                            :account="lesson"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedAccountLessons || !computedAccountLessons.length">
                                            no lessons
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add lesson"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                        </template>
                        <template v-if="account.account === 'facilitator' ||
                            account.account === 'professional'">
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton" 
                                subText="assessments"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(assessment,index) in computedAccountAssessments"
                                            :key="index"
                                            type="assessment"
                                            :account="assessment"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedAccountAssessments || !computedAccountAssessments.length">
                                            no assessments
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add assessment"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton" 
                                subText="lessons"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(lesson,index) in computedAccountLessons"
                                            :key="index"
                                            type="lesson"
                                            :account="lesson"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedAccountLessons || !computedAccountLessons.length">
                                            no lessons
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add lesson"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="subjects"
                                v-if="account.account === 'facilitator'"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(subject,index) in computedSubjects"
                                            :key="index"
                                            type="subject"
                                            :account="subject"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSubjects || !computedSubjects.length">
                                            no subjects
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add subject"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="curricula"
                                v-if="account.account === 'facilitator'"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(curriculum,index) in computedCurricula"
                                            :key="index"
                                            type="curriculum"
                                            :account="curriculum"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedCurricula || !computedCurricula.length">
                                            no curricula
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add curriculum"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned classes"
                                v-if="account.account === 'facilitator'"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(ownedClass,index) in computedOwnedClasses"
                                            :key="index"
                                            type="owned class"
                                            :account="ownedClass"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedClasses || !computedOwnedClasses.length">
                                            no classes
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add class"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="schedules"
                                v-if="account.account === 'professional'"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(schedule,index) in computedSchedules"
                                            :key="index"
                                            type="schedule"
                                            :account="schedule"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchedules || !computedSchedules.length">
                                            no schedules
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add schedule"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="counselling sections"
                                v-if="account.account === 'professional'"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(counselling,index) in computedCounselling"
                                            :key="index"
                                            type="counselling"
                                            :account="counselling"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedCounselling || !computedCounselling.length">
                                            no counselling sections
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add counselling"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned courses"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(course,index) in computedOwnedCourses"
                                            :key="index"
                                            type="owned course"
                                            :account="course"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedCourses || !computedOwnedCourses.length">
                                            no courses
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add course"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned extracurriculums"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(extracurriculum,index) in computedOwnedExtracurriculums"
                                            :key="index"
                                            type="owned extracurriculum"
                                            :account="extracurriculum"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedExtracurriculums || !computedOwnedExtracurriculums.length">
                                            no extracurriculums
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add extracurriculum"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="owned programs"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(program,index) in computedOwnedPrograms"
                                            :key="index"
                                            type="owned program"
                                            :account="program"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedOwnedPrograms || !computedOwnedPrograms.length">
                                            no programs
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add program"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                @clickedDashboardActionButton="clickedDashboardActionButton"
                                subText="collaborations"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(collaboration,index) in computedCollaborations"
                                            :key="index"
                                            type="collaboration"
                                            :account="collaboration"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedCollaborations || !computedCollaborations.length">
                                            no collaborations
                                        </div>
                                    </div>
                                </template>
                                <template slot="bottom">
                                    <dashboard-action-button
                                        class="add-another"
                                        text="add collaboration"
                                        icon="plus"
                                        :data="null"
                                        @click="clickedDashboardActionButton"
                                    ></dashboard-action-button>
                                </template>
                            </dashboard-sub-section>
                        </template>
                        <fade-right>
                            <template slot="transition" v-if="showSelection">                                
                                <div class="main">
                                    <account-info class="account-info"
                                        v-for="account in computedAccountDetails.parents"
                                        :key="account.account + account.accountId"
                                        @click="clickedAccountProfile(account)"
                                        :name="account.name"
                                        :url="account.url"
                                        :type="account.role"
                                    >
                                    </account-info>
                                </div>
                            </template>
                        </fade-right>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="main-section" v-if="mainSection.length">
            <div class="loading" v-if="mainSectionLoading">
                <pulse-loader :loading="mainSectionLoading"></pulse-loader>
            </div>
            <div class="section" v-if="!mainSectionLoading">
                <div class="back" @click="clickedMainSectionBack">
                    <font-awesome-icon :icon="['fa','long-arrow-alt-left']"></font-awesome-icon>
                </div>
                <dashboard-main-section
                    :type="mainSection"
                    :account="computedAccount"
                    :learner="computedMainLearner"
                    :facilitator="computedMainFacilitator"
                    :parent="computedMainParent"
                    :professional="computedMainProfessional"
                    :admin="computedMainAdmin"
                    :mainSectionData="mainSectionData"
                    @clickedEditSomething="clickedEditSomething"
                    @clickedDashboardActionButton="clickedDashboardActionButton"
                ></dashboard-main-section>
                <dashboard-sub-section
                    subText="comments"
                    v-if="mainSectionData"
                >
                    <template slot="body">
                        <div class="actions">
                            <add-comment
                                :what="mainSection"
                                :id="computedIdMainSection"
                                :showAddComment="true"
                                :account="computedCurrentSectionAccount"
                                :schoolAdmin="computedSchoolAdmin"
                                @dashboardCommentCreated="dashboardCommentCreated"
                            ></add-comment>
                        </div>
                        <div class="comments-section"
                            v-if="computedMainSectionComments"
                        >
                            <comment-single
                                v-for="comment in computedMainSectionComments"
                                :key="comment.id" 
                                :comment="comment"
                                :dashboard="true"
                                :account="computedCurrentSectionAccount"
                                :schoolAdmin="computedSchoolAdmin"
                                @clickedMedia="clickedCommentMedia"
                                @commentDeleteSuccess="commentDeleteSuccess"
                                @postModalCommentEdited="postModalCommentEdited"
                            ></comment-single>

                            <infinite-loader
                                v-if="commentsNextPage !== 1"
                                @infinite="commentsInfiniteLoader"
                            ></infinite-loader>
                        </div>
                        <div class="no-data" v-else>
                            no comments yet
                        </div>
                    </template>
                </dashboard-sub-section>
            </div>
        </div>
        <div class="dashboard-footer">footer(give some info)</div>

        <ward-modal
            v-if="showWardModal"
            :show="showWardModal"
            :ward="ward"
            @closeWardModal="showWardModal = false"
        ></ward-modal>

        <dashboard-request-modal
            v-if="showRequests"
            :show="showRequests"
            :account="account"
            :action="actionRequests"
            @requestsModalDisappear="showRequests = false"
        ></dashboard-request-modal>

        <!-- for attaching subjects, grades etc -->
        <attachment-modal
            v-if="showAttachmentModal"
            :show="showAttachmentModal"
            :type="attachmentType"
            :accountDetails="computedAccountDetails"
            @attachmentSuccess="attachmentSuccess"
            @attachmentModalDisappear="closeModal('attachment')"
        ></attachment-modal>

        <create-class
            :show="showCreateModal === 'class'"
            @closeCreateClass="closeCreateModal"
            :editable="mainSectionData"
            :mainSection="mainSection"
            :schoolAdmin="computedSchoolAdmin"
            :edit="createModalEdit === 'class'"
            @classSuccessfullyEdited="successfullyEdited"
        ></create-class>
        
        <create-assessment
            :show="showCreateModal === 'assessment'"
            @closeCreateAssessment="closeCreateModal"
            :editable="mainSectionData"
            :main="true"
            :mainSection="mainSection"
            :schoolAdmin="computedSchoolAdmin"
            :edit="createModalEdit === 'assessment'"
        ></create-assessment>
        
        <create-lesson
            :show="showCreateModal === 'lesson'"
            @createLessonDisappear="closeCreateModal"
            :editable="mainSectionData"
            :mainSection="mainSection"
            :edit="createModalEdit === 'lesson'"
            @classSuccessfullyEdited="successfullyEdited"
        ></create-lesson>
        
        <create-extracurriculum
            :show="showCreateModal === 'extracurriculum'"
            @closeCreateExtracurriculum="closeCreateModal"
            :editable="mainSectionData"
            :schoolAdmin="computedSchoolAdmin"
            :edit="createModalEdit === 'extracurriculum'"
            @extracurriculumSuccessfullyEdited="successfullyEdited"
        ></create-extracurriculum>

        <create-collaboration
            :show="showCreateModal === 'collaboration'"
            @closeCreateCollaboration="closeCreateModal"
            :editable="mainSectionData"
            :mainSection="mainSection"
            :schoolAdmin="computedSchoolAdmin"
            :edit="createModalEdit === 'collaboration'"
            @collaborationSuccessfullyEdited="successfullyEdited"
        ></create-collaboration>

        <create-program
            :show="showCreateModal === 'program'"
            @closeCreateProgram="closeCreateModal"
            :editable="mainSectionData"
            :mainSection="mainSection"
            :schoolAdmin="computedSchoolAdmin"
            :edit="createModalEdit === 'program'"
            @programSuccessfullyEdited="successfullyEdited"
        ></create-program>
        
        <create-course
            :show="showCreateModal === 'course'"
            @closeCreateCourse="closeCreateModal"
            :editable="mainSectionData"
            :mainSection="mainSection"
            :schoolAdmin="computedSchoolAdmin"
            :edit="createModalEdit === 'course'"
            @courseSuccessfullyEdited="successfullyEdited"
        ></create-course>

        <activity-modal
            v-if="showActivityModal"
            :show="showActivityModal"
            :account="activitiesAccount"
            :admin="computedAccountDetails"
            @activityModalDisappear="closeModal('activity')"
            @viewItem="viewItem"
        ></activity-modal>
        <invitation-modal
            v-if="showInvitationModal"
            :show="showInvitationModal"
            :account="account"
            :wards="computedWards"
            :admin="computedSchoolAdmin"
            :type="invitationType"
            @invitationDisappear="showInvitationModal = false"
        ></invitation-modal>
        <edit-profile></edit-profile>

        <lesson-modal
            :show="viewDashboardItem === 'lesson'"
            @closeLessonModal="viewDashboardItem = ''"
        ></lesson-modal>

        <dashboard-item-modal
            :show="viewDashboardItem === 'item'"
            @closeDashboardItemModal="viewDashboardItem = ''"
        ></dashboard-item-modal>

        <view-account-items
            :show="viewMoreItems.length > 0"
            :item="viewMoreItems"
            @closeViewAccountItems="viewMoreItems = ''"
            @clickedDashboardActionButton="clickedDashboardActionButton"
        ></view-account-items>

        <view-comments
            v-if="itemType === 'comment' || itemType === 'answer'"
            :show="true"
            :itemData="itemData"
            @viewModalDisappear="closeItemViewModals"
        ></view-comments>
        <post-modal
            v-if="itemType.length && itemType === 'post' || 
                itemType === 'question' || itemType === 'activity' || itemType === 'poem' ||
                itemType === 'riddle' || itemType === 'book'"
            :show="true"
            :type="itemType"
            :itemData="itemData"
            @mainModalDisappear="closeItemViewModals"
        ></post-modal>
        <modal-switcher
            v-if="itemType === 'discussion' || itemType === 'class' || 
                itemType === 'course' || itemType === 'lesson'"
            :show="true"
            :itemData="itemData"
        ></modal-switcher>
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    @disappear="hideSmallModal"
                    :show="showSmallModal"
                    class="small-modal"
                    :title="smallModalTitle"
                    :main="smallModalMain"
                    :loading="smallModalLoading"
                >
                    <template slot="actions" v-if="!smallModalMain">
                        <post-button
                            buttonText="yes"
                            buttonStyle="success"
                            @click="clickedSmallModalButton"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            buttonStyle="danger"
                            @click="clickedSmallModalButton"
                        ></post-button>
                    </template>
                    <template slot="other" 
                        v-if="smallModalMain && !smallModalLoading"
                    >
                        <div class="item"
                            @click="banTypeSelection('overall')"
                            :class="{selected: banType === 'overall'}"
                            v-if="smallModalData.type === 'ban'"
                        >overall</div>
                        <div class="item"
                            @click="banTypeSelection('post')"
                            :class="{selected: banType === 'post'}"
                            v-if="smallModalData.type === 'ban'"
                        >post</div>
                        <div class="item"
                            @click="banTypeSelection('comment')"
                            :class="{selected: banType === 'comment'}"
                            v-if="smallModalData.type === 'ban'"
                        >comment</div>
                        <div class="item"
                            @click="banTypeSelection('answer')"
                            :class="{selected: banType === 'answer'}"
                            v-if="smallModalData.type === 'ban'"
                        >answer</div>
                        <template
                            v-if="smallModalData.type === 'unban'"
                        >
                            <div class="item"
                                v-for="(itemBan,index) in smallModalData.data.bans"
                                :key="index"
                                @click="banSelection(itemBan)"
                                :class="{selected: ban && ban.id === itemBan.id}"                                
                            >
                                <div class="main">
                                    {{`ban created at: ${getReadableDate(itemBan.createdAt)}`}}
                                </div>
                                <div class="due" v-if="itemBan.dueDate">
                                    {{`will be due on: ${getReadableDate(itemBan.dueDate)}`}}
                                </div>
                                <div class="info">
                                    {{`state: ${itemBan.state}, type: ${itemBan.type}`}}
                                </div>
                            </div>
                        </template>
                        <post-button
                            buttonText="ok"
                            buttonStyle="success"
                            class="post-button"
                            @click="clickedSmallModalButton"
                        ></post-button>
                    </template>
                    <template slot="other" 
                        v-if="smallModalMain && smallModalData.type === 'unban'"
                    >
                    </template>
                </small-modal>
            </template>
        </fade-up>
    </div>
</template>

<script>
import PostButton from '../PostButton'
import AccountInfo from './AccountInfo'
import DashboardItemBadge from './DashboardItemBadge'
import ProfilePicture from '../profile/ProfilePicture'
import FadeRight from '../transitions/FadeRight'
import EditProfile from '../forms/EditProfile'
import ActionButton from '../ActionButton'
import CreateClass from '../forms/CreateClass'
import CreateAssessment from '../forms/CreateAssessment'
import CreateProgram from '../forms/CreateProgram'
import CreateCourse from '../forms/CreateCourse'
import CreateCollaboration from '../forms/CreateCollaboration'
import CreateExtracurriculum from '../forms/CreateExtracurriculum'
import CreateLesson from '../forms/CreateLesson'
import InfiniteLoader from 'vue-infinite-loading'
import CommentSingle from '../CommentSingle'
import AddComment from '../AddComment'
import FadeUp from '../transitions/FadeUp'
import AccountBadge from '../dashboard/AccountBadge'
import OptionalActions from '../OptionalActions'
import DashboardRequestModal from './DashboardRequestModal'
import ActivityModal from './ActivityModal'
import InvitationModal from '../InvitationModal'
import MainSelect from '../MainSelect'
import DashboardSubSection from './DashboardSubSection'
import DashboardSectionAccount from './DashboardSectionAccount'
import DashboardMainSection from './DashboardMainSection'
import DashboardActionButton from './DashboardActionButton'
import ViewComments from '../ViewComments'
import ViewAccountItems from './ViewAccountItems'
import LessonModal from '../LessonModal'
import DashboardItemModal from './DashboardItemModal'
import PostModal from '../PostModal'
import ModalSwitcher from '../ModalSwitcher'
import AttachmentModal from '../AttachmentModal'
import WardModal from './WardModal'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import { mapActions, mapGetters } from 'vuex'
import { dates } from '../../services/helpers'
import {bus} from '../../app';
    export default {
        components: {
            PulseLoader,
            WardModal,
            AttachmentModal,
            ModalSwitcher,
            PostModal,
            DashboardItemModal,
            LessonModal,
            ViewAccountItems,
            ViewComments,
            DashboardActionButton,
            DashboardMainSection,
            DashboardSectionAccount,
            DashboardSubSection,
            MainSelect,
            InvitationModal,
            ActivityModal,
            DashboardRequestModal,
            OptionalActions,
            AccountBadge,
            FadeUp,
            AddComment,
            CommentSingle,
            InfiniteLoader,
            CreateProgram,
            CreateAssessment,
            CreateClass,
            CreateLesson,
            CreateExtracurriculum,
            CreateCourse,
            ActionButton,
            EditProfile,
            FadeRight,
            ProfilePicture,
            DashboardItemBadge,
            AccountInfo,
            PostButton,
            CreateCollaboration,
        },
        props: {
            barSmall: {
                type: Boolean,
                default: true
            },
            type: {
                type: String,
                default: ''
            },
            activeButton: {
                type: String,
                default: ''
            },
            account: {
                type: Object,
                default(){
                    return {}
                }
            },
        },
        data() {
            return {
                showHeaderDropdown: false,
                loading: false,
                subSelectionLoading: false,
                full: false,
                subSelection: '',
                showSelection: '',
                activePostButton: 'info',
                selection: '',
                //main section
                mainSection: '',
                mainSectionData: null,
                mainSectionComments: [],
                mainSectionCommentsLoading: false,
                mainSectionLoading: false,
                commentsNextPage: 1,
                showCreateModal: '',
                createModalEdit: '',
                //requests
                showRequests: false,
                actionRequests: '',
                showOptionalActions: false,
                //invitation
                showInvitationModal: false,
                invitationType: '',
                //ward
                ward: null,
                showWardModal: false,
                //youredu users
                usersNextPage: 1,
                usersLoading: false,
                //youredu accounts
                accountsNextPage: 1,
                accountsLoading: false,
                //youredu admins
                adminsNextPage: 1,
                adminsLoading: false,
                activitiesAccount: null,
                banType: '',
                ban: null,
                //small modal
                showActivityModal: false,
                showSmallModal: false,
                smallModalMain: false,
                smallModalLoading: false,
                smallModalTitle: '',
                smallModalData: null,
                //items
                itemType: '',
                itemData: null,
                //attachment
                showAttachmentModal: false,
                attachmentType: '',
                viewMoreItems: '',
                viewDashboardItem: '',
            }
        },
        mounted () {
            bus.
            $on('editLesson',()=>{
                this.showCreateModal = 'lesson'
                this.createModalEdit = 'lesson'
            }).
            $on('editClass',()=>{
                this.showCreateModal = 'class'
                this.createModalEdit = 'class'
            }).
            $on('editCourse',()=>{
                this.showCreateModal = 'course'
                this.createModalEdit = 'course'
            }).
            $on('editProgram',()=>{
                this.showCreateModal = 'program'
                this.createModalEdit = 'program'
            }).
            $on('editExtracurriculum',()=>{
                this.showCreateModal = 'extracurriculum'
                this.createModalEdit = 'extracurriculum'
            }).
            $on('editCollaboration',()=>{
                this.showCreateModal = 'collaboration'
                this.createModalEdit = 'collaboration'
            }).
            $on('viewDashboardItem',()=>{
                console.log('in main section');
                this.viewDashboardItem = 'item'
            }).
            $on('viewLesson',()=>{
                this.viewDashboardItem = 'lesson'
            })
        },
        watch: {
            showHeaderDropdown(newValue) {
                if (newValue) {
                    setTimeout(() => {
                        this.showHeaderDropdown = false
                    }, 5000);
                }
            },
            account(newValue,oldValue) {
                if (newValue && newValue.account) {
                    this.mainSection = ''
                    this.getAccountDetails(newValue)
                    if (newValue.account === 'school') {
                        this.schoolListen(newValue.accountId)
                    } else if (oldValue && oldValue.account === 'school') {
                        this.schoolUnlisten(oldValue.accountId)
                    }
                }
            },
            activeButton(newValue) {
                this.activePostButton = newValue
            },
            showOptionalActions(newValue){
                if (newValue) {
                    setTimeout(() => {
                        this.showOptionalActions = false
                    }, 4000);
                }
            },
            mainSectionData: {
                deep: true,
                handler(newValue,oldValue){
                    if (newValue && newValue.type === 'class') {
                        this.classListen(newValue.id)
                    } else if (oldValue && oldValue.type === 'class') {
                        this.classUnlisten(oldValue.id)
                    }
                    if (newValue && newValue.type === 'school') {
                        this.schoolListen(newValue.id)
                    } else if (oldValue && oldValue.type === 'school') {
                        this.schoolUnlisten(oldValue.id)
                    }
                    if (newValue && newValue.type === 'course') {
                        this.courseListen(newValue.id)
                    } else if (oldValue && oldValue.type === 'course') {
                        this.courseUnlisten(oldValue.id)
                    }
                }
            },
        },
        computed: {
            ...mapGetters(['getUser','getProfiles','dashboard/getCurrentAccount',
                'dashboard/getAccountDetails',"dashboard/getMainSectionComments",
                'dashboard/getAdmins','dashboard/getAccounts']),
            computedUserName() {
                return this.getUser ? this.getUser.username : '' 
            },
            computedWards(){
                return this["dashboard/getAccountDetails"].wards ? 
                    this["dashboard/getAccountDetails"].wards : []
            },
            //schools not main section
            computedSchoolAdmin(){
                return this.computedAccountDetails && this.computedAccountDetails.admin ?
                    this.computedAccountDetails.admin : null
            },
            computedSchoolAdmins(){
                return this.computedAccountDetails && this.computedAccountDetails.admins ?
                    this.computedAccountDetails.admins : null
            },
            computedSchoolGrades(){
                return this.computedAccountDetails && this.computedAccountDetails.grades ?
                    this.computedAccountDetails.grades : null
            },
            computedSchoolSubjects(){
                return this.computedAccountDetails && this.computedAccountDetails.subjects ?
                    this.computedAccountDetails.subjects : null
            },
            computedSchoolFacilitators(){
                return this.computedAccountDetails && this.computedAccountDetails.facilitators ?
                    this.computedAccountDetails.facilitators : null
            },
            computedSchoolProfessionals(){
                return this.computedAccountDetails && this.computedAccountDetails.professionals ?
                    this.computedAccountDetails.professionals : null
            },
            computedSchoolLearners(){
                return this.computedAccountDetails && this.computedAccountDetails.learners ?
                    this.computedAccountDetails.learners : null
            },
            computedSchoolParents(){
                return this.computedAccountDetails && this.computedAccountDetails.parents ?
                    this.computedAccountDetails.parents : null
            },
            computedCollaborations(){
                return this['dashboard/getAccountDetails'] && 
                    this['dashboard/getAccountDetails'].collaborations ?
                    this['dashboard/getAccountDetails'].collaborations : null
            },
            //end of schools
            computedDob() {
                return this.getUser.dob ? dates.dateReadable(this.getUser.dob) : '' 
            },
            computedBottomTitle(){
                return this.type === 'user' ? 'current information' : 
                    this.type === 'account' ? `${this.account.account} account information` : ''
            },
            computedAccountDetailsWards(){
                return this.computedCurrentAccount.account !== 'parent' ? null :
                    this.computedAccountDetails.wards.filter(ward=>{
                        return ward.userId !== this.getUser.id
                    })
                
            },
            computedAccountDetails(){
                return this['dashboard/getAccountDetails']
            },
            computedCurrentAccount(){
                return this.computedSchoolAdmin ? {
                    accountId: this.computedSchoolAdmin.id,
                    account: 'admin',
                    level: this.computedSchoolAdmin.level
                } : this['dashboard/getCurrentAccount']
            },
            computedLearners(){
                if (this.computedAccountDetails && this.computedAccountDetails.account === 'school') {
                    return this.computedAccountDetails.learners.map(learner=>{
                        return {
                            sectionOne: learner.name,
                            id: learner.id,
                        }
                    })
                }
                return []
            },
            computedAccountLessons() {
                return (this.computedAccountDetails.account === 'facilitator' ||
                    this.computedAccountDetails.account === 'professional') &&
                    this.computedAccountDetails.lessons ? 
                    this.computedAccountDetails.lessons : []
            },
            computedAccountAssessments() {
                return (this.computedAccountDetails.account === 'facilitator' ||
                    this.computedAccountDetails.account === 'professional') &&
                    this.computedAccountDetails.assessments ? 
                    this.computedAccountDetails.assessments : []
            },
            computedParents(){
                
            },
            computedFacilitators(){
                
            },
            computedProfessionals(){
                
            },
            computedSchools(){
                if (this.computedAccountDetails && 
                    this.computedAccountDetails.account !== 'school' ||
                     this.computedAccountDetails.schools) {
                    return this.computedAccountDetails.schools.map(item=>{
                        return {
                            sectionOne: item.company_name ? item.company_name : item.name,
                            sectionTwo: item.role,
                            sectionThree: item.about ? item.about : '',
                            id: item.id,
                        }
                    })
                }
                return []
            },
            computedGrades(){
                
            },
            computedClasses(){
                let classes = []
                if (this.computedAccountDetails && 
                    this.computedAccountDetails.account === 'school') {
                    classes.push(...this.computedAccountDetails.ownedClasses)
                } else if (this.computedAccountDetails && 
                    this.computedAccountDetails.account !== 'school' && 
                    this.computedAccountDetails.account !== 'facilitator') {
                    classes.push(...this.computedAccountDetails.classes)
                } else if (this.computedAccountDetails && 
                    this.computedAccountDetails.account === 'facilitator') {
                    classes.push(...this.computedAccountDetails.classes)
                }
                return this.getDashboardItemBadgeClasses(classes)
            },
            computedSubjects(){
                return this.computedAccountDetails.subjects
            },
            computedExtracurriculums(){
                
            },
            computedCurriculums(){
                
            },
            computedOwnedPrograms() {
                return this.computedAccountDetails && 
                    this.computedAccountDetails.ownedPrograms ? 
                    this.computedAccountDetails.ownedPrograms : []
            },
            computedCourses(){
                if (this.computedAccountDetails && 
                    this.computedAccountDetails.courses) {
                    return this.computedAccountDetails.courses.map(item=>{
                        return {
                            sectionOne: item.name,
                            sectionTwo: `lessons: ${item.lessons}`,
                            sectionThree: item.description ? item.description : '',
                            id: item.id,
                        }
                    })
                }
                return []
            },
            computedPrograms(){
                return this['dashboard/getAccountDetails'].programs ?
                    this.computedAccountDetails.programs.map(item=>{
                        return {
                            sectionOne: item.name,
                            sectionTwo: '',
                            sectionThree: item.description ? item.description : '',
                            id: item.id,
                        }
                    }) : []
            },
            //facilitator and professional
            computedOwnedExtracurriculums(){
                if (this.computedCurrentAccount.account === 'facilitator' ||
                    this.computedCurrentAccount.account === 'professional' ||
                    this.computedCurrentAccount.account === 'school'||
                    this.computedCurrentAccount.account === 'admin') {
                    return this.computedAccountDetails.ownedExtracurriculums
                }
                return null
            },
            computedOwnedClasses(){
                if (this.computedCurrentAccount.account === 'facilitator' ||
                    this.computedCurrentAccount.account === 'professional' ||
                    this.computedCurrentAccount.account === 'school' ||
                    this.computedCurrentAccount.account === 'admin') {
                    return this.computedAccountDetails.ownedClasses
                }
                return null
            },
            computedCurricula(){
                if (this.computedAccountDetails.curriculum) this.computedAccountDetails.curriculum
                return null
            },
            computedOwnedCourses(){
                if (this.computedCurrentAccount.account === 'facilitator' ||
                    this.computedCurrentAccount.account === 'professional' || 
                    this.computedCurrentAccount.account === 'school' ||
                    this.computedCurrentAccount.account === 'admin') {
                    return this.computedAccountDetails.ownedCourses
                }
                return null
            },
            computedCounselling(){
                if (this.computedAccountDetails.counselling) this.computedAccountDetails.counselling
                return null
            },
            computedSchedules(){
                if (this.computedAccountDetails.schedules) this.computedAccountDetails.schedules
                return null
            },
            //main section
            computedIdMainSection(){
                return this.mainSectionData ? this.mainSectionData.id : null
            },
            computedMainProfessional(){
                if (this.computedMainOwner && this.mainSectionData && 
                    this.mainSectionData.professionals) {
                    let index = this.mainSectionData.professionals.findIndex(professional=>{
                        return professional.userId === this.getUser.id
                    })
                    if (index > -1) {
                        return this.mainSectionData.professionals[index]
                    }
                }
                return null
            },
            computedMainParent(){
                if (this.computedMainOwner && this.mainSectionData && 
                    this.mainSectionData.parents) {
                    let index = this.mainSectionData.parents.findIndex(parent=>{
                        return parent.userId === this.getUser.id
                    })
                    if (index > -1) {
                        return this.mainSectionData.parents[index]
                    }
                }
                return null
            },
            computedMainOwner(){ //for classes
                return this.mainSectionData && this.mainSectionData.ownedby ? 
                    this.mainSectionData.ownedby : this.computedCurrentAccount.owner ?
                    this.computedCurrentAccount : null
            },
            computedMainAdmin(){ //school admins and class owner (facilitator)
                if (this.computedAccountDetails.account === 'facilitator' ||
                    this.computedAccountDetails.account === 'professional') {
                    return this.computedMainOwner
                } else if (this.computedSchoolAdmin) {
                    return this.computedSchoolAdmin
                }
                return null
            },
            computedMainFacilitator(){
                if (this.computedMainOwner && this.mainSectionData && 
                    this.mainSectionData.facilitators) {
                    let index = this.mainSectionData.facilitators.findIndex(facilitator=>{
                        return facilitator.userId === this.getUser.id
                    })
                    if (index > -1) {
                        return this.mainSectionData.facilitators[index]
                    }
                }
                return null
            },
            computedMainLearner(){
                if (this.computedMainOwner && this.mainSectionData && 
                    this.mainSectionData.learners) {
                    let index = this.mainSectionData.learners.findIndex(learner=>{
                        return learner.userId === this.getUser.id
                    })
                    if (index > -1) {
                        return this.mainSectionData.learners[index]
                    }
                }
                return null
            },
            computedSchoolAuthority() {
                return this.computedCurrentMainAccount?.hasOwnProperty('owner') ||
                    this.computedCurrentMainAccount?.hasOwnProperty('admin')
            },
            computedCurrentMainAccount(){
                return this.computedMainOwner ? {owner: true, account: this.computedMainOwner} :
                    this.computedMainAdmin ? {admin: true, account: this.computedMainAdmin} :
                    this.computedMainFacilitator ? {facilitator: true, account: this.computedMainFacilitator} :
                    this.computedMainProfessional ? {professional: true, account: this.computedMainProfessional} :
                    this.computedMainLearner ? {learner: true, account: this.computedMainLearner} :
                    this.computedMainParent ? {parent: true, account: this.computedMainParent} : null
            },
            computedAccount(){ //use this for dashboard main section
                return this.computedCurrentMainAccount
                // if (this.mainSection === 'class' || this.mainSection === 'course' ||
                //     this.mainSection === 'extracurriculum' || this.mainSection === 'school') {
                //     return this.computedCurrentMainAccount
                // } else if (this.mainSection === 'school') {
                //     return this.computedCurrentSchoolAccount
                // }
                return null
            },
            computedCurrentSectionAccount(){ // use this for comments, likes, etc
                if (this.mainSection === 'class' || this.mainSection === 'course' ||
                    this.mainSection === 'school' || this.mainSection === 'extracurriculum') {
                    return this.computedCurrentMainAccount ?
                        this.computedCurrentMainAccount.account : null
                } 
                // else if (this.mainSection === 'school') {
                //     return this.computedCurrentSchoolAccount ?
                //         this.computedCurrentSchoolAccount.account : null
                // }
                return null
            },
            computedMainSectionComments(){
                return this['dashboard/getMainSectionComments']
            },
            //for youredu admin
            computedUsers(){
                return this['dashboard/getUsers']
            },
            computedAdmins(){
                return this['dashboard/getAdmins']
            },
            computedAccounts(){
                return this['dashboard/getAccounts']
            },
        },
        methods: {
            ...mapActions(['dashboard/getDashboardAccountDetails',
                "dashboard/getSectionItemData",'dashboard/addAccountDetails',
                "dashboard/getSectionItemComments",'dashboard/newComment',
                'dashboard/removeComment','dashboard/updateComment',
                'dashboard/addClass','dashboard/updateClass','dashboard/removeClass',
                'dashboard/addExtracurriculum','dashboard/updateExtracurriculum',
                'dashboard/removeExtracurriculum',
                'dashboard/addLesson','dashboard/updateLesson','dashboard/removeLesson',
                'dashboard/fetchUsers','dashboard/fetchAdmins',"dashboard/fetchAccounts",
                "dashboard/banUser",'dashboard/deleteAccountAttachments',
                'dashboard/addAccountAttachments','dashboard/addCourse',
                'dashboard/updateCourse','dashboard/removeCourse',
                'dashboard/deleteClass','dashboard/deleteCourse',
                'dashboard/deleteLesson','dashboard/deleteExtracurriculum',
                'dashboard/addProgram','dashboard/updateProgram',
                'dashboard/removeProgram','dashboard/deleteProgram',
                'dashboard/addCollaboration','dashboard/updateCollaboration',
                'dashboard/removeCollaboration','dashboard/deleteCollaboration',
                'dashboard/addAssessment','dashboard/updateAssessment',
                'dashboard/removeAssessment','dashboard/deleteAssessment',
            ]),
            clickedHeaderDropdown() {
                this.showHeaderDropdown = !this.showHeaderDropdown
            },
            getDashboardItemBadgeClasses(classes) {
                let msg = ''
                return classes.map(item=>{
                    if (item.maxLearners) {
                        msg = `max learners: ${item.maxLearners}`
                    }
                    if (item.description.length) {
                        msg = `description: ${item.description}`
                    }
                    return {
                        sectionOne: item.name,
                        sectionTwo: item.state,
                        sectionThree: msg,
                        id: item.id,
                    }
                })
            },
            viewItem(data){
                this.itemData = data
                this.itemType = data.item
            },
            closeItemViewModals(){
                this.itemType = ''
                this.itemData = null
            },
            clickedDashboardActionButton(data){
                console.log(data);
                if (data.type === 'view more') {
                    this.viewMoreItems = data.subText
                } else if (data.text === 'add admin') {
                    this.invitationType = data.text
                    this.showInvitationModal = true
                } else if (data.buttonData) {
                    if (data.buttonData.text === 'ban') {
                        let name = data.buttonData.data.username ? 
                            data.buttonData.data.fullName : data.buttonData.data.name
                        this.smallModalTitle = `are you sure you want to ban ${name}?`
                        this.smallModalData = {type: 'ban' , data: data.buttonData.data}
                        this.showSmallModal = true
                    } else if (data.buttonData.text === 'unban') {
                        let name = data.buttonData.data.username ? 
                            data.buttonData.data.fullName : data.buttonData.data.name
                        this.smallModalTitle = `are you sure you want to unban ${name}?`
                        this.smallModalData = {type: 'unban' , data: data.buttonData.data}
                        this.showSmallModal = true
                    }  else if (data.buttonData.text === 'activities') {
                        this.activitiesAccount = data.buttonData.data
                        this.showActivityModal = true
                    } else if (data.type === 'grade'|| data.type === 'subject' ||
                        data.type === 'course' || data.type === 'program') {
                        this.smallModalTitle = `are you sure you want to unattach ${data.buttonData.data.data.name}?`
                        this.smallModalData = {type: 'unattach' , data: data.buttonData.data}
                        this.showSmallModal = true
                    } else if (data.type === 'owned extracurriculum' ||
                        data.type === 'lesson' ||
                        data.type === 'owned program' ||
                        data.type === 'owned course' ||
                        data.type === 'owned class' ||
                        data.type === 'assessment' ||
                        data.type === 'collaboration') {
                        let name = data.type.includes('extracurriculum') ? 'extracurriculum' : 
                            data.type.includes('program') ? 'program' : 
                            data.type.includes('course') ? 'course' : 
                            data.type.includes('class') ? 'class' : 
                            data.type
                        if (data.buttonData.text === 'delete') {
                            this.smallModalTitle = `are you sure you want to delete ${name} with name: ${data.buttonData.data.name ?? data.buttonData.data.title}?`
                            this.smallModalData = {type: name , data: data.buttonData.data, action: 'delete'}
                            this.showSmallModal = true
                        } else if (data.buttonData.text === 'undo delete') {
                            this.smallModalTitle = `are you sure you want to undo deletion of ${name} with name: ${data.buttonData.data.name ?? data.buttonData.data.title}?`
                            this.smallModalData = {type: name , data: data.buttonData.data, action: 'undo'}
                            this.showSmallModal = true
                        } else if (data.buttonData.text === 'edit') {
                            let event = this.getEventName('edit',name)
                            this.createModalEdit = name
                            this.showCreateModal = name
                            bus.$emit(event, _.cloneDeep(data.buttonData.data))
                        } else if (data.buttonData.text === 'view') {
                            this.mainSection = name
                            this.getMainSectionData(_.cloneDeep(data.buttonData.data))
                        }
                    } else if (data.buttonData.icon === 'pencil-alt') {
                        this.$emit('accountModal',{
                            account: data.buttonData.data,
                            action: 'edit'
                        })
                    } else if (data.buttonData.text === 'profile') {
                        this.$router.push({
                            name: 'profile', 
                            params: {
                                account: data.buttonData.data.account,
                                accountId: data.buttonData.data.accountId
                            }
                        })
                    } else if (data.buttonData.text === 'remove facilitator' ||
                        data.buttonData.text === 'remove learner' || 
                        data.buttonData.text === 'remove parent' ||
                        data.buttonData.text === 'remove professional') {
                        this.deleteAccountAttachments(data.buttonData.data)
                    }
                } else if (data.text === 'add subject') {
                    this.attachmentType = data.text.slice(4)
                    this.showAttachmentModal = true
                } else if (data.text.includes('add')) {
                    this.createModalEdit = ''
                    let item = data.text.slice(4)
                    this.showCreateModal = item
                    bus.$emit(`${item}Ownership`)
                }
            },
            attachmentSuccess(data){
                console.log('data :>> ', data);
                
                this['dashboard/addAccountAttachments'](data)
            },
            closeModal(data){
                if (data === 'activity') {
                    this.showActivityModal = false
                    this.activitiesAccount = null
                } else if (data === 'attachment') {
                    this.attachmentType = ''
                    this.showAttachmentModal = false
                }
            },
            //small modal
            clickedSmallModalButton(data){
                console.log('data :>> ', data);
                if (data === 'yes') {
                    if (this.smallModalData.type === 'ban') {
                        this.smallModalTitle = 'please select type of ban'
                        this.smallModalMain = true
                    } else if (this.smallModalData.type === 'unban') {
                        this.smallModalTitle = 'please select the ban'
                        this.smallModalMain = true
                    } else if (this.smallModalData.type === 'unattach') {
                        this.smallModalContinueProcess()
                    } else if (this.smallModalData.type === 'class' || 
                        this.smallModalData.type === 'course' || 
                        this.smallModalData.type === 'lesson' ||
                        this.smallModalData.type === 'program' ||
                        this.smallModalData.type === 'collaboration' ||
                        this.smallModalData.type === 'assessment' ||
                        this.smallModalData.type === 'extracurriculum') {
                        this.smallModalContinueProcess()
                    }
                } else if (data === 'ok') {
                    this.smallModalContinueProcess()
                } else if (data === 'no') {
                    this.hideSmallModal()
                }
            },
            async smallModalContinueProcess(){
                this.smallModalTitle = ''
                this.smallModalLoading = true
                if (this.smallModalData.type === 'ban' ||
                    this.smallModalData.type === 'unban') {
                    await this.banUser()
                } else if (this.smallModalData.type === 'unattach') {
                    await this.deleteAccountAttachments()
                } else {
                    await this.deleteAccountItem(this.smallModalData)
                }
                this.smallModalLoading = false
                this.hideSmallModal()
            },
            hideSmallModal(){
                this.smallModalMain = false
                this.smallModalData = null
                this.showSmallModal = false
                this.smallModalTitle = ''
                this.ban = null
                this.banType = ''
            },
            getReadableDate(date){
                return dates.dateReadable(date)
            },
            schoolListen(schoolId){
                Echo.private(`youredu.school.${schoolId}`)
                    .listen('.newAttachableAccount',data=>{
                        console.log('data :>> ', data);
                        this['dashboard/addAccountDetails']({
                            account: this.account.account,
                            accountId: this.account.accountId,
                            what: data.type,
                            data: data.account
                        })
                    })
                    .listen('.newClass',data=>{
                        console.log('data :>> ', data);
                        let owner = this.checkOwnership(data.class)
                        this['dashboard/addClass']({
                            class: data.class,
                            owner
                        })
                    })
                    .listen('.updateClass',data=>{
                        console.log('data :>> ', data);
                        let owner = this.checkOwnership(data.class)
                        if (this.mainSection === 'class' &&
                            data.class.id === this.mainSectionData.id) {
                            this.mainSectionData = data.classResource
                        }
                        this['dashboard/updateClass']({
                            class: data.class,
                            owner
                        })
                    })
                    .listen('.deleteClass',data=>{
                        console.log('data :>> ', data);
                        let owner = this.checkOwnership(data.class)
                        if (this.mainSection === 'class' &&
                            data.class.id === this.mainSectionData.id) {
                            this.mainSection = ''
                            this.mainSectionData = null
                        }
                        this['dashboard/removeClass']({
                            classId: data.classId,
                            owner
                        })
                    })
                    .listen('.newCourse',data=>{
                        console.log('data :>> ', data);
                        let owner = this.checkOwnership(data.course)
                        if (this.mainSection === 'school' && 
                            data.course.ownedby.accountId === this.mainSectionData.id) {
                            this.mainSectionData.courses.push(data.course)
                        }
                        this['dashboard/addCourse']({
                            course: data.course,
                            owner
                        })
                    })
                    .listen('.updateCourse',data=>{
                        console.log('data :>> ', data);
                        let owner = this.checkOwnership(data.course)
                        if (this.mainSection === 'school' && 
                            data.course.ownedby.accountId === this.mainSectionData.id) {
                            let index = this.mainSectionData.courses.findIndex(course=>{
                                return course.id === data.course.id
                            })
                            if (index > -1) {
                                this.mainSectionData.courses.splice(index,1,data.course)
                            }
                        }
                        this['dashboard/updateCourse']({
                            course: data.course,
                            owner
                        })
                    })
                    .listen('.deleteCourse',data=>{
                        console.log('data :>> ', data);
                        //todo
                        let owner = this.checkOwnership(data.course)
                        if (this.mainSection === 'school' && 
                            data.course.ownedby.accountId === this.mainSectionData.id) {
                            let index = this.mainSectionData.courses.findIndex(course=>{
                                return course.id == data.courseId
                            })
                            if (index > -1) {
                                this.mainSectionData.courses.splice(index,1)
                            }
                        }
                        this['dashboard/removeCourse']({
                            course: data.courseId,
                            owner
                        })
                    })
            },
            checkOwnership(data) {    //data is class or course              
                return this.computedCurrentAccount.account === data.ownedby &&
                    this.computedCurrentAccount.accountId === data.ownedbyId
            },
            schoolUnlisten(schoolId){
                Echo.leaveChannel(`youredu.school.${schoolId}`)
            },
            classListen(classId){
                Echo.private(`youredu.class.${classId}`)
                    .listen('.newComment',comment=>{
                        console.log('comment :>> ', comment);
                        this['dashboard/newComment'](comment)
                    })
                    .listen('.deleteComment',comment=>{
                        console.log('comment :>> ', comment);
                        this['dashboard/removeComment'](comment)
                    })
                    .listen('.updateComment',comment=>{
                        console.log('comment :>> ', comment);
                        this['dashboard/updateComment'](comment)
                    })
                    .listen('.updateClass',data=>{
                        console.log('data :>> ', data);
                        this.mainSectionData = data.classResource
                        this['dashboard/updateClass']({
                            class: data.class,
                            owner: false
                        })
                    })
                    .listen('.deleteClass',data=>{
                        console.log('data :>> ', data);
                        this.mainSection = ''
                        this.mainSectionData = null
                        this['dashboard/removeClass']({
                            classId: data.classId,
                            owner: false
                        })
                    })
                    .listen('.newCourse',data=>{
                        console.log('data :>> ', data);
                        if (this.mainSection === 'class') {
                            this.mainSectionData.courses.push(data.course)
                        }
                    })
                    .listen('.updateCourse',data=>{
                        console.log('data :>> ', data);
                        if (this.mainSection === 'class') {
                            let index = this.mainSectionData.courses.findIndex(course=>{
                                return course.id === data.course.id
                            })
                            if (index > -1) {
                                this.mainSectionData.courses.splice(index,1,data.course)
                            }
                        }
                    })
                    .listen('.deleteCourse',data=>{
                        console.log('data :>> ', data);
                        if (this.mainSection === 'class') {
                            let index = this.mainSectionData.courses.findIndex(course=>{
                                return course.id === data.courseId
                            })
                            if (index > -1) {
                                this.mainSectionData.courses.splice(index,1)
                            }
                        }
                    })
            },
            classUnlisten(classId){
                Echo.leaveChannel(`youredu.class.${classId}`)
            },
            //listen to a course
            courseListen(courseId){
                Echo.private(`youredu.course.${courseId}`)
                    .listen('.newComment',comment=>{
                        console.log('comment :>> ', comment);
                        this['dashboard/newComment'](comment)
                    })
                    .listen('.deleteComment',comment=>{
                        console.log('comment :>> ', comment);
                        this['dashboard/removeComment'](comment)
                    })
                    .listen('.updateComment',comment=>{
                        console.log('comment :>> ', comment);
                        this['dashboard/updateComment'](comment)
                    })
                    .listen('.updateCourse',data=>{
                        console.log('data :>> ', data);
                        //todo
                    })
                    .listen('.deleteCourse',data=>{
                        console.log('data :>> ', data);
                        //todo
                    })
            },
            courseUnlisten(courseId){
                Echo.leaveChannel(`youredu.course.${courseId}`)
            },
            async deleteAccountItem(item) {
                console.log('item :>> ', item);
                let response,
                    attribute, //finding the attribute of the account in which to determine facilitation
                    data = {action: item.action}

                if (this.computedSchoolAdmin) {
                    data.adminId = this.computedSchoolAdmin.id
                }
                if (item.type === 'class') {
                    attribute = `${item.type}es`
                } else {
                    attribute = `${item.type}s`
                }
                if (this['dashboard/getAccountDetails'][attribute]) {
                    data.hasAttribute = true
                }
                if (item.type === 'class') {
                    data.classId = item.data.id
                    response = await this['dashboard/deleteClass'](data)
                } else if (item.type === 'course') {
                    data.courseId = item.data.id
                    response = await this['dashboard/deleteCourse'](data)
                } else if (item.type === 'extracurriculum') {
                    data.extracurriculumId = item.data.id
                    response = await this['dashboard/deleteExtracurriculum'](data)
                } else if (item.type === 'lesson') {
                    data.lessonId = item.data.id
                    response = await this['dashboard/deleteLesson']({
                        data,items:item.data.items
                    })
                } else if (item.type === 'program') {
                    data.programId = item.data.id
                    response = await this['dashboard/deleteProgram'](data)
                } else if (item.type === 'collaboration') {
                    data.collaborationId = item.data.id
                    data.account = item.data.addedby.account
                    data.accountId = item.data.addedby.accountId
                    response = await this['dashboard/deleteCollaboration'](data)
                } else if (item.type === 'assessment') {
                    data.assessmentId = item.data.id
                    data.account = item.data.addedby.account
                    data.accountId = item.data.addedby.accountId
                    response = await this['dashboard/deleteAssessment'](data)
                }

                if (response.status) {
                    this.emitEventsListeners({
                        data: response.data ? response.data : item.data.id,
                        action: response.action,
                        type: item.type
                    })
                } else {
                    console.log('response :>> ', response);
                }
            },
            emitEventsListeners(data) {
                let event = this.getEventName(data.action,data.type)
                bus.$emit(event,data.data)
            },
            getEventName(action,item) {
                return `${action}${item.charAt(0).toUpperCase()}${item.slice(1)}`
            },
            async unattachAccount(account) {
                let response,
                    data = {
                        account: account.account,
                        accountId: account.accountId
                    }

                response = await this['dashboard/unattachAccount'](data)
                if (response.status) {
                    
                } else {
                    console.log('response :>> ', response);
                }
            },
            clickedDashboardItem(data){
                if (data.heading === 'classes') {
                    this.mainSection = 'class'
                } else if (data.heading === 'learners') {
                    this.mainSection = 'learner'
                } else if (data.heading === 'schools') {
                    this.mainSection = 'school'
                } else if (data.heading === 'courses') {
                    this.mainSection = 'course'
                }
                this.getMainSectionData(data.data)
            },
            clickedAction(data){
                if (data === 'requests') {
                    this.showOptionalActions = true
                    return
                }
                
                this.showInvitationModal = true
            },
            clickedOptionalAction(data){
                this.showOptionalActions = false
                if (data === 'send request') {
                    this.showInvitationModal = true
                } else if (data === 'view requests') {
                    this.actionRequests = 'view'
                    this.showRequests = true
                }
            },
            successfullyEdited(classResource){
                this.mainSectionData = classResource
            },
            closeCreateModal() {
                this.showCreateModal = ''
                this.createModalEdit = ''
            },
            clickedEditSomething(data){
                this.createModalEdit = data
                this.showCreateModal = data
            },
            async deleteAccountAttachments(item) { //for removing attachments like grade, subject
                let response,
                    data = {
                        account: this.mainSectionData ? this.mainSection :
                            this.computedCurrentAccount.account,
                        accountId: this.mainSectionData ? this.mainSectionData.id :
                            this.computedCurrentAccount.accountId,
                        item: item ? item.account : this.smallModalData.data.type
                            .slice(0,this.smallModalData.data.type.length - 1),
                        itemId: item ? item.accountId : this.smallModalData.data.data.id,
                        mainSection: this.mainSectionData ? true : false
                    }

                response = await this['dashboard/deleteAccountAttachments'](data)

                if (response.status) {
                    if (data.mainSection) {
                        let index = this.mainSectionData[this.smallModalData.data.type].findIndex(item=>{
                            if (item.data) {                                
                                return item.data.id === this.smallModalData.data.data.id
                            }
                        })
                        if (index > -1) {
                            this.mainSectionData[this.smallModalData.data.type].splice(index,1)
                        }
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            //main section
            async getMainSectionData(item){
                let response,
                    data = {
                        item: this.mainSection,
                        itemId: item.id
                    }

                this.mainSectionLoading = true

                response = await this["dashboard/getSectionItemData"](data)

                this.mainSectionLoading = false
                if (response.status) {
                    this.mainSectionData = response.mainSectionData
                    this.getMainSectionComments()
                } else {
                    console.log('response :>> ', response);
                }
            },
            async getMainSectionComments(){
                let data = await this.getComments()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.commentsNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.commentsNextPage += 1
                }
            },
            async getComments(){
                let response,
                    data = {
                        item: this.mainSection,
                        itemId: this.mainSectionData.id,
                        nextPage: this.commentsNextPage
                    }

                this.mainSectionCommentsLoading = true

                response = await this["dashboard/getSectionItemComments"](data)

                this.mainSectionCommentsLoading = false
                if (response.status) {
                    return {next: response.next}
                } else {
                    console.log('response :>> ', response);
                }
            },
            async commentsInfiniteLoader($state){
                if (this.commentsNextPage === null) {
                    $state.complete()
                    return
                }

                let data = await this.getComments()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.commentsNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.commentsNextPage += 1
                }
            },
            dashboardCommentCreated(comment){
                this.mainSectionComments.unshift(comment)
            },
            clickedCommentMedia(){
                this.$emit('clickedMedia',data)
            },
            commentDeleteSuccess(data){
                let index =  this.mainSectionComments.findIndex(comment=>{
                    return comment.id == data.commentId
                })
                if (index > -1) {
                    this.mainSectionComments.splice(index,1)
                }
            },
            postModalCommentEdited(comment){
                let index =  this.mainSectionComments.findIndex(comnt=>{
                    return comnt.id == comment.id
                })
                if (index > -1) {
                    this.mainSectionComments.splice(index,1)
                    this.mainSectionComments.unshift(comment)
                }
            },
            async getUsersForAdmin(){
                let data = await this.getUsers()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.usersNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.usersNextPage += 1
                }
            },
            async getUsers(){
                let response,
                    data = {
                        account: this.account.account,
                        accountId: this.account.accountId,
                        nextPage: this.usersNextPage
                    }

                response = await this["dashboard/fetchUsers"](data)

                if (response.status) {
                    return {next: response.next}
                } else {
                    console.log('response :>> ', response);
                }
            },
            async usersInfiniteLoader($state){
                if (this.usersNextPage === null) {
                    $state.complete()
                    return
                }

                let data = await this.getUsers()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.usersNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.usersNextPage += 1
                }
            },
            //accounts for superadmin and supervisoradmin
            async getAccountsForAdmin(){
                let data = await this.getAccounts()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.accountsNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.accountsNextPage += 1
                }
            },
            async getAccounts(){
                let response,
                    data = {
                        account: this.account.account,
                        accountId: this.account.accountId,
                        nextPage: this.accountsNextPage
                    }
                this.usersLoading = true

                response = await this["dashboard/fetchAccounts"](data)

                this.usersLoading = false
                if (response.status) {
                    return {next: response.next}
                } else {
                    console.log('response :>> ', response);
                }
            },
            async accountsInfiniteLoader($state){
                if (this.accountsNextPage === null) {
                    $state.complete()
                    return
                }

                let data = await this.getAccounts()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.accountsNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.accountsNextPage += 1
                }
            },
            banTypeSelection(data){
                this.banType = data
            },
            banSelection(data){
                this.ban = data
            },
            async getAdminsForAdmin(){
                let data = await this.getAdmins()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.adminsNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.adminsNextPage += 1
                }
            },
            async getAdmins(){
                let response,
                    data = {
                        account: this.account.account,
                        accountId: this.account.accountId,
                        nextPage: this.adminsNextPage
                    }
                this.adminsLoading = true

                response = await this["dashboard/fetchAdmins"](data)

                this.adminsLoading = false
                if (response.status) {
                    return {next: response.next}
                } else {
                    console.log('response :>> ', response);
                }
            },
            async adminsInfiniteLoader($state){
                if (this.adminsNextPage === null) {
                    $state.complete()
                    return
                }

                let data = await this.getAdmins()

                if (data.hasOwnProperty('next') && !data.next) {
                    this.adminsNextPage = null
                } else if (data.hasOwnProperty('next')) {
                    this.adminsNextPage += 1
                }
            },
            async banUser(){
                if (this.smallModalData.type === 'ban' && !this.banType.length) {
                    this.smallModalTitle = 'please select a type before continuing'
                    return
                } else if (this.smallModalData.type === 'unban' && !this.ban) {
                    this.smallModalTitle = 'please select a ban before continuing'
                    return
                }
                let response,
                    data = {
                        action: this.smallModalData.type,
                        adminId: this.computedCurrentAccount.accountId,
                        account: this.smallModalData.data.username ? 'user' : 
                            this.smallModalData.data.account,
                        accountId: this.smallModalData.data.username ? 
                            this.smallModalData.data.id : 
                            this.smallModalData.data.accountId,
                    }

                if (this.smallModalData.type === 'ban') {
                    data.state = 'served'
                    data.type = this.banType
                } else if (this.smallModalData.type === 'unban') {
                    data.banId = this.ban.id
                }

                response = await this["dashboard/banUser"](data)

                if (response.status) {
                    
                } else {
                    console.log('response :>> ', response);
                }
            },
            ////
            clickedMainSectionBack(){
                this.mainSection = ''
            },
            clickedMainSection(){
                if (!this.barSmall) {
                    this.$emit('clickedMainSection')
                }
            },
            clickedSectionIcon(data){
                if (this.showSelection === data) {
                    this.showSelection = ''
                } else this.showSelection = data
            },
            clickedPostButton(data){
                if (data === `${this.type} info`) {
                    this.activePostButton = 'info'
                } else if (data === 'create class' ||
                    data === 'create course' || data === 'create extracurriculum' ||
                    data === 'create lesson' || data === 'create program') {
                    this.createModalEdit = ''
                    this.showCreateModal = data.substr(7)
                    this.activePostButton = data
                } else {
                    this.activePostButton = data
                    this.$emit('clickedPostButton',{type:this.type,data})
                }
            },
            clickedAccount(data){
                this.$emit('clickedAccount',{type:'account',account:data.params})
            },
            clickedAccountProfile(data){
                if (this.showSelection === 'parents') {
                    if (this.subSelection.length) {
                        this.subSelection = ''
                    } else this.subSelection = 'parent'
                }
            },
            clickedWard(ward){
                this.showWardModal = true
                this.ward = ward
            },
            async getAccountDetails(account){
                if (this.computedCurrentAccount &&
                    this.computedCurrentAccount.account == account.account &&
                    this.computedCurrentAccount.accountId == account.accountId) {
                    return
                }
                this.loading = true
                this.activePostButton = 'info'
                let response,
                    data = {
                        account:account.account,
                        accountId:account.accountId,
                        owner: true
                    }

                response = await this['dashboard/getDashboardAccountDetails'](data)

                if (response.status) {
                    if (this.account.account === 'admin') {
                        this.getUsersForAdmin()
                        if (this.computedAccountDetails.role === 'SUPERADMIN') {
                            this.getAdminsForAdmin()
                        }
                        this.getAccountsForAdmin()
                    }
                } else {
                    console.log('response :>> ', response);
                }
                this.loading = false
            },
            clickedUsername(){
                this.$emit('clickedAccount',{type:'user'})
            },
        },
    }
</script>

<style lang="scss" scoped>
// $background-main: whitesmoke;
$background-color-section: white;

    .dashboard-section{
        width: 100%;
        // height: 100vh;
        padding: 0 0 0 50px;
        transition: all 1s ease-in-out;

        .no-ward{
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: gray;
        }

        .small-modal{

            .post-button{
                margin: 20px auto 0;
            }
        }

        .mt-8{
            margin-top: 80px !important
        }

        .dashboard-header{
            padding: 10px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            margin: 0 auto;
            color: rgba(105, 105, 105, 0.7);
            text-align: center;
            border-bottom: 2px solid dimgray;
            display: inline-flex;
            justify-content: space-between;
            width: calc(100% - 50px);
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 1;
            background: $color-primary;

            .youredu{

            }

            .user{
                width: 150px;
                display: inline-flex;
                justify-content: flex-end;
                align-items: center;
                
                .username{
                    font-size: 12px;
                    text-transform: lowercase;
                }

                .dropdown-icon{
                    margin-left: 5px;
                    font-size: 16px;
                }

                .dropdown-section{
                    position: absolute;
                    top: 100%;
                    z-index: 100;
                    padding: 10px;
                    border-radius: 10px;
                    background: white;
                    font-size: 14px;
                    min-width: 100px;

                    .item{
                        padding: 5px;
                        border-radius: inherit;
                        margin-bottom: 5px;

                        &:hover{
                            transition: all .5s linear;
                            box-shadow: 0 0 2px grey;
                        }
                    }
                }
            }
        }

        .top-section,
        .middle-section,
        .bottom-section{
            background: $background-color-section;
            width: 100%;
            max-width: 700px;

            .loading{
                width: 100%;
                height: 70px;
                display: flex;
                justify-content: center;
                align-items: center;
                transition: all .3s linear;
            }
            
            .main-title{
                @include main-section-headings(14px);
                margin: 10px auto;
                width: 100%;
                position: relative;

                .icon{
                    float: right;
                    padding: 5px 10px;
                    cursor: pointer;
                    color: gray;
                    font-size: 18px;
                }
            }

        }

        .top-section{
            margin: 0px auto 20px;
            position: relative;
            min-height: 100px;
            transition: all .5s linear;

            .heading{
                @include main-section-headings(16px);
                text-transform: capitalize;
            }

            .title{
                font-size: 14px;
                color: gray;
                text-transform: capitalize;
                font-weight: 500;
                width: 100%;
                text-align: start;
                padding: 5px;
            }
        }

        .middle-section{
            margin: 0px auto 10px;
            position: relative;
            min-height: 100px;
            transition: all .5s linear;

            .action-buttons,
            .selection-badges{
                width: 100%;
                display: flex;
                justify-content: flex-start;
                align-items: center;
                padding: 5px;
                flex-wrap: wrap;

                .post-button{
                    margin-right: 10px;
                    margin-bottom: 10px;
                }
            }

            .selection-badges{
                flex-wrap: nowrap; 
                overflow-x: auto;

                .dashboard-badge{
                    margin: 0 10px 10px;
                    min-height: 100px;
                    min-width: 100px;
                }
            }
        }

        .bottom-section{
            min-height: 100px;
            margin: 10px auto 80px;

            .info{
                width: 100%;
                height: 90px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .view-requests{
                float: right;
            }

            .user-info,
            .account-info{

                .accounts{
                    padding: 5px;
                    text-align: center;
                    box-shadow: 0 0 2px black;
                    margin: 10px 0;

                    .title{
                        font-size: 14px;
                        color: gray;
                        width: 100%;
                    }
                    
                    .account-info{

                    }
                }
            }
            
            .user-info{
                font-size: 14px;                

                .body{        
                    padding: 5px;    
                    box-shadow: 0 0 2px black;

                    .info-item{
                        width: 45%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: flex-start;
    
                        .label{
                            padding-right: 5px;
                            min-width: fit-content;
                            color: gray;
                        }
    
                        .value{
                            width: 100%;
                            @include text-overflow();
                        }
                    }
                }
            }

            .account-info{
                padding: 5px 0 50px;

                .top{
                    display: flex;
                    align-items: flex-start;
                    width: 100%;
                    padding: 10px;
                    max-width: 600px;
                    margin: 0 auto;

                    .profile-picture{
                        max-width: 60px;
                        height: 60px;
                        margin-right: 10px;
                    }

                    .other{
                        width: 100%;

                        .name{
                            @include text-overflow();
                            text-transform: capitalize;
                            font-size: 14px;
                        }

                        .type{
                            width: 100%;
                            text-align: end;
                            font-size: 12px;
                            color: gray;
                        }
                    }
                }

                .middle{

                    .section{

                        .heading{
                            font-size: 14px;
                            color: gray;
                            position: relative;
                            padding: 5px;

                            .icon{
                                position: absolute;
                                font-size: 16px;
                                color: gray;
                                top: 0;
                                right: 0;
                                padding: 5px;
                                cursor: pointer;
                            }
                        }

                        .sub-main{

                            table{
                                border-bottom: 1px solid gray;

                                thead{

                                    th{
                                        font-size: 14px;
                                        font-weight: 14px;
                                    }
                                }

                                tbody{

                                    tr{
                                        cursor: pointer;

                                        th{
                                            font-size: 12px;
                                            text-transform: capitalize;
                                            font-weight: 400;
                                        }
                                    }
                                }
                            }

                            .dashboard-section-account{
                                margin: 0 0 10px;
                            }
                        }

                        .main{

                            .accounts{
                                padding: 0;
                                box-shadow: none;
                            }
                        }
                    }

                    .section-active{
                        min-height: 150px;
                        border: none;
                        border-right: 2px solid;
                        transition: all .5s ease;

                        .heading{
                            border-bottom: 1px solid gray;
                            transition: all .5s ease;
                        }
                    }

                }
            }
        }

        .main-section{
            margin: 80px auto 80px;
            max-width: 700px;

            .loading{
                text-align: center;
            }

            .section{

                .back{
                    font-size: 18px;
                    color: gray;
                    cursor: pointer;
                    position: absolute;
                    right: 10px;
                    z-index: 1;
                    padding: 0 10px 10px;
                }

                .comments-section{
                    padding: 10px;
                    margin-top: 10px;
                    min-height: 100px;
                    max-height: 250px;
                    overflow-y: auto;
                }
            }
        }

        .top-section.full,
        .middle-section.full{
            height: 0;
            opacity: 0;
            display: none;
        }

        .dashboard-footer{
            position: fixed;
            bottom: 0;
            padding: 10px;
            width: 100%;
            background: $color-primary;
        }
    }

    .dashboardShift{
        transition: all 1s ease-in-out;
        padding: 0;
        margin: 0 0 0 200px;

        .dashboard-header{
            justify-content: flex-start;
        }
    }


@media screen and (max-width: 800px) {
    
    .dashboard-section{

    }
}

@media screen and (max-width: 600px) {
    
    .dashboard-section{

        .bottom-section{
            
            .user-info{

                .body{

                    .info-item{
                        width: 100%;
                    }
                }
            }
        }
    }
}
</style>