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

Route::resource('events', EventController::class);

//Доступ до усіх маршрутів групи events лише для автентифікованого користувача з роллю admin
//Route::resource('events',EventController::class)->middleware(['auth', 'isAdmin']);

Route::get('/events/{event}/confirm-delete', [EventController::class, 'confirmDelete'])
        ->name('events.confirm_delete')->middleware(['auth', 'isAdmin']);
