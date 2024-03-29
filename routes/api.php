<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ChannelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/todo',[TodoController::class,'show']);
Route::post('/todo/store',[TodoController::class,'store']);
Route::post('/todo/delete',[TodoController::class,'delete']);
Route::post('/todo/update',[TodoController::class,'update']);
Route::post('/todo/response',[TodoController::class,'itemsResponse']);

Route::get('/channel',[ChannelController::class,'channelResponse']);
Route::post('/channel/store',[ChannelController::class,'channelStore']);
Route::post('/channel/delete',[ChannelController::class,'channelDelete']);
Route::post('/channel/update',[ChannelController::class,'channelUpdate']);