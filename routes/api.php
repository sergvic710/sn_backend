<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Route::get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::get('/export-device', function () {
//    dd('1');
//});


//Route::prefix('export')->group(function () {
//    Route::get('/device', [\App\Http\Controllers\Api\DevicesController::class,'export']);
//});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [\App\Http\Controllers\UserController::class,'login']);
//Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class,'register']);
Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/devices',[\App\Http\Controllers\Api\DevicesController::class,'index']);
    Route::post('/devices/save-columns',[\App\Http\Controllers\Api\DevicesController::class,'saveColumns']);
    Route::get('/devices/get-columns',[\App\Http\Controllers\Api\DevicesController::class,'getColumns']);
    Route::post('/devices/notrespond/save-columns',[\App\Http\Controllers\Api\DevicesController::class,'saveColumnsNotRespond']);
    Route::get('/devices/notrespond/get-columns',[\App\Http\Controllers\Api\DevicesController::class,'getColumnsNotRespond']);

    Route::post('/cards',[\App\Http\Controllers\Api\CardsController::class,'index']);
    Route::post('/cards/save-columns',[\App\Http\Controllers\Api\CardsController::class,'saveColumns']);
    Route::get('/cards/get-columns',[\App\Http\Controllers\Api\CardsController::class,'getColumns']);
    Route::post('/saveclient',[\App\Http\Controllers\Api\CardsController::class,'save']);

    Route::post('/export-devices', [\App\Http\Controllers\Api\DevicesController::class,'export']);
    Route::post('/export-devicesnotrespond', [\App\Http\Controllers\Api\DevicesController::class,'exportnotrespond']);
    Route::post('/export-cards', [\App\Http\Controllers\Api\CardsController::class,'export']);
//    Route::post('/clientcards',[\App\Http\Controllers\Api\ClientCardController::class,'index']);
//    Route::get('/accounts',[\App\Http\Controllers\Api\ClientCardController::class,'index']);
//    Route::resource('users', \App\Http\Controllers\UserController::class);
});


//Route::get('/devices',[\App\Http\Controllers\Api\DevicesController::class,'index']);
//Route::post('/clientcards',[\App\Http\Controllers\Api\ClientCardController::class,'index']);
//Route::get('/accounts',[\App\zHttp\Controllers\Api\ClientCardController::class,'index']);
//Route::resource('users', \App\Http\Controllers\UserController::class);
