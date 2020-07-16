<template>
    <div class="profile-picture-out"
        @mouseover="show = true"
        @mouseleave="show = false"
    >
        <div class="profile-picture-in">
            <div class="edit">
                <just-fade>
                    <template slot="transition">
                        <black-white-badge
                            @click="clicked"
                            v-if="show"
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
import BlackWhiteBadge from "../BlackWhiteBadge";
import JustFade from "../transitions/JustFade";

    export default {
        props: {
            showEdit: {
                type: Boolean,
                default: false
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

        },
    }
</script>

<style lang="scss" scoped>
    $the-width: 100%;

    .profile-picture-out {
        width: $the-width;
        height: $the-width;
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
        
        width: $the-width * 0.85;
        height: $the-width * 0.85;
        border-radius: inherit;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;

        .edit{
            position: absolute;
            right: 0;
            top: 0;
            font-size: 12px;
            margin: 5px 10px 0 0;
        }
    }
    
    img {
        width: inherit;
        height: inherit;
        border-radius: inherit;
    }

@media screen and (max-width: 800px){
    
}
</style>