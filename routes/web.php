<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\FactoryController;
use App\Http\Controllers\Users\AgentController;
use App\Http\Controllers\Users\WarrantyController;
use App\Http\Controllers\Users\CustomerController;
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
        
        Route::get('/product_line', [AdminController::class, 'product_line'])->name('product_line');
        Route::get('/create_product_line', [AdminController::class, 'create_product_line'])->name('create_product_line');
        Route::post('/store_product_line', [AdminController::class, 'store_product_line'])->name('store_product_line');

        Route::get('/statistic', [AdminController::class, 'product_statistic'])->name('product_statistic');
        Route::post('/print_statistic', [AdminController::class, 'print_product_statistic'])->name('print_statistic');

        Route::get('/show_batches_recall', [AdminController::class, 'show_batches_recall'])->name('show_batches_recall');
        Route::get('/new_batch_recall', [AdminController::class, 'new_batch_recall'])->name('new_batch_recall');
        Route::get('/post_new_batch_recall', [AdminController::class, 'post_new_batch_recall'])->name('post_new_batch_recall');
        Route::get('/return_batch_recall/{id}', [AdminController::class, 'return_batch_recall'])->name('return_batch_recall');
    });

    Route::name('factory.')->prefix('factory')->middleware(['author:factory'])->group(function () {
        Route::get('/', [FactoryController::class, 'index']);
        Route::get('/create_batch', [FactoryController::class, 'create_batch'])->name('create_batch');
        Route::post('/create_batch', [FactoryController::class, 'create_batch_post'])->name('create_batch_post');

        Route::get('/factory_depots', [FactoryController::class, 'factory_depots'])->name('factory_depots');
        Route::get('/add_factory_depot', [FactoryController::class, 'add_factory_depot'])->name('add_factory_depot');
        Route::post('/add_factory_depot', [FactoryController::class, 'post_add_factory_depot'])->name('post_add_factory_depot');
        Route::get('/delete_factory_depot/{id}', [FactoryController::class, 'delete_factory_depot'])->name('delete_factory_depot');
        Route::get('/edit_factory_depot/{id}', [FactoryController::class, 'edit_factory_depot'])->name('edit_factory_depot');
        Route::post('/edit_factory_depot/{id}', [FactoryController::class, 'put_edit_factory_depot'])->name('put_edit_factory_depot');

        Route::get('/transfer_prod_to_agent', [FactoryController::class, 'transfer_prod_to_agent'])->name('transfer_prod_to_agent');
        Route::post('/transfer_prod_to_agent', [FactoryController::class, 'post_transfer_prod_to_agent'])->name('post_transfer_prod_to_agent');

        Route::get('/depot_product', [FactoryController::class, 'depot_product'])->name('depot_product');

        Route::get('statistic', [FactoryController::class, 'product_statistic'])->name('product_statistic');
        Route::post('print_statistic', [FactoryController::class, 'print_product_statistic'])->name('print_statistic');

        Route::get('sales_statistic', [FactoryController::class, 'product_sales_statistic'])
        ->name('product_sales_statistic');
        Route::post('print_sales_statistic', [FactoryController::class, 'print_product_sales_statistic'])
        ->name('print_product_sales_statistic');

    });

    Route::name('warranty.')->prefix('warranty')->middleware(['author:warranty'])->group(function () {
        Route::get('/', [WarrantyController::class, 'index']);
        Route::get('/show_product', [WarrantyController::class, 'show_product'])->name('show_product');
        Route::get('/return_prod_to_agent/{id}', [WarrantyController::class, 'return_prod_to_agent'])->name('return_prod_to_agent');
        Route::get('/return_prod_to_factory/{id}', [WarrantyController::class, 'return_prod_to_factory'])->name('return_prod_to_factory');

    });

    Route::name('customer.')->prefix('customer')->middleware(['author:customer'])->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/show_product', [CustomerController::class, 'show_product'])->name('show_product');
        Route::get('/warranty_claim/{id}', [CustomerController::class, 'warranty_claim'])->name('warranty_claim');
        Route::post('/send_warranty_claim', [CustomerController::class, 'send_warranty_claim'])->name('send_warranty_claim');
    });

    Route::name('agent.')->prefix('agent')->middleware(['author:agent'])->group(function () {
        Route::get('/', [AgentController::class, 'index']);
        Route::get('/depot_product', [AgentController::class, 'depot_product'])->name('depot_product');
        Route::get('/waiting_products', [AgentController::class, 'waiting_products'])->name('waiting_products');
        Route::get('/transfer_to_depot', [AgentController::class, 'transfer_to_depot'])->name('transfer_to_depot');

        Route::get('/sell_to_customer', [AgentController::class, 'sell_to_customer'])->name('sell_to_customer');
        Route::get('/check_sell_to_customer', [AgentController::class, 'check_sell_to_customer'])->name('check_sell_to_customer');
        Route::post('/confirm_sell_to_customer', [AgentController::class, 'confirm_sell_to_customer'])->name('confirm_sell_to_customer');
        
        Route::get('/show_product_warranty', [AgentController::class, 'show_product_warranty'])->name('show_product_warranty');
        Route::post('/transfer_error_prod_to_warranty', [AgentController::class, 'transfer_error_prod_to_warranty'])->name('transfer_error_prod_to_warranty');
        Route::post('/transfer_error_prod_return_to_customer', [AgentController::class, 'transfer_error_prod_return_to_customer'])->name('transfer_error_prod_return_to_customer');
    });

    Route::post('password/change', [ChangePasswordController::class, 'index'])->name('password.change');
});


Auth::routes(['verify' => true]);
