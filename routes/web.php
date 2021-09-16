<?php

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


Auth::routes();

Route::get('/', [App\Http\Controllers\WorkboardController::class, 'index'])->name('workboard.home');

Route::post('/b/{board}/l', [App\Http\Controllers\ListtController::class, 'create'])->name('listt.create');
Route::delete('/l/{listt}', [App\Http\Controllers\ListtController::class, 'destroy'])->name('listt.destroy');
Route::patch('/l/{listt}', [App\Http\Controllers\ListtController::class, 'update'])->name('listt.update');
Route::get('/l/{listt}/edit', [App\Http\Controllers\ListtController::class, 'edit'])->name('listt.edit');

Route::get('/b/{board}', [App\Http\Controllers\WorkboardController::class, 'show'])->name('workboard.show');
Route::post('/b/', [App\Http\Controllers\WorkboardController::class, 'create'])->name('workboard.create');
Route::patch('/b/{board}', [App\Http\Controllers\WorkboardController::class, 'update'])->name('workboard.update');
Route::delete('/b/{board}', [App\Http\Controllers\WorkboardController::class, 'destroy'])->name('workboard.destroy');

Route::get('/c/{card}', [App\Http\Controllers\CardController::class, 'get'])->name('card.get');
Route::post('/l/{listt}/c', [App\Http\Controllers\CardController::class, 'create'])->name('card.create');
Route::patch('/c/{card}', [App\Http\Controllers\CardController::class, 'update'])->name('card.update');
Route::patch('/c/{card}/move', [App\Http\Controllers\CardController::class, 'move'])->name('card.move');
Route::delete('/c/{card}', [App\Http\Controllers\CardController::class, 'destroy'])->name('card.destroy');
