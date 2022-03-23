<template>
    <div v-for="item in db_items_pro" v-bind:key="item.id">
        <!-- 編集ボタンを押したとき -->
        <p v-if="edit_mode === item.id">
            <input type="text" v-model="edit_data.todo_content">
            <input type="datetime-local" v-model="edit_data.deadline">
            <input type="radio" class="radio" v-on:change="editPost" v-bind:id="item.id" v-bind:value="item.id" v-model="edit_data.id">
            <button><label v-bind:for="item.id">完了</label></button>
        </p>
        <!-- 編集ボタンを押していないとき -->
        <p v-else>
            {{item.todo_content}}
            {{item.deadline}}
            <!-- radioがonになったときに削除処理実行 -->
            <input type="radio" class="radio" v-on:change="todo_delete" v-bind:id="item.id+'delete'" v-bind:value="item.id" v-model="delete_data.id">
            <button><label v-bind:for="item.id+'delete'">削除</label></button>
            <input type="radio" class="radio" v-on:change="editGet" v-bind:id="item.id+'edit'" v-bind:value="item.id" v-model="edit_mode">
            <button><label v-bind:for="item.id+'edit'">編集</label></button>
        </p>
    </div>
</template>

<style scoped>
.radio{
    display: none;
}
</style>

<script>
import { reactive,ref,toRef,toRefs } from 'vue'
import axios from 'axios'

export default {
    name:'TodoListComponent',
    props:['db_items_pro'],
    emits:['todo_update'],
    setup(props,{emit}) {
        let db_items = ref([]);

        // 削除ボタンがポストするデータ
        const delete_data = {
            id:'',
            type:'delete',
        }

        // 削除ボタンの処理
        const todo_delete = ()=>{
            axios.post('/api/todo',delete_data)
            axios.get('/api/DB').then((res)=>{
                db_items.value = res.data
                emit('todo_update',db_items)
            })
            
        }

        //編集モードの管理
        let edit_mode = ref('')

        //編集内容をreactiveで設定
        const edit_data = reactive({
            todo_content:'',
            deadline:'',
            type:'edit',
        })
        const refEditData = toRefs(edit_data)

        //編集ボタンの処理
        const editGet = ()=>{
            axios.post('/api/DB/edit',{id:edit_mode.value}).then((res)=>{
                refEditData.todo_content.value = res.data[0].todo_content
                refEditData.deadline.value = res.data[0].deadline
            })
        }

        //編集完了ボタンの処理
        const editPost = ()=>{
            axios.post('/api/todo',edit_data)
            edit_mode.value = ''
            refEditData.todo_content.value = ''
            refEditData.deadline.value = ''
            axios.get('/api/DB').then((res)=>{
                db_items.value = res.data
                emit('todo_update',db_items)
            })
           
        }

        return{
            db_items,
            delete_data,
            todo_delete,
            edit_mode,
            edit_data,
            editGet,
            editPost,
        }
    },
}
</script>