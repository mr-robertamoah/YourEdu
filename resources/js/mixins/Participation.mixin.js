import { useRoute } from 'vue-router'
import {mapActions} from 'vuex'

export default {
    data() {
        return {
            removedParticipant: null,
            joinOrInvitationType: '',
        }
    },
    computed: {
        computedPendingParticipant() {
            return this.getUser && 
                this.computedItemable.pendingParticipants.findIndex(pending=>{
                    return pending.userId === this.getUser.id
                }) > -1
        },
        computedRouteName() {
            return useRoute().name
        },
    },
    methods: {
        ...mapActions([
            'profile/joinItem', 'profile/inviteAccount',
            'profile/newItemParticipant','profile/removeItemParticipant',
            'home/newItemParticipant','home/removeItemParticipant',
            'profile/updateItemParticipant','home/updateItemParticipant',
            'profile/newItemPendingParticipant',
            'home/newItemPendingParticipant',
            'profile/removeItemPendingParticipant',
            'home/removeItemPendingParticipant',
        ]),
        doneRemovingParticipant() {
            this.removedParticipant = null
        },
        listenForParticipation() {
            Echo.channel(`youredu.${this.computedItem.item}.${this.computedItem.itemId}`)
                .listen('.newParticipant', data=>{
                    
                    this[`${this.computedRouteName}/newItemParticipant`]({
                        ...data,
                        ...this.computedItem
                    })
                    
                    this.alertSuccess = true
                    this.alertMessage = 'a new participant just joined this discussion'
                })
                .listen('.updateParticipant', data=>{
                    
                    this[`${this.computedRouteName}/updateItemParticipant`]({
                        ...data,
                        ...this.computedItem
                    })

                    if (this.getUser && data.participant.userId === this.getUser.id) {
                        if (data.participant.state === 'RESTRICTED' ||
                            data.participant.state === 'BANNED') {
                            this.showDiscussionInfo = false
                            this.showDiscussionEdit = false
                            this.alertDanger = true
                            this.alertMessage = `you have been ${data.participant.state.toLowerCase()}`
                        }
                    }                        
                })
                .listen('.removeParticipant', data=>{
                    
                    this[`${this.computedRouteName}/removeItemParticipant`]({
                        ...data,
                        ...this.computedItem
                    })
                    
                    if (this.getUser && data.userId === this.getUser.id) {
                        this.showDiscussionInfo = false
                        this.showDiscussionEdit = false
                        this.alertDanger = true
                        this.alertMessage = `you have been removed`
                    }    
                })
                .listen('.newPendingParticipant', data=>{
                    if (this.computedAdmin) {
                        this.alertSuccess = true
                        this.alertMessage = 'new join request received'
                    }

                    this[`${this.computedRouteName}/newItemPendingParticipant`]({
                        ...data,
                        ...this.computedItem
                    })
                })
                .listen('.removePendingParticipant', data=>{
                    
                    this[`${this.computedRouteName}/removeItemPendingParticipant`]({
                        ...data,
                        ...this.computedItem
                    })
                })
        },
        async join(account) {
            let response,
                data = {
                    account: account.account,
                    accountId: account.accountId,
                    type: this.joinOrInvitationType
                }

            this.loading = true

            response = await this['profile/joinItem']({
                computedItem: this.computedItem, 
                item: this.computedItemable,
                data
            })

            this.loading = false
            this.joinOrInvitationType = ''
            
            if (! response.status) {
                this.issueDangerAlertForResponse(response, "oops 😕! something happened. please try again later")
                return
            }

            this.alertSuccess = true

            if (this.computedItemable.type === 'PRIVATE') {
                this.alertMessage = `you have successfully requested to participate in this ${this.computedItem.item}. have fun as you wait for owners response 😏.`
                return
            }

            this.alertMessage = `you have been successfully added to ${this.computedItem.item}. you can now participate 😎.`
        },
        async invite(account){
            this.loading = true
            let response,
                data = {
                    account: account.account,
                    accountId: account.accountId,
                    type: this.joinOrInvitationType
                }

            response = await this['profile/inviteAccount']({
                item: this.computedItemable, 
                computedItem: this.computedItem, 
                data
            })

            this.loading = false
            this.joinOrInvitationType = ''
            if (response.status) {
                this.alertSuccess = true
                this.alertMessage = `invitation request to ${account.name} was successfully sent`

                this.removedParticipant = account
                return
            }

            this.issueDangerAlertForResponse(response, `invitation request to ${account.name} was unsuccessfully`)
        },
        clickedParticpantAction(data) {
            if (data.action === 'invite') {
                this.invite(data.account)
            }
        },
    },
}