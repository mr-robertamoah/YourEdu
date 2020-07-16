<template>
    <div class="bar-wrapper" :class="{'bar-small':barSmall}" >
        <div class="icon" @click="switchIcon" :title="iconTitle">
            <font-awesome-icon :icon="icons"></font-awesome-icon>
        </div>
        <div class="main">
            <div class="item">
                <div class="item-text">
                    item 1
                </div>
                <div class="item-icon">
                    <font-awesome-icon :icon="['fas','eye']"></font-awesome-icon>
                </div>
                <div class="item-sub">
                    <div class="sub-item">
                        <div class="sub-text">
                            sub 1
                        </div>
                        <div class="sub-icon">
                            icon
                        </div>
                    </div>
                    <div class="sub-item">
                        <div class="sub-text">
                            sub 2
                        </div>
                        <div class="sub-icon">
                            icon
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="item-text">
                    item 2
                </div>
                <div class="item-sub">
                    <div class="sub-item">
                        <div class="sub-text">
                            sub 1
                        </div>
                        <div class="sub-icon">
                            icon
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="item-text">
                    item 3
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                icons: ['fas','bars'],
                barSmall: true,
                iconTitle: 'click to expand'
            }
        },
        methods: {
            switchIcon() {
                if (this.icons[1] === 'bars') {
                    this.icons  = ['fas','times']
                    this.barSmall = false
                    this.iconTitle = 'click to collapse'
                } else if (this.icons[1] === 'times') {
                    this.icons  = ['fas','bars']
                    this.iconTitle = 'click to expand'
                    this.barSmall = true
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
    .bar-wrapper{
        position: absolute;
        width: 150px;
        background-color: rgba(22, 233, 205, 0.65);
        height: 100vh;
        z-index: 10000;
        transition: width 1s ease-in-out;
        top: 0;
        left: 0;

        .icon{
            padding: 10px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            margin: 0 auto;
            color: rgba(105, 105, 105, .7);
            text-align: center;
            border-bottom: 2px solid rgba(105, 105, 105, 1);

            & :hover {
                color: rgba(105, 105, 105, 1);
                transition: all 1s ease-in-out;
            }
        }

        .main{
            padding: 20px  10px 10px 10px;
            font-size: 16px;
            font-weight: 500;

            .item{
                display: flex;
                justify-content: space-around;
                align-items: center;
                margin: 0 auto;
                width: 100%;
                padding: 10px;
                cursor: pointer;
                position: relative;

                &:hover{
                    background-color: rgba(22, 233, 205, 0.9);
                    box-shadow: 0 0 2px rgba(105, 105, 105, .8);
                    transition: all .5s ease-in-out;
                    

                    .item-sub{
                        min-width: 150px;
                        opacity: 1;
                        visibility: visible;
                        transition: all .5s ease-in-out;
                    }
                }

                .item-text{
                    display: block;
                    visibility: visible;
                }

                .item-icon{
                    display: block;
                    visibility: visible;
                }

                .item-sub{
                    position: absolute;
                    width: 0;
                    top: 0;
                    left: 100%;
                    margin: 0;
                    opacity: 0;
                    visibility: hidden;
                    background-color: transparent;

                    .sub-item{
                        width: auto;
                        display: flex;
                        justify-content: space-around;
                        align-items: center;
                        padding: 10px;
                        margin: 0 0 0 15px;
                        background-color: rgba(22, 233, 205, 0.5);

                        &:hover{
                            box-shadow: 0 0 1px rgba(105, 105, 105, .6);
                            background-color: rgba(22, 233, 205, 0.65);
                        }
                    }
                }
            }
        }
    }

    .bar-small{
        width: 50px;
        transition: all 1s ease-in-out;
        position: relative;

        .main{
            padding: 20px  5px 5px 5px;

            .item{

                .item-text{
                    display: none;
                    visibility: hidden;
                }
            }
            
        }
    }


@media screen and (min-width:800px) and (max-width:1100px){
    
}


@media screen and (max-width:800px){
    
    .bar-wrapper{

        .main{
            font-size: 14px;

            .item{
                display: flex;


                &:hover{     
                    box-shadow: none;               
                    background-color: inherit;
                    display: block;
                    text-align: center;
                    transition: all 1s ease-in-out;

                    .item-text{
                        display: none;
                    }

                    .item-sub{
                        min-height: 30px;
                        transition: height 1s ease-in-out;
                        padding: 10px;
                        width: 100%;
                        min-width: 0;
                        position: relative;
                        top: 100%;
                        left: 0;
                        margin: 10px auto;
                        height: auto;

                        .sub-item{
                            margin: 5px auto;
                            width: 100%;
                            padding: 5px;
                        }
                    }
                }

                .item-text,
                .item-icon{
                    margin: 5px;
                }

                .item-sub{
                    transition: none;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 0;
                    height: 0;
                    background-color: rgba(22, 233, 205, 0.65);

                    .sub-item{
                        position: relative;
                    }
                }
            }
        }
    }

    .bar-small{

        .main{
            font-size: 14px;

            .item{
                display: flex;


                &:hover{ 
                    background-color: rgba(22, 233, 205, 0.9);
                    box-shadow: 0 0 2px rgba(105, 105, 105, .8);
                    transition: all .5s ease-in-out;
                    

                    .item-sub{
                        min-width: 150px;
                        position: absolute;
                        width: 0;
                        top: 0;
                        left: 100%;
                        opacity: 1;
                        visibility: visible;
                        transition: all .5s ease-in-out;
                        position: absolute;
                        margin: 0;
                        padding: 0;
                        

                        .sub-item{
                            width: auto;
                            display: flex;
                            justify-content: space-around;
                            align-items: center;
                            padding: 10px;
                            margin: 0 0 0 15px;
                            background-color: rgba(22, 233, 205, 0.5);
                            margin: 0 0 0 5px;

                            &:hover{
                                box-shadow: 0 0 1px rgba(105, 105, 105, .6);
                                background-color: rgba(22, 233, 205, 0.65);
                            }
                        }
                        
                    }
                    
                }

                .item-text,
                .item-icon{
                    margin: 5px;
                }

                .item-sub{
                    margin: 0;
                    opacity: 0;
                    visibility: hidden;
                    background-color: transparent;
                }
            }
        }
    }
}
</style>