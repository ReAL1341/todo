<template>
    <input
        v-model="toChannel"
        v-bind:id="item.id+'current-id'"
        v-bind:value="item.name"
        class="public-hidden"
        type="radio"
        v-on:change="changeChannelEmit(toChannel)"
    >
    <div class="channel-item">
        <label v-bind:for="item.id+'current-id'">{{item.name}}</label>
        <span v-if="item.name!=='やることリスト'">
            <input
                v-model="channelMenuId"
                v-bind:value="item.id"
                v-bind:id="item.id+'channel-menu'"
                class="public-hidden"
                type="radio"
                name="channel-menu-radio"
                v-on:change="menuIdChangeEmit"
            >
            <label v-bind:for="item.id+'channel-menu'">&#8942;</label>
        </span>
    </div>
</template>


<script>
import { ref } from 'vue'
export default {
    name:'TodoChannelItemComponent',
    props:{
        item:{
            type:Object,
            required:true,
        },
    },
    emits:[
        'change-channel',
        'menu-id-change',
    ],
    setup(props,{emit}){
        
        //チャンネル切り替え
        const toChannel = ref('')
        const changeChannelEmit = (toChannel)=>{
            emit('change-channel',toChannel)
        }

        const channelMenuId = ref(0)
        const menuIdChangeEmit = ()=>{
            emit('menu-id-change',channelMenuId.value)
        }

        return {
            toChannel,
            changeChannelEmit,
            channelMenuId,
            menuIdChangeEmit,
        }
    },
}
// radioのチェックを外したい
</script>