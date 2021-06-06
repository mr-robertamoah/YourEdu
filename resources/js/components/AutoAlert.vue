<template>
    <fade-right>
        <template slot="transition" 
                v-if="show">
            <div class="alert-wrapper z-50" 
                :class="{success, danger, sticky}"
            >
                {{message}}
            </div>
        </template>
    </fade-right>
</template>

<script>
import FadeRight from './transitions/FadeRight'
import { mapActions, mapGetters } from 'vuex'
    export default {
        props: {
            message: {
                type: String,
                default: ''
            },
            success: {
                type: Boolean,
                default: false
            },
            danger: {
                type: Boolean,
                default: false
            },
            lengthy: {
                type: Boolean,
                default: false
            },
            sticky: {
                type: Boolean,
                default: false
            },
        },
        components: {
            FadeRight,
        },
        data() {
            return {
                show: false,
                time: 4000
            }
        },
        watch: {
            message(newValue) {
                if (newValue && newValue.length) {
                    this.show = true
                    setTimeout(() => {
                        this.show = false
                        this.emit(newValue)
                        if (this['profile/getMsg'] && this['profile/getMsg'].length) {
                            this['profile/clearMsg']()
                        }
                    }, this.time);
                }
            },
            lengthy: {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        this.time = 15000
                    } else {
                        this.time = 4000
                    }
                }
            },
        },
        computed: {
            ...mapGetters(['profile/getMsg'])
        },
        methods: {
            ...mapActions(['profile/clearMsg']),
            emit(value) {
                if (this.success) {
                    this.$emit('doneShowingSuccessMessage', value)
                }
                this.$emit('hideAlert', value)
            }
        },
    }
</script>

<style lang="scss" scoped>
$danger-color: red;
$success-color: green;
$shadow-color: aliceblue;

    .alert-wrapper{
        padding: 10px;
        width: 90%;
        margin: 10px auto;
        position: absolute;
        top: 30px;
        right: 5%;
        font-size: 14px;
        color: white;
        box-shadow: 0 0 2px lighten($color: $shadow-color, $amount: 50);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
        z-index: 30000;
    }

    .danger{
        background-color: $danger-color;
    }

    .success{
        background-color: $success-color;
    }

    .alert-wrapper.sticky{
        position: sticky;
    }

@media screen and (max-width: 800px) {

}
</style>