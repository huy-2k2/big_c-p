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

    Route::name('admin.')->prefix('admin')->middleware(['author:admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/create_notifi', [AdminController::class, 'create_notifi'])->name('create_notifi');
        Route::post('/store_notifi', [AdminController::class, 'store_notifi'])->name('store_notifi');
        Route::get('/notifi', [AdminController::class, 'notifi'])->name('notifi');
        Route::get('/accept_user', [AdminController::class, 'accept_user'])->name('accept_user');
    });

    Route::name('factory.')->prefix('factory')->middleware(['author:factory'])->group(function () {
        Route::get('/', [FactoryController::class, 'index']);
        Route::get('/create_batch', [FactoryController::class, 'create_batch'])->name('create_batch');
        Route::post('/create_batch', [FactoryController::class, 'create_batch_post']);
    });

    Route::name('warranty.')->prefix('warranty')->middleware(['author:warranty'])->group(function () {
        Route::get('/', [WarrantyController::class, 'index']);
    });

    Route::name('agent.')->prefix('agent')->middleware(['author:agent'])->group(function () {
        Route::get('agent', [AgentController::class, 'index']);
    });

    Route::post('password/change', [ChangePasswordController::class, 'index'])->name('password.change');
});


Auth::routes(['verify' => true]);
