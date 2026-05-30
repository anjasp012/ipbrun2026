<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Enduser\TicketController as EnduserTicket;
use App\Http\Controllers\Admin\TicketController as AdminTicket;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\AdminController as AdminDashboard;
use App\Http\Controllers\Admin\ScanController;
use App\Http\Controllers\Enduser\PaymentController;
use App\Http\Controllers\Enduser\TestController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Enduser\ToolController;
use App\Http\Controllers\Enduser\SponsorshipController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Enduser Routes
Route::get('/', [EnduserTicket::class, 'home']);
Route::get('/check-order', [EnduserTicket::class, 'checkOrder'])->name('check.order');
// Route::get('/start', [ToolController::class, 'startPage'])->name('start.tool');
Route::post('/trigger-start', [ToolController::class, 'triggerStart'])->name('trigger.start');
Route::get('/checkout/{ticket}', [EnduserTicket::class, 'checkout'])->name('checkout');
Route::post('/check-voucher', [\App\Http\Controllers\Enduser\VoucherController::class, 'check'])->name('voucher.check');
Route::post('/register', [EnduserTicket::class, 'register'])->name('register');
Route::get('/faq', function () {
    return view('pages.enduser.faq');
})->name('faq');
Route::get('/surat-kuasa', function () {
    return view('pages.enduser.surat_kuasa');
})->name('surat.kuasa');
Route::get('/rules', function () {
    return view('pages.enduser.rules');
})->name('rules');
Route::get('/dashboard', [EnduserTicket::class, 'dashboard'])->name('participant.dashboard')->middleware('auth');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

// Sponsorship Routes
Route::get('/sponsorship', [SponsorshipController::class, 'index'])->name('sponsorship.index');
Route::get('/sponsorship/checkout/{ticket}', [SponsorshipController::class, 'checkout'])->name('sponsorship.checkout');
Route::post('/sponsorship/register', [SponsorshipController::class, 'register'])->name('sponsorship.register');

// Komunitas Flow
Route::prefix('komunitas')->group(function () {
    Route::get('/', [\App\Http\Controllers\Enduser\CommunityTicketController::class, 'home'])->name('komunitas.home');
    Route::get('/checkout/{ticket}', [\App\Http\Controllers\Enduser\CommunityTicketController::class, 'checkout'])->name('komunitas.checkout');
    Route::post('/check-voucher', [\App\Http\Controllers\Enduser\CommunityTicketController::class, 'checkVoucher'])->name('komunitas.check-voucher');
    Route::post('/register', [\App\Http\Controllers\Enduser\CommunityTicketController::class, 'register'])->name('komunitas.register');
});

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
Route::redirect('admin', 'admin/dashboard');
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Shared Routes (Superadmin, Admin, PIC)
    Route::middleware(['role:superadmin,admin,pic'])->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'dashboard']);
        Route::get('/participants', [AdminDashboard::class, 'participants']);

        // QR Scan Race Pack
        Route::get('/scan-rpc', [ScanController::class, 'index'])->name('admin.scan-rpc');
        Route::post('/scan-rpc/check', [ScanController::class, 'check'])->name('admin.scan-rpc.check');
        Route::post('/scan-rpc/process', [ScanController::class, 'process'])->name('admin.scan-rpc.process');
        
        // Blast Email RPC
        Route::get('/scan-rpc/blast', [ScanController::class, 'blastForm'])->name('admin.scan-rpc.blast');
        Route::post('/scan-rpc/blast', [ScanController::class, 'sendBlast'])->name('admin.scan-rpc.blast.send');
    });

    // Superadmin Only Routes
    Route::middleware(['role:superadmin'])->group(function () {
        Route::post('/toggle-running', [AdminDashboard::class, 'toggleRunning']);
        Route::put('/participants/{participant}/change-password', [AdminDashboard::class, 'changePassword'])->name('participants.change-password');
        Route::post('/scan-rpc/{raceEntry}/reset', [ScanController::class, 'reset'])->name('admin.scan-rpc.reset');

        Route::get('/tickets', [AdminTicket::class, 'index']);
        Route::post('/tickets', [AdminTicket::class, 'store'])->name('tickets.store');
        Route::put('/tickets/{ticket}', [AdminTicket::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{ticket}', [AdminTicket::class, 'destroy'])->name('tickets.destroy');
        Route::post('/periods/{period}/toggle', [AdminTicket::class, 'togglePeriod'])->name('periods.toggle');
        Route::post('/periods/{period}/toggle-sold-out', [AdminTicket::class, 'toggleSoldOut'])->name('periods.toggle-sold-out');

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

    // Superadmin & Admin Only (PIC Restricted)
    Route::middleware(['role:superadmin,admin'])->group(function () {
        Route::get('/participants/export', [AdminDashboard::class, 'exportParticipants'])->name('participants.export');
        Route::get('/participants/import/template', [AdminDashboard::class, 'importTemplate'])->name('participants.import-template');
        Route::post('/participants/import', [AdminDashboard::class, 'importParticipants'])->name('participants.import');
        Route::get('/participants/{participant}', [AdminDashboard::class, 'participantShow']);
        Route::post('/participants/{participant}/add-ticket', [AdminDashboard::class, 'addTicket'])->name('participants.add-ticket');
        Route::get('/participants/{participant}/resend-invoice', [AdminDashboard::class, 'resendInvoice'])->name('participants.resend-invoice');
        Route::put('/participants/{participant}', [AdminDashboard::class, 'participantUpdate'])->name('participants.update');
        Route::delete('/participants/{participant}/cancel', [AdminDashboard::class, 'cancelParticipant'])->name('participants.cancel');
        Route::post('/participants/bulk-cancel', [AdminDashboard::class, 'bulkCancelParticipants'])->name('participants.bulk-cancel');
        Route::post('/participants/bulk-resend', [AdminDashboard::class, 'bulkResendInvoice'])->name('participants.bulk-resend');
      
        
        Route::get('/vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('admin.vouchers.index');
        Route::post('/vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('admin.vouchers.store');
        Route::delete('/vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
        Route::patch('/vouchers/{voucher}/toggle', [\App\Http\Controllers\Admin\VoucherController::class, 'toggleActive'])->name('admin.vouchers.toggle');
    });
});

Route::get('/test-tailwind', function () {
    return view('pages.enduser.test');
});
