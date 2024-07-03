<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                @mainModalDisappear="attachmentModalDisappear"
                :main="false"
                :mainOther="false"
                class="main-modal"
            >
                <template slot="requests">
                    <welcome-form
                        :title="title"
                    >
                        <template slot="input">
                            <auto-alert
                                :message="alertMessage"
                                :success="alertSuccess"
                                :danger="alertDanger"
                                :sticky="true"
                                @hideAlert="clearAlert"
                            ></auto-alert>
                            <pulse-loader :loading="loading"></pulse-loader>
                            <div class="preview">
                                <template v-if="attachments.length">
                                    <attachment-badge
                                        v-for="(attachment,index) in attachments"
                                        :key="index"
                                        :hasClose="true"
                                        :attachment="attachment.data"
                                        :type="attachment.type"
                                        @removeAttachment="clickedRemoveAttachment"
                                    ></attachment-badge>
                                </template>
                                <div class="no-data" v-else>
                                    no attachments yet
                                </div>
                            </div>
                            <post-attachment
                                :show="true"
                                :hasSelect="true"
                                :mainSearchItem="computedType"
                                :hasClose="false"
                                @clickedAttachmentSelection="typeSelected"
                                class="class-input"
                            ></post-attachment>
                        </template>
                        <template slot="buttons">
                            <post-button
                                :buttonText="buttonText"
                                buttonStyle='success'
                                @click="clickedCreate"
                            ></post-button>
                        </template>
                    </welcome-form>
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import AttachmentBadge from './AttachmentBadge.vue';
import PostAttachment from './PostAttachment.vue';
import PostButton from './PostButton.vue';
import AutoAlert from './AutoAlert.vue';
    export default {
        components: {
            AutoAlert,
            PostButton,
            PostAttachment,
            AttachmentBadge,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            multiple: {
                type: Boolean,
                default: true
            },
            type: {
                type: String,
                default: ''
            },
            accountDetails: {
                type: Object,
                default(){
                    return null
                }
            },
        },
        data() {
            return {
                attachments: [],
                title: 'create an attachment',
                alertMessage: '',
                alertDanger: false,
                alertSuccess: false,
                loading: false,
                buttonText: `attach`,
            }
        },
        watch: {
            type: {
                immediate: true,
                handler(newValue){
                    if (newValue.length) {
                        this.title = `attach a ${newValue}`
                        this.buttonText = `attach ${newValue}`
                    }
                }
            }
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails']),
            computedType() {
                return this.type + 's' 
            },
            computedAccountDetails(){
                return this['dashboard/getAccountDetails']
            },
        },
        methods: {
            ...mapActions(['dashboard/createAccountAttachments']),
            attachmentModalDisappear() {
                this.$emit('attachmentModalDisappear')
            },
            clickedRemoveAttachment(data){
                let index = this.getAttachmentIndex(data)
                if (index > -1){
                    this.attachments.splice(index,1)
                }
            },
            getAttachmentIndex(data){
                let index  = this.attachments.findIndex(attachment=>{
                    return attachment.type === data.type && 
                        attachment.data.id === data.data.id
                })
                return index
            },
            isAttached(data){
                // check if selected attachment is already attached to account 
                let index
                if (this.type === 'subject') {
                    index = this.accountDetails.subjects.findIndex(subject=>{
                        return subject.data.id === data.data.id
                    })
                } else if (this.type === 'grade') {
                    index = this.accountDetails.grades.findIndex(subject=>{
                        return subject.data.id === data.data.id
                    })
                }
                if (index > -1) {
                    return true //already attached
                }

                return false //not attached
            },
            typeSelected(data){
                if (!this.multiple) this.attachments = data
                if (this.getAttachmentIndex(data) > -1) return
                if (this.isAttached(data)) {
                    this.alertDanger = true
                    this.alertMessage = `${this.type} already attached`
                    return
                }
                this.attachments.push(data)
            },
            clearAlert(){
                this.alertMessage = ''
                this.alertSuccess = false
                this.alertDanger = false
            },
            async clickedCreate(){
                if (this.loading) {
                    return
                }
                if (!this.attachments.length) {
                    this.alertDanger = true
                    this.alertMessage = `please select at least one before attaching`
                    return
                }
                this.loading = true
                let response,
                    data = {
                        account: this.computedAccountDetails.account,
                        accountId: this.computedAccountDetails.accountId,
                        attachments: JSON.stringify(this.attachments.map(attachment=>{
                            return {
                                type: attachment.type,
                                id: attachment.data.id
                            }
                        }))
                    }

                response = await this['dashboard/createAccountAttachments'](data)

                this.loading = false
                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = 'attachment added successfully'
                    this.$emit('attachmentSuccess', this.attachments)
                    this.attachments = []
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = 'attachment was unsuccessfully'
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

    .main-modal{
        
        .preview{
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
            min-height: 20px;
            margin-bottom: 10px;

            .no-data{
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                font-size: 14px;
                color: gray;
            }
        }

        .v-spinner{
            text-align: center;
        }
    }
</style>