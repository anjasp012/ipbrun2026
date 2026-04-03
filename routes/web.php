<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Enduser\TicketController as EnduserTicket;
use App\Http\Controllers\Admin\TicketController as AdminTicket;
use App\Http\Controllers\Admin\AdminController as AdminDashboard;
use App\Http\Controllers\Enduser\PaymentController;
use App\Http\Controllers\Enduser\TestController;

// Enduser Routes
Route::get('/', [EnduserTicket::class, 'home']);
Route::get('/checkout/{ticket}', [EnduserTicket::class, 'checkout'])->name('checkout');
Route::post('/register', [EnduserTicket::class, 'register'])->name('register');
Route::get('/payment/{participant}', function (\App\Models\Participant $participant) {
    return view('pages.enduser.payment', compact('participant'));
})->name('payment.show');
Route::post('/payments/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');

// Utilities / Test
Route::get('/test-email', [TestController::class, 'emailForm']);
Route::post('/test-email', [TestController::class, 'sendEmail']);

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'dashboard']);
    Route::post('/toggle-running', [AdminDashboard::class, 'toggleRunning']);
    Route::get('/participants', [AdminDashboard::class, 'participants']);
    Route::get('/participants/{participant}', [AdminDashboard::class, 'participantShow']);
    Route::get('/tickets', [AdminTicket::class, 'index']);
    Route::post('/periods/{period}/toggle', [AdminTicket::class, 'togglePeriod'])->name('periods.toggle');
});

Route::get('/test-tailwind', function () {
    return view('pages.enduser.test');
});
