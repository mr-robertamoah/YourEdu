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
                this.likeData.likeTitle = 'unlike this discussion'
            } else {
                this.likeData.likeTitle = 'like this discussion'
            }
        },
        "likeData.likes"(newValue){
            if (! newValue) {
                this.likeData.myLike = null
                this.likeData.isLiked = false
            }
        },
    },
    methods: {
        async clickedLike(data){
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
            
            if (!this.getProfiles.length) {
                this.smallModalInfo= true
                this.smallModalDelete = false
                this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can like.'
                this.showSmallModal = true
                setTimeout(() => {
                    this.clearSmallModal()
                }, 4000);
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

            let newData = {
                likeId: this.likeData.myLike.id,
                item: this.computedItem.item,
                itemId: this.computedItem.itemId,
                owner: this.computedOwner.account,
                ownerId: this.computedOwner.accountId,
            }

            newData.where = this.$route.name

            let response = await this['profile/deleteLike'](newData)

            if (response === 'unsuccessful') {
                this.likeData.isLiked = true
                this.likeData.likes += 1
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

            data.where = this.$route.name

            let response = await this['profile/createLike'](data)

            if (response === 'unsuccessful') {
                this.likeData.isLiked = false
                this.likeData.likes -= 1
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
                return like.user_id == this.getUser.id
            })

            if (index > -1) {
                this.likeData.myLike = this.computedItemable.likes[index]
                return
            }

            this.likeData.myLike = null
        },
    },
}