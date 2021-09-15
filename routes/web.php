<?php

use App\Http\Controllers\UrlController;
use App\Http\Controllers\CheckController;
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

Route::view('/', 'index')->name('index');
Route::resource('urls', UrlController::class)
    ->only(['store', 'index', 'show'])
    ->parameters(['urls' => 'id']);
Route::post('/urls/{id}/checks', [CheckController::class, 'store'])->name('checks.store');
