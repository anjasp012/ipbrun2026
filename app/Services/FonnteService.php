<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class FonnteService
{
    protected $token;
    protected $url = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = Setting::getValue('fonnte_token', config('services.fonnte.token'));
    }

    /**
     * Send message via Fonnte
     *
     * @param string $target Phone number
     * @param string $message Message body
     * @return array Response from Fonnte
     */
    public function sendMessage($target, $message)
    {
        $isActive = Setting::getValue('wa_notification_active', '0');
        
        if ($isActive !== '1') {
            Log::info('WhatsApp notification is disabled in settings. Message not sent.');
            return ['status' => false, 'reason' => 'whatsapp_disabled'];
        }

        if (empty($this->token)) {
            Log::warning('Fonnte token is not set. Message not sent.');
            return ['status' => false, 'reason' => 'token_missing'];
        }

        // Standardize phone number for Indonesian market (e.g. 0812 -> 62812)
        $target = $this->formatNumber($target);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post($this->url, [
                'target' => $target,
                'message' => $message,
                'delay' => '2', // Optional delay to prevent spam detection
            ]);

            Log::info('Fonnte message sent', [
                'target' => $target,
                'response' => $response->json(),
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Fonnte sending failed', [
                'target' => $target,
                'error' => $e->getMessage(),
            ]);
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    /**
     * Standardize phone number format
     */
    private function formatNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }

        return $number;
    }
}
