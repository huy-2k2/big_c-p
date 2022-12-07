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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth'])->name('home');

Route::get('notfound', fn () => view('notfound'));
Route::get('notaccept',  fn () => view('notaccept'));

Route::group(['middleware' => ['auth', 'verified', 'accepted']], function () {
    Route::get('admin', [AdminController::class, 'index']);
    Route::get('admin/create_notifi', [AdminController::class, 'create_notifi'])->name('admin.create_notifi');
    Route::post('admin/store_notifi', [AdminController::class, 'store_notifi'])->name('admin.store_notifi');
    Route::get('admin/notifi', [AdminController::class, 'notifi'])->name('admin.notifi');
    Route::get('factory', [FactoryController::class, 'index']);
    Route::get('warranty_center', [WarrantyCenterController::class, 'index']);
    Route::get('vendor', [VendorController::class, 'index']);

    Route::post('password/change', [ChangePasswordController::class, 'index'])->name('password.change');
});


Auth::routes(['verify' => true]);
