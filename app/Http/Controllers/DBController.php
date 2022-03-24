<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DBController extends Controller
{
    public function DBResponse(){
        $items = DB::table('todo')->get(['id','todo_content','deadline']);
        $res = json_encode($items);
        return $res;
    }

    public function currentDataResponse(Request $request){
        $items = DB::table('todo')->where('id',$request->input('id'))->get();
        $res = json_encode($items);
        return $res;
    }
}
