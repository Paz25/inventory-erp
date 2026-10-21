<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Models\Item;

Route::get('/', function () {
    return redirect()->route('items');
});

Route::get('/dashboard', function () {
    return redirect()->route('items');
})->name('dashboard');

Route::get('/items', [ItemController::class, 'index'])->name('items');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
