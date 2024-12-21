<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientEventController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/athenticated', function (Request $request) {
    return true;
});

Route::get('/index',[ClientEventController::class, 'index']);
Route::get('/popular',[ClientEventController::class, 'popular']);
Route::get('/event/{id}',[ClientEventController::class, 'show']);
Route::post('/event/{id}/visit', [ClientEventController::class, 'incrementPopularity']);
Route::post('/events/search/{text}', [ClientEventController::class, 'search']);

Route::post(
    '/event/{id}/participate', 
    [ClientEventController::class, 'incrementNumber']
)->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post(
    '/emails/event-invitation/{idUser}/{idEvent}/{number}', 
    [ClientEventController::class, 'sendInvitationMail']
)->middleware('auth:sanctum');

Route::post(
    '/emails/event-ticket/{idUser}/{idEvent}/{typeTicket}/{finalPrice}/{number}', 
    [ClientEventController::class, 'sendTicketMail']
)->middleware('auth:sanctum');

Route::post(
    '/emails/attendance-report/{idUser}', 
    [ClientEventController::class, 'sendAttendanceReportMail']
)->middleware('auth:sanctum');

//Логіка генерації та завантаження звіту із використанням черг:
//Роут буде викликатися, коли клієнт хоче розпочати генерацію звіту: відправляє завдання в чергу і повертає job_id
Route::get('/popularity-report', 
    [ClientEventController::class, 'downloadPopularityReport']
)->name('popularity-report');

//Роут для завантаження файлу після його створення
Route::get('/download-popularity-report/{jobId}', 
    [ClientEventController::class, 'downloadReport']
)->name('download-popularity-report');

//-----------------------------------------------------
//Проста логіка (без черг) генерації та завантаження звіту:
//-----------------------------------------------------
// Route::get(
//     '/popularity-report', 
//     [ClientEventController::class, 'downloadPopularityReport']
// )->name('popularity-report');
//-----------------------------------------------------