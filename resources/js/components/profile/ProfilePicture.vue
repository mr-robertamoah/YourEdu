<template>
    <div class="profile-picture-out"
        @mouseover="show = true"
        @mouseleave="show = false"
        :class="[classes, ! classes.length ? 'w-full h-full' : '']"
    >
        <div class="profile-picture-in w-10/12 h-10/12">
            <div class="edit">
                <just-fade>
                    <template slot="transition">
                        <black-white-badge
                            @click="clicked"
                            v-if="computedShow"
                        >
                            <template slot="badge">
                                <font-awesome-icon :icon="['fa','edit']"></font-awesome-icon>
                            </template>
                        </black-white-badge>
                    </template>
                </just-fade>
            </div>
            <slot name="image"></slot>
        </div>
    </div>
</template>

<script>
import BlackWhiteBadge from "../BlackWhiteBadge.vue";
import JustFade from "../transitions/JustFade.vue";

    export default {
        props: {
            showEdit: {
                type: Boolean,
                default: false
            },
            classes: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                show: false
            }
        },
        components: {
            JustFade,
            BlackWhiteBadge,
        },
        methods: {
            methodShow() {
                this.showEdit ? this.show = true : this.show = false
            },
            methodHide() {
                this.showEdit ? this.show = true : this.show = false
            },
            clicked() {
                this.$emit('editProfile')
            },
        },
        computed: {
            computedShow(){
                return this.showEdit ? this.show ? true : false : false
            },
        },
    }
</script>

<style lang="scss" scoped>

    .profile-picture-out {
        background-color: aquamarine;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 100%;
        cursor: pointer;
        position: relative;
        // margin: 5px 0 0 5px;
    }

    .profile-picture-in {
        border-radius: inherit;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        background-color: gray;

        .edit{
            position: absolute;
            right: 0;
            top: 0;
            font-size: 12px;
            margin: 5px 10px 0 0;
        }
    
        img {
            width: 100%;
            height: 100%;
            border-radius: inherit;
        }
    }

@media screen and (max-width: 800px){
    
}
</style>