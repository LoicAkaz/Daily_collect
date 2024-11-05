<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\user;
//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
//Route::get('/', function () {
//    return 'API';
//});
Route::apiResource('users',user::class)->middleware('auth:sanctum');
Route::apiResource('clients', clientController::class);
Route::apiResource('transactions', transactionController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class ,'login']);
Route::post('/logout', [AuthController::class , 'logout'])->middleware('auth:sanctum');
