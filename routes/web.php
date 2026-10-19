<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ItemController::class, 'index'])
    ->name('dashboard');

Route::post('/items', [ItemController::class, 'store'])
    ->name('items.store');

Route::put('/items/{item}', [ItemController::class, 'update'])
    ->name('items.update');

Route::delete('/items/{item}', [ItemController::class, 'destroy'])
    ->name('items.destroy');
