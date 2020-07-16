<template>
    <div>
        <div class="message">
            {{message}}
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: ''
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                vm.message = to.params.message
                // console.log('catchall', to)
            });
        },
    }
</script>

<style lang="scss" scoped>

</style>