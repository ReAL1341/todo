<template>
    <div
        v-for="item in todoItems"
        v-bind:key="item.id"
    >


        <!-- 編集ボタンを押したとき -->
        <p v-if="updateMode === item.id">
            <input
                v-model="updateData.todo_content"
                type="text"
            >
            <input
                v-model="updateData.deadline"
                type="datetime-local"
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
        </p>
    
    
        <!-- 編集ボタンを押していないとき -->
        <p v-else>
            {{item.todo_content}}
            {{item.deadline}}
            <input
                v-model="updateMode"
                v-bind:id="item.id+'edit'"
                v-bind:value="item.id"
                class="radio"
                type="radio"
                v-on:change="updateIdPost"
            >
            <button><label v-bind:for="item.id+'edit'">編集</label></button>
            <input
                v-model="deleteData.id"
                v-bind:id="item.id+'delete'"
                v-bind:value="item.id"
                class="radio"
                type="radio"
                v-on:change="deleteDataPost"
            >
            <button><label v-bind:for="item.id+'delete'">削除</label></button>
        </p>
    
    
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
            type:'delete',
        }

        // 削除ボタンの処理
        const deleteDataPost = ()=>{
            axios.post('/api/todo',deleteData)
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
            deadline:'',
            type:'edit',
        })
        const refUpdateData = toRefs(updateData)

        //編集ボタンの処理
        const updateIdPost = ()=>{
            axios.post('/api/DB/edit',{id:updateMode.value}).then((res)=>{
                refUpdateData.todo_content.value = res.data[0].todo_content
                refUpdateData.deadline.value = res.data[0].deadline
            })
        }

        //編集完了ボタンの処理
        const updateDataPost = ()=>{
            axios.post('/api/todo',updateData)
            updateMode.value = ''
            refUpdateData.todo_content.value = ''
            refUpdateData.deadline.value = ''
            axios.get('/api/DB').then((res)=>{
                newTodoItems.value = res.data
                emit('todo-update',newTodoItems)
            })
           
        }

        return{
            deleteData,
            deleteDataPost,
            updateMode,
            updateData,
            updateIdPost,
            updateDataPost,
        }
    },
}
</script>



<style scoped>
.radio{
    display: none;
}
</style>