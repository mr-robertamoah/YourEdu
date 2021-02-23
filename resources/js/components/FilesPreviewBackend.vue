<template>
    <div class="files-preview-backend-wrapper">
        <div class="message" v-if="mainMessage.length">
            {{mainMessage}}
        </div>
        <div class="main">
            <div class="single-file-preview"
                v-for="item in mainFiles"
                :key="`${item.type}.${item.id}`"
            >
                <video 
                    v-if="item.type === 'video'"
                    :src="item.url" controls></video>
                <audio 
                    v-if="item.type === 'audio'"
                    :src="item.url" controls></audio>
                <img 
                    v-if="item.type === 'image'"
                    :src="item.url" alt="">
                <div class="remove"
                    @click="clickedRemove(item,'main')"
                    v-if="hasRemove"
                >
                    <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                </div>
            </div>
        </div>
        <div class="message" v-if="hasRemove && removedMessage.length">
            {{removedMessage}}
        </div>
        <div class="removed"
            v-if="hasRemove && removedFiles.length"
        >
            <div class="single-file-preview"
                v-for="item in removedFiles"
                :key="`${item.type}.${item.id}`"
            >
                <video 
                    v-if="item.type === 'video'"
                    :src="item.url" controls></video>
                <audio 
                    v-if="item.type === 'audio'"
                    :src="item.url" controls></audio>
                <img 
                    v-if="item.type === 'image'"
                    :src="item.url" alt="">
                <div class="remove"
                    @click="clickedRemove(item,'removed')"
                >
                    <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            files: {
                type: Array,
                default() {
                    return null
                }
            },
            localFiles: {
                type: Array,
                default() {
                    return null
                }
            },
            hasRemove: {
                type: Boolean,
                default: true
            },
            checkLocalFiles: {
                type: Boolean,
                default: false
            },
            mainMessage: {
                type: String,
                default: ''
            },
            removedMessage: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                removedFiles: [],
                mainFiles: [],
            }
        },
        watch: {
            removedFiles(newValue) {
                this.$emit('previewFiles',{
                    removed: newValue,
                    main: this.mainFiles
                })
            },
            files: {
                immediate: true,
                handler(newValue) {
                    if (newValue) {
                        this.mainFiles = newValue
                    }
                }
            }
        },
        methods: {
            clickedRemove(item, type) {
                let index = this.findFileIndex(item,type)
                if (index > -1) {
                    if (type === 'main') {                        
                        this.mainFiles.splice(index,1)
                        this.removedFiles.push(item)
                    } else if (type === 'removed') { 
                        if (this.itemTypeInLocalFiles(item)) return
                        this.removedFiles.splice(index,1)
                        this.mainFiles.push(item)
                    }
                }
            },
            findFileIndex(item,type) {
                let files = type === 'main' ? this.mainFiles : this.removedFiles
                return files.findIndex(file=>{
                    return item.id === file.id && 
                        item.type === file.type
                })
            },
            itemTypeInLocalFiles(item) {
                if (this.localFiles) {
                    this.localFiles.forEach(file => {
                        if (file && file.type.includes(item.type)) {
                            this.$emit('error',{
                                lengthy: true,
                                message: `please you cannot undo the removal of this file till you delete a ${item.type} from your locally uploaded files.`})
                            return true
                        }
                    });
                }
                return false
            },
        },
    }
</script>

<style lang="scss" scoped>

    .files-preview-backend-wrapper{

        .message{
            font-size: 12px;
            color: gray;
            text-align: center;
        }

        .main,
        .removed{
            width: 90%;
            max-width: 400px;
            margin: 10px auto;
            max-height: 400px;
            display: flex;
            flex-wrap: nowrap;
            overflow-y: hidden;
            overflow-x: auto;
            padding: 10px;
            align-items: center;

            .single-file-preview{
                min-width: 100%;
                max-width: 100%;
                position: relative;
                padding: 5px;
                padding-bottom: 0;
                background: mintcream;
                margin-right: 10px;

                video,
                img{
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    object-position: center;
                }
                
                audio{
                    width: 100%;
                }
            }

            .remove{
                position: absolute;
                z-index: 1;
                top: 10px;
                right: 10px;
                padding: 5px;
                font-size: 16px;
                color: red;
                cursor: pointer;
            }
        }

        .removed{
            background: red;
        }
    }
</style>