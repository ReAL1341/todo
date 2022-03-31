<template>
    <todo-channel-add-component
        v-on:channel-list-reload="channelListReload"
    ></todo-channel-add-component>

    <div>
        <div
            v-for="item in channelItems"
            v-bind:key="item.id"
        >
            <p v-if="updateChannelId != item.id">
                <input
                    v-model="toChannel"
                    v-bind:id="item.id+'current-id'"
                    v-bind:value="item.name"
                    class="button-hidden"
                    type="radio"
                    v-on:change="changeChannel(toChannel)"
                >
                <button><label v-bind:for="item.id+'current-id'">{{item.name}}</label></button>
                <input
                    v-model="updateChannelId"
                    v-bind:id="item.id+'channel-update'"
                    v-bind:value="item.id"
                    class="button-hidden"
                    type="radio"
                >
                <button class="update-button"><label v-bind:for="item.id+'channel-update'">編集</label></button>
                <input
                    v-model="deleteChannelId"
                    v-bind:id="item.id+'channel-delete'"
                    v-bind:value="item.id"
                    class="button-hidden"
                    type="radio"
                    v-on:change="deleteChannelIdRequest"
                >
                <button class="delete-button"><label v-bind:for="item.id+'channel-delete'">削除</label></button>
            </p>
               
            <p v-else-if="updateChannelId === item.id">
                <todo-channel-update-component
                    v-bind:channel="item"
                    v-on:channel-update-finish="channelUpdateFinish"
                ></todo-channel-update-component>
            </p>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'
import TodoChannelAddComponent from './TodoChannelAddComponent.vue'

export default{
    name:'TodoChannelComponent',
    components: { TodoChannelAddComponent },
    props:{
        currentChannel:{
            type:String,
            required:true,
        },
    },
    emits:[
        'change-channel',
    ],
    setup(props,{emit}){

        let channelItems = ref([])
        axios.get('/api/channel').then((res)=>{
            channelItems.value = res.data
        })
        
        //チャンネルの再読み込み
        const channelListReload = ()=>{
            axios.get('/api/channel').then((res)=>{
                channelItems.value = res.data
            })
        }

        //チャンネル切り替え
        let toChannel = ref('')
        const changeChannel = (toChannel)=>{
            emit('change-channel',toChannel)
        }

        //削除ボタン
        let deleteChannelId = ref('')
        const deleteChannelIdRequest = ()=>{
            axios.post('/api/channel/delete',{id:deleteChannelId.value}).then((res)=>{
                if(res.data == props.currentChannel){
                    changeChannel('やることリスト')
                }
            })
            deleteChannelId.value = ''
            channelListReload()
        }

        //編集処理
        let updateChannelId = ref('')
        const channelUpdateFinish = (toChannel)=>{
            updateChannelId.value = ''
            channelListReload()
            changeChannel(toChannel)
        }

        return {
            channelItems,
            channelListReload,
            toChannel,
            changeChannel,
            deleteChannelId,
            deleteChannelIdRequest,
            updateChannelId,
            channelUpdateFinish,
        }
    },
}
</script>


<style scoped>
    .button-hidden{
        display: none;
    }
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
    .delete-button{
        border-radius: 3px;
        border-color: rgb(188, 17, 17);
        background-color: rgb(248, 20, 20);
        color: white;
        font-weight: bold;
    }
    .delete-button:hover{
        background-color: rgb(210, 20, 20);
    }
</style>