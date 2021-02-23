<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <main-modal
                :show="show"
                :mainOther="false"
                :requests="false"
                @mainModalDisappear='closeModal'
                class="modal-wrapper"
            >
                <template slot="main">
                    lesson modal {{lesson}}
                </template>
            </main-modal>
        </template>
    </just-fade>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { bus } from '../app'
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                lessonId: null,
                lesson: null,
                loading: false,
            }
        },
        watch: {
            lessonId(newValue) {
                if (newValue) {
                    this.getLessonDetails()
                }
            }
        },
        mounted () {
            bus.$on('viewLesson',(data)=>{
                this.lessonId = data.id
            })
        },
        computed: {
            ...mapGetters(['dashboard/getAccountDetails']),
            computedAccount() {
                return this['dashboard/getAccountDetails']
            },
        },
        methods: {
            ...mapActions(['dashboard/getItemDetails']),
            closeModal() {
                this.clearData()
                this.$emit('closeLessonModal')
            },
            clearData() {
                this.lessonId = null
                this.lesson = null
            },
            async getLessonDetails() {                
                if (!this.lessonId) {
                    return
                }
                let response

                this.loading = true

                response = await this['dashboard/getItemDetails']({
                    type: 'lesson',
                    id: this.lessonId,
                })

                this.loading = false

                if (response.status) {
                    this.lesson = response.item
                } else {
                    console.log('response :>> ', response);
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

    .modal-wrapper{
        z-index: 10006;
    }
</style>