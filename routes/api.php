<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ReplyController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\UserController;
// use App\Http\Middleware\SimulateAuth;


// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();

    Route::post('users', [UserController::class, 'register']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::get('tickets', [TicketController::class, 'index']);
    Route::post('tickets', [TicketController::class, 'store']);
    Route::get('tickets/{ticket}', [TicketController::class, 'show']);
    Route::put('tickets/{ticket}', [TicketController::class, 'update']);
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy']);
    Route::post('/tickets/{ticket}/replies', [ReplyController::class, 'store']);

    Route::get('tickets/{ticket}/activities', [ActivityController::class, 'getTicketActivities']);
    Route::get('companies/{company}/activities', [ActivityController::class, 'getCompanyActivities']);

    Route::put('tickets/{ticket}/status', [TicketController::class, 'updateTicket']);
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'replyToTicket']);


    Route::get('companies', [CompaniesController::class, 'index']);
    Route::post('companies', [CompaniesController::class, 'store']);
    Route::get('companies/{company}', [CompaniesController::class, 'show']);
    Route::put('companies/{company}', [CompaniesController::class, 'update']);
    Route::delete('companies/{company}', [CompaniesController::class, 'destroy']);
    
// });




Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Route::middleware(['simulateAuth'])->group(function () {
    
    
// });


Route::get('/test-middleware', function () {
    return 'Middleware test route';
})->middleware('simulateAuth');

    
