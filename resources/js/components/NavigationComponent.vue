<template>
    <div class="flex-shrink-0 flex justify-around">
        <navigation-button
            @click.native="clickedNavigator('previous')"
            :disabled="isFirst"
        >previous</navigation-button>
        <navigation-button 
            class="mx-2.5"
            v-for="(button, index) in buttons"
            :key="index"
            @click.native="clickedNavigator(button)"
        >{{button}}</navigation-button>
        <navigation-button
            @click.native="clickedNavigator('next')"
            :disabled="isLast"
        >next</navigation-button>
    </div>
</template>

<script>
import NavigationButton from './NavigationButton.vue'
    export default {
        components: {
            NavigationButton
        },
        props: {
            isFirst: {
                type: Boolean,
                default: false
            },
            isLast: {
                type: Boolean,
                default: false
            },
            buttons: {
                type: Array,
                default() {
                    return []
                }
            },
        },
        methods: {
            clickedNavigator(text) {
                this.$emit('clickedNavigator', text)
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>