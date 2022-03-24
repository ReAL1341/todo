<template>
    <div
        v-for="item in todoItems"
        v-bind:key="item.id"
    >


        <!-- 編集ボタンを押したとき -->
        <div v-if="updateMode === item.id">
            <input
                v-model="updateData.todo_content"
                type="text"
            >
            <input
                v-model="updateData.deadline_month"
                type="number"
                min="1"
                max="12"
            >
            <span>月</span>
            <input
                v-model="updateData.deadline_date"
                type="number"
                min="1"
                max="31"
            >
            <span>日</span>
            <input
                v-model="updateData.deadline_time"
                class="update-time"
                type="time"
            >
            <input
                v-model="updateData.id"
                v-bind:id="item.id"
                v-bind:value="item.id"
                class="radio"
                type="radio"
                v-on:change="updateDataPost"
            >
            <button><label v-bind:for="item.id">完了</label></button>
        </div>
    
    
        <!-- 編集ボタンを押していないとき -->
        <div v-else>
            <p>
                <span>{{item.todo_content}}</span>
                
                <input
                    v-model="updateMode"
                    v-bind:id="item.id+'edit'"
                    v-bind:value="item.id"
                    class="radio"
                    type="radio"
                    v-on:change="updateIdPost"
                >
                <button class="update-button"><label v-bind:for="item.id+'edit'">編集</label></button>
                <input
                    v-model="deleteData.id"
                    v-bind:id="item.id+'delete'"
                    v-bind:value="item.id"
                    class="radio"
                    type="radio"
                    v-on:change="deleteDataPost"
                >
                <button class="delete-button"><label v-bind:for="item.id+'delete'">削除</label></button>
            </p>
            <p>
                {{dayString(item.deadline_month,item.deadline_date)}}
                {{item.deadline_time}}
            </p>
        </div>
    
    
    </div>
</template>



<script>
import { reactive,ref,toRefs } from 'vue'
import axios from 'axios'

export default {
    name:'TodoListComponent',
    props:{
        todoItems:{
            type:Array,
            required:true,
        }
    },
    emits:['todo-update'],
    setup(props,{emit}) {
        let newTodoItems = ref([]);

        // 削除ボタンがポストするデータ
        const deleteData = {
            id:'',
        }

        // 削除ボタンの処理
        const deleteDataPost = ()=>{
            axios.post('/api/todo/delete',deleteData)
            axios.get('/api/DB').then((res)=>{
                newTodoItems.value = res.data
                emit('todo-update',newTodoItems)
            })
            
        }

        //編集モードの管理
        let updateMode = ref('')

        //編集内容をreactiveで設定
        const updateData = reactive({
            todo_content:'',
            deadline_month:'',
            deadline_date:'',
            deadline_time:'',
        })
        const refUpdateData = toRefs(updateData)

        //編集ボタンの処理
        const updateIdPost = ()=>{
            axios.post('/api/DB/update',{id:updateMode.value}).then((res)=>{
                refUpdateData.todo_content.value = res.data[0].todo_content
                refUpdateData.deadline_month.value = res.data[0].deadline_month
                refUpdateData.deadline_date.value = res.data[0].deadline_date
                refUpdateData.deadline_time.value = res.data[0].deadline_time
            })
        }

        //編集完了ボタンの処理
        const updateDataPost = ()=>{
            axios.post('/api/todo/update',updateData)
            updateMode.value = ''
            refUpdateData.todo_content.value = ''
            refUpdateData.deadline_month.value = ''
            refUpdateData.deadline_date.value = ''
            refUpdateData.deadline_time.value = ''
            axios.get('/api/DB').then((res)=>{
                newTodoItems.value = res.data
                emit('todo-update',newTodoItems)
            })
           
        }

        const dayString = (month,date)=>{
            return month + "月" + date + '日'
        }

        return{
            deleteData,
            deleteDataPost,
            updateMode,
            updateData,
            updateIdPost,
            updateDataPost,
            dayString,
        }
    },
}
</script>



<style scoped>
.radio,
.update-time::-webkit-calendar-picker-indicator{
    display: none;
}
.update-button{
    border-radius: 3px;
    border-color: rgb(8, 168, 8);
    background-color: rgb(11, 228, 11);
    color: white;
    font-weight: bold;
}
.update-button:hover{
    background-color:rgb(11, 190, 11) ;
}
.delete-button{
    border-radius: 3px;
    border-color: rgb(188, 17, 17);
    background-color: rgb(248, 20, 20);
    color: white;
    font-weight: bold;
}
.delete-button:hover{
    background-color: rgb(210, 20, 20);
}
</style>