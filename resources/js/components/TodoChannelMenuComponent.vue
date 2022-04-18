<template>
    <div v-if="menuFlag == itemId">
        <input
            v-bind:id="itemId+'hidden'"
            class="button-hidden"
            type="radio"
            v-on:change="foldMenuEmit"
        >
        <label
            class="channel-menu-background"
            v-bind:for="itemId+'hidden'"
        ></label>
        <div class="channel-menu-content">
            <input
                v-model="updateChannelId"
                v-bind:id="itemId+'channel-update'"
                v-bind:value="itemId"
                class="button-hidden"
                type="radio"
                v-on:change="updateIdChangeEmit"
            >
            <label v-bind:for="itemId+'channel-update'">編集</label>
            <input
                v-model="deleteChannelId"
                v-bind:id="itemId+'channel-delete'"
                v-bind:value="itemId"
                class="button-hidden"
                type="radio"
                v-on:change="deleteConfirmEmit"
            >
            <label v-bind:for="itemId+'channel-delete'">削除</label>
        </div>
    </div>
</template>


<script>
import { ref, watchEffect } from 'vue'
export default {
    name:'TodoChannelMenuComponent',
    props:{
        itemId:{
            type:Number,
            required:true,
        },
        channelMenuId:{
            type:Number,
            required:true,
        },
    },
    emits:[
        'fold-menu',
        'update-id-change',
        'delete-confirm',
    ],
    setup(props,{emit}) {
        
        const menuFlag = ref(0)
        watchEffect(()=>{
            menuFlag.value = props.channelMenuId
        })

        const foldMenuEmit = ()=>{
            emit('fold-menu')
        }

        // 編集ボタンの処理
        const updateChannelId = ref(0)
        const updateIdChangeEmit = ()=>{
            emit('update-id-change',updateChannelId.value)
        }

        // 削除ボタンの処理
        const deleteChannelId = ref(0)
        const deleteConfirmEmit = ()=>{
            emit('delete-confirm',deleteChannelId.value)
        }

        return {
            menuFlag,
            foldMenuEmit,
            updateChannelId,
            updateIdChangeEmit,
            deleteChannelId,
            deleteConfirmEmit,
        }

    },
}
</script>