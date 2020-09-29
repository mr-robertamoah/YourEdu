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
        discussions: [],
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
            if (state.profile) {
                state.profile.follows.unshift(data.follow)
            }
        },
        UNFOLLOW_SUCCESS(state,data){
            if (state.profile) {
                let followIndex = state.profile.follows.findIndex(follow=>{
                    return follow.id === data.followId
                })
    
                if (followIndex > -1) {
                    state.profile.follows.splice(followIndex,1)
                }
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
                state.postNextPage = data.hasOwnProperty('meta') ?
                    data.meta.current_page + 1 : null
            } else{
                state.postDone = true
            }
        },
        NEW_POST(state,post){
            let oldPost = state.posts[0]
            if (oldPost.postedby_id === post.postedby_id &&
                oldPost.postedby_type === post.postedby_type) {
                state.posts.unshift(post)
            }
        },
        REPLACE_POST(state,post){
            let postIndex = state.posts.findIndex(oldPost=>{
                return oldPost.id === post.id
            })
            if (postIndex > -1) {
                state.posts.splice(postIndex,1,post)
            }
        },
        REMOVE_POST(state,postInfo){
            let postIndex = null,
                postId = Number(postInfo.postId)
            postIndex = state.posts.findIndex(p=>{
                return p.id === postId
            })
            if (postIndex > -1) {
                state.posts.splice(postIndex,1)
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

        ////////////////////////////////////////////////answers

        ANSWER_CREATE_SUCCESS(state,data){
            let postId = data.data.postId

            let postIndex = state.posts.findIndex(post=>{
                return post.id === postId
            })
            if (postIndex > -1) {
                state.posts[postIndex].type[0].answers = data.response.hasOwnProperty(answer) ?
                    data.response.answer : {}
                state.posts[postIndex].type[0].answers_number += 1
            }
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
                state.commentNextPage = data.hasOwnProperty('meta') ?
                    data.meta.current_page + 1 : null
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
        COMMENT_SUCCESS(state, data){
            if (data.comment.commentable_type.toLocaleLowerCase().includes('post')) {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    let commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        return
                    }
                    state.posts[postIndex].comments.unshift(data.comment)
                }
            } else if (data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                if (state.comments && state.comments.length) {
                    state.comments.unshift(data.comment)
                }
            }
        },
        COMMENT_FAILURE(state, data){

        },
        COMMENTS_CLEAR(state){
            state.comments = []
            state.commentNextPage = 0
        },
        NEW_COMMENT(state, data){ //only for posts
            let itemId = Number(data.commentData.itemId),
                postIndex = null
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId
            })
            if (postIndex > -1) {
                state.posts[postIndex].comments.unshift(data.commentData.comment)
            }
        },
        REPLACE_COMMENT(state, data){
            let itemId = Number(data.commentData.itemId),
                postIndex = null,
                commentIndex = null
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId
            })
            if (postIndex > -1) {
                commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                    return comment.id === data.commentData.comment.id
                })
                if (commentIndex > -1) {
                    state.posts[postIndex].comments.splice(commentIndex,1,data.commentData.comment)
                }
            }
        },
        REMOVE_COMMENT(state, data){
            let itemId = Number(data.commentData.itemId),
                commentId = Number(data.commentData.commentId),
                postIndex = null,
                commentIndex = null
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId
            })
            if (postIndex > -1) {
                commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                    return comment.id === commentId
                })
                if (commentIndex > -1) {
                    state.posts[postIndex].comments.splice(commentIndex,1)
                }
            }
        },
        //////////////////////////////////////// saves
        
        SAVE_CREATE_SUCCESS(state, data){
            let saveIndex = null
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('comment')) {
                    let commentIndex = state.comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })

                    if (commentIndex > -1) {
                        saveIndex = state.comments[commentIndex].saves.findIndex(save=>{
                            return save.id === data.sava.id
                        })
                        if (saveIndex > -1) {
                            return
                        }
                        state.comments[commentIndex].saves.push(data.save)
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
                            saveIndex = state.posts[postIndex]
                                .comments[commentIndex].saves.findIndex(save=>{
                                return save.id === data.sava.id
                            })
                            if (saveIndex > -1) {
                                return
                            }
                            state.posts[postIndex]
                                .comments[commentIndex].saves.push(data.save)
                        }
                    }
                    
                }
            } else if (data.item === 'post') {
                let postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    saveIndex = state.posts[postIndex].saves.findIndex(save=>{
                        return save.id === data.sava.id
                    })
                    if (saveIndex > -1) {
                        return
                    }
                    state.posts[postIndex].saves.unshift(data.save)
                }
            }
        
        },
        SAVE_DELETE_SUCCESS(state, data){
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('comment')) {
                    let commentIndex = state.comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })

                    if (commentIndex > -1) {
                        let saveIndex = state.comments[commentIndex].findIndex(save=>{
                            return save.id === data.saveId
                        })

                        if (saveIndex > -1) {
                            state.comments[commentIndex].saves.splice(saveIndex,1)
                            return
                        }
                    }
                } else if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null
                    let commentIndex = null
                    let saveIndex = null
                    
                    postIndex = state.posts.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.posts[postIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.posts[postIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                                return
                            }
                        }
                    }
                    
                }
            } else if (data.item === 'post') {
                let postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    let saveIndex = state.posts[postIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.posts[postIndex].saves.splice(saveIndex,1)
                    }
                }
            }
        },
        NEW_SAVE(state,data){

        },
        REMOVE_SAVE(state,data){

        },

        //////////////////////////////////////// likes
        
        LIKE_CREATE_SUCCESS(state, data){
            let likeIndex = null
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('comment')) {
                    let commentIndex = state.comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })

                    if (commentIndex > -1) {
                        likeIndex = state.comments[commentIndex].likes(like=>{
                            return like.id === data.like.id
                        })
                        if (likeIndex > -1) {
                            return
                        }
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
                            likeIndex = state.posts[postIndex].comments[commentIndex]
                                .likes.findIndex(like=>{
                                    return like.id = data.like.id
                                })
                            if (likeIndex > -1) {
                                return
                            }
                            state.posts[postIndex].comments[commentIndex]
                            .likes.push(data.like)
                        }
                    }
                    
                }
            } else if (data.item === 'post') {
                // console.log(data);
                let postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    likeIndex = state.posts[postIndex].likes.findIndex(like=>{
                        return like.id === data.like.id
                    })
                    if (likeIndex > -1) {
                        return
                    }
                    state.posts[postIndex].likes.push(data.like)
                }
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
        NEW_LIKE(state,like){
            let index = null,
                likeIndex = null,
                likeable = strings.getAccount(like.likeable_type)
            if (likeable === 'post') {
                index = state.posts.findIndex(post=>{
                    return post.id === like.likeable_id
                })
                if (index > -1) {
                    likeIndex = state.posts[index].likes.findIndex(l=>{
                        return l.id === like.id
                    })
                    if (likeIndex === -1) {
                        state.posts[index].likes.push(like)
                    }
                }
            } else if (likeable === 'discussion') {
                index = state.discussions.findIndex(discussion=>{
                    return discussion.id === like.likeable_id
                })
                if (index > -1) {
                    likeIndex = state.posts[index].likes.findIndex(l=>{
                        return l.id === like.id
                    })
                    if (likeIndex === -1) {
                        state.posts[index].likes.push(like)
                    }
                }
            } else if (likeable === 'comment') {
                index = state.comments.findIndex(comment=>{
                    return comment.id === like.likeable_id
                })
                if (index > -1) {
                    likeIndex = state.posts[index].likes.findIndex(l=>{
                        return l.id === like.id
                    })
                    if (likeIndex === -1) {
                        state.posts[index].likes.push(like)
                    }
                }
            }
        },
        REMOVE_LIKE(state,like){
            let index = null,
                likeIndex = null,
                likeable = strings.getAccount(like.likeable_type)
            if (likeable === 'post') {
                index = state.posts.findIndex(post=>{
                    return post.id === like.likeable_id
                })
                if (index > -1) {
                    likeIndex = state.posts[index].likes.findIndex(l=>{
                        return l.id === like.id
                    })
                    if (likeIndex > -1) {
                        state.posts[index].likes.splice(likeIndex,1)
                    }
                }
            } else if (likeable === 'discussion') {
                index = state.discussions.findIndex(discussion=>{
                    return discussion.id === like.likeable_id
                })
                if (index > -1) {
                    likeIndex = state.discussions[index].likes.findIndex(l=>{
                        return l.id === like.id
                    })
                    if (likeIndex > -1) {
                        state.discussions[index].likes.splice(likeIndex,1)
                    }
                }
            } else if (likeable === 'comment') {
                index = state.comments.findIndex(comment=>{
                    return comment.id === like.likeable_id
                })
                if (index > -1) {
                    likeIndex = state.comments[index].likes.findIndex(l=>{
                        return l.id === like.id
                    })
                    if (likeIndex > -1) {
                        state.comments[index].likes.splice(likeIndex,1)
                    }
                }
            }
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
        NEW_FLAG(state,flag){
            let index = null,
                flaggable = strings.getAccount(flag.flaggable_type)
            if (flaggable === 'post') {
                index = state.posts.findIndex(post=>{
                    return post.id === flag.flaggable_id
                })
                if (index > -1) {
                    state.posts.splice(index,1)
                }
            } else if (flaggable === 'discussion') {
                index = state.discussions.findIndex(discussion=>{
                    return discussion.id === flag.flaggable_id
                })
                if (index > -1) {
                    state.discussions.slice(index,1)
                }
            } else if (flaggable === 'comment') {
                index = state.comments.findIndex(comment=>{
                    return comment.id === flag.flaggable_id
                })
                if (index > -1) {
                    state.comments.slice(index,1)
                }
            }
        },

        //////////////////////////////////////// attachments
        
        ATTACHMENT_CREATE_SUCCESS(state, data){
            let attachmentIndex = null
            if (data.data.item === 'post') {
                let postIndex = state.posts.findIndex(post => {
                    return post.id === data.data.itemId
                })
                if (postIndex > -1) {
                    attachmentIndex = state.posts[postIndex]
                        .attachments.findIndex(attachment=>{
                            return attachment.id === data.attachment.id
                        })
                    if (attachmentIndex > -1) {
                        return 
                    }
                    state.posts[postIndex].attachments.push(data.attachment)
                }
            }
        },
        ATTACHMENT_DELETE_SUCCESS(state, data){
            if (data.item === 'post') {
                state.posts.forEach(post => {
                    if (post.attachments && post.id === data.itemId) {
                        let index = post.attachments.findIndex(attachment=>{
                            return attachment.id === data.attachmentId
                        })
                        if (index > -1) {
                            post.attachments.splice(index,1)
                            return
                        }
                    }
                })
            }
        },
        NEW_ATTACHMENT(state,attachment){
            let index = null,
                attachable = strings.getAccount(attachment.attachable_type)
            if (attachable === 'post') {
                index = state.posts.findIndex(post=>{
                    return post.id === attachment.attachable_id
                })
                if (index > -1) {
                    state.posts[index].attachments.push(attachment)
                }
            } else if (attachable === 'discussion') {
                index = state.discussions.findIndex(discussion=>{
                    return discussion.id === attachment.attachable_id
                })
                if (index > -1) {
                    state.discussions[index].attachments.push(attachment)
                }
            }
        },
        REMOVE_ATTACHMENT(state,attachment){
            let index = null,
                attachmentIndex = null,
                attachable = strings.getAccount(attachment.attachable_type)
            if (attachable === 'post') {
                index = state.posts.findIndex(post=>{
                    return post.id === attachment.attachable_id
                })
                if (index > -1) {
                    attachmentIndex = state.posts[index].attachments.findIndex(attach=>{
                        return attach.id === attachment.id
                    })
                    if (attachmentIndex > -1) {
                        state.posts[index].attachments.splice(attachmentIndex,1)
                    }
                }
            } else if (attachable === 'discussion') {
                index = state.discussions.findIndex(discussion=>{
                    return discussion.id === attachment.attachable_id
                })
                if (index > -1) {
                    attachmentIndex = state.discussions[index].attachments.findIndex(attach=>{
                        return attach.id === attachment.id
                    })
                    if (attachmentIndex > -1) {
                        state.discussions[index].attachments.splice(attachmentIndex,1)
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

        ///////////////////////////////////// follows
        async follow({commit}, data){
            console.log('data in profile',data)
            let response = await ProfileService.followCreate(data)

            if (response.data.message === 'successful') {
                commit('FOLLOW_SUCCESS',response.data)
                commit('FOLLOW_SUCCESS',response.data.following,{root:true})
                return {status: true, follow: response.data.follow}
            }else {
                commit('PROFILE_FAILURE','following was unsuccessul')
                return {status: false}
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
        setActiveProfile({commit,rootGetters},data){
            let profilesArray = [],
                computedArray = []

            if (data) {
                let {account, account_id} = data
                
                if (rootGetters.getProfiles && rootGetters.getProfiles.length) {
                    profilesArray = rootGetters.getProfiles
    
                    computedArray = profilesArray.filter(profile => {
                        return profile.params.accountId === account_id && 
                            profile.params.account === account
                    })
                    if (computedArray.length) {
                        commit('SET_ACTIVE_PROFILE',computedArray[0])
                        return
                    }
                }
            } else {
                if (rootGetters.getProfiles && rootGetters.getProfiles.length) {
                    commit('SET_ACTIVE_PROFILE',rootGetters.getProfiles[0])
                    return
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

        ////////////////////////////////////// chat

        async sendMessageResponse({commit},data){

            let response = await ProfileService.sendMessageResponse(data)

            if (response.data.message === 'successful') {
                commit('UPDATE_USER_FOLLOWS',{
                    type: 'response',
                    conversationId:response.data.conversation.id,
                    response: data.data.response,
                    mine: true
                }, {root:true})
                return {status: true, conversation: response.data.conversation}
            }else {
                return {status: false, message: response}
            }
        },
        async blockConversation({commit},data){

            let response = await ProfileService.blockConversation(data)

            if (response.data.message === 'successful') {
                commit('UPDATE_USER_FOLLOWS',{
                    type: 'response',
                    conversationId:response.data.conversation.id,
                    response: 'BLOCK',
                    mine: true
                }, {root:true})
                return {status: true, conversation: response.data.conversation}
            }else {
                return {status: false, message: response}
            }
        },
        async unblockConversation({commit},data){

            let response = await ProfileService.unblockConversation(data)

            if (response.data.message === 'successful') {
                commit('UPDATE_USER_FOLLOWS',{
                    type: 'response',
                    conversationId:response.data.conversation.id,
                    response: 'ACCEPT',
                    mine: true
                }, {root:true})
                return {status: true, conversation: response.data.conversation}
            }else {
                return {status: false, message: response}
            }
        },
        async sendChatMessage({commit},data){

            let response = await ProfileService.sendChatMessage(data)

            if (response.data.message === 'successful') {
                return {status: true, chatMessage: response.data.chatMessage}
            }else {
                return {status: false, message: response}
            }
        },
        async getChatMessages({commit},data){

            let response = await ProfileService.getChatMessages(data)

            if (response.data.data) {
                return {
                    status: true, 
                    data: response.data.data,
                    next: response.data.links.next
                }
            }else {
                return {status: false, message: response}
            }
        },
        async getChatConversations({commit},data){

            let response = await ProfileService.getChatConversations(data)

            if (response.data.data) {
                return {
                    status: true, 
                    data: response.data.data,
                    next: response.data.links.next
                }
            }else {
                return {status: false, message: response}
            }
        },
        async getBlockedConversations({commit},data){

            let response = await ProfileService.getBlockedConversations(data)

            if (response.data.data) {
                return {
                    status: true, 
                    data: response.data.data,
                    next: response.data.links.next
                }
            }else {
                return {status: false, message: response}
            }
        },
        async getPendingConversations({commit},data){

            let response = await ProfileService.getPendingConversations(data)

            if (response.data.data) {
                return {
                    status: true, 
                    data: response.data.data,
                    next: response.data.links.next
                }
            }else {
                return {status: false, message: response}
            }
        },
        async createConversation({commit},data){

            let response = await ProfileService.createConversation(data)

            if (response.data.message === 'successful') {
                commit('UPDATE_USER_FOLLOWS',{
                    type: 'conversation',
                    conversation:response.data.conversation
                }, {root:true})
                return {
                    status: true, 
                    conversation: response.data.conversation
                }
            }else {
                return {status: false, message: response}
            }
        },

        ////////////////////////////////////// saves

        async createSave({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.saveCreate(data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                data['save'] = response.data.save
                commit('SAVE_CREATE_SUCCESS', data)
                commit('home/SAVE_CREATE_SUCCESS', data,{root: true})
                return {status: true, save: response.data.save}
            }else {
                commit('PROFILE_FAILURE','saving unsuccessful')
                return {status: false, message: 'unsuccessful'}
            }
        },
        async deleteSave({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.saveDelete(data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                commit('SAVE_DELETE_SUCCESS', data)
                commit('home/SAVE_DELETE_SUCCESS', data, {root: true})
                return {status: true, message: 'successful'}
            }else {
                commit('PROFILE_FAILURE','liking unsuccessful')
                return {status: false, message: 'unsuccessful'}
            }
        },

        ////////////////////////////////////// likes

        async createLike({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.likeCreate(data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                data['like'] = response.data.like
                commit('LIKE_CREATE_SUCCESS', data)
                commit('home/LIKE_CREATE_SUCCESS', data, {root: true})
                return response.data.like
            }else {
                commit('PROFILE_FAILURE','liking unsuccessful')
                return 'unsuccessful'
            }
        },
        async deleteLike({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.likeDelete(data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
                commit('LIKE_DELETE_SUCCESS', data)
                commit('home/LIKE_DELETE_SUCCESS', data, {root: true})
                return data
            }else {
                commit('PROFILE_FAILURE','liking unsuccessful')
                return 'unsuccessful'
            }
        },
        newLike({commit}, like){
            commit('NEW_LIKE', like)
        },
        removeLike({commit}, likeInfo){
            commit('REMOVE_LIKE', likeInfo)
        },

        ////////////////////////////////////// flags

        async createFlag({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.flagCreate(data)

            commit('LIKING_END')
            if (response.data.message === 'successful') {
                data['flag'] = response.data.flag
                commit('FLAG_CREATE_SUCCESS', {data,flag: response.data.flag})
                commit('home/FLAG_CREATE_SUCCESS', {data,flag: response.data.flag}, {root: true})
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
                commit('home/FLAG_DELETE_SUCCESS', data, {root: true})
                return {data,status: true}
            }else {
                commit('PROFILE_FAILURE','unflagging unsuccessful')
                return {status: false, message:'unsuccessful'}
            }
        },
        newFlag({commit}, flag){
            commit('NEW_FLAG', flag)
        },

        ////////////////////////////////////// attachments

        async createAttachment({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.attachmentCreate(data)

            commit('LIKING_END')
            if (response.data.message === 'successful') {
                data['attachment'] = response.data.attachment
                commit('ATTACHMENT_CREATE_SUCCESS', {
                    data,
                    attachment: response.data.attachment
                })
                commit('home/ATTACHMENT_CREATE_SUCCESS', {
                    data,
                    attachment: response.data.attachment
                }, {root: true})
                return {status: true,attachment: response.data.attachment}
            }else {
                commit('PROFILE_FAILURE','attaching unsuccessful')
                return {status: false, message:'unsuccessful'}
            }
        },
        async deleteAttachment({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.attachmentDelete(data)

            commit('LIKING_END')
            if (response.data.message === 'successful') {
                commit('ATTACHMENT_DELETE_SUCCESS', data)
                commit('home/ATTACHMENT_DELETE_SUCCESS', data, {root: true})
                return {data,status: true}
            }else {
                commit('PROFILE_FAILURE','unattaching unsuccessful')
                return {status: false, message:'unsuccessful'}
            }
        },
        newAttachment({commit}, attachment){
            commit('NEW_ATTACHMENT', attachment)
        },
        removeAttachment({commit}, attachmentInfo){
            commit('REMOVE_ATTACHMENT', attachmentInfo)
        },

        ////////////////////////////////////// marks

        async createMark({commit},data){
            commit('LIKING_START')
            let response = await ProfileService.markCreate(data)
            commit('LIKING_END')
            if (response.data.message === 'successful') {
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
            commit('COMMENTING_END')
            if (response.data.message === 'successful') {
                commit('home/COMMENT_SUCCESS',response.data, {root: true})
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
            if (response.data.message === 'successful') {
                commit('COMMENT_DELETE_SUCCESS',data)
                commit('home/COMMENT_DELETE_SUCCESS',data, {root: true})
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
            if (response.data.message === 'successful') {
                commit('COMMENT_UPDATE_SUCCESS',response.data)
                commit('home/COMMENT_UPDATE_SUCCESS',response.data, {root: true})
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
                        data: response.data,
                        currentPage: response.data.meta.current_page
                    }
            }else {
                commit('PROFILE_FAILURE','commenting was unsuccessful')
                return 'unsuccessful'
            }
        },

        clearComments({commit}){
            commit('COMMENTS_CLEAR')
        },

        newComment({commit}, comment){
            commit('NEW_COMMENT', comment)
        },
        replaceComment({commit}, comment){
            commit('REPLACE_COMMENT', comment)
        },
        removeComment({commit}, commentInfo){
            commit('REMOVE_COMMENT', commentInfo)
        },

        ///////////////////////////////////////// grades
        
        async createGrade({commit},mainData){

            let response = await ProfileService.gradeCreate(mainData)

            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of grade unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async createGradeAlias({commit},mainData){

            let response = await ProfileService.gradeAliasCreate(mainData)
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of grade alias unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async getGrades({commit}){

            let response = await ProfileService.gradesGet()
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of grade unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async searchGrades({commit},data){

            let response = await ProfileService.gradesSearch(data)
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of grade unsuccessful')
                return {status: false, message: response.data.message}
            }
        },

        ///////////////////////////////////////// programs
        
        async createProgram({commit},mainData){

            let response = await ProfileService.programCreate(mainData)

            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of program unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async createProgramAlias({commit},mainData){

            let response = await ProfileService.programAliasCreate(mainData)
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of program alias unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async getPrograms({commit}){

            let response = await ProfileService.programsGet()
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of program unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async searchPrograms({commit},data){

            let response = await ProfileService.programsSearch(data)
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of program unsuccessful')
                return {status: false, message: response.data.message}
            }
        },

        ///////////////////////////////////////// courses
        
        async createCourse({commit},mainData){

            let response = await ProfileService.courseCreate(mainData)

            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of course unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async createCourseAlias({commit},mainData){

            let response = await ProfileService.courseAliasCreate(mainData)
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of course alias unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async getCourses({commit}){

            let response = await ProfileService.coursesGet()
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of course unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async searchCourses({commit},data){

            let response = await ProfileService.coursesSearch(data)
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of course unsuccessful')
                return {status: false, message: response.data.message}
            }
        },

        ///////////////////////////////////////// subject
        
        async createSubject({commit},mainData){

            let response = await ProfileService.subjectCreate(mainData)

            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of subject unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async createSubjectAlias({commit},mainData){

            let response = await ProfileService.subjectAliasCreate(mainData)
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of subject alias unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async getSubjects({commit}){

            let response = await ProfileService.subjectsGet()
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of subject unsuccessful')
                return {status: false, message: response.data.message}
            }
        },
        async searchSubjects({commit},data){

            let response = await ProfileService.subjectsSearch(data)
            
            if (response.data.message === 'successful') {

                return {status: true, data: response.data}
            }else {
                commit('PROFILE_FAILURE','creation of subject unsuccessful')
                return {status: false, message: response.data.message}
            }
        },

        ///////////////////////////////////////// answers
        
        async createAnswer({commit},mainData){
            commit('COMMENTING_START')
            let response = await ProfileService.answerCreate(mainData)
            // console.log('comment data', data)
            commit('COMMENTING_END')
            if (response.data.message === 'successful') {
                commit('ANSWER_CREATE_SUCCESS',{
                    data: mainData.data, response: response.data})
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
            if (response.data.message === 'successful') {
                commit('POST_CREATE_SUCCESS',response.data)
                commit('home/POST_CREATE_SUCCESS',response.data, {root: true})
                return 'success'
            }else {
                commit('PROFILE_FAILURE','post unsuccessful')
                return 'unsuccessful'
            }
        },
        async getPost({commit},data){
            let response = await ProfileService.postGet(data)

            if (response.data.message === 'successful') {
                return response.data.post
            }else {
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
                commit('home/POST_UPDATE_SUCCESS',response.data, {root: true})
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','post update unsuccessful')
                return 'unsuccessful'
            }
        },
        newPost({commit}, post){
            commit('NEW_POST', post)
        },
        replacePost({commit}, post){
            commit('REPLACE_POST', post)
        },
        removePost({commit}, postInfo){
            commit('REMOVE_POST', postInfo)
        },
        async deletePost({commit},data){
            commit('POST_START')
            let response = await ProfileService.postDelete(data)

            commit('POST_END')
            if (response.data.message === 'successful') {
                commit('POST_DELETE_SUCCESS',data)
                commit('home/POST_DELETE_SUCCESS',data, {root: true})
                return 'successful'
            }else {
                commit('PROFILE_FAILURE','post deletion unsuccessful')
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
                // console.log('next',response.data.links.next)
                if (response.data.hasOwnProperty('links')) {
                    
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