<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\UserController;

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

Route::group(['prefix' => '/v1'], function(){

    // User Login request routes
    Route::match([ 'post'], 'login-request', [UserController::class, 'loginRequest']);
    Route::match([ 'get'], 'login-check', [UserController::class, 'loginCheck']);
    Route::match([ 'post'], 'update-request', [UserController::class, 'updateDetails']);
      
});
