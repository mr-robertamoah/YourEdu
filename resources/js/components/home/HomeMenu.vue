<template>
    <div class="home-menu-wrapper">
        <div class="home-menu-item">
            <div 
                class="input"
                v-for="(item, index) in items"
                :key="index"
            >
                <input type="radio" :value="item" v-model="value" :id="item">
                <label :for="item">{{item}}</label>
            </div>
        </div>
        <div class="attachments" v-if="showAttachment">
            <post-attachment
                :show="showAttachment"
                :items="attachments"
                :hasSelect="true"
                @clickedAttachmentSelection="clickedAttachmentSelection"
                @hidePostAttachment="hidePostAttachment"
            ></post-attachment>
        </div>
    </div>
</template>

<script>
import PostAttachment from '../PostAttachment.vue';
    export default {
        props: {
            menuValue: {
                type: String,
                default: ''
            },
            items: {
                type: Array,
                default() {
                    return [
                        'all',
                        'mine',
                        'followers',
                        'followings',
                        'attachments'
                    ]
                }
            }
        },
        components: {
            PostAttachment,
        },
        data() {
            return {
                value: '',
                showAttachment: false,
                attachments: [],
            }
        },
        watch: {
            value(newValue, oldValue) {
                if (newValue === 'attachments') {
                    this.showAttachment = true
                } else {
                    this.showAttachment = false
                }
                this.$emit('emitMenuValue',newValue)
            },
            menuValue: {
                immediate: true,
                handler(newValue){
                    if (newValue === 'attachments' ||
                        newValue === 'all' || 
                        newValue === 'followers' ||
                        newValue === 'mine' ||
                        newValue === 'followings') {
                        this.value = newValue
                    }
                }
            },
            showAttachment(newValue){
                if (!newValue) {
                    // this.value = 'all'
                }
            },
        },
        methods: {
            clickedAttachmentSelection(data) {
                this.showAttachment = false
                this.$emit('clickedAttachmentSelection',data)
            },
            hidePostAttachment(){
                this.showAttachment = false
            },
        },
    }
</script>

<style lang="scss" scoped>

    .home-menu-wrapper{
        padding: 10px;

        .input{
            display: inline-flex;
            align-items: center;
            position: relative;

            input[type='radio']{
                display: none;                
            }

            label{
                color: gray;
                font-weight: normal;
                font-size: 12px;
                margin: 5px;
                cursor: pointer;
                
                &::before{
                    content: '';
                    position: relative;
                    top: 3px;
                    margin: 0 5px 0 0;
                    display: inline-flex;
                    width: 20px;
                    height: 20px;
                    border-radius: 11px;
                    border: 2px solid gray;
                    background-color: transparent;
                }
            }

            input[type='radio']:checked + label::after{
                border-radius: 11px;
                width: 12px;
                height: 12px;
                position: absolute;
                top: 12px;
                left: 9px;
                content: '';
                display: block;
                background: green;
            }

            input[type='radio']:checked + label{
                color: green;
            }

            input[type='radio']:checked + label::before{
                border-color: green;
            }
        }

        .attachments{
            max-width: 500px;

            .no-attachments{
                width: 100%;
                text-align: center;
                padding: 5px;
                font-size: 14px;
            }
        }
    }
</style>