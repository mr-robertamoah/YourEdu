<template>
    <fade-right>
        <template slot="transition" 
                v-if="show">
            <div class="alert-wrapper" 
                :class="{success:success, danger:danger}"
            >
                {{message}}
            </div>
        </template>
    </fade-right>
</template>

<script>
import FadeRight from './transitions/FadeRight'
import { mapActions } from 'vuex'
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
        },
        components: {
            FadeRight,
        },
        data() {
            return {
                show: false
            }
        },
        watch: {
            message: {
                immediate: true,
                handler(newValue){
                    if (newValue != '') {
                        this.show = true
                        setTimeout(() => {
                            this.show = false
                            this.$emit('hideAlert', this.message)
                            this['profile/clearMsg']()
                        }, 3000);
                    }
                }
            }
        },
        computed: {
            computedMessage() {
                return this.message != '' || this.message != null ? true : false
            }
        },
        methods: {
            ...mapActions(['profile/clearMsg']),
        },
    }
</script>

<style lang="scss" scoped>
$danger-color: red;
$success-color: green;
$shadow-color: aliceblue;

    .alert-wrapper{
        padding: 10px;
        width: 80%;
        margin: 10px auto;
        position: absolute;
        top: 30px;
        font-size: 18px;
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

@media screen and (max-width: 800px) {
    
    .alert-wrapper{
        width: 50%;
        margin: 10px 10px 10px auto;
        font-size: 16px;
    }

}
</style>