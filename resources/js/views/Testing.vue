<template>
    <div class="testing" @click.self="show = !show">
        <select-input
        ></select-input>
        
    </div>
</template>

<script>
import SelectInput from '../components/SelectInput'
import CreateSubject from '../components/forms/CreateSubject'

    export default {
        data() {
            return {
                show: false,
                value: ''
            }
        },
        components: {
            CreateSubject,
            SelectInput,
        },
        methods: {
            input(data){
                this.value = data
            },
            getContent(data) {
                this.textareaContent = data
            },
            post(){
                console.log('post',this.textareaContent)
            },
            takeFiles(files, fileTypes){
                this.files = files
                this.fileTypes = fileTypes
            },
            submit(){
                let formData = new FormData
                let images = 0
                let videos = 0
                let files = 0
                let audio = 0
                
                for (let i = 0; i < this.files.length; i++) {
                   
                    if (this.fileTypes[i] === 'imag') {
                        formData.append(`images[${images}]`,this.files[i])
                        images++
                    }
                    
                    if (this.fileTypes[i] === 'vide') {
                        formData.append(`videos[${videos}]`,this.files[i])
                        videos++
                    }
                    
                    if (this.fileTypes[i] === 'appl' || this.fileTypes[i] === 'text') {
                        formData.append(`files[${files}]`,this.files[i])
                        files++
                    }
                    
                    if (this.fileTypes[i] === 'audio') {
                        formData.append(`audio[${audio}]`,this.files[i])
                        audio++
                    }                    
                }

                axios.post('api/files', formData, {
                    headers:{
                        'Content-Type': 'multipart/form-data'
                    }
                })

                .then(
                    response =>{
                        console.log('response',response)
                    }
                )
                .catch(
                    error=>{
                        console.log(error)
                    }
                )
            },
            listen(){
                // Echo.channel('test-channel').listen('TestEvent', (e)=>{
                //     console.log(e)
                // })
            },
        },
        mounted () {
            this.listen()
        },
    }
</script>

<style lang="scss" scoped>
    .testing{
        width: 100%;
        height: 100vh;
    }
</style>