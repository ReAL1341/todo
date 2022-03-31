<template>
    <div>

        <h2>チャンネル</h2>
        <todo-channel-component
            v-bind:currentChannel="currentChannel"
            v-on:change-channel="changeChannel"
        ></todo-channel-component>


        <todo-input-component
            v-bind:currentChannel="currentChannel"
            v-on:todo-list-reload="todoListReload"
        ></todo-input-component>


        <h1>{{currentChannel}}</h1>
        <todo-list-component
            v-bind:todoItems="todoItems"
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

        return {
            currentChannel,
            todoItems,
            todoListReload,
            changeChannel,
        }
    },
}
</script>