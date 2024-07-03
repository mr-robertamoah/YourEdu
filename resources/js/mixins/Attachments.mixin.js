import {strings} from '../services/helpers'
import PostAttachment from '../components/PostAttachment.vue'
import AttachmentBadge from '../components/AttachmentBadge.vue'
import { mapActions } from 'vuex'
import { useRoute } from 'vue-router'

export default {
    components: {
        PostAttachment,
        AttachmentBadge,
    },
    data() {
        return {
            attachmentData: {
                isAttached: false, //means i have attached and it goes for likes, etc
                myAttachments: null,
                postAttachments: [],
                attachable: null,
                showAttach: false,
                selectedAttachment: null
            },
        }
    },
    watch: {
        "attachmentData.postAttachments"(newValue){
            if (!newValue || ! newValue.length) {
                this.attachmentData.myAttachments = null
                this.attachmentData.isAttached = false
            }
        },
        "attachmentData.myAttachments"(newValue) {
            if (! newValue) {
                return
            }
            
            if (! newValue.length) {
                return
            }

            this.attachmentData.isAttached = true
        },
    },
    computed: {
        computedRouteName() {
            return useRoute().name
        },
    },
    methods: {
        ...mapActions([
            'profile/createAttachment', 'profile/deleteAttachment',
            'profile/newAttachment', 'profile/removeAttachment',
            'home/newAttachment', 'home/removeAttachment',
        ]),
        listenForAttachments() {
            
            Echo.channel(`youredu.${this.computedItem.item}.${this.computedItem.itemId}`)
                .listen('.newAttachment', data=>{
                    this[`${this.computedRouteName.name}/newAttachment`]({
                        ...this.computedItem,
                        attachment: data.attachment
                    })
                })
                .listen('.deleteAttachment', data=>{
                    this[`${this.computedRouteName.name}/removeAttachment`]({
                        ...this.computedItem,
                        attachmentId: data.attachmentId
                    })
                })
        },
        setMyAttachment() {
            if (!this.getUser) {
                return 
            }
    
            if (this.computedItemable && this.computedItemable.hasOwnProperty('attachments')){
                return
            }
    
            this.attachmentData.myAttachments = this.computedItemable.attachments
                .filter(attachment => {
                    return attachment.user_id == this.getUser.id
                }).map(attachment=>{
                    return {
                        id: attachment.id,
                        with_type: strings.getAccount(attachment.attachedwith_type),
                        with_id: attachment.attachedwith_id,
                        with: attachment.name,
                    }
                })
    
            this.attachmentData.postAttachments = this.computedItemable.attachments.map(attach=>{
                return {
                    data: {name: attach.name},
                    type: strings.getAccount(attach.attachedwith_type)
                }
            })
        },
        clickedUnattach(attachment){
            this.attachmentData.showAttach = false
            this.attachmentData.selectedAttachment = attachment
            this.attach(null)
        },
        attachmentClicked(data){
            this.attachmentData.showAttach = false
            this.attachmentData.attachable = data
            this.showProfilesAction = 'attach'
            this.showProfilesText = 'attach as'
            this.profilesAppear()
        },
        async attach(who){

            this.loading = true
            let data = {},
                response = null,
                state = ''

            data.where = this.computedRouteName.name
            data.item = this.computedItem.item
            data.itemId = this.computedItem.itemId
            data.postType = this.computedItem.postType

            if (who) {
                state = 'attachment'
                data.type = this.attachmentData.attachable.item
                data.typeId = this.attachmentData.attachable.itemId
                data.account = who.account
                data.accountId = who.accountId
                data.note = this.attachmentData.attachable.note
                response = await this['profile/createAttachment'](data)
            } else {
                state = 'unattachment'
                data.attachmentId = this.attachmentData.selectedAttachment.id
                response = await this['profile/deleteAttachment'](data)
            }
            
            this.loading = false

            if (! response.status) {
                this.alertDanger = true
                this.alertMessage = `${state} unsuccessful 😞`
                return
            }

            this.alertSuccess = true
            this.alertMessage = `${state} successful 👍`
            this.setMyAttachment()
        },
    },
}