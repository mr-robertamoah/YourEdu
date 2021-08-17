import ApiService from "./api.service";

const DashboadService = {

    async createAcademicYear(data){
        try {
            let response = await ApiService.post(`api/dashboard/school/academicyear`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getDashboardAccountDetails(data){
        try {
            let response = await ApiService.get(`api/dashboard/account`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getSectionItemComments(data){
        let {nextPage, item, itemId} = data
        try {
            let response = await ApiService.get(`api/${item}/${itemId}/comments?page=${nextPage}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //users
    async fetchUsers(data){
        let {nextPage, account, accountId} = data
        try {
            let response = await ApiService.get(`api/dashboard/users?page=${nextPage}&account=${account}&accountId=${accountId}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //accounts
    async fetchAccounts(data){
        let {nextPage, account, accountId} = data
        try {
            let response = await ApiService.get(`api/dashboard/accounts?page=${nextPage}&account=${account}&accountId=${accountId}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async fetchAccountActivities(data){
        let {nextPage, account, accountId, adminId} = data
        try {
            let response = await ApiService.get(`api/dashboard/activities?page=${nextPage}&account=${account}&accountId=${accountId}&adminId=${adminId}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //admins
    async fetchAdmins(data){
        let {nextPage, account, accountId} = data
        try {
            let response = await ApiService.get(`api/dashboard/admins?page=${nextPage}&account=${account}&accountId=${accountId}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async fetchItem(data){
        let {item, itemId} = data
        try {
            let response = await ApiService.get(`api/${item}/${itemId}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getSectionItemData(data){
        try {
            let response = await ApiService.get(`api/dashboard/item/data`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getAccountRequests(main){
        let {data,nextPage} = main
        try {
            let response = await ApiService.get(`api/dashboard/requests?page=${nextPage}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async banUser(data){
        try {
            let response = await ApiService.post(`api/dashboard/banning`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async sendRequestMessage(main){
        let {formData,requestId} = main
        try {
            let response = await ApiService.post(`api/request/${requestId}/message`,formData)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getRequestMessages(main){
        let {requestId, nextPage} = main
        try {
            let response = await ApiService.get(`api/request/${requestId}/messages?page=${nextPage}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async searchAccounts(main){
        let {data, nextPage} = main
        try {
            let response = await ApiService.get(`api/request/accounts/search?page=${nextPage}`, data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async searchItems(main){
        let {data, nextPage} = main
        try {
            let response = await ApiService.get(`api/request/items/search?page=${nextPage}`, data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async addTime(data) {
        try {
            let response = await ApiService.post(`api/timer`, data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateTime(data) {
        try {
            let response = await ApiService.put(`api/timer/${data.timerId}`, data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async answerAssessment(main) {
        let {assessmentId, formData, done} = main
        try {
            let response = await ApiService.post(`api/assessment/${assessmentId}/answer/${done ? 'done' : ''}`, formData)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async markAssessment(main) {
        let {assessmentId, formData, done} = main
        try {
            let response = await ApiService.post(`api/assessment/${assessmentId}/mark/${done ? 'done' : ''}`, formData)

            return response
        } catch (error) {
            console.log(error.response);
            return error.response
        }
    },
    async getWork(main) {
        let {assessmentId, data} = main
        try {
            let response = await ApiService.get(`api/assessment/${assessmentId}/work`, data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async sendRequest(data){
        try {
            let response = await ApiService.post(`api/request/account/send`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async sendResponse(data){
        try {
            let response = await ApiService.post(`api/request/account/respond`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteRequestMessage(data){
        try {
            let response = await ApiService.delete(`api/request/message/${data.messageId}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //class
    async createClass(data){
        try {
            let response = await ApiService.post(`api/class`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteClass(data){
        try {
            let response = await ApiService.delete(`api/class/${data.postId}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateClass(data){
        try {
            let response = await ApiService.put(`api/class/${data.get('postId')}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //program
    async createProgram(data){
        try {
            let response = await ApiService.post(`api/program/create/main`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteProgram(data){
        try {
            let response = await ApiService.post(`api/program/delete/main`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateProgram(data){
        try {
            let response = await ApiService.post(`api/program/update/main`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //course
    async createCourse(data){
        try {
            let response = await ApiService.post(`api/course/main`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteCourse(data){
        try {
            let response = await ApiService.delete(`api/course/${data.courseId}/main`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateCourse(data){
        try {
            let response = await ApiService.post(`api/course/${data.get('courseId')}/main`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async createAssessment(data){
        try {
            let response = await ApiService.post(`api/assessment`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteAssessment(data){
        try {
            let response = await ApiService.delete(`api/assessment/${data.assessmentId}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateAssessment(data){
        try {
            let response = await ApiService.put(`api/assessment/${data.get('assessmentId')}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //lesson
    async createLesson(data){
        try {
            let response = await ApiService.post(`api/lesson`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteLesson(data){
        try {
            let response = await ApiService.delete(`api/lesson/${data.lessonId}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateLesson(data){
        try {
            let response = await ApiService.put(`api/lesson/${data.get('lessonId')}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //extracurriculum
    async createExtracurriculum(data){
        try {
            let response = await ApiService.post(`api/extracurriculum`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteExtracurriculum(data){
        try {
            let response = await ApiService.delete(`api/extracurriculum/${data.extracurriculumId}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateExtracurriculum(data){
        try {
            let response = await ApiService.put(`api/extracurriculum/${data.get('extracurriculumId')}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //extracurriculum
    async createCollaboration(data){
        try {
            let response = await ApiService.post(`api/collaboration/create`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteCollaboration(data){
        try {
            let response = await ApiService.post(`api/collaboration/delete`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateCollaboration(data){
        try {
            let response = await ApiService.post(`api/collaboration/update`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //
    async createAccountAttachments(data){
        try {
            let response = await ApiService.post(`api/dashboard/account/attach`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteAccountAttachments(data){
        try {
            let response = await ApiService.post(`api/dashboard/account/unattach`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getAccountSpecificItems(main){
        let {nextPage, data} = main
        try {
            let response = 
                await ApiService.get(`api/dashboard/account/specific/items?page=${nextPage}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getAccountItems(main){ //to help view more of an item
        let {nextPage, data} = main
        try {
            let response = 
                await ApiService.get(`api/dashboard/account/items?page=${nextPage}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async getItemDetails(data){ //to help view details of an item in a modal
        let {type, id} = data
        try {
            let response = 
                await ApiService.get(`api/dashboard/${type}/${id}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
}

export default DashboadService