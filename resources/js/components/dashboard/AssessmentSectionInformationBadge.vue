<template>
    <div v-if="assessmentSection">
        <div class="shadow-sm border-b-2 w-full flex-shrink-0 bg-gray-50 h-10 flex justify-center items-center mt-1 mb-3 relative">
            <div class="absolute left-0 top-0 text-gray-400 text-xs">current section</div>
            <div class="text-lg font-header capitalize">{{assessmentSection.name}}</div>
        </div>
        <div class="w-full px-5 mb-2 flex-shrink-0">
            <div 
                class="overflow-ellipsis text-center text-gray-500 text-sm"
                v-if="assessmentSection.instruction"
            >{{assessmentSection.instruction}}</div>
            <div 
                class="text-xs text-right text-gray-400"
            >{{`${assessmentSection.questions.length} questions`}}</div>
            <div 
                class="text-gray-400 text-right text-xs"
                v-if="assessmentSection.random"
            >the questions where selected randomly</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            assessmentSection: {
                type: Object,
                default() {
                    return null
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>