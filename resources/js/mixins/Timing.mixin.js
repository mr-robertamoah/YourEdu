import { mapActions } from "vuex"

export default {
    data() {
        return {
            timingItemLocked: false,
            timingItemWait: false,
            timingItemHasFewTimeLeft: null,
            timingItemTimeLeft: false,
            timingItemTime: null,
            noTimeLeftHandled: false,
        }
    },
    watch: {
        "computedItemable.timer"(newValue) {
            this.startTimer()
        }
    },
    computed: {
        computedTimingShow() {
            return !this.timingItemLocked && !this.timingItemWait
        },
    },
    methods: {
        ...mapActions([
            'dashboard/addTime',
        ]),
        startTimer() {
            if (! this.computedItemable.duration) {
                return
            }

            if (! this.computedItemable.timer) {
                this.getTimer()
                return
            }

            this.setTimeObject()

            this.setTimingData()

            if (this.timingItemTime.isPast()) {
                this.noTimeLeft()
                return
            }

            this.timeItemable()
        },
        noTimeLeft(data) {

            if (this.noTimeLeftHandled) {
                return
            }

            this.timingItemLocked = true
            this.timingItemWait = false

            this.$emit('noTimeLeft', { ...this.computedItem })

            if ('handleNoTimeLeft' in this) {
                this.handleNoTimeLeft(data)
            }
        },
        fewMinutesMore(data) {
            this.$emit('fewMinutesMore', { ...this.computedItem })

            if ('handleFewMinutesMore' in this) {
                this.handleFewMinutesMore(data)
            }
        },
        setTimeObject() {
            if (this.timingItemTime) {
                return
            }
            
            let time =  {
                startDate: new Date(this.computedItemable.timer.startTime),
                endDate: new Date(this.computedItemable.timer.endTime),
                floatMinutesleft: function() {
                    return (this.endDate - new Date())/(1000 * 60)
                },
                timeFormat: function(number) {
                    if (number > 9) {
                        return number
                    }

                    if (number < 0) {
                        number = 0
                    }
                        
                    return `0${number}`
                },
                currentHours: function() {
                    return this.timeFormat(Math.floor(this.floatMinutesleft()/60))
                },
                currentMinutes: function() {
                    return this.timeFormat(Math.floor(this.floatMinutesleft() > 59 ? 59 : this.floatMinutesleft()))
                },
                currentSeconds: function() {
                    let floatSeconds = (this.floatMinutesleft() - Math.floor(this.floatMinutesleft())) * 60

                    return this.timeFormat(Math.floor(floatSeconds))
                },
                currentTimeLeft: function () {
                    return `${this.currentHours()}:${this.currentMinutes()}:${this.currentSeconds()}`
                },
                isPast: function() {
                    return new Date() > this.endDate
                },
                hasFewTimeLeft: function() {
                    if (this.duration) {
                        return false
                    }

                    if (5 >= this.timingItemTime.floatMinutesleft()) {
                        return true
                    }

                    return false
                }
            }

            time['duration'] = Math.floor((time.endDate - time.startDate)/(1000 * 60))

            this.timingItemTime = time
        },
        async getTimer() {

            if (! this.computedAccount) {
                this.issueDangerAlert({
                    message: `sorry 😞, there is no valid account associated with this ${this.computedItem.item}`
                })
                return
            }

            this.timingItemWait = true
            let data = {
                time: new Date().toISOString(),
                ...this.computedItem,
                accountId: this.computedAccount.accountId,
                account: this.computedAccount.account,
            }


            let response = await this['dashboard/addTime']({
                item: this.computedItemable,
                data
            })

            if (response.status) {
                // this.issueSuccessAlert({message: ``})
            }

            if (!response.status && 'issueDangerAlertForResponse' in this) {
                this.issueDangerAlertForResponse(response)
            }

            this.timingItemWait = false
        },
        setTimingData() {
            this.timingItemTimeLeft = this.timingItemTime.currentTimeLeft()

            this.timingItemHasFewTimeLeft = this.timingItemTime.hasFewTimeLeft()
        },
        timeItemable() {
            this.timingItemWait = false
            this.timingItemLocked = false

            let interval = setInterval(() => {

                this.setTimingData()

                if (this.timingItemTime.floatMinutesleft() <= -2) {
                    this.noTimeLeft()
                    
                    clearInterval(interval)
                    
                    return
                }

            }, 30000);
        },
    },
}