<template>
    <div class="input-wrap">
        <p class="input-up-wrap">
            <input
                v-model="inputData.todo_content"
                type="text"
                placeholder="タスクを入力してください"
                v-on:keyup.enter="$event.target.blur()"
            >
            <button v-on:click="inputDataPost">追加</button>
        </p>
        <p class="input-bottom-wrap">
            <span>期限:</span>
            <input
                v-model="inputData.deadline_month"
                type="text"
                v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
            >
            <span>月</span>
            <input
                v-model="inputData.deadline_date"
                type="text"
                v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
            >
            <span>日</span>
            <input
                v-model="inputData.deadline_time"
                class="input-deadline-time"
                type="time"
                v-on:keyup.enter="$event.target.blur()"
            >
        </p>
        <span class="input-error-message">{{errorMessage}}</span>
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