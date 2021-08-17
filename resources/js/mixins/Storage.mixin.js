export default {
    methods: {
        setItem(key, items) {
            window.YoureduStorage.set(key, items)
        },
        getItem(key) {
            return window.YoureduStorage.get(key)
        },
        removeItem(key) {
            return window.YoureduStorage.remove(key)
        },
    },
}