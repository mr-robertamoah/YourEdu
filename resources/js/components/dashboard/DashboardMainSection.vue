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
        <div class="data" v-if="computedClassAdmin || computedClassOwner">
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
        </div>
        <dashboard-sub-section
            subText="collaboration"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.collaboration}}
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="facilitators"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.facilitators}}
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
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="lessons"
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.lessons}}
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="subjects"
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
        >
            <template slot="body">
                <div class="actions">

                </div>
                <div class="main">
                    {{mainSectionData.extracurriculums}}
                </div>
            </template>
        </dashboard-sub-section>
        <dashboard-sub-section
            subText="discussions"
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
    export default {
        components: {
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
        },
        computed: {
            computedName() {
                return this.type === 'class' ? this.mainSectionData.name : 
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
                    `${this.type} description: ${this.mainSectionData.description}` : ''
            },
        },
        methods: {
            clickedEditClass() {
                this.$emit('clickedEditClass')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .dashboard-main-section-wrapper{
        position: relative;

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