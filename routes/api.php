<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
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
Route::apiResource('clients', clientController::class)->middleware('auth:sanctum');
Route::apiResource('transactions', transactionController::class)->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class ,'login']);
Route::post('/logout', [AuthController::class , 'logout'])->middleware('auth:sanctum');
Route::post('/messages', [ChatController::class, 'sendMessage'])->middleware('auth:sanctum');
Route::post('/getMessages', [ChatController::class, 'getMessages'])->middleware('auth:sanctum');
Route::post('/clientTransactions', [TransactionController::class, 'clientTransactions'])->middleware('auth:sanctum');
Route::post('/maxTransaction', [TransactionController::class, 'highestTransaction'])->middleware('auth:sanctum');
Route::post('/totalTransaction', [TransactionController::class, 'totalTransaction'])->middleware('auth:sanctum');
Route::post('/averageTransaction', [TransactionController::class, 'averageTransaction'])->middleware('auth:sanctum');
Route::get('/latestTransaction', [TransactionController::class, 'latestTransactions'])->middleware('auth:sanctum');
Route::get('/totalTransactions', [TransactionController::class, 'totalTransactions'])->middleware('auth:sanctum');
Route::get('/weeklyTransactionStats', [TransactionController::class, 'getWeeklyTransactionStats']);
Route::get('/monthlyTransactionStats', [TransactionController::class, 'getMonthlyTransactions']);
Route::get('/semesterTransactionStats', [TransactionController::class, 'getSemestralTransactions']);
