<template>
    <div class="home-side-wrapper" ref="checkinputwrapper">
        <div class="home-side-item"
            @click="clickedItem('posts')"
            :class="{active: value === 'posts'}"
        >
            posts
        </div>
        <div class="home-side-item"
            @click="clickedItem('reads')"
            :class="{active: value === 'reads'}"
        >
            reads
        </div>
        <div class="home-side-item"
            @click="clickedItem('discussions')"
            :class="{active: value === 'discussions'}"
        >
            discussions
        </div>
        <div class="home-side-item"
            @click="clickedItem('questions')"
            :class="{active: value === 'questions'}"
        >
            questions
        </div>
        <div class="home-side-item"
            @click="clickedItem('riddles')"
            :class="{active: value === 'riddles'}"
        >
            riddles
        </div>
        <div class="home-side-item"
            @click="clickedItem('activities')"
            :class="{active: value === 'activities'}"
        >
            activities
        </div>
        <div class="home-side-item"
            @click="clickedItem('poems')"
            :class="{active: value === 'poems'}"
        >
            poems
        </div>
        <div class="home-side-item"
            @click="clickedItem('books')"
            :class="{active: value === 'books'}"
        >
            books
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
                    if (newValue === 'posts' ||
                        newValue === 'reads' || 
                        newValue === 'discussions' ||
                        newValue === 'questions' ||
                        newValue === 'riddles' ||
                        newValue === 'poems' ||
                        newValue === 'activities' ||
                        newValue === 'books') {
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