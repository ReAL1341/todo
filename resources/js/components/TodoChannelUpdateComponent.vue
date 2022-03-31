<template>
    <div>
        <p>
            <input
                v-model="newChannelName"
                type="text"
            >
            <button 
                class="update-button"
                v-on:click="newChannelNamePost"
            >完了</button>
        </p>
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
            }).then(($res)=>{
                if($res.data.errors != undefined){
                    errorMessages.value = $res.data.errors
                }
                else{
                    errorMessages.value = ''
                    emit('channel-update-finish',newChannelName.value)
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


<style scoped>
    .update-button{
        border-radius: 3px;
        border-color: rgb(8, 168, 8);
        background-color: rgb(11, 228, 11);
        color: white;
        font-weight: bold;
    }
    .update-button:hover{
        background-color:rgb(11, 190, 11) ;
    }
</style>