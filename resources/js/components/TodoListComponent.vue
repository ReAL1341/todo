<template>
    <div class="todo-list-wrap">
        <h1 class="current-channel">{{currentChannel}}</h1>

        <div
            v-for="item in todoItems"
            v-bind:key="item.id"
            class="todo-item-wrap"
        >

            <!-- todoリストを表示 -->
            <div v-if="updateItemId != item.id">
                <input type="checkbox" v-bind:id="item.id+'checkbox'">
                <label class="todo-item" v-bind:for="item.id+'checkbox'">
                    <span class="todo-content">{{item.todo_content}}</span>        
                    
                    <span class="date-time">
                        {{dayString(item.deadline_month,item.deadline_date)}}
                        {{item.deadline_time}}
                    </span>
                    <span class="todo-list-buttons">
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
                    </span>
                </label>
            </div>

            <!-- 編集ボタンを押したときの入力フォーム -->
            <div v-if="updateItemId === item.id">
                <todo-list-update-component
                    v-bind:item="item"
                    v-on:update-finish="updateFinish"
                ></todo-list-update-component>
            </div>

        </div>

    </div>
</template>



<script>
import axios from 'axios'
import { ref } from 'vue'
import TodoListUpdateComponent from './TodoListUpdateComponent.vue'

export default {
    name:'TodoListComponent',
    components:{ TodoListUpdateComponent },
    props:{
        todoItems:{
            type:Array,
            required:true,
        },
        currentChannel:{
            type:String,
            required:true,
        }
    },
    emits:['todo-list-reload'],
    setup(props,{emit}) {


        // 削除ボタン
        let deleteItemId = ref('')        
        const deleteItemIdRequest = ()=>{
            axios.post('/api/todo/delete',{id:deleteItemId.value})
            emit('todo-list-reload')
        }

        
        //編集処理
        let updateItemId = ref('')
        const updateFinish = ()=>{
            updateItemId.value = ''
            emit('todo-list-reload')
        }
      
        const dayString = (month,date)=>{
            if (month!=null && date!=null){
                return month + "月" + date + '日'
            }
            else{
                return ''
            }
        }

        return{
            deleteItemId,
            deleteItemIdRequest,
            updateItemId,
            updateFinish,
            dayString,
        }
    },
}
</script>