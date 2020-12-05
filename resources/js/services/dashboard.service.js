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
        let {params, nextPage} = main
        try {
            let response = await ApiService.get(`api/request/accounts/search?page=${nextPage}&${params}`)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async sendRequest(data){
        try {
            let response = await ApiService.post(`api/request/accounts/send`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteRequestMessage(data){
        try {
            let response = await ApiService.post(`api/request/message/delete`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    //class
    async createClass(data){
        try {
            let response = await ApiService.post(`api/class/create`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async deleteClass(data){
        try {
            let response = await ApiService.post(`api/class/delete`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
    async updateClass(data){
        try {
            let response = await ApiService.post(`api/class/update`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
}

export default DashboadService