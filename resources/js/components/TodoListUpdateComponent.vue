<template>
    <p>
        <input
            v-model="updateData.todo_content"
            type="text"
            v-on:keyup.enter="$event.target.nextElementSibling.focus()"
        >
        <input
            v-model="updateData.deadline_month"
            type="number"
            min="1"
            max="12"
            v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
        >
        <span>月</span>
        <input
            v-model="updateData.deadline_date"
            type="number"
            min="1"
            max="31"
            v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
        >
        <span>日</span>
        <input
            v-model="updateData.deadline_time"
            class="update-time"
            type="time"
            v-on:keyup.enter="$event.target.blur()"
        >
        <button 
            class="update-button"
            v-on:click="updateDataPost"
        >完了</button>
    </p>
</template>


<script>
import axios from 'axios'
import { reactive,toRefs } from 'vue'

export default {
    name:'TodoListUpdateComponent',
    props:{
        item:{
            type:Object,
            required:true,
        },
        updateItemId:{
            type:String,
            required:true,
        },
        currentInputData:{
            type:Object,
            required:true,
        },
        todoFormValidation:{
            type:Function,
            required:true,
        },
    },
    emits:['update-finish'],
    setup(props,{emit}){
        
        const updateData = reactive(props.currentInputData)

        //編集完了ボタンの処理
        const updateDataPost = ()=>{
            if(props.todoFormValidation(updateData)){
                axios.post('/api/todo/update',updateData)
                emit('update-finish')
            }
        }

        return {
            updateData,
            updateDataPost,
        }
    },
}
</script>