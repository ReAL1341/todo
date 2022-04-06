<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoInputRequest;
use App\Http\Requests\TodoUpdateRequest;


class TodoController extends Controller
{
    //ページの表示
    public function show(){
        return view('index');
    }

    //todoリスト取得処理
    public function itemsResponse(Request $request){
        $items = Todo::where('channel',$request->input('channel'))
                                    ->get(['id','todo_content','deadline_month','deadline_date','deadline_time']);
        $res = json_encode($items);
        return $res;
    }

    //追加ボタンの処理
    public function store(TodoInputRequest $request){
        $todo = new Todo();
        $input = $request->all();
        unset($input['_token']);
        $todo->fill($input)->save();
    }

    //削除ボタンの処理
    public function delete(Request $request){
        foreach($request->input('items') as $item){
            Todo::where('id',$item['id'])->delete();
        }
    }

    //編集完了ボタンの処理
    public function update(TodoUpdateRequest $request){
        $param = [
            'todo_content' => $request->input('todo_content'),
            'deadline_month' => $request->input('deadline_month'),
            'deadline_date' => $request->input('deadline_date'),
            'deadline_time' => $request->input('deadline_time'),
        ];
        Todo::where('id',$request->input('id'))->update($param);
    }
}