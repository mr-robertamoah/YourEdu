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
import BlackWhiteBadge from "./BlackWhiteBadge";
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
                    let fileReader = new FileReader
                    if (this.$refs.preview) {
                        this.$refs.preview.innerHTML = ''
                    }
                    fileReader.addEventListener("load",function(){
                        if (newValue.type.includes('image')) {
                            let el = document.createElement('img')
                            el.setAttribute('id','img')
                            if (this.type === 'normal') {
                                this.$refs.preview.appendChild(el)
                                el.style.width = '100%'
                                el.style.height = '100%'
                                el.style.objectFit = 'contain'
                                el.style.objectPosition = 'center'
                            } else {
                                this.$refs.circlepreview.appendChild(el)
                                el.style.width = 'inherit'
                                el.style.height = 'inherit'
                                el.style.borderRadius = 'inherit'
                            }
                            el.src = fileReader.result
                        } else if (newValue.type.includes('video')) {
                            let el = document.createElement('video')
                            el.setAttribute('id','video')
                            el.setAttribute('controls','true')
                            el.setAttribute('controlslist','nodownload')
                            el.style.width = '100%'
                            el.style.height = '100%'
                            el.style.objectFit = 'contain'
                            el.style.objectPosition = 'center'
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
            }
        },
    }
</script>

<style lang="scss" scoped>
$input-color: rgba(22, 233, 205, 1);

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
            background-color: $input-color;

            .inner-circle{
                width: 130px;
                height: 130px;
                border-radius: inherit;
            }
        }
    }
</style>