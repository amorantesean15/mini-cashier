<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cashier Routes
Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/cart/{id}', [CashierController::class, 'addToCart']);
    Route::get('/cart', [CashierController::class, 'showCart']);
    Route::post('/checkout', [CashierController::class, 'checkout']);
    Route::post('/cart/update/{id}', [CashierController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [CashierController::class, 'deleteCart'])->name('cart.delete');
});


// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::post('/items', [AdminController::class, 'addItem']);
    Route::get('/items/{id}/edit', [AdminController::class, 'editForm']); // show edit form
    Route::put('/items/{id}', [AdminController::class, 'updateItem']);     // update item
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem']);
    Route::get('/admin/reports/sales', [AdminController::class, 'salesReport'])->name('admin.salesReport');

});



