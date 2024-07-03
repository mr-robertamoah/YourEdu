<template>
    <just-fade>
        <template slot="transition" v-if="show">
            <div>
                <div class="search-outer-wrapper"
                    @click.self="clearData"
                ></div>
                <div class="search-output-wrapper">
                    <div class="close"
                        @click="clearData"
                    >
                        <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                    </div>
                    <div class="what">
                        <div class="search-output-button"
                            @click="clickedButton('all')"
                            :class="{active: searchType === 'all'}"
                        >all</div>
                        <div class="search-output-button"
                            @click="clickedButton('learners')"
                            :class="{active: searchType === 'learners'}"
                        >learners</div>
                        <div class="search-output-button"
                            @click="clickedButton('parents')"
                            :class="{active: searchType === 'parents'}"
                        >parents</div>
                        <div class="search-output-button"
                            @click="clickedButton('professionals')"
                            :class="{active: searchType === 'professionals'}"
                        >professionals</div>
                        <div class="search-output-button"
                            @click="clickedButton('schools')"
                            :class="{active: searchType === 'schools'}"
                        >schools</div>
                        <div class="search-output-button"
                            @click="clickedButton('posts')"
                            :class="{active: searchType === 'posts'}"
                        >posts</div>
                        <div class="search-output-button"
                            @click="clickedButton('discussions')"
                            :class="{active: searchType === 'discussions'}"
                        >discussions</div>
                        <div class="search-output-button"
                            @click="clickedButton('reads')"
                            :class="{active: searchType === 'reads'}"
                        >reads</div>
                        <div class="search-output-button"
                            @click="clickedButton('facilitators')"
                            :class="{active: searchType === 'facilitators'}"
                        >facilitators</div>
                    </div>
                    <div class="output-section">
                        <template
                            v-if="computedData.length"
                        >
                            <div class="data-section"
                                v-for="data in computedData"
                                :key="data.id"
                                :class="{account:data.hasOwnProperty('account_id'),
                                post:data.hasOwnProperty('addedby_id')}"
                            >
                                <account-badge
                                    v-if="data.hasOwnProperty('account_id')"
                                    :account="data"
                                    @addMyfollow="addMyfollow"
                                    @removeMyfollow="removeMyfollow"
                                ></account-badge>
                                <div class="item"
                                    v-if="data.hasOwnProperty('addedby_id')"
                                    @click="clickedViewPost(data)"
                                >
                                    <div class="top">
                                        <div class="name">{{data.name}}</div>
                                        <div class="account-type">{{data.addedby_type}}</div>
                                    </div>
                                    <div class="lower">

                                        <div class="profile-picture">
                                            <profile-picture>
                                                <template slot="image">
                                                    <img :src="data.url" >
                                                </template>
                                            </profile-picture>
                                        </div>
                                        <div class="text">
                                            {{data.content.length ? shortenText(data.content) : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="load-more"
                        @click="clickedLoadMore"
                        v-if="!loading && loadMore"
                    >
                        <font-awesome-icon :icon="['fa','ellipsis-h']"></font-awesome-icon>
                    </div>
                    <div class="no-more"
                        v-if="!loading && !loadMore"
                    >
                        nothing more ...
                    </div>
                </div>
            </div>
        </template>
    </just-fade>
</template>

<script>
import JustFade from './transitions/JustFade.vue'
import ProfilePicture from './profile/ProfilePicture.vue'
import AccountBadge from './dashboard/AccountBadge.vue'
import { strings } from '../services/helpers'
import { mapGetters } from 'vuex'

    export default {
        components: {
            AccountBadge,
            ProfilePicture,
            JustFade,
        },
        props: {
            show: {
                type: Boolean,
                default: false
            },
            loadMore: {
                type: Boolean,
                default: false
            },
            loading: {
                type: Boolean,
                default: false
            },
            propSearchType: {
                type: String,
                default: '',
            },
            nextPage: {
                type: Number,
                default: 1,
            },
            searchData: {
                type: Array,
                default(){
                    return []
                }
            },
        },
        data() {
            return {
                searchType: 'all',
            }
        },
        watch: {
            propSearchType: {
                immediate: true,
                handler(newValue){
                    if (newValue !== this.searchType) {
                        this.searchType = newValue
                    }
                }
            },
        },
        computed: {
            computedData() {
                return this.searchData
            },
        },
        methods: {
            clickedButton(data) {
                this.searchType = data
                this.$emit('clickedButton', data)
            },
            clickedViewPost(data) {
                this.searchType = data
                this.$emit('clickedViewPost', data)
            },
            clickedLoadMore(){
                this.$emit('clickedLoadMore')
            },
            shortenText(text){
                return strings.trim(text, 200)
            },
            removeMyfollow(data){
                this.$emit('removeMyfollow',data)
            },
            addMyfollow(data){
                this.$emit('addMyfollow',data)
            },
            clearData(){
                this.$emit('clearData')
            },
        },
    }
</script>

<style lang="scss" scoped>
$first-color: aliceblue;
$second-color: rebeccapurple;

    .search-outer-wrapper{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
    }

    .search-output-wrapper{
        width: 60%;
        height: 80vh;
        top: 10vh;
        left: 20%;
        padding: 10px;
        position: fixed;
        border-radius: 5px;
        background-color: $first-color;
        box-shadow: 0 0 3px grey;
        overflow-y: auto;

        .load-more,
        .no-more{
            text-align: center;
            width: fit-content;
            margin: 5px auto;
            cursor: pointer; 
            padding: 5px;

            &:hover{
                box-shadow: 0 0 2px;
            }           
        }

        .load-more{
            font-size: 20px;
            font-weight: 500;
            color: gray;

            &:hover{
                color: green;
            }
        }

        .no-more{
            color: green;
            font-size: 12px ;
        }


        .close{
            position: fixed;
            right: 22%;
            padding: 5px;
            color: gray;
            cursor: pointer;
            z-index: 1;

            &:hover{
                color: red;
            }
        }

        .what{
            width: 90%;
            margin: 0 auto 10px;
            display: flex;
            flex-wrap: wrap;
            position: relative;

            .search-output-button{
                font-size: 12px;
                padding: 5px;
                margin-right: 10px;
                cursor: pointer;
                box-shadow: 0 0 2px grey;
                border-radius: 10px;
                margin-bottom: 10px;
                background: gray;
                color: white;
                min-width: 30px;
                text-align: center;

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

        .output-section{
            display: flex;
            width: 100%;
            flex-wrap: wrap;
            padding: 10px;

            .data-section{
                cursor: pointer;
            }

            .account{
                width: 40%;
                margin: 5px 10px;
                min-width: 150px;
            }

            .post{
                width:80%;
                margin: 5px auto;
                border: none;

                .top{
                    display: inline-flex;
                    width: 100%;
                    justify-content: space-between;
                    align-items: center;

                    .name{
                        font-size: 12px;
                        font-weight: 500;
                        text-transform: capitalize;
                    }

                    .account-type{
                        font-size: 10px;
                    }
                }

                .lower{
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;

                    .profile-picture{
                        width: 30px;
                        height: 30px;
                    }

                    .text{
                        width: 90%;
                        font-size: 14px;
                        background: rgb(128 128 128 / .2);
                        padding: 10px;
                        border-radius: 10px;
                    }
                }
                
            }
        }
    }

@media screen and (max-width: 800px) {
    .search-output-wrapper{
        width: 85%;
        height: 90vh;
        top: 8vh;
        left: 7.5%;

        .close{
            right: 12%;
        }
    }
}

@media screen and (max-width: 500px) {
    .search-output-wrapper{
        width: 100%;
        height: 90vh;
        top: 8vh;
        left: 0;

        .close{
            right: 5%;
        }

        .output-section{
            justify-content: center;

            .account{
                width: 70%;
            }
        }
    }
}
</style>