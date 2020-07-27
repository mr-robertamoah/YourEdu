<template>
    <div class="file-preview-wrapper"
    >
        <div class="edit"
            @click="clickedRemove"
            v-if="showRemove"
        >
            <black-white-badge
                text='remove'
                v-if="showEdit"
            ></black-white-badge>
            </div>
        <div class="inner-wrapper" ref="preview">
        </div>
    </div>
</template>

<script>
import BlackWhiteBadge from "./BlackWhiteBadge";
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            file: {
                // type: File,
            },
            showRemove: {
                type: Boolean,
                default: true,
            },
        },
        components: {
            BlackWhiteBadge,
        },
        data() {
            return {
                message: '',
                showEdit: true,
            }
        },
        watch: {
            file: {
                immediate: true,
                handler(newValue){
                    let fileReader = new FileReader
                     var inner = document.getElementById('img')
                    if (inner) {
                        this.$refs.preview.removeChild(inner)
                    }
                    inner = document.getElementById('audio')
                    if (inner) {
                        this.$refs.preview.removeChild(inner)
                    }
                    inner = document.getElementById('video')
                    if (inner) {
                        this.$refs.preview.removeChild(inner)
                    }
                    fileReader.addEventListener("load",function(){
                        if (newValue.type.includes('image')) {
                            let el = document.createElement('img')
                            el.setAttribute('id','img')
                            el.style.width = '75%'
                            this.$refs.preview.appendChild(el)
                            el.src = fileReader.result
                        } else if (newValue.type.includes('video')) {
                            let el = document.createElement('video')
                            el.setAttribute('id','video')
                            el.setAttribute('controls','true')
                            el.setAttribute('controlslist','nodownload')
                            el.style.width = '75%'
                            this.$refs.preview.appendChild(el)
                            el.src = fileReader.result
                        } else if (newValue.type.includes('audio')) {
                            let el = document.createElement('audio')
                            el.setAttribute('id','audio')
                            el.setAttribute('controls','true')
                            el.setAttribute('controlslist','nodownload')
                            el.style.width = '75%'
                            this.$refs.preview.appendChild(el)
                            el.src = fileReader.result
                        } else {
                            this.message = `${newValue.name} is not acceptable`
                        }
                    }.bind(this))
                    
                    if (newValue) {
                        fileReader.readAsDataURL(newValue)
                    }
                    
                }
            }
        },
        methods: {
            clickedRemove() {
                 var inner = document.getElementById('img')
                if (inner) {
                    this.$refs.preview.removeChild(inner)
                }
                inner = document.getElementById('audio')
                if (inner) {
                    this.$refs.preview.removeChild(inner)
                }
                inner = document.getElementById('video')
                if (inner) {
                    this.$refs.preview.removeChild(inner)
                }

                this.$emit('removeFile')
            }
        },
    }
</script>

<style lang="scss" scoped>

    .file-preview-wrapper{
        width: 100%;

        .edit{
            position: absolute;
            widows: 100;
            font-size: 14px;
        }

        .inner-wrapper{
            width: 100%;
            text-align: center;

            .special{
                width: 100%;
            }

            img{
                width: 100%;
                height: auto;
            }
            
            video{
                width: auto;
                height: auto;
            }

            .audio{
                width: 100%;
            }
        }
    }
</style>