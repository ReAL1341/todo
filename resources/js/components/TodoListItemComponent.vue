<template>
    <div>   
        <input
            v-model="checked"
            v-bind:id="item.id+'checkbox'"
            type="checkbox"
            v-on:change="checkboxAdmin"
        >
        <label v-bind:for="item.id+'checkbox'" >
            <span class="todo-content">{{item.todo_content}}</span>   
            <span class="date-time">
                {{dayString(item.deadline_month,item.deadline_date)}}
                {{item.deadline_time}}
            </span>
            <button 
                class="update-button"
                v-on:click="updateItemIdChange"
            >編集</button>
        </label>
    </div>
</template>

<script>
import { ref } from 'vue'

export default {
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
        'checked-items-admin',
        'update-item-id-change',
    ],
    setup(props,{emit}) {

        const checked = ref(false)
        const checkboxAdmin = ()=>{
            emit('checked-items-admin',{
                item:props.item,
                checked:checked.value,
            })
        }

        const updateItemIdChange = ()=>{
            emit('update-item-id-change',{id:props.item.id})
        }

        return {
            checked,
            checkboxAdmin,
            updateItemIdChange,
        }
    },
}
</script>