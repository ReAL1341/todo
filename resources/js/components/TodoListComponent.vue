<template>
    <div class="todo-list-wrap">
        <h1 class="current-channel">{{currentChannel}}</h1>
        <button
            class="delete-button"
            v-on:click="deleteConfirm"
        >削除
        </button>
        <button
            v-on:click="checkClear"
        >チェックを外す
        </button>

        <!-- 削除確認ウィンドウ -->
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

        <!-- todoリストを表示 -->
        <div
            v-for="item in todoTasks"
            v-bind:key="item.id"
            class="todo-item-wrap"
        >
            <todo-list-item-component
                v-bind:item="item"
                v-bind:dayString="dayString"
                v-on:change-checked="changeChecked"
                v-on:change-updating="changeUpdating"
                v-on:todo-list-reload-emit="todoListReloadEmit"
            ></todo-list-item-component>
        </div>

    </div>
</template>



<script>
import axios from 'axios'
import { ref, watchEffect } from 'vue'
import TodoListItemComponent from './TodoListItemComponent.vue'

export default {
    name:'TodoListComponent',
    components:{ TodoListItemComponent },
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
        const todoTasks = ref([])
        watchEffect(()=>{
            todoTasks.value = props.todoItems
            todoTasks.value.forEach((item)=>{
                Object.defineProperties(item,{
                    checked:{
                        value:false,
                        writable:true,
                    },
                    updating:{
                        value:false,
                        writable:true,
                    },
                })
            })
        })

        // チェック変更
        const checkedItems = ref([])
        const changeChecked = (checkedId)=>{
            todoTasks.value.forEach((item) => {
                if(item.id === checkedId && item.checked === false){
                    item.checked = true
                }
                else if(item.id === checkedId && item.checked === true){
                    item.checked = false
                }
            })
            checkedItems.value = []
            todoTasks.value.forEach((item)=>{
                if(item.checked){
                    checkedItems.value.push(item)
                }
            })
        }

        // チェッククリア
        const checkClear = ()=>{
            todoTasks.value.forEach((item)=>{
                item.checked = false
            })
            checkedItems.value = []
        }

        // 編集ボタン処理
        const changeUpdating = (updatingId)=>{
            checkClear()
            todoTasks.value.forEach((item) => {
                if(item.id === updatingId){
                    item.updating = true
                }
                else{
                    item.updating = false
                }
            })
        }

        // 削除ボタン
        let deleteFlag = ref(false)
        const deleteConfirm = ()=>{
            deleteFlag.value = true
        }
        const deleteItemsRequest = ()=>{
            axios.post('/api/todo/delete',{items:checkedItems.value})
            deleteFlag.value = false
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

        const todoListReloadEmit = ()=>{
            emit('todo-list-reload')
        }

        return{
            todoTasks,
            checkedItems,
            checkClear,
            changeChecked,
            changeUpdating,
            deleteFlag,
            deleteConfirm,
            deleteItemsRequest,
            deleteCancel,
            dayString,
            todoListReloadEmit,
        }
    },
}
</script>