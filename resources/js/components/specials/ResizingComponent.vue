<template>
    <div
        class="relative"
        :class="{'cursor-move': move}"
        @mousedown="startMoving"
        @dblclick="stop"
    >
        <div 
            :class="[resize || move ? 'absolute h-full w-full top-0 left-0 border-2 border-blue-300' : '']"
        >
            <template v-if="resize">
                <div 
                    class="absolute -left-1 -top-1 h-2 w-2 cursor-nwse 
                        rounded-full bg-blue-300"
                    @mousedown="startResize"
                    data-button="top-left"
                ></div>
                <div 
                    class="absolute -left-1 -bottom-1 h-2 w-2 cursor-nesw 
                        rounded-full bg-blue-300"
                    @mousedown="startResize"              
                    data-button="bottom-left"
                ></div>
                <div 
                    class="absolute -right-1 -top-1 h-2 w-2 cursor-nesw 
                        rounded-full bg-blue-300"
                    @mousedown="startResize"              
                    data-button="top-right"
                ></div>
                <div 
                    class="absolute -right-1 -bottom-1 h-2 w-2 cursor-nwse 
                        rounded-full bg-blue-300"
                    @mousedown="startResize"              
                    data-button="bottom-right"
                ></div>
            </template>
        </div>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            hasNegativeTop: {
                type: Boolean,
                default: false
            },
            resize: {
                type: Boolean,
                default: true
            },
            move: {
                type: Boolean,
                default: false
            },
            minWidth: {
                type: Number,
                default: 10
            },
            minHeight: {
                type: Number,
                default: 10
            },
            maxWidth: {
                type: Number,
                default: null
            },
            maxHeight: {
                type: Number,
                default: null
            },
            maxRight: {
                type: Number,
                default: null
            },
            maxBottom: {
                type: Number,
                default: null
            },
        },
        data() {
            return {
                isResizing: false,
                isMoving: false,
                sizeType: null,
                originalHeight: 0,
                originalWidth: 0,
                originalMouseX: 0,
                originalMouseY: 0,
                originalBoundingClientRectLeft: 0,
                originalBoundingClientRectTop: 0,
            }
        },
        watch: {
            isResizing(newValue, oldValue) {
                if (newValue) {
                    this.$emit('startedResizing', this.$slots.default[0])
                }

                if (! newValue) {
                    this.$emit('stoppedResizing', this.$slots.default[0])
                }
            },
            isMoving(newValue, oldValue) {
                if (newValue) {
                    this.$emit('startedMoving', this.$slots.default[0])
                }

                if (! newValue) {
                    this.$emit('stoppedMoving', this.$slots.default[0])
                }
            },
        },
        methods: {
            startMoving($event) {
                if (this.isResizing || !this.move) {
                    return
                }

                this.isMoving = true

                this.originalBoundingClientRectLeft = this.convertElementPropertyFromPixelsToNumber(this.$el, 'left')
                this.originalBoundingClientRectTop = this.convertElementPropertyFromPixelsToNumber(this.$el, 'top')

                this.originalMouseX = $event.pageX
                this.originalMouseY = $event.pageY

                window.addEventListener('mousemove', this.continueMoving)
                window.addEventListener('mouseup', this.stopMoving)
            },
            continueMoving($event) {
                this.setElementsStyleProperty('left', `${$event.pageX - this.originalMouseX + this.originalBoundingClientRectLeft}px`)
                this.setElementsStyleProperty('top', `${$event.pageY - this.originalMouseY + this.originalBoundingClientRectTop}px`)
            },
            stopMoving() {
                this.isMoving = false
                window.removeEventListener('mousemove', this.continueMoving)
                window.removeEventListener('mouseup', this.stopMoving)
            },
            startResize($event) {
                if (this.isMoving) {
                    return
                }

                this.isResizing = true

                this.originalHeight = this.convertElementPropertyFromPixelsToNumber(this.$slots.default[0].elm, 'height')
                this.originalWidth = this.convertElementPropertyFromPixelsToNumber(this.$slots.default[0].elm, 'width')
                this.originalBoundingClientRectLeft = this.$el.offsetLeft
                this.originalBoundingClientRectTop = this.convertElementPropertyFromPixelsToNumber(this.$el, 'top')

                this.originalMouseX = $event.pageX
                this.originalMouseY = $event.pageY

                if ($event.target.dataset.button) {
                    this.sizeType = $event.target.dataset.button
                }
                
                window.addEventListener('mousemove', this.continueResizing)
                window.addEventListener('mouseup', this.stopResizing)
            },
            continueResizing($event) {
                if (! this.isResizing) {
                    return
                }

                this.changeSize($event)
            },
            changeSize($event) {
                if (this.sizeType === 'top-left') {
                    this.toTopLeft($event)
                    return
                }
                
                if (this.sizeType === 'top-right') {
                    this.toTopRight($event)
                    return
                }
                
                if (this.sizeType === 'bottom-left') {
                    this.toBottomLeft($event)
                    return
                }
                
                this.toBottomRight($event)
            },
            stopResizing() {
                this.isResizing = false
                window.removeEventListener('mousemove', this.continueResizing)
                window.removeEventListener('mouseup', this.stopResizing)
            },
            adjustHeight(height) {
                
                if (this.maxBottom && 
                    this.originalBoundingClientRectTop + height > this.maxBottom) {
                    return this.maxBottom - this.originalBoundingClientRectTop
                }

                return height
            },
            convertElementPropertyFromPixelsToNumber(el, property) {
                let value = Number(getComputedStyle(el)[property].replace('px',''))

                return isNaN(value) ? 0 : value
            },
            toBottomLeft($event) {
                let addedWidth = $event.pageX - this.originalMouseX,
                    addedHeight = $event.pageY - this.originalMouseY

                this.updateElementAndSlotSize({
                    width: this.originalWidth - addedWidth,
                    height: this.adjustHeight(this.originalHeight + addedHeight),
                    addedWidth
                })
            },
            toBottomRight($event) {
                let addedWidth = $event.pageX - this.originalMouseX,
                    addedHeight = $event.pageY - this.originalMouseY

                this.updateElementAndSlotSize({
                    width: this.originalWidth + addedWidth,
                    height: this.adjustHeight(this.originalHeight + addedHeight)
                })
            },
            toTopLeft($event) {
                let addedWidth = $event.pageX - this.originalMouseX
                let addedHeight = $event.pageY - this.originalMouseY

                this.updateElementAndSlotSize({
                    width: this.originalWidth - addedWidth,
                    addedWidth,
                    height: this.adjustHeight(this.originalHeight - addedHeight),
                    addedHeight
                })
            },
            toTopRight($event) {
                let addedHeight = $event.pageY - this.originalMouseY

                this.updateElementAndSlotSize({
                    width: this.originalWidth + ($event.pageX - this.originalMouseX),
                    addedHeight,
                    height: this.adjustHeight(this.originalHeight - addedHeight)
                })
            },
            updateElementAndSlotSize({width, addedWidth, height, addedHeight}) {
                
                if (width >= this.minWidth && (!this.maxWidth || width <= this.maxWidth)) {
                    this.setElementsStyleProperty('width', `${width}px`)

                    if (addedWidth && this.originalBoundingClientRectLeft + addedWidth >= 0) {
                        
                        this.setElementsStyleProperty('left', `${this.originalBoundingClientRectLeft + addedWidth}px`)
                    }
                }

                if (height >= this.minHeight && (!this.maxHeight || height <= this.maxHeight)) {
                    this.setElementsStyleProperty('height', `${height}px`)
                    
                    if (addedHeight && (this.hasNegativeTop || this.originalBoundingClientRectTop + addedHeight >= 0)) {
                        
                        this.setElementsStyleProperty('top', `${this.originalBoundingClientRectTop + addedHeight}px`)
                    }
                }
            },
            setElementsStyleProperty(property, value) {
                this.$el.style[property] = this.$slots.default[0].elm.style[property] = value
            },
            stop() {
                this.$emit('stop')
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>