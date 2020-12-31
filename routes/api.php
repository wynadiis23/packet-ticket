<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PacketController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//testing
Route::get('/getemail', [PacketController::class, 'getEmail']);

Route::middleware(['auth:sanctum'])->group(function() {
    //user route
    Route::resource('/users', UserController::class);  
    
    //packet route
    Route::resource('/packets', PacketController::class);  

    //get user id
    Route::get('/isloggin', [AuthController::class, 'isLoggin']);

    //logout
    Route::get('/logout', [AuthController::class, 'logout']);
});

//customer route
Route::resource('/customers', CustomerController::class);
// Route::resource('/users', UserController::class);
