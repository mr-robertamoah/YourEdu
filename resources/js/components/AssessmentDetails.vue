<template>
    <div class="w-full min-h-90vh p-2">
        <div class="p-1 mb-4">
            <div class="text-xs text-gray-300">choose what to see</div>
            <div class="flex flex-nowrap overflow-y-hidden overflow-x-auto">
                <radio-input
                    :label="'all'"
                    :radioValue="'all'"
                    :name="'filter'"
                    v-model="filter"
                ></radio-input>
                <radio-input
                    :label="'participants'"
                    :radioValue="'participants'"
                    :name="'filter'"
                    v-model="filter"
                ></radio-input>
                <radio-input
                    :label="'markers'"
                    :radioValue="'markers'"
                    :name="'filter'"
                    v-model="filter"
                ></radio-input>
            </div>
            <div class="text-xs text-gray-300 my-1 text-center">{{computedFilterMessage}}</div>
        </div>
        <div 
            v-for="account in computedFilteredAllParticipants"
            :key="`${account.account}${account.accountId}`"
            class="w-full border-b border-gray-400 p-2"
        >
            <div
                class="flex"
            >
                <profile-picture
                    :classes="'w-10 h-10 min-h-10 flex-shrink-0'"
                >
                    <template slot="image">
                        <img :src="account.url" >
                    </template>
                </profile-picture>
                <div class="ml-2 w-full relative">
                    <div class="text-gray-500 text-sm">
                        {{account.name}}
                    </div>
                    <div class="text-right text-gray-400 text-xs font-semibold">
                        {{account.account}}
                    </div>
                    <div class="text-center text-gray-400 text-xs">
                        {{getDetails(account)}}
                    </div>
                    <div 
                        class="absolute bottom-0 left-0 text-xs font-semibold text-gray-400"
                        v-if="isYourAccount(account)"
                    >you</div>
                </div>
            </div>
            <div class="flex w-full overflow-y-hidden overflow-x-auto mt-1">
                <special-button
                    buttonText="mark work"
                    class="p-1 mr-1 text-xs"
                    @click="clickedButton('mark work', account)"
                    v-if="canMark(account)"
                ></special-button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ProfilePicture from './profile/ProfilePicture.vue'
import RadioInput from './RadioInput.vue'
import SpecialButton from './SpecialButton.vue'
import { default as _ } from 'lodash';
export default {
        components: {
            ProfilePicture,
            SpecialButton,
            RadioInput,
        },
        props: {
            assessment: {
                type: Object,
                default() {
                    return null
                },
                required: true
            },
        },
        data() {
            return {
                filter: 'all'
            }
        },
        computed: {
            ...mapGetters(['getUser']),
            computedFilteredAllParticipants() {
                if (this.filter == 'all') {
                    return this.computedAllParticipants
                }

                if (this.filter == 'markers') {
                    return this.computedMarkers
                }
                
                if (this.filter == 'participants') {
                    return this.computedParticipants
                }
            },
            computedMarkers() {
                return this.assessment.markers.map(marker=>{
                    let newMarker = _.cloneDeep(marker)
                    newMarker.isMarker = true
                    return newMarker
                })
            },
            computedParticipants() {
                return this.assessment.participants.map(participant=>{
                    let newParticipant = _.cloneDeep(participant)
                    newParticipant.isParticipant = true
                    return newParticipant
                })
            },
            computedAllParticipants() {
                let participants = _.uniqWith(
                    [
                        this.assessment.addedby,
                        ...this.computedMarkers,
                        ...this.computedParticipants,
                    ], 
                    (firstAccount, secondAccount)=> this.areSameAccount(firstAccount, secondAccount)
                )

                return participants.map(participant=>{
                    let newParticipant = _.cloneDeep(participant)

                    if (this.isAddedby(newParticipant)) {
                        newParticipant.isAddedby = true
                    }

                    return newParticipant
                })
            },
            computedParticipant() {
                return this.computedAllParticipants.find(participant => participant.userId == this.getUser?.id)
            },
            computedCanMark() {
                return this.computedParticipant?.isMarker || this.computedParticipant?.isAddedby
            },
            computedFilterMessage() {
                return this.filter === 'all' ? 
                    'these are all the user accounts invovled in this assessment 😎' :
                    this.filter === 'markers' ? 
                    'these user accounts will be marking the all the submitted works 😐' :
                    this.filter === 'participants' ? 
                    'these are the accounts to take the assessment and have their work submitted and marked 😁' : ''
            },
        },
        methods: {
            clickedButton(text, account) {
                this.$emit('clickedButton', {text, account})
            },
            isAddedbyOrMarker(account) {
                if (this.isAddedby(account)) {
                    return true
                }

                if (this.isMarker(account)) {
                    return true
                }

                return false
            },
            isAddedby(account) {
                if (this.areSameAccount(account, this.assessment.addedby)) {
                    return true
                }

                return false
            },
            isMarker(account) {
                this.assessment.markers.forEach(marker=>{
                    if (this.areSameAccount(account, marker)) {
                        return true
                    }
                })

                return false
            },
            areSameAccount(firstAccount, secondAccount) {
                return firstAccount.account === secondAccount.account &&
                    firstAccount.accountId === secondAccount.accountId
            },
            areNotSameAccount(firstAccount, secondAccount) {
                return ! this.areSameAccount(firstAccount, secondAccount)
            },
            canMark(account) {
                if (! this.computedCanMark) {
                    return false
                }

                if (this.areSameAccount(this.computedParticipant, account)) {
                    return false
                }

                if (account.userId == this.getUser?.id) {
                    return false
                }

                return true
            },
            getDetails(account) {
                let msg = ''
                if (account.isAddedby) {
                    msg += `owner of assessment`
                }

                if (account.isMarker) {
                    if (msg.length) msg += ', '

                    msg += `marker of submitted works`
                }

                if (account.isParticipant) {
                    if (msg.length) msg += ', '

                    msg += `participant of assessment`
                }

                return msg
            },
            isYourAccount(account) {
                return this.getUser?.id === account.userId
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>