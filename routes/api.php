<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\AgentController;
use App\Http\Controllers\Users\FactoryController;
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
        Route::post('admin/product_line/update', [AdminController::class, 'update_product_line'])->name('admin.product_line.update');
        Route::post('/return_batch_recall', [AdminController::class, 'return_batch_recall'])->name('admin.return_batch_recall');
    });

    Route::group(['middleware' => ['author.api:factory']], function () {
        Route::post('/edit_factory_depot', [FactoryController::class, 'put_edit_factory_depot'])->name('factory.put_edit_factory_depot');
    });

    Route::group(['middleware' => ['author.api:agent']], function () {
        Route::post('/edit_agent_depot', [AgentController::class, 'put_edit_agent_depot'])->name('agent.put_edit_agent_depot');
        Route::post('/transfer_to_depot', [AgentController::class, 'transfer_to_depot'])->name('agent.transfer_to_depot');
    });;
});
