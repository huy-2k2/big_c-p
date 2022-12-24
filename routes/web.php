<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\FactoryController;
use App\Http\Controllers\Users\AgentController;
use App\Http\Controllers\Users\WarrantyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::get('notfound', fn () => view('notfound'));
Route::get('notaccept',  fn () => view('notaccept'));

Route::group(['middleware' => ['auth', 'verified', 'accepted', 'token']], function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['middleware' => 'author:admin'], function () {
        Route::get('admin', [AdminController::class, 'index']);
        Route::get('admin/create_notifi', [AdminController::class, 'create_notifi'])->name('admin.create_notifi');
        Route::post('admin/store_notifi', [AdminController::class, 'store_notifi'])->name('admin.store_notifi');
        Route::get('admin/notifi', [AdminController::class, 'notifi'])->name('admin.notifi');
        Route::get('admin/accept_user', [AdminController::class, 'accept_user'])->name('admin.accept_user');
    });

    Route::group(['middleware' => 'author:factory'], function () {
        Route::get('factory', [FactoryController::class, 'index']);
    });

    Route::group(['middleware' => 'author:warranty'], function () {
        Route::get('warranty', [WarrantyController::class, 'index']);
    });

    Route::group(['middleware' => 'author:agent'], function () {
        Route::get('agent', [AgentController::class, 'index']);
    });

    Route::post('password/change', [ChangePasswordController::class, 'index'])->name('password.change');
});


Auth::routes(['verify' => true]);
