<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                @mainModalDisappear='closeModal'
                class="dashboard-item-modal-wrapper"
            >
                <template slot="main">
                    <div class="item"
                        v-if="item"
                    >
                        <div class="item-type">
                            {{item.type}}
                        </div>
                        <div class="item-heading">
                            {{computedHeading}}
                        </div>
                    </div>
                    <div class="details" v-if="dashboardItem">
                        <state-badge
                            :state="dashboardItem.state"
                        ></state-badge>

                        <div class="main" v-if="computedMainDetail">
                            {{computedMainDetail}}
                        </div>
                        <div class="other" v-if="computedOtherDetail">
                            {{computedOtherDetail}}
                        </div>
                    </div>
                    <rotate-loader
                        :loading="loading"
                    ></rotate-loader>
                    <dashboard-sub-section
                        subText="academic years"
                        v-if="computedShowAcademicYears"
                    >
                        <template slot="body">
                            <div class="actions">

                            </div>
                            <div class="main">
                                <item-badge
                                    v-for="(item,index) in dashboardItem.academicYears"
                                    :key="index"
                                    :item="item"
                                    type="academicYear"
                                    :hasRemove="false"
                                    class="class-badge"
                                ></item-badge>
                                <div class="no-data" v-if="!dashboardItem.academicYears.length">
                                    no academic years
                                </div>
                            </div>
                        </template>
                    </dashboard-sub-section>
                    <dashboard-sub-section
                        subText="academic year sections"
                        v-if="computedShowAcademicSections"
                    >
                        <template slot="body">
                            <div class="actions">

                            </div>
                            <div class="main">
                                <item-badge
                                    v-for="(item,index) in dashboardItem.sections"
                                    :key="index"
                                    :item="item"
                                    type="section"
                                    :hasRemove="false"
                                    class="class-badge"
                                ></item-badge>
                                <div class="no-data" v-if="!dashboardItem.sections.length">
                                    no academic year sections
                                </div>
                            </div>
                        </template>
                    </dashboard-sub-section>
                    <dashboard-sub-section
                        subText="facilitators"
                        v-if="computedShowFacilitators"
                    >
                        <template slot="body">
                            <div class="actions">

                            </div>
                            <div class="main">
                                <dashboard-section-account
                                    class="dashboard-section-account"
                                    v-for="(facilitator,index) in dashboardItem.facilitators"
                                    :key="index"
                                    :type="'facilitator'"
                                    :account="facilitator"
                                    @clickedDashboardActionButton="clickedDashboardActionButton"
                                >                                            
                                </dashboard-section-account>
                                <div class="no-data" v-if="!dashboardItem.facilitators.length">
                                    no facilitators
                                </div>
                            </div>
                        </template>
                    </dashboard-sub-section>
                    <dashboard-sub-section
                        subText="lessons"
                        v-if="computedShowLessons"
                    >
                        <template slot="body">
                            <div class="actions">

                            </div>
                            <div class="main">
                                <dashboard-section-account
                                    class="dashboard-section-account"
                                    v-for="(lesson,index) in dashboardItem.lessons"
                                    :key="index"
                                    :type="'lesson'"
                                    :account="lesson"
                                    @clickedDashboardActionButton="clickedDashboardActionButton"
                                >                                            
                                </dashboard-section-account>
                                <div class="no-data" v-if="!dashboardItem.lessons.length">
                                    no lessons
                                </div>
                                <dashboard-action-button
                                    class="add-another"
                                    text="add lesson"
                                    icon="plus"
                                    :data="null"
                                    v-if="computedFacilitator"
                                    @click="clickedDashboardActionButton"
                                ></dashboard-action-button>
                            </div>
                        </template>
                    </dashboard-sub-section>
                    <dashboard-sub-section
                        subText="facilitators"
                        v-if="computedShowFacilitators"
                    >
                        <template slot="body">
                            <div class="actions">

                            </div>
                            <div class="main">
                                <dashboard-section-account
                                    class="dashboard-section-account"
                                    v-for="(facilitator,index) in dashboardItem.facilitators"
                                    :key="index"
                                    :type="'facilitator'"
                                    :account="facilitator"
                                    @clickedDashboardActionButton="clickedDashboardActionButton"
                                >                                            
                                </dashboard-section-account>
                                <div class="no-data" v-if="!dashboardItem.facilitators.length">
                                    no facilitators
                                </div>
                            </div>
                        </template>
                    </dashboard-sub-section>
                    <dashboard-sub-section
                        subText="professionals"
                        v-if="computedShowProfessionals"
                    >
                        <template slot="body">
                            <div class="actions">

                            </div>
                            <div class="main">
                                <dashboard-section-account
                                    class="dashboard-section-account"
                                    v-for="(professional,index) in dashboardItem.professionals"
                                    :key="index"
                                    :type="'professional'"
                                    :account="professional"
                                    @clickedDashboardActionButton="clickedDashboardActionButton"
                                >                                            
                                </dashboard-section-account>
                                <div class="no-data" v-if="!dashboardItem.professionals.length">
                                    no professionals
                                </div>
                            </div>
                        </template>
                    </dashboard-sub-section>
                    <dashboard-sub-section
                        subText="discussion"
                        v-if="computedShowDiscussions"
                    >
                        <template slot="body">
                            <div class="actions">

                            </div>
                            <div class="main">
                                <discussion-single
                                    v-for="discussion in dashboardItem.discussions"
                                    :key="discussion.id"
                                    
                                ></discussion-single>
                                <div class="no-data" v-if="!dashboardItem.discussions.length">
                                    no discussions
                                </div>
                            </div>
                        </template>
                    </dashboard-sub-section>
                    <dashboard-sub-section
                        subText="comments"
                        v-if="dashboardItem"
                    >
                        <template slot="body">
                            <div class="actions">
                                <add-comment
                                    :what="item.type"
                                    :id="item.id"
                                    :showAddComment="true"
                                    :account="computedCurrentSectionAccount"
                                    :schoolAdmin="computedAdmin"
                                    @dashboardCommentCreated="dashboardCommentCreated"
                                ></add-comment>
                            </div>
                            <div class="comments-section"
                                v-if="comments"
                            >
                                <comment-single
                                    v-for="comment in comments"
                                    :key="comment.id" 
                                    :comment="comment"
                                    :dashboard="true"
                                    :account="computedCurrentSectionAccount"
                                    :schoolAdmin="computedAdmin"
                                    @clickedMedia="clickedCommentMedia"
                                    @commentDeleteSuccess="commentDeleteSuccess"
                                    @postModalCommentEdited="postModalCommentEdited"
                                ></comment-single>

                                <Infinite-Loading
                                    v-if="commentsNextPage !== 1"
                                    @infinite="commentsInfiniteLoader"
                                ></Infinite-Loading>
                            </div>
                            <div class="no-data" v-else>
                                no comments yet
                            </div>
                        </template>
                    </dashboard-sub-section>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import AddComment from '../AddComment.vue';
import ItemBadge from './ItemBadge.vue';
import StateBadge from './StateBadge.vue';
import DashboardSectionAccount from './DashboardSectionAccount.vue';
import DashboardSubSection from './DashboardSubSection.vue';
import { mapActions, mapGetters } from 'vuex'
import { bus } from '../../app'
    export default {
        components: {
            DashboardSubSection,
            DashboardSectionAccount,
            StateBadge,
            ItemBadge,
            AddComment,
        },
        props: {
            show: {
                type: Boolean,
                default: true
            },
            data: {
                type: Object,
                default() {
                    return null
                }
            }
        },
        data() {
            return {
                item: null,
                loading: false,
                dashboardItem: null,
                comments: null,
                commentsNextPage: 1,
                commentsLoading: false
            }
        },
        watch: {
            item(newValue) {
                if (newValue) {
                    this.getItemDetails()
                }
            }
        },
        mounted () {
            bus.$on('viewDashboardItem', (data)=>{
                console.log('in dashboard item modal');
                this.viewItem(data)
            })
        },
        computed: {
            ...mapGetters(['getUser']),
            computedHeading() {
                return this.dashboardItem ? this.dashboardItem.name : 
                    this.item ? this.item.name : '' 
            },
            computedAccount() {

            },
            computedMainDetail() {
                return this.dashboardItem?.description
            },
            computedOtherDetail() {

            },
            computedLearner() {
                return this.getUser ? this.dashboardItem?.learners?.find(learner=>{
                    return learner.userId === this.getUser.id
                }) : null
            },
            computedFacilitator() {
                return this.getUser ? this.dashboardItem?.facilitators?.find(facilitator=>{
                    return facilitator.userId === this.getUser.id
                }) : null
            },
            computedProfessional() {
                return this.getUser ? this.dashboardItem?.professionals?.find(professional=>{
                    return professional.userId === this.getUser.id
                }) : null
            },
            computedAdmin() {
                return this.getUser ? this.dashboardItem?.admins?.find(admin=>{
                    return admin.userId === this.getUser.id
                }) : null
            },
            computedParent() {
                return this.getUser ? this.dashboardItem?.parents?.find(parent=>{
                    return parent.userId === this.getUser.id
                }) : null
            },
            computedPayments() {
                let payments = []
                if (this.dashboardItem.fees) {
                    payments.push(this.dashboardItem.fees)
                }
                if (this.dashboardItem.subscriptions) {
                    payments.push(this.dashboardItem.subscriptions)
                }
                if (this.dashboardItem.prices) {
                    payments.push(this.dashboardItem.prices)
                }
                return payments
            },
            computedCurrentSectionAccount() {

            },
            computedShowFacilitators() {
                return this.dashboardItem ? this.dashboardItem.facilitators : null
            },
            computedShowLessons() {
                return this.dashboardItem ? this.dashboardItem.lessons : null
            },
            computedShowCourses() {
                return this.dashboardItem ? this.dashboardItem.courses : null
            },
            computedShowSubjects() {
                return this.dashboardItem ? this.dashboardItem.subjects : null
            },
            computedShowProfessionals() {
                return this.dashboardItem ? this.dashboardItem.professionals : null
            },
            computedShowDiscussions() {
                return this.dashboardItem ? this.dashboardItem.discussions : null
            },
            computedShowAttachments() {

            },
            computedShowAcademicYears() {
                return this.dashboardItem?.academicYears
            },
            computedShowAcademicSections() {
                return this.dashboardItem?.sections
            },
        },
        methods: {
            ...mapActions(['dashboard/getItemDetails','dashboard/getSectionItemComments']),
            closeModal() {
                this.clearData()
                this.$emit('closeDashboardItemModal')
            },
            clearData() {
                this.dashboardItem = null
                this.item = null
                this.comments = null
                this.loading = false
                this.commentsLoading = false
                this.commentsNextPage = 1
            },
            async getComments() {
                if (!this.item) {
                    return
                }
                this.commentsNextPage = 1
                let response = await this.getItemComments()

                this.comments = response.comments

                this.commentsNextPage = response.next ? this.commentsNextPage + 1 : null
            },
            async getItemComments() {
                let response,
                    data = {
                        item: this.item.type,
                        itemId: this.item.id,
                        nextPage: this.commentsNextPage,
                        notSection: true, //so it is not mutated to dashboard section comments
                    }

                this.commentsLoading = true

                response = await this["dashboard/getSectionItemComments"](data)

                this.commentsLoading = false
                if (response.status) {
                    return {next: response.next,comments: response.comments}
                } else {
                    console.log('response :>> ', response);
                }
            },
            async commentsInfiniteLoader($state) {
                if (!this.commentsNextPage) {
                    $state.complete()
                    return
                }
                let response = await this.getItemComments()

                this.comments = response.comments

                if (response.next) {
                    this.commentsNextPage += 1
                    $state.loaded()
                } else {
                    this.commentsNextPage = null
                    $state.loaded()
                }
            },
            async getItemDetails() {
                if (!this.item) {
                    return
                }
                let response

                this.loading = true

                response = await this['dashboard/getItemDetails']({
                    type: this.item.type,
                    id: this.item.id,
                })

                this.loading = false

                if (response.status) {
                    this.dashboardItem = response.item
                    this.getComments()
                } else {
                    console.log('response :>> ', response);
                }
            },
            viewItem(data) {
                console.log('data :>> ', data);
                this.item = data
            },
            clickedEditSomething(data){
                this.createModalEdit = data
                this.showCreateModal = data
            },
            clickedDashboardActionButton() {

            },
            dashboardCommentCreated() {

            },
            clickedCommentMedia() {

            },
            commentDeleteSuccess() {

            },
            postModalCommentEdited() {

            },
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-item-modal-wrapper{
        z-index: 10006;

        .v-spinner{
            text-align: center;
            margin: 50px;
        }

        .item{
            @include main-section-headings(14px);
            text-align: start;
            margin: 30px 0 20px;
            position: sticky;
            top: 0;
            display: flex;
            align-items: center;
            width: 100%;

            .item-type{
                width: fit-content;
                max-width: fit-content;
                padding: 10px;
                margin-right: 10px;
            }

            .item-heading{
                padding: 10px;
                text-align: center;
                text-transform: capitalize;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: 1px;
                width: 100%;
            }
        }
    }
</style>