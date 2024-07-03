import { useRoute } from "vue-router"
import { mapActions } from "vuex"

export default {
    data() {
        return {
            likeData: {
                likeTitle: '',
                isLiked: false,
                myLike: null,
                likes: 0,
            }
        }
    },
    watch: {
        "likeData.isLiked"(newValue){
            if (newValue) {
                this.likeData.likeTitle = `unlike this ${this.computedItem.item}`
            } else {
                this.likeData.likeTitle = `like this ${this.computedItem.item}`
            }
        },
        "likeData.myLike"(newValue){
            if (newValue) {
                this.likeData.isLiked = true
                return
            }
            
            this.likeData.isLiked = false
        },
    },
    computed: {
        computedRoute() {
            return useRoute()
        },
    },
    methods: {
        ...mapActions([
            'profile/createLike', 'profile/deleteLike',
            'profile/newLike', 'profile/removeLike',
            'home/newLike', 'home/removeLike',
        ]),
        listenForLikes() {
            
            Echo.channel(`youredu.${this.computedItem.item}.${this.computedItem.itemId}`)
                .listen('.newLike', data=>{
                    this[`${this.computedRoute.name}/newLike`]({
                        ...this.computedItem,
                        like: data.like
                    })
                })
                .listen('.deleteLike', data=>{
                    this[`${this.computedRoute.name}/removeLike`]({
                        ...this.computedItem,
                        likeId: data.likeId
                    })
                })
        },
        async clickedLike() {
            if (this.disabled) {
                return
            }
            
            if(this.computedBanned) {
                return
            }

            if (!this.getUser) {
                this.$emit('askLoginRegister',this.computedItem.item)
                return
            }
            
            if (! this.getProfiles.length) {
                this.issueCustomMessage({
                    type: 'info',
                    message: 'you must have an account (eg. learner, parent, etc) before you can like.',
                })

                this.clearSmallModal(false)
                return
            }
            
            if (! this.likeData.isLiked) {
                this.showProfilesText = 'like as'
                this.showProfilesAction = 'like'
                this.profilesAppear()
                return
            }
            
            if (! this.likeData.myLike || 
                ! this.likeData.myLike.hasOwnProperty('id')) {
                return
            }
            
            this.deleteLike()
        },
        async deleteLike() {
            this.likeData.likes -= 1
            this.likeData.isLiked = false

            let data = {
                likeId: this.likeData.myLike.id,
                item: this.computedItem.item,
                itemId: this.computedItem.itemId,
                owner: this.computedOwner.account,
                ownerId: this.computedOwner.accountId,
            }

            if (this.schoolAdmin) {
                data.adminId = this.schoolAdmin.id
            }

            data.where = this.computedRoute.name

            let response = await this['profile/deleteLike'](data)

            if (response === 'unsuccessful') {
                this.likeData.isLiked = true
                this.likeData.likes += 1

                this.$emit('unlikeSuccessful', {...this.computedItem, likeId: data.like}) //commentUnlikeSuccessful
                return
            }

            this.setMyLike()
        },
        async like(who){
            this.showProfiles = false

            this.likeData.isLiked = true
            this.likeData.likes += 1

            let data = {
                item: this.computedItem.item,
                itemId: this.computedItem.itemId,
                account: who.account,
                accountId: who.accountId,
                owner: this.computedOwner.account,
                ownerId: this.computedOwner.accountId,
            }

            if (this.schoolAdmin) {
                data.adminId = this.schoolAdmin.id
            }
            
            data.where = this.computedRoute.name

            let response = await this['profile/createLike'](data)

            if (response === 'unsuccessful') {
                this.likeData.isLiked = false
                this.likeData.likes -= 1

                this.$emit('likeSuccessful', {...this.computedItem, like: response.like}) //commentLikeSuccessful
                return
            }

            this.setMyLike()
        },
        setMyLike() {
            if (! this.getUser || ! this.computedItemable) {
                this.likeData.myLike = null
                return
            }

            let index = this.computedItemable.likes.findIndex(like=>{
                return like.userId == this.getUser.id
            })

            if (index > -1) {
                this.likeData.myLike = this.computedItemable.likes[index]
                return
            }

            this.likeData.myLike = null
        },
    },
}