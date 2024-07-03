import { mapActions, mapGetters } from 'vuex';
import FlagCover from '../components/FlagCover.vue';
import { useRoute } from 'vue-router';

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
    computed: {
        ...mapGetters(['isAdult']),
        computedRoute() {
            return useRoute()
        },
    },
    methods: {
        ...mapActions([
            'profile/createFlag', 'profile/deleteFlag',
            'profile/newFlag', 'profile/removeFlag',
            'home/newFlag', 'home/removeFlag',
        ]),
        listenForFlags() {
            
            Echo.channel(`youredu.${this.computedItem.item}.${this.computedItem.itemId}`)
                .listen('.newFlag', data=>{
                    this[`${this.computedRoute.name}/newFlag`]({
                        ...this.computedItem,
                        flag: data.flag
                    })
                })
                .listen('.deleteFlag', data => {
                    
                    if (this.computedItem.item === 'comment') {
                        
                        return
                    }
                    this[`${this.computedRoute.name}/removeFlag`]({
                        ...this.computedItem,
                        flagId: data.flagId
                    })
                })
        },
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

            if (!this.getUser) {
                this.$emit('askLoginRegister', this.computedIem.item)
                return
            }
            
            if (!this.getProfiles.length) {
                this.issueCustomMessage({
                    type: 'info',
                    message: 'you must have an account (eg. learner, parent, etc) before you can flag.',
                })
                
                this.clearSmallModal(false)
                return
            }
            
            this.showProfilesText = 'flag as'
            this.showProfilesAction = 'flag'
            this.showFlagReason = true
        },
        async flag(who){
            this.loading = true

            let data = {}
            let response = null

            data.where = this.computedRoute.name
            data.itemId = this.computedItem.itemId
            data.item = this.computedItem.item
            
            if (this.schoolAdmin) {
                data.adminId = this.schoolAdmin.id
            }

            if (who) {
                data.account = who.account
                data.accountId = who.accountId
                data.reason = this.flagData.flagReason

                response = await this['profile/createFlag'](data)
            } 
            
            if (this.flagData.myFlag?.flagId && !this.isAdult) {
                this.alertDanger = true
                this.alertMessage = "sorry 😞, you cannot unflag because it was flagged by a parent or school"
                return
            }

            if (! who && this.flagData.myFlag) {

                data.flagId = this.flagData.myFlag.id

                response = await this['profile/deleteFlag'](data)
            }

            this.loading = false
            this.flagData.flagReason = ''

            if (! response.status) {

                this.alertDanger = true
                this.alertMessage = 'oops! something unfortunate happened. try again later 😏'
                return
            }

            this.clearSmallModal()
            this.setMyFlag()
            this.alertSuccess = true

            if (who) {
                this.alertMessage = 'successfully flagged'
                this.$emit('flaggedSuccessful', this.computedItem) //postDeleteSuccess
                return
            }

            this.alertMessage = 'successfully unflagged'
            this.$emit('unflaggedSuccessful', { //postUnflaggedSuccess
                flag: response.flag,
                ...this.computedItem
            })
        },
        setMyFlag() {
            if (! this.getUser || ! this.computedItemable) {
                this.flagData.myFlag = null
                return
            }
            
            let index = this.computedItemable.flags.findIndex(flag=>{
                return flag.userId == this.getUser.id
            })

            if (index > -1) {
                this.flagData.myFlag = this.computedItemable.flags[index]
                return
            }

            this.flagData.myFlag = null
        },
    },
}