import mutations from "./profile/profile.mutations";
import actions from "./profile/profile.actions";

const profile = {
    namespaced: true,
    state: () => ({
        account: null,
        profile: null,
        message: null,
        post: null,
        nextPage: 0,
        commentNextPage: 0,
        postNextPage: 0,
        done: false,
        posts: [],
        comments: [],
        postingStatus: null,
        commentingStatus: null,
        commentsDone: false,
        postsDone: false,
        loading: null,
        activeProfile: null, //the account the user is currently using
        media: [],
        mediaType: '',
        moreMedia: false,
    }),

    mutations: mutations,

    actions: actions,

    getters: {
        getAccount(state){
            return state.account ? state.account : null
        },
        getProfile(state){
            return state.profile ? state.profile : null
        },
        profilePosts(state){
            return state.posts
        },
        getMsg(state){
            return state.message ? state.message : null
        },
        getPostingStatus(state){
            return state.postingStatus ? true : false
        },
        getLoadingStatus(state){
            return state.loading ? true : false
        },
        getCommentingStatus(state){
            return state.commentingStatus ? true : false
        },
        getPostsDone(state){
            return state.done ? true : false
        },
        getCommentsDone(state){
            return state.commentsDone ? true : false
        },
        getActiveProfile(state){
            return state.activeProfile
        },
        getNextPage(state){
            return state.nextPage
        },
        getStateComments(state){
            return state.comments.length ? state.comments : null
        },
        getCommentNextPage(state){
            return state.commentNextPage
        },
        getPostNextPage(state){
            return state.postNextPage
        },
        getMoreMedia(state){
            return state.moreMedia
        },
        getStateMedia(state){
            return { 
                media: state.media,
                mediaType: state.mediaType
            }
        },
    }
}

export default profile