import { createApp } from 'vue';
import TodoComponent from './components/TodoComponent.vue';
import TodoListComponent from './components/TodoListComponent.vue';
import TodoChannelComponent from './components/TodoChannelComponent.vue'

let app = createApp({})
app.component('todo-component',TodoComponent);
app.component('todo-list-component',TodoListComponent);
app.component('todo-channel-component',TodoChannelComponent);

app.mount("#app");