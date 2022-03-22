<template>
    <div>

        <div>
            <div v-for="item in db_items" v-bind:key="item.id"><!--このままだと全部edit_modeになる -->
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
        </div>

        <div>
            <div>
                <p><input type="text" v-model="input_data.todo_content"></p>
                <input type="datetime-local" v-model="input_data.deadline">
                <p>{{ input_data.todo_content }}</p>
                <p>{{ input_data.deadline }}</p>
                <button v-on:click="apiPost">+</button>
            </div>
        </div>

    </div>
</template>

<style scoped>
.radio{
    display: none;
}
</style>

<script>
import axios from 'axios'
import { reactive,ref,toRefs } from 'vue'

export default{
    name:'TodoComponent',
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
        let db_items = ref([])
        axios.get('/api/DB').then((res)=>{
            db_items.value = res.data
        })


        const apiPost = ()=>{
            //入力データを非同期でポスト
            axios.post("/api/todo",input_data)
            // DBレコードを非同期で全取得
            axios.get('/api/DB').then((res)=>{
                db_items.value = res.data
            })
            // 入力フォームの初期化
            refData.todo_content.value = ''
            refData.deadline.value = ''
        }

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
            })
        }

        //編集モードの管理
        let edit_mode = ref('')

        const edit_data = reactive({
            todo_content:'',
            deadline:'',
            type:'edit',
        })
        const refEditData = toRefs(edit_data)

        const editGet = ()=>{
            axios.post('/api/DB/edit',{id:edit_mode.value}).then((res)=>{
                refEditData.todo_content.value = res.data[0].todo_content
                refEditData.deadline.value = res.data[0].deadline
            })
        }

        const editPost = ()=>{
            axios.post('/api/todo',edit_data)
            edit_mode.value = ''
            refEditData.todo_content.value = ''
            refEditData.deadline.value = ''
            axios.get('/api/DB').then((res)=>{
                db_items.value = res.data
            })
        }

        return {
            input_data,
            delete_data,
            db_items,
            edit_mode,
            edit_data,
            apiPost,
            todo_delete,
            editGet,
            editPost,
        }
    },
}

//命名の整理

//削除確認
//一括削除

// チャンネル
//カラムの変更

//スクロールによる順序変更
</script>