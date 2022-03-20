<template>
        <div>
            <todo-list-component v-bind:items="db_items"></todo-list-component>

            <div>
                <p><input type="text" v-model="data.todo_content"></p>
                <input type="datetime-local" v-model="data.deadline">
                <p>{{ data.todo_content }}</p>
                <p>{{ data.deadline }}</p>
                <button v-on:click="apiPost">+</button>
            </div>
        </div>
</template>

<script>
import axios from 'axios'
import TodoListComponent from './TodoListComponent.vue'
import { reactive,ref } from 'vue'

export default{
    name:'AddTodoComponent',
    components: { TodoListComponent },
    setup() {
        const data = reactive({
            todo_content:'',
            deadline:'',
            type:'add',
        })

        let db_items = ref([])
        axios.get('/api/DB').then((res)=>{    //ControllerからDBレコードを取得
            db_items.value = res.data  //レスポンスのうち、dataに格納された配列を取得
        })

        const apiPost = ()=>{
            axios.post("/api/todo",data)
            axios.get('/api/DB').then((res)=>{     //ControllerからDBレコードを取得
                db_items.value = res.data  //レスポンスのうち、dataに格納された配列を取得
            })
        }

        return {
            data,
            db_items,
            apiPost,
        };
    },
}
// 入力クリア
// 削除ボタン
// 編集ボタン
// チャンネル
</script>