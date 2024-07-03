<template>
    <div class="text-array-wrapper my-2" v-if="show">
        <div class="add" 
            @click="pushSection"
            title="add another section"
        >
            <font-awesome-icon
                :icon="['fa','plus']"
            ></font-awesome-icon>
        </div>
        <div class="minus" 
            @click="popSection"
            v-if="theArray.length > 0"
            title="remove current section"
        >
            <font-awesome-icon
                :icon="['fa','minus']"
            ></font-awesome-icon>
        </div>
        <div class="form-edit"
        >
            <text-textarea type="text"  
                @keyup.enter="pushSection"
                :placeholder="placeholder" 
                :error="inputError" 
                :bottomBorder="true"
                v-model="input"></text-textarea>
        </div>
        <p
            v-if="example.length"
        >{{`eg. ${example}`}}</p>
        <div class="show-list-button"
            @click="clickedShowList"
            :class="{showActive:showList}"
            v-if="theArray.length"
        >
            {{showListText}}
        </div>
        <div class="show-list" v-if="showList">
            <div class="array-list"
                :key="key"
                v-for="(item,key) in theArray"
            >
                {{item}}
            </div>
            <div class="array-list" v-if="computedShowLastItem">
                {{computedLastItem}}
            </div>
        </div>
        <div class="lower">
            <post-button
                buttonText="previous"
                :makeDisabled="disablePrevious"
                @click="clickedPrevious"
                title="previous section"
            ></post-button>
            <post-button
                buttonText="next"
                :makeDisabled="disableNext"
                @click="clickedNext"
                title="next section"
            ></post-button>
        </div>
    </div>
</template>

<script>
import TextTextarea from './TextTextarea.vue'
import PostButton from './PostButton.vue'

    export default {
        components: {
            PostButton,
            TextTextarea
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            edit: {
                type: Boolean,
                default: false
            },
            example: {
                type: String,
                default: ''
            },
            propsArray: {
                type: Array,
                default(){
                    return []
                }
            },
            error: {
                type: Boolean,
                default: false
            },
            placeholder: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                inputError: false,
                theArray: [],
                currentArrayPosition: 0, //holds the value of current array postion
                arrayPositions: 0,//max number of array positions so +1 gives array length
                input: '',
                temporaryInput: '', //holds the last unpushed input value when clicked previous
                showListText: 'show list',
                showList: false,
            }
        },
        watch: {
            showList(value){
                if (value) {
                    this.showListText = 'hide list'
                } else {
                    this.showListText = 'show list'
                }
            },
            input(newValue) {
                this.inputError = false
                if (newValue && newValue != '' && 
                    this.currentArrayPosition < this.arrayPositions) {
                    this.theArray[this.currentArrayPosition] = newValue //editing existing section
                }
                this.$emit('textArrayData',{
                    input: newValue,
                    temporaryInput: this.temporaryInput,
                    inputArray: this.theArray,
                    positions: this.arrayPositions,
                    currentPosition: this.currentArrayPosition
                })
            },
            temporaryInput(newValue) {
                this.$emit('textArrayData',{
                    input: this.input,
                    temporaryInput: newValue,
                    inputArray: this.theArray,
                    positions: this.arrayPositions,
                    currentPosition: this.currentArrayPosition
                })
            },
            theArray: {
                immediate: true,
                handler(newValue){
                    this.$emit('textArrayData',{
                        input: this.input,
                        temporaryInput: this.temporaryInput,
                        inputArray: newValue,
                        positions: this.arrayPositions,
                        currentPosition: this.currentArrayPosition
                    })
                },
                deep: true
            },
            error: { 
                immediate: true,
                handler(newValue){
                    this.inputError = newValue
                },
            },
            edit: { // for editing 
                immediate: true,
                handler(newValue){
                    this.theArray = this.propsArray
                },
            },
        },
        computed: {
            disablePrevious(){
                return this.currentArrayPosition -1  < 0 ? true : false
            },
            disableNext(){
                return this.input && this.input.trim() != '' ?
                    false : this.currentArrayPosition +1  > this.arrayPositions ? true : false
            },
            computedLastItem(){
                if (this.temporaryInput || this.input) {
                    if (this.currentArrayPosition === this.arrayPositions) {
                        return this.input
                    } else {
                        return this.temporaryInput
                    }
                } else {
                    return ''
                }
            },
            computedShowLastItem(){
                return this.theArray && this.theArray[this.theArray.length - 1] !== this.computedLastItem
                    ? true : false
            },
        },
        methods: {
            clickedShowList(){
                this.showList = !this.showList
            },
            pushSection(){
                if (this.input && this.input.trim() != '') {
                    if (this.arrayPositions === this.currentArrayPosition) { //at last position
                        this.theArray.push(this.input)
                    } else {
                        this.theArray[this.currentArrayPosition] = this.input
                    }
                    this.input = ''
                    this.arrayPositions++
                    this.currentArrayPosition++
                }else {
                    this.inputError = true
                }
            },
            popSection(){
                if (this.currentArrayPosition === this.arrayPositions) {
                    this.input = ''
                } else {
                    this.theArray.pop(this.currentArrayPosition)
                    if (this.currentArrayPosition - 1 >= 0) {
                        this.currentArrayPosition -= 1
                    }

                    if (this.arrayPositions - 1 >= 0) {
                        this.arrayPositions -= 1
                    }
                }
                if (this.currentArrayPosition === 0 && this.arrayPositions === 0) {
                    this.input = ''
                }
            },
            clickedPrevious(){
                if (this.currentArrayPosition === this.arrayPositions) {
                    this.temporaryInput = this.input
                }
                
                this.currentArrayPosition-=1
                this.input = this.theArray[this.currentArrayPosition]
            },
            clickedNext(){
                if (this.currentArrayPosition === this.arrayPositions) {
                    this.arrayPositions+=1
                    this.currentArrayPosition+=1
                    this.theArray.push(this.input)
                } else{
                    this.currentArrayPosition+=1
                }

                if (this.currentArrayPosition === this.arrayPositions) {
                    this.input = this.temporaryInput
                }else {
                    this.input = this.theArray[this.currentArrayPosition]
                }
                
            },
        },
    }
</script>

<style lang="scss" scoped>
$input-color: rgba(22, 233, 205, 1);

    .text-array-wrapper{
        width: 100%;
        position: relative;
        display: block;

        p{
            font-size: 12px;
            color: gray;
        }

        .add{
            position: absolute;
            top: 0;
            right: 0;
            font-size: 16px;
            color: $input-color;
            cursor: pointer;
        }

        .minus{
            position: absolute;
            top: 0;
            right: 0;
            margin-top: 25px;
            font-size: 16px;
            color: red;
            cursor: pointer;
        }

        .lower{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }

        .show-list-button{
            font-size: 14px;
            cursor: pointer;
            padding: 5px;
            margin-left: 5px;
            width: fit-content;

            &:hover{
                box-shadow: 0 0 3px gray;
                transition: all 1s ease;
            }
        }
        
        .showActive{
            box-shadow: 0 0 3px gray;
            transition: all 1s ease;
        }

        .show-list{
            width: 100%;
            font-size: 12px;
            text-align: center;

            .array-list{
                width: 80%;
                margin: 0 auto;
                padding: 5px;
            }
        }
    }
</style>