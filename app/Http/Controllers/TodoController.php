<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Todo;

class TodoController extends Controller
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

    public function itemsResponse(Request $request){
        $items = DB::table('todo')->where('channel',$request->input('channel'))->get(['id','todo_content','deadline_month','deadline_date','deadline_time']);
        $res = json_encode($items);
        return $res;
    }

    public function preUpdateResponse(Request $request){
        $items = DB::table('todo')->where('id',$request->input('id'))->get();
        $res = json_encode($items);
        return $res;
    }
}