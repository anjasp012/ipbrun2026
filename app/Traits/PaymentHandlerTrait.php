<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ParticipantPaidNotification;

trait PaymentHandlerTrait
{
    protected function handleSuccessPayment($order, $participant)
    {
        // Don't repeat if already paid
        if ($order->status === 'paid') {
            return;
        }

        // 1. Update Order & Race Entry Status
        $order->update(['status' => 'paid']);
        foreach ($order->raceEntries as $entry) {
            $entry->update(['status' => 'paid']);
        }

        // 2. Determine if this is a Sponsorship Order
        $isSponsorship = str_starts_with($order->order_code, 'IPBR26-SP-');

        // 3. Create or Get User Account & Determine Credentials
        $isCommunity = $participant->is_community;
        $user = null;
        $userExists = false;
        $password = "";
        $loginIdentifier = "";

        if ($isSponsorship) {
            // For Sponsorship: Use NIK as Username and Password (like Community)
            $loginIdentifier = $participant->nik;
            $password = $participant->nik;
            
            // Check if user with this NIK already exists
            $user = User::where('username', $participant->nik)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'username' => $participant->nik,
                    'password' => Hash::make($password),
                    'role' => 'participant'
                ]);
                $userExists = false;
            } else {
                // If user exists, update their data and password
                $user->update([
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'password' => Hash::make($password),
                ]);
                $userExists = true;
            }
        } elseif ($isCommunity) {
            $loginIdentifier = $participant->nik;
            $userExists = User::where('username', $participant->nik)->exists();
            $password = $participant->nik; // Credential for community is always NIK

            $user = User::updateOrCreate(
                ['username' => $participant->nik],
                [
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'password' => Hash::make($password),
                    'role' => 'participant'
                ]
            );
        } else {
            $loginIdentifier = $participant->email;
            $userExists = User::where('email', $participant->email)->exists();
            $password = Str::random(8);

            $user = User::firstOrCreate(
                ['email' => $participant->email],
                [
                    'name' => $participant->name,
                    'username' => $participant->email,
                    'password' => Hash::make($password),
                    'role' => 'participant'
                ]
            );
        }

        // 4. Link Participant to User
        $participant->update(['user_id' => $user->id]);

        // 5. Send Email Notification
        try {
            \App\Jobs\SendQueuedEmail::dispatch($participant->email, new ParticipantPaidNotification($participant, $password, $order, $userExists));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email Queuing Failed: ' . $e->getMessage());
        }

        // 6. Send WhatsApp Notification
        try {
            $url = "https://dev.ipbrun2026.id/login";
            
            if ($isSponsorship) {
                $message = "📢 *Konfirmasi Pendaftaran Sponsorship – IPB Run 2026*\n\n" .
                    "Halo *{$participant->name}*,\n\n" .
                    "Pendaftaran sponsorship kamu dengan kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                    "*Selamat! Kamu resmi terdaftar sebagai peserta IPB Run 2026* 🏁\n\n" .
                    "🔐 Gunakan NIK kamu untuk akses dashboard:\n" .
                    "Username: *{$participant->nik}*\n" .
                    "Password: *{$participant->nik}*\n" .
                    "URL: {$url}\n\n" .
                    "Sampai jumpa di garis start! 🏃‍♂️🔥";
            } elseif ($isCommunity) {
                if ($userExists) {
                    $message = "📢 *Konfirmasi Pendaftaran Komunitas – IPB Run 2026*\n\n" .
                        "Halo *{$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Tiket komunitas kamu resmi terdaftar di IPB Run 2026* 🏁\n\n" .
                        "Silakan cek detail tiket kamu di dashboard menggunakan akun NIK yang sudah ada:\n" .
                        "URL: {$url}\n\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                } else {
                    $message = "📢 *Konfirmasi Pendaftaran Komunitas – IPB Run 2026*\n\n" .
                        "Halo *{$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Kamu resmi terdaftar sebagai peserta IPB Run 2026* 🏁\n\n" .
                        "🔐 Gunakan NIK kamu untuk akses dashboard:\n" .
                        "Username: *{$participant->nik}*\n" .
                        "Password: *{$participant->nik}*\n" .
                        "URL: {$url}\n\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                }
            } else {
                // Regular Flow
                if ($userExists) {
                    $message = "📢 *Konfirmasi Pembayaran Tambahan – IPB Run 2026*\n\n" .
                        "Halo *{$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Tiket tambahan kamu resmi terdaftar di IPB Run 2026* 🏁\n\n" .
                        "Silakan cek detail tiket baru kamu di dashboard:\n" .
                        "URL: {$url}\n\n" .
                        "Terima kasih atas partisipasinya,\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                } else {
                    $message = "📢 *Konfirmasi Pendaftaran – IPB Run 2026*\n\n" .
                        "*Halo {$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Kamu resmi menjadi peserta IPB Run 2026* 🏁\n\n" .
                        "🔐 Berikut akses dashboard kamu:\n" .
                        "Email: *{$participant->email}*\n" .
                        "Password: *{$password}*\n" .
                        "URL: {$url}\n\n" .
                        "Terima kasih atas partisipasinya,\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                }
            }
            \App\Jobs\SendWhatsAppBlast::dispatch($participant->phone_number, $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte success notification failed: ' . $e->getMessage());
        }
    }
}
