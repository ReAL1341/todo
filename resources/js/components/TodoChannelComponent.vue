<template>
    <!-- チャンネル追加フォーム -->
    <todo-channel-add-component
        v-on:channel-list-reload="channelListReload"
    ></todo-channel-add-component>


    <div>
        <div
            v-for="item in channelItems"
            v-bind:key="item.id"
        >
            <!-- 削除確認ウィンドウ -->
            <div
                v-if="deleteChannelId===item.id"
                class="delete-confirm"
            >
                <div class="confirm-display-channel">
                    <p>「{{item.name}}」を削除していいですか?</p>
                    <button 
                        class="delete-button"
                        v-on:click="deleteChannelIdRequest"
                    >はい</button>
                    <button
                        class="cancel-button"
                        v-on:click="deleteCancel"
                    >いいえ</button>
                </div>
            </div>


            <!-- チャンネル項目 -->
            <div v-if="updateChannelId != item.id">
                <input
                    v-model="toChannel"
                    v-bind:id="item.id+'current-id'"
                    v-bind:value="item.name"
                    class="button-hidden"
                    type="radio"
                    v-on:change="changeChannel(toChannel)"
                >
                <div>
                    <div class="channel-item">
                        <label
                            v-bind:for="item.id+'current-id'"
                            class="channel-name"
                        >{{item.name}}</label>
                        <span v-if="item.name!=='やることリスト'">
                            <input
                                v-model="channelMenuId"
                                v-bind:value="item.id"
                                v-bind:id="item.id+'channel-menu'"
                                class="button-hidden"
                                type="radio"
                            >
                            <label
                                v-bind:for="item.id+'channel-menu'"
                                class="kebab-menu"
                            >&#8942;</label>
                        </span>
                    </div>

                    <!-- ケバブメニューの内容 -->
                    <todo-channel-menu-component 
                        v-if="channelMenuId===item.id"
                        v-bind:itemId="item.id"
                        v-bind:channelMenuId="channelMenuId"
                        v-on:fold-menu="foldMenu"
                        v-on:update-id-change="updateIdChange"
                        v-on:delete-confirm="deleteConfirm"
                    ></todo-channel-menu-component>
                    
                </div>
            </div>
               
            <!-- 編集フォーム -->
            <div v-else-if="updateChannelId === item.id">
                <todo-channel-update-component
                    v-bind:channel="item"
                    v-on:channel-update-finish="channelUpdateFinish"
                ></todo-channel-update-component>
            </div>

        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'
import TodoChannelAddComponent from './TodoChannelAddComponent.vue'
import TodoChannelMenuComponent from './TodoChannelMenuComponent.vue'

export default{
    name:'TodoChannelComponent',
    components: { TodoChannelAddComponent,TodoChannelMenuComponent },
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

        const channelItems = ref([])
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
        const toChannel = ref('')
        const changeChannel = (toChannel)=>{
            emit('change-channel',toChannel)
        }

        //ケバブメニュー
        const channelMenuId = ref(0)
        const foldMenu = ()=>{
            channelMenuId.value = 0
        }

        //削除ボタン
        const deleteChannelId = ref(0)
        const deleteConfirm = (id)=>{
            channelMenuId.value = 0
            deleteChannelId.value = id
        }
        const deleteChannelIdRequest = ()=>{
            axios.post('/api/channel/delete',{id:deleteChannelId.value}).then((res)=>{
                if(res.data == props.currentChannel){
                    changeChannel('やることリスト')
                }
            })
            deleteChannelId.value = 0
            channelListReload()
        }
        const deleteCancel = ()=>{
            deleteChannelId.value = 0
        }

        //編集処理
        const updateChannelId = ref(0)
        const updateIdChange = (id)=>{
            updateChannelId.value = id 
        }
        const channelUpdateFinish = ()=>{
            channelListReload()
            updateChannelId.value = 0
            channelMenuId.value = 0
        }

        return {
            channelItems,
            channelListReload,
            toChannel,
            changeChannel,
            channelMenuId,
            foldMenu,
            deleteChannelId,
            deleteConfirm,
            deleteChannelIdRequest,
            deleteCancel,
            updateChannelId,
            updateIdChange,
            channelUpdateFinish,
        }
    },
}
</script>