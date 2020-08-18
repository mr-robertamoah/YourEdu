import {ProfileService} from "../../services/profile.service";
import {strings} from '../../services/helpers';

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



    mutations: {
        CLEAR_MEDIA(state){
            state.media = []
            state.mediaType = ''
            state.moreMedia = false
        },
        PUBLIC_MEDIA_SUCCESS(state, data){
            if (data.media === 'images') {
                state.profile.images.unshift(data.main.media)
            } else if (data.media === 'videos') {
                state.profile.videos.unshift(data.main.media)
            } else if (data.media === 'audios') {
                state.profile.audios.unshift(data.main.media)
            }
        },
        GET_MEDIA_SUCCESS(state,media){
            let {mainData, data} = media
            console.log('get media', state.media)
            if (data.media === 'images') {
                state.mediaType = 'image'
            } else if (data.media === 'videos') {
                state.mediaType = 'video'
            } else if (data.media === 'audios') {
                state.mediaType = 'audio'
            }
            if (mainData.links.next) {
                state.moreMedia = true
            } else {
                state.moreMedia = false
            }
            state.media.push(...mainData.data)
        },
        GET_PROFILE_SUCCESS(state,data){
            state.message = 'successfully got the requested profile'
            state.account = data.account
            state.profile = data.profile
        },
        UPDATE_PROFILE_SUCCESS(state,data){
            state.message = 'successfully updated your profile'
            state.profile = data.profile
        },
        UPDATE_PROFILE_PIC_SUCCESS(state,data){
            state.profile.url = data.image.url
        },
        UPLOAD_FILE_SUCCESS(state,data){
            if (data.hasOwnProperty('image')) {
                state.profile.images.unshift(data.image)
            } else if (data.hasOwnProperty('video')) {
                state.profile.videos.unshift(data.video)
            } else if (data.hasOwnProperty('audio')) {
                state.profile.audios.unshift(data.audio)
            }
        },
        PROFILE_FAILURE(state, msg){
            state.message = msg
        },
        CLEAR_PROFILE_MSG(state){
            state.message = null
        },
        ADD_INFO_SUCCESS(state,responseData,data){
            if (data.hasOwnProperty('email')) {
                state.profile.owner.emails.unshift(responseData.email)
            } else if (data.hasOwnProperty('social')){
                state.profile.socials.unshift(responseData.social)
            } else if (data.hasOwnProperty('phone')){
                state.profile.owner.phoneNumbers.unshift(responseData.phone)
            }
        },
        MARK_INFO_SUCCESS(state,data){
            if (data.item === 'email') {
                let emailIndex = state.profile.owner.emails.findIndex(email=>{
                    return email.id === data.id
                })

                if (emailIndex > -1) {
                    // console.log(state.profile.owner.emails[emailIndex].show)
                    state.profile.owner.emails[emailIndex].show = !state.profile.owner.emails[emailIndex].show
                }
            } else if (data.item === 'social'){
                let socailIndex = state.profile.socials.findIndex(social=>{
                    return social.id === data.id
                })

                if (socailIndex > -1) {
                    state.profile.socails[socailIndex].show = !state.profile.socails[socailIndex].show
                }
            } else if (data.item === 'phone'){
                let phoneIndex = state.profile.owner.phoneNumbers.findIndex(phone=>{
                    return phone.id === data.id
                })

                if (phoneIndex > -1) {
                    state.profile.owner.phoneNumbers[phoneIndex].show = !state.profile.owner.phoneNumbers[phoneIndex].show
                }
            }
        },
        REMOVE_INFO_SUCCESS(state,data){
            if (data.item === 'email') {
                let emailIndex = state.profile.owner.emails.findIndex(email=>{
                    return email.id === data.id
                })

                if (emailIndex > -1) {
                    state.profile.owner.emails.splice(emailIndex,1)
                }
            } else if (data.item === 'social'){
                let socailIndex = state.profile.socials.findIndex(social=>{
                    return social.id === data.id
                })

                if (socailIndex > -1) {
                    state.profile.socails.splice(socailIndex,1)
                }
            } else if (data.item === 'phone'){
                let phoneIndex = state.profile.owner.phoneNumbers.findIndex(phone=>{
                    return phone.id === data.id
                })

                if (phoneIndex > -1) {
                    state.profile.owner.phoneNumbers.splice(phoneIndex,1)
                }
            }
        },

        ////////////////////////////////////////////////////////// follow

        FOLLOW_SUCCESS(state,data){
            state.profile.follows.unshift(data.follow)
        },
        UNFOLLOW_SUCCESS(state,data){
            let followIndex = state.profile.follows.findIndex(follow=>{
                return follow.id === data.followId
            })

            if (followIndex > -1) {
                state.profile.follows.splice(followIndex,1)
            }
        },

        /////////////////////////////////////////////////////////// posts
        CLEAR_POSTS(state){
            state.posts = []
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
                state.postNextPage = data.meta.current_page + 1
            } else{
                state.postDone = true
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
            if (data.owner.toLocaleLowerCase().includes('post')) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.ownerId
                })
                if (postIndex > -1) {
                    let commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.posts[postIndex].comments.splice(commentIndex,1)
                    }
                } else if (data.owner.toLocaleLowerCase().includes('post')) {
                    let commentIndex = state.comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.comments.splice(commentIndex,1)
                    }
                }
            }
        },
        // COMMENTS_SUCCESS(state,data){
        //     if (data.data.length) {
        //         state.posts.push(...data.data)
        //         state.nextPage = data.meta.current_page + 1
        //     } else{
        //         state.done = true
        //     }
        // },
        COMMENT_SUCCESS(state, data){
            if (data.comment.commentable_type.toLocaleLowerCase().includes('post')) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    state.posts[postIndex].comments.unshift(data.comment)
                }
            } else if (data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                if (state.comments && state.comments.length) {
                    state.comments.unshift(data.comment)
                }
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
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('comment')) {
                    let commentIndex = state.comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })

                    if (commentIndex > -1) {
                        state.comments[commentIndex].likes.push(data.like)
                    }
                } else if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null
                    let commentIndex = null
                    
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

        
        ////////////////////////////////// flag

        FLAG_CREATE_SUCCESS(state, data){
            if (data.data.hasOwnProperty('profile') && data.data.profile) {
                state.profile.flags.unshift(data.flag)
            } else if (data.data.hasOwnProperty('post') && data.data.post) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.data.itemId //itemId is the post id
                })
                if (postIndex > -1) {
                    state.posts.splice(postIndex,1)
                }
            } else if (data.data.hasOwnProperty('comment') && data.data.comment) {
                if (strings.getAccount(data.data.commentable_type) !== 'post') {
                    return
                }
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.data.commentable_id //commentable_id is the post id
                })
                if (postIndex > -1) {
                    let commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.data.itemId
                    })
                    if (commentIndex > -1) {
                        state.posts[postIndex].comments.splice(commentIndex,1)
                    }
                }
            }
        },
        FLAG_DELETE_SUCCESS(state, data){
            if (data.hasOwnProperty('profile') && data.profile) {
                let flagIndex = state.profile.flags.findIndex(flag=>{
                    return flag.id === data.flagId
                })
                if (flagIndex > -1) {
                    state.profile.flags.splice(flagIndex,1)
                }
            } else if (data.hasOwnProperty('post') && data.post) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.itemId //itemId is the post id
                })
                if (postIndex > -1) {
                    let flagIndex = state.posts[postIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.posts[postIndex].flags.splice(flagIndex,1)
                    }
                }
            } else if (data.hasOwnProperty('comment') && data.comment) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.commentable_id //itemId is the post id
                })
                if (postIndex > -1) {
                    let commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })
                    if (commentIndex > -1) {
                        let flagIndex = state.posts[postIndex].comments[commentIndex]
                            .flags.findIndex(flag=>{
                                return flag.id === data.flagId
                            })
                        if (flagIndex > -1) {
                            state.posts[postIndex].comments[commentIndex]
                                .flags.splice(flagIndex,1)
                        }
                    }
                }
            }
        },

        ////////////////////////////////////////
        SET_ACTIVE_PROFILE(state, data){
            state.activeProfile = data
        },
    },



    actions: {

        async follow({commit}, data){
            console.log('data in profile',data)
            let response = await ProfileService.followCreate(data)

            console.log('response in profile',response.data)
            if (response.data.message === 'successful') {
                commit('FOLLOW_SUCCESS',response.data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','following was unsuccessul')
                return 'unsuccessful'
            }
        },
        async unfollow({commit}, data){
            
            let response = await ProfileService.followDelete(data)

            if (response.data.message === 'successful') {
                commit('UNFOLLOW_SUCCESS',data)
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','unfollowing was unsuccessul')
                return 'unsuccessful'
            }
        },

        ///////////////////////////////////////////// profile

        async deleteMedia({commit},data){
            let response = await ProfileService.profileMediaDelete(data)

            if (response.data.message === 'successful') {
                return 'successful'
            }else {
                return 'unsuccessful'
            }
        },
        async changeMedia({commit},data){
            let response = await ProfileService.profileMediaChange(data)

            if (response.data.message === 'successful') {
                commit('PUBLIC_MEDIA_SUCCESS',{main: response.data,data})
                return 'successful'
            }else {
                return 'unsuccessful'
            }
        },
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
                commit('UPDATE_PROFILE_SUCCESS',response.data,data)
            }else {
                commit('PROFILE_FAILURE','update was unsuccessul')
            }
        },
        async updateProfilePic({commit},data){

            let response = await ProfileService.profilePicUpdate(data)

            if (response.data.message === 'successful') {
                commit('UPDATE_PROFILE_PIC_SUCCESS',response.data)
                return response.data.message
            }else {
                return response.data.message
            }
        },
        async uploadFile({commit},data){

            let response = await ProfileService.profileFileUpload(data)

            if (response.data.message === 'successful') {
                commit('UPLOAD_FILE_SUCCESS',response.data)
                return response.data.message
            }else {
                return response.data.message
            }
        },
        clearMedia({commit}){
            commit("CLEAR_MEDIA")
        },
        async getMedia({commit},data){
            // commit('CLEAR_MEDIA')
            let response = await ProfileService.profileMediaGet(data)

            if (response.data.data) {
                // commit('GET_MEDIA_SUCCESS',{mainData: response.data,data})
                return response.data
            }else {
                return 'unsuccessful'
            }
        },
        async getPrivateMedia({commit},data){
            // dispatch('clearMedia')
            let response = await ProfileService.profilePrivateMediaGet(data)

            if (response.data.data) {
                // commit('GET_MEDIA_SUCCESS',{mainData: response.data,data})
                return response.data
            }else {
                return 'unsuccessful'
            }
        },
        async addInfo({commit},data){

            let response = await ProfileService.profileAddInfo(data)

            if (response.data.message === 'successful') {
                commit('ADD_INFO_SUCCESS',response.data,data)
                return 'successful'
            }else {
                return response.data.errors
            }
        },
        async markInfo({commit},data){

            let response = await ProfileService.profileMarkInfo(data)

            if (response.data.message === 'successful') {
                commit('MARK_INFO_SUCCESS',data)
                return 'successful'
            }else {
                return 'unsuccessful'
            }
        },
        async removeInfo({commit},data){

            let response = await ProfileService.profileDeleteInfo(data)

            if (response.data.message === 'successful') {
                commit('REMOVE_INFO_SUCCESS',data)
                return 'successful'
            }else {
                return 'unsuccessful'
            }
        },

        ///////////////////////////////////////////////////

        clearMsg({commit}){
            commit('CLEAR_PROFILE_MSG')
        },

        ////////////////////////////////////// likes

        async createLike({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.likeCreate(data)
            // console.log('like data', data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                data['like'] = response.data.like
                commit('LIKE_CREATE_SUCCESS', data)
                return response.data.like
            }else {
                commit('PROFILE_FAILURE','liking unsuccessful')
                return 'unsuccessful'
            }
        },
        async deleteLike({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.likeDelete(data)
            // console.log('like data', data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                commit('LIKE_DELETE_SUCCESS', data)
                return data
            }else {
                commit('PROFILE_FAILURE','liking unsuccessful')
                return 'unsuccessful'
            }
        },

        ////////////////////////////////////// flags

        async createFlag({commit},data){
            commit('LIKING_START')
            console.log('create flag');
            let response = await ProfileService.flagCreate(data)

            commit('LIKING_END')
            if (response.data.message === 'successful') {
                data['flag'] = response.data.like
                // commit('FLAG_CREATE_SUCCESS', data)
                commit('FLAG_CREATE_SUCCESS', {data,flag: response.data.flag})
                return {status: true,flag: response.data.flag}
            }else {
                commit('PROFILE_FAILURE','flagging unsuccessful')
                return {status: false, message:'unsuccessful'}
            }
        },
        async deleteFlag({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.flagDelete(data)

            commit('LIKING_END')
            if (response.data.message === 'successful') {
                commit('FLAG_DELETE_SUCCESS', data)
                return {data,status: true}
            }else {
                commit('PROFILE_FAILURE','unflagging unsuccessful')
                return {status: false, message:'unsuccessful'}
            }
        },

        ////////////////////////////////////// marks

        async createMark({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.markCreate(data)
            // console.log('like data', data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                // data['mark'] = response.data.like
                // commit('MARK_CREATE_SUCCESS', data)
                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','marking unsuccessful')
                return {status: false, message: response.data.message}
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
                return response.data
            }else {
                commit('PROFILE_FAILURE','commenting unsuccessful')
                return 'unsuccessful'
            }
        },
        async deleteComment({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.commentDelete(data)

            commit('COMMENTING_END')
            // console.log('deleted comment', response)
            if (response.data.message === 'successful') {
                commit('COMMENT_DELETE_SUCCESS',data)
                return data
            }else {
                commit('PROFILE_FAILURE','comment update unsuccessful')
                return 'unsuccessful'
            }
        },
        async updateComment({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.commentUpdate(data)

            commit('COMMENTING_END')
            // console.log('update post', response)
            if (response.data.message === 'successful') {
                commit('COMMENT_UPDATE_SUCCESS',response.data)
                return response.data
            }else {
                commit('PROFILE_FAILURE','post update unsuccessful')
                return 'unsuccessful'
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
            if (response.data.data) {
                return {
                        status:response.data.links.next,
                        data: response.data
                    }
            }else {
                commit('PROFILE_FAILURE','commenting was unsuccessful')
                return 'unsuccessful'
            }
        },

        clearComments({commit}){
            commit('COMMENTS_CLEAR')
        },

        ///////////////////////////////////////// answers
        
        async createAnswer({commit},mainData){
            commit('COMMENTING_START')
            let response = await ProfileService.answerCreate(mainData)
            // console.log('comment data', data)
            commit('COMMENTING_END')
            if (response.data.message === 'successful') {
                // commit('COMMENT_SUCCESS',response.data)
                return response.data
            }else {
                commit('PROFILE_FAILURE','creation of answer unsuccessful')
                return response.data.message
            }
        },
        async deleteAnswer({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.answerDelete(data)

            commit('COMMENTING_END')
            // console.log('deleted comment', response)
            if (response.data.message === 'successful') {
                // commit('COMMENT_DELETE_SUCCESS',data)
                return response.data
            }else {
                commit('PROFILE_FAILURE','comment update unsuccessful')
                return 'unsuccessful'
            }
        },
        async updateAnswer({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.answerUpdate(data)

            commit('COMMENTING_END')
            // console.log('update post', response)
            if (response.data.message === 'successful') {
                // commit('COMMENT_UPDATE_SUCCESS',response.data)
                return response.data
            }else {
                commit('PROFILE_FAILURE','post update unsuccessful')
                return response.data.message
            }
        },
        async getAnswers({commit},data){
            commit('COMMENTING_START')
            let response = await ProfileService.answersGet(data)

            commit('COMMENTING_END')
            if (response.data.data) {
                return {
                        status:response.data.links.next,
                        data: response.data
                    }
            }else {
                commit('PROFILE_FAILURE','commenting was unsuccessful')
                return 'unsuccessful'
            }
        },

        ////////////////////////////////// posts

        clearPosts({commit}){
            commit('CLEAR_POSTS')
        },
        async createPost({commit},data){
            commit('POST_START')
            let response = await ProfileService.postCreate(data)

            commit('POST_END')
            // console.log('comment data', response)
            if (response.data.message === 'successful') {
                commit('POST_CREATE_SUCCESS',response.data)
                return 'success'
            }else {
                commit('PROFILE_FAILURE','post unsuccessful')
                return 'unsuccessful'
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

        async getPosts({commit},data){
            commit('LOADING_START')
            let response = await ProfileService.postsGet(data)
            // console.log(response.data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POSTS_SUCCESS',response.data)
                return response.data.links.next
            }else {
                commit('PROFILE_FAILURE','retrieving posts unsuccessful')
                return 'unsuccessful'
            }
        },

        async getUserPosts({commit},data){
            commit('LOADING_START')
            let response = await ProfileService.userPostsGet(data)
            // console.log(response.data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POSTS_SUCCESS',response.data)
                return response.data.links.next
            }else {
                commit('PROFILE_FAILURE','retrieving posts unsuccessful')
                return 'unsuccessful'
            }
        },

        async getProfilePosts({commit},data){
            commit('LOADING_START')
            console.log('responsedata',data)
            let response = await ProfileService.profilePostsGet(data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POSTS_SUCCESS',response.data)
                console.log('next',response.data.links.next)
                if (response.data.links.next) {
                    
                    return response.data.links.next
                } else {
                    return null
                }
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
        getHomePosts(state){
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