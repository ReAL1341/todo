<template>
    <div>
        <p>
            <input
                v-model="updateData.todo_content"
                class="update-todo-content"
                type="text"
                v-on:keyup.enter="$event.target.blur()"
            >
            <span class="update-deadline-wrap">
                <input
                    v-model="updateData.deadline_month"
                    class="update-deadline-day"
                    type="text"
                    v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
                >
                <span class="update-string">月</span>
                <input
                    v-model="updateData.deadline_date"
                    class="update-deadline-day"
                    type="text"
                    v-on:keyup.enter="$event.target.nextElementSibling.nextElementSibling.focus()"
                >
                <span class="update-string">日</span>
                <input
                    v-model="updateData.deadline_time"
                    class="update-deadline-time"
                    type="time"
                    v-on:keyup.enter="$event.target.blur()"
                >
            </span>
            <button 
                class="update-button"
                v-on:click="updateDataPost"
            >完了</button>
        </p>
        <p>{{errorMessage}}</p>
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
            deadline_month:Number(props.item.deadline_month),
            deadline_date:Number(props.item.deadline_date),
            deadline_time:props.item.deadline_time,
        })
        const errorMessage = ref('')

        //編集完了ボタンの処理
        const updateDataPost = ()=>{
            axios.post('/api/todo/update',updateData).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessage.value = res.data.errors
                }
                else{
                    errorMessage.value = ''
                    emit('update-finish')
                }
            })
        }

        return {
            updateData,
            errorMessage,
            updateDataPost,
        }
    },
}
</script>