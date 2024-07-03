<template>
    <div class="file-preview-wrapper"
    >
        <div class="edit"
            @click="clickedRemove"
            v-if="computedRemove"
        >
            <black-white-badge
                text='remove'
                v-if="showEdit"
            ></black-white-badge>
        </div>
        <div class="inner-wrapper" 
            ref="preview" 
            v-if="type === 'normal'"
            :class="{middle:middle}"
        ></div>
        <div class="circle" v-if="type === 'circle'">
            <div class="inner-circle" ref="circlepreview"></div>
            <div class="inner-circle" ref="circlepreviewimg" v-if="hasCircleImg">
                <img :src="imgSrc" alt="profile pic">
            </div>
        </div>
    </div>
</template>

<script>
import BlackWhiteBadge from "./BlackWhiteBadge.vue";
    export default {
        props: {
            hasCircleImg: {
                type: Boolean,
                default: false
            },
            show: {
                type: Boolean,
                default: false
            },
            middle: {
                type: Boolean,
                default: false
            },
            file: {
                default: null,
            },
            showRemove: {
                type: Boolean,
                default: true,
            },
            imgSrc: {
                type: String,
                default: '',
            },
            width: {
                type: String,
                default: '75%',
            },
            type: {
                type: String,
                default: 'normal',
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
            hasCircleImg: {
                immediate: true,
                handler(newVal){
                    if (newVal) {
                        if (this.$refs.circlepreview) {
                            this.$refs.circlepreview.style.display = 'none'
                        }
                        
                        if (this.$refs.circlepreviewimg) {
                            this.$refs.circlepreviewimg.style.display = 'block'
                        }
                    } else {
                        if (this.$refs.circlepreview) {
                            this.$refs.circlepreview.style.display = 'block'
                        }
                        
                        if (this.$refs.circlepreviewimg) {
                            this.$refs.circlepreviewimg.style.display = 'none'
                        }
                    }
                }
            },
            file: {
                immediate: true,
                handler(newValue){
                    if (!newValue) {
                        return
                    }

                    if (newValue.url) {
                        this.showFile(newValue)
                        return
                    }

                    this.readFile(newValue)                    
                }
            }
        },
        computed: {
            computedRemove() {
                return this.showRemove ? this.file ? true
                    : false : false 
            }
        },
        methods: {
            clickedRemove() {
                this.$refs.preview.innerHTML = ''

                this.$emit('removeFile')
            },
            showFile(file) {
                if (this.$refs.preview) {
                    this.$refs.preview.innerHTML = ''
                }

                let el = null
                if (file.type.includes('image')) {
                    el = document.createElement('img')
                    el = this.setImageAttributes(el)
                    el.src = file.url
                } else if (file.type.includes('video')) {
                    el = document.createElement('video')
                    el = this.setVideoAttributes(el)
                    el.src = file.url
                } else if (file.type.includes('audio')) {
                    el = document.createElement('audio')
                    el = this.setAudioAttributes(el)
                    el.src = file.url
                } else if (file.type.includes('file')) {
                    el = document.createElement('div')
                    el = this.setFileAttributes(el, file)
                } else {
                    this.message = `${newValue.name} is not a valid file`
                }

                if (!el) {
                    return
                }
                this.appendElementToPreview(el)
            },
            appendElementToPreview(element) {
                if (!this.$refs.preview) {
                    setTimeout(() => {
                        this.appendElementToPreview(element)
                    }, 100);
                    return
                }
                this.$refs.preview.appendChild(element)
            },
            setImageAttributes(el) {
                el.setAttribute('id','img')
                el.style.width = '100%'
                el.style.height = '100%'
                el.style.objectFit = 'contain'
                el.style.objectPosition = 'center'
                return el
            },
            setVideoAttributes(el) {
                el.setAttribute('id','video')
                el.setAttribute('controls','true')
                el.setAttribute('controlslist','nodownload')
                el.style.width = '100%'
                el.style.height = '100%'
                el.style.objectFit = 'contain'
                el.style.objectPosition = 'center'
                return el
            },
            setAudioAttributes(el) {
                el.setAttribute('id','audio')
                el.setAttribute('controls','true')
                el.setAttribute('controlslist','nodownload')
                el.style.width = '75%'
                return el
            },
            setFileAttributes(el, file) {
                el.className = 'application'
                el.innerText = file.name
                return el
            },
            readFile(file) {
                
                let fileReader = new FileReader
                if (this.$refs.preview) {
                    this.$refs.preview.innerHTML = ''
                }
                fileReader.addEventListener("load",function(){
                    if (file.type.includes('image')) {
                        let el = document.createElement('img')
                        if (this.type === 'normal') {
                            el = this.setImageAttributes(el)
                            el.src = fileReader.result
                            this.appendElementToPreview(el)
                        } else {
                            el.style.width = 'inherit'
                            el.style.height = 'inherit'
                            el.style.borderRadius = 'inherit'
                            el.setAttribute('id','img')
                            el.src = fileReader.result
                            this.$refs.circlepreview.appendChild(el)
                        }
                    } else if (file.type.includes('video')) {
                        let el = document.createElement('video')
                        el = this.setVideoAttributes(el)
                        el.src = fileReader.result
                        this.appendElementToPreview(el)
                    } else if (file.type.includes('audio')) {
                        let el = document.createElement('audio')
                        el = this.setAudioAttributes(el)
                        el.src = fileReader.result
                        this.appendElementToPreview(el)
                    } else if (file.type.includes('application')) {
                        let el = document.createElement('div')
                        el = this.setFileAttributes(el, file)
                        this.appendElementToPreview(el)
                    } else {
                        this.message = `${file.name} is not acceptable`
                    }
                }.bind(this))
                
                fileReader.readAsDataURL(file)
            },
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

            .audio{
                width: 100%;
            }

            .application{
                font-size: 12px;
                height: 50px;
                background: gray;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .middle{
            width: 100%;
            height: 100%;
        }

        .circle{
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: auto;
            background-color: $color-primary;

            .inner-circle{
                width: 130px;
                height: 130px;
                border-radius: inherit;
            }
        }
    }
</style>