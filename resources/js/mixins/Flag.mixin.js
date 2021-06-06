import FlagCover from '../components/FlagCover';

export default {
    components: {
        FlagCover,
    },
    data() {
        return {
            showFlagReason: false,
            flagData: {
                isFlagged: false,
                myFlag: null,
                flagReason: '',
                flagTitle: '',
                flagRed: '',
            }
        }
    },
    watch: {
        computedItemable(newValue){
            if (newValue) {
                
                this.setMyFlag()
            }
        },
        getUser(newValue){
            if (newValue) {
                
                this.setMyFlag()
            }
        },
        "flagData.isFlagged"(newValue){
            if (newValue) {
                this.flagData.flagTitle = 'unflag this discussion'
                this.flagData.flagRed = 'red'
            } else {
                this.flagData.flagTitle = 'flag this discussion'
                this.flagData.flagRed = ''
            }
        },
        "flagData.myFlag"(newValue){
            if (newValue) {
                this.flagData.isFlagged = true
                return
            }
            
            this.flagData.isFlagged = false
        },
    },
    created () {
        this.setMyFlag()

        if (this.flagData.myFlag) {
            this.flagData.isFlagged = true
        }
    },
    methods: {
        reasonGiven(data){
            this.showFlagReason = false
            this.flagData.flagReason = data
            this.profilesAppear()
        },
        continueFlagProcess(){
            this.flagData.flagReason = null
            this.showFlagReason = false
            this.profilesAppear()
        },
        cancelFlagProcess(){
            this.flagData.flagReason = ''
            this.showFlagReason = false
        },
        clickedFlag(){
            if (this.disabled) {
                return
            }
            if(this.computedBanned) return

            if (this.flagData.isFlagged) {
                this.flag(null)
                return
            }
            this.showProfilesText = 'flag as'
            this.showProfilesAction = 'flag'

            if (!this.getUser) {
                this.$emit('askLoginRegister','discussionsingle')
                return
            }
            
            if (!this.getProfiles.length) {
                this.smallModalInfo= true
                this.smallModalDelete = false
                this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can flag.'
                this.showSmallModal = true
                setTimeout(() => {
                    this.clearSmallModal()
                }, 4000);

                return
            }
            
            this.showFlagReason = true
        },
        async flag(who){
            this.loading = true

            let data = {}
            let response = null

            data.where = this.$route.name
            data.itemId = this.computedItem.itemId
            data.item = this.computedItem.item

            if (who) {
                data.account = who.account
                data.accountId = who.accountId
                data.reason = this.flagData.flagReason

                response = await this['profile/createFlag'](data)
            } else {
                data.flagId = this.flagData.myFlag.id

                response = await this['profile/deleteFlag'](data)
            }

            this.loading =false
            if (response.status) {
                this.alertSuccess = true
                if (who) {
                    this.alertMessage = 'successfully flagged'
                    this.$emit('postDeleteSuccess',{postId: data.itemId,type:'discussion'})
                } else {
                    this.alertMessage = 'successfully unflagged'
                    this.$emit('postUnflaggedSuccess', {
                        flag: response.flag,
                        answerId: this.discussion.id
                    })
                }
            } else {
                this.flagData.isFlagged = !this.flagData.isFlagged

                this.alertDanger = true
                this.alertMessage = 'oops! something unfortunate happened. try again later 😏'
            }
            
            this.setMyFlag()

            this.flagData.flagReason = ''
            this.smallModalData = null
            this.clearSmallModal()
        },
        setMyFlag(){
            if (! this.getUser || ! this.computedItemable) {
                this.flagData.myFlag = null
                return
            }
            
            let index = this.computedItemable.flags.findIndex(flag=>{
                return flag.user_id === this.getUser.id
            })

            if (index > -1) {
                this.flagData.myFlag = this.computedItemable.flags[index]
                return
            }

            this.flagData.myFlag = null
        },
    },
}