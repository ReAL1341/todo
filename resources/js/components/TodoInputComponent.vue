<template>
    <div>
        <p>
            <input
                v-model="inputData.todo_content"
                type="text"
                v-on:keyup.enter="$event.target.nextElementSibling.focus()"
            >
            <input
                v-model="inputData.deadline_month"
                type="number"
                min="1"
                max="12"
                v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
            >
            <span>月</span>
            <input
                v-model="inputData.deadline_date"
                type="number"
                min="1"
                max="31"
                v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
            >
            <span>日</span>
            
            <input
                v-model="inputData.deadline_time"
                class="input-time"
                type="time"
                v-on:keyup.enter="$event.target.blur()"
            >
            <button 
                class="input-button"
                v-on:click="inputDataPost"
            >追加</button>
        </p>
        <p
            v-for="message in errorMessages"
            v-bind:key="message"
        >{{message}}</p>
    </div>
</template>




<script>
import axios from 'axios'
import { reactive,ref,toRefs } from 'vue'

export default{
    name:'TodoComponent',
    props:{
        currentChannel:{
            type:String,
            required:true,
        },
    },
    emits:[`todo-list-reload`],
    setup(props,{emit}){

        const inputData = reactive({
            todo_content:'',
            deadline_month:'',
            deadline_date:'',
            deadline_time:'',
            channel:'',
        })
        const refInputData = toRefs(inputData)
        const errorMessages = ref([])

        const inputDataPost = ()=>{
            refInputData.channel.value = props.currentChannel
            axios.post("/api/todo/store",inputData).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessages.value = res.data.errors
                }
                else{
                    errorMessages.value = ''
                    emit('todo-list-reload')
                    // 入力フォームの初期化
                    refInputData.todo_content.value = ''
                    refInputData.deadline_month.value = ''
                    refInputData.deadline_date.value = ''
                    refInputData.deadline_time.value = ''
                }
            })
        }

        return {
            inputData,
            errorMessages,
            inputDataPost,
        }
    },
}
</script>


<style scoped>
.input-time::-webkit-calendar-picker-indicator{
    display: none;
}
.input-button{
    border-radius: 3px;
    border-color: rgb(15, 122, 175);
    background-color: rgb(30, 144, 255);
    color: white;
    font-weight: bold;
}
.input-button:hover{
    background-color: rgb(18,122,189);
}
</style>