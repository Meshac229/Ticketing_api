<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderIntentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTypeController;
use Illuminate\Support\Facades\Route;

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
Route::get('/events', [EventController::class, 'index']);
Route::apiResource('events', EventController::class);
Route::apiResource('ticket-types', TicketTypeController::class);
Route::apiResource('order-intents', OrderIntentController::class);
Route::get('/client/{apiRequestId}/orders', [OrderController::class, 'getOrderByUser']);

Route::apiResource('orders', OrderController::class);

Route::get('orders/number/{orderNumber}', [OrderController::class, 'getByOrderNumber']);

Route::get('/user/orders', [OrderController::class, 'getUserOrders']);

Route::get('events/{eventId}/ticket-types', [TicketTypeController::class, 'getTicketTypesByEvent']);

Route::post('/validate-order-intent/{orderIntentId}', [OrderIntentController::class, 'validateOrderIntent']);

Route::get('/download-tickets/{orderId}', [TicketController::class, 'downloadTickets']);

Route::get('/api-request-success', function () {
    return view('emails.success');
})->name('emails.success');

Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::get('/api/documentation', function () {
        return view('swagger.index');
    });
});
