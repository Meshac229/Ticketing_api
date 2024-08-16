<?php

use App\Http\Controllers\ApiRequestController;
use Illuminate\Support\Facades\Route;

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
    return view('api_request_form');
});

Route::get('/api/request', [ApiRequestController::class, 'showForm']);
Route::post('/api/request', [ApiRequestController::class, 'handleRequest'])->name('api.request');
