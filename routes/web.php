<?php

use App\Http\Controllers\UrlController;
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
Route::post('/urls', [UrlController::class, 'store'])->name('urls.store');
Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
Route::get('/urls/{id}', [UrlController::class, 'show'])->name('urls.show');
Route::post('/urls/{id}/checks', [UrlController::class, 'checkStore'])->name('urls.checks.store');
