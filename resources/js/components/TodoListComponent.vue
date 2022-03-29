<template>
    <div
        v-for="item in todoItems"
        v-bind:key="item.id"
    >

        <!-- todoリストを表示 -->
        <div v-if="updateItemId != item.id">
            <p>
                <span>{{item.todo_content}}</span>        
                <input
                    v-model="updateItemId"
                    v-bind:id="item.id+'edit'"
                    v-bind:value="item.id"
                    class="button-hidden"
                    type="radio"
                    v-on:change="updateItemIdRequest"
                >
                <button class="update-button"><label v-bind:for="item.id+'edit'">編集</label></button>
                <input
                    v-model="deleteItemId"
                    v-bind:id="item.id+'delete'"
                    v-bind:value="item.id"
                    class="button-hidden"
                    type="radio"
                    v-on:change="deleteItemIdRequest"
                >
                <button class="delete-button"><label v-bind:for="item.id+'delete'">削除</label></button>
            </p>
            <p>
                {{dayString(item.deadline_month,item.deadline_date)}}
                {{item.deadline_time}}
            </p>
        </div>

        <!-- 編集ボタンを押したときの入力フォーム -->
        <div v-if="updateItemId === item.id">
            <todo-list-update-component
                v-bind:item="item"
                v-bind:updateItemId="updateItemId"
                v-bind:currentInputData="currentInputData"
                v-bind:todoFormValidation="todoFormValidation"
                v-on:update-finish="updateFinish"
            ></todo-list-update-component>
        </div>

    </div>
</template>



<script>
import axios from 'axios'
import { ref,reactive,toRefs } from 'vue'
import TodoListUpdateComponent from './TodoListUpdateComponent.vue'

export default {
    name:'TodoListComponent',
    components:{ TodoListUpdateComponent },
    props:{
        todoItems:{
            type:Array,
            required:true,
        },
        todoFormValidation:{
            type:Function,
            required:true,
        },
    },
    emits:['todo-list-reload'],
    setup(props,{emit}) {


        // 削除ボタン
        let deleteItemId = ref('')        
        const deleteItemIdRequest = ()=>{
            axios.post('/api/todo/delete',{id:deleteItemId.value})
            emit('todo-list-reload')
        }

        
        //編集ボタン
        let updateItemId = ref('')
        const currentInputData = reactive({
            id:'',
            todo_content:'',
            deadline_month:'',
            deadline_date:'',
            deadline_time:'',
        })
        const refCurrentInputData = toRefs(currentInputData)
        const updateItemIdRequest = ()=>{
            axios.post('/api/todo/response/update',{id:updateItemId.value}).then((res)=>{
                refCurrentInputData.id.value = res.data[0].id
                refCurrentInputData.todo_content.value = res.data[0].todo_content
                refCurrentInputData.deadline_month.value = res.data[0].deadline_month
                refCurrentInputData.deadline_date.value = res.data[0].deadline_date
                refCurrentInputData.deadline_time.value = res.data[0].deadline_time
            })
        }
        const updateFinish = ()=>{
            updateItemId.value = ''
            refCurrentInputData.todo_content.value = ''
            refCurrentInputData.deadline_month.value = ''
            refCurrentInputData.deadline_date.value = ''
            refCurrentInputData.deadline_time.value = ''
            emit('todo-list-reload')
        }
      
        const dayString = (month,date)=>{
            return month + "月" + date + '日'
        }

        return{
            deleteItemId,
            deleteItemIdRequest,
            updateItemId,
            currentInputData,
            updateItemIdRequest,
            updateFinish,
            dayString,
        }
    },
}
</script>


<style scoped>
    .button-hidden,
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