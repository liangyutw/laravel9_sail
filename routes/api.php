<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/info', function () {
    return phpinfo();
});

Route::get('/insert/user', [App\Http\Controllers\SwooleController::class, 'insertUsers']);
Route::get('/insert/message', [App\Http\Controllers\SwooleController::class, 'insertMessage']);

Route::post('sendmessage', [App\Http\Controllers\ChatController::class, 'sendMessage']);
Route::get('getmessage', [App\Http\Controllers\ChatController::class, 'getMessage']);

Route::get('/swoole', [App\Http\Controllers\SwooleController::class, 'test']);
Route::get('/swoole_client', [App\Http\Controllers\SwooleController::class, 'client']);