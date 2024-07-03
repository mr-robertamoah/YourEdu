<template>
    <div class="w-full min-h-screen bg-whitesmoke relative px-2 pb-2">
        <auto-alert
            class="absolute w-full text-center top-1/2"
            :message="alertMessage"
            :success="alertSuccess"
            :danger="alertDanger"
            :sticky="true"
            @hideAlert="clearAlert"
            @doneShowingDangerMessage="afterDangerAlert"
            @stop="stopResizingAndMoving"
        ></auto-alert>
        <div 
            class="fixed rounded-full cursor-pointer
                text-whitesmoke text-sm h-content w-content p-2 
                bottom-1 right-1 z-50"
        >
            <div 
                @click="clickedButton('add board')"
                title="add a new board"
                class="rounded-full my-2 bg-green-600 cursor-pointer
                    text-whitesmoke text-sm h-content w-content p-2"
            >
                <font-awesome-icon
                    :icon="['fa', 'plus']"
                ></font-awesome-icon>
            </div>
            <div 
                @click="clickedButton('tools')"
                title="show other tools"
                class="rounded-full my-2 bg-gray-600 cursor-pointer
                    text-whitesmoke text-sm h-content w-content p-2"
            >
                <font-awesome-icon
                    :icon="['fa', 'wrench']"
                ></font-awesome-icon>
            </div>
            <div 
                @click="clickedButton('colors')"
                ref="activecolor" 
                class="w-8 h-8 my-2 cursor-pointer rounded-full border-gray-900 border-2"
            ></div>
        </div>
        <resizing-component
            :resize="resize"
            :move="move"
            v-for="board in boards"
            :hasNegativeTop="getBoardIndex(board) > 0"
            :key="board.id"
            @startedResizing="startedResizing"
            @stoppedResizing="stoppedResizing"
            class="w-content h-content"
        >
            <the-board
                :board="board"
                :resize="board.name === resizedBoard.name"
                :key="board.name"
                :lineWidth="lineWidth"
                :activeColor="selectedColor"
                :what="computedDrawWhat"
                :angle="drawObject.angle"
                :sides="drawObject.sides"
                @editBoard="editBoard"
                @deleteBoard="deleteBoard"
                class="mb-10"
            ></the-board>
        </resizing-component>

        <div v-if="showItem === 'accounts'" class="w-full flex justify-start items-center">
            <profile-picture
                class="w-10 h-10"
                v-for="(account, index) in accounts"
                :key="index"
            >
                <template>
                    <img :src="account.url" alt="">
                </template>
            </profile-picture>
        </div>
        
        <pop-up
            :show="showPopUp"
            :responses="responses"
            :message="popUpMessage"
            default="cancel"
            :hasResponses="popUpHasResponses"
            @clickedResponse="clickedPopupResponse"
            @closePopUp="closePopUp"
        >
            <div v-if="showItem === 'board form'">
                <text-input
                    :bottomBorder="true"
                    placeholder="name of the new board"
                    v-model="board.name"
                    @keydown.enter.native="clickedPopupResponse('ok')"
                    class="mb-2"
                ></text-input>
            </div>
            <div v-if="showItem === 'tools'">
                <div class="flex flex-wrap w-full items-center justify-center">
                    <div
                        class="w-8 h-8 m-2 cursor-pointer hover:w-12 hover:h-12 rounded-full"
                        v-for="(color, index) in colors"
                        :key="index"
                        :class="[color ? `bg-${color.name}-500` : 'bg-black']"
                        @click="clickedColor(color.value)"
                    ></div>
                </div>
                <div class="flex w-full flex-wrap items-center">
                    <input 
                        type="color" 
                        :value="`${selectedColor}`" @input="clickedColor($event.target.value)" title="more colors"
                        class="mx-2"
                    >
                    <input 
                        type="range" 
                        :value="lineWidth" @change="setLineWidth" @input="setLineWidth" title="select line width" min="1" max="100"
                        class="mx-2"
                    >
                    <main-checkbox
                        :label="'resize'"
                        v-model="resize"
                    ></main-checkbox>
                    <main-checkbox
                        :label="'move'"
                        v-model="move"
                    ></main-checkbox>
                </div>
                <div class="">
                    <main-checkbox
                        :label="'freehand'"
                        v-model="drawObject.freehand"
                    ></main-checkbox>
                    <main-checkbox
                        :label="'line'"
                        v-model="drawObject.line"
                    ></main-checkbox>
                    <main-checkbox
                        :label="'polygon'"
                        v-model="drawObject.polygon"
                    ></main-checkbox>
                    <main-checkbox
                        :label="'circle'"
                        v-model="drawObject.circle"
                    ></main-checkbox>
                    <input 
                        v-if="drawObject.polygon"
                        type="range" 
                        v-model="drawObject.angle"
                        title="select angle" 
                        min="0" 
                        max="3600"
                        class="mx-2 mb-2"
                    >
                    <number-input
                        v-if="drawObject.polygon"
                        v-model="drawObject.sides"
                        :hasMin="true"
                        :inputMin="3"
                        :inputMax="16"
                        :placeholder="'enter number of sides of polygon'"
                    ></number-input>
                </div>
            </div>
        </pop-up>
    </div>
</template>

<script>
import TheBoard from './TheBoard.vue'
import TextInput from './TextInput.vue';
import ResizingComponent from './specials/ResizingComponent.vue';
import MainCheckbox from './MainCheckbox.vue';
import PopUp from '../mixins/PopUp.mixin'; 
import Alert from '../mixins/Alert.mixin'; 
import MainSelect from './MainSelect.vue';
import NumberInput from './NumberInput.vue';
import { default as _ } from 'lodash';
import RadioInput from './RadioInput.vue';
    export default {
        components: {
            TheBoard,
            ResizingComponent,
            TextInput,
            MainCheckbox,
            MainSelect,
            NumberInput,
                RadioInput,
        },
        props: {
            colors: {
                type: Array,
                default() {
                    return [
                        {name: 'red', value: '#FF0000'},
                        {name: 'yellow', value: '#FFFF00'},
                        {name: 'green', value: '#008000'},
                        {name: 'blue', value: '#0000FF'},
                        {name: 'gray', value: '#808080'},
                        {name: 'indigo', value: '#4B0082'},
                        {name: 'purple', value: '#800080'},
                        {name: 'pink', value: '#FFC0CB'}
                    ]
                }
            },
        },
        mixins: [Alert, PopUp],
        data() {
            return {
                accounts: [],
                boards: [],
                board: {
                    id: null,
                    name: ''
                },
                showItem: '',
                selectedColor: null,
                lineWidth: 1,
                resize: false,
                move: false,
                resizedBoard: {},
                responses: ['ok', 'cancel'],
                defaultResponse: 'cancel',
                drawObject: {
                    sides: 3,
                    polygon: false,
                    line: false,
                    angle: 0,
                    circle: false,
                    freehand: true,
                }
            }
        },
        watch: {
            showItem(newValue) {
                if (newValue === 'colors') {
                    setTimeout(() => {
                        this.showItem = ''
                    }, 4000);
                }
            },
            selectedColor(newValue) {
                if (newValue) {
                    this.setActiveColor(newValue)
                }
            },
            "board.name"(newValue) {
                if (newValue?.length) {
                    
                    this.showPopUp = true
                }
            },
            "drawObject.line"(newValue) {
                if (newValue) {
                    this.drawObject.circle = false
                    this.drawObject.polygon = false
                    this.drawObject.freehand = false
                }
            },
            "drawObject.circle"(newValue) {
                if (newValue) {
                    this.drawObject.line = false
                    this.drawObject.polygon = false
                    this.drawObject.freehand = false
                }
            },
            "drawObject.polygon"(newValue) {
                if (newValue) {
                    this.drawObject.line = false
                    this.drawObject.circle = false
                    this.drawObject.freehand = false
                }
            },
            "drawObject.freehand"(newValue) {
                if (newValue) {
                    this.drawObject.line = false
                    this.drawObject.circle = false
                    this.drawObject.polygon = false
                }
            },
        },
        mounted() {
            this.selectedColor = '#000000'
            this.backgroundColor = '#ffffff'
        },
        computed: {
            computedBoardNameExists() {
                return this.boards.findIndex(board=>{
                    return board.name === this.board.name
                }) > -1
            },
            computedDrawWhat() {
                if (this.drawObject.line) {
                    return 'line'
                }
                
                if (this.drawObject.circle) {
                    return 'circle'
                }

                if (this.drawObject.polygon) {
                    return 'polygon'
                }

                return 'freehand'
            },
        },
        methods: {
            startedResizing({componentInstance: theBoard}) {
                this.resizedBoard = theBoard.board
            },
            stoppedResizing(data) {
                this.resizedBoard = {}
            },
            stopResizingAndMoving() {
                this.resize = false
                this.move = false
            },
            clickedColor(value) {
                this.selectedColor = value
                this.showPopUp = false
            },
            setActiveColor(color) {
                this.$refs.activecolor.style.background = color
            },
            clickedPopupResponse(data) {
                if (data === 'ok') {
                    this.addBoard()
                    return
                }
                
                if (data === 'yes') {
                    this.removeBoard()
                }

                this.showPopUp = false

                this.clearBoard()

                this.setPopUpDefault()
            },
            setPopUpDefault() {
                this.responses = ['ok', 'cancel']
                this.defaultResponse = 'cancel'
                this.popUpMessage = ''
                this.popUpHasResponses = true
                this.showItems = ''
            },
            setLineWidth($event) {
                this.lineWidth = $event.target.value
                this.showPopUp = false
            },
            clickedButton(data) {
                if (data === 'add board') {
                    this.resize = false
                    this.showBoard()
                    return
                }

                if (data === 'tools') {
                    this.showTools()
                    return
                }

                if (data === 'colors') {
                    this.showItem = this.showItem.length ? '' : 'colors'
                    return
                }
            },
            editBoard(data) {
                this.board.id = data.id
                this.board.name = data.name

                this.showBoard()
            },
            deleteBoard(data) {
                this.responses = ['yes', 'no']
                this.defaultResponse = 'no'
                this.popUpMessage = 'are sure you want to delete this board'
                this.popUpHasResponses = false
                
                this.showItems = 'board form'
                this.showPopUp = true

                this.board.id = data.id
                this.board.name = data.name
            },
            removeBoard() {
                let index = this.getBoardIndex(this.board)

                if (index === -1) {
                    return
                }

                this.boards.splice(index, 1)
                this.adjustHeight()
            },
            adjustHeight() {
                if (this.boards.length < 2) {
                    this.$el.style.height = `${window.innerHeight}px`
                    return
                }

                this.$el.style.height = `${window.innerHeight * this.boards.length}px`
            },
            showBoard() {
                this.popUpHasResponses = true
                this.showItem = 'board form'
                this.showPopUp = true
            },
            showTools() {
                this.popUpHasResponses = false
                this.showItem = 'tools'
                this.showPopUp = true
            },
            afterDangerAlert(message) {
                if (message === `sorry, a board with name ${this.board.name} already exists. choose a different name.`) {
                    
                    this.showPopUp = true
                }
            },
            addBoard() {
                if (! this.board.name.length) {
                    this.board.name = `board ${this.boards.length + 1}`
                }

                if (this.computedBoardNameExists) {
                    this.issueDangerAlert({
                        message: `sorry, a board with name ${this.board.name} already exists. choose a different name.`
                    })
                    return
                }

                if (! this.board.id) {
                    this.board.id = Math.floor(Math.random() * 1000000)
                    this.boards.push(_.clone(this.board))                    
                }
                
                if (this.board.id) {
                    this.updateBoard()
                }

                this.adjustHeight()
                this.clearBoard()
            },
            updateBoard() {
                let index = this.getBoardIndex(this.board)

                if (index === -1) {
                    return
                }

                this.boards.splice(index, 1, _.clone(this.board))
            },
            getBoardIndex(searchBoard, by = 'id') {
                return this.boards.findIndex(board=>{
                    return board[by] == searchBoard[by]
                })
            },
            clearBoard() {
                this.board.id = null
                this.board.name = ''
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>