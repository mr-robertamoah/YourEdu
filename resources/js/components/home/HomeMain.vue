<template>
    <div class="home-main-wrapper">
        <div class="extras-section">
            <post-button 
                buttonText="discussion" 
                @click="clickedPostButton('discussion')"
                titleText="have something to discuss?"
            ></post-button>
            <post-button 
                buttonText="assessment" 
                @click="clickedPostButton('assessment')"
                titleText="want to create an assessment?"
            ></post-button>
        </div>
        <div class="loading" v-if="otherLoading">
            <pulse-loader :loading="otherLoading" :size="'10px'"></pulse-loader>
        </div>
        <div class="alert" 
            v-if="alertMessage.length"
            :class="{success:alertSuccess, danger:alertDanger}"
        >
            {{alertMessage}}
        </div>
        <post-create v-if="computedPostCreate"></post-create>
        <div 
            v-else-if="!loading && !computedPostCreate"
            @askLoginRegister="askLoginRegister"
        >
            must finish work on this
        </div>
        <div class="loading" v-if="loading">
            <pulse-loader
                :loading="loading"
            ></pulse-loader>
        </div>
        <template 
            v-if="posts"
        >
            <template
                v-for="post in posts"
            >
                <post-single
                    :key="`post.${post.id}`"
                    v-if="post.isPost"
                    :post="post"
                    @askLoginRegister="askLoginRegister"
                    @clickedMedia="clickedMedia"
                    @clickedShowPostComments="clickedShowPostComments"
                    @clickedShowPostPreview="clickedShowPostPreview"
                ></post-single>
                <discussion-single
                    v-if="post.isDiscussion"
                    :key="`discussion.${post.id}`"
                    :discussion="post"
                    @askLoginRegister="askLoginRegister"
                ></discussion-single>
                <assessment-single
                    v-if="post.isAssessment"
                    :key="`assessment.${post.id}`"
                    :assessment="post"
                    @askLoginRegister="askLoginRegister"
                ></assessment-single>
            </template>
        </template>

        <!-- create discussion -->
        <create-discussion
            v-if="showCreateItem === 'discussion'"
            :show="showCreateItem === 'discussion'"
            @createDiscussionDisappear="clickedCloseCreateItem"
            @clickedCreate="clickedCreateDiscussion"
        ></create-discussion>
        <!-- create assessment -->
        <create-assessment
            :show="showCreateItem === 'assessment'"
            @closeCreateAssessment="clickedCloseCreateItem"
            @clickedCreate="clickedCreateAssessment"
        ></create-assessment>

    </div>
</template>

<script>
import PostCreate from '../PostCreate'
import PostCreateAlt from '../PostCreateAlt'
import PostButton from '../PostButton'
import CreateDiscussion from '../forms/CreateDiscussion'
import CreateAssessment from '../forms/CreateAssessment'
import PostSingle from '../PostSingle'
import DiscussionSingle from '../DiscussionSingle'
import AssessmentSingle from '../AssessmentSingle'
import PulseLoader from 'vue-spinner/src/PulseLoader'
import { mapGetters, mapActions } from 'vuex'
import InfiniteLoading from 'vue-infinite-loading'

    export default {
        components: {
            CreateAssessment,
            CreateDiscussion,
            PostButton,
            PostCreate,
            PostCreateAlt,
            PulseLoader,
            AssessmentSingle,
            DiscussionSingle,
            PostSingle,
            InfiniteLoading,
        },
        props: {
            loading: {
                type: Boolean,
                default: false
            },
            posts: {
                type: Array,
                default() {
                    return []
                }
            },
        },
        data() {
            return {
                showLoginRegister: false,
                showCreateItem: '',
                otherLoading: false,
                alertDanger: false,
                alertSuccess: false,
                alertMessage: ''
            }
        },
        computed: {
            ...mapGetters(['getProfiles']),
            computedPostCreate(){
                return this.getProfiles && this.getProfiles.length ? true : false
            },
        },
        methods: {
            ...mapActions(['profile/createDiscussion',
                'profile/createAssessment', 'dashboard/createAssessment'
            ]),
            clickedPostButton(text){

                this.showCreateItem = text
            },
            clickedCloseCreateItem(){
                this.showCreateItem = ''
            },
            clearAlert(){
                setTimeout(() => {
                    this.alertDanger = false
                    this.alertSuccess = false
                    this.alertMessage = ''
                }, 3000);
            },
            async clickedCreateDiscussion(data){
                let response,
                    formData = new FormData

                this.otherLoading = true

                formData.append('account', data.account)
                formData.append('accountId', data.accountId)
                formData.append('title', data.title)
                formData.append('type', data.type)
                formData.append('allowed', data.allowed)
                formData.append('restricted', JSON.stringify(data.restricted))
                formData.append('preamble', data.preamble)
                data.files.forEach(file=>{
                    formData.append('file[]', file)
                })
                if (data.postAttachments.length) {
                    formData.append('attachments', JSON.stringify(data.postAttachments.map(attachment=>{
                        return {
                            attachable: attachment.type.slice(0,attachment.type.length - 1),
                            attachableId: attachment.data.id
                        }
                    })))
                }

                response = await this['profile/createDiscussion'](formData)

                this.otherLoading = false

                this.handleResponse(response, 'discussion')
            },
            handleResponse(response, item) {
                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = `${item} created successfully`
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = `${item} creation failed`
                }
                this.clearAlert()
            },
            async clickedCreateAssessment(formData) {

                this.otherLoading = true

                let response = await this['dashboard/createAssessment'](formData)

                this.otherLoading = false
                
                this.handleResponse(response, 'assessment')
            },
            clickedShowPostComments(data){
                this.$emit('clickedShowPostComments',data)
            },
            clickedShowPostPreview(data){
                this.$emit('clickedShowPostPreview',data)
            },
            askLoginRegister(){
                this.$emit('askLoginRegister','HomeMain')
            },
            clickedMedia(data){
                this.$emit('clickedMedia',data)
            },
        },
    }
</script>

<style lang="scss" scoped>

    .home-main-wrapper{
        background: inherit;
        
        .extras-section{
            width: 100%;
            display: inline-flex;
            justify-content: space-around;
            flex-wrap: wrap;
            align-items: center;
        }

        .loading,
        .alert{
            text-align: center;
            width: 100%;
            font-size: 14px;
        }

        .alert{
            color: white;
        }

        .success{
            background-color: green;
        }

        .danger{
            background-color: red;
        }

        .create{
            width: 100%;
            min-height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid dimgrey;
            border-right: 2px solid rgb(105, 105, 105);

            div{
                padding: 10px;
                font-size: 16px;

                &:hover{
                    transition: all 1s ease;
                    box-shadow: 0 0 3px gray;
                }
            }
        }
    }
</style>