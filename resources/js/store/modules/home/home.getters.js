const getters = {
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
    getHomeDiscussionsMine(state){
        return state.discussionsMine.length ? state.discussionsMine : null
    },
    getHomeDiscussionsFollowers(state){
        return state.discussionsFollowers.length ? state.discussionsFollowers : null
    },
    getHomeDiscussionsFollowings(state){
        return state.discussionsFollowings.length ? state.discussionsFollowings : null
    },
    getHomeDiscussionsAttachments(state){
        return state.discussionsAttachments.length ? state.discussionsAttachments : null
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
}

export default getters