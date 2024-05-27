<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ReplyController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Middleware\SimulateAuth;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::post('tickets', [TicketController::class, 'store']);


Route::middleware(['simulateAuth'])->group(function () {
    Route::get('tickets', [TicketController::class, 'index']);
    Route::post('tickets', [TicketController::class, 'store']);
    Route::get('tickets/{ticket}', [TicketController::class, 'show']);
    Route::put('tickets/{ticket}', [TicketController::class, 'update']);
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy']);
    Route::post('/tickets/{ticket}/replies', [ReplyController::class, 'store']);
    Route::get('/tickets/{ticket}/activities', [ActivityController::class, 'index']);

    Route::put('tickets/{ticket}/status', [TicketController::class, 'updateTicket']);
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'replyToTicket']);
});


Route::get('/test-middleware', function () {
    return 'Middleware test route';
})->middleware('simulateAuth');

    
