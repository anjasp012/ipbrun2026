<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Enduser\TicketController as EnduserTicket;
use App\Http\Controllers\Admin\TicketController as AdminTicket;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\AdminController as AdminDashboard;
use App\Http\Controllers\Enduser\PaymentController;
use App\Http\Controllers\Enduser\TestController;
use App\Http\Controllers\Auth\AuthController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Enduser Routes
Route::get('/', [EnduserTicket::class, 'home']);
Route::get('/checkout/{ticket}', [EnduserTicket::class, 'checkout'])->name('checkout');
Route::post('/register', [EnduserTicket::class, 'register'])->name('register');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('/payment/{participant}', function (\App\Models\Participant $participant) {
    return view('pages.enduser.payment', compact('participant'));
})->name('payment.show');
Route::match(['GET', 'POST'], '/payments/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');

Route::middleware('auth')->group(function () {
    Route::get('/buy-more/{ticket}', [EnduserTicket::class, 'buyMore'])->name('participant.buy-more');
    Route::post('/buy-more/{ticket}/process', [EnduserTicket::class, 'buyMoreProcess'])->name('participant.buy-more.process');
});

// Utilities / Test
Route::get('/test-email', [TestController::class, 'emailForm']);
Route::post('/test-email', [TestController::class, 'sendEmail']);

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'dashboard']);
    Route::post('/toggle-running', [AdminDashboard::class, 'toggleRunning']);
    Route::get('/participants', [AdminDashboard::class, 'participants']);
    Route::get('/participants/{participant}', [AdminDashboard::class, 'participantShow']);
    Route::get('/participants/{participant}/resend-invoice', [AdminDashboard::class, 'resendInvoice'])->name('participants.resend-invoice');
    Route::get('/tickets', [AdminTicket::class, 'index']);
    Route::post('/tickets', [AdminTicket::class, 'store'])->name('tickets.store');
    Route::put('/tickets/{ticket}', [AdminTicket::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [AdminTicket::class, 'destroy'])->name('tickets.destroy');
    Route::post('/periods/{period}/toggle', [AdminTicket::class, 'togglePeriod'])->name('periods.toggle');

    Route::get('/categories', [AdminCategory::class, 'index']);
    Route::post('/categories', [AdminCategory::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AdminCategory::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategory::class, 'destroy'])->name('categories.destroy');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    Route::get('/blast', [\App\Http\Controllers\Admin\BlastController::class, 'index'])->name('admin.blast');
    Route::post('/blast/email', [\App\Http\Controllers\Admin\BlastController::class, 'blastEmail'])->name('admin.blast.email');
    Route::post('/blast/whatsapp', [\App\Http\Controllers\Admin\BlastController::class, 'blastWhatsapp'])->name('admin.blast.whatsapp');
});

Route::get('/test-tailwind', function () {
    return view('pages.enduser.test');
});
