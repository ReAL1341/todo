import { createApp } from 'vue';
import TodoComponent from './components/TodoComponent.vue';

let app = createApp({})
app.component('todo-component',TodoComponent);

app.mount("#app");