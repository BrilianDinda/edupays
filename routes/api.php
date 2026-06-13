<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::post('/tap-card', [FoodController::class, 'tapCard']);
Route::post('/save-scan', [FoodController::class, 'saveScan']);
Route::get('/last-scan', [FoodController::class, 'lastScan']);
Route::post('/buy-food', [FoodController::class, 'buyFromCard']);
Route::get('/foods', [FoodController::class, 'index']);
Route::post('/transaction', [TransactionController::class, 'store']);
Route::post('/saldo', [FoodController::class, 'saldo']);
Route::post('/buy/{id}', [FoodController::class, 'buy']);
Route::get('/history', [FoodController::class, 'history']);
Route::get('/history/{studentId}', [FoodController::class, 'historyByStudent']);
Route::get('/history/monthly/{studentId}', [FoodController::class, 'monthlyTotal']);
Route::post('/add-food', [FoodController::class, 'store']);
Route::post('/delete-food/{id}', [FoodController::class, 'destroy']);
Route::post('/update-food/{id}', [FoodController::class, 'update']);
Route::get('/students', [StudentController::class, 'index']);
Route::post('/add-student', [StudentController::class, 'store']);
Route::post('/delete-student/{id}', [StudentController::class, 'destroy']);
Route::post('/update-student/{id}', [StudentController::class, 'update']);
Route::post('/topup', [TransactionController::class, 'topup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/add-parent', [UserController::class, 'addParent']);
Route::get('/parents', [App\Http\Controllers\Api\UserController::class, 'parents']);
Route::post('/update-parent/{id}', [UserController::class, 'updateParent']);
Route::post('/delete-parent/{id}', [UserController::class, 'destroyParent']);
Route::get('/my-students/{userId}', [StudentController::class, 'myStudents']);