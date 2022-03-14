<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Todo;

class HomeController extends Controller
{


    public function show(){

        $items = DB::table('todo')->get();
        return view('home',compact('items'));
    
    }


    public function store(Request $request){
   
        //+ボタンの処理
        if($request->has('add')){
            //保存処理
            $todo = new Todo();
            $form = $request->all();
            unset($form['_token']);
            $todo->fill($form)->save();

 
            $items = DB::table('todo')->get();
            return redirect('/todo')->with(compact('items'));
        }

        //×ボタンの処理
        if($request->has('delete')){
            //レコード削除処理
            DB::table('todo')->where('id',$request->delete)->delete();
            
            $items = DB::table('todo')->get();
            return redirect('/todo')->with(compact('items'));
        }

        //編集ボタンの処理
        if($request->has('edit')){
            $items = DB::table('todo')->get();
            foreach($items as $item){
                if($item->id == $request->edit){
                    $item->mode = 'edit';
                }
            };
            return view('home',compact('items'));
        }

        //編集完了ボタンの処理
        if($request->has('update')){
            $param = [
                'todo_content' => $request->todo_content,
                'deadline' => $request->deadline,
            ];
            DB::table('todo')->where('id',$request->update)->update($param);
            
            $items = DB::table('todo')->get();
            return redirect('/todo')->with(compact('items'));
        }
    }
}
