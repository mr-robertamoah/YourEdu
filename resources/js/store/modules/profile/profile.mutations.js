const mutations = {
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

    /////////////////////////////////////////////////////////// discussions
    DISCUSSION_CREATE_SUCCESS(state,data){
        state.message = 'discussion successful'
        state.posts.unshift(data.discussion)
    },
    DISCUSSION_UPDATE_SUCCESS(state,data){
        let index = state.posts.findIndex(el=>{
            return el.id === data.discussion.id && el.isDiscussion
        })

        if (index >= 0) {
            state.posts.splice(index, 1, data.discussion)
        }
    },
    DISCUSSION_DELETE_SUCCESS(state,discussionId){
        let index = state.posts.findIndex(el=>{
            return el.id == discussionId && el.isDiscussion
        })

        if (index >= 0) {
            state.posts.splice(index, 1)
            state.message = 'successfully deleted post.'
        }
    },
    CREATE_DISCUSSION_PARTICIPANT(state,data){
        let index = state.posts.findIndex(item=>{
            return item.id === data.discussionId && item.isDiscussion
        })
        if (index > -1) {
            state.posts[index].participants.push(data.discussionParticipant)
        }
    },
    CREATE_PENDING_DISCUSSION_PARTICIPANT(state,data){
        let index = state.posts.findIndex(item=>{
            return item.id === data.discussionId && item.isDiscussion
        })
        if (index > -1) {
            state.posts[index].pendingJoinParticipants.push(data.pendingParticipant)
        }
    },
    NEW_DISCUSSION(state,discussion){ //from websocket to profile
        state.posts.unshift(discussion)
    },
    REPLACE_DISCUSSION(state,discussion){
        let index = state.posts.findIndex(oldPost=>{
            return oldPost.id === discussion.id && oldPost.isDiscussion
        })
        if (index > -1) {
            state.posts.splice(index,1,discussion)
        }
    },
    REMOVE_DISCUSSION(state,info){
        let postIndex
        postIndex = state.posts.findIndex(discussion=>{
            return discussion.id == info.discussionId && 
                discussion.isDiscussion
        })
        if (postIndex > -1) {
            state.posts.splice(postIndex,1)
        }
    },
    NEW_DISCUSSION_PARTICIPANT(state,data){
        let index = state.posts.findIndex(post=>{
            return post.id == data.discussionId && post.isDiscussion
        })
        if (index > -1) {
            state.posts[index].participants.push(data.discussionParticipant)
        }
    },
    UPDATE_DISCUSSION_PARTICIPANT(state,data){
        let index,
            participantIndex
        index = state.posts.findIndex(post=>{
            return post.id == data.discussionId && post.isDiscussion
        })
        if (index > -1) {
            participantIndex = state.posts[index].participants.findIndex(participant=>{
                return participant.userId === data.discussionParticipant.userId
            })
            if (participantIndex > -1) {
                state.posts[index].participants.
                    splice(participantIndex,1,data.discussionParticipant)
            }
        }
    },
    REMOVE_DISCUSSION_PARTICIPANT(state,data){
        let index,
            participantIndex
        index = state.posts.findIndex(post=>{
            return post.id == data.discussionId && post.isDiscussion
        })
        if (index > -1) {
            participantIndex = state.posts[index].participants.findIndex(participant=>{
                return participant.userId == data.userId
            })
            if (participantIndex > -1) {
                state.posts[index].participants.
                    splice(participantIndex,1)
            }
        }
    },
    NEW_DISCUSSION_PENDING_PARTICIPANT(state,data){
        let index = state.posts.findIndex(post=>{
            return post.id == data.discussionId && post.isDiscussion
        })
        if (index > -1) {
            state.posts[index].pendingJoinParticipants.push(data.pendingParticipant)
        }
    },
    REMOVE_DISCUSSION_PENDING_PARTICIPANT(state,data){
        let index,
            pendingIndex
        index = state.posts.findIndex(post=>{
            return post.id == data.discussionId && post.isDiscussion
        })
        if (index > -1) {
            pendingIndex = state.posts[index].pendingJoinParticipants.findIndex(p=>{
                return p.userId === data.pendingParticipant.userId
            })
            if (pendingIndex > -1) {
                state.posts[index].pendingJoinParticipants.splice(pendingIndex,1)
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
            return el.id === data.post.id && el.isPost
        })

        if (index >= 0) {
            state.posts.splice(index, 1, data.post)
        }
    },
    POST_DELETE_SUCCESS(state,data){
        let posts = state.posts
        let index = null

        index = posts.findIndex(el=>{
            return el.id === data.postId && el.isPost
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
        // let oldPost = state.posts[0]
        // if (oldPost.isDiscussion &&
        //     oldPost.raisedby_id === post.addedby_id &&
        //     oldPost.raisedby_type === post.addedby_type) {
        //     state.posts.unshift(post)
        //     return
        // }
        // if (oldPost.isPost &&
        //     oldPost.addedby_id === post.addedby_id &&
        //     oldPost.addedby_type === post.addedby_type) {
        //     }
        state.posts.unshift(post)
    },
    REPLACE_POST(state,post){
        let postIndex = state.posts.findIndex(oldPost=>{
            return oldPost.id === post.id && oldPost.isPost
        })
        if (postIndex > -1) {
            state.posts.splice(postIndex,1,post)
        }
    },
    REMOVE_POST(state,postInfo){
        let postIndex = null,
            postId = Number(postInfo.postId)
        postIndex = state.posts.findIndex(p=>{
            return p.id === postId && p.isPost
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
                return post.id === data.comment.commentable_id && post.isPost
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
        } else if (data.comment.commentable_type.toLocaleLowerCase().includes('discussion')) {
            let postIndex = state.posts.findIndex(post=>{
                return post.id === data.comment.commentable_id && post.isDiscussion
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
        let postIndex,
            commentIndex
        if (data.owner.toLocaleLowerCase().includes('post')) {
            postIndex = state.posts.findIndex(post=>{
                return post.id === data.ownerId && post.isPost
            })
        } else if (data.owner.toLocaleLowerCase().includes('discussion')) {
            postIndex = state.posts.findIndex(post=>{
                return post.id === data.ownerId && post.isDiscussion
            })
        }
        if (postIndex > -1) {
            commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                return comment.id === data.commentId
            })
            if (commentIndex > -1) {
                state.posts[postIndex].comments.splice(commentIndex,1)
            }
        } else if (data.owner.toLocaleLowerCase().includes('comment')) {
            commentIndex = state.comments.findIndex(comment=>{
                return comment.id === data.commentId
            })
            if (commentIndex > -1) {
                state.comments.splice(commentIndex,1)
            }
        }
    },
    COMMENT_SUCCESS(state, data){
        if (data.comment.commentable_type.toLocaleLowerCase().includes('post')) {
            let postIndex = state.posts.findIndex(post=>{
                return post.id === data.comment.commentable_id && post.isPost
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
        } else if (data.comment.commentable_type.toLocaleLowerCase().includes('discussion')) {
            let postIndex = state.posts.findIndex(post=>{
                return post.id === data.comment.commentable_id && post.isDiscussion
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
        let itemId = Number(data.itemId),
            postIndex = null
        if (data.item === 'post') {
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId && post.isPost
            })
        } else if (data.item === 'discussion') {
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId && post.isDiscussion
            })
        }
        if (postIndex > -1) {
            state.posts[postIndex].comments.unshift(data.comment)
        }
    },
    REPLACE_COMMENT(state, data){
        let itemId = Number(data.itemId),
            postIndex = null,
            commentIndex = null
        if (data.item === 'post') {
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId && post.isPost
            })
        } else if (data.item === 'discussion') {
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId && post.isDiscussion
            })
        }
        if (postIndex > -1) {
            commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                return comment.id === data.comment.id
            })
            if (commentIndex > -1) {
                state.posts[postIndex].comments.splice(commentIndex,1,data.comment)
            }
        }
    },
    REMOVE_COMMENT(state, data){
        let itemId = Number(data.itemId),
            commentId = Number(data.commentId),
            postIndex = null,
            commentIndex = null
        if (data.item === 'post') {
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId && post.isPost
            })
        } else if (data.item === 'discussion') {
            postIndex = state.posts.findIndex(post=>{
                return post.id === itemId && post.isDiscussion
            })
        }
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
        let saveIndex = null,
            postIndex = null,
            commentIndex = null
        if (data.item === 'comment') {
            if (data.owner.toLocaleLowerCase().includes('comment')) {
                commentIndex = state.comments.findIndex(comment=>{
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
                postIndex = state.posts.findIndex(post=>{
                    return post.id === data.ownerId && post.isPost
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
            } else if (data.owner.toLocaleLowerCase().includes('discussion')) {                    
                postIndex = state.posts.findIndex(post=>{
                    return post.id === data.ownerId && post.isDiscussion
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
        let postIndex = null,
            commentIndex = null,
            saveIndex = null
        if (data.item === 'comment') {
            if (data.owner.toLocaleLowerCase().includes('comment')) {
                commentIndex = state.comments.findIndex(comment=>{
                    return comment.id === data.itemId
                })

                if (commentIndex > -1) {
                    saveIndex = state.comments[commentIndex].findIndex(save=>{
                        return save.id === data.saveId
                    })

                    if (saveIndex > -1) {
                        state.comments[commentIndex].saves.splice(saveIndex,1)
                        return
                    }
                }
            } else if (data.owner.toLocaleLowerCase().includes('post')) {                    
                postIndex = state.posts.findIndex(post=>{
                    return post.id === data.ownerId && post.isPost
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
            } else if (data.owner.toLocaleLowerCase().includes('discussion')) {                    
                postIndex = state.posts.findIndex(post=>{
                    return post.id === data.ownerId && post.isDiscussion
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
                return post.id === data.itemId && post.isPost
            })
            if (postIndex > -1) {
                let saveIndex = state.posts[postIndex].saves.findIndex(save=>{
                    return save.id === data.saveId
                })
                if (saveIndex > -1) {
                    state.posts[postIndex].saves.splice(saveIndex,1)
                }
            }
        } else if (data.item === 'discussion') {
            let postIndex = state.posts.findIndex(post => {
                return post.id === data.itemId && post.isDiscussion
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
                    return post.id === data.ownerId && post.isPost
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
            } else if (data.owner.toLocaleLowerCase().includes('discussion')) {
                let index = null
                let commentIndex = null
                
                index = state.posts.findIndex(post=>{
                    return post.id === data.ownerId && post.isDiscussion
                })
                if (index > -1) {
                    commentIndex = state.posts[index].comments.findIndex(comment=>{
                        return comment.id === data.itemId
                    })

                    if (commentIndex > -1) {
                        likeIndex = state.posts[index].comments[commentIndex]
                            .likes.findIndex(like=>{
                                return like.id = data.like.id
                            })
                        if (likeIndex > -1) {
                            return
                        }
                        state.posts[index].comments[commentIndex]
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
        } else if (data.item === 'post') {
            console.log('data :>> ', data);
            let index = state.posts.findIndex(post => {
                return post.id === data.itemId && post.isDiscussion
            })
            if (index > -1) {
                likeIndex = state.posts[index].likes.findIndex(like=>{
                    return like.id === data.like.id
                })
                if (likeIndex > -1) {
                    return
                }
                state.posts[index].likes.push(data.like)
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
                    let likeIndex = state.comments[commentIndex].likes.findIndex(like=>{
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
                    return post.id === data.ownerId && post.isPost
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
            } else if (data.owner.toLocaleLowerCase().includes('discussion')) {
                let postIndex = null
                let commentIndex = null
                let likeIndex = null
                
                postIndex = state.posts.findIndex(post=>{
                    return post.id === data.ownerId
                })
                if (postIndex > -1) {
                    commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.itemId && post.isDiscussion
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
                if (post.likes && post.id === data.itemId && post.isPost) {
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
        } else if (data.item === 'discussion') {
            state.posts.forEach(post => {
                if (post.likes && post.id === data.itemId && post.isDiscussion) {
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
    NEW_LIKE(state,data){
        let index = null,
            likeIndex = null,
            itemId = Number(data.itemId)
        if (data.item === 'post') {
            index = state.posts.findIndex(post=>{
                return post.id === itemId && post.isPost
            })
            if (index > -1) {
                likeIndex = state.posts[index].likes.findIndex(l=>{
                    return l.id === data.like.id
                })
                if (likeIndex === -1) {
                    state.posts[index].likes.push(data.like)
                }
            }
        } else if (data.item === 'discussion') {
            index = state.posts.findIndex(discussion=>{
                return discussion.id === itemId && discussion.isDiscussion
            })
            if (index > -1) {
                likeIndex = state.posts[index].likes.findIndex(l=>{
                    return l.id === data.like.id
                })
                if (likeIndex === -1) {
                    state.posts[index].likes.push(data.like)
                }
            }
        } else if (data.item === 'comment') {
            index = state.comments.findIndex(comment=>{
                return comment.id === itemId
            })
            if (index > -1) {
                likeIndex = state.posts[index].likes.findIndex(l=>{
                    return l.id === data.like.id
                })
                if (likeIndex === -1) {
                    state.posts[index].likes.push(data.like)
                }
            }
        }
    },
    REMOVE_LIKE(state,data){
        let index = null,
            likeIndex = null,
            itemId = Number(data.itemId),
            likeId = Number(data.likeId)
        if (data.item === 'post') {
            index = state.posts.findIndex(post=>{
                return post.id === itemId && post.isPost
            })
            if (index > -1) {
                likeIndex = state.posts[index].likes.findIndex(l=>{
                    return l.id === likeId
                })
                if (likeIndex > -1) {
                    state.posts[index].likes.splice(likeIndex,1)
                }
            }
        } else if (data.item === 'discussion') {
            index = state.posts.findIndex(discussion=>{
                return discussion.id === itemId && discussion.isDiscussion
            })
            if (index > -1) {
                likeIndex = state.posts[index].likes.findIndex(l=>{
                    return l.id === likeId
                })
                if (likeIndex > -1) {
                    state.posts[index].likes.splice(likeIndex,1)
                }
            }
        } else if (data.item === 'comment') {
            index = state.comments.findIndex(comment=>{
                return comment.id === itemId
            })
            if (index > -1) {
                likeIndex = state.comments[index].likes.findIndex(l=>{
                    return l.id === likeId
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
                return post.id === data.data.itemId && post.isPost //itemId is the post id
            })
            if (postIndex > -1) {
                state.posts.splice(postIndex,1)
            }
        } else if (data.data.hasOwnProperty('discussion') && data.data.discussion) {
            let postIndex = state.posts.findIndex(post=>{
                return post.id === data.data.itemId && post.isDiscussion //itemId is the post id
            })
            if (postIndex > -1) {
                state.posts.splice(postIndex,1)
            }
        } else if (data.data.hasOwnProperty('comment') && data.data.comment) {
            if (strings.getAccount(data.data.commentable_type) === 'post') {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.data.commentable_id && post.isPost //commentable_id is the post id
                })
                if (postIndex > -1) {
                    let commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.data.itemId
                    })
                    if (commentIndex > -1) {
                        state.posts[postIndex].comments.splice(commentIndex,1)
                    }
                }
            } else if (strings.getAccount(data.data.commentable_type) === 'discussion') {
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.data.commentable_id && post.isDiscussion //commentable_id is the post id
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
                return post.id === data.itemId && post.isPost //itemId is the post id
            })
            if (postIndex > -1) {
                let flagIndex = state.posts[postIndex].flags.findIndex(flag=>{
                    return flag.id === data.flagId
                })
                if (flagIndex > -1) {
                    state.posts[postIndex].flags.splice(flagIndex,1)
                }
            }
        } else if (data.hasOwnProperty('discussion') && data.discussion) {
            let postIndex = state.posts.findIndex(post=>{
                return post.id === data.itemId && post.isDiscussion
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
            let postIndex
            if (strings.getAccount(data.comment.commentable_type) === 'post') {
                postIndex = state.posts.findIndex(post=>{
                    return post.id === data.commentable_id && post.isPost//itemId is the post id
                })
            } else if (strings.getAccount(data.comment.commentable_type) === 'discussion') {
                postIndex = state.posts.findIndex(post=>{
                    return post.id === data.commentable_id && post.isDiscussion
                })
            }
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
                return post.id === flag.flaggable_id && post.isPost
            })
            if (index > -1) {
                state.posts.splice(index,1)
            }
        } else if (flaggable === 'discussion') {
            index = state.posts.findIndex(discussion=>{
                return discussion.id === flag.flaggable_id && discussion.isDiscussion
            })
            if (index > -1) {
                state.posts.slice(index,1)
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
        let attachmentIndex = null,
            postIndex
        if (data.data.item === 'post') {
            postIndex = state.posts.findIndex(post => {
                return post.id === data.data.itemId && post.isPost
            })
        } else if (data.data.item === 'discussion') {
            postIndex = state.posts.findIndex(post => {
                return post.id === data.data.itemId && post.isDiscussion
            })
        }
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
    },
    ATTACHMENT_DELETE_SUCCESS(state, data){
        let index
        if (data.item === 'post') {
            state.posts.forEach(post => {
                if (post.attachments && post.id === data.itemId &&
                        post.isPost) {
                    index = post.attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (index > -1) {
                        post.attachments.splice(index,1)
                        return
                    }
                }
            })
        } else if (data.item === 'discussion') {
            state.posts.forEach(post => {
                if (post.attachments && post.id === data.itemId &&
                        post.isDiscussion) {
                    index = post.attachments.findIndex(attachment=>{
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
                return post.id === attachment.attachable_id && post.isPost
            })
            if (index > -1) {
                state.posts[index].attachments.push(attachment)
            }
        } else if (attachable === 'discussion') {
            index = state.posts.findIndex(discussion=>{
                return discussion.id === attachment.attachable_id && discussion.isDiscussion
            })
            if (index > -1) {
                state.posts[index].attachments.push(attachment)
            }
        }
    },
    REMOVE_ATTACHMENT(state,attachment){
        let index = null,
            attachmentIndex = null,
            attachable = strings.getAccount(attachment.attachable_type)
        if (attachable === 'post') {
            index = state.posts.findIndex(post=>{
                return post.id === attachment.attachable_id && post.isPost
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
            index = state.posts.findIndex(discussion=>{
                return discussion.id === attachment.attachable_id && discussion.isDiscussion
            })
            if (index > -1) {
                attachmentIndex = state.posts[index].attachments.findIndex(attach=>{
                    return attach.id === attachment.id
                })
                if (attachmentIndex > -1) {
                    state.posts[index].attachments.splice(attachmentIndex,1)
                }
            }
        }
    },

    ////////////////////////////////////////
    SET_ACTIVE_PROFILE(state, data){
        state.activeProfile = data
    },
}

export default mutations