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
        <div class="top-section" 
            v-if="!mainSection.length"
            :class="{full}"
        >
            <div class="heading">
                {{`welcome ${getUser.full_name} to your dashboard.`}}
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
            v-if="type === 'account' && !mainSection.length"
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
                    heading="collaborations"
                    :items="computedCollaborations"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account === 'school' || account.account === 'facilitator' ||
                        account.account === 'professional'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
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
                    v-if="account.account === 'facilitator' || account.account === 'school' ||
                        account.account === 'learner'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    heading="subjects"
                    :items="computedSubjects"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account === 'learner' || account.account === 'school' || 
                        account.account === 'facilitator'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    heading="extracurriculum"
                    :items="computedExtracurriculums"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account !== 'parent'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    heading="curriculum"
                    :items="computedCurriculums"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account !== 'parent' && account.account !== 'professional'"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    heading="courses"
                    :items="computedCourses"
                    @clickedItem="clickedDashboardItem"
                ></dashboard-item-badge>
                <dashboard-item-badge
                    class="dashboard-badge"
                    :hasItems="true"
                    heading="programs"
                    :items="computedPrograms"
                    @clickedItem="clickedDashboardItem"
                    v-if="account.account !== 'parent'"
                ></dashboard-item-badge>
            </div>
        </div>
        <div class="bottom-section" v-if="!mainSection.length">
            <div class="info" v-if="!loading && !type.length">
                Yaay...get active
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
                    v-if="account && account.account !== 'school'"
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
                        :key="account.params.account + account.params.accountId"
                        @click="clickedAccount(account)"
                        :name="account.name"
                        :type="account.params.account"
                    >
                    </account-info>
                </div>
            </div>
            <div class="account-info" 
                v-if="!loading && type === 'account' && activePostButton === 'info'"
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
                                subText="admins"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(admin,index) in computedSchoolAdmins"
                                            :key="index"
                                            type="admin"
                                            :account="admin"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolAdmins || !computedSchoolAdmins.length">
                                            no admins
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add admin"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
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
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolGrades || !computedSchoolGrades.length">
                                            no grades
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add grade"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
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
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolSubjects || !computedSchoolSubjects.length">
                                            no subjects
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add subject"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                subText="programs"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(program,index) in computedSchoolPrograms"
                                            :key="index"
                                            type="program"
                                            :account="program"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolPrograms || !computedSchoolPrograms.length">
                                            no programs
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add program"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
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
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolFacilitators || !computedSchoolFacilitators.length">
                                            no facilitators
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add facilitator"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
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
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolProfessionals || !computedSchoolProfessionals.length">
                                            no professionals
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add professional"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
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
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolLearners || !computedSchoolLearners.length">
                                            no learners
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add learner"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
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
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolParents || !computedSchoolParents.length">
                                            no parents
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add parent"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
                                </template>
                            </dashboard-sub-section>
                            <dashboard-sub-section
                                subText="collaborations"
                            >
                                <template slot="body">
                                    <div class="sub-main">
                                        <dashboard-section-account
                                            class="dashboard-section-account"
                                            v-for="(collaboration,index) in computedSchoolCollaboration"
                                            :key="index"
                                            type="collaboration"
                                            :account="collaboration"
                                            @clickedDashboardActionButton="clickedDashboardActionButton"
                                        >                                            
                                        </dashboard-section-account>
                                        <div class="no-ward" v-if="!computedSchoolCollaboration || !computedSchoolCollaboration.length">
                                            no collaborations
                                        </div>
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add collaboration"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
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
                                        <dashboard-action-button
                                            class="add-another"
                                            text="add admin"
                                            icon="plus"
                                            :data="null"
                                            @click="clickedDashboardActionButton"
                                        ></dashboard-action-button>
                                    </div>
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
            <div class="section">
                <div class="back" @click="clickedMainSectionBack">
                    <font-awesome-icon :icon="['fa','long-arrow-alt-left']"></font-awesome-icon>
                </div>
                <dashboard-main-section
                    :type="mainSection"
                    :account="computedAccount"
                    :mainSectionData="mainSectionData"
                    @clickedEditClass="clickedEditClass"
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

        <create-class
            v-if="showEditClass"
            :show="showEditClass"
            @closeCreateClass="showEditClass = false"
            :editableClass="mainSectionData"
            :schoolAdmin="computedSchoolAdmin"
            :edit="true"
            @classSuccessfullyEdited="classSuccessfullyEdited"
        ></create-class>

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

        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    @disappear="hideSmallModal"
                    :show="showSmallModal"
                    class="small-modal"
                    :title="smallModalTitle"
                    :main="smallModalMain"
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
                    <template slot="other" v-if="smallModalMain">
                        <div class="item"
                            @click="banTypeSelection('overall')"
                            v-if="!isLearner"
                        >overall</div>
                        <div class="item"
                            @click="banTypeSelection('post')"
                            v-if="!isParent"
                        >post</div>
                        <div class="item"
                            @click="banTypeSelection('comment')"
                            v-if="!isFacilitator"
                        >comment</div>
                        <div class="item"
                            v-if="professionalsCount < 3"
                            @click="banTypeSelection('answer')"
                        >answer</div>
                        <post-button
                            buttonText="ok"
                            buttonStyle="success"
                            class="post-button"
                            @click="clickedSmallModalButton"
                        ></post-button>
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
import InfiniteLoader from 'vue-infinite-loading'
import CommentSingle from '../CommentSingle'
import AddComment from '../AddComment'
import FadeUp from '../transitions/FadeUp'
import AccountBadge from '../dashboard/AccountBadge'
import OptionalActions from '../OptionalActions'
import DashboardRequestModal from './DashboardRequestModal'
import InvitationModal from '../InvitationModal'
import MainSelect from '../MainSelect'
import DashboardSubSection from './DashboardSubSection'
import DashboardSectionAccount from './DashboardSectionAccount'
import DashboardMainSection from './DashboardMainSection'
import DashboardActionButton from './DashboardActionButton'
import WardModal from './WardModal'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import { mapActions, mapGetters } from 'vuex'
import { dates } from '../../services/helpers'
    export default {
        components: {
            PulseLoader,
            WardModal,
            DashboardActionButton,
            DashboardMainSection,
            DashboardSectionAccount,
            DashboardSubSection,
            MainSelect,
            InvitationModal,
            DashboardRequestModal,
            OptionalActions,
            AccountBadge,
            FadeUp,
            AddComment,
            CommentSingle,
            InfiniteLoader,
            CreateClass,
            ActionButton,
            EditProfile,
            FadeRight,
            ProfilePicture,
            DashboardItemBadge,
            AccountInfo,
            PostButton,
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
                showEditClass: false,
                //requests
                showRequests: false,
                actionRequests: '',
                showOptionalActions: false,
                //invitation
                showInvitationModal: false,
                invitationType: false,
                //ward
                ward: null,
                showWardModal: false,
                //youredu users
                usersNextPage: 1,
                //youredu admins
                adminsNextPage: 1,
                banType: '',
                //small modal
                showSmallModal: false,
                smallModalMain: false,
                smallModalTitle: '',
                smallModalData: null,
            }
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
                    if (newValue.type === 'class') {
                        this.classListen(newValue.id)
                    } else if (oldValue.type === 'class') {
                        this.classUnlisten(newValue.id)
                    }
                }
            },
        },
        computed: {
            ...mapGetters(['getUser','getProfiles','dashboard/getCurrentAccount',
                'dashboard/getAccountDetails',"dashboard/getMainSectionComments",
                'dashboard/getUsers','dashboard/getAdmins']),
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
            computedSchoolPrograms(){
                return this.computedAccountDetails && this.computedAccountDetails.programs ?
                    this.computedAccountDetails.programs : null
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
            computedSchoolCollaboration(){
                return this.computedAccountDetails && this.computedAccountDetails.collaborations ?
                    this.computedAccountDetails.collaborations : null
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
                return this['dashboard/getCurrentAccount']
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
            computedParents(){
                
            },
            computedFacilitators(){
                
            },
            computedProfessionals(){
                
            },
            computedCollaborations(){
                
            },
            computedSchools(){
                
            },
            computedGrades(){
                
            },
            computedClasses(){
                if (this.computedAccountDetails && this.computedAccountDetails.account === 'school') {
                    return this.computedAccountDetails.ownedClasses.map(item=>{
                        let msg = ''
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
                }
                return []
            },
            computedSubjects(){
                
            },
            computedExtracurriculums(){
                
            },
            computedCurriculums(){
                
            },
            computedCourses(){
                
            },
            computedPrograms(){

            },
            //main section
            computedIdMainSection(){
                return this.mainSectionData ? this.mainSectionData.id : null
            },
            computedClassOwner(){
                return this.mainSectionData && this.mainSectionData.ownedby ? 
                    this.mainSectionData.ownedby : null
            },
            computedClassAdmin(){ //school admins and class owner (facilitator)
                if (this.computedAccountDetails.account === 'facilitator') {
                    return this.computedClassOwner
                }
                if (this.computedClassOwner) {
                    return this.computedSchoolAdmin
                }
                return null
            },
            computedClassFacilitator(){
                if (this.computedClassOwner) {
                    let index = this.mainSectionData.facilitators.findIndex(facilitator=>{
                        return facilitator.userId === this.getUser.id
                    })
                    if (index > -1) {
                        return this.mainSectionData.facilitators[index]
                    }
                }
                return null
            },
            computedClassLearner(){
                if (this.computedClassOwner) {
                    let index = this.mainSectionData.learners.findIndex(learner=>{
                        return learner.userId === this.getUser.id
                    })
                    if (index > -1) {
                        return this.mainSectionData.learners[index]
                    }
                }
                return null
            },
            computedMainSectionComments(){
                return this['dashboard/getMainSectionComments']
            },
            computedCurrentClassAccount(){
                return this.computedClassOwner ? {owner: true, account: this.computedClassOwner} :
                    this.computedClassAdmin ? {admin: true, account: this.computedClassAdmin} :
                    this.computedClassFacilitator ? {facilitator: true, account: this.computedClassFacilitator} :
                    this.computedClassLearner ? {learner: true, account: this.computedClassLearner} : null
            },
            computedAccount(){ //use this for dashboard main section
                if (this.mainSection === 'class') {
                    return this.computedCurrentClassAccount
                }
                return null
            },
            computedCurrentSectionAccount(){ // use this for comments, likes, etc
                if (this.mainSection === 'class') {
                    return this.computedCurrentClassAccount ?
                        this.computedCurrentClassAccount.account : null
                }
                return null
            },
            //for youredu admin
            computedUsers(){
                return this['dashboard/getUsers']
            },
            computedAdmins(){
                return this['dashboard/getAdmins']
            },
        },
        methods: {
            ...mapActions(['dashboard/getDashboardAccountDetails',
                "dashboard/getSectionItemData",'dashboard/addAccountDetails',
                "dashboard/getSectionItemComments",'dashboard/newComment',
                'dashboard/removeComment','dashboard/updateComment',
                'dashboard/addClass','dashboard/updateClass','dashboard/removeClass',
                'dashboard/fetchUsers','dashboard/fetchAdmins',
                "dashboard/banUser"]),
            clickedHeaderDropdown() {
                this.showHeaderDropdown = !this.showHeaderDropdown
            },
            clickedDashboardActionButton(data){
                console.log(data);
                if (data.text === 'add admin') {
                    this.invitationType = data.text
                    this.showInvitationModal = true
                } else if (data.buttonData) {
                    if (data.buttonData.icon === 'pencil-alt') {
                        this.$emit('accountModal',{
                            account: data.buttonData.data,
                            action: 'edit'
                        })
                    } else if (data.buttonData.icon === 'ban') {
                        let name = data.buttonData.data.username ? 
                            data.buttonData.data.full_name : data.buttonData.data.name
                        this.smallModalTitle = `are you sure you want to ban ${name}?`
                        this.smallModalData = {type: 'ban' , data: data.buttonData.data}
                        this.showSmallModal = true
                    }
                }
            },
            //small modal
            async clickedSmallModalButton(data){
                console.log('data :>> ', data);
                if (data === 'yes') {
                    if (this.smallModalData.type === 'ban') {
                        this.smallModalMain = true
                        this.smallModalTitle = 'please select type of ban'
                    }
                } else if (data === 'ok') {
                    if (this.smallModalData.type === 'ban') {
                        await this.banUser()
                    }
                } else if (data === 'no') {
                    this.hideSmallModal()
                }
            },
            hideSmallModal(){
                this.smallModalMain = false
                this.smallModalData = null
                this.showSmallModal = false
                this.smallModalTitle = ''
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
                        let owner = this.computedCurrentAccount.account === data.class.ownedby &&
                            this.computedCurrentAccount.accountId === data.class.ownedbyId
                        this['dashboard/addClass']({
                            class: data.class,
                            owner
                        })
                    })
                    .listen('.updateClass',data=>{
                        console.log('data :>> ', data);
                        let owner = this.computedCurrentAccount.account === data.class.ownedby &&
                            this.computedCurrentAccount.accountId === data.class.ownedbyId
                        if (this.mainSection === 'class' &&
                            this.data.class.id === this.mainSectionData.id) {
                            this.mainSectionData = data.classResource
                        }
                        this['dashboard/updateClass']({
                            class: data.class,
                            owner
                        })
                    })
                    .listen('.deleteClass',data=>{
                        console.log('data :>> ', data);
                        let owner = this.computedCurrentAccount.account === data.class.ownedby &&
                            this.computedCurrentAccount.accountId === data.class.ownedbyId
                        if (this.mainSection === 'class' &&
                            this.data.class.id === this.mainSectionData.id) {
                            this.mainSection = ''
                            this.mainSectionData = null
                        }
                        this['dashboard/removeClass']({
                            classId: data.classId,
                            owner
                        })
                    })
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
                    .listen('.updateClass',data=>{ //for facilitator owned
                        console.log('data :>> ', data);
                        this.mainSectionData = data.classResource
                        this['dashboard/updateClass']({
                            class: data.class,
                            owner: false
                        })
                    })
                    .listen('.deleteClass',data=>{ //for facilitator owned
                        console.log('data :>> ', data);
                        this.mainSection = ''
                        this.mainSectionData = null
                        this['dashboard/removeClass']({
                            classId: data.classId,
                            owner: false
                        })
                    })
            },
            classUnlisten(classId){
                Echo.leaveChannel(`youredu.class.${classId}`)
            },
            clickedDashboardItem(data){
                if (data.heading === 'classes') {
                    this.mainSection = 'class'
                } else if (data.heading === 'learners') {
                    
                }
                this.getMainSectionData(data.data)
            },
            clickedAction(data){
                if (data === 'requests') {
                    this.showOptionalActions = true
                } else if (data === 'send requests') {
                    this.showInvitationModal = true
                }
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
            classSuccessfullyEdited(classResource){
                this.mainSectionData = classResource
            },
            clickedEditClass(){
                this.showEditClass = true
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
            //comments for main section
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
            //users for superadmin and supervisoradmin
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
            //users for superadmin and supervisoradmin
            banTypeSelection(data){
                this.banType = data
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

                response = await this["dashboard/fetchAdmins"](data)

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
                if (!this.banType.length) {
                    this.smallModalTitle = 'please select a type before continuing'
                    return
                }
                let response,
                    data = {
                        action: this.smallModalData.type,
                        account: this.smallModalData.data.username ? 'user' : 
                            this.smallModalData.data.account,
                        accountId: this.smallModalData.data.id,
                        adminId: this.computedCurrentAccount.accountId,
                        state: 'served',
                        type: this.banType,
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
                } else {
                    this.activePostButton = data
                }
                this.$emit('clickedPostButton',{type:this.type,data})
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

                this.loading = false
                if (response.status) {
                    if (this.account.account === 'admin') {
                        this.getUsersForAdmin()
                        if (this.computedAccountDetails.role === 'SUPERADMIN') {
                            setTimeout(() => {                                
                                this.getAdminsForAdmin()
                            }, 1000);
                        }
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            clickedUsername(){
                this.$emit('clickedAccount',{type:'user'})
            },
        },
    }
</script>

<style lang="scss" scoped>
$color-main: rgba(127,255,212,1.0);
$background-color-main: whitesmoke;
$background-color-other: rgb(99,236,218);
$background-color-section: white;

@mixin text-overflow(){
    text-overflow: ellipsis;
    overflow: hidden;
    width: 100%;
    white-space: nowrap;
}

    .dashboard-section{
        width: 100%;
        height: 100vh;
        padding: 0 0 0 50px;
        transition: all 1s ease-in-out;
        background: $background-color-main;

        .small-modal{

            .post-button{
                margin: 20px auto 0;
            }
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
            width: 100%;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1;
            background: $background-color-other;

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

            .loading{
                width: 100%;
                height: 70px;
                display: flex;
                justify-content: center;
                align-items: center;
                transition: all .3s linear;
            }
            
            .main-title{
                margin: 10px auto;
                width: 100%;
                color: gray;
                font-weight: 500;
                padding: 10px;
                background: mintcream;
                font-size: 14px;
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
            margin: 20px auto;
            position: relative;
            min-height: 100px;
            transition: all .5s linear;

            .heading{
                font-size: 16px;
                text-transform: capitalize;
                text-align: center;
                background: mintcream;
                padding: 10px;
                font-weight: 500;
                color: gray;
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
            margin-bottom: 80px;

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
                padding: 5px;

                .top{
                    display: inline-flex;
                    align-items: flex-start;
                    width: 100%;

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

                            .no-ward{
                                width: 100%;
                                height: 100px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                font-size: 12px;
                                color: gray;
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

            .loading{
                text-align: center;
            }

            .section{
                max-height: 85vh;
                overflow-y: auto;
                overflow-x: hidden;

                .back{
                    font-size: 18px;
                    color: gray;
                    cursor: pointer;
                    position: absolute;
                    right: 20px;
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
            background: $background-color-other;
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