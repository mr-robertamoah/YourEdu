<template>
    <div v-if="data" class="flex flex-col h-full justify-between p-3 border-2 rounded-lg">
        <div class="text-gray-500 text-sm lowercase flex justify-between w-full">
            <div v-if="computedTypeText.length">
                {{computedTypeText}}
            </div>
            <div v-if="additionalText.length">
                {{additionalText}}
            </div>
        </div>
        <div 
            class="mx-auto min-w-3/4 max-w-full p-2 bg-gray-50"
            :class="{'text-transparent': transparent}"
        >
            <div v-if="data.name" class="capitalize text-lg font-black">
                {{data.name}}
            </div>
            <div v-if="data.description" class="pt-10 text-center truncate">
                {{data.description}}
            </div>
        </div>
        <div class="flex p-2 items-center overflow-y-auto">
            <slot name="buttons"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type: Object,
                default() {
                    return null
                }
            },
            additionalText: {
                type: String,
                default: ''
            },
            transparent: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            computedTypeText() {
                return this.data.type ? 
                    `this is an ${this.data.type}` : ''
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>