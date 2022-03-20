<template>
    <div>  
        <p v-for="item in this.items" v-bind:key="item.id">
            {{item.todo_content}}
            {{item.deadline}}
        </p>
    </div>
</template>

<script>
import { ref } from 'vue'

export default{
    name:'TodoListComponent',
    props:{
        items:{
            type:Array,
        },
    },
    setup() {
    },
}
</script>