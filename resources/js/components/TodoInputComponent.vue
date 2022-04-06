<template>
    <div class="input-wrap">
        <p>
            <input
                v-model="inputData.todo_content"
                class="input-todo-content"
                type="text"
                placeholder="タスクを入力してください"
                v-on:keyup.enter="$event.target.blur()"
            >
            <button 
                class="input-submit-button"
                v-on:click="inputDataPost"
            >追加</button>
        </p>
        <p class="input-deadline-wrap">
            <span class="input-deadline-text">期限:</span>
            <input
                v-model="inputData.deadline_month"
                class="input-deadline  input-deadline-month"
                type="text"
                v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
            >
            <span class="input-deadline-text">月</span>
            <input
                v-model="inputData.deadline_date"
                class="input-deadline  input-deadline-date"
                type="text"
                v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
            >
            <span class="input-deadline-text">日</span>
            <input
                v-model="inputData.deadline_time"
                class="input-deadline input-deadline-time"
                type="time"
                v-on:keyup.enter="$event.target.blur()"
            >
            <span class="input-error-message">{{errorMessage}}</span>
        </p>
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
        const errorMessage = ref('')

        const inputDataPost = ()=>{
            refInputData.channel.value = props.currentChannel
            axios.post("/api/todo/store",inputData).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessage.value = res.data.errors
                }
                else{
                    errorMessage.value = ''
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
            errorMessage,
            inputDataPost,
        }
    },
}
</script>