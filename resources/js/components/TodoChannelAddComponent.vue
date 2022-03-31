<template>
    <div>
        <p>
            <input
                v-model="newChannel"
                type="text"
            >
            <button v-on:click="newChannelPost">追加</button>
        </p>
        <p
            v-for="message in errorMessages"
            v-bind:key="message"
        >{{message}}</p>
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
        const errorMessages = ref([])

        const newChannelPost = ()=> {
            axios.post('/api/channel/store',{name:newChannel.value}).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessages.value = res.data.errors
                }
                else{
                    errorMessages.value = ''
                   emit('channel-list-reload')
                    newChannel.value = '' 
                }
            })
        }

        return {
            newChannel,
            errorMessages,
            newChannelPost,
        }
    },
}
</script>