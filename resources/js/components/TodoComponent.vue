<template>
    <div>


        <todo-list-component
            v-bind:todoItems="todoItems"
            v-on:todo-update="todoUpdate"
        ></todo-list-component>


        <div>
            <input
                v-model="inputData.todo_content"
                type="text"
            >
            <input
                v-model="inputData.deadline"
                type="datetime-local"
            >
            <button v-on:click="inputDataPost">+</button>
        </div>


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
        //入力データをreactiveで設定
        const inputData = reactive({
                todo_content:'',
                deadline:'',
                type:'add',
        })
        // ref化することで、分割代入を可能にする
        const refInputData = toRefs(inputData)


        // DBレコードを非同期で全取得
        let todoItems = ref([])
        axios.get('/api/DB').then((res)=>{
            todoItems.value = res.data
        })


        const inputDataPost = ()=>{
            //入力データを非同期でポスト
            axios.post("/api/todo",inputData)
            // DBレコードを非同期で全取得
            axios.get('/api/DB').then((res)=>{
                todoItems.value = res.data
            })
            // 入力フォームの初期化
            refInputData.todo_content.value = ''
            refInputData.deadline.value = ''
        }

        const todoUpdate = (newTodoItems)=>{
            //DBレコードの再取得
            todoItems.value = newTodoItems.value
        }

        return {
            inputData,
            todoItems,
            inputDataPost,
            todoUpdate,
        }
    },
}

//命名の整理
//コメント
//css

// チャンネル
//カラムの変更

//削除確認
//一括削除

//読み込み表示
//スクロールによる順序変更
</script>