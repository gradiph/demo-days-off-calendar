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
    'prefix' => 'v1',
], function () {
    Route::get('calendar', [CalendarController::class, 'index']);
});