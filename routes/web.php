<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCategoryController as CategoryController;
use App\Http\Controllers\AdminProductController as ProductController;
use App\Http\Controllers\AdminOrderController as OrderController;

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

Route::redirect('/', 'login');

Route::prefix('Administrator')->name('admin.')->group(function(){

    Route::middleware('auth')->group(function(){
        Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class)->only(['store']);
        Route::resource('orders', OrderController::class);
        Route::post('orderSelectedProducts', [OrderController::class, 'getOrderSelectedProducts'])->name('selectedprods');
        Route::post('orderChangeQuantity', [OrderController::class, 'changeQuantity'])->name('changeQuantity');
    });

});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
