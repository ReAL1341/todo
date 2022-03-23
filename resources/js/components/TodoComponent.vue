<template>
    <div>


        <todo-list-component
            v-on:todo_update="todoUpdate"
            v-bind:db_items_pro="db_items_pro"
        ></todo-list-component>


        <div>
            <div>
                <p>
                    <input
                        type="text"
                        v-model="input_data.todo_content"
                    >
                </p>
                <p>
                    <input
                        type="datetime-local"
                        v-model="input_data.deadline"
                    >
                </p>
                <button v-on:click="apiPost">+</button>
            </div>
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
        const input_data = reactive({
                todo_content:'',
                deadline:'',
                type:'add',
        })
        // ref化することで、分割代入を可能にする
        const refData = toRefs(input_data)


        // DBレコードを非同期で全取得
        let db_items_pro = ref([])
        axios.get('/api/DB').then((res)=>{
            db_items_pro.value = res.data
        })


        const apiPost = ()=>{
            //入力データを非同期でポスト
            axios.post("/api/todo",input_data)
            // DBレコードを非同期で全取得
            axios.get('/api/DB').then((res)=>{
                db_items_pro.value = res.data
            })
            // 入力フォームの初期化
            refData.todo_content.value = ''
            refData.deadline.value = ''
        }

        const todoUpdate = (db_items)=>{
            db_items_pro.value = db_items.value
        }

        return {
            input_data,
            db_items_pro,
            apiPost,
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