<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//Доступ до маршрутів групи events лише для автентифікованого користувача
Route::resource('events',EventController::class)->middleware('auth');
Route::get('/events/{event}/confirm-delete', [EventController::class, 'confirmDelete'])->name('events.confirm_delete');
