import { UserService } from "../../services/user.service";

const miscellaneous = {
    namespaced: true,
    state: () => ({
        loadingContent: false,
        secretQuestions: [],
        secretQuestionAnswers: [],
    }),
    mutations: {

        LOAD_CONTENT_COMPLETE(state){
            state.loadingContent = false
        },    
        LOAD_CONTENT(state){
            state.loadingContent = true
        },
        SET_SECRET_QUESTIONS(state,data){
            let q = data.questions
            state.secretQuestions = q.map(el=>el.question)

            state.secretQuestionAnswers = data.questions

        }
    },
    actions: {

        async getSecret({commit}){
            commit('LOAD_CONTENT')

            let response = await UserService.getSecretQuestions()
            
            if (response.data.message != 'unsuccessful') {
                commit('SET_SECRET_QUESTIONS',response.data)
            }

            commit('LOAD_CONTENT_COMPLETE')

        },
    },
    getters: {
        getLoadingContent(state){
            return state.loadingContent
        },
        getSecretQuestions(state){
            return state.secretQuestions.length > 0 ? 
            state.secretQuestions : []
        },
        getSecretQuestionAnswers(state){
            return state.secretQuestionAnswers.length > 0 ? 
            state.secretQuestionAnswers : []
        },
    }
}

export default miscellaneous