import { createApp } from 'vue';
import TodoComponent from './components/TodoComponent.vue';
import TodoListComponent from './components/TodoListComponent.vue';

let app = createApp({})
app.component('todo-component',TodoComponent);
app.component('todo-list-component',TodoListComponent);

app.mount("#app");