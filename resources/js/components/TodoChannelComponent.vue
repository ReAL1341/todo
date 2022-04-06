<template>
    <todo-channel-add-component
        v-on:channel-list-reload="channelListReload"
    ></todo-channel-add-component>



    <div>
        <div
            v-for="item in channelItems"
            v-bind:key="item.id"
        >

            <div
                v-if="deleteFlag"
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

            <div v-if="updateChannelId!=item.id && item.name=='やることリスト'">
                <input
                    v-model="toChannel"
                    v-bind:id="item.id+'current-id'"
                    v-bind:value="item.name"
                    class="button-hidden"
                    type="radio"
                    v-on:change="changeChannel(toChannel)"
                >
                <div class="channel-item"><label v-bind:for="item.id+'current-id'">{{item.name}}</label></div>
                
            </div>

            <div v-else-if="updateChannelId != item.id">
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
                        <label class="channel-name" v-bind:for="item.id+'current-id'">{{item.name}}</label>
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
                    </div>

                    <!-- ケバブメニューの内容 -->
                    <div v-if="channelMenuId == item.id">
                        <input
                            v-model="channelMenuId"
                            v-bind:value="''"
                            v-bind:id="item.id+'hidden'"
                            class="button-hidden"
                            type="radio"
                        >
                        <label
                            class="channel-menu-background"
                            v-bind:for="item.id+'hidden'"
                        ></label>
                        <div class="channel-menu-content">
                                <input
                                    v-model="updateChannelId"
                                    v-bind:id="item.id+'channel-update'"
                                    v-bind:value="item.id"
                                    class="button-hidden"
                                    type="radio"
                                >
                                <label v-bind:for="item.id+'channel-update'" class="channel-menu-button">編集</label>
                                <input
                                    v-model="deleteChannelId"
                                    v-bind:id="item.id+'channel-delete'"
                                    v-bind:value="item.id"
                                    class="button-hidden"
                                    type="radio"
                                    v-on:change="deleteConfirm"
                                >
                                <label v-bind:for="item.id+'channel-delete'" class="channel-menu-button">削除</label>
                        </div>
                    </div>
                </div>
            </div>
               

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

        //ケバブメニュー
        let channelMenuId = ref('')

        //削除ボタン
        let deleteChannelId = ref('')
        let deleteFlag = ref(false)
        const deleteConfirm = ()=>{
            channelMenuId.value = ''
            deleteChannelId.value = ''
            deleteFlag.value = true
        }
        const deleteChannelIdRequest = ()=>{
            axios.post('/api/channel/delete',{id:deleteChannelId.value}).then((res)=>{
                if(res.data == props.currentChannel){
                    changeChannel('やることリスト')
                }
            })
            deleteFlag.value = false
            deleteChannelId.value = ''
            channelListReload()
        }
        const deleteCancel = ()=>{
            deleteFlag.value = false
        }

        //編集処理
        let updateChannelId = ref('')
        const channelUpdateFinish = ()=>{
            updateChannelId.value = ''
            channelMenuId.value = ''
            channelListReload()
        }

        return {
            channelItems,
            channelListReload,
            toChannel,
            changeChannel,
            channelMenuId,
            deleteChannelId,
            deleteFlag,
            deleteConfirm,
            deleteChannelIdRequest,
            deleteCancel,
            updateChannelId,
            channelUpdateFinish,
        }
    },
}
</script>