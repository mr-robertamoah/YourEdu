import { mapGetters } from 'vuex'
import ProfileBar from '../components/profile/ProfileBar.vue'

export default {
    components: {
        ProfileBar,
    },
    data() {
        return {
            showProfilesAction: '',
            showProfilesText: '',
            showOnlyProfiles: false,
            showProfiles: false,
        }
    },
    watch: {
        showProfiles(newValue){
            if (newValue) {
                setTimeout(() => {
                    this.clearProfilesData()
                }, 4000);
            }
        },
    },
    computed: {
        ...mapGetters(['getProfiles']),
        computedProfiles(){
            return this.getProfiles ? this.getProfiles : []
        },
    },
    methods: {
        profilesAppear(){
            this.showProfiles = true
        },
        clearProfilesData() {
            this.showProfiles = false
            this.showOnlyProfiles = false
            // this.showProfilesAction = ''
            this.showProfilesText = ''
        }
    },
}