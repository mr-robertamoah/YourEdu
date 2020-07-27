import {ProfileService} from "../../services/profile.service";

const profile = {
    namespaced: true,
    state: () => ({
        account: null,
        profile: null,
        message: null,
        post: null,
        nextPage: 0,
        commentNextPage: 0,
        done: false,
        posts: [],
        comments: [],
        postingStatus: null,
        commentingStatus: null,
        commentsDone: false,
        loading: null,
        activeProfile: null, //the account the user is currently using
    }),



    mutations: {
        GET_PROFILE_SUCCESS(state,data){
            state.message = 'successfully got the requested profile'
            state.account = data.account
            state.profile = data.profile
        },
        UPDATE_PROFILE_SUCCESS(state,data){
            state.message = 'successfully updated your profile'
            state.profile = data.profile
        },
        PROFILE_FAILURE(state, msg){
            state.message = msg
        },
        CLEAR_PROFILE_MSG(state){
            state.message = null
        },

        /////////////////////////////////////////////////////////// posts
        CLEAR_POSTS(state){
            state.post = null
            state.nextPage = 0
        },
        POST_CREATE_SUCCESS(state,data){
            state.message = 'post successful'
            state.posts.unshift(data.post)
        },
        POST_UPDATE_SUCCESS(state,data){
            let posts = state.posts
            let index = null

            index = posts.findIndex(el=>{
                return el.id === data.post.id
            })

            if (index >= 0) {
                state.posts.splice(index, 1, data.post)
            }
        },
        POST_DELETE_SUCCESS(state,data){
            let posts = state.posts
            let index = null

            index = posts.findIndex(el=>{
                return el.id === data.postId
            })

            if (index >= 0) {
                state.posts.splice(index, 1)
                state.message = 'successfully deleted post.'
            }
        },
        POSTS_SUCCESS(state,data){
            if (data.data.length) {
                state.posts.push(...data.data)
                state.nextPage = data.meta.current_page + 1
            } else{
                state.done = true
            }
        },
        POST_START(state){
            state.postingStatus = true
        },
        POST_END(state){
            state.postingStatus = false
        },
        //////////////////////////////////////////////////////////
        LOADING_START(state){
            state.loading = true
        },
        LOADING_END(state){
            state.loading = false
        },
        ///////////////////////////////////////////// comments
        COMMENTING_START(state){
            state.commentingStatus = true
        },
        COMMENTING_END(state){
            state.commentingStatus = false
        },
        COMMENTS_GET_SUCCESS(state, data){
            // console.log(data)
            if (data.data.length) {
                state.comments.push(...data.data)
                state.commentNextPage = data.meta.current_page + 1
            } else{
                state.commentDone = true
            }
            
        },
        COMMENT_GET_SUCCESS(state, data){
            // state.comment
        },
        COMMENT_UPDATE_SUCCESS(state,data){
            if (data.comment.commentable_type.toLocaleLowerCase().includes('post')) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    let commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.posts[postIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
            } else if (data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                let commentIndex = state.comments.findIndex(comment=>{
                    return comment.id === data.comment.id
                })
                if (commentIndex > -1) {
                    state.comments.splice(commentIndex,1,data.comment)
                }
            }
        },
        COMMENT_DELETE_SUCCESS(state,data){

        },
        COMMENTS_SUCCESS(state,data){
            if (data.data.length) {
                state.posts.push(...data.data)
                state.nextPage = data.meta.current_page + 1
            } else{
                state.done = true
            }
        },
        COMMENT_SUCCESS(state, data){
            if (data.comment.commentable_type.toLocaleLowerCase().includes('post')) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    state.posts[postIndex].comments.push(data.comment)
                }
            } else if (data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                
                // let commentIndex = state.comments.findIndex(comment=>{
                //     return comment.id === item.itemId
                // })
                // if (commentIndex > -1) {
                //     state.comments[commentIndex].comments.push(data.comment)
                // }
            }
        },
        COMMENT_FAILURE(state, data){

        },
        COMMENTS_CLEAR(state){
            state.comments = []
            state.commentNextPage = 0
        },

        //////////////////////////////////////// likes
        
        LIKE_CREATE_SUCCESS(state, data){
            // console.log('create success', data)
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('comment')) {
                    let commentIndex = state.comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })

                    if (commentIndex > -1) {
                        state.comments[commentIndex].likes.push(data.like)
                        // let likeIndex = state.comments[commentIndex].findIndex(like=>{
                        //     return like.id === data.likeId
                        // })
                        // if (likeIndex > -1) {
                        //     state.comments[commentIndex].likes.splice(likeIndex,1,data.like)
                        //     return
                        // }
                    }
                } else if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null
                    let commentIndex = null
                    let likeIndex = null
                    
                    postIndex = state.posts.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.posts[postIndex]
                                .comments[commentIndex].likes.push(data.like)
                            // likeIndex = state.posts[postIndex]
                            //     .comments[commentIndex].likes.findIndex(like =>{
                            //     return like.id === data.likeId
                            // })
                            // if (likeIndex > -1) {
                            //     state.posts[postIndex].comments[commentIndex]
                            //         .likes.splice(likeIndex,1,data.like)
                            //     return
                            // }
                        }
                    }
                    
                }
            } else if (data.item === 'post') {
                state.posts.forEach(post => {
                    if (post.likes && post.id === data.itemId) {
                        post.likes.push(data.like)
                        return
                    }
                })
            }
        
        },
        LIKE_DELETE_SUCCESS(state, data){
            // console.log('delete success', data)
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('comment')) {
                    let commentIndex = state.comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })

                    if (commentIndex > -1) {
                        let likeIndex = state.comments[commentIndex].findIndex(like=>{
                            return like.id === data.likeId
                        })

                        if (likeIndex > -1) {
                            state.comments[commentIndex].likes.splice(likeIndex,1)
                            return
                        }
                    }
                } else if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null
                    let commentIndex = null
                    let likeIndex = null
                    
                    postIndex = state.posts.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.posts[postIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.posts[postIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                                return
                            }
                        }
                    }
                    
                }
            } else if (data.item === 'post') {
                state.posts.forEach(post => {
                    if (post.likes && post.id === data.itemId) {
                        // console.log('the right post id', post.id)
                        let index = post.likes.findIndex(like=>{
                            return like.id === data.likeId
                        })
                        if (index > -1) {
                            post.likes.splice(index,1)
                            return
                        }
                    }
                })
            }
        },
        LIKE_FAILURE(state, data){

        },
        LIKING_START(state){

        },
        LIKING_END(state){
            
        },

        ////////////////////////////////////////
        SET_ACTIVE_PROFILE(state, data){
            state.activeProfile = data
        },
    },



    actions: {
        setActiveProfile({commit, rootGetter},data){
            let profilesArray = []
            let computedArray = []

            if (data) {
                let {account, account_id} = data
                
                if (rootGetter.getProfiles && rootGetter.getProfiles.length) {
                    profilesArray = rootGetter.getProfiles
    
                    computedArray = profilesArray.filter(el => {
                        return el.account_id === account_id && el.account_type === account
                    })
                    if (computedArray.length) {
                        commit('SET_ACTIVE_PROFILE',computedArray[0])
                    }
                }
            } else {
                if (rootGetter.getProfiles && rootGetter.getProfiles.length) {
                    commit('SET_ACTIVE_PROFILE',rootGetter.getProfiles[0])
                }
            }
            commit('SET_ACTIVE_PROFILE', null)
        },

        async updateProfile({commit},data){

            let response = await ProfileService.profileUpdate(data)

            if (response.data.message === 'successful') {
                commit('UPDATE_PROFILE_SUCCESS',response.data)
            }else {
                commit('PROFILE_FAILURE','update was unsuccessul')
            }
        },

        clearMsg({commit}){
            commit('CLEAR_PROFILE_MSG')
        },

        ////////////////////////////////////// likes

        async createLike({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.likeCreate(data)
            console.log('like data', data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                data['like'] = response.data.like
                commit('LIKE_CREATE_SUCCESS', data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','liking unsuccessful')
                return 'unsuccessful'
            }
        },
        async deleteLike({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.likeDelete(data)
            console.log('like data', data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                commit('LIKE_DELETE_SUCCESS', data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','liking unsuccessful')
                return 'unsuccessful'
            }
        },


        //////////////////////////////////////// comments

        async createComment({commit},mainData){
            commit('COMMENTING_START')
            let response = await ProfileService.commentCreate(mainData)
            // console.log('comment data', data)
            commit('COMMENTING_END')
            if (response.data.message === 'successful') {
                commit('COMMENT_SUCCESS',response.data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','commenting unsuccessful')
                return 'unsuccessful'
            }
        },

        async deleteComment({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.commentDelete(data)

            commit('COMMENTING_END')
            console.log('deleted comment', response)
            if (response.data.message === 'successful') {
                commit('COMMENT_DELETE_SUCCESS',data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','comment update unsuccessful')
                return 'unsuccessful'
            }
        },

        async updateComment({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.commentUpdate(data)

            commit('COMMENTING_END')
            console.log('update post', response)
            if (response.data.message === 'successful') {
                commit('COMMENT_UPDATE_SUCCESS',response.data)
            }else {
                commit('PROFILE_FAILURE','post update unsuccessful')
            }
        },

        async getComment({commit}, data){
            let response = await ProfileService.commentGet(data)
            if (response.data.message === 'successful') {
                commit('COMMENT_GET_SUCCESS',response.data)
                return response.data.comment
            } else {
                return 'unsuccessful'
            }
        },

        async getComments({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.commentsGet(data)

            commit('COMMENTING_END')
            console.log('update post', response)
            if (response.data.data) {
                commit('COMMENTS_GET_SUCCESS',response.data)
                // if (response.data.links.next) {
                //     return response.data.meta.current_page + 1
                // } else {
                //     return response.data.meta.current_page
                // }
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','post update unsuccessful')
                return 'unsuccessful'
            }
        },

        clearComments({commit}){
            commit('COMMENTS_CLEAR')
        },

        ////////////////////////////////// posts
        async createPost({commit},data){
            commit('POST_START')
            let response = await ProfileService.postCreate(data)

            commit('POST_END')
            // console.log('comment data', response)
            if (response.data.message === 'successful') {
                commit('POST_CREATE_SUCCESS',response.data)
            }else {
                commit('PROFILE_FAILURE','post unsuccessful')
            }
        },

        async updatePost({commit},data){
            commit('POST_START')
            let response = await ProfileService.postUpdate(data)

            commit('POST_END')
            console.log('update post', response)
            if (response.data.message === 'successful') {
                commit('POST_UPDATE_SUCCESS',response.data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','post update unsuccessful')
                return 'unsuccessful'
            }
        },

        async deletePost({commit},data){
            commit('POST_START')
            let response = await ProfileService.postDelete(data)

            commit('POST_END')
            console.log('deleted post', response)
            if (response.data.message === 'successful') {
                commit('POST_DELETE_SUCCESS',data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','post deletion unsuccessful')
                return 'unsuccessful'
            }
        },

        async getPosts({commit,state},data){
            commit('LOADING_START')
            commit('CLEAR_POSTS')
            let response = await ProfileService.postsGet(data,state.nextPage)
            // console.log(response.data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POSTS_SUCCESS',response.data)
            }else {
                commit('PROFILE_FAILURE','retrieving posts unsuccessful')
            }
        },

        async getProfilePosts({commit},data){
            commit('LOADING_START')
            // commit('CLEAR_POSTS')
            console.log('responsedata',data)
            let response = await ProfileService.profilePostsGet(data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POSTS_SUCCESS',response.data)
                
            }else {
                commit('PROFILE_FAILURE','retrieving posts unsuccessful')
                // return 'unsuccessful'
            }
        },
        ////////////////////
    },



    getters: {
        getAccount(state){
            return state.account ? state.account : null
        },
        getProfile(state){
            return state.profile ? state.profile : null
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
        getPosts(state){
            return state.posts.length ? state.posts : null
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
    }
}

export default profile