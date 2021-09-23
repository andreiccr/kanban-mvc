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
Route::patch('/l/{listt}/move', [App\Http\Controllers\ListtController::class, 'move'])->name('listt.move');
Route::get('/l/{listt}/edit', [App\Http\Controllers\ListtController::class, 'edit'])->name('listt.edit');

Route::get('/b/{board}', [App\Http\Controllers\WorkboardController::class, 'show'])->name('workboard.show');
Route::post('/b/', [App\Http\Controllers\WorkboardController::class, 'create'])->name('workboard.create');
Route::patch('/b/{board}', [App\Http\Controllers\WorkboardController::class, 'update'])->name('workboard.update');
Route::delete('/b/{board}', [App\Http\Controllers\WorkboardController::class, 'destroy'])->name('workboard.destroy');

Route::get('/b/{board}/u/{user}', [App\Http\Controllers\WorkboardController::class, 'member'])->name('workboard.member');
Route::post('/b/{board}/u/{user}', [App\Http\Controllers\WorkboardController::class, 'register'])->name('workboard.register');
Route::delete('/b/{board}/u/{user}', [App\Http\Controllers\WorkboardController::class, 'unregister'])->name('workboard.unregister');

Route::get('/c/{card}', [App\Http\Controllers\CardController::class, 'get'])->name('card.get');
Route::post('/l/{listt}/c', [App\Http\Controllers\CardController::class, 'create'])->name('card.create');
Route::patch('/c/{card}', [App\Http\Controllers\CardController::class, 'update'])->name('card.update');
Route::patch('/c/{card}/move', [App\Http\Controllers\CardController::class, 'move'])->name('card.move');
Route::delete('/c/{card}', [App\Http\Controllers\CardController::class, 'destroy'])->name('card.destroy');

Route::get('/c/{card}/display', [App\Http\Controllers\CardController::class, 'display'])->name('card.display');
