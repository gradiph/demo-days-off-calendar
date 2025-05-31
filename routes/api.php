<?php

use App\Http\Controllers\API\CalendarController;
use App\Http\Controllers\API\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', LoginController::class);

Route::group([
    'middleware' => ['auth:sanctum'],
    'prefix' => '/v1',
], function () {
    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::post('/calendar', [CalendarController::class, 'store']);
    Route::get('/calendar/{user}', [CalendarController::class, 'byUser'])
        ->where('user', '[0-9]+');
    Route::get('/calendar/{date}', [CalendarController::class, 'byDate'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
});