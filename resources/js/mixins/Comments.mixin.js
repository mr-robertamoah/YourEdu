export default {
    data() {
        return {
            showAddComment: false,
        }
    },
    computed: {
        computedComments(){
            return this[this.computedItem.item] && 
                this[this.computedItem.item].comments.length > 0 ?
                _.take(this[this.computedItem.item].comments,2) : null
        },
    },
}