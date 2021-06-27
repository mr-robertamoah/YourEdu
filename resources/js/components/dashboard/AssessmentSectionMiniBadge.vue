<template>
    <div v-if="assessmentSection" class="bg-white rounded-sm p-2">
        <div class="text-center">{{assessmentSection.name}}</div>
        <div class="text-sm text-gray-500">{{assessmentSection.instruction}}</div>
        <div class="text-right text-xs">{{`${assessmentSection.questions.length} questions`}}</div>
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