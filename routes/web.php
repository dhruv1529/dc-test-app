<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/get-all-data', [UsersController::class,"index"]);
Route::post('/get-filter-data', [UsersController::class,"index"]);
Route::get('/dash', [UsersController::class,"dash"]);
Route::get('/re-filter', [UsersController::class,"reFilter"]);
Route::get('/top-3-users', [UsersController::class,"getTop3Users"]);
