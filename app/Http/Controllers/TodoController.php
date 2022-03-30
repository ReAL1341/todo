<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoInputRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Todo;


class TodoController extends Controller
{

    public function show(){
        return view('index');
    }

    //todoリスト取得処理
    public function itemsResponse(Request $request){
        $items = DB::table('todo')->where('channel',$request->input('channel'))->get(['id','todo_content','deadline_month','deadline_date','deadline_time']);
        $res = json_encode($items);
        return $res;
    }

    //追加ボタンの処理
    public function store(TodoInputRequest $request){
        $todo = new Todo();
        $input = $request->all();
        unset($input['_token']);
        $todo->fill($input)->save();
        return view('index');
    }

    //削除ボタンの処理
    public function delete(Request $request){
        DB::table('todo')->where('id',$request->input('id'))->delete();
        return view('index');
    }

    //編集ボタンの処理
    public function preUpdateResponse(Request $request){
        $items = DB::table('todo')->where('id',$request->input('id'))->get();
        $res = json_encode($items);
        return $res;
    }

    //編集完了ボタンの処理
    public function update(TodoInputRequest $request){
        $param = [
            'todo_content' => $request->input('todo_content'),
            'deadline_month' => $request->input('deadline_month'),
            'deadline_date' => $request->input('deadline_date'),
            'deadline_time' => $request->input('deadline_time'),
        ];
        DB::table('todo')->where('id',$request->input('id'))->update($param);
        return view('index');
    }
}