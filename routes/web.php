<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;

Route::redirect('/', '/dashboard');
Route::view('/dashboard', 'dashboard.index')->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');;
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/items', [ItemController::class, 'index'])->name('items');
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements');
    Route::get('/stock-movements/data', [StockMovementController::class, 'data'])->name('stock-movements.data');

    Route::middleware('role:admin,staff_gudang')->group(function () {
        Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
        Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
        Route::get('/suppliers/data', [SupplierController::class, 'data'])->name('suppliers.data');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });
});
