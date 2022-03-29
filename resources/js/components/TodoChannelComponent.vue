<template>
    <todo-channel-add-component
        v-on:channel-list-reload="channelListReload"
    ></todo-channel-add-component>

    <div>
        <p
            v-for="item in channelItems"
            v-bind:key="item.id"
        >
            <input
                v-model="toChannel"
                v-bind:id="item.id+'current-id'"
                v-bind:value="item.name"
                class="button-hidden"
                type="radio"
                v-on:change="changeChannel"
            >
            <button><label v-bind:for="item.id+'current-id'">{{item.name}}</label></button>
        </p>
    </div>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'
import TodoChannelAddComponent from './TodoChannelAddComponent.vue'

export default{
    name:'TodoChannelComponent',
    components: { TodoChannelAddComponent },
    emits:['change-channel'],
    setup(props,{emit}){

        let channelItems = ref([])
        axios.get('/api/channel').then(($res)=>{
            channelItems.value = $res.data
        })
        
        const channelListReload = ()=>{
            axios.get('/api/channel').then(($res)=>{
                channelItems.value = $res.data
            })
        }

        let toChannel = ref('')
        const changeChannel = ()=>{
            emit('change-channel',toChannel.value)
        }

        return {
            channelItems,
            channelListReload,
            toChannel,
            changeChannel,
        }
    },
}
</script>


<style scoped>
.button-hidden{
    display: none;
}
</style>