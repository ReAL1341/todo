<template>
    <div>

        <todo-channel-component
            v-on:change-channel="changeChannel"
        ></todo-channel-component>

        <h1>{{currentChannel}}</h1>
        <todo-list-component
            v-bind:todoItems="todoItems"
            v-bind:currentChannel="currentChannel"
            v-bind:postValidation="postValidation"
            v-on:todo-update="todoUpdate"
        ></todo-list-component>


        <div>
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
        </div>
        <p>{{errorMessage}}</p>


    </div>
</template>




<script>
import axios from 'axios'
import { reactive,ref,toRefs } from 'vue'
import TodoListComponent from './TodoListComponent.vue'

export default{
    name:'TodoComponent',
    components:{
        TodoListComponent,
    },
    setup() {
        let currentChannel = ref('やることリスト')
        //入力データをreactiveで設定
        const inputData = reactive({
                todo_content:'',
                deadline_month:'',
                deadline_date:'',
                deadline_time:'',
                channel:currentChannel.value,
        })
        // ref化することで、分割代入を可能にする
        const refInputData = toRefs(inputData)
        
        let errorMessage = ref('')
        


        // DBレコードを非同期で全取得
        let todoItems = ref([])
        axios.post('/api/todo/response',{channel:currentChannel.value}).then((res)=>{
            todoItems.value = res.data
        })

        const postValidation = (data,errorMessage)=>{
            if(data.todo_content == ''){
                errorMessage.value = 'タスクを入力してください'
                return false
            }
            else if(Boolean(data.deadline_month) !== Boolean(data.deadline_date)){
                errorMessage.value = '「月日」の両方を入力してください'
                return false
            }
            else if(data.deadline_month == '' && data.deadline_time != ''){
                errorMessage.value = '「日付と時間」もしくは「日付のみ」で入力してください'
                return false
            }
            else if(data.deadline_month > 12 || data.deadline_month < 1){
                errorMessage.value = '正確に「月」を入力してください'
                return false
            }
            else if(data.deadline_date >31 || data.deadline_date < 1){
                errorMessage.value = '正確に「日」を入力してください'
                return false
            }            
            else{
                errorMessage.value = ''
                return true
            }
        }

        const inputDataPost = ()=>{
            if(postValidation(inputData,errorMessage)){
                //入力データを非同期でポスト
                axios.post("/api/todo/store",inputData)
                // DBレコードを非同期で全取得
                axios.post('/api/todo/response',{channel:currentChannel.value}).then((res)=>{
                    todoItems.value = res.data
                })
                // 入力フォームの初期化
                refInputData.todo_content.value = ''
                refInputData.deadline_month.value = ''
                refInputData.deadline_date.value = ''
                refInputData.deadline_time.value = ''
            }
        }

        const todoUpdate = (newTodoItems)=>{
            //DBレコードの再取得
            todoItems.value = newTodoItems.value
        }

        

        const changeChannel = (toChannel)=>{
            currentChannel.value = toChannel
            axios.post('/api/todo/response',{channel:currentChannel.value}).then((res)=>{
                    todoItems.value = res.data
            })
            refInputData.channel.value = toChannel
        }

        return {
            inputData,
            errorMessage,
            todoItems,
            inputDataPost,
            todoUpdate,
            postValidation,
            changeChannel,
            currentChannel,
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