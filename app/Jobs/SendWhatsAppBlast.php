<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\FonnteService;

class SendWhatsAppBlast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recipient;
    public $messageStr;

    /**
     * Create a new job instance.
     */
    public function __construct($recipient, $messageStr)
    {
        $this->recipient = $recipient;
        $this->messageStr = $messageStr;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fonnte = new FonnteService();
        $fonnte->sendMessage($this->recipient, $this->messageStr);
    }
}
