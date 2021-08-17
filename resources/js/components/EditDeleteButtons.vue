<template>
    <div class="w-content">
        <div
            class="p-2 cursor-pointer hover:text-green-500 z-30 mr-1 text-gray-500"
            @click="clickedButton('edit')"
            data-testid="edit"
        >
            <font-awesome-icon
                :icon="['fa', 'pencil-alt']"
            ></font-awesome-icon>
        </div>
        <div
            class="p-2 cursor-pointer hover:text-red-500 z-30"
            @click="clickedButton('delete')"
            data-testid="delete"
        >
            <font-awesome-icon
                :icon="['fa', 'trash']"
            ></font-awesome-icon>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            clickedButton(text) {
                this.$emit('click', text)
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>