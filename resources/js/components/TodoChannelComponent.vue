<template>
    <h2>チャンネル</h2>

    <div>
        <input
            v-model="newChannel"
            type="text"
        >
        <button v-on:click = "newChannelPost">追加</button>
    </div>

    <div>
        <p 
            v-for="item in channelItems"
            v-bind:key="item.id"
        >
            <input
                v-model="toChannel"
                v-bind:id="item.id+'current-id'"
                v-bind:value="item.id"
                class="button-hidden"
                type="radio"
                v-on:click = "changeChannel"
            >
            <button><label v-bind:for="item.id+'current-id'">{{item.name}}</label></button>
        </p>
    </div>

</template>

<script>
import {reactive,ref,toRefs} from 'vue'
import axios from 'axios'

export default{
    name:'TodoChannelComponent',
    setup(){
        let newChannel = ref('')

        let channelItems = ref([])

        axios.get('/api/channel').then(($res)=>{
                channelItems.value = $res.data
        })

        const newChannelPost = ()=> {
            axios.post('/api/channel/store',{name:newChannel.value})
            axios.get('/api/channel').then(($res)=>{
                channelItems.value = $res.data
            })
            newChannel.value = ''
        }

        let toChannel = ref('')

        const changeChannel = ()=>{
            axios.post('',{toChannel:toChannel.value})
        }

        return {
            newChannel,
            channelItems,
            newChannelPost,
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