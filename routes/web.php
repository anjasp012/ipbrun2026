<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;

Route::get('/', [TicketController::class, 'home']);
Route::get('/checkout/{ticket}', [TicketController::class, 'checkout'])->name('checkout');
Route::post('/register', [TicketController::class, 'register'])->name('register');
Route::get('/payment/{participant}', function (\App\Models\Participant $participant) {
    return view('pages.enduser.payment', compact('participant'));
})->name('payment.show');
Route::post('/payments/midtrans-callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('midtrans.callback');

Route::get('/test-email', [\App\Http\Controllers\TestController::class, 'emailForm']);
Route::post('/test-email', [\App\Http\Controllers\TestController::class, 'sendEmail']);

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);
    Route::post('/toggle-running', [\App\Http\Controllers\AdminController::class, 'toggleRunning']);
    Route::get('/participants', [\App\Http\Controllers\AdminController::class, 'participants']);
    Route::get('/participants/{participant}', [\App\Http\Controllers\AdminController::class, 'participantShow']);
    Route::get('/tickets', [TicketController::class, 'index']);
});

Route::get('/test-tailwind', function () {
    return view('pages.enduser.test');
});
