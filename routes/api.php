<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Users\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth.api']], function () {
    Route::post('/notification/mark_readed', [NotificationController::class, 'mark_readed']);

    Route::group(['middleware' => ['author.api:admin']], function () {
        Route::post('admin/accept_user/store', [AdminController::class, 'accept_user_store'])->name('admin.accept_user.store');
        Route::post('admin/accept_user/remove', [AdminController::class, 'accept_user_remove'])->name('admin.accept_user.remove');
    });
});
