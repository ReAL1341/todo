<template>
    <div>
        <input
            v-model="newChannel"
            type="text"
        >
        <button v-on:click="newChannelPost">追加</button>
    </div>
</template>


<script>
import axios from 'axios'
import { ref } from 'vue'

export default{
    name:'TodoChannelAddComponent',
    emits:['channel-list-reload'],
    setup(props,{emit}) {
        let newChannel = ref('')

        const newChannelPost = ()=> {
            axios.post('/api/channel/store',{name:newChannel.value})
            emit('channel-list-reload')
            newChannel.value = ''
        }

        return {
            newChannel,
            newChannelPost,
        }
    },
}
</script>