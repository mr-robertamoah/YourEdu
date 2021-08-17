
class StorageService {
    types = ['localStorage', 'sessionStorage']
    currentType = null
    storage = null

    constructor(type = 'localStorage') {
        if (this.types.includes(type)) {
            
            this.currentType = type
        } else {

            this.currentType = 'localStorage'
        }

        this.initiateStorage()
    }

    initiateStorage() {
        this.storage = window[this.currentType]
    }

    set(key, value, stringify = true){

        if (stringify) {
            value = JSON.stringify(value)
        }

        this.storage.setItem(key, value)
    }
    
    get(key, parse = true){

        if (parse) {
            return JSON.parse(this.storage.getItem(key))
        }

        return this.storage.getItem(key)
    }
    
    remove(key){
        this.storage.removeItem(key)
    }
}

export default StorageService