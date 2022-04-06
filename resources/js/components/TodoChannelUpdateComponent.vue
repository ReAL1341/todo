<template>
    <div class="update-channel-wrap">
        <input
            v-model="newChannelName"
            type="text"
        >
        <p>{{errorMessage}}</p>
        <button 
            v-on:click="newChannelNamePost"
        >完了</button>
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
        const errorMessage = ref('')
        const newChannelNamePost = ()=>{
            axios.post('/api/channel/update',{
                id:props.channel.id,
                name:newChannelName.value,
            }).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessage.value = res.data.errors
                }
                else{
                    errorMessage.value = ''
                    emit('channel-update-finish')
                }
            })
        }

        return {
            newChannelName,
            errorMessage,
            newChannelNamePost,
        }
    },
}
</script>