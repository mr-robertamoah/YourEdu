<template>
    <div class="main-preview-wrapper"
        @mouseover="showBadge = true"
        @mouseleave="showBadge = false"
    >
        <div class="remove">
            <fade-left>
                <template slot="transition" v-if="showBadge">
                    <black-white-badge
                        text="remove"
                        @click="clicked"
                    ></black-white-badge>
                </template>
            </fade-left>
        </div>
        <div class="main">
            <div class="heading">
                {{heading}}
            </div>
            <div class="author" v-if="author.length > 0">
                {{title}}
            </div>
            <div class="title" v-if="title.length > 0">
                {{title}}
            </div>
            <div class="body" v-if="body.length > 0">
                {{body}}
            </div>
            <div class="file" v-if="hasFile">
                <file-preview
                    :showRemove="false"
                    :file="file"
                ></file-preview>
            </div>
        </div>
    </div>
</template>

<script>
import FilePreview from './FilePreview'
import FadeLeft from './transitions/FadeLeft'
import BlackWhiteBadge from './BlackWhiteBadge'
    export default {
        props: {
            heading: {
                type: String,
                default: ''
            },
            title: {
                type: String,
                default: ''
            },
            author: {
                type: String,
                default: ''
            },
            hasFile: {
                type: Boolean,
                default: false
            },
            body: {
                type: String,
                default: ''
            },
            file: {
                type: File,
            },
        },
        components: {
            BlackWhiteBadge,
            FadeLeft,
            FilePreview,
        },
        data() {
            return {
                showBadge: false,
            }
        },
        methods: {
            clicked() {
                this.$emit('clickedBadge')
            }
        },
    }
</script>

<style lang="scss" scoped>
@mixin text-overflow(){
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}
    .main-preview-wrapper{
        position: relative;
        width: 100%;
        min-height: 100px;

        .remove{
            position: absolute;
            margin: 5px;
        }
        
        .main{
            width: 100&;
            padding: 5px;
            border: 1px solid gray;
            background-color: rgba(128, 128, 128, 0.507);
            padding: 0.75rem;

            .heading{
                width: 100%;
                text-align: end;
                padding: 5px;
                font-size: 14px;
                @include text-overflow();
                border-bottom: 1px solid gray;
            }
            
            .body{
                font-size: 12px;
                text-align: justify;
                margin: 10px 0;
            }

            .title{
                font-size: 14px;
                @include text-overflow();
                text-align: center;
                margin: 10px 0;
                font-weight: 500;
            }

            .file{
                width: 70%;
                margin: 0 auto;
            }
        }
    }
</style>