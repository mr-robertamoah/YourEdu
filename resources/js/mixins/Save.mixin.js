export default {
    data() {
        return {
            saveData: {
                isSaved: false,
                mySave: null,
                saves: 0,
            }
        }
    },
    watch: {
        "saveData.saves"(newValue){
            if (!newValue) {
                this.saveData.mySave = null
                this.saveData.isSaved = false
            }
        },
    },
    methods: {
        async save(who){
            this.showProfiles = false
            this.loading = true

            let data = {
                item: 'discussion',
                itemId: this.discussion.id,
                owner: 'discussion',
                ownerId: this.discussion.id,
            },
                response = null,
                state = ''

            data.where = this.$route.name
            if (who) {
                data.account = who.account
                data.accountId = who.accountId
                state = 'saving'

                this.saveData.isSaved = true
                this.saveData.saves += 1
                response = await this['profile/createSave'](data)
            } else {
                data.saveId = this.mySave.id
                state = 'unsaving'

                this.saveData.saves -= 1
                this.saveData.isSaved = false
                response = await this['profile/deleteSave'](data)
            }

            this.loading = false
            if (response.status) {
                this.alertSuccess = true
                this.alertMessage = `${state} successful`
                
                this.setMySave()
                return
            }

            this.alertDanger = true
            this.alertMessage = `${state} unsuccessful`

            if (this.saveData.isSaved) {
                this.saveData.saves -= 1
            }

            if (! this.saveData.isSaved) {
                this.saveData.saves += 1
            }

            this.saveData.isSaved = !this.saveData.isSaved
        },
        setMySave(){
            if (! this.getUser || ! this.computedItemable) {
                this.saveData.mySave = null
                return
            }
            
            let index = this.computedItemable.saves.findIndex(save=>{
                return save.user_id === this.getUser.id
            })

            if (index > -1) {
                this.mySave = this.computedItemable.saves[index]
                return
            }

            this.saveData.mySave = null
        },
    },
}