import PostAttachment from '../components/PostAttachment.vue';
import AttachmentBadge from '../components/AttachmentBadge.vue';
import { default as _ } from 'lodash';

export default {
    components: {
        PostAttachment,
        AttachmentBadge,
    },
    methods: {
        getOnlyNewAttachments() {
            if (!this.edit) {
                return this.data.attachments
            }

            let index,
                attachments = _.clone(this.data.attachments)

            this.data.mainAttachments.forEach(mainAttachment => {

                index = attachments.indexOf(mainAttachment)
                if (index > -1) {
                    attachments.splice(index, 1)
                }
            })

            return attachments
        },
        clickedRemoveAttachment(data){
            let index = findIndex(this.data.attachments, data)

            if (index > -1) {
                this.data.attachments.splice(index,1)
            }

            this.addToRemovedAttachments(data)
        },
        isNotPartOfMainAttachments(data) {
            return ! this.isPartOfMainAttachments(data)
        },
        isPartOfMainAttachments(data) {
            let index = findIndex(this.data.mainAttachments, data)
            
            if (index > -1) {
                return false
            }

            return true
        },
        findIndex(attachments, item) {
            return attachments.findIndex(attachment=>{
                return attachment.type === item.type &&
                    attachment.data.id ===  item.data.id
            })
        },
        attachmentSelected(data){
            let index = this.data.attachments.findIndex(attachment=>{
                return attachment.type === data.type &&
                    attachment.data.id ===  data.data.id
            })
            if (index === -1) {
                this.data.attachments.unshift(data)
            }
        },
        clickedUndoRemoveAttachment(data) {
            this.data.removedAttachments.splice(this.data.removedAttachments.indexOf(data), 1)

            this.data.attachments.unshift(data)
        },
        addToRemovedAttachments(data) {
            if (!this.edit) {
                return
            }

            if (this.isNotPartOfMainAttachments(data)) {
                return
            }

            this.data.removedAttachments.unshift(data)
        },
    },
}