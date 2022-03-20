<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Todo;
use App\Models\Channel;

class HomeController extends Controller
{

    public function DBGet(){
        $items = DB::table('todo')->get(['id','todo_content','deadline']);
        $res = json_encode($items);
        return $res;
    }

    public function show(){
        // if(session('current_channel') != null){
        //     $current_channel = session('current_channel');
        // }else{
        //     $current_channel = 'やることリスト';
        // }
        // $items = DB::table('todo')->where('channel',$current_channel)->get();
        // $channels = DB::table('channel')->get();
        return view('home');
    }


    public function store(Request $request){
        //+ボタンの処理
        // if($request->type == 'add'){
            // $rules = [
            //     'todo_content' => 'required|unique:todo,todo_content',
            // ];
            // $message = [
            //     'todo_content.required' => 'タスクを入力してください',
            //     'todo_content.unique' => 'そのタスクはすでに存在します',
            //     'deadline.after' => '期限は今よりも後にしてください',
            // ];
            // $validator = Validator::make($request->all(),$rules,$message);
            // $validator->sometimes('deadline','after:now',function($input){
            //     return $input->deadline != null;
            // });
            // if($validator->fails()){
            //     return redirect('/todo')->withErrors($validator)->withInput();
            // }
            $todo = new Todo();
            $form = $request->all();
            unset($form['_token']);
            $todo->fill($form)->save();

            return view('home');

        //×ボタンの処理
        if($request->has('delete')){
            DB::table('todo')->where('id',$request->delete)->delete();
            
            $current_channel = $request->channel;
            return redirect('/todo')->with(compact('current_channel'));
        }

        //まとめて削除ボタンの処理
        if($request->has('delete_multi_request')){
            $current_channel = $request->delete_multi_request;
            $items = DB::table('todo')->where('channel',$current_channel)->get();
            $items->mode = 'delete';
            $channels = DB::table('channel')->get();
            return view('home',compact('current_channel','items','channels'));   
        }

        //削除ボタンの処理
        if($request->has('delete_multi')){
            $delete_items = $request->get('delete_items');
            if($delete_items != null){
                foreach($delete_items as $delete_item){
                    DB::table('todo')->where('channel',$request->delete_multi)->where('id',$delete_item)->delete();
                }
            }

            $current_channel = $request->channel;
            return redirect('/todo')->with(compact('current_channel'));
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

            $current_channel = $request->channel;
            return redirect('/todo')->with(compact('current_channel'));
        }

        //チャンネル+ボタン
        if($request->has('add_channel')){
            $rules = [
                'name' => 'required|unique:channel,name',
            ];
            $message = [
                'name.required' => 'チャンネル名を入力してください',
                'name.unique' => 'その名前のチャンネルはすでに存在します'
            ];
            $validator = Validator::make($request->all(),$rules,$message);
            if($validator->fails()){
                return redirect('/todo')->withErrors($validator);
            }

            $new_channel = new Channel();
            $form = $request->all();
            unset($form['_token']);
            $new_channel->fill($form)->save();

            $current_channel = $request->name;
            return redirect('/todo')->with(compact('current_channel'));
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
