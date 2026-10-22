<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect()->route('items');
});

Route::get('/dashboard', function () {
    return redirect()->route('items');
})->name('dashboard');

Route::get('/items', [ItemController::class, 'index'])->name('items');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements');
Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
