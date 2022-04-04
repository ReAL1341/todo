import { createApp } from 'vue';
import AppComponent from './components/AppComponent.vue';
import TodoInputComponent from './components/TodoInputComponent.vue';
import TodoListComponent from './components/TodoListComponent.vue';
import TodoListItemComponent from './components/TodoListItemComponent.vue';
import TodoListUpdateComponent from './components/TodoListUpdateComponent.vue';
import TodoChannelComponent from './components/TodoChannelComponent.vue';
import TodoChannelAddComponent from './components/TodoChannelAddComponent.vue';
import TodoChannelUpdateComponent from './components/TodoChannelUpdateComponent.vue';

let app = createApp({})
app.component('app-component',AppComponent);
app.component('todo-input-component',TodoInputComponent);
app.component('todo-list-component',TodoListComponent);
app.component('todo-list-item-component',TodoListItemComponent);
app.component('todo-list-update-component',TodoListUpdateComponent);
app.component('todo-channel-component',TodoChannelComponent);
app.component('todo-channel-add-component',TodoChannelAddComponent);
app.component('todo-channel-update-component',TodoChannelUpdateComponent);

app.mount("#app");