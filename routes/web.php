<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteCOntroller;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManageController;
use App\Http\Controllers\Admin\RequestController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [SiteCOntroller::class, 'index'])->name('home'); 
Route::get('logout', [SiteCOntroller::class, 'logout'])->name('logout'); 
Route::get('/home', [SiteCOntroller::class, 'index']); 
Route::post('login/check', [AdminDashboardController::class, 'login_check'])->name('login.check');
Route::get('reset-password', [AdminDashboardController::class, 'password_reset'])->name('resetpassword');
Route::prefix('admin')->middleware('admin', 'prevent-back-history')->group(function () {
    
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admindashboard');
    Route::post('details/update', [AdminDashboardController::class, 'update_record'])->name('details.update');
    Route::post('password/update', [AdminDashboardController::class, 'update_password'])->name('password.update');
    
    //Manage Users Routes
	Route::get('users/list', [UserManageController::class, 'getList'])->name('users.list');
	Route::get('user/status/update', [UserManageController::class, 'change_status'])->name('user.status');
	Route::get('user/add', [UserManageController::class, 'add_form'])->name('user.add');
	Route::post('user/create', [UserManageController::class, 'create_record'])->name('user.create');
	Route::get('user/edit/{id}', [UserManageController::class, 'edit_form'])->name('user.edit');
	Route::post('user/update', [UserManageController::class, 'update_record'])->name('user.update');
	Route::get('user/changepassword/{id}', [UserManageController::class, 'change_password'])->name('user.changepassword');
	Route::post('user/updatepassword', [UserManageController::class, 'update_password'])->name('user.updatepassword');
    Route::get('user/delete/{id}', [UserManageController::class, 'del_record'])->name('user.delete');

    //Manage login request Routes
	Route::get('login-request/list', [RequestController::class, 'getList'])->name('request.list');
	Route::get('login-request/status/update', [RequestController::class, 'changeStatus'])->name('request.status');
	Route::post('login-request/update', [RequestController::class, 'updateRequest'])->name('request.update');


});
