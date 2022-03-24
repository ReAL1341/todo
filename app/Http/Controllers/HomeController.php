<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Todo;
use App\Models\Channel;

class HomeController extends Controller
{
    public function show(){
        return view('home');
    }


    public function store(Request $request){
    //+ボタンの処理
        $todo = new Todo();
        $input = $request->all();
        unset($input['_token']);
        $todo->fill($input)->save();
        return view('home');
    }

    //削除ボタンの処理
    public function delete(Request $request){
        DB::table('todo')->where('id',$request->input('id'))->delete();
        return view('home');
    }

    //編集ボタンの処理
    public function update(Request $request){
        $param = [
            'todo_content' => $request->input('todo_content'),
            'deadline_month' => $request->input('deadline_month'),
            'deadline_date' => $request->input('deadline_date'),
            'deadline_time' => $request->input('deadline_time'),
        ];
        DB::table('todo')->where('id',$request->input('id'))->update($param);            
        return view('home');
    }


    //まとめて削除ボタンの処理
    // if($request->has('delete_multi_request')){
    //     $current_channel = $request->delete_multi_request;
    //     $items = DB::table('todo')->where('channel',$current_channel)->get();
    //     $items->mode = 'delete';
    //     $channels = DB::table('channel')->get();
    //     return view('home',compact('current_channel','items','channels'));   
    // }

    //削除ボタンの処理
    // if($request->has('delete_multi')){
    //     $delete_items = $request->get('delete_items');
    //     if($delete_items != null){
    //         foreach($delete_items as $delete_item){
    //             DB::table('todo')->where('channel',$request->delete_multi)->where('id',$delete_item)->delete();
    //         }
    //     }

    //     $current_channel = $request->channel;
    //     return redirect('/todo')->with(compact('current_channel'));
    // }





        // //チャンネル+ボタン
        // if($request->has('add_channel')){
        //     $rules = [
        //         'name' => 'required|unique:channel,name',
        //     ];
        //     $message = [
        //         'name.required' => 'チャンネル名を入力してください',
        //         'name.unique' => 'その名前のチャンネルはすでに存在します'
        //     ];
        //     $validator = Validator::make($request->all(),$rules,$message);
        //     if($validator->fails()){
        //         return redirect('/todo')->withErrors($validator);
        //     }

        //     $new_channel = new Channel();
        //     $form = $request->all();
        //     unset($form['_token']);
        //     $new_channel->fill($form)->save();

        //     $current_channel = $request->name;
        //     return redirect('/todo')->with(compact('current_channel'));
        // }

        // //チャンネル変更ボタン
        // if($request->has('change_channel')){
        //     $request->session()->put('current_channnel',$request->change_channel);
        //     $current_channel = $request->change_channel;
        //     $items = DB::table('todo')->where('channel',$current_channel)->get();
        //     $channels = DB::table('channel')->get();
        //     return view('home',compact('current_channel','items','channels'));
        // }

        // //チャンネル削除ボタン
        // if($request->has('channel_delete')){
        //     DB::table('channel')->where('name',$request->channel_delete)->delete();
        //     DB::table('todo')->where('channel',$request->channel_delete)->delete();
            
        //     return redirect('/todo');
        // }
}