import { TokenService } from "./token.service";
import store from '../store/index'

const ApiService = {

    init(baseURL){
        axios.defaults.baseURL = baseURL
    },

    setHeaderAuth() {
        axios.defaults.headers.common['Authorization'] = `Bearer ${TokenService.getToken()}`
    },

    removeHeaderAuth(){
        axios.defaults.headers.common['Authorization'] = ''
    },

    get(resource,params = null){
        return axios.get(resource,{params})
    },

    post(resource,data, hasMultipart = false){
        if (hasMultipart) {
            return axios.post(resource,data, {
                headers:{
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: e=>{
                    if (e.lengthComputable) {
                        window.progress = (e.loaded/e.total) * 100
                    }
                    window.uploadEvent = e
                }
            })
        } else {
            return axios.post(resource,data)
        }
        
    },

    put(resource,data){
        return axios.put(resource,data)
    },

    delete(resource, data = null){
        return axios.delete(resource, {data})
    },

    custom(data){
        return axios(data)
    },


    _401Interceptor : null,

    mount401Interceptor(){
        axios.interceptors.response.use(response=>response,
            (error)=>{
                if (error.response.status == 401) {
                    if (error.config.url.includes('/api/login') ||
                        error.config.url.includes('/api/register')) {
                        // store.dispatch('logout')
                        TokenService.removeToken()
                        throw error
                    } 

                    if (error.config.url.includes('/api/refresh')) {
                        store.dispatch('logout')
                        throw error
                    } 
                    // else {
                    //     try {
                    //         store.dispatch('refreshToken')
                    //         return this.custom({
                    //             method: error.config.method,
                    //             url: error.config.url,
                    //             data: error.config.data
                    //         })
                    //     } catch (e) {
                    //         throw error
                    //     }
                    // }
                }

                throw error
            })
    },
    
    unmount401Interceptor(){
        axios.interceptors.response.eject(this._401Interceptor)
    }

}

export default ApiService