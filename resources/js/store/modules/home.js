import mutations from "./home/home.mutations";
import actions from "./home/home.actions";
import getters from "./home/home.getters";

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
        lessons: [],
        lessonsMine: [],
        lessonsFollowers: [],
        lessonsFollowings: [],
        lessonsAttachments: [],
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
        assessments: [],
        assessmentsMine: [],
        assessmentsFollowers: [],
        assessmentsFollowings: [],
        loading: false,
        vistedSections: []
    }),

    mutations: mutations,

    actions: actions,

    getters: getters,

}

export default home