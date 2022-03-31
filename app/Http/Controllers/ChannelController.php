<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Channel;
use App\Http\Requests\ChannelInputRequest;

class ChannelController extends Controller
{
    //チャンネル一覧表示処理
    public function channelResponse(){
        $channels = DB::table('channel')->get(['id','name']);
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
        $deleteChannel = DB::table('channel')->where('id',$request->input('id'))->first();
        DB::table('todo')->where('channel',$deleteChannel->name)->delete();
        DB::table('channel')->where('id',$request->input('id'))->delete();
        return $deleteChannel->name;
    }

    //編集完了ボタンの処理
    public function channelUpdate(ChannelInputRequest $request){
        $nowChannel = DB::table('channel')->where('id',$request->input('id'))->first();
        $nameParam = [
            'name' => $request->input('name'),
        ];
        DB::table('channel')->where('id',$request->input('id'))->update($nameParam);
        $newChannel = DB::table('channel')->where('id',$request->input('id'))->first();
        $channelParam = [
            'channel' => $newChannel->name,
        ];
        DB::table('todo')->where('channel',$nowChannel->name)->update($channelParam);
    }
}