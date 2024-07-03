<template>
    <just-fade>
        <template slot="transition" v-if="show"> 
            <main-modal :show='show' 
                @mainModalDisappear='modalDisappear'
                :alertError="true"
                :requests="false"
                :mainOther="false"
            >
                <template slot="main">
                    <welcome-form :title="title" 
                        class="welcome-form"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="hideAlert"
                            ></auto-alert>

                            <div class="loading" v-if="creating">
                                <pulse-loader
                                    :loading="creating"
                                ></pulse-loader>
                            </div>
                            <text-input placeholder="name of academic year*"
                                v-model="academicYearData.name"
                                class="text-input"
                                :noBorder="true"
                            ></text-input>

                            <date-picker
                                :placeholder="computedStartDate"
                                @datePicked="getStartDate"
                                :bottomBorder="true"
                                class="date-picker"
                                :flatPickrConfig="{
                                    maxDate: academicYearStartDateMax,
                                    minDate: '',
                                    dateFormat: 'F j, Y',
                                    altFormat: 'F j, Y',
                                }"
                            ></date-picker>
                            
                            <date-picker
                                :placeholder="computedEndDate"
                                @datePicked="getEndDate"
                                :bottomBorder="true"
                                class="date-picker"
                                :flatPickrConfig="{
                                    maxDate: '',
                                    minDate: academicYearEndDateMin,
                                    dateFormat: 'F j, Y',
                                    altFormat: 'F j, Y',
                                }"
                            ></date-picker>

                            <text-textarea placeholder="description of this academic year"
                                v-model="academicYearData.description"
                                class="text-input"
                                :noBorder="true"
                            ></text-textarea>

                            <div class="note" v-if="academicYearData.name">
                                You can create many sections for this academic year.
                                But the sections must be within the start and end dates of the academic year.
                                If you are unsure about the end date of the academic year, you can leave it out and end the academic year later, when it is time. 
                            </div>
                            <div class="sections-preview" v-if="academicYearData.name">
                                <template
                                    v-if="sections.length"
                                >
                                    <academic-year-section-badge
                                        v-for="(section,index) in sections"
                                        :key="index"
                                        :section="section"
                                        @clickedSection="clickedSection"
                                        @clickedRemoveSection="clickedRemoveSection"
                                    />
                                </template>
                                <div class="section-preview" v-if="section.name">
                                    <div class="name">{{section.name}}</div>
                                    <div class="dates"
                                        v-if="section.startDate.length && section.endDate.length"
                                    >
                                        from {{section.startDate}} to {{section.endDate}}
                                    </div>
                                    <div class="close"
                                        @click="clickedRemoveSection(null)"
                                    >
                                        <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                                    </div>
                                    <div class="promotion"
                                        v-if="section.promotion"
                                    >
                                        promotional
                                    </div>
                                </div>
                            </div>
                            <div class="section" v-if="academicYearData.name">
                                <text-input placeholder="name of section*"
                                    v-model="section.name"
                                    class="text-input"
                                    :noBorder="true"
                                ></text-input>

                                <date-picker
                                    :placeholder="computedSectionStartDate"
                                    @datePicked="getSectionStartDate"
                                    :bottomBorder="true"
                                    class="date-picker"
                                    :flatPickrConfig="{
                                        maxDate: sectionStartDateMax,
                                        minDate: '',
                                        dateFormat: 'F j, Y',
                                        altFormat: 'F j, Y',
                                    }"
                                ></date-picker>
                                
                                <date-picker
                                    :placeholder="computedSectionEndDate"
                                    @datePicked="getSectionEndDate"
                                    :bottomBorder="true"
                                    class="date-picker"
                                    :flatPickrConfig="{
                                        maxDate: '',
                                        minDate: sectionEndDateMin,
                                        dateFormat: 'F j, Y',
                                        altFormat: 'F j, Y',
                                    }"
                                ></date-picker>

                                <main-checkbox
                                    v-model="section.promotion"
                                    label="check this if section is a promotional one"
                                    class="class-input"
                                ></main-checkbox>

                                <action-button
                                    text="add another section"
                                    @click="clickedAction"
                                    class="action-button"
                                    v-if="!creating"
                                ></action-button>
                            </div>

                            <action-button
                                text="create academic year"
                                @click="clickedAction"
                                class="action-button"
                                v-if="!creating"
                            ></action-button>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import TextInput from '../TextInput.vue'
import DatePicker from '../DatePicker.vue'
import MainCheckbox from '../MainCheckbox.vue'
import AutoAlert from '../AutoAlert.vue'
import ActionButton from '../ActionButton.vue'
import { mapActions, mapGetters } from 'vuex'
import { dates } from '../../services/helpers'
import TextTextarea from '../TextTextarea.vue'
import AcademicYearSectionBadge from '../dashboard/AcademicYearSectionBadge.vue'

    export default {
        components: {
            
            AcademicYearSectionBadge,
            TextTextarea,
            TextInput,
            ActionButton,
            AutoAlert,
            MainCheckbox,
            DatePicker,
        },        
        props: {
            show: {
                type: Boolean,
                default: false
            },
            school: {
                type: Object,
                default(){
                    return {}
                }
            },
        },
        data() {
            return {
                academicYearData: {
                    name: '',
                    startDate: '',
                    endDate: '',
                },
                section: {
                    name: '',
                    startDate: '',
                    endDate: '',
                    promotion: false
                },
                sections: [],
                title: 'create academic year',
                alertMessage: '',
                alertDanger: false,
                alertSuccess: false,
                creating: false,
                sectionStartDateMax: '',
                sectionEndDateMin: '',
                academicYearStartDateMax: '',
                academicYearEndDateMin: '',
            }
        },
        watch: {
            academicYearData: {
                deep: true,
                handler(newValue){
                    if (newValue.startDate.length) {
                        this.academicYearEndDateMin = newValue.startDate
                        this.sectionEndDateMin = newValue.startDate
                    }
                    if (newValue.endDate.length) {
                        this.sectionStartDateMax = newValue.endDate
                        this.academicYearStartDateMax = newValue.endDate
                    }
                }
            },
            section: {
                deep: true,
                handler(newValue){
                    if (newValue.startDate.length) {
                        this.sectionEndDateMin = newValue.startDate
                    }
                    if (newValue.endDate.length) {
                        this.sectionStartDateMax = newValue.endDate
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['dashboard/getCurrentAccount','getUser']),
            computedStartDate() {
                return this.academicYearData.startDate.length ? 
                    this.academicYearData.startDate : 'start date*' 
            },
            computedEndDate() {
                return this.academicYearData.endDate.length ? 
                    this.academicYearData.endDate : 'end date' 
            },
            computedSectionStartDate() {
                return this.section.startDate.length ? 
                    this.section.startDate : 'start date*' 
            },
            computedSectionEndDate() {
                return this.section.endDate.length ? 
                    this.section.endDate : 'end date' 
            },
            computedAdmin(){
                let index = this.school.admins.findIndex(admin=>{
                    return admin.userId === this.getUser.id
                })
                if (index > -1) {
                    return this.school.admins[index]
                }
                return null
            },
        },
        methods: {
            ...mapActions(['dashboard/createAcademicYear']),
            modalDisappear() {
                this.cleanUp()
                this.$emit('closeCreateAcademicYear')
            },
            hideAlert(){
                this.alertMessage = ''
                this.alertSuccess = false
                this.alertDanger = false
            },
            clickedRemoveSection(section){
                if (section) {
                    let index = this.sections.findIndex(sec=>{
                        return sec.name === section.name && 
                            sec.startDate === section.startDate &&
                            sec.endDate === section.endDate
                    })
                    if (index > -1) {
                        this.sections.splice(index,1)
                    }
                } else {
                    this.clearSection()
                }
            },
            clickedSection(section){
                if (section) {
                    this.section.name = section.name
                    this.section.startDate = section.startDate
                    this.section.endDate = section.endDate
                    this.section.promotion = section.promotion

                    this.clickedRemoveSection(section)
                }
            },
            getStartDate(data){
                this.academicYearData.startDate = data
            },
            getEndDate(data){
                this.academicYearData.endDate = data
            },
            getSectionStartDate(data){
                this.section.startDate = data
            },
            getSectionEndDate(data){
                this.section.endDate = data
            },
            fallsWithinOtherSection(){
                let startDate = dates.toDate(new Date(this.section.startDate)),
                    endDate = dates.toDate(new Date(this.section.endDate)),
                    index = this.sections.findIndex(section=>{
                        return (dates.toDate(new Date(section.startDate)) < startDate &&
                            startDate < dates.toDate(new Date(section.endDate))) ||
                            (dates.toDate(new Date(section.startDate)) < endDate &&
                            endDate < dates.toDate(new Date(section.endDate)))
                    })

                if (index > -1) {
                    return {status: true, name: this.sections[index].name}
                } else {
                    return {status: false}
                }
            },
            validateSection(){
                let msg = ''
                if (!this.section.name.length || !this.section.name.trim().length) {
                    msg = 'Please enter a name for this section of the academic year.'
                } else if (!this.section.startDate.length) {
                    msg = 'A section must have a start date'
                } else if (this.academicYearData.startDate.length && 
                    dates.toDate(new Date(this.academicYearData.startDate)) >
                    dates.toDate(new Date(this.section.startDate))) {
                    msg = 'The start date of a section must be within the academic year'
                } else if (!this.section.endDate.length) {
                    msg = 'A section must have an end date'
                } else if (this.academicYearData.endDate.length && 
                    dates.toDate(new Date(this.academicYearData.endDate)) <
                    dates.toDate(new Date(this.section.endDate))) {
                    msg = 'The end date of a section must be within the academic year'
                } else if (dates.toDate(new Date(this.section.startDate)) >=
                    dates.toDate(new Date(this.section.endDate))) {
                    msg = 'The end date of a section must come after its start date'
                } else if (this.sections.findIndex(
                    section=>section.name === this.section.name) > -1) {
                    msg = 'A section cannot have the same name as another section in the same'
                } else if (this.section.promotion && this.sections.findIndex(
                    section=>section.promotion) > -1) {
                    msg = 'Only one section can be promotional. Click end edit the other promotional if you want to make this section promotional.'
                }
                
                let fallsWithinOtherSectionObject = this.fallsWithinOtherSection()
                if (fallsWithinOtherSectionObject.status) {
                    msg = `the start or end date falls within section with name ${fallsWithinOtherSectionObject.name}`
                }

                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return false
                } else {
                    return true
                }
            },
            validateAcademicYear(){
                let msg = ''
                if (!this.academicYearData.name.length || !this.academicYearData.name.trim().length) {
                    msg = 'Please enter a name for this academic year.'
                } else if (!this.academicYearData.startDate.length) {
                    msg = 'An academic year must have a start date'
                } else if (!this.academicYearData.endDate.length && dates.toDate(new Date(this.section.startDate)) >=
                    dates.toDate(new Date(this.section.endDate))) {
                    msg = 'The end date of an academic year must come after its start date, and not on or before it.'
                }

                if (msg.length) {
                    this.alertDanger = true
                    this.alertMessage = msg
                    return false
                } else {
                    return true
                }
            },
            clearSection(){
                this.section.name = ''
                this.section.startDate = ''
                this.section.endDate = ''
                this.section.promotion = false
                this.sectionStartDateMax = this.academicYearStartDateMax
                this.sectionEndDateMin = this.academicYearEndDateMin
            },
            addSection(){                
                if (this.validateSection()) {                    
                    this.sections.push({
                        name: this.section.name,
                        startDate: this.section.startDate,
                        endDate: this.section.endDate,
                        promotion: this.section.promotion,
                    })

                    this.clearSection()
                }
            },
            async createAcademicYear(){
                let sections = []
                if (!this.validateAcademicYear()) {
                    return
                } else {
                    if (!this.validateSection()) {
                        return
                    } else {
                        sections.push(this.section)
                    }
                }

                sections.push(...this.sections)

                let response,
                    data = {
                        sections: JSON.stringify(sections),
                        name: this.academicYearData.name,
                        description: this.academicYearData.description,
                        startDate: this.academicYearData.startDate,
                        endDate: this.academicYearData.endDate,
                        account: this.computedAdmin ? 'admin' : null,
                        accountId: this.computedAdmin ? this.computedAdmin.accountId : null,
                        schoolId: this.school.accountId,
                    }

                this.creating = true

                response = await this['dashboard/createAcademicYear'](data)

                this.creating = false

                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = `${this.academicYearData.name} academic year was successfully created`
                    this.cleanUp()
                } else {
                    this.alertDanger = true
                    if (response.response.data) {
                        this.alertMessage = `${response.response.data.message}`
                    } else {
                        this.alertMessage = `creating ${this.academicYearData.name} academic year failed`
                    }

                    console.log('response :>> ', response);
                }
            },
            clickedAction(data){
                if (data === 'add another section') {
                    this.addSection()
                } else {
                    this.createAcademicYear()
                }
            },
            cleanUp(){
                this.academicYearData = {
                    name: '',
                    description: '',
                    startDate: '',
                    endDate: '',
                }
                this.academicYearStartDateMax = ''
                this.academicYearEndDateMax = ''
                this.clearSection()
                this.sections = []
            },
        },
    }
</script>

<style lang="scss" scoped>

    .welcome-form{        

        .loading{
            position: sticky;
            width: 100%;
            text-align: center;
            top: 10px;
        }

        .date-picker,
        .text-input{
            width: 90%;
            margin: 10px auto;
            border: none;
            border-bottom: 2px solid $color-primary;
            border-radius: 0;
        }

        .note{
            width: 90%;
            padding: 10px;
            color: gray;
            font-style: italic;
            margin: auto;
            font-size: 14px;
        }

        .section{
            padding: 10px;
        }

        .sections-preview{
            width: 100%;
            display: flex;
            align-items: center;
            flex-wrap: wrap;

            .section-preview{
                font-size: 14px;
                padding: 5px;
                background: mintcream;
                cursor: pointer;
                border-radius: 5px;
                box-shadow: 0 0 2px grey;
                position: relative;
                margin: 0 10px 10px 0;

                .name{
                    text-align: center;
                }

                .dates{
                    color: gray;
                    font-size: 12px;
                }

                .close{
                    font-size: 14px;
                    color: red;
                    position: absolute;
                    top: 0;
                    right: 0;
                    padding: 5px;
                }

                .promotion{
                    color: green;
                    font-size: 10px;
                    text-align: end;
                }
            }
        }

        .action-button{
            margin: 10px auto;
        }
    }
</style>