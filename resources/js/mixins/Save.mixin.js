import { useRoute } from "vue-router"
import { mapActions } from "vuex"

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
        "saveData.mySave"(newValue) {
            if (newValue) {
                this.saveData.isSaved = true
                return
            }

            this.saveData.isSaved = false
        }
    },
    methods: {
        ...mapActions([
            'profile/createSave', 'profile/deleteSave',
            'profile/newSave', 'profile/removeSave',
            'home/newSave', 'home/removeSave',
        ]),
        listenForSaves() {
            
            // Echo.channel(`youredu.${this.computedItem.item}.${this.computedItem.itemId}`)
            //     .listen('.newSave', data=>{
            //         this[`${this.$route.name}/newSave`]({
            //             ...this.computedItem,
            //             save: data.save
            //         })
            //     })
            //     .listen('.deleteSave', data=>{
            //         this[`${this.$route.name}/removeSave`]({
            //             ...this.computedItem,
            //             saveId: data.saveId
            //         })
            //     })
        },
        async save(who){
            this.showProfiles = false
            this.loading = true

            let data = {
                item: this.computedItem.item,
                itemId: this.computedItem.itemId,
            },
                response = null,
                state = ''
            
            if (this.schoolAdmin) {
                data.adminId = this.schoolAdmin.id
            }
            
            data.where = useRoute().name
            if (who) {
                data.account = who.account
                data.accountId = who.accountId
                state = 'saving'

                this.saveData.saves += 1
                response = await this['profile/createSave'](data)
            } else {
                data.saveId = this.mySave.id
                state = 'unsaving'

                this.saveData.saves -= 1
                response = await this['profile/deleteSave'](data)
            }

            this.loading = false
            if (response.status) {
                this.alertSuccess = true
                this.alertMessage = `${state} successful`
                
                this.$emit(
                    who ? 'saveSuccessful' : 'unsaveSuccessful', { //commentSaveSuccessful commentUnsaveSuccessful
                    ...this.computedItem,
                    save: response.save,
                    saveId: data.saveId
                })
                this.setMySave()
                return
            }

            this.alertDanger = true
            this.alertMessage = `${state} unsuccessful`

            if (who) {
                this.saveData.saves -= 1
            }

            if (! who) {
                this.saveData.saves += 1
            }
        },
        clickedSave() {
            this.showProfilesText = 'save as'
            this.showProfilesAction = 'save'
            this.profilesAppear()
        },
        setMySave(){
            if (! this.getUser || ! this.computedItemable) {
                this.saveData.mySave = null
                return
            }
            
            let index = this.computedItemable.saves.findIndex(save=>{
                return save.userId == this.getUser.id
            })

            if (index > -1) {
                this.mySave = this.computedItemable.saves[index]
                return
            }

            this.saveData.mySave = null
        },
    },
}