<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Channel;

class ChannelController extends Controller
{
    public function channelResponse(){
        $channels = DB::table('channel')->get(['id','name']);
        $res = json_encode(($channels));
        return $res;
    }

    //チャンネル追加ボタン
    public function channelStore(Request $request){
        $newChannel = new Channel();
        $form = $request->all();
        unset($form['_token']);
        $newChannel->fill($form)->save();
        return view('index');
    }
}