<template>
    <div class="home-side-wrapper" ref="checkinputwrapper">
        <div 
            v-for="(item, index) in items"
            :key="index"
            class="home-side-item"
            @click="clickedItem(item)"
            :class="{active: value === item}"
        >
            {{item}}
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            sideValue: {
                type: String,
                default: ''
            },
            items: {
                type: Array,
                default() {
                    return [
                        'posts',
                        'discussions',
                        'assessments',
                        'reads',
                        'books',
                        'questions',
                        'poems',
                        'riddles',
                        'activities'
                    ]
                }
            }
        },
        data() {
            return {
                value: ''
            }
        },
        watch: {
            sideValue: {
                immediate: true,
                handler(newValue){
                    if (this.items.includes(newValue)) {
                        this.value = newValue
                    }
                }
            }
        },
        methods: {
            clickedItem(data) {
                this.value = data
                this.$emit('clickedItem',data)
            }
        },
    }
</script>

<style lang="scss" scoped>

    .home-side-wrapper{
        padding: 10px;
        display: flex;
        align-items: center;
        width: 100%;
        flex-wrap: wrap;

        .home-side-item{
            font-size: 12px;
            padding: 5px;
            margin-right: 10px;
            cursor: pointer;
            box-shadow: 0 0 2px grey;
            border-radius: 10px;
            margin-bottom: 10px;
            background: gray;
            color: white;

            &:hover{
                box-shadow: 0 0 3px grey;
                background: green;
                transition: all .5s ease;
            }
        }

        .active{
            box-shadow: 0 0 3px grey;
            background: green;
            transition: all .5s ease;
        }
    }
</style>