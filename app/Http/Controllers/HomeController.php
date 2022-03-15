<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Todo;
use App\Models\Channel;

class HomeController extends Controller
{


    public function show(){
        $current_channel = 'やることリスト';
        $items = DB::table('todo')->where('channel',$current_channel)->get();
        $channels = DB::table('channel')->get();
        return view('home',compact('current_channel','items','channels'));
    }


    public function store(Request $request){
        //+ボタンの処理
        if($request->has('add')){
            //保存処理
            $todo = new Todo();
            $form = $request->all();
            unset($form['_token']);
            $todo->fill($form)->save();

            return redirect('/todo');
        }

        //×ボタンの処理
        if($request->has('delete')){
            //レコード削除処理
            DB::table('todo')->where('id',$request->delete)->delete();
            
            return redirect('/todo');
        }

        //編集ボタンの処理
        if($request->has('edit')){
            $current_channel = DB::table('todo')->where('id',$request->edit)->first();
            $current_channel = $current_channel->channel;
            $items = DB::table('todo')->where('channel',$current_channel)->get();
            $channels = DB::table('channel')->get();
            foreach($items as $item){
                if($item->id == $request->edit){
                    $item->mode = 'edit';
                }
            };
            return view('home',compact('current_channel','items','channels'));
        }

        //編集完了ボタンの処理
        if($request->has('update')){
            $param = [
                'todo_content' => $request->todo_content,
                'deadline' => $request->deadline,
            ];
            DB::table('todo')->where('id',$request->update)->update($param);

            return redirect('/todo');
        }

        //チャンネル+ボタン
        if($request->has('add_channel')){
            $new_channel = new Channel();
            $form = $request->all();
            unset($form['_token']);
            $new_channel->fill($form)->save();

            return redirect('/todo');
        }

        //チャンネル変更ボタン
        if($request->has('change_channel')){
            $request->session()->put('current_channnel',$request->change_channel);
            $current_channel = $request->change_channel;
            $items = DB::table('todo')->where('channel',$current_channel)->get();
            $channels = DB::table('channel')->get();
            return view('home',compact('current_channel','items','channels'));
        }

        //チャンネル削除ボタン
        if($request->has('channel_delete')){
            DB::table('channel')->where('name',$request->channel_delete)->delete();
            DB::table('todo')->where('channel',$request->channel_delete)->delete();
            
            return redirect('/todo');
        }
    }
}
