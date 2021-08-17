<template>
    <div class="home-wrapper">
        <app-nav></app-nav>
        <div class="home-top">
            <YourEduLogo 
                class="move-to-top w-10"
                @click="moveToTop"
            />
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
            <div 
                class="hover:text-whitesmoke text-gray-500 text-base p-1 cursor-pointer
                    absolute right-1"
                @click="clickedButton('details')"
            >
                <font-awesome-icon
                    :icon="['fa', `${showDetails ? 'chevron-up' : 'chevron-down'}`]"
                ></font-awesome-icon>
            </div>
        </div>
        <fade-down>
            <template slot="transition" v-if="showDetails">
                <home-menu-bar
                    :activeItem="sideValue"
                    @clickedMenuItem="emitSideValue"
                ></home-menu-bar>
            </template>
        </fade-down>
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
                    :posts="computedPosts"
                    @clickedShowPostComments="clickedShowPostComments"
                    @clickedShowPostPreview="clickedShowPostPreview"
                ></home-main>
            </div>
            <div class="side">
                <home-side
                    @clickedItem="emitSideValue"
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
            v-if="computedPosts.length"
            force-use-infinite-wrapper
        ></infinite-loading>
    </div>
</template>

<script>
import HomeMain from '../components/home/HomeMain.vue'
import HomeMenu from '../components/home/HomeMenu'
import YourEduLogo from '../components/YourEduLogo'
import HomeSide from '../components/home/HomeSide'
import FadeUp from '../components/transitions/FadeUp'
import SmallModal from '../components/SmallModal'
import SearchInput from '../components/SearchInput'
import SearchOutput from '../components/SearchOutput'
import InfiniteLoading from 'vue-infinite-loading'
import { mapActions, mapGetters } from 'vuex'
import FadeDown from '../components/transitions/FadeDown.vue'
import HomeMenuBar from '../components/HomeMenuBar.vue'

    export default {
        components: {
            YourEduLogo,
            HomeMain,
            HomeMenu,
            SearchOutput,
            SearchInput,
            SmallModal,
            FadeUp,
            HomeSide,
            InfiniteLoading,
            FadeDown,
            HomeMenuBar,
        },
        data() {
            return {
                showLoginRegister: false,
                showDetails: false,
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
                //
                readsNextPage: 1,
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
                views: [],
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
                this.getData()
            },
            menuValue(newValue){
                if (! this.getUser) {
                    this.menuValue = 'all'
                    this.showLoginRegister = true
                    return
                }

                this.params = {
                    user: this.getUser.id,
                }

                if (newValue === 'all') {
                    this.params.all = true
                }
                
                if (newValue === 'followers') {
                    this.params.followers = true
                }
                
                if (newValue === 'followings') {
                    this.params.followings = true
                }
                
                if (newValue === 'mine') {
                    this.params.mine = true
                }
                
                if (newValue === 'attachments') {
                    
                }

                this.getData()
            },
            menuAttachment(newValue){
                if (! newValue) return

                this.params = {}
                this.params.user = this.getUser.id
                this.params.attachments = true
                this.params.attach = newValue.type,
                this.params.id = newValue.data.id

                this.getData()
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
            ...mapGetters(['getAccessToken', 'getUser']),
            computedPosts() {
                return [
                    ...new Set(this.$store.getters[`home/getHome${_.capitalize(this.computedCurrentStates)}`])
                ]
            },
            computedProfiles() {
                return this.getProfiles ? this.getProfiles : []
            },
            computedMenuValue() {
                return this.menuValue === 'all' ? '' : this.menuValue
            },
            computedCurrentStates() {
                return `${this.sideValue}${_.capitalize(this.computedMenuValue)}`
            },
            computedNextPageForMenuValue() {
                return this[`${this.computedCurrentStates}NextPage`]
            },
            computedIsPostType() {
                return !this.computedIsNotPostType
            },
            computedIsNotPostType() {
                return ['posts', 'discussions', 'reads', 'assessments'].includes(this.sideValue)
            },
        },
        methods: {
            ...mapActions(['home/getPosts','home/clearPosts','home/getUserPosts',
                'getProfiles','home/clearHomePostsAttachments','home/getPostTypes',
                'home/getUserPostTypes','home/clearHomeQuestionsAttachments'
                ,'home/clearHomePoemsAttachments','home/clearHomeRiddlesAttachments'
                ,'home/clearHomeBooksAttachments','home/clearHomeActivitiesAttachments',
                'home/search','home/newPost', 'home/newAssessment', 'home/newRead',
                'home/newDiscussion','profile/getPost'
            ]),
            clickedButton(data) {
                if (data === 'details') {
                    this.showDetails = !this.showDetails
                    return
                }
            },
            setPostTypeOnParams(params) {
                if (this.computedIsPostType) {
                    params.postType = this.sideValue
                    return params
                }

                params.postType = 'posts'
                return params
            },
            setTypeOnParams(params) {
                if (this.computedIsPostType || this.sideValue === 'posts') {
                    params.type = 'posts'
                    return params
                }

                params.type = this.sideValue
                return params
            },
            clickedSearchOutputButton(data){
                this.searchOutputType = data
            },
            listen(){
                Echo.channel('youredu.home')
                    .listen('.newPost', (data)=>{
                        this['home/newPost'](data)
                    })
                    .listen('.newDiscussion', (data)=>{
                        this['home/newDiscussion'](data)
                    })
                    .listen('.newAssessment', (data)=>{
                        this['home/newAssessment'](data)
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
                    if (searchItem.accountId === data.accountId &&
                        searchItem.account === data.account) {
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
                    if (searchItem.accountId === data.accountId &&
                        searchItem.account === data.account) {
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
            emitSideValue(data){
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
            getData(){
                let what = this.computedIsPostType ? 'PostTypes' : 'Posts'

                this[`get${what}`]() //getPosts getPostTypes
            },
            incrementNextPage(){
                if (!this[`${this.sideValue}${_.capitalize(this.computedNextPageForMenuValue)}NextPage`]) {
                    return
                }

                this[`${this.sideValue}${_.capitalize(this.computedNextPageForMenuValue)}NextPage`] += 1
            },
            decrementNextPage(){
                if (!this[`${this.sideValue}${_.capitalize(this.computedNextPageForMenuValue)}NextPage`]) {
                    return
                }

                this[`${this.sideValue}${_.capitalize(this.computedNextPageForMenuValue)}NextPage`] -= 1
            },
            checkNextPageCancel(){
                if (this[`${this.sidevalue}${_.capitalize(this.computedNextPageForMenuValue)}NextPage`] !== 1 && 
                    this[`home/getHome${_.capitalize(this.sideValue)}${_.capitalize(this.menuValue)}`]) {
                    return true
                }

                return false
            },
            setAttachmentAfter(){

                if (!this.params.attachments) {
                    return
                } 

                this[`${this.sideValue}AttachmentAfter`] =  this.menuAttachment
            },
            setUpParams() {
                let params = this.setPostTypeOnParams(this.params)
                params = this.setTypeOnParams(params)

                return params
            },
            inView() {
                return this.views.includes(this.computedCurrentStates)
            },
            thereIsEnoughPosts() {
                return this.computedPosts?.length >= 10
            },
            thereIsntEnoughPosts() {
                return !this.thereIsEnoughPosts()
            },
            addToView() {
                if (! this.computedPosts?.length) {
                    return
                }

                if (this.inView()) {
                    return
                }

                this.views.push(this.computedCurrentStates)
            },
            async getPosts() {
                console.log({
                    nextPage: this.computedNextPageForMenuValue,
                    params: this.setUpParams(),
                });
                
                if (this.checkNextPageCancel()) {
                    return
                }

                if (this.inView() && this.thereIsEnoughPosts()) {
                    return
                }

                this.loading = true 
                let response = await this[`home/get${this.getAccessToken ? 'User' : ''}Posts`]({
                    nextPage: this.computedNextPageForMenuValue,
                    params: this.setUpParams(),
                })

                this.setNextPageAndAttachmentAfter(response)
                
                this.loading = false

                this.addToView()
            },
            async getPostTypes(){
                console.log({
                    nextPage: this.computedNextPageForMenuValue,
                    params: this.setUpParams(),
                });
                
                if (this.checkNextPageCancel()) {
                    return
                }

                if (this.inView()) {
                    return
                }

                this.loading = true 

                let response = await this[`home/get${this.getAccessToken ? 'User' : ''}PostTypes`]({
                    nextPage: this.computedNextPageForMenuValue,
                    params: this.setUpParams(),
                })
                    
                this.setNextPageAndAttachmentAfter(response)

                this.loading = false
            },
            setNextPageAndAttachmentAfter(response, $state = null) {
                if (response) {
                    this.incrementNextPage()
                    $state?.loaded()
                } 
                
                if (! response) {
                    this.decrementNextPage()
                    $state?.complete()
                }

                this.setAttachmentAfter()
            },
            async infiniteHandler($state){
                
                if (this.computedNextPageForMenuValue === 1) {
                    return
                }

                if (! this.computedNextPageForMenuValue) {
                    $state.complete()
                    return
                }
                
                let response = null

                if (this.computedIsNotPostType) {
                    response = await this['home/getUserPosts']({
                        nextPage: this.computedNextPageForMenuValue,
                        params: this.params,
                    })
                }
                
                if (this.computedIsPostType) {
                    response = await this['home/getUserPostTypes']({
                        nextPage: this.computedNextPageForMenuValue,
                        params: this.params,
                    })
                }

                this.setNextPageAndAttachmentAfter(response, $state)
            },
        },
    }
</script>

<style lang="scss" scoped>

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
                padding-top: 20px;
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