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

Route::view('/', 'urls.form')->name('home');
Route::get('/urls', [UrlController::class, 'index'])->name('url.index');
Route::post('/urls', [UrlController::class, 'store'])->name('url.store');
Route::get('/urls/{id}', [UrlController::class, 'show'])->name('url.show');
