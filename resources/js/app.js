import { createApp } from 'vue';
import AddTodoComponent from './components/AddTodoComponent.vue';
import TodoListComponent from './components/TodoListComponent.vue';

let app = createApp({})
app.component('add-todo-component', AddTodoComponent);
app.component('todo-list-component',TodoListComponent);

app.mount("#app");