<template>
    <div class="main-select-wrapper" @focus="clickedSelect" @keydown.tab="clickedSelect">
        <div class="selected-section"
            @click.self="clickedSelect"
            ref="selectedsection"
            :class="{active:value.length,'has-radius':hasRadius}"
        >
            {{value.length ? value : placeholder}}
            <div class="icon" @click="clickedSelect">
                <font-awesome-icon 
                    :icon="['fa','chevron-down']"
                    v-if="activeIcon === 'up'"
                ></font-awesome-icon>
                <font-awesome-icon 
                    :icon="['fa','chevron-up']"
                    v-if="activeIcon === 'down'"
                ></font-awesome-icon>
            </div>
        </div>
        <div class="dropdown-section"
            :class="{active:activeIcon === 'down'}"
            ref="dropdownsection"
        >
            <div class="dropdown-item"
                v-for="(item,index) in computedItems"
                :key="index"
                @click="clickedItem(item)"
                :class="{active:value === item}"
            >
                {{item.name ? item.name : item}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            placeholder: {
                type: String,
                default: 'select an item'
            },
            hasRadius: {
                type: Boolean,
                default: false
            },
            value: {
                type: String,
                default: ''
            },
            backgroundColor: {
                type: String,
                default: ''
            },
            items: {
                type: Array,
                default(){
                    return []
                }
            },
            objects: {
                type: Array,
                default(){
                    return []
                }
            }
        },
        data() {
            return {
                activeIcon: 'up',
                inputValue: '',
            }
        },
        watch: {
            inputValue(newValue) {
                if (newValue) {                    
                    if (this.items.length) {
                        this.$emit('selection',newValue)
                        this.$emit('input',newValue)
                    } else {
                        let index = this.findObjectIndex(newValue)
                        if (index > -1) {
                            this.$emit('input', this.objects[index])
                            this.$emit('selection', this.objects[index])
                        }
                    }
                } else {
                    this.inputValue = ''
                }
            },
            items: {
                immediate:true,
                handler(newValue){
                    if (newValue.findIndex(item => item === this.inputValue) === -1) {
                        this.inputValue = ''
                    }
                }
            },
            value: {
                immediate: true,
                handler(newValue){
                    if (newValue !== this.inputValue) {
                        this.inputValue = newValue
                    }
                }
            },
        },
        mounted(){
            if (this.backgroundColor.length) {
                this.$refs.selectedsection.style.backgroundColor = `${this.backgroundColor}`
                this.$refs.dropdownsection.style.backgroundColor = `${this.backgroundColor}`
            }
        },
        computed: {
            computedItems() {
                return this.items.length ? this.items : this.objects 
            }
        },
        methods: {
            clickedSelect() {
                if (this.activeIcon === 'up') {
                    this.activeIcon = 'down'
                } else {
                    this.activeIcon = 'up'
                }
            },
            clickedItem(data){
                if (this.inputValue === data) {
                    this.inputValue = ''
                } else this.inputValue = data
                this.activeIcon = 'up'
            },
            findObjectIndex(name) {
                return this.objects.findIndex(object=>{
                    return object === name || object.name === name
                })
            },
        },
    }
</script>

<style lang="scss" scoped>
$border-color-main: rgba(22, 233, 205, 1);

    .main-select-wrapper{
        position: relative;

        .selected-section{ 
            padding: 10px;
            border-bottom: 2px solid $border-color-main;
            position: relative;
            color: gray;
            cursor: pointer;

            .icon{
                position: absolute;
                top: 5px;
                right: 5px;
                cursor: pointer;
                padding: 5px;
                color: gray;
            }
        }

        .has-radius{
            border-radius: 10px;
        }

        .selected-section.active{
            color: black;
        }

        .dropdown-section{
            border: 1px solid $border-color-main;
            width: 90%;
            margin: 0 auto;
            border-radius: 5px;
            height: fit-content;
            max-height: 0;
            overflow-y: hidden;
            opacity: 0;
            position: absolute;

            .dropdown-item{
                font-size: 16px;
                color: gray;
                cursor: pointer;

                &:hover{
                    background-color: mintcream;
                }
            }

            .dropdown-item.active{
                background-color: mintcream;
                color: black;
            }
        }

        .dropdown-section.active{
            transition: all .4s ease;
            max-height: 200px;
            overflow-y: auto;
            margin-top: 10px;
            opacity: 1;
            z-index: 1;
        }
    }

@media screen and (max-width:800px) {
    
    .main-select-wrapper{

        .selected-section{
            font-size: 14px;
        }

        .dropdown-section{

            .dropdown-item{
                font-size: 14px;
            }
        }
    }
}
</style>