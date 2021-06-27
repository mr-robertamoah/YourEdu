<template>
    <div class="bg- w-full h-screen">
        <div class="relative w-full h-full">
            <canvas 
                class="w-full h-full max-w-2xl mx-auto mt-2 border-2" 
                ref="canvas"
                @mousedown.prevent="start"
                @mousemove.prevent="draw"
                @mouseup.prevent="stop"
                @mouseout.prevent="stop"
            ></canvas>
            <div class="absolute z-auto left-3 bottom-3">
                <template v-if="showColors">
                    <div
                        class="w-8 h-8 my-3 cursor-pointer hover:w-12 hover:h-12 rounded-full"
                        v-for="(color, index) in colors"
                        :key="index"
                        :class="[color ? `bg-${color}-500` : 'bg-black']"
                        @click="selectedColor = color"
                    ></div>

                </template>
                
                <div 
                    @click="showColors = !showColors"
                    ref="activecolor" 
                    class="w-8 h-8 cursor-pointer rounded-full border-gray-900 border-2"
                ></div>
                <div>
                    <input type="color" :value="selectedColor" @input="selectedColor = $event.target.value" title="more colors">
                    <input type="range" :value="setLineWidth" @change="setLineWidth" @input="setLineWidth" title="select line width" min="1" max="100">
                </div>
            </div>
        </div>
        <div class="w-full flex justify-start items-center">
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
    </div>
</template>

<script>
import ProfilePicture from './profile/ProfilePicture'
    export default {
        components: {
            ProfilePicture,
        },
        props: {
            colors: {
                type: Array,
                default() {
                    return [
                        'red',
                        'yellow',
                        'green',
                        'blue',
                        'gray',
                        'indigo',
                        'purple',
                        'pink'
                    ]
                }
            },
        },
        mounted () {
            this.selectedColor = 'black'
            this.initiateCanvas()

            window.addEventListener('resize', this.resizeCanvas())

            this.backgroundColor = 'white'
        },
        data() {
            return {
                accounts: [],
                selectedColor: null,
                lineWidth: 1,
                showColors: false,
                context: null,
                isDrawing: false,
                backgroundColor: null
            }
        },
        watch: {
            selectedColor(newValue) {
                if (newValue) {
                    this.setActiveColor(newValue)
                }
            },
            backgroundColor(newValue) {
                if (newValue) {
                    this.clearContext()
                }
            }
        },
        methods: {
            setActiveColor(color) {
                this.$refs.activecolor.style.background = color
                this.stop()
            },
            initiateCanvas() {
                this.resizeCanvas()

                this.context = this.$refs.canvas.getContext('2d')

                this.clearContext()
            },
            resizeCanvas() {
                this.$refs.canvas.style.height =  `${this.getCanvasParentNodeHeight()}px`
                this.$refs.canvas.style.width =  `${this.getCanvasParentNodeWidth()}px`

                this.$refs.canvas.width = this.getCanvasParentNodeWidth()
                this.$refs.canvas.height = this.getCanvasParentNodeHeight()
            },
            setLineWidth($event) {
                this.lineWidth = $event.target.value
                this.stop()
            },
            getCanvasParentNodeHeight() {
                return this.$refs.canvas.parentNode.clientHeight
            },
            getCanvasParentNodeWidth() {
                return this.$refs.canvas.parentNode.clientWidth > 672 ? 675 : this.$refs.canvas.parentNode.clientWidth
            },
            start($event) {
                this.isDrawing = true
                this.context.beginPath()
                this.context.moveTo(this.getClientX($event), this.getClientY($event))
            },
            clearContext() {
                this.context.fillStyle = this.backgroundColor
                this.context.fillRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height)
            },
            draw($event) {
                if (!this.isDrawing) {
                    return
                }
                this.context.strokeStyle = this.selectedColor
                this.context.lineWidth = Number( this.lineWidth)
                this.context.lineCap = 'rounded'
                this.context.lineJoin = 'rounded'
                this.context.lineTo(this.getClientX($event), this.getClientY($event))
                this.context.stroke()
            },
            getClientX($event) {
                return $event.clientX - this.$refs.canvas.offsetLeft
            },
            getClientY($event) {
                return $event.clientY - this.$refs.canvas.offsetTop
            },
            stop() {
                if (!this.isDrawing) {
                    return
                }
                
                this.context.stroke()
                this.context.closePath()
                this.isDrawing = false
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>