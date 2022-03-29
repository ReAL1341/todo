<template>
    <div>

        <h2>チャンネル</h2>
        <todo-channel-component
            v-on:change-channel="changeChannel"
        ></todo-channel-component>


        <todo-input-component
            v-bind:currentChannel="currentChannel"
            v-bind:todoFormValidation="todoFormValidation"
            v-on:todo-list-reload="todoListReload"
        ></todo-input-component>


        <h1>{{currentChannel}}</h1>
        <todo-list-component
            v-bind:todoItems="todoItems"
            v-bind:todoFormValidation="todoFormValidation"
            v-on:todo-list-reload="todoListReload"
        ></todo-list-component>


    </div>
</template>



<script>
import axios from 'axios'
import { ref } from 'vue'
import TodoChannelComponent from './TodoChannelComponent.vue'
import TodoInputComponent from './TodoInputComponent.vue'
import TodoListComponent from './TodoListComponent.vue'

export default{
    components: { TodoChannelComponent, TodoInputComponent, TodoListComponent },
    setup() {

        let currentChannel = ref('やることリスト')

        let todoItems = ref([]);
        axios.post('/api/todo/response',{channel:currentChannel.value}).then((res)=>{
            todoItems.value = res.data
        })
        
        const todoListReload = ()=>{
            axios.post('/api/todo/response',{channel:currentChannel.value}).then((res)=>{
                todoItems.value = res.data
            })
        }

        const changeChannel = (toChannel)=>{
            currentChannel.value = toChannel
            todoListReload()
        }

        //最終的にはLaravel側に移行する
        const todoFormValidation = (data)=>{
            if(data.todo_content == ''){
                //errorMessage.value = 'タスクを入力してください'
                return false
            }
            else if(Boolean(data.deadline_month) !== Boolean(data.deadline_date)){
                //errorMessage.value = '「月日」の両方を入力してください'
                return false
            }
            else if(data.deadline_month == '' && data.deadline_time != ''){
                //errorMessage.value = '「日付と時間」もしくは「日付のみ」で入力してください'
                return false
            }
            else if(data.deadline_month > 12 || data.deadline_month < 1){
                //errorMessage.value = '正確に「月」を入力してください'
                return false
            }
            else if(data.deadline_date >31 || data.deadline_date < 1){
                //errorMessage.value = '正確に「日」を入力してください'
                return false
            }            
            else{
                return true
            }
        }

        

        return {
            currentChannel,
            todoItems,
            todoListReload,
            changeChannel,
            todoFormValidation,
        }
    },
}
</script>
