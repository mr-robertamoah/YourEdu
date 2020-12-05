<template>
    <fade-right>
        <template slot="transition" 
                v-if="show">
            <div class="alert-wrapper" 
                :class="{success, danger, sticky}"
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
                show: false
            }
        },
        watch: {
            message(newValue) {
                if (newValue && newValue.length) {
                    this.show = true
                    setTimeout(() => {
                        this.show = false
                        this.$emit('hideAlert', newValue)
                        this['profile/clearMsg']()
                    }, 3000);
                }
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