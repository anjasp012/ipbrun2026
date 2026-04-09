<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batalkan pesanan yang statusnya pending lebih dari 10 menit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan pesanan expired...');

        // Ambil order yang pending dan dibuat lebih dari 10 menit yang lalu
        $expiredOrders = Order::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(10))
            ->with('raceEntries')
            ->get();

        $count = $expiredOrders->count();

        if ($count === 0) {
            $this->info('Tidak ada pesanan yang expired.');
            return;
        }

        foreach ($expiredOrders as $order) {
            // Update status Order
            $order->update(['status' => 'failed']);

            // Update status semua RaceEntry terkait agar stok tiket kembali tersedia
            foreach ($order->raceEntries as $entry) {
                $entry->update(['status' => 'failed']);
            }

            $name = $order->participant->name ?? 'Unknown';
            $this->line("Batal: {$order->order_code} ({$name})");
        }

        $this->info("Berhasil membatalkan {$count} pesanan yang expired.");
    }
}
