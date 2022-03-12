<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Todo;

class HomeController extends Controller
{
    public function show(){
        return view('home');
    }

    public function store(Request $request){
        //保存処理
        $todo = new Todo();       //インスタンス作成
        $form = $request->all();   //セッションからデータを受け取る
        unset($form['_token']);
        $todo->fill($form)->save();  //fillはモデルの$fillableに引数を代入

        $items = DB::table('todo')->get();

        return view('home',['items'=> $items]);
    }
}
