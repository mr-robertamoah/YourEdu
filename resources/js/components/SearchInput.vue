<template>
    <div class="search-wrapper"
        :class="{background:background, home: homeSearch}"
    >
        <div class="input-search" title="search"
            @click.prevent="search"
            :class="{gray:paClass === 'gray'}"
        >
            <font-awesome-icon :icon="['fas','search']"></font-awesome-icon>
        </div>
        <input type="text" name="search" class="form-control input-text" 
            v-model="searchText"
            :placeholder="placeholder"
        >
        <div class="input-close"
            @click="clickedClose"
            :class="{gray: paClass === 'gray', closeActive:closeActive}"
            v-if="showClose"
        >
            <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
        </div>
    </div>
</template>

<script>

    export default {
        components: {
        },
        props: {
            placeholder: {
                type: String,
                default: 'search here'
            },
            homeSearch: {
                type: Boolean,
                default: false
            },
            background: {
                type: Boolean,
                default: true
            },
            value: {
                type: String,
                default: ''
            },
            paClass: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                searchText: '',
                closeActive: false,
                showClose: false
            }
        },
        watch: {
            searchText(newValue) {
                if (newValue.length) {
                    this.showClose = true
                } else {
                    this.showClose = false
                }
                this.$emit('search',newValue)
            },
            value(newValue){
                this.searchText = newValue
            },
        },
        methods: {
            search() {
                this.$emit('search',this.searchText)
            },
            clickedClose(){
                this.closeActive = true
                setTimeout(() => {
                    this.closeActive = false
                }, 2000);
                this.searchText = ''
            },
        },
    }
</script>

<style lang="scss" scoped>
$search-color:rgba(22, 233, 205, 1);

    .search-wrapper{
        width: 80%;
        margin: 0 auto;
        display: flex;
        justify-content:space-between;
        align-items: center;

        .input-text{
            min-width: 80%;
            border: none;
            font-size: 16px;
            box-shadow: none;
            border-radius: inherit;
            background: transparent;
        }
        
        .input-search,
        .input-close{
            color: $search-color;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 12px;
            width: 10%;
            text-align: center;
        }
        .gray{
            color: gray;
        }

        .closeActive{
            color: red;
        }
    }

    .home{

        input::placeholder,
        input{
            font-weight: 400;
            color: gray;
        }
    }
        
    .background{
        border: 1px solid rgba(22, 233, 205, 1);
    }


@media screen and (max-width: 800px){
    .search-wrapper{

        .input-text{
            font-size: 14px;
        }
    }
}
</style>