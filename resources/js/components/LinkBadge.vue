<template>
    <div class="link-badge-wrapper" 
        v-if="link"
        @click.self="clickedLink"
    >
        <div class="link">
            <a :href="link.link">
                {{computedName}}
            </a>
        </div>
        <div class="description"
            @click="clickedLink"
        >
            {{computedDescription}}
        </div>
        <div class="close" 
            @click="clickedRemove"
            v-if="hasRemove"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
    </div>
</template>

<script>
import { strings } from '../services/helpers'
    export default {
        props: {
            link: {
                type: Object,
                default() {
                    return null
                }
            },
            hasRemove: {
                type: Boolean,
                default: true
            }
        },
        computed: {
            computedName() {
                if (this.link.name.length) return this.link.name
                return strings.trim(this.link.link) 
            },
            computedDescription() {
                return strings.trim(this.link.description)
            },
        },
        methods: {
            clickedLink() {
                this.$emit("clickedLink",this.link)
            },
            clickedRemove() {
                this.$emit("clickedRemoveLink",this.link)
            }
        },
    }
</script>

<style lang="scss" scoped>
    
    .link-badge-wrapper{
        position: relative;
        background: antiquewhite;
        border-radius: 5px;
        margin: 0 10px 5px 0;
        cursor: pointer;

        .link{
            @include text-overflow();
            font-size: 12px;
            text-align: center;
            padding: 0 10px 0 5px;

            a{
                text-decoration: none;
            }
        }

        .description{
            font-size: 14px;
            padding: 5px 10px;
        }

        .close{
            @include small-close()
        }
    }
</style>