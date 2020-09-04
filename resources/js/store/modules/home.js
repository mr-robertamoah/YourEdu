import {HomeService} from "../../services/home.service";

const home = {
    namespaced: true,
    state: () => ({
        posts: [],
        postsMine: [],
        postsFollowers: [],
        postsFollowings: [],
        postsAttachments: [],
        discussions: [],
        discussionsMine: [],
        discussionsFollowers: [],
        discussionsFollowings: [],
        discussionsAttachments: [],
        riddles: [],
        riddlesMine: [],
        riddlesFollowers: [],
        riddlesFollowings: [],
        riddlesAttachments: [],
        reads: [],
        readsMine: [],
        readsFollowers: [],
        readsFollowings: [],
        readsAttachments: [],
        questions: [],
        questionsMine: [],
        questionsFollowers: [],
        questionsFollowings: [],
        questionsAttachments: [],
        activities: [],
        activitiesMine: [],
        activitiesFollowers: [],
        activitiesFollowings: [],
        activitiesAttachments: [],
        poems: [],
        poemsMine: [],
        poemsFollowers: [],
        poemsFollowings: [],
        poemsAttachments: [],
        books: [],
        booksMine: [],
        booksFollowers: [],
        booksFollowings: [],
        booksAttachments: [],
        loading: false,
    }),

    mutations: {
        
        //////////////////////////////////////////////////////////
        LOADING_START(state){
            state.loading = true
        },
        LOADING_END(state){
            state.loading = false
        },

        ////////////////////////////////////////////////////posts
        POST_CREATE_SUCCESS(state, data){
            state.posts.unshift(data.post)
            state.postsMine.unshift(data.post)

            if (data.post.typeName === 'question') {
                state.questions.unshift(data.post)
                state.questionsMine.unshift(data.post)
            }
            if (data.post.typeName === 'poem') {
                state.poems.unshift(data.post)
                state.poemsMine.unshift(data.post)
            }
            if (data.post.typeName === 'riddle') {
                state.riddles.unshift(data.post)
                state.riddlesMine.unshift(data.post)
            }
            if (data.post.typeName === 'activity') {
                state.activities.unshift(data.post)
                state.activitiesMine.unshift(data.post)
            }
            if (data.post.typeName === 'book') {
                state.books.unshift(data.post)
                state.booksMine.unshift(data.post)
            }
        },
        POST_UPDATE_SUCCESS(state, data){
            let index = null
            //posts
            index = state.posts.findIndex(post=>{
                return post.id === data.post.id
            })
            if (index > -1) {
                state.posts.splice(index, 1, data.post)
            }
            index = state.postsMine.findIndex(post=>{
                return post.id === data.post.id
            })
            if (index > -1) {
                state.postsMine.splice(index, 1, data.post)
            }
            index = state.postsAttachments.findIndex(post=>{
                return post.id === data.post.id
            })
            if (index > -1) {
                state.postsAttachments.splice(index, 1, data.post)
            }
            //questions
            if (data.post.typeName === 'question') {
                index = state.questions.findIndex(question=>{
                    return question.id === data.post.id
                })
                if (index > -1) {
                    state.questions.splice(index, 1, data.post)
                }
                index = state.questionsMine.findIndex(question=>{
                    return question.id === data.post.id
                })
                if (index > -1) {
                    state.questionsMine.splice(index, 1, data.post)
                }
                index = state.questionsAttachments.findIndex(question=>{
                    return question.id === data.post.id
                })
                if (index > -1) {
                    state.questionsAttachments.splice(index, 1, data.post)
                }
            }
            //poems
            if (data.post.typeName === 'poem') {
                index = state.poems.findIndex(poem=>{
                    return poem.id === data.post.id
                })
                if (index > -1) {
                    state.poems.splice(index, 1, data.post)
                }
                index = state.poemsMine.findIndex(poem=>{
                    return poem.id === data.post.id
                })
                if (index > -1) {
                    state.poemsMine.splice(index, 1, data.post)
                }
                index = state.poemsAttachments.findIndex(poem=>{
                    return poem.id === data.post.id
                })
                if (index > -1) {
                    state.poemsAttachments.splice(index, 1, data.post)
                }
            }
            //books
            if (data.post.typeName === 'book') {
                index = state.books.findIndex(book=>{
                    return book.id === data.post.id
                })
                if (index > -1) {
                    state.books.splice(index, 1, data.post)
                }
                index = state.booksMine.findIndex(book=>{
                    return book.id === data.post.id
                })
                if (index > -1) {
                    state.booksMine.splice(index, 1, data.post)
                }
                index = state.booksAttachments.findIndex(book=>{
                    return book.id === data.post.id
                })
                if (index > -1) {
                    state.booksAttachments.splice(index, 1, data.post)
                }
            }
            //riddles
            if (data.post.typeName === 'riddle') {
                index = state.riddles.findIndex(riddle=>{
                    return riddle.id === data.post.id
                })
                if (index > -1) {
                    state.riddles.splice(index, 1, data.post)
                }
                index = state.riddlesMine.findIndex(riddle=>{
                    return riddle.id === data.post.id
                })
                if (index > -1) {
                    state.riddlesMine.splice(index, 1, data.post)
                }
                index = state.riddlesAttachments.findIndex(riddle=>{
                    return riddle.id === data.post.id
                })
                if (index > -1) {
                    state.riddlesAttachments.splice(index, 1, data.post)
                }
            }
            //activities
            if (data.post.typeName === 'activitie') {
                index = state.activities.findIndex(activity=>{
                    return activity.id === data.post.id
                })
                if (index > -1) {
                    state.activities.splice(index, 1, data.post)
                }
                index = state.activitiesMine.findIndex(activity=>{
                    return activity.id === data.post.id
                })
                if (index > -1) {
                    state.activitiesMine.splice(index, 1, data.post)
                }
                index = state.activitiesAttachments.findIndex(activity=>{
                    return activity.id === data.post.id
                })
                if (index > -1) {
                    state.activitiesAttachments.splice(index, 1, data.post)
                }
            }
        },
        POST_DELETE_SUCCESS(state, data){
            let index = null
            //posts
            index = state.posts.findIndex(post=>{
                return post.id === data.postId
            })
            if (index > -1) {
                state.posts.splice(index, 1)
            }
            index = state.postsMine.findIndex(post=>{
                return post.id === data.postId
            })
            if (index > -1) {
                state.postsMine.splice(index, 1)
            }
            index = state.postsAttachments.findIndex(post=>{
                return post.id === data.postId
            })
            if (index > -1) {
                state.postsAttachments.splice(index, 1)
            }
            //poems
            index = state.poems.findIndex(poem=>{
                return poem.id === data.postId
            })
            if (index > -1) {
                state.poems.splice(index, 1)
            }
            index = state.poemsMine.findIndex(poem=>{
                return poem.id === data.postId
            })
            if (index > -1) {
                state.poemsMine.splice(index, 1)
            }
            index = state.poemsAttachments.findIndex(poem=>{
                return poem.id === data.postId
            })
            if (index > -1) {
                state.poemsAttachments.splice(index, 1)
            }
            //questions
            index = state.questions.findIndex(question=>{
                return question.id === data.postId
            })
            if (index > -1) {
                state.questions.splice(index, 1)
            }
            index = state.questionsMine.findIndex(question=>{
                return question.id === data.postId
            })
            if (index > -1) {
                state.questionsMine.splice(index, 1)
            }
            index = state.questionsAttachments.findIndex(question=>{
                return question.id === data.postId
            })
            if (index > -1) {
                state.questionsAttachments.splice(index, 1)
            }
            //riddle
            index = state.riddles.findIndex(riddle=>{
                return riddle.id === data.postId
            })
            if (index > -1) {
                state.riddles.splice(index, 1)
            }
            index = state.riddlesMine.findIndex(riddle=>{
                return riddle.id === data.postId
            })
            if (index > -1) {
                state.riddlesMine.splice(index, 1)
            }
            index = state.riddlesAttachments.findIndex(riddle=>{
                return riddle.id === data.postId
            })
            if (index > -1) {
                state.riddlesAttachments.splice(index, 1)
            }
            //books
            index = state.books.findIndex(book=>{
                return book.id === data.postId
            })
            if (index > -1) {
                state.books.splice(index, 1)
            }
            index = state.booksMine.findIndex(book=>{
                return book.id === data.postId
            })
            if (index > -1) {
                state.booksMine.splice(index, 1)
            }
            index = state.booksAttachments.findIndex(book=>{
                return book.id === data.postId
            })
            if (index > -1) {
                state.booksAttachments.splice(index, 1)
            }
            //activities
            index = state.activities.findIndex(activity=>{
                return activity.id === data.postId
            })
            if (index > -1) {
                state.activities.splice(index, 1)
            }
            index = state.activitiesMine.findIndex(activity=>{
                return activity.id === data.postId
            })
            if (index > -1) {
                state.activitiesMine.splice(index, 1)
            }
            index = state.activitiesAttachments.findIndex(activity=>{
                return activity.id === data.postId
            })
            if (index > -1) {
                state.activitiesAttachments.splice(index, 1)
            }
        },
        POSTS_SUCCESS(state,main){
            let {params, data} = main
            if (data.data.length) {
                if (params.hasOwnProperty('mine')) {
                    state.postsMine.push(...data.data)
                } else if (params.hasOwnProperty('followers')) {
                    state.postsFollowers.push(...data.data)
                } else if (params.hasOwnProperty('followings')) {
                    state.postsFollowings.push(...data.data)
                } else if (params.hasOwnProperty('attachments')) {
                    state.postsAttachments.push(...data.data)
                } else {
                    state.posts.push(...data.data)
                }
            } else{
                state.postDone = true
            }
        },
        POST_TYPES_SUCCESS(state,main){
            let {params, data} = main
            if (data.data.length) {
                if (params.hasOwnProperty('postType') &&
                    params.postType === 'questions') {
                    if (params.hasOwnProperty('mine')) {
                        state.questionsMine.push(...data.data)
                    } else if (params.hasOwnProperty('followers')) {
                        state.questionsFollowers.push(...data.data)
                    } else if (params.hasOwnProperty('followings')) {
                        state.questionsFollowings.push(...data.data)
                    } else if (params.hasOwnProperty('attachments')) {
                        state.questionsAttachments.push(...data.data)
                    } else {
                        state.questions.push(...data.data)
                    }
                } else if (params.hasOwnProperty('postType') &&
                    params.postType === 'riddles') {
                    if (params.hasOwnProperty('mine')) {
                        state.riddlesMine.push(...data.data)
                    } else if (params.hasOwnProperty('followers')) {
                        state.riddlesFollowers.push(...data.data)
                    } else if (params.hasOwnProperty('followings')) {
                        state.riddlesFollowings.push(...data.data)
                    } else if (params.hasOwnProperty('attachments')) {
                        state.riddlesAttachments.push(...data.data)
                    } else {
                        state.riddles.push(...data.data)
                    }
                } else if (params.hasOwnProperty('postType') &&
                    params.postType === 'poems') {
                    if (params.hasOwnProperty('mine')) {
                        state.poemsMine.push(...data.data)
                    } else if (params.hasOwnProperty('followers')) {
                        state.poemsFollowers.push(...data.data)
                    } else if (params.hasOwnProperty('followings')) {
                        state.poemsFollowings.push(...data.data)
                    } else if (params.hasOwnProperty('attachments')) {
                        state.poemsAttachments.push(...data.data)
                    } else {
                        state.poems.push(...data.data)
                    }
                } else if (params.hasOwnProperty('postType') &&
                    params.postType === 'activities') {
                    if (params.hasOwnProperty('mine')) {
                        state.activitiesMine.push(...data.data)
                    } else if (params.hasOwnProperty('followers')) {
                        state.activitiesFollowers.push(...data.data)
                    } else if (params.hasOwnProperty('followings')) {
                        state.activitiesFollowings.push(...data.data)
                    } else if (params.hasOwnProperty('attachments')) {
                        state.activitiesAttachments.push(...data.data)
                    } else {
                        state.activities.push(...data.data)
                    }
                } else if (params.hasOwnProperty('postType') &&
                    params.postType === 'books') {
                    if (params.hasOwnProperty('mine')) {
                        state.booksMine.push(...data.data)
                    } else if (params.hasOwnProperty('followers')) {
                        state.booksFollowers.push(...data.data)
                    } else if (params.hasOwnProperty('followings')) {
                        state.booksFollowings.push(...data.data)
                    } else if (params.hasOwnProperty('attachments')) {
                        state.booksAttachments.push(...data.data)
                    } else {
                        state.books.push(...data.data)
                    }
                }
            }
        },
        CLEAR_POSTS(state){
            state.posts = []
        },
        CLEAR_POSTS_ATTACHMENTS(state){
            state.postsAttachments = []
        },
        CLEAR_QUESTIONS_ATTACHMENTS(state){
            state.questionsAttachments = []
        },
        CLEAR_POEMS_ATTACHMENTS(state){
            state.poemsAttachments = []
        },
        CLEAR_ACTIVITIES_ATTACHMENTS(state){
            state.activitiesAttachments = []
        },
        CLEAR_RIDDLES_ATTACHMENTS(state){
            state.riddlesAttachments = []
        },
        CLEAR_BOOKS_ATTACHMENTS(state){
            state.booksAttachments = []
        },
        ////////////////////////////////////comments

        COMMENT_UPDATE_SUCCESS(state,data){
            if (data.comment.commentable_type.toLocaleLowerCase().includes('post')) {
                //posts
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
                postIndex = state.postsMine.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsMine[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.postsMine[postIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                postIndex = state.postsFollowers.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.postsFollowers[postIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                postIndex = state.postsFollowings.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.postsFollowings[postIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                postIndex = state.postsAttachments.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.postsAttachments[postIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                //poem
                let poemIndex = state.poems.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.poems[poemIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                poemIndex = state.poemsMine.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsMine[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.poemsMine[poemIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                poemIndex = state.poemsFollowers.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.poemsFollowers[poemIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                poemIndex = state.poemsFollowings.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.poemsFollowings[poemIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                poemIndex = state.poemsAttachments.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.poemsAttachments[poemIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                //questions
                let questionIndex = state.questions.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.questions[questionIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                questionIndex = state.questionsMine.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsMine[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.questionsMine[questionIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                questionIndex = state.questionsFollowers.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.questionsFollowers[questionIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                questionIndex = state.questionsFollowings.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.questionsFollowings[questionIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                questionIndex = state.questionsAttachments.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.questionsAttachments[questionIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                //activities
                let activitiesIndex = state.activities.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activitiesIndex > -1) {
                    let commentIndex = state.activities[activitiesIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.activities[activitiesIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                activitiesIndex = state.activitiesMine.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activitiesIndex > -1) {
                    let commentIndex = state.activitiesMine[activitiesIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.activitiesMine[activitiesIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                activitiesIndex = state.activitiesFollowers.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activitiesIndex > -1) {
                    let commentIndex = state.activitiesFollowers[activitiesIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.activitiesFollowers[activitiesIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                activitiesIndex = state.activitiesFollowings.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activitiesIndex > -1) {
                    let commentIndex = state.activitiesFollowings[activitiesIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.activitiesFollowings[activitiesIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                activitiesIndex = state.activitiesAttachments.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activitiesIndex > -1) {
                    let commentIndex = state.activitiesAttachments[activitiesIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.activitiesAttachments[activitiesIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                //books
                let bookIndex = state.books.findIndex(book=>{
                    return book.id === data.comment.commentable_id
                })
                if (bookIndex > -1) {
                    let commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.books[bookIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                bookIndex = state.booksMine.findIndex(book=>{
                    return book.id === data.comment.commentable_id
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksMine[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.booksMine[bookIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                bookIndex = state.booksFollowers.findIndex(book=>{
                    return book.id === data.comment.commentable_id
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.booksFollowers[bookIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                bookIndex = state.booksFollowings.findIndex(book=>{
                    return book.id === data.comment.commentable_id
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.booksFollowings[bookIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                bookIndex = state.booksAttachments.findIndex(book=>{
                    return book.id === data.comment.commentable_id
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.booksAttachments[bookIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                //riddles
                let riddleIndex = state.riddles.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.riddles[riddleIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                riddleIndex = state.riddlesMine.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesMine[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.riddlesMine[riddleIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.riddlesFollowers[riddleIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.riddlesFollowings[riddleIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.comment.id
                    })
                    if (commentIndex > -1) {
                        state.riddlesAttachments[riddleIndex].comments.splice(commentIndex,1,data.comment)
                        return
                    }
                }
            }
        },
        COMMENT_DELETE_SUCCESS(state,data){
            if (data.owner.toLocaleLowerCase().includes('post')) {
                //posts
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
                }
                postIndex = state.postsMine.findIndex(post=>{
                    return post.id === data.ownerId
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsMine[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.postsMine[postIndex].comments.splice(commentIndex,1)
                    }
                }
                postIndex = state.postsFollowers.findIndex(post=>{
                    return post.id === data.ownerId
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.postsFollowers[postIndex].comments.splice(commentIndex,1)
                    }
                }
                postIndex = state.postsFollowings.findIndex(post=>{
                    return post.id === data.ownerId
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.postsFollowings[postIndex].comments.splice(commentIndex,1)
                    }
                }
                postIndex = state.postsAttachments.findIndex(post=>{
                    return post.id === data.ownerId
                })
                if (postIndex > -1) {
                    let commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.postsAttachments[postIndex].comments.splice(commentIndex,1)
                    }
                }
                ///poems
                let poemIndex = state.poems.findIndex(poem=>{
                    return poem.id === data.ownerId
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.poems[poemIndex].comments.splice(commentIndex,1)
                    }
                }
                poemIndex = state.poemsMine.findIndex(poem=>{
                    return poem.id === data.ownerId
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsMine[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.poemsMine[poemIndex].comments.splice(commentIndex,1)
                    }
                }
                poemIndex = state.poemsFollowers.findIndex(poem=>{
                    return poem.id === data.ownerId
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.poemsFollowers[poemIndex].comments.splice(commentIndex,1)
                    }
                }
                poemIndex = state.poemsFollowings.findIndex(poem=>{
                    return poem.id === data.ownerId
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.poemsFollowings[poemIndex].comments.splice(commentIndex,1)
                    }
                }
                poemIndex = state.poemsAttachments.findIndex(poem=>{
                    return poem.id === data.ownerId
                })
                if (poemIndex > -1) {
                    let commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.poemsAttachments[poemIndex].comments.splice(commentIndex,1)
                    }
                }
                //questions
                let questionIndex = state.questions.findIndex(question=>{
                    return question.id === data.ownerId
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.questions[questionIndex].comments.splice(commentIndex,1)
                    }
                }
                questionIndex = state.questionsMine.findIndex(question=>{
                    return question.id === data.ownerId
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsMine[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.questionsMine[questionIndex].comments.splice(commentIndex,1)
                    }
                }
                questionIndex = state.questionsFollowers.findIndex(question=>{
                    return question.id === data.ownerId
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.questionsFollowers[questionIndex].comments.splice(commentIndex,1)
                    }
                }
                questionIndex = state.questionsFollowings.findIndex(question=>{
                    return question.id === data.ownerId
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.questionsFollowings[questionIndex].comments.splice(commentIndex,1)
                    }
                }
                questionIndex = state.questionsAttachments.findIndex(question=>{
                    return question.id === data.ownerId
                })
                if (questionIndex > -1) {
                    let commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.questionsAttachments[questionIndex].comments.splice(commentIndex,1)
                    }
                }
                //riddles
                let riddleIndex = state.riddles.findIndex(riddle=>{
                    return riddle.id === data.ownerId
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.riddles[riddleIndex].comments.splice(commentIndex,1)
                    }
                }
                riddleIndex = state.riddlesMine.findIndex(riddle=>{
                    return riddle.id === data.ownerId
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesMine[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.riddlesMine[riddleIndex].comments.splice(commentIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                    return riddle.id === data.ownerId
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.riddlesFollowers[riddleIndex].comments.splice(commentIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                    return riddle.id === data.ownerId
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.riddlesFollowings[riddleIndex].comments.splice(commentIndex,1)
                    }
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                    return riddle.id === data.ownerId
                })
                if (riddleIndex > -1) {
                    let commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.riddlesAttachments[riddleIndex].comments.splice(commentIndex,1)
                    }
                }
                //books
                let bookIndex = state.books.findIndex(book=>{
                    return book.id === data.ownerId
                })
                if (bookIndex > -1) {
                    let commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.books[bookIndex].comments.splice(commentIndex,1)
                    }
                }
                bookIndex = state.booksMine.findIndex(book=>{
                    return book.id === data.ownerId
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksMine[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.booksMine[bookIndex].comments.splice(commentIndex,1)
                    }
                }
                bookIndex = state.booksFollowers.findIndex(book=>{
                    return book.id === data.ownerId
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.booksFollowers[bookIndex].comments.splice(commentIndex,1)
                    }
                }
                bookIndex = state.booksFollowings.findIndex(book=>{
                    return book.id === data.ownerId
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.booksFollowings[bookIndex].comments.splice(commentIndex,1)
                    }
                }
                bookIndex = state.booksAttachments.findIndex(book=>{
                    return book.id === data.ownerId
                })
                if (bookIndex > -1) {
                    let commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.booksAttachments[bookIndex].comments.splice(commentIndex,1)
                    }
                }
                //activities
                let activityIndex = state.activities.findIndex(activity=>{
                    return activity.id === data.ownerId
                })
                if (activityIndex > -1) {
                    let commentIndex = state.activities[activityIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.activities[activityIndex].comments.splice(commentIndex,1)
                    }
                }
                activityIndex = state.activitiesMine.findIndex(activity=>{
                    return activity.id === data.ownerId
                })
                if (activityIndex > -1) {
                    let commentIndex = state.activitiesMine[activityIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.activitiesMine[activityIndex].comments.splice(commentIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowers.findIndex(activity=>{
                    return activity.id === data.ownerId
                })
                if (activityIndex > -1) {
                    let commentIndex = state.activitiesFollowers[activityIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.activitiesFollowers[activityIndex].comments.splice(commentIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowings.findIndex(activity=>{
                    return activity.id === data.ownerId
                })
                if (activityIndex > -1) {
                    let commentIndex = state.activitiesFollowings[activityIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.activitiesFollowings[activityIndex].comments.splice(commentIndex,1)
                    }
                }
                activityIndex = state.activitiesAttachments.findIndex(activity=>{
                    return activity.id === data.ownerId
                })
                if (activityIndex > -1) {
                    let commentIndex = state.activitiesAttachments[activityIndex].comments.findIndex(comment=>{
                        return comment.id === data.commentId
                    })
                    if (commentIndex > -1) {
                        state.activitiesAttachments[activityIndex].comments.splice(commentIndex,1)
                    }
                }
            } else if (data.owner.toLocaleLowerCase().includes('comment')) {
                state.posts.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.postsMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.postsFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.postsFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.postsAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.poems.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.poemsMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.poemsFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.poemsFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.poemsAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.questions.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.questionsMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.questionsFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.questionsFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.questionsAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.riddles.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.riddlesMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.riddlesFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.riddlesFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.riddlesAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.activities.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.activitiesMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.activitiesFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.activitiesFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.activitiesAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.books.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.booksMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.booksFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.booksFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
                state.booksAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.commentId) {
                            comment.comments -= 1
                        }
                    })
                })
            }
        },
        COMMENT_SUCCESS(state, data){
            if (data.comment.commentable_type.toLocaleLowerCase().includes('post')) {
                //posts
                let postIndex = state.posts.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    state.posts[postIndex].comments.unshift(data.comment)
                }
                postIndex = state.postsMine.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    state.postsMine[postIndex].comments.unshift(data.comment)
                }
                postIndex = state.postsFollowers.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    state.postsFollowers[postIndex].comments.unshift(data.comment)
                }
                postIndex = state.postsFollowings.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    state.postsFollowings[postIndex].comments.unshift(data.comment)
                }
                postIndex = state.postsAttachments.findIndex(post=>{
                    return post.id === data.comment.commentable_id
                })
                if (postIndex > -1) {
                    state.postsAttachments[postIndex].comments.unshift(data.comment)
                }
                //poems
                let poemIndex = state.poems.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    state.poems[poemIndex].comments.unshift(data.comment)
                }
                poemIndex = state.poemsMine.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    state.poemsMine[poemIndex].comments.unshift(data.comment)
                }
                poemIndex = state.poemsFollowers.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    state.poemsFollowers[poemIndex].comments.unshift(data.comment)
                }
                poemIndex = state.poemsFollowings.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    state.poemsFollowings[poemIndex].comments.unshift(data.comment)
                }
                poemIndex = state.poemsAttachments.findIndex(poem=>{
                    return poem.id === data.comment.commentable_id
                })
                if (poemIndex > -1) {
                    state.poemsAttachments[poemIndex].comments.unshift(data.comment)
                }
                //questions
                let questionIndex = state.questions.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    state.questions[questionIndex].comments.unshift(data.comment)
                }
                questionIndex = state.questionsMine.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    state.questionsMine[questionIndex].comments.unshift(data.comment)
                }
                questionIndex = state.questionsFollowers.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    state.questionsFollowers[questionIndex].comments.unshift(data.comment)
                }
                questionIndex = state.questionsFollowings.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    state.questionsFollowings[questionIndex].comments.unshift(data.comment)
                }
                questionIndex = state.questionsAttachments.findIndex(question=>{
                    return question.id === data.comment.commentable_id
                })
                if (questionIndex > -1) {
                    state.questionsAttachments[questionIndex].comments.unshift(data.comment)
                }
                //riddles
                let riddleIndex = state.riddles.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    state.riddles[riddleIndex].comments.unshift(data.comment)
                }
                riddleIndex = state.riddlesMine.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    state.riddlesMine[riddleIndex].comments.unshift(data.comment)
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowers[riddleIndex].comments.unshift(data.comment)
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowings[riddleIndex].comments.unshift(data.comment)
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                    return riddle.id === data.comment.commentable_id
                })
                if (riddleIndex > -1) {
                    state.riddlesAttachments[riddleIndex].comments.unshift(data.comment)
                }
                //activities
                let activityIndex = state.activities.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activityIndex > -1) {
                    state.activities[activityIndex].comments.unshift(data.comment)
                }
                activityIndex = state.activitiesMine.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activityIndex > -1) {
                    state.activitiesMine[activityIndex].comments.unshift(data.comment)
                }
                activityIndex = state.activitiesFollowers.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activityIndex > -1) {
                    state.activitiesFollowers[activityIndex].comments.unshift(data.comment)
                }
                activityIndex = state.activitiesFollowings.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activityIndex > -1) {
                    state.activitiesFollowings[activityIndex].comments.unshift(data.comment)
                }
                activityIndex = state.activitiesAttachments.findIndex(activity=>{
                    return activity.id === data.comment.commentable_id
                })
                if (activityIndex > -1) {
                    state.activitiesAttachments[activityIndex].comments.unshift(data.comment)
                }
            } else if (data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                //add 1 to the number of comments of the comment belonging to the post
                state.posts.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.postsMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.postsFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.postsFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.postsAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.poems.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.poemsMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.poemsFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.poemsFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.poemsAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.questions.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.questionsMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.questionsFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.questionsFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.questionsAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.riddles.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.riddlesMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.riddlesFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.riddlesFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.riddlesAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.activities.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.activitiesMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.activitiesFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.activitiesFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.activitiesAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.books.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.booksMine.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.booksFollowers.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.booksFollowings.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
                state.booksAttachments.forEach(post=>{
                    post.comments.forEach(comment=>{
                        if (comment.id === data.comment.commentable_id) {
                            comment.comments += 1
                        }
                    })
                })
            }
        },

        ///////////////////////////////////saves

        SAVE_CREATE_SUCCESS(state, data){
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null,
                        commentIndex = null,
                        poemIndex = null,
                        bookIndex = null,
                        riddleIndex = null,
                        activityIndex = null,
                        questionIndex = null
                    //posts
                    postIndex = state.posts.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.posts[postIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    postIndex = state.postsFollowers.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsFollowers[postIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    postIndex = state.postsFollowings.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsFollowings[postIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    postIndex = state.postsAttachments.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsAttachments[postIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    //poems
                    poemIndex = state.poems.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poems[poemIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    poemIndex = state.poemsFollowers.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsFollowers[poemIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    poemIndex = state.poemsFollowings.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsFollowings[poemIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    poemIndex = state.poemsAttachments.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsAttachments[poemIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    //questions
                    questionIndex = state.questions.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questions[questionIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    questionIndex = state.questionsFollowers.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsFollowers[questionIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    questionIndex = state.questionsFollowings.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsFollowings[questionIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    questionIndex = state.questionsAttachments.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsAttachments[questionIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    //riddles
                    riddleIndex = state.riddles.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddles[riddleIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesFollowers[riddleIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesFollowings[riddleIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesAttachments[riddleIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    //books
                    bookIndex = state.books.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.books[bookIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    bookIndex = state.booksFollowers.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksFollowers[bookIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    bookIndex = state.booksFollowings.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksFollowings[bookIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    bookIndex = state.booksAttachments.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksAttachments[bookIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    //activities
                    activityIndex = state.activities.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activities[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activities[activityIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    activityIndex = state.activitiesFollowers.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesFollowers[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesFollowers[activityIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    activityIndex = state.activitiesFollowings.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesFollowings[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesFollowings[activityIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                    activityIndex = state.activitiesAttachments.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesAttachments[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesAttachments[activityIndex].comments[commentIndex].saves.push(data.save)
                        }
                    }
                }
            } else if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    bookIndex = null,
                    riddleIndex = null,
                    activityIndex = null,
                    questionIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.posts[postIndex].saves.unshift(data.save)
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowers[postIndex].saves.unshift(data.save)
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowings[postIndex].saves.unshift(data.save)
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsAttachments[postIndex].saves.unshift(data.save)
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poems[poemIndex].saves.unshift(data.save)
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowers[poemIndex].saves.unshift(data.save)
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowings[poemIndex].saves.unshift(data.save)
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsAttachments[poemIndex].saves.unshift(data.save)
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddles[riddleIndex].saves.unshift(data.save)
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowers[riddleIndex].saves.unshift(data.save)
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowings[riddleIndex].saves.unshift(data.save)
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesAttachments[riddleIndex].saves.unshift(data.save)
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questions[questionIndex].saves.unshift(data.save)
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowers[questionIndex].saves.unshift(data.save)
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowings[questionIndex].saves.unshift(data.save)
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsAttachments[questionIndex].saves.unshift(data.save)
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.books[bookIndex].saves.unshift(data.save)
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowers[bookIndex].saves.unshift(data.save)
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowings[bookIndex].saves.unshift(data.save)
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksAttachments[bookIndex].saves.unshift(data.save)
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activities[activityIndex].saves.unshift(data.save)
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowers[activityIndex].saves.unshift(data.save)
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowings[activityIndex].saves.unshift(data.save)
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesAttachments[activityIndex].saves.unshift(data.save)
                }
            }
        
        },
        SAVE_DELETE_SUCCESS(state, data){
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null,
                        commentIndex = null,
                        saveIndex = null,
                        poemIndex = null,
                        bookIndex = null,
                        riddleIndex = null,
                        activityIndex = null,
                        questionIndex = null
                    //posts
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
                            }
                        }
                    }
                    postIndex = state.postsFollowers.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.postsFollowers[postIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.postsFollowers[postIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsFollowings.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.postsFollowings[postIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.postsFollowings[postIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsAttachments.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.postsAttachments[postIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.postsAttachments[postIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    //poems
                    poemIndex = state.poems.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.poems[poemIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.poems[poemIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsFollowers.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.poemsFollowers[poemIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.poemsFollowers[poemIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsFollowings.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.poemsFollowings[poemIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.poemsFollowings[poemIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsAttachments.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.poemsAttachments[poemIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.poemsAttachments[poemIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    //riddles
                    riddleIndex = state.riddles.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.riddles[riddleIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.riddles[riddleIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.riddlesFollowers[riddleIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.riddlesFollowers[riddleIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.riddlesFollowings[riddleIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.riddlesFollowings[riddleIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.riddlesAttachments[riddleIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.riddlesAttachments[riddleIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    //questions
                    questionIndex = state.questions.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.questions[questionIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.questions[questionIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsFollowers.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.questionsFollowers[questionIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.questionsFollowers[questionIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsFollowings.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.questionsFollowings[questionIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.questionsFollowings[questionIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsAttachments.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.questionsAttachments[questionIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.questionsAttachments[questionIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    //books
                    bookIndex = state.books.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.books[bookIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.books[bookIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksFollowers.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.booksFollowers[bookIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.booksFollowers[bookIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksFollowings.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.booksFollowings[bookIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.booksFollowings[bookIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksAttachments.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.booksAttachments[bookIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.booksAttachments[bookIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    //activities
                    activityIndex = state.activities.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activities[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.activities[activityIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.activities[activityIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesFollowers.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesFollowers[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.activitiesFollowers[activityIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.activitiesFollowers[activityIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesFollowings.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesFollowings[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.activitiesFollowings[activityIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.activitiesFollowings[activityIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesAttachments.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesAttachments[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            saveIndex = state.activitiesAttachments[activityIndex]
                                .comments[commentIndex].saves.findIndex(save =>{
                                return save.id === data.saveId
                            })
                            if (saveIndex > -1) {
                                state.activitiesAttachments[activityIndex].comments[commentIndex]
                                    .saves.splice(saveIndex,1)
                            }
                        }
                    }
                }
            } else if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    bookIndex = null,
                    riddleIndex = null,
                    activityIndex = null,
                    questionIndex = null,
                    saveIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    saveIndex = state.posts[postIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.posts[postIndex].saves.splice(saveIndex,1)
                    }
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    saveIndex = state.postsFollowers[postIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.postsFollowers[postIndex].saves.splice(saveIndex,1)
                    }
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    saveIndex = state.postsFollowings[postIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.postsFollowings[postIndex].saves.splice(saveIndex,1)
                    }
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    saveIndex = state.postsAttachments[postIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.postsAttachments[postIndex].saves.splice(saveIndex,1)
                    }
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    saveIndex = state.poems[poemIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.poems[poemIndex].saves.splice(saveIndex,1)
                    }
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    saveIndex = state.poemsFollowers[poemIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.poemsFollowers[poemIndex].saves.splice(saveIndex,1)
                    }
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    saveIndex = state.poemsFollowings[poemIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.poemsFollowings[poemIndex].saves.splice(saveIndex,1)
                    }
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    saveIndex = state.poemsAttachments[poemIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.poemsAttachments[poemIndex].saves.splice(saveIndex,1)
                    }
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    saveIndex = state.riddles[riddleIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.riddles[riddleIndex].saves.splice(saveIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    saveIndex = state.riddlesFollowers[riddleIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.riddlesFollowers[riddleIndex].saves.splice(saveIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    saveIndex = state.riddlesFollowings[riddleIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.riddlesFollowings[riddleIndex].saves.splice(saveIndex,1)
                    }
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    saveIndex = state.riddlesAttachments[riddleIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.riddlesAttachments[riddleIndex].saves.splice(saveIndex,1)
                    }
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    saveIndex = state.questions[questionIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.questions[questionIndex].saves.splice(saveIndex,1)
                    }
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    saveIndex = state.questionsFollowers[questionIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.questionsFollowers[questionIndex].saves.splice(saveIndex,1)
                    }
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    saveIndex = state.questionsFollowings[questionIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.questionsFollowings[questionIndex].saves.splice(saveIndex,1)
                    }
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    saveIndex = state.questionsAttachments[questionIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.questionsAttachments[questionIndex].saves.splice(saveIndex,1)
                    }
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    saveIndex = state.activities[activityIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.activities[activityIndex].saves.splice(saveIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    saveIndex = state.activitiesFollowers[activityIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.activitiesFollowers[activityIndex].saves.splice(saveIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    saveIndex = state.activitiesFollowings[activityIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.activitiesFollowings[activityIndex].saves.splice(saveIndex,1)
                    }
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    saveIndex = state.activitiesAttachments[activityIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.activitiesAttachments[activityIndex].saves.splice(saveIndex,1)
                    }
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    saveIndex = state.books[bookIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.books[bookIndex].saves.splice(saveIndex,1)
                    }
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    saveIndex = state.booksFollowers[bookIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.booksFollowers[bookIndex].saves.splice(saveIndex,1)
                    }
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    saveIndex = state.booksFollowings[bookIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.booksFollowings[bookIndex].saves.splice(saveIndex,1)
                    }
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    saveIndex = state.booksAttachments[bookIndex].saves.findIndex(save=>{
                        return save.id === data.saveId
                    })
                    if (saveIndex > -1) {
                        state.booksAttachments[bookIndex].saves.splice(saveIndex,1)
                    }
                }
            }
        },

        ///////////////////////////////////flags

        FLAG_CREATE_SUCCESS(state, data){
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null,
                        commentIndex = null,
                        poemIndex = null,
                        bookIndex = null,
                        riddleIndex = null,
                        activityIndex = null,
                        questionIndex = null
                    //posts
                    postIndex = state.posts.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.posts[postIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    postIndex = state.postsFollowers.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsFollowers[postIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    postIndex = state.postsFollowings.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsFollowings[postIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    postIndex = state.postsAttachments.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsAttachments[postIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    //poems
                    poemIndex = state.poems.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poems[poemIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    poemIndex = state.poemsFollowers.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsFollowers[poemIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    poemIndex = state.poemsFollowings.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsFollowings[poemIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    poemIndex = state.poemsAttachments.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsAttachments[poemIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    //questions
                    questionIndex = state.questions.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questions[questionIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    questionIndex = state.questionsFollowers.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsFollowers[questionIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    questionIndex = state.questionsFollowings.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsFollowings[questionIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    questionIndex = state.questionsAttachments.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsAttachments[questionIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    //riddles
                    riddleIndex = state.riddles.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddles[riddleIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesFollowers[riddleIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesFollowings[riddleIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesAttachments[riddleIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    //books
                    bookIndex = state.books.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.books[bookIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    bookIndex = state.booksFollowers.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksFollowers[bookIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    bookIndex = state.booksFollowings.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksFollowings[bookIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    bookIndex = state.booksAttachments.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksAttachments[bookIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    //activities
                    activityIndex = state.activities.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activities[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activities[activityIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    activityIndex = state.activitiesFollowers.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesFollowers[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesFollowers[activityIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    activityIndex = state.activitiesFollowings.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesFollowings[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesFollowings[activityIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                    activityIndex = state.activitiesAttachments.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesAttachments[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesAttachments[activityIndex].comments[commentIndex].flags.push(data.flag)
                        }
                    }
                }
            } else if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    bookIndex = null,
                    riddleIndex = null,
                    activityIndex = null,
                    questionIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.posts[postIndex].flags.unshift(data.flag)
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowers[postIndex].flags.unshift(data.flag)
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowings[postIndex].flags.unshift(data.flag)
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsAttachments[postIndex].flags.unshift(data.flag)
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poems[poemIndex].flags.unshift(data.flag)
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowers[poemIndex].flags.unshift(data.flag)
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowings[poemIndex].flags.unshift(data.flag)
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsAttachments[poemIndex].flags.unshift(data.flag)
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddles[riddleIndex].flags.unshift(data.flag)
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowers[riddleIndex].flags.unshift(data.flag)
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowings[riddleIndex].flags.unshift(data.flag)
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesAttachments[riddleIndex].flags.unshift(data.flag)
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questions[questionIndex].flags.unshift(data.flag)
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowers[questionIndex].flags.unshift(data.flag)
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowings[questionIndex].flags.unshift(data.flag)
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsAttachments[questionIndex].flags.unshift(data.flag)
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.books[bookIndex].flags.unshift(data.flag)
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowers[bookIndex].flags.unshift(data.flag)
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowings[bookIndex].flags.unshift(data.flag)
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksAttachments[bookIndex].flags.unshift(data.flag)
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activities[activityIndex].flags.unshift(data.flag)
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowers[activityIndex].flags.unshift(data.flag)
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowings[activityIndex].flags.unshift(data.flag)
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesAttachments[activityIndex].flags.unshift(data.flag)
                }
            }
        },
        FLAG_DELETE_SUCCESS(state, data){
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null,
                        commentIndex = null,
                        flagIndex = null,
                        poemIndex = null,
                        bookIndex = null,
                        riddleIndex = null,
                        activityIndex = null,
                        questionIndex = null
                    //posts
                    postIndex = state.posts.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.posts[postIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.posts[postIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsFollowers.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.postsFollowers[postIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.postsFollowers[postIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsFollowings.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.postsFollowings[postIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.postsFollowings[postIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsAttachments.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.postsAttachments[postIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.postsAttachments[postIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    //poems
                    poemIndex = state.poems.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.poems[poemIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.poems[poemIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsFollowers.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.poemsFollowers[poemIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.poemsFollowers[poemIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsFollowings.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.poemsFollowings[poemIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.poemsFollowings[poemIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsAttachments.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.poemsAttachments[poemIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.poemsAttachments[poemIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    //riddles
                    riddleIndex = state.riddles.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.riddles[riddleIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.riddles[riddleIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.riddlesFollowers[riddleIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.riddlesFollowers[riddleIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.riddlesFollowings[riddleIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.riddlesFollowings[riddleIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.riddlesAttachments[riddleIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.riddlesAttachments[riddleIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    //questions
                    questionIndex = state.questions.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.questions[questionIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.questions[questionIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsFollowers.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.questionsFollowers[questionIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.questionsFollowers[questionIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsFollowings.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.questionsFollowings[questionIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.questionsFollowings[questionIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsAttachments.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.questionsAttachments[questionIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.questionsAttachments[questionIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    //books
                    bookIndex = state.books.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.books[bookIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.books[bookIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksFollowers.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.booksFollowers[bookIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.booksFollowers[bookIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksFollowings.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.booksFollowings[bookIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.booksFollowings[bookIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksAttachments.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.booksAttachments[bookIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.booksAttachments[bookIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    //activities
                    activityIndex = state.activities.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activities[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.activities[activityIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.activities[activityIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesFollowers.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesFollowers[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.activitiesFollowers[activityIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.activitiesFollowers[activityIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesFollowings.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesFollowings[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.activitiesFollowings[activityIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.activitiesFollowings[activityIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesAttachments.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesAttachments[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            flagIndex = state.activitiesAttachments[activityIndex]
                                .comments[commentIndex].flags.findIndex(flag =>{
                                return flag.id === data.flagId
                            })
                            if (flagIndex > -1) {
                                state.activitiesAttachments[activityIndex].comments[commentIndex]
                                    .flags.splice(flagIndex,1)
                            }
                        }
                    }
                }
            } else if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    bookIndex = null,
                    riddleIndex = null,
                    activityIndex = null,
                    questionIndex = null,
                    flagIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    flagIndex = state.posts[postIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.posts[postIndex].flags.splice(flagIndex,1)
                    }
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    flagIndex = state.postsFollowers[postIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.postsFollowers[postIndex].flags.splice(flagIndex,1)
                    }
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    flagIndex = state.postsFollowings[postIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.postsFollowings[postIndex].flags.splice(flagIndex,1)
                    }
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    flagIndex = state.postsAttachments[postIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.postsAttachments[postIndex].flags.splice(flagIndex,1)
                    }
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    flagIndex = state.poems[poemIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.poems[poemIndex].flags.splice(flagIndex,1)
                    }
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    flagIndex = state.poemsFollowers[poemIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.poemsFollowers[poemIndex].flags.splice(flagIndex,1)
                    }
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    flagIndex = state.poemsFollowings[poemIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.poemsFollowings[poemIndex].flags.splice(flagIndex,1)
                    }
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    flagIndex = state.poemsAttachments[poemIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.poemsAttachments[poemIndex].flags.splice(flagIndex,1)
                    }
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    flagIndex = state.riddles[riddleIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.riddles[riddleIndex].flags.splice(flagIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    flagIndex = state.riddlesFollowers[riddleIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.riddlesFollowers[riddleIndex].flags.splice(flagIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    flagIndex = state.riddlesFollowings[riddleIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.riddlesFollowings[riddleIndex].flags.splice(flagIndex,1)
                    }
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    flagIndex = state.riddlesAttachments[riddleIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.riddlesAttachments[riddleIndex].flags.splice(flagIndex,1)
                    }
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    flagIndex = state.questions[questionIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.questions[questionIndex].flags.splice(flagIndex,1)
                    }
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    flagIndex = state.questionsFollowers[questionIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.questionsFollowers[questionIndex].flags.splice(flagIndex,1)
                    }
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    flagIndex = state.questionsFollowings[questionIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.questionsFollowings[questionIndex].flags.splice(flagIndex,1)
                    }
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    flagIndex = state.questionsAttachments[questionIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.questionsAttachments[questionIndex].flags.splice(flagIndex,1)
                    }
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    flagIndex = state.activities[activityIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.activities[activityIndex].flags.splice(flagIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    flagIndex = state.activitiesFollowers[activityIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.activitiesFollowers[activityIndex].flags.splice(flagIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    flagIndex = state.activitiesFollowings[activityIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.activitiesFollowings[activityIndex].flags.splice(flagIndex,1)
                    }
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    flagIndex = state.activitiesAttachments[activityIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.activitiesAttachments[activityIndex].flags.splice(flagIndex,1)
                    }
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    flagIndex = state.books[bookIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.books[bookIndex].flags.splice(flagIndex,1)
                    }
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    flagIndex = state.booksFollowers[bookIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.booksFollowers[bookIndex].flags.splice(flagIndex,1)
                    }
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    flagIndex = state.booksFollowings[bookIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.booksFollowings[bookIndex].flags.splice(flagIndex,1)
                    }
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    flagIndex = state.booksAttachments[bookIndex].flags.findIndex(flag=>{
                        return flag.id === data.flagId
                    })
                    if (flagIndex > -1) {
                        state.booksAttachments[bookIndex].flags.splice(flagIndex,1)
                    }
                }
            }
        },

        ///////////////////////////////////likes

        LIKE_CREATE_SUCCESS(state, data){
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null,
                        poemIndex = null,
                        riddleIndex = null,
                        questionIndex = null,
                        bookIndex = null,
                        activityIndex = null,
                        commentIndex = null
                    //posts
                    postIndex = state.posts.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.posts[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.posts[postIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    postIndex = state.postsMine.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsMine[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsMine[postIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    postIndex = state.postsFollowers.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsFollowers[postIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    postIndex = state.postsFollowings.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsFollowings[postIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    postIndex = state.postsAttachments.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        
                        commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.postsAttachments[postIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    //poems
                    poemIndex = state.poems.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poems[poemIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    poemIndex = state.poemsMine.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsMine[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsMine[poemIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    poemIndex = state.poemsFollowers.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsFollowers[poemIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    poemIndex = state.poemsFollowings.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsFollowings[poemIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    poemIndex = state.poemsAttachments.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        
                        commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.poemsAttachments[poemIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    //questions
                    questionIndex = state.questions.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questions[questionIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    questionIndex = state.questionsMine.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsMine[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsMine[questionIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    questionIndex = state.questionsFollowers.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsFollowers[questionIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    questionIndex = state.questionsFollowings.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsFollowings[questionIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    questionIndex = state.questionsAttachments.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        
                        commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.questionsAttachments[questionIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    //riddles
                    riddleIndex = state.riddles.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddles[riddleIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    riddleIndex = state.riddlesMine.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesMine[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesMine[riddleIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesFollowers[riddleIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesFollowings[riddleIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        
                        commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.riddlesAttachments[riddleIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    //books
                    bookIndex = state.books.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.books[bookIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    bookIndex = state.booksMine.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksMine[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksMine[bookIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    bookIndex = state.booksFollowers.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksFollowers[bookIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    bookIndex = state.booksFollowings.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksFollowings[bookIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    bookIndex = state.booksAttachments.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        
                        commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.booksAttachments[bookIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    //activities
                    activityIndex = state.activities.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activities[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activities[activityIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    activityIndex = state.activitiesMine.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesMine[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesMine[activityIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    activityIndex = state.activitiesFollowers.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesFollowers[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesFollowers[activityIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    activityIndex = state.activitiesFollowings.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesFollowings[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesFollowings[activityIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                    activityIndex = state.activitiesAttachments.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        
                        commentIndex = state.activitiesAttachments[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            state.activitiesAttachments[activityIndex].comments[commentIndex].likes.push(data.like)
                        }
                    }
                }
            } else if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    riddleIndex = null,
                    questionIndex = null,
                    bookIndex = null,
                    activityIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.posts[postIndex].likes.unshift(data.like)
                }
                postIndex = state.postsMine.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsMine[postIndex].likes.unshift(data.like)
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowers[postIndex].likes.unshift(data.like)
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowings[postIndex].likes.unshift(data.like)
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsAttachments[postIndex].likes.unshift(data.like)
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poems[poemIndex].likes.unshift(data.like)
                }
                poemIndex = state.poemsMine.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsMine[poemIndex].likes.unshift(data.like)
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowers[poemIndex].likes.unshift(data.like)
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowings[poemIndex].likes.unshift(data.like)
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsAttachments[poemIndex].likes.unshift(data.like)
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddles[riddleIndex].likes.unshift(data.like)
                }
                riddleIndex = state.riddlesMine.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesMine[riddleIndex].likes.unshift(data.like)
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowers[riddleIndex].likes.unshift(data.like)
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowings[riddleIndex].likes.unshift(data.like)
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesAttachments[riddleIndex].likes.unshift(data.like)
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questions[questionIndex].likes.unshift(data.like)
                }
                questionIndex = state.questionsMine.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsMine[questionIndex].likes.unshift(data.like)
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowers[questionIndex].likes.unshift(data.like)
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowings[questionIndex].likes.unshift(data.like)
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsAttachments[questionIndex].likes.unshift(data.like)
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.books[bookIndex].likes.unshift(data.like)
                }
                bookIndex = state.booksMine.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksMine[bookIndex].likes.unshift(data.like)
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowers[bookIndex].likes.unshift(data.like)
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowings[bookIndex].likes.unshift(data.like)
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksAttachments[bookIndex].likes.unshift(data.like)
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activities[activityIndex].likes.unshift(data.like)
                }
                activityIndex = state.activitiesMine.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesMine[activityIndex].likes.unshift(data.like)
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowers[activityIndex].likes.unshift(data.like)
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowings[activityIndex].likes.unshift(data.like)
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesAttachments[activityIndex].likes.unshift(data.like)
                }
            }
        
        },
        LIKE_DELETE_SUCCESS(state, data){
            if (data.item === 'comment') {
                if (data.owner.toLocaleLowerCase().includes('post')) {
                    let postIndex = null,
                        poemIndex = null,
                        riddleIndex = null,
                        questionIndex = null,
                        bookIndex = null,
                        activityIndex = null,
                        commentIndex = null,
                        likeIndex = null
                    //posts
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
                            }
                        }
                    }
                    postIndex = state.postsMine.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsMine[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.postsMine[postIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.postsMine[postIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsFollowers.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsFollowers[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.postsFollowers[postIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.postsFollowers[postIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsFollowings.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsFollowings[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.postsFollowings[postIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.postsFollowings[postIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    postIndex = state.postsAttachments.findIndex(post=>{
                        return post.id === data.ownerId
                    })
                    if (postIndex > -1) {
                        commentIndex = state.postsAttachments[postIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.postsAttachments[postIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.postsAttachments[postIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    //poems
                    poemIndex = state.poems.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poems[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.poems[poemIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.poems[poemIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsMine.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsMine[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.poemsMine[poemIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.poemsMine[poemIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsFollowers.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsFollowers[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.poemsFollowers[poemIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.poemsFollowers[poemIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsFollowings.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsFollowings[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.poemsFollowings[poemIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.poemsFollowings[poemIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    poemIndex = state.poemsAttachments.findIndex(poem=>{
                        return poem.id === data.ownerId
                    })
                    if (poemIndex > -1) {
                        commentIndex = state.poemsAttachments[poemIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.poemsAttachments[poemIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.poemsAttachments[poemIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    //riddles
                    riddleIndex = state.riddles.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddles[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.riddles[riddleIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.riddles[riddleIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesMine.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesMine[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.riddlesMine[riddleIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.riddlesMine[riddleIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesFollowers.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesFollowers[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.riddlesFollowers[riddleIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.riddlesFollowers[riddleIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesFollowings.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesFollowings[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.riddlesFollowings[riddleIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.riddlesFollowings[riddleIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    riddleIndex = state.riddlesAttachments.findIndex(riddle=>{
                        return riddle.id === data.ownerId
                    })
                    if (riddleIndex > -1) {
                        commentIndex = state.riddlesAttachments[riddleIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.riddlesAttachments[riddleIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.riddlesAttachments[riddleIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    //questions
                    questionIndex = state.questions.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questions[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.questions[questionIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.questions[questionIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsMine.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsMine[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.questionsMine[questionIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.questionsMine[questionIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsFollowers.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsFollowers[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.questionsFollowers[questionIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.questionsFollowers[questionIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsFollowings.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsFollowings[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.questionsFollowings[questionIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.questionsFollowings[questionIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    questionIndex = state.questionsAttachments.findIndex(question=>{
                        return question.id === data.ownerId
                    })
                    if (questionIndex > -1) {
                        commentIndex = state.questionsAttachments[questionIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.questionsAttachments[questionIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.questionsAttachments[questionIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    //books
                    bookIndex = state.books.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.books[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.books[bookIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.books[bookIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksMine.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksMine[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.booksMine[bookIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.booksMine[bookIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksFollowers.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksFollowers[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.booksFollowers[bookIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.booksFollowers[bookIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksFollowings.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksFollowings[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.booksFollowings[bookIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.booksFollowings[bookIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    bookIndex = state.booksAttachments.findIndex(book=>{
                        return book.id === data.ownerId
                    })
                    if (bookIndex > -1) {
                        commentIndex = state.booksAttachments[bookIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.booksAttachments[bookIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.booksAttachments[bookIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    //activities
                    activityIndex = state.activities.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activities[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.activities[activityIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.activities[activityIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesMine.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesMine[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.activitiesMine[activityIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.activitiesMine[activityIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesFollowers.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesFollowers[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.activitiesFollowers[activityIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.activitiesFollowers[activityIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesFollowings.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesFollowings[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.activitiesFollowings[activityIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.activitiesFollowings[activityIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                    activityIndex = state.activitiesAttachments.findIndex(activity=>{
                        return activity.id === data.ownerId
                    })
                    if (activityIndex > -1) {
                        commentIndex = state.activitiesAttachments[activityIndex].comments.findIndex(comment=>{
                            return comment.id === data.itemId
                        })

                        if (commentIndex > -1) {
                            likeIndex = state.activitiesAttachments[activityIndex]
                                .comments[commentIndex].likes.findIndex(like =>{
                                return like.id === data.likeId
                            })
                            if (likeIndex > -1) {
                                state.activitiesAttachments[activityIndex].comments[commentIndex]
                                    .likes.splice(likeIndex,1)
                            }
                        }
                    }
                }
            } else if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    riddleIndex = null,
                    questionIndex = null,
                    bookIndex = null,
                    activityIndex = null,
                    likeIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    likeIndex = state.posts[postIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.posts[postIndex].likes.splice(likeIndex,1)
                    }
                }
                postIndex = state.postsMine.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    likeIndex = state.postsMine[postIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.postsMine[postIndex].likes.splice(likeIndex,1)
                    }
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    likeIndex = state.postsFollowers[postIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.postsFollowers[postIndex].likes.splice(likeIndex,1)
                    }
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    likeIndex = state.postsFollowings[postIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.postsFollowings[postIndex].likes.splice(likeIndex,1)
                    }
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    likeIndex = state.postsAttachments[postIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.postsAttachments[postIndex].likes.splice(likeIndex,1)
                    }
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    likeIndex = state.poems[poemIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.poems[poemIndex].likes.splice(likeIndex,1)
                    }
                }
                poemIndex = state.poemsMine.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    likeIndex = state.poemsMine[poemIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.poemsMine[poemIndex].likes.splice(likeIndex,1)
                    }
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    likeIndex = state.poemsFollowers[poemIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.poemsFollowers[poemIndex].likes.splice(likeIndex,1)
                    }
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    likeIndex = state.poemsFollowings[poemIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.poemsFollowings[poemIndex].likes.splice(likeIndex,1)
                    }
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    likeIndex = state.poemsAttachments[poemIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.poemsAttachments[poemIndex].likes.splice(likeIndex,1)
                    }
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    likeIndex = state.riddles[riddleIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.riddles[riddleIndex].likes.splice(likeIndex,1)
                    }
                }
                riddleIndex = state.riddlesMine.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    likeIndex = state.riddlesMine[riddleIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.riddlesMine[riddleIndex].likes.splice(likeIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    likeIndex = state.riddlesFollowers[riddleIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.riddlesFollowers[riddleIndex].likes.splice(likeIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    likeIndex = state.riddlesFollowings[riddleIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.riddlesFollowings[riddleIndex].likes.splice(likeIndex,1)
                    }
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    likeIndex = state.riddlesAttachments[riddleIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.riddlesAttachments[riddleIndex].likes.splice(likeIndex,1)
                    }
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    likeIndex = state.questions[questionIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.questions[questionIndex].likes.splice(likeIndex,1)
                    }
                }
                questionIndex = state.questionsMine.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    likeIndex = state.questionsMine[questionIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.questionsMine[questionIndex].likes.splice(likeIndex,1)
                    }
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    likeIndex = state.questionsFollowers[questionIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.questionsFollowers[questionIndex].likes.splice(likeIndex,1)
                    }
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    likeIndex = state.questionsFollowings[questionIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.questionsFollowings[questionIndex].likes.splice(likeIndex,1)
                    }
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    likeIndex = state.questionsAttachments[questionIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.questionsAttachments[questionIndex].likes.splice(likeIndex,1)
                    }
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    likeIndex = state.activities[activityIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.activities[activityIndex].likes.splice(likeIndex,1)
                    }
                }
                activityIndex = state.activitiesMine.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    likeIndex = state.activitiesMine[activityIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.activitiesMine[activityIndex].likes.splice(likeIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    likeIndex = state.activitiesFollowers[activityIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.activitiesFollowers[activityIndex].likes.splice(likeIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    likeIndex = state.activitiesFollowings[activityIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.activitiesFollowings[activityIndex].likes.splice(likeIndex,1)
                    }
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    likeIndex = state.activitiesAttachments[activityIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.activitiesAttachments[activityIndex].likes.splice(likeIndex,1)
                    }
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    likeIndex = state.books[bookIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.books[bookIndex].likes.splice(likeIndex,1)
                    }
                }
                bookIndex = state.booksMine.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    likeIndex = state.booksMine[bookIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.booksMine[bookIndex].likes.splice(likeIndex,1)
                    }
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    likeIndex = state.booksFollowers[bookIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.booksFollowers[bookIndex].likes.splice(likeIndex,1)
                    }
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    likeIndex = state.booksFollowings[bookIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.booksFollowings[bookIndex].likes.splice(likeIndex,1)
                    }
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    likeIndex = state.booksAttachments[bookIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })
                    if (likeIndex > -1) {
                        state.booksAttachments[bookIndex].likes.splice(likeIndex,1)
                    }
                }
            }
        },

        ///////////////////////////////////attachment

        ATTACHMENT_CREATE_SUCCESS(state, data){
            if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    riddleIndex = null,
                    questionIndex = null,
                    bookIndex = null,
                    activityIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.posts[postIndex].attachments.unshift(data.attachment)
                }
                postIndex = state.postsMine.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsMine[postIndex].attachments.unshift(data.attachment)
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowers[postIndex].attachments.unshift(data.attachment)
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsFollowings[postIndex].attachments.unshift(data.attachment)
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    state.postsAttachments[postIndex].attachments.unshift(data.attachment)
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poems[poemIndex].attachments.unshift(data.attachment)
                }
                poemIndex = state.poemsMine.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsMine[poemIndex].attachments.unshift(data.attachment)
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowers[poemIndex].attachments.unshift(data.attachment)
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsFollowings[poemIndex].attachments.unshift(data.attachment)
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    state.poemsAttachments[poemIndex].attachments.unshift(data.attachment)
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddles[riddleIndex].attachments.unshift(data.attachment)
                }
                riddleIndex = state.riddlesMine.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesMine[riddleIndex].attachments.unshift(data.attachment)
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowers[riddleIndex].attachments.unshift(data.attachment)
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesFollowings[riddleIndex].attachments.unshift(data.attachment)
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    state.riddlesAttachments[riddleIndex].attachments.unshift(data.attachment)
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questions[questionIndex].attachments.unshift(data.attachment)
                }
                questionIndex = state.questionsMine.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsMine[questionIndex].attachments.unshift(data.attachment)
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowers[questionIndex].attachments.unshift(data.attachment)
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsFollowings[questionIndex].attachments.unshift(data.attachment)
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    state.questionsAttachments[questionIndex].attachments.unshift(data.attachment)
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.books[bookIndex].attachments.unshift(data.attachment)
                }
                bookIndex = state.booksMine.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksMine[bookIndex].attachments.unshift(data.attachment)
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowers[bookIndex].attachments.unshift(data.attachment)
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksFollowings[bookIndex].attachments.unshift(data.attachment)
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    state.booksAttachments[bookIndex].attachments.unshift(data.attachment)
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activities[activityIndex].attachments.unshift(data.attachment)
                }
                activityIndex = state.activitiesMine.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesMine[activityIndex].attachments.unshift(data.attachment)
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowers[activityIndex].attachments.unshift(data.attachment)
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesFollowings[activityIndex].attachments.unshift(data.attachment)
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    state.activitiesAttachments[activityIndex].attachments.unshift(data.attachment)
                }
            }
        },
        ATTACHMENT_DELETE_SUCCESS(state, data){
            if (data.item === 'post') {
                let postIndex = null,
                    poemIndex = null,
                    riddleIndex = null,
                    questionIndex = null,
                    bookIndex = null,
                    activityIndex = null,
                    attachmentIndex = null
                //posts
                postIndex = state.posts.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    attachmentIndex = state.posts[postIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.posts[postIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                postIndex = state.postsMine.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    attachmentIndex = state.postsMine[postIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.postsMine[postIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                postIndex = state.postsFollowers.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    attachmentIndex = state.postsFollowers[postIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.postsFollowers[postIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                postIndex = state.postsFollowings.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    attachmentIndex = state.postsFollowings[postIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.postsFollowings[postIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                postIndex = state.postsAttachments.findIndex(post => {
                    return post.id === data.itemId
                })
                if (postIndex > -1) {
                    attachmentIndex = state.postsAttachments[postIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.postsAttachments[postIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                //poems
                poemIndex = state.poems.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    attachmentIndex = state.poems[poemIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.poems[poemIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                poemIndex = state.poemsMine.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    attachmentIndex = state.poemsMine[poemIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.poemsMine[poemIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                poemIndex = state.poemsFollowers.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    attachmentIndex = state.poemsFollowers[poemIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.poemsFollowers[poemIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                poemIndex = state.poemsFollowings.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    attachmentIndex = state.poemsFollowings[poemIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.poemsFollowings[poemIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                poemIndex = state.poemsAttachments.findIndex(poem => {
                    return poem.id === data.itemId
                })
                if (poemIndex > -1) {
                    attachmentIndex = state.poemsAttachments[poemIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.poemsAttachments[poemIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                //riddles
                riddleIndex = state.riddles.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    attachmentIndex = state.riddles[riddleIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.riddles[riddleIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                riddleIndex = state.riddlesMine.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    attachmentIndex = state.riddlesMine[riddleIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.riddlesMine[riddleIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowers.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    attachmentIndex = state.riddlesFollowers[riddleIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.riddlesFollowers[riddleIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                riddleIndex = state.riddlesFollowings.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    attachmentIndex = state.riddlesFollowings[riddleIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.riddlesFollowings[riddleIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                riddleIndex = state.riddlesAttachments.findIndex(riddle => {
                    return riddle.id === data.itemId
                })
                if (riddleIndex > -1) {
                    attachmentIndex = state.riddlesAttachments[riddleIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.riddlesAttachments[riddleIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                //questions
                questionIndex = state.questions.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    attachmentIndex = state.questions[questionIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.questions[questionIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                questionIndex = state.questionsMine.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    attachmentIndex = state.questionsMine[questionIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.questionsMine[questionIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                questionIndex = state.questionsFollowers.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    attachmentIndex = state.questionsFollowers[questionIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.questionsFollowers[questionIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                questionIndex = state.questionsFollowings.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    attachmentIndex = state.questionsFollowings[questionIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.questionsFollowings[questionIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                questionIndex = state.questionsAttachments.findIndex(question => {
                    return question.id === data.itemId
                })
                if (questionIndex > -1) {
                    attachmentIndex = state.questionsAttachments[questionIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.questionsAttachments[questionIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                //activities
                activityIndex = state.activities.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    attachmentIndex = state.activities[activityIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.activities[activityIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                activityIndex = state.activitiesMine.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    attachmentIndex = state.activitiesMine[activityIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.activitiesMine[activityIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowers.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    attachmentIndex = state.activitiesFollowers[activityIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.activitiesFollowers[activityIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                activityIndex = state.activitiesFollowings.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    attachmentIndex = state.activitiesFollowings[activityIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.activitiesFollowings[activityIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                activityIndex = state.activitiesAttachments.findIndex(activity => {
                    return activity.id === data.itemId
                })
                if (activityIndex > -1) {
                    attachmentIndex = state.activitiesAttachments[activityIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.activitiesAttachments[activityIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                //books
                bookIndex = state.books.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    attachmentIndex = state.books[bookIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.books[bookIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                bookIndex = state.booksMine.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    attachmentIndex = state.booksMine[bookIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.booksMine[bookIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                bookIndex = state.booksFollowers.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    attachmentIndex = state.booksFollowers[bookIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.booksFollowers[bookIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                bookIndex = state.booksFollowings.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    attachmentIndex = state.booksFollowings[bookIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.booksFollowings[bookIndex].attachments.splice(attachmentIndex,1)
                    }
                }
                bookIndex = state.booksAttachments.findIndex(book => {
                    return book.id === data.itemId
                })
                if (bookIndex > -1) {
                    attachmentIndex = state.booksAttachments[bookIndex].attachments.findIndex(attachment=>{
                        return attachment.id === data.attachmentId
                    })
                    if (attachmentIndex > -1) {
                        state.booksAttachments[bookIndex].attachments.splice(attachmentIndex,1)
                    }
                }
            }
        },

    },

    actions: {

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
            if (response.data.data) {
                commit('POSTS_SUCCESS',{params: data.params, data: response.data})
                return response.data.hasOwnProperty('links') ? 
                    response.data.links.next : null
            }else {
                return 'unsuccessful'
            }
        },
        async getUserPosts({commit},data){
            commit('LOADING_START')
            let response = await HomeService.userPostsGet(data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POSTS_SUCCESS',{params: data.params, data: response.data})
                return response.data.hasOwnProperty('links') ? 
                    response.data.links.next : null
            }else {
                return 'unsuccessful'
            }
        },
        async getPostTypes({commit},data){
            commit('LOADING_START')
            let response = await HomeService.postTypesGet(data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POST_TYPES_SUCCESS',{params: data.params, data: response.data})
                return response.data.hasOwnProperty('links') ? 
                    response.data.links.next : null
            }else {
                return 'unsuccessful'
            }
        },
        async getUserPostTypes({commit},data){
            commit('LOADING_START')
            let response = await HomeService.userPostTypesGet(data)
            commit('LOADING_END')
            if (response.data.data) {
                commit('POST_TYPES_SUCCESS',{params: data.params, data: response.data})
                return response.data.hasOwnProperty('links') ? 
                    response.data.links.next : null
            }else {
                return 'unsuccessful'
            }
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
    },

    getters: {
        //posts
        getHomePosts(state){
            return state.posts.length ? state.posts : null
        },
        getHomePostsMine(state){
            return state.postsMine.length ? state.postsMine : null
        },
        getHomePostsFollowers(state){
            return state.postsFollowers.length ? state.postsFollowers : null
        },
        getHomePostsFollowings(state){
            return state.postsFollowings.length ? state.postsFollowings : null
        },
        getHomePostsAttachments(state){
            return state.postsAttachments.length ? state.postsAttachments : null
        },
        //discussions
        getHomeDiscussions(state){
            return state.discussions.length ? state.discussions : null
        },
        //reads
        getHomeReads(state){
            return state.reads.length ? state.reads : null
        },
        //questions
        getHomeQuestions(state){
            return state.questions.length ? state.questions : null
        },
        getHomeQuestionsMine(state){
            return state.questionsMine.length ? state.questionsMine : null
        },
        getHomeQuestionsFollowers(state){
            return state.questionsFollowers.length ? state.questionsFollowers : null
        },
        getHomeQuestionsFollowings(state){
            return state.questionsFollowings.length ? state.questionsFollowings : null
        },
        getHomeQuestionsAttachments(state){
            return state.questionsAttachments.length ? state.questionsAttachments : null
        },
        //riddles
        getHomeRiddles(state){
            return state.riddles.length ? state.riddles : null
        },
        getHomeRiddlesMine(state){
            return state.riddlesMine.length ? state.riddlesMine : null
        },
        getHomeRiddlesFollowers(state){
            return state.riddlesFollowers.length ? state.riddlesFollowers : null
        },
        getHomeRiddlesFollowings(state){
            return state.riddlesFollowings.length ? state.riddlesFollowings : null
        },
        getHomeRiddlesAttachments(state){
            return state.riddlesAttachments.length ? state.riddlesAttachments : null
        },
        //activities
        getHomeActivities(state){
            return state.activities.length ? state.activities : null
        },
        getHomeActivitiesMine(state){
            return state.activitiesMine.length ? state.activitiesMine : null
        },
        getHomeActivitiesFollowers(state){
            return state.activitiesFollowers.length ? state.activitiesFollowers : null
        },
        getHomeActivitiesFollowings(state){
            return state.activitiesFollowings.length ? state.activitiesFollowings : null
        },
        getHomeActivitiesAttachments(state){
            return state.activitiesAttachments.length ? state.activitiesAttachments : null
        },
        //poems
        getHomePoems(state){
            return state.poems.length ? state.poems : null
        },
        getHomePoemsMine(state){
            return state.poemsMine.length ? state.poemsMine : null
        },
        getHomePoemsFollowers(state){
            return state.poemsFollowers.length ? state.poemsFollowers : null
        },
        getHomePoemsFollowings(state){
            return state.poemsFollowings.length ? state.poemsFollowings : null
        },
        getHomePoemsAttachments(state){
            return state.poemsAttachments.length ? state.poemsAttachments : null
        },
        //books
        getHomeBooks(state){
            return state.books.length ? state.books : null
        },
        getHomeBooksMine(state){
            return state.booksMine.length ? state.booksMine : null
        },
        getHomeBooksFollowers(state){
            return state.booksFollowers.length ? state.booksFollowers : null
        },
        getHomeBooksFollowings(state){
            return state.booksFollowings.length ? state.booksFollowings : null
        },
        getHomeBooksAttachments(state){
            return state.booksAttachments.length ? state.booksAttachments : null
        },
    },

}

export default home