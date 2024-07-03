<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div class="discusssion-info-section">
                <div class="close" @click="clickedDiscussionInfo('view')">
                    <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                </div>
                <div class="pencil" 
                    @click="clickedDiscussionInfo('edit')"
                    v-if="(owner || admin) && !showEdit"
                >
                    <font-awesome-icon :icon="['fa','pencil-alt']"></font-awesome-icon>
                </div>
                <div class="title">
                    {{showEdit ? 'edit discussion information' : 'discussion information'}}
                </div>
                <div class="body" v-if="!showEdit">
                    <div class="section">Admin</div>
                    <div class="owner-section">
                        <div class="name">{{discussion.raisedby.name}}</div>
                        <div class="account">{{discussion.raisedby.account}}</div>
                    </div>
                    <div class="section" v-if="showParticipantsButton !== 'hide'">Information</div>
                    <div class="info-section" v-if="showParticipantsButton !== 'hide'">
                        <div class="info-item">
                            <div class="label">title</div>
                            <div class="item">{{discussion.title}}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">type</div>
                            <div class="item">{{computedType}}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">restriction</div>
                            <div class="item">
                                {{discussion.restricted ? 'restricted mode' : 'unrestricted mode'}}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="label">participants</div>
                            <div class="item">{{computedParticipantsInfo}}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">note</div>
                            <div class="item">{{note}}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">attachments</div>
                            <div class="item">
                                <attachment-badge></attachment-badge>
                            </div>
                        </div>
                    </div>
                    <div class="section">Participation</div>
                    <div class="show-participants"
                        @click="clickedShowParticipants"
                    >{{showParticipantsButton}}</div>
                    <div class="participants-section"
                        v-if="showParticipants"
                        infinite-wrapper>
                        <other-user-account
                            v-for="participant in participants"
                            :key="participant.id"
                            :account="participant"
                            :loading="otherUserAccountLoading"
                            :admin="admin"
                            :owner="owner"
                            :participant="true"
                            :participating="participant"
                            @clickedParticipantAction="clickedParticipantAction"
                        ></other-user-account>
                        <div class="loading" v-if="participantsLoading">
                            <pulse-loader :loading="participantsLoading" size="10px"></pulse-loader>
                        </div>
                    </div>
                    <Infinite-Loading
                        @infinite="infiniteHandlerParticipants"
                        v-if="nextPage && nextPage > 1"
                        force-use-infinite-wrapper
                    ></Infinite-Loading>
                </div>
                <just-fade>
                    <template slot="transition" v-if="showEdit">
                        <div class="edit-section">
                            <div class="loading" v-if="loading && showEdit">
                                <pulse-loader 
                                    :loading="loading" 
                                    :size="'10px'"
                                ></pulse-loader>
                            </div>
                            <div class="section">Discussion Info</div>
                            <div class="form-edit">
                                <text-input
                                    placeholder="discussion title"  
                                    :bottomBorder="true"
                                    :error="errorTitle"
                                    v-model="data.title"></text-input>
                            </div>
                            <div class="form-edit">
                                <text-textarea type="text" 
                                    placeholder="discussion preamble (an introduction to the discussion)"
                                    :bottomBorder="true"
                                    v-model="data.preamble"></text-textarea>
                            </div>
                            <div class="form-edit">
                                <div class="main-section">
                                    <div class="label">type:</div>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('public')"
                                        :active="toLowercase(data.type) === 'public'"
                                        text="public"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('private')"
                                        :active="toLowercase(data.type) === 'private'"
                                        text="private"
                                    ></grey-button>
                                </div>
                            </div>
                            <div class="form-edit">
                                <div class="main-section">
                                    <div class="label">restricted:</div>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('yes')"
                                        :active="data.restricted"
                                        text="yes"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('no')"
                                        :active="! data.restricted"
                                        text="no"
                                    ></grey-button>
                                </div>
                            </div>
                            <div class="form-edit">
                                <div class="main-section">
                                    <div class="label">allowed:</div>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('all')"
                                        :active="toLowercase(data.allowed) === 'all'"
                                        text="main"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('learners')"
                                        :active="toLowercase(data.allowed) === 'learners'"
                                        text="learners"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('parents')"
                                        :active="toLowercase(data.allowed) === 'parents'"
                                        text="parents"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('facilitators')"
                                        :active="toLowercase(data.allowed) === 'facilitators'"
                                        text="facilitators"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('professionals')"
                                        :active="toLowercase(data.allowed) === 'professionals'"
                                        text="professionals"
                                    ></grey-button>
                                    <grey-button
                                        class="grey-button"
                                        @clickedAction="clickedEditActionButton('schools')"
                                        :active="toLowercase(data.allowed) === 'schools'"
                                        text="schools"
                                    ></grey-button>
                                </div>
                            </div>
                            <div class="section">Discussion Resources</div>
                            <div class="info">you can up upload up to three files</div>
                            <div class="files" v-if="computedEditFilesLength < 3">
                                <div class="file"
                                    @click="clickedFileType('video')"
                                    :class="{active: fileType === 'video'}"
                                >video</div>
                                <div class="file"
                                    @click="clickedFileType('audio')"
                                    :class="{active: fileType === 'audio'}"
                                >audio</div>
                                <div class="file"
                                    @click="clickedFileType('picture')"
                                    :class="{active: fileType === 'picture'}"
                                >picture</div>
                            </div>
                            <div class="actions" v-if="computedEditFilesLength < 3">
                                <div class="action"
                                    @click="clickedEditAction('upload')"
                                    v-if="fileType.length"
                                    :title="`upload ${fileType}`"
                                >
                                    <font-awesome-icon :icon="['fa','upload']"></font-awesome-icon>
                                </div>
                                <div class="action"
                                    v-if="fileType === 'video'" 
                                    @click="clickedEditAction('video')"
                                    title="record a video"
                                >
                                    <font-awesome-icon :icon="['fa','video']"></font-awesome-icon>
                                </div>
                                <div class="action"
                                    v-if="fileType === 'picture'" 
                                    @click="clickedEditAction('camera')"
                                    title="snap a picture"
                                >
                                    <font-awesome-icon :icon="['fa','camera']"></font-awesome-icon>
                                </div>
                                <div class="action"
                                    v-if="fileType === 'audio'" 
                                    @click="clickedEditAction('microphone')"
                                    title="record an audio"
                                >
                                    <font-awesome-icon :icon="['fa','microphone']"></font-awesome-icon>
                                </div>
                            </div>
                            <div class="media-section resources" v-if="computedEditFilesLength">
                                <div class="media-item"
                                    v-for="(mediaItem,index) in files"
                                    :key="index"
                                >
                                    <div class="item-type" @click="clickedEditFile(mediaItem,'resource')">
                                        <font-awesome-icon :icon="['fa','image']"></font-awesome-icon>
                                    </div>
                                    <div class="item-info" @click="clickedEditFile(mediaItem,'resource')">
                                        {{mediaItem.name ? mediaItem.name : shortenUrl(mediaItem.url)}}
                                    </div>
                                    <div class="item-clear"
                                        @click="clickedEditBan(mediaItem,'resource')"
                                        :title="`remove ${getFileType(mediaItem.type)}`"
                                    >
                                        <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                    </div>
                                </div>
                            </div>
                            <div class="media-section uploads" v-if="data.uploadFiles.length">
                                <div class="media-item"
                                    v-for="(mediaItem,index) in data.uploadFiles"
                                    :key="index"
                                >
                                    <div class="item-type" @click="clickedEditFile(mediaItem,'upload')">
                                        <font-awesome-icon :icon="['fa','image']"></font-awesome-icon>
                                    </div>
                                    <div class="item-info" @click="clickedEditFile(mediaItem,'upload')">
                                        {{mediaItem.name ? mediaItem.name : shortenUrl(mediaItem.url)}}
                                    </div>
                                    <div class="item-clear"
                                        @click="clickedEditBan(mediaItem,'upload')"
                                        :title="`remove ${getFileType(mediaItem.type)}`"
                                    >
                                        <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                    </div>
                                </div>
                            </div>
                            <fade-up>
                                <template slot="transition" v-if="showPreview">
                                    <file-preview
                                        class="file-preview"
                                        :file="activeFile"
                                        :middle="true"
                                        @removeFile="removeFile"
                                    ></file-preview>
                                </template>
                            </fade-up>
                            <input type="file" class="hidden" 
                                @change="editFileChange" 
                                ref="inputfile"
                                :accept="fileAccept"
                                multiple
                            >
                    
                            <div class="buttons">
                                <post-button 
                                    :buttonText="'edit'" 
                                    buttonStyle='success'
                                    @click="clickedEdit"
                                ></post-button>
                                <post-button 
                                    :buttonText="'cancel'" 
                                    buttonStyle='danger'
                                    @click="clickedEdit"
                                ></post-button>
                            </div>
                        </div>
                    </template>
                </just-fade>
            </div>
        </template>
    </just-fade>
</template>

<script>
import OtherUserAccount from './chat/OtherUserAccount.vue';
import GreyButton from './GreyButton.vue';
import PostButton from './PostButton.vue';
import FadeUp from './transitions/FadeUp.vue';
    export default {
        components: {
            OtherUserAccount,
            GreyButton,
            PostButton,
            
            
            FadeUp,
        },
        props: {
            discussion: {
                type: Object,
                default() {
                    return null
                }
            },
            files: {
                type: Array,
                default() {
                    return []
                }
            },
            participants: {
                type: Array,
                default() {
                    return []
                }
            },
            activeFile: {
                type: File,
                default() {
                    return null
                }
            },
            show: {
                type: Boolean,
                default: false
            },
            participant: {
                type: Boolean,
                default: false
            },
            participantsLoading: {
                type: Boolean,
                default: false
            },
            loading: {
                type: Boolean,
                default: false
            },
            OtherUserAccountLoading: {
                type: Boolean,
                default: false
            },
            nextPage: {
                type: Number,
                default: null
            },
            owner: {
                type: Boolean,
                default: false
            },
            showFilePreview: {
                type: Boolean,
                default: false
            },
            admin: {
                type: Boolean,
                default: false
            },
            showEdit: {
                type: Boolean,
                default: false
            },
            note: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                data: {
                    title: '',
                    type: '',
                    preamble: '',
                    allowed: '',
                    restricted: false,
                    files: [],
                    uploadFiles: [],
                    deletedFiles: [],
                },
                errorTitle: false,
                fileAccept: '',
                fileType: '',
                showPreview: false,
                showParticipants: false,
                showParticipantsButton: 'participants',
            }
        },
        watch: {
            showEdit(newValue){
                if (newValue && !this.title.length) {
                    this.data.title = this.discussion.title
                    this.data.preamble = this.discussion.preamble
                    this.data.type = this.discussion.type
                    this.data.restricted = this.discussion.restricted
                    this.data.allowed = this.discussion.allowed
                    this.data.files = this.files
                    this.data.deletedFiles = []
                    this.data.uploadFiles = []
                } else if (!newValue) {
                    this.data.title = ''
                }
            },
            'data.title'(newValue, oldValue) {
                if (newValue.length && !oldValue.length) {
                    this.errorTitle = false
                }
            },
            activeFile(newValue){
                if (newValue) {
                    this.data.uploadFiles.push(newValue)
                }
            },
            showParticipants(newValue){
                if (newValue) {
                    this.getDiscussionParticipants()
                    this.showParticipantsButton = 'hide'
                } else {
                    this.showParticipantsButton = 'participants'
                    this.updateNextPage(1)
                }
            },
            showFilePreview(newValue){
                if (newValue) {
                    this.showPreview = true
                    return
                }

                this.showPreview = false
            },
            files(newValue) {
                this.data.files = newValue
            }
        },
        computed: {
            computedType(){
                return this.discussion.type.toLowerCase()
            },
            computedEditFilesLength(){
                return this.data.type.length ? 
                    this.data.files.length + 
                    this.data.uploadFiles.length : 0
            },
            computedParticipantsInfo(){
                return this.computedParticipantsNumber === 1 ? 
                    `${this.computedParticipantsNumber} participant` :
                    `${this.computedParticipantsNumber} participants`
            },
            computedParticipantsNumber(){
                return this.discussion.participants.length + 1
            },
        },
        methods: {
            updateNextPage(page) {
                this.$emit('updateNextPage', 1)
            },
            clickedShowParticipants(){
                this.showParticipants = ! this.showParticipants
            },
            clickedDiscussionInfo(text) {
                this.$emit('clickedDiscussionInfo',  text)
            },
            clickedEditAction(data) {
                this.$emit('clickedEditAction', data)
            },
            getDiscussionParticipants() {
                this.$emit('getDiscussionParticipants')
            },
            infiniteHandlerParticipants($state) {
                this.$emit('infiniteHandlerParticipants', $state)
            },
            clickedEditActionButton(data){
                if (data === 'all') {
                    this.data.allowed = data
                } else if (data === 'learners') {
                    this.data.allowed = data
                } else if (data === 'parents') {
                    this.data.allowed = data
                } else if (data === 'facilitators') {
                    this.data.allowed = data
                } else if (data === 'professionals') {
                    this.data.allowed = data
                } else if (data === 'schools') {
                    this.data.allowed = data
                } else if (data === 'private') {
                    this.data.type = data
                } else if (data === 'public') {
                    this.data.type = data
                } else if (data === 'yes') {
                    this.data.restricted = true
                } else if (data === 'no') {
                    this.data.restricted = false
                }
            },
            getFileType(data){
                if (data.includes('image')) {
                    return 'image'
                } else if (data.includes('video')) {
                    return 'video'
                } else if (data.includes('audio')) {
                    return 'audio'
                }
            },
            shortenUrl(data){
                return strings.trim(data,20)
            },
            toLowercase(data){
                return data.toLowerCase()
            },
            clickedFileType(data){
                if (data === 'picture') {
                    this.fileAccept = 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp'
                } else if (data === 'video') {
                    this.fileAccept = 'video/webm,video/mp4,video/ogg'
                } else if (data === 'audio') {
                    this.fileAccept = 'audio/mpeg,audio/ogg,audio/wav'
                }
                this.fileType = data
            },
            clickedEdit(data){
                this.$emit('clickedEdit', {type: data, data: this.data})
            },
            removeFile(){
                this.fileType = ''
                this.showPreview = false
                this.clickedEditBan(this.activeFile,'upload')
            },
            clickedEditFile(data,type){
                this.$emit('clickedEditFile', {data, type})
            },
            clickedEditBan(data, type){
                if (type === 'upload') {
                    let index = this.data.uploadFiles.findIndex(file=>{
                        return file.name === data.name && file.size === data.size
                    })
                    if (index > -1) {
                        this.data.uploadFiles.splice(index,1)
                    }
                } else if (type === 'resource') {
                    let index = this.data.files.findIndex(file=>{
                        return file.type === data.type && file.id === data.id
                    })
                    if (index > -1) {
                        this.data.files.splice(index,1)
                        this.data.deletedFiles.push(data)
                    }
                }
                this.showPreview = false
            },
            editFileChange(){
                if (this.$refs.inputfile.files.length + this.computedEditFilesLength > 3) {
                    this.$refs.inputfile.value = ''
                    return
                }
                this.activeFile = this.$refs.inputfile.files[0]
                for (let i = 0; i < this.$refs.inputfile.files.length; i++) {
                    this.data.uploadFiles.push(this.$refs.inputfile.files[i])
                }
                this.showPreview = true
            },
            clickedParticipantAction(data){
                if (data.action === 'remove' || data.action === 'leave') {
                    this.$emit('clickedLeaveRemoveParticipant', data)
                    return
                }

                this.$emit('updateParticpantState', data)
            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin button(){
    padding: 5px;
    width: fit-content;
    font-size: 14px;
    min-width: 35px;
    text-align: center;
    margin: 0 5px;
    border-radius: 10px;
    box-shadow: 0 0 2px grey;
    color: gray;
    cursor: pointer;
}

@mixin close(){
    color: gray;
    position: absolute;
    right: 3px;
    top: 3px;
    padding: 5px;
    font-size: 14px;
    cursor: pointer;

    &:hover{
        color: red;
        transition: all 1s ease-in-out;
    }
}

@mixin loading(){
    width: 100%;
    text-align: center;
    padding: 5px;
}

@mixin section(){
    width: 100%;
    color: gray;
    font-size: 12px;
    border-bottom: 1px solid gray;
    margin-top: 10px;
}

@mixin info(){
    text-align: center;
    font-size: 12px;
    color: gray;
}

    .discusssion-info-section{
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: mintcream;
        border-radius: 10px;
        box-shadow: 0 0 2px grey;

        .close, 
        .pencil{
            color: gray;
            position: absolute;
            right: 10px;
            top: 10px;
            padding: 5px;
            font-size: 14px;
            cursor: pointer;

            &:hover{
                color: red;
                transition: all 1s ease-in-out;
            }
        }

        .pencil{
            right: 40px;
            top: 5px;

            &:hover{
                color: green;
            }
        }
        
        .title{
            width: 100%;
            text-align: center;
            margin: 20px 0 0;
            color: gray;
            text-transform: capitalize;
        }

        .body{
            padding: 10px;
            height: 88%;

            .section{
                @include section();
            }

            .owner-section{
                display: inline-flex;
                width: 100%;
                justify-content: space-between;
                align-items: center;

                .name{
                    font-size: 14px;
                    text-transform: capitalize;
                }

                .account{
                    font-size: 12px;
                    color: gray;
                }
            }

            .info-section{

                .info-item{
                    display: table;
                    width: 100%;
                    font-size: 14px;
                    padding: 5px;

                    .label{
                        display: table-cell;
                        width: 80px;
                        max-width: 30%;
                        color: gray;
                        padding-right: 5px;
                    }

                    .item{
                        font-weight: 500;
                        width: 100%;
                        font-variant: small-caps;
                    }
                }
            }

            .show-participants{
                @include button();
                margin: 10px auto;
            }

            .participants-section{
                height: 75%;
                overflow-y: auto;
                padding: 10px;
                width: 100%;

                .loading{
                    @include loading();
                }
            }
        }

        .edit-section{
            width: 100%;
            height: 88%;
            overflow-y: auto;
            padding: 10px;

            .loading{
                width: 100%;
                text-align: center;
                padding: 5px;
            }

            .section{
                @include section();
            }

            .info{
                @include info();
                margin-bottom: 10px;
            }

            .form-edit{
                margin: 10px auto;

                input,
                textarea{
                    width: 90%;
                    margin: 10px auto;
                }

                .main-section{
                    display: flex;
                    justify-content: flex-start;
                    align-items: flex-start;
                    flex-wrap: wrap;
                    width: 100%;

                    .label{
                        margin-right: 10px;
                        font-size: 14px;
                        color: gray;
                    }
                    
                    .grey-button{
                        margin: 0 10px 10px 0;
                    }
                }
            }

            .files{
                display: inline-flex;
                justify-content: space-around;
                width: 100%;
                font-size: 14px;
                margin: 20px 0 10px;

                .file{
                    padding: 5px;
                    border-radius: 10px;
                    background: gray;
                    color: mintcream;
                    cursor: pointer;

                    &:hover{
                        background: green;
                        transition: all .5s ease;
                    }
                }

                .active{
                    background: green;
                    transition: all .5s ease;
                }
            }

            .actions{
                display: inline-flex;
                margin-bottom: 10px;

                .action{
                    color: gray;
                    cursor: pointer;
                    padding: 5px;
                    margin: 0 10px 0 0;
                }
            }

            .media-section{
                width: 100%;
                padding: 5px;
                margin: 5px 0;
                overflow-x: auto;
                overflow-y: hidden;
                display: inline-flex;

                .media-item{
                    display: inline-flex;
                    justify-content: space-between;
                    align-items: center;
                    color: gray;
                    background-color: white;
                    width: 150px;
                    font-size: 10px;
                    padding: 5px;
                    cursor: pointer;
                    position: relative;
                    margin: 0 10px 0 0;

                    .item-info{
                        font-size: 11px;
                        max-width: 70%;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        white-space: nowrap;
                    }
                    
                    .item-type{
                        font-size: 10px;
                    }

                    .item-clear{
                        @include close();
                        z-index: 1;
                    }
                }
            }

            .file-preview{
                width: 100%;
                position: relative;

                .edit{
                    position: absolute;
                    font-size: 14px;
                    top: 0;
                }
            }

            .buttons{
                margin-top: 10px;
                width: 100%;
                display: inline-flex;
                justify-content: space-around;
            }
        }
    }
</style>