import { mapActions } from "vuex"
import { default as _ } from 'lodash';
import { useRoute } from "vue-router";

export default {
    data() {
        return {
            showAddComment: false,
        }
    },
    computed: {
        computedComments(){
            return this[this.computedItem.item]?.comments?.length > 0 ?
                _.take(this[this.computedItem.item].comments,2) : null
        },
    },
    methods: {
        ...mapActions([
            'home/replaceComment','home/removeComment',
            'profile/replaceComment','profile/removeComment',
            'home/newomment','profile/newComment', 'profile/deleteComment',
        ]),
        listenForComments() {
            const route = useRoute()

            Echo.channel(`youredu.${this.computedItem.item}.${this.computedItem.itemId}`)
                .listen('.updateComment', commentData=>{
                    this[`${route.name}/replaceComment`](commentData)
                })
                .listen('.deleteComment', data=>{
                    this[`${route.name}/removeComment`]({
                        ...data,
                        ...this.computedItem
                    })
                })
                .listen('.newComment', comment=>{
                    this[`${route.name}/newComment`](comment)
                })
        }
    },
}