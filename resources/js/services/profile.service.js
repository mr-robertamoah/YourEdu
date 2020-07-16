import ApiService from "./api.service";

const ProfileService = {
    async profileGet(data){

        try {
            let {account, accountId} = data
            let response = await ApiService.get(`/api/profile/${account}/${accountId}`)
            // let response = await ApiService.get(`api/profile/facilitator/15`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    
    async profileUpdate(mainData){

        try {
            let {profile_id, data} = mainData
            let response = await ApiService.post(`/api/profile/${profile_id}/update`,data)
            // let response = await ApiService.get(`api/profile/facilitator/15`)
    
            return response
        } catch (error) {
            return error.response
        }
    }
}

export {ProfileService}