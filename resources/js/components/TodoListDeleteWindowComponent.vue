<template>
    <div
        v-if="deleteFlag"
        class="delete-confirm-wrap"
    >
        <div class="confirm-display">
            <p>次のタスクを削除していいですか?</p>
            <div>
                <p
                    v-for="checkedItem in checkedItems" 
                    v-bind:key="checkedItem.id"
                    class="delete-item"
                >
                    <span>{{checkedItem.todo_content}}</span>
                    <span>{{dayString(checkedItem.deadline_month,checkedItem.deadline_date)}}</span>
                    <span>{{checkedItem.deadline_time}}</span>
                </p>
            </div>
            <button
                class="public-button-red"
                v-on:click="deleteItemsRequestEmit"
            >はい</button>
            <button
                class="public-button-gray"
                v-on:click="deleteCancelEmit"
            >いいえ</button>
        </div>
    </div>
</template>


<script>
export default {
    name:'TodoListDeleteWindowComponent',
    props:{
        dayString:{
            type:Function,
            required:true,
        },
        checkedItems:{
            type:Array,
            required:true,
        },
        deleteFlag:{
            type:Boolean,
            required:true,
        },
    },
    emits:[
        'delete-items-request',
        'delete-cancel',
    ],
    setup(props,{emit}) {
        
        const deleteItemsRequestEmit = ()=>{
            emit('delete-items-request')
        }

        const deleteCancelEmit = ()=>{
            emit('delete-cancel')
        }

        return {
            deleteItemsRequestEmit,
            deleteCancelEmit,
        }

    },
}
</script>