<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
Route::post('/login', [AuthController::class, 'login']); //created user get token here
Route::post('/register', [AuthController::class, 'register']); //create user here

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('/users', UserController::class);    

    //get user id
    Route::get('/isloggin', [AuthController::class, 'isLoggin']);

    //logout
    Route::get('/logout', [AuthController::class, 'logout']);
});

// Route::resource('/users', UserController::class);
