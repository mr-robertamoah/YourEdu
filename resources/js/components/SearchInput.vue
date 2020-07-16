<template>
    <div class="search-wrapper">
        <div class="input-wrapper">
            <input type="text" name="search" class="form-control input-text" 
                v-model="searchText"
                @change="search"
                :placeholder="searchPlaceholder">
            <div class="input-prepend" title="search"
                @click.prevent="search">
                <font-awesome-icon :icon="['fas','search']"></font-awesome-icon>
            </div>
        </div>

        <div class="output-section" v-if="outputSeection">
            <search-output>
                <template slot="main">
                    Robert Amoah
                </template>
            </search-output>
        </div>
    </div>
</template>

<script>
import SearchOutput from './SearchOutput'

    export default {
        components: {
            SearchOutput,
        },
        props: {
            searchPlaceholder: {
                type: String,
                default: 'search here'
            },
            outputSection: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                searchText: ''
            }
        },
        watch: {
            searchText(newValue) {
                console.log(newValue)
            }
        },
        methods: {
            search() {
                console.log('searching', this.searchText)
            }
        },
    }
</script>

<style lang="scss" scoped>
$search-color:rgba(22, 233, 205, 1);

    .search-wrapper{
        width: 80%;
        margin: 0 auto;

        .input-wrapper{
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border: 1px solid rgba(22, 233, 205, 1);
            border-radius: 50px;

            .input-text{
                min-width: 80%;
                border: none;
                font-size: 16px;
                box-shadow: none;
                border-radius: inherit;
            }
            
            .input-prepend{
                color: $search-color;
                padding: 5px 10px 5px 5px;
                cursor: pointer;
                border: 1px solid $search-color;
                border-radius: inherit;
                border-right: none;
            }
        }
    }


@media screen and (max-width: 800px){
    .search-wrapper{

        .input-wrapper{

            .input-text{
                font-size: 14px;
            }
        }
    }
}
</style>