<template>
    <div>
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
        <p
            v-for="message in errorMessages"
            v-bind:key="message"
        >{{message}}</p>
    </div>
</template>


<script>
import axios from 'axios'
import { reactive,ref } from 'vue'

export default {
    name:'TodoListUpdateComponent',
    props:{
        item:{
            type:Object,
            required:true,
        },
    },
    emits:['update-finish'],
    setup(props,{emit}){
        
        const updateData = reactive({
            id:props.item.id,
            todo_content:props.item.todo_content,
            deadline_month:props.item.deadline_month,
            deadline_date:props.item.deadline_date,
            deadline_time:props.item.deadline_time,
        })
        const errorMessages = ref([])

        //編集完了ボタンの処理
        const updateDataPost = ()=>{
            axios.post('/api/todo/update',updateData).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessages.value = res.data.errors
                }
                else{
                    errorMessages.value = ''
                    emit('update-finish')
                }
            })
        }

        return {
            updateData,
            errorMessages,
            updateDataPost,
        }
    },
}
</script>