<template>
    <div class="assessment-section-answering-badge">
        <div class="main-section">
            name
        </div>
        <div class="instruction">
            Mollit consectetur velit minim voluptate non aute pariatur pariatur tempor eiusmod.
        </div>
        <div class="details">

        </div>
        <question-answering-form
            class="questions-form"
        ></question-answering-form>
    </div>
</template>

<script>
import QuestionAnsweringForm from '../forms/QuestionAnsweringForm';
    export default {
        components: {
            QuestionAnsweringForm,
        },
    }
</script>

<style lang="scss" scoped>

    .assessment-section-answering-badge{

        .main-section{
            width: 100%;
            background: aquamarine;
            color: gray;
            font-size: 14px;
            padding: 5px;
            margin: 0 0 10px;
            text-transform: capitalize;
        }

        .instruction{
            background: white;
            padding: 5px;
            text-align: center;
        }
    }
</style>