<template>
    <div class="w-80 h-80" v-if="board">
        <div class="text-xs absolute ml-1  mt-1 p-1 z-10 cursor-pointer"
            @dblclick="editBoard"
            :class="[backgroundColor === 'white' ? 'text-gray-500' : 'text-gray-100']"
        >{{board.name}}</div>
        
        <div class="text-xs absolute right-0 ml-1  mt-1 p-1 z-10 cursor-pointer"
            @dblclick="deleteBoard"
            :class="[backgroundColor === 'white' ? 'text-gray-500' : 'text-gray-100']"
        >
            <font-awesome-icon
                :icon="['fa', 'trash']"
            ></font-awesome-icon>
        </div>

        <canvas 
            class="w-full h-full max-w-2xl mx-auto mt-2 border-2" 
            ref="canvas"
            @mousedown.prevent="start"
            @mousemove.prevent="draw"
            @mouseup.prevent="stop"
            @mouseout.prevent="stop"
        ></canvas>
        <div class="flex justify-center items-center bg-whitesmoke">                
            <button 
                class="text-gray-500 p-1 mx-2 text-sm border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded"
                @click="clickedButton('undo')"
            >undo</button>
            <button 
                class="text-gray-500 p-1 mx-2 text-sm border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded"
                @click="clickedButton('clear')"
            >clear</button>
            <font-awesome-icon
                class="cursor-pointer mx-2 text-2xl"
                :icon="['fa', `toggle-${toggleType}`]"
                title="toggle between black and white board"
                @click="toggleBackgroundType"
            ></font-awesome-icon>
        </div>
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture.vue'
    export default {
        components: {
            ProfilePicture,
        },
        props: {
            board: {
                type: Object,
                default() {
                    return null
                }
            },
            activeColor: {
                type: String,
                default: ''
            },
            resize: {
                type: Boolean,
                default: false
            },
            what: {
                type: String,
                default: 'freehand'
            },
            sides: {
                default: 3
            },
            angle: {
                default: 0
            },
            lineWidth: {
                default: 1
            },
        },
        mounted () {
            this.initiateCanvas()

            this.$el.addEventListener('resize', this.resizeCanvas())
        },
        beforeDestroy () {
            this.$el.removeEventListener('resize', this.resizeCanvas())
        },
        data() {
            return {
                context: null,
                isDrawing: false,
                backgroundColor: null,
                toggleType: 'on',
                drawingsData: [],
            }
        },
        watch: {
            backgroundColor(newValue) {
                if (newValue) {
                    this.changeBackgroundColor()
                }
            },
            resize(newValue) {
                if (! newValue) {
                    this.resizeCanvas()
                    this.clearRectAndPutImageData()
                }
            },
            lineWidth(newValue) {
                this.stop()
            },
            activeColor(newValue) {
                this.stop()
            },
            sides(newValue) {
                this.stop()
            },
            angle(newValue) {
                this.stop()
            },
            what(newValue) {
                this.stop()
            },
            toggleType(newValue) {
                if (newValue) {
                    this.toggleBackground()
                }
            },
        },
        methods: {
            editBoard() {
                this.$emit('editBoard', this.board)
            },
            deleteBoard() {
                this.$emit('deleteBoard', this.board)
            },
            initiateCanvas() {
                this.resizeCanvas()

                this.context = this.$refs.canvas.getContext('2d')

                this.backgroundColor = 'white'
                this.clearContext()
            },
            resizeCanvas() {
                let height = this.getCanvasParentNodeHeight()
                let width = this.getCanvasParentNodeWidth()

                this.$refs.canvas.style.height =  `${height}px`
                this.$refs.canvas.style.width =  `${width}px`

                this.$refs.canvas.width = width
                this.$refs.canvas.height = height
            },
            getCanvasParentNodeHeight() {
                return Number(getComputedStyle(this.$refs.canvas.parentNode).height.replace('px',''))
            },
            getCanvasParentNodeWidth() {
                let width = Number(getComputedStyle(this.$refs.canvas.parentNode).width.replace('px',''))

                return width > 675 ? 675 : width
            },
            start($event) {
                this.isDrawing = true
                this.context.beginPath()
                this.context.moveTo(this.getClientX($event),this.getClientY($event))
            },
            clearContext() {
                this.drawingsData = []
                this.clearRect()
            },
            clearRect() {
                this.context.fillStyle = this.backgroundColor
                this.context.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height)
                this.context.fillRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height)
            },
            changeBackgroundColor() {
                this.clearRectAndPutImageData()
            },
            clearRectAndPutImageData() {
                this.clearRect()
                this.putContextImageData()
            },
            draw($event) {
                if (!this.isDrawing) {
                    return
                }
                this.context.strokeStyle = this.activeColor
                this.context.lineWidth = Number( this.lineWidth)
                this.context.lineCap = 'round'
                this.context.lineJoin = 'round'
                this.context.lineTo(this.getClientX($event), this.getClientY($event))
                this.context.stroke()
            },
            getClientX($event) {
                return $event.layerX - this.$refs.canvas.offsetLeft
            },
            getClientY($event) {
                return $event.layerY - this.$refs.canvas.offsetTop
            },
            stop() {
                if (!this.isDrawing) {
                    return
                }
                
                this.context.closePath()
                this.isDrawing = false
                this.saveData()
            },
            saveData() {
                this.drawingsData.push(this.getContextImageData())
            },
            clickedButton(text) {
                if (text == 'clear') {
                    this.clearContext()
                    return
                }

                this.undoDrawing()
            },
            undoDrawing() {
                this.drawingsData.pop()

                if (!this.drawingsData.length) {
                    this.clearContext()
                    return
                }
                this.putContextImageData()
            },
            getContextImageData() {
                return this.context.getImageData(0, 0, this.$refs.canvas.width, this.$refs.canvas.height)
            },
            putContextImageData() {
                if (!this.drawingsData.length) {
                    return
                }

                this.context.putImageData(this.drawingsData[this.drawingsData.length - 1], 0, 0)
            },
            toggleBackgroundType() {
                this.toggleType = this.toggleType === 'on' ?
                    'off' : 'on'
            },
            toggleBackground() {
                this.backgroundColor = this.backgroundColor === 'white' ?
                    'black' : 'white'
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>