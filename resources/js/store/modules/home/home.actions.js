import { HomeService } from "../../../services/home.service"

const actions = {

    clearPosts({commit}){
        commit('CLEAR_POSTS')
    },
    clearHomePostsAttachments({commit}){
        commit('CLEAR_POSTS_ATTACHMENTS')
    },
    clearHomeQuestionsAttachments({commit}){
        commit('CLEAR_QUESTIONS_ATTACHMENTS')
    },
    clearHomePoemsAttachments({commit}){
        commit('CLEAR_POEMS_ATTACHMENTS')
    },
    clearHomeBooksAttachments({commit}){
        commit('CLEAR_BOOKS_ATTACHMENTS')
    },
    clearHomeActivitiesAttachments({commit}){
        commit('CLEAR_ACTIVITIES_ATTACHMENTS')
    },
    clearHomeRiddlesAttachments({commit}){
        commit('CLEAR_RIDDLES_ATTACHMENTS')
    },
    async getPosts({commit},data){
        commit('LOADING_START')
        let response = await HomeService.postsGet(data)
        commit('LOADING_END')
        if (! response.data.data) {
            return 'unsuccessful'
        }

        commit('POSTS_SUCCESS',{
            params: data.params, 
            data: response.data.data,
            nextPage: data.nextPage
        })
        return response.data?.links?.next
    },
    async getUserPosts({commit},data){
        commit('LOADING_START')
        let response = await HomeService.userPostsGet(data)
        commit('LOADING_END')
        if (! response.data.data) {
            return 'unsuccessful'
        }

        commit('POSTS_SUCCESS',{
            params: data.params,
            data: response.data.data,
            nextPage: data.nextPage
        })
        return response.data?.links?.next
    },
    async getPostTypes({commit},data){
        commit('LOADING_START')
        let response = await HomeService.postTypesGet(data)
        commit('LOADING_END')

        if (! response.data.data) {
            return 'unsuccessful'
        }

        commit('POST_TYPES_SUCCESS', {
            params: data.params,
            data: response.data.data,
            nextPage: data.nextPage
        })
        return response.data?.links?.next
    },
    async getUserPostTypes({commit},data){
        commit('LOADING_START')
        let response = await HomeService.userPostTypesGet(data)
        commit('LOADING_END')

        if (! response.data.data) {
            return 'unsuccessful'
        }

        commit('POST_TYPES_SUCCESS', {
            params: data.params,
            data: response.data.data,
            nextPage: data.nextPage
        })
        return response.data?.links?.next
    },
    newPost({commit,rootGetters}, post){
        commit('NEW_POST', {
            post,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    newItem({commit,rootGetters}, data){
        commit('NEW_ITEM', {
            ...data,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    newFollowerItem({commit}, data){
        commit('NEW_ACCOUNT_ITEM', {
            ...data,
            isAFollower: true,
        })
    },
    newFollowingItem({commit}, data){
        commit('NEW_ACCOUNT_ITEM', {
            ...data,
            isAFollowing: true,
        })
    },
    replacePost({commit,rootGetters}, post){
        commit('REPLACE_POST', {
            post,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    removePost({commit}, postInfo){
        commit('REMOVE_POST', postInfo)
    },

    //////////////////////////////////////////discussions
    
    newItemParticipant({commit}, data){
        commit('NEW_ITEM_PARTICIPANT', data)
    },
    removeItemParticipant({commit}, data){
        commit('REMOVE_ITEM_PARTICIPANT', data)
    },
    updateItemParticipant({commit}, data){
        commit('UPDATE_ITEM_PARTICIPANT', data)
    },
    newItemPendingParticipant({commit}, data){
        commit('NEW_ITEM_PENDING_PARTICIPANT', data)
    },
    removeItemPendingParticipant({commit}, data){
        commit('REMOVE_ITEM_PENDING_PARTICIPANT', data)
    },
    newDiscussion({commit,rootGetters}, discussion){
        commit('NEW_DISCUSSION', {
            discussion,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    replaceDiscussion({commit,rootGetters}, discussion){
        commit('REPLACE_DISCUSSION', {
            discussion,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    removeDiscussion({commit}, discussionInfo){
        commit('REMOVE_DISCUSSION', discussionInfo)
    },

    /////////////////////////////////////comments

    newComment({commit,rootGetters}, commentData){
        commit('NEW_COMMENT', {
            commentData,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    replaceComment({commit,rootGetters}, commentData){
        commit('REPLACE_COMMENT', {
            commentData,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    removeComment({commit, rootGetters}, commentData){
        commit('REMOVE_COMMENT', {
            commentData,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },

    //////////////////////////////////////attachment
    
    newAttachment({commit,rootGetters}, attachmentData){
        commit('NEW_ATTACHMENT', {
            attachmentData,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    removeAttachment({commit}, attachmentInfo){
        commit('REMOVE_ATTACHMENT', attachmentInfo)
    },

    //////////////////////////////////////flags
    
    newFlag({commit,rootGetters}, flag){
        commit('NEW_FLAG', {
            flag,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    removeFlag({commit}, flagInfo){
        commit('REMOVE_FLAG', flagInfo)
    },

    //////////////////////////////////////likes
    
    newLike({commit,rootGetters}, likeData){
        commit('NEW_LIKE', {
            likeData,
            userFollowers: rootGetters.getUserFollowers,
            userFollowings: rootGetters.getUserFollowings,
        })
    },
    removeLike({commit}, likeInfo){
        commit('REMOVE_LIKE', likeInfo)
    },

    //////////////////////////////////// search

    async search(ctx, params){

        let response =  await HomeService.searchGet(params)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false}
        }
    },
}

export default actions