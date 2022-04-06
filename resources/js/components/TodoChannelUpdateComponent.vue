<template>
    <div class="update-channel-wrap">
        <div>
            <input
                v-model="newChannelName"
                type="text"
            >
            <button 
                v-on:click="newChannelNamePost"
            >完了</button>
        </div>
        <p
            v-for="message in errorMessages"
            v-bind:key="message"
        >{{message}}</p>
    </div>
</template>


<script>
import { ref } from 'vue'
import axios from 'axios'

export default {
    name:'TodoChannelUpdateComponent',
    props:{
        channel:{
            type:Object,
            required:true,
        },
    },
    emits:['channel-update-finish'],
    setup(props,{emit}) {
        
        const newChannelName = ref(props.channel.name)
        const errorMessages = ref([])
        const newChannelNamePost = ()=>{
            axios.post('/api/channel/update',{
                id:props.channel.id,
                name:newChannelName.value,
            }).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessages.value = res.data.errors
                }
                else{
                    errorMessages.value = ''
                    emit('channel-update-finish')
                }
            })
        }

        return {
            newChannelName,
            errorMessages,
            newChannelNamePost,
        }
    },
}
</script>