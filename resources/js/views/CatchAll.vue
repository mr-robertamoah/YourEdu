<template>
    <div>
        <div class="message">
            {{computedMessage}}
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
                if (to.params.message) {
                    
                    vm.message = to.params.message
                }
            });
        },
        computed: {
            computedMessage() {
                return this.message.length ? this.message : this.$route.name === 'item'
                    ? `${this.$route.params.item} does not exist` : ''
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>