<template>
    <div>
        <div class="message">
            {{computedMessage}}
        </div>
    </div>
</template>

<script>
import { useRoute } from 'vue-router';

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
                const route = useRoute()

                return this.message.length ? this.message : route.name === 'item'
                    ? `${route.params.item} does not exist` : ''
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>