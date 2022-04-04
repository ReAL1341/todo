<template>
    <div class="todo-list-wrap">
        <h1 class="current-channel">{{currentChannel}}</h1>
        <button
            class="delete-button"
            v-on:click="deleteConfirm"
        >削除
        </button>

        <div
            v-if="deleteFlag"
            class="delete-confirm"
        >
            <div class="confirm-display">
                <p>次のタスクを削除していいですか?</p>
                <div class="delete-items">
                    <p
                        v-for="checkedItem in checkedItems"
                        v-bind:key="checkedItem.id"
                    >
                        <span>{{checkedItem.todo_content}}</span>
                        <span>{{dayString(checkedItem.deadline_month,checkedItem.deadline_date)}}</span>
                        <span>{{checkedItem.deadline_time}}</span>
                    </p>
                </div>
                <button v-on:click="deleteItemsRequest">はい</button>
                <button v-on:click="deleteCancel">いいえ</button>
            </div>
        </div>

        <div
            v-for="item in todoItems"
            v-bind:key="item.id"
            class="todo-item-wrap"
        >

            <!-- todoリストを表示 -->
            <div
                v-if="updateItemId != item.id"
                class="todo-item"
            >
                <todo-list-item-component
                    v-bind:item="item"
                    v-bind:dayString="dayString"
                    v-on:checked-items-admin="checkedItemsAdmin"
                    v-on:update-item-id-change="updateItemIdChange"
                ></todo-list-item-component>
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
import TodoListItemComponent from './TodoListItemComponent.vue'

export default {
    name:'TodoListComponent',
    components:{ TodoListItemComponent,TodoListUpdateComponent },
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

        let checkedItems = ref([])
        const checkedItemsAdmin = (checkboxAdminObject)=>{
            if(checkboxAdminObject.checked){
                checkedItems.value.push(checkboxAdminObject.item)
            }
            else{
                checkedItems.value = checkedItems.value.filter((elementObject)=>{
                    return elementObject.id !== checkboxAdminObject.item.id
                })
            }
        }

        //編集処理
        let updateItemId = ref('')
        const updateItemIdChange = (idObject)=>{
            updateItemId.value = idObject.id
        }
        const updateFinish = ()=>{
            updateItemId.value = ''
            emit('todo-list-reload')
        }

        // 削除ボタン
        let deleteFlag = ref(false)
        const deleteConfirm = ()=>{
            deleteFlag.value = true
        }
        const deleteItemsRequest = ()=>{
            axios.post('/api/todo/delete',{items:checkedItems.value}) //個々かえる
            checkedItems.value = []
            emit('todo-list-reload')
        }
        const deleteCancel = ()=>{
            deleteFlag.value = false
        }

        //「●月●日」を返す
        const dayString = (month,date)=>{
            if (month!=null && date!=null){
                return month + "月" + date + '日'
            }
            else{
                return ''
            }
        }

        return{
            checkedItems,
            checkedItemsAdmin,
            deleteFlag,
            deleteConfirm,
            deleteItemsRequest,
            deleteCancel,
            updateItemId,
            updateItemIdChange,
            updateFinish,
            dayString,
        }
    },
}
</script>