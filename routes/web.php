<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\FactoryController;
use App\Http\Controllers\Users\VendorController;
use App\Http\Controllers\Users\WarrantyCenterController;
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

        Route::get('admin/product_line', [AdminController::class, 'product_line'])->name('admin.product_line');
        Route::get('admin/create_product_line', [AdminController::class, 'create_product_line'])->name('admin.create_product_line');
        Route::post('admin/store_product_line', [AdminController::class, 'store_product_line'])->name('admin.store_product_line');

        Route::get('admin/statistic', [AdminController::class, 'product_statistic'])->name('admin.product_statistic');
        Route::post('admin/print_statistic', [AdminController::class, 'print_product_statistic'])->name('admin.print_statistic');
    });

    Route::group(['middleware' => 'author:factory'], function () {
        Route::get('factory', [FactoryController::class, 'index']);
    });

    Route::group(['middleware' => 'author:warranty'], function () {
        Route::get('warranty', [WarrantyCenterController::class, 'index']);
    });

    Route::group(['middleware' => 'author:agent'], function () {
        Route::get('agent', [VendorController::class, 'index']);
    });

    Route::post('password/change', [ChangePasswordController::class, 'index'])->name('password.change');
});


Auth::routes(['verify' => true]);
