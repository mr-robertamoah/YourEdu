<template>
    <div class="home-wrapper">
        <app-nav></app-nav>
        <div class="home-top">
            <div class="move-to-top" @click="moveToTop">
                <font-awesome-icon :icon="['fa','home']"></font-awesome-icon>
            </div>
            <div class="search-section">
                <search-input
                    placeholder="searching for?"
                    paClass="gray"
                    :value="searchText"
                    :homeSearch="true"
                    :background="false"
                    @search="homeSearch"
                ></search-input>
            </div>
        </div>
        <div class="home-middle">
            <div class="menu">
                <home-menu
                    :menuValue="menuValue"
                    @emitMenuValue="emitMenuValue"
                    @clickedAttachmentSelection="clickedAttachmentSelection"
                ></home-menu>
            </div>
            <div class="main" infinite-wrapper>
                <home-main 
                    @askLoginRegister="askLoginRegister"
                    @clickedMedia="clickedMedia"
                    :loading="loading"
                    :type="sideValue"
                    :params="params" 
                    @clickedShowPostComments="clickedShowPostComments"
                    @clickedShowPostPreview="clickedShowPostPreview"
                ></home-main>
            </div>
            <div class="side">
                <home-side
                    @clickedItem="clickedSideMenuItem"
                    :sideValue="sideValue"
                ></home-side>
            </div>
        </div>
        <fade-up>
            <template slot="transition" v-if="showLoginRegister">
                <small-modal
                    @disappear="showLoginRegister = false"
                    :showForm='showLoginRegister'
                    title="welcome to this new community."
                >
                    <template slot="other">
                        <router-link to="/login">login</router-link> or 
                        <router-link to='/register'>register</router-link> to interact and grow in a positve way.
                    </template>
                </small-modal>
            </template>
        </fade-up>
        
        <just-fade>
            <template slot="transition" v-if="showMediaModal">
                <media-modal
                    @mainModalDisappear="mediaModalDisappear"
                    :url="mediaUrl"
                    :urlType="mediaUrlType"
                    :justUrl="mediaJustUrl"
                >
                </media-modal>
            </template>
        </just-fade>
        <!-- post modal for showing post/type and its comments -->
        <just-fade>
            <template slot="transition" v-if="showPostModal">
                <post-modal
                    @mainModalDisappear="showPostModal = false"
                    :data="postModalData"
                    :postLoading="postModalLoading"
                    :type="postModalType"
                >
                </post-modal>
            </template>
        </just-fade>
        <!-- post modal for search output -->
                <search-output
                    :type="postModalType"
                    :propSearchType="searchOutputType"
                    @clickedButton="clickedSearchOutputButton"
                    @clickedViewPost="clickedViewPost"
                    :show="showSearchOutput"
                    :searchData="searchData"
                    :loadMore="searchHasMore"
                    :loading="searchLoading"
                    @clickedLoadMore="clickedLoadMoreSearchOutput"
                    @clearData="clearSearchData"
                    @addMyfollow="addMyfollow"
                    @removeMyfollow="removeMyfollow"
                >
                </search-output>

        <infinite-loading
            @infinite="infiniteHandler"
            v-if="computedPosts"
            force-use-infinite-wrapper
        ></infinite-loading>
    </div>
</template>

<script>
import HomeMain from '../components/home/HomeMain'
import HomeMenu from '../components/home/HomeMenu'
import HomeSide from '../components/home/HomeSide'
import FadeUp from '../components/transitions/FadeUp'
import SmallModal from '../components/SmallModal'
import SearchInput from '../components/SearchInput'
import SearchOutput from '../components/SearchOutput'
import _ from 'lodash'
import InfiniteLoading from 'vue-infinite-loading'
import { mapActions, mapGetters } from 'vuex'

    export default {
        components: {
            HomeMain,
            HomeMenu,
            SearchOutput,
            SearchInput,
            SmallModal,
            FadeUp,
            HomeSide,
            InfiniteLoading,
        },
        data() {
            return {
                showLoginRegister: false,
                //discussions
                discussionsNextPage: 1,
                discussionsMineNextPage: 1,
                discussionsFollowersNextPage: 1,
                discussionsFollowingsNextPage: 1,
                discussionsAttachmentsNextPage: 1,
                //posts
                postsNextPage: 1,
                postsMineNextPage: 1,
                postsFollowersNextPage: 1,
                postsFollowingsNextPage: 1,
                postsAttachmentsNextPage: 1,
                //questions
                questionsNextPage: 1,
                questionsMineNextPage: 1,
                questionsFollowersNextPage: 1,
                questionsFollowingsNextPage: 1,
                questionsAttachmentsNextPage: 1,
                //poems
                poemsNextPage: 1,
                poemsMineNextPage: 1,
                poemsFollowersNextPage: 1,
                poemsFollowingsNextPage: 1,
                poemsAttachmentsNextPage: 1,
                //riddles
                riddlesNextPage: 1,
                riddlesMineNextPage: 1,
                riddlesFollowersNextPage: 1,
                riddlesFollowingsNextPage: 1,
                riddlesAttachmentsNextPage: 1,
                //books
                riddlesNextPage: 1,
                riddlesMineNextPage: 1,
                riddlesFollowersNextPage: 1,
                riddlesFollowingsNextPage: 1,
                riddlesAttachmentsNextPage: 1,
                //activities
                activitiesNextPage: 1,
                activitiesMineNextPage: 1,
                activitiesFollowersNextPage: 1,
                activitiesFollowingsNextPage: 1,
                activitiesAttachmentsNextPage: 1,
                readsNextPage: 1,
                discussionsNextPage: 1,
                loading: false,
                params: {all:true},
                //media modal
                mediaUrl: '',
                mediaJustUrl: true,
                showMediaModal: false,
                mediaJustUrl: true, // for now this will be receiving url type
                mediaUrlType: '',
                //post modal
                showPostModal: false,
                postModalData: null,
                postModalType: '',
                postModalLoading: false,
                //home menu
                menuValue: "all",
                sideValue: 'posts',
                menuAttachment: null,
                booksAttachmentAfter: null,
                activitiesAttachmentAfter: null,
                riddlesAttachmentAfter: null,
                poemsAttachmentAfter: null,
                questionsAttachmentAfter: null,
                postsAttachmentAfter: null, // to track the current attachment data being kept in home
                //search
                searchOutputType: 'all',
                searchText: '',
                oldSearchText: '',
                showSearchOutput: false,
                searchData: [],
                searchNextPage: 1,
                searchLoading: false,
                searchHasMore: false, //this determines if load more should appear to user
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                // vm.getData()
                vm.getPosts()
            });
        },
        mounted () {
            this.listen()
        },
        watch: {
            sideValue(newValue) {
                // this.menuValue = 'all'
                this.getData(newValue)
            },
            menuValue(newValue){
                if (!this.getUser) {
                    this.menuValue = 'all'
                    this.showLoginRegister = true
                } else {
                    if (newValue === 'all') {
                        this.params = {user: this.getUser.id}
                        this.params.all = true
                    } else if (newValue === 'followers') {
                        this.params = {user: this.getUser.id}
                        this.params.followers = true
                    } else if (newValue === 'followings') {
                        this.params = {user: this.getUser.id}
                        this.params.followings = true
                    } else if (newValue === 'mine') {
                        this.params = {user: this.getUser.id}
                        this.params.mine = true
                    } else if (newValue === 'attachments') {
                        
                        return
                    }

                    this.getData(this.sideValue)
                }
            },
            menuAttachment(newValue){
                this.params = {}
                this.params.user = this.getUser.id
                this.params.attachments = true
                this.params.attach = newValue.type,
                this.params.id = newValue.data.id

                this.getData(this.sideValue)
            },
            searchText(newValue){
                this.searchNextPage = 1
                if (newValue.length) {
                    this.showSearchOutput = true
                    this.debouncedHomeSearch()
                } else {
                    this.showSearchOutput = false
                    this.searchData = []
                }
            },
            searchOutputType(newValue){
                this.searchNextPage = 1
                if (this.searchText.length) {
                    this.debouncedHomeSearch()
                }
            },
            searchNextPage(newValue){
                if (newValue && newValue > 1) {
                    this.searchHasMore = true
                } else {
                    this.searchHasMore = false
                }
            },
        },
        computed: {
            ...mapGetters(['home/getHomePosts','home/getHomePostsMine','home/getHomePostsFollowers',
                'getAccessToken','home/getHomeReads','home/getHomePostsFollowings','home/getHomePostsAttachments',
                'home/getHomeDiscussions','getUser',
                'home/getHomeQuestions','home/getHomeQuestionsMine','home/getHomeQuestionsFollowers',
                'home/getHomeQuestionsFollowings','home/getHomeQuestionsAttachments',
                'home/getHomeRiddles','home/getHomeRiddlesMine','home/getHomeRiddlesFollowers',
                'home/getHomeRiddlesFollowings','home/getHomeRiddlesAttachments',
                'home/getHomePoems','home/getHomePoemsMine','home/getHomePoemsFollowers',
                'home/getHomePoemsFollowings','home/getHomePoemsAttachments',
                'home/getHomeBooks','home/getHomeBooksMine','home/getHomeBooksFollowers',
                'home/getHomeBooksFollowings','home/getHomeBooksAttachments',
                'home/getHomeActivities','home/getHomeActivitiesMine','home/getHomeActivitiesFollowers',
                'home/getHomeActivitiesFollowings','home/getHomeActivitiesAttachments']),
            computedPosts() {
                return this['home/getHomePosts'] ? this['home/getHomePosts'] : null
            },
            computedProfiles() {
                return this.getProfiles ? this.getProfiles : []
            }
        },
        methods: {
            ...mapActions(['home/getPosts','home/clearPosts','home/getUserPosts',
                'getProfiles','home/clearHomePostsAttachments','home/getPostTypes',
                'home/getUserPostTypes','home/clearHomeQuestionsAttachments'
                ,'home/clearHomePoemsAttachments','home/clearHomeRiddlesAttachments'
                ,'home/clearHomeBooksAttachments','home/clearHomeActivitiesAttachments',
                'home/search','home/newPost','home/removePost','home/replacePost',
                'home/newComment','home/removeComment','home/replaceComment',
                'home/newDiscussion','home/removeDiscussion','home/replaceDiscussion',
                'home/newFlag','home/newLike','home/removeLike',
                'home/newAttachment','home/removeAttachment','profile/getPost'
            ]),
            clickedSearchOutputButton(data){
                this.searchOutputType = data
            },
            listen(){
                Echo.channel('youredu.home')
                    .listen('.newPost', (post)=>{
                        console.log(post)
                        this['home/newPost'](post.post)
                    })
                    .listen('.updatePost', post=>{
                        console.log(post)
                        this['home/replacePost'](post.post)
                    })
                    .listen('.deletePost', postInfo=>{
                        console.log(postInfo)
                        this['home/removePost'](postInfo)
                    })
                    .listen('.newComment', (commentData)=>{
                        console.log(commentData)
                        this['home/newComment'](commentData)
                    })
                    .listen('.updateComment', commentData=>{
                        console.log(commentData)
                        this['home/replaceComment'](commentData)
                    })
                    .listen('.deleteComment', commentInfo=>{
                        console.log(commentInfo)
                        this['home/removeComment'](commentInfo)
                    })
                    .listen('.newAttachment', (attachmentData)=>{
                        console.log(attachmentData)
                        this['home/newAttachment'](attachmentData)
                    })
                    .listen('.deleteAttachment', attachmentInfo=>{
                        console.log(attachmentInfo)
                        this['home/removeAttachment'](attachmentInfo)
                    })
                    .listen('.newFlag', (flag)=>{
                        console.log(flag)
                        this['home/newFlag'](flag)
                    })
                    .listen('.newLike', (likeData)=>{
                        console.log(likeData)
                        this['home/newLike'](likeData)
                    })
                    .listen('.deleteLike', like=>{
                        console.log(like)
                        this['home/removeLike'](like)
                    })
                    .listen('.newDiscussion', (discussion)=>{
                        console.log(discussion)
                        this['home/newDiscussion'](discussion.discussion)
                    })
                    .listen('.updateDiscussion', discussion=>{
                        console.log(discussion)
                        this['home/replaceDiscussion'](discussion.discussion)
                    })
                    .listen('.deleteDiscussion', discussionInfo=>{
                        console.log(discussionInfo)
                        this['home/removeDiscussion'](discussionInfo)
                    })
                
            },
            async clickedViewPost(data){
                this.showPostModal = true
                this.postModalLoading = true
                let response = await this['profile/getPost'](data.id)
                if (response !== 'unsuccessful') {
                    this.postModalType = 'post'
                    this.postModalData = response
                } else {
                    this.showPostModal = false
                }
                this.postModalLoading = false
            },
            removeMyfollow(data){
                this.searchData.forEach(searchItem=>{
                    if (searchItem.hasOwnProperty('account_id') &&
                        searchItem.account_id === data.accountId &&
                        searchItem.account_type === data.account) {
                        let followIndex = searchItem.follows.findIndex(follow=>{
                            return follow.id === data.follow.id
                        })
                        if (followIndex > -1) {
                            searchItem.follows.splice(followIndex,1)
                        }
                    }
                })
            },
            addMyfollow(data){
                this.searchData.forEach(searchItem=>{
                    if (searchItem.hasOwnProperty('account_id') &&
                        searchItem.account_id === data.accountId &&
                        searchItem.account_type === data.account) {
                        searchItem.follows.push(data.follow)
                    }
                })
            },
            clearSearchData(){
                this.searchText = ''
                this.oldSearchText = ''
                this.searchOutputType = 'all'
                this.searchData = []
            },
            clickedLoadMoreSearchOutput(){
                this.debouncedHomeSearch()
            },
            homeSearch(data){
                this.searchText = data
            },
            debouncedHomeSearch: _.debounce(function() {
                this.search()
            }, 200),
            async search(){
                if (this.searchNextPage === null) {
                    return 
                }
                this.searchLoading = true
                let response = null,
                    params = {},
                    data = {}

                params.search = this.searchText
                params.searchType = this.searchOutputType
                if (this.getUser) {
                    params.user_id = this.getUser.id
                }
                data.nextPage = this.searchNextPage
                data.params = params

                response = await this['home/search'](data)

                if (response.status) {
                    if (this.oldSearchText === this.searchText && 
                        this.searchNextPage && this.searchNextPage > 1) {
                        this.searchData.push(...response.data)
                    } else {
                        this.searchData = response.data
                    }
                    if (response.next) {
                        this.searchNextPage += 1
                    } else {
                        this.searchNextPage = null
                    }
                } else {

                }
                this.oldSearchText = this.searchText
                this.searchLoading = false
            },
            clickedSideMenuItem(data){
                this.sideValue = data
            },
            clickedAttachmentSelection(data){
                this.menuAttachment = data
            },
            emitMenuValue(data){
                this.menuValue = data
            },
            clickedShowPostPreview(data){
                this.postModalData = data.data
                this.postModalType = 'posttype'
                this.showPostModal = true
            },
            clickedShowPostComments(data){
                this.postModalData = data.post
                this.postModalType = 'post'
                this.showPostModal = true
            },
            mediaModalDisappear(){
                this.showMediaModal = false
                this.mediaUrl = ''
                this.mediaUrlType = ''
            },
            clickedMedia(data){
                this.mediaUrl = data.url
                this.mediaUrlType = data.mediaType
                this.showMediaModal = true
            },
            askLoginRegister(){
                this.showLoginRegister = true
                setTimeout(() => {
                    this.showLoginRegister = false
                }, 4000);
            },
            moveToTop(){
                window.scrollTo(0,0)
            },
            getData(data){
                if (data === 'posts') {
                    this.getPosts()
                } else if (data === 'reads') {
                    this.getReads()
                } else if (data === 'discussions') {
                    this.getDiscussions()
                } else if (data === 'questions') {
                    this.getQuestions()
                } else if (data === 'books') {
                    this.getBooks()
                } else if (data === 'poems') {
                    this.getPoems()
                } else if (data === 'activities') {
                    this.getActivities()
                } else if (data === 'riddles') {
                    this.getRiddles()
                }
            },
            incrementNextPage(){
                if (this.sideValue === 'posts') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.postsMineNextPage += 1
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.postsFollowersNextPage += 1
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.postsFollowingsNextPage += 1
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.postsAttachmentsNextPage += 1
                    } else {
                        this.postsNextPage += 1
                    }
                } else if (this.sideValue === 'reads') {
                    this.readsNextPage += 1
                } else if (this.sideValue === 'discussions') {
                    this.discussionsNextPage += 1
                } else if (this.sideValue === 'riddles') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.riddlesMineNextPage += 1
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.riddlesFollowersNextPage += 1
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.riddlesFollowingsNextPage += 1
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.riddlesAttachmentsNextPage += 1
                    } else {
                        this.riddlesNextPage += 1
                    }
                } else if (this.sideValue === 'poems') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.poemsMineNextPage += 1
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.poemsFollowersNextPage += 1
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.poemsFollowingsNextPage += 1
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.poemsAttachmentsNextPage += 1
                    } else {
                        this.poemsNextPage += 1
                    }
                } else if (this.sideValue === 'books') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.booksMineNextPage += 1
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.booksFollowersNextPage += 1
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.booksFollowingsNextPage += 1
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.booksAttachmentsNextPage += 1
                    } else {
                        this.booksNextPage += 1
                    }
                } else if (this.sideValue === 'activities') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.activitiesMineNextPage += 1
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.activitiesFollowersNextPage += 1
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.activitiesFollowingsNextPage += 1
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.activitiesAttachmentsNextPage += 1
                    } else {
                        this.activitiesNextPage += 1
                    }
                } else if (this.sideValue === 'questions') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.questionsMineNextPage += 1
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.questionsFollowersNextPage += 1
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.questionsFollowingsNextPage += 1
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.questionsAttachmentsNextPage += 1
                    } else {
                        this.questionsNextPage += 1
                    }
                }
            },
            decrementNextPage(){
                if (this.sideValue === 'posts') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.postsMineNextPage = null
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.postsFollowersNextPage = null
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.postsFollowingsNextPage = null
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.postsAttachmentsNextPage = null
                    } else {
                        this.postsNextPage = null
                    }
                } else if (this.sideValue === 'reads') {
                    this.readsNextPage = null
                } else if (this.sideValue === 'discussions') {
                    this.discussionsNextPage = null
                } else if (this.sideValue === 'riddles') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.riddlesMineNextPage = null
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.riddlesFollowersNextPage = null
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.riddlesFollowingsNextPage = null
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.riddlesAttachmentsNextPage = null
                    } else {
                        this.riddlesNextPage = null
                    }
                } else if (this.sideValue === 'poems') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.poemsMineNextPage = null
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.poemsFollowersNextPage = null
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.poemsFollowingsNextPage = null
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.poemsAttachmentsNextPage = null
                    } else {
                        this.poemsNextPage = null
                    }
                } else if (this.sideValue === 'books') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.booksMineNextPage = null
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.booksFollowersNextPage = null
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.booksFollowingsNextPage = null
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.booksAttachmentsNextPage = null
                    } else {
                        this.booksNextPage = null
                    }
                } else if (this.sideValue === 'activities') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.activitiesMineNextPage = null
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.activitiesFollowersNextPage = null
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.activitiesFollowingsNextPage = null
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.activitiesAttachmentsNextPage = null
                    } else {
                        this.activitiesNextPage = null
                    }
                } else if (this.sideValue === 'questions') {
                    if (this.params.hasOwnProperty('mine')) {
                        this.questionsMineNextPage = null
                    } else if (this.params.hasOwnProperty('followers')) {
                        this.questionsFollowersNextPage = null
                    } else if (this.params.hasOwnProperty('followings')) {
                        this.questionsFollowingsNextPage = null
                    } else if (this.params.hasOwnProperty('attachments')) {
                        this.questionsAttachmentsNextPage = null
                    } else {
                        this.questionsNextPage = null
                    }
                }
            },
            checkNextPageCancel(data){
                let nextPage = null,
                    cancel = false
                if (this.params.hasOwnProperty('mine')) {
                    if (data === 'posts') {
                        nextPage = this.postsMineNextPage
                        if (nextPage !== 1 && this['home/getHomePostsMine']) {
                            cancel = true
                        }
                    } else if (data === 'reads') {
                        
                    } else if (data === 'discussions') {
                        
                    } else if (data === 'questions') {
                        nextPage = this.questionsMineNextPage
                        if (nextPage !== 1 && this['home/getHomeQuestionsMine']) {
                            cancel = true
                        }
                    } else if (data === 'riddles') {
                        nextPage = this.riddlesMineNextPage
                        if (nextPage !== 1 && this['home/getHomeRiddlesMine']) {
                            cancel = true
                        }
                    } else if (data === 'poems') {
                        nextPage = this.poemsMineNextPage
                        if (nextPage !== 1 && this['home/getHomePoemsMine']) {
                            cancel = true
                        }
                    } else if (data === 'activities') {
                        nextPage = this.activitiesMineNextPage
                        if (nextPage !== 1 && this['home/getHomeActivitiesMine']) {
                            cancel = true
                        }
                    } else if (data === 'books') {
                        nextPage = this.booksMineNextPage
                        if (nextPage !== 1 && this['home/getHomeBooksMine']) {
                            cancel = true
                        }
                    }
                } else if (this.params.hasOwnProperty('followers')) {
                    if (data === 'posts') {
                        nextPage = this.postsFollowersNextPage
                        if (nextPage !== 1 && this['home/getHomePostsFollowers']) {
                            cancel = true
                        }
                    } else if (data === 'reads') {
                        
                    } else if (data === 'discussions') {
                        
                    } else if (data === 'questions') {
                        nextPage = this.questionsFollowersNextPage
                        if (nextPage !== 1 && this['home/getHomeQuestionsFollowers']) {
                            cancel = true
                        }
                    } else if (data === 'riddles') {
                        nextPage = this.riddlesFollowersNextPage
                        if (nextPage !== 1 && this['home/getHomeRiddlesFollowers']) {
                            cancel = true
                        }
                    } else if (data === 'poems') {
                        nextPage = this.poemsFollowersNextPage
                        if (nextPage !== 1 && this['home/getHomePoemsFollowers']) {
                            cancel = true
                        }
                    } else if (data === 'activities') {
                        nextPage = this.activitiesFollowersNextPage
                        if (nextPage !== 1 && this['home/getHomeActivitiesFollowers']) {
                            cancel = true
                        }
                    } else if (data === 'books') {
                        nextPage = this.booksFollowersNextPage
                        if (nextPage !== 1 && this['home/getHomeBooksFollowers']) {
                            cancel = true
                        }
                    }
                } else if (this.params.hasOwnProperty('followings')) {
                    if (data === 'posts') {
                        nextPage = this.postsFollowingsNextPage
                        if (nextPage !== 1 && this['home/getHomePostsFollowings']) {
                            cancel = true
                        }
                    } else if (data === 'reads') {
                        
                    } else if (data === 'discussions') {
                        
                    } else if (data === 'questions') {
                        nextPage = this.questionsFollowingsNextPage
                        if (nextPage !== 1 && this['home/getHomeQuestionsFollowings']) {
                            cancel = true
                        }
                    } else if (data === 'riddles') {
                        nextPage = this.riddlesFollowingsNextPage
                        if (nextPage !== 1 && this['home/getHomeRiddlesFollowings']) {
                            cancel = true
                        }
                    } else if (data === 'poems') {
                        nextPage = this.poemsFollowingsNextPage
                        if (nextPage !== 1 && this['home/getHomePoemsFollowings']) {
                            cancel = true
                        }
                    } else if (data === 'activities') {
                        nextPage = this.activitiesFollowingsNextPage
                        if (nextPage !== 1 && this['home/getHomeActivitiesFollowings']) {
                            cancel = true
                        }
                    } else if (data === 'books') {
                        nextPage = this.booksFollowingsNextPage
                        if (nextPage !== 1 && this['home/getHomeBooksFollowings']) {
                            cancel = true
                        }
                    }
                } else if (this.params.hasOwnProperty('attachments')) {
                    if (data === 'posts') {
                        nextPage = this.postsAttachmentsNextPage
                        if (nextPage !== 1 && this['home/getHomePostsAttachments'] && 
                            (this.menuAttachment.data.id === this.postsAttachmentAfter.data.id) &&
                            (this.menuAttachment.type === this.postsAttachmentAfter.type)) {
                            cancel = true
                        } else {
                            this['home/clearHomePostsAttachments']()
                        }
                    } else if (data === 'reads') {
                        
                    } else if (data === 'discussions') {
                        
                    } else if (data === 'questions') {
                        nextPage = this.questionsAttachmentsNextPage
                        if (nextPage !== 1 && this['home/getHomeQuestionsAttachments'] && 
                            (this.menuAttachment.data.id === this.questionsAttachmentAfter.data.id) &&
                            (this.menuAttachment.type === this.questionsAttachmentAfter.type)) {
                            cancel = true
                        } else {
                            this['home/clearHomeQuestionsAttachments']()
                        }
                    } else if (data === 'riddles') {
                        nextPage = this.riddlesAttachmentsNextPage
                        if (nextPage !== 1 && this['home/getHomeRiddlesAttachments'] && 
                            (this.menuAttachment.data.id === this.riddlesAttachmentAfter.data.id) &&
                            (this.menuAttachment.type === this.riddlesAttachmentAfter.type)) {
                            cancel = true
                        } else {
                            this['home/clearHomeRiddlesAttachments']()
                        }
                    } else if (data === 'poems') {
                        nextPage = this.poemsAttachmentsNextPage
                        if (nextPage !== 1 && this['home/getHomePoemsAttachments'] && 
                            (this.menuAttachment.data.id === this.poemsAttachmentAfter.data.id) &&
                            (this.menuAttachment.type === this.poemsAttachmentAfter.type)) {
                            cancel = true
                        } else {
                            this['home/clearHomePoemsAttachments']()
                        }
                    } else if (data === 'activities') {
                        nextPage = this.activitiesAttachmentsNextPage
                        if (nextPage !== 1 && this['home/getHomeActivitiesAttachments'] && 
                            (this.menuAttachment.data.id === this.activitiesAttachmentAfter.data.id) &&
                            (this.menuAttachment.type === this.activitiesAttachmentAfter.type)) {
                            cancel = true
                        } else {
                            this['home/clearHomeActivitiesAttachments']()
                        }
                    } else if (data === 'books') {
                        nextPage = this.booksAttachmentsNextPage
                        if (nextPage !== 1 && this['home/getHomeBooksAttachments'] && 
                            (this.menuAttachment.data.id === this.booksAttachmentAfter.data.id) &&
                            (this.menuAttachment.type === this.booksAttachmentAfter.type)) {
                            cancel = true
                        } else {
                            this['home/clearHomeBooksAttachments']()
                        }
                    }
                } else {
                    if (data === 'posts') {
                        nextPage = this.postsNextPage
                        if (nextPage !== 1 && this['home/getHomePosts']) {
                            cancel = true
                        }
                    } else if (data === 'reads') {
                        
                    } else if (data === 'discussions') {
                        
                    } else if (data === 'questions') {
                        nextPage = this.questionsNextPage
                        if (nextPage !== 1 && this['home/getHomeQuestions']) {
                            cancel = true
                        }
                    } else if (data === 'riddles') {
                        nextPage = this.riddlesNextPage
                        if (nextPage !== 1 && this['home/getHomeRiddles']) {
                            cancel = true
                        }
                    } else if (data === 'poems') {
                        nextPage = this.poemsNextPage
                        if (nextPage !== 1 && this['home/getHomePoems']) {
                            cancel = true
                        }
                    } else if (data === 'activities') {
                        nextPage = this.activitiesNextPage
                        if (nextPage !== 1 && this['home/getHomeActivities']) {
                            cancel = true
                        }
                    } else if (data === 'books') {
                        nextPage = this.booksNextPage
                        if (nextPage !== 1 && this['home/getHomeBooks']) {
                            cancel = true
                        }
                    }
                }
                console.log(`nextpage ${nextPage} cancel ${cancel}`);
                return {nextPage, cancel}
            },
            setAttachmentAfter(){
                if (this.params.hasOwnProperty('attachments')) {
                    if (this.sideValue === 'posts') {
                        this.postsAttachmentAfter =  this.menuAttachment
                    } else if (this.sideValue === 'questions') {
                        this.questionsAttachmentAfter =  this.menuAttachment
                    } else if (this.sideValue === 'riddles') {
                        this.riddlesAttachmentAfter =  this.menuAttachment
                    } else if (this.sideValue === 'poems') {
                        this.poemsAttachmentAfter =  this.menuAttachment
                    } else if (this.sideValue === 'activities') {
                        this.activitiesAttachmentAfter =  this.menuAttachment
                    } else if (this.sideValue === 'books') {
                        this.booksAttachmentAfter =  this.menuAttachment
                    }
                } 
            },
            async getPosts() {
                console.log('in get posts');
                let {nextPage, cancel} = this.checkNextPageCancel('posts')
                
                if (cancel) {
                    return
                }
                this.loading = true 
                let response = null
                if (this.getAccessToken) {
                    response = await this['home/getUserPosts']({
                        nextPage,
                        params: this.params,
                    })
                } else {
                    response = await this['home/getPosts']({
                        nextPage,
                        params: this.params,
                    })
                }
                this.loading = false
                if (response) {
                    this.incrementNextPage()
                } else {
                    this.decrementNextPage()
                }
                this.setAttachmentAfter()
            },
            async getReads(){
                if (this.getUser) {
                    
                }
            },
            async getDiscussions(){

            },
            async getPostTypes(){
                
                let {nextPage, cancel} = this.checkNextPageCancel(this.sideValue)
                
                if (cancel) {
                    return
                }
                this.loading = true 
                let response = null
                if (this.getAccessToken) {
                    response = await this['home/getUserPostTypes']({
                        nextPage,
                        params: this.params,
                    })
                } else {
                    response = await this['home/getPostTypes']({
                        nextPage,
                        params: this.params,
                    })
                }
                this.loading = false
                if (response) {
                    this.incrementNextPage()
                } else {
                    this.decrementNextPage()
                }
                this.setAttachmentAfter()
            },
            getBooks(){
                this.params.postType = 'books' //attach posttype for filtering using the same api call as getPosts
                this.getPostTypes()
            },
            getRiddles(){
                this.params.postType = 'riddles'
                this.getPostTypes()
            },
            getPoems(){
                this.params.postType = 'poems'
                this.getPostTypes()
            },
            getQuestions(){
                this.params.postType = 'questions'
                this.getPostTypes()
            },
            getActivities(){
                this.params.postType = 'activities'
                this.getPostTypes()
            },
            async infiniteHandler($state){
                let response = null,
                    {nextPage} = this.checkNextPageCancel(this.sideValue)

                if (this.sideValue === 'posts') {
                    if (!nextPage) {
                        $state.complete()
                        return
                    }
                    if (nextPage === 1) {
                        return
                    }
                    if (this.getAccessToken) {
                        response = await this['home/getUserPosts']({
                            nextPage,
                            params: this.params,
                        })
                    } else {
                        response = await this['home/getPosts']({
                            nextPage,
                            params: this.params,
                        })
                    }
                } else if (this.sideValue === 'reads') {

                } else if (this.sideValue === 'discussions') {

                } else if (this.sideValue === 'riddles' || this.sideValue === 'poems' ||
                    this.sideValue === 'books' || this.sideValue === 'questions' ||
                    this.sideValue === 'activities') {
                    if (!nextPage) {
                        $state.complete()
                        return
                    }
                    if (this.getAccessToken) {
                        response = await this['home/getUserPostTypes']({
                            nextPage,
                            params: this.params,
                        })
                    } else {
                        response = await this['home/getPostTypes']({
                            nextPage,
                            params: this.params,
                        })
                    }
                }

                if (response) {
                    this.incrementNextPage()
                    
                    $state.loaded()
                } else {
                    this.decrementNextPage()
                    $state.complete()
                }
                this.setAttachmentAfter()
            },
        },
    }
</script>

<style lang="scss" scoped>
// $color-primary: rgba(127,255,212,1.0);
// $local-color-main: whitesmoke;

    .home-wrapper{
        position: relative;
        background: $dashboard-section-second-background-color;

        .home-top{
            background: $color-primary;
            width: 100%;
            margin: 0;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: center;
            align-items: center;

            .move-to-top{
                color: gray;
                padding: 5px;
                cursor: pointer;
                position: absolute;
                left: 10px;
            }

            .search-section{

            }
        }

        .home-middle{
            width: 95%;
            margin: 0 auto 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;

            .menu{
                width: 15%;
            }

            .main{
                width: 50%;
                background-color: inherit;
            }

            .side{
                width: 25%;
            }
        }
    }

@media screen and (max-width: 800px) {
    
    .home-wrapper{

        .home-middle{ 

            .menu{
                width: 100%;
                margin: 10px 0;
            }

            .main{
                width: 60%;
            }

            .side{
                width: 35%;
            }
        }
    }
}


@media screen and (max-width: 550px) {
    
    .home-wrapper{

        .home-middle{

            .menu{
                width: 45%;
            }

            .main{
                width: 100%;
                order: 10;
            }

            .side{
                width: 50%;
                margin: 10px 0;
            }
        }
    }
}
</style>