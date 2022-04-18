<template>
    <div class="channel-add-wrap">
        <div>
            <input
                v-model="newChannel"
                type="text"
                placeholder="チャンネル名を入力してください"
                v-on:keyup.enter="$event.target.blur()"
            >
            <button v-on:click="newChannelPost">追加</button>
        </div>
        <p>{{errorMessage}}</p>
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
        const errorMessage = ref('')

        const newChannelPost = ()=> {
            axios.post('/api/channel/store',{name:newChannel.value}).then((res)=>{
                if(res.data.errors != undefined){
                    errorMessage.value = res.data.errors
                }
                else{
                    errorMessage.value = ''
                   emit('channel-list-reload')
                    newChannel.value = '' 
                }
            })
        }

        return {
            newChannel,
            errorMessage,
            newChannelPost,
        }
    },
}
</script>