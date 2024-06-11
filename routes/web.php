<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeTypeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::resource('items', ItemController::class)->except(['show', 'index'])->middleware('auth');
//item route that takes the hashid and returns the item
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::group(['prefix' => 'items'], function () {
    Route::get('/item/{public_id}', [ItemController::class, 'show'])->name('items.show');
});

Route::resource('attribute_types', AttributeTypeController::class)->middleware('auth');
Route::resource('attributes', AttributeController::class);

Route::get('/choice-helper', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
