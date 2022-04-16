<template>
     <div
        v-if="!item.updating"
        class="todo-item"
    >   
        <span
            v-on:click="changeCheckedEmit"
        >
            <input
                v-bind:checked="item.checked"
                type="checkbox"
            >
            <span class="todo-content">{{item.todo_content}}</span>
            <span class="date-time">
                {{dayString(item.deadline_month,item.deadline_date)}}
                {{item.deadline_time}}
            </span>
        </span>
        <button
            class="public-button-blue"
            v-on:click="changeUpdatingEmit"
        >編集</button>
    </div>

    <!-- 編集ボタンを押したときの入力フォーム -->
    <div
        v-show="item.updating"
        class="update-todo-item"
    >
        <todo-list-update-component
            v-bind:item="item"
            v-on:update-finish="updateFinish"
        ></todo-list-update-component>
    </div>

</template>

<script>
import TodoListUpdateComponent from './TodoListUpdateComponent.vue'

export default {
  components: { TodoListUpdateComponent },
    name:'TodoListItemComponent',
    props:{
        item:{
            type:Object,
            required:true,
        },
        dayString:{
            type:Function,
            required:true,
        },
    },
    emits:[
        'change-checked',
        'change-updating',
        'todo-list-reload-emit',
    ],
    setup(props,{emit}) {

        const changeCheckedEmit = ()=>{
            emit('change-checked',props.item.id)
        }

        const changeUpdatingEmit = ()=>{
            emit('change-updating',props.item.id)
        }

        const updateFinish = ()=>{
            emit('todo-list-reload-emit')
        }

        return {
            changeCheckedEmit,
            changeUpdatingEmit,
            updateFinish,
        }
    },
}
</script>