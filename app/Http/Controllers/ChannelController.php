<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Channel;
use Illuminate\Http\Request;
use App\Http\Requests\ChannelInputRequest;
use App\Http\Requests\ChannelUpdateRequest;


class ChannelController extends Controller
{
    //チャンネル一覧表示処理
    public function channelResponse(){
        $channels = Channel::get(['id','name']);
        $res = json_encode(($channels));
        return $res;
    }

    //チャンネル追加ボタンの処理
    public function channelStore(ChannelInputRequest $request){
        $newChannel = new Channel();
        $form = $request->all();
        unset($form['_token']);
        $newChannel->fill($form)->save();
        return view('index');
    }

    //チャンネル削除ボタンの処理
    public function channelDelete(Request $request){
        $deleteChannel = Channel::where('id',$request->input('id'))->first();
        Todo::where('channel',$deleteChannel->name)->delete();
        Channel::where('id',$request->input('id'))->delete();
        return $deleteChannel->name;
    }

    //編集完了ボタンの処理
    public function channelUpdate(ChannelUpdateRequest $request){
        $nowChannel = Channel::where('id',$request->input('id'))->first();
        $nameParam = [
            'name' => $request->input('name'),
        ];
        Channel::where('id',$request->input('id'))->update($nameParam);
        $newChannel = Channel::where('id',$request->input('id'))->first();
        $channelParam = [
            'channel' => $newChannel->name,
        ];
        Todo::where('channel',$nowChannel->name)->update($channelParam);
    }
}