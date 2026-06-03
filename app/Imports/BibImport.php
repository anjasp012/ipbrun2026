<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\RaceEntry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BibImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $ticketId;
    protected $successCount = 0;
    protected $errors = [];

    public function __construct($ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception("File Excel kosong atau tidak memiliki data di bawah header.");
        }

        foreach ($rows as $index => $row) {
            $lineNum = $index + 2;

            $orderCode = isset($row['id']) ? trim((string)$row['id']) : null;
            $bibNumber = isset($row['bib']) ? trim((string)$row['bib']) : null;

            if (empty($orderCode)) {
                $this->errors[] = "Baris $lineNum: Kolom ID (Order Code) kosong.";
                continue;
            }

            if (empty($bibNumber)) {
                $this->errors[] = "Baris $lineNum: Kolom BIB kosong untuk Order Code '$orderCode'.";
                continue;
            }

            // Find order
            $order = Order::where('order_code', $orderCode)->first();
            if (!$order) {
                $this->errors[] = "Baris $lineNum: Order dengan ID '$orderCode' tidak ditemukan di database.";
                continue;
            }

            // Find race entry for the selected ticket
            $entry = $order->raceEntries()->where('ticket_id', $this->ticketId)->first();
            if (!$entry) {
                $this->errors[] = "Baris $lineNum: Order '$orderCode' tidak memiliki tiket/kategori yang dipilih.";
                continue;
            }

            // Unique check for BIB number
            $existingBibEntry = RaceEntry::where('bib_number', $bibNumber)->first();
            if ($existingBibEntry && $existingBibEntry->id !== $entry->id) {
                $this->errors[] = "Baris $lineNum: BIB number '$bibNumber' sudah digunakan oleh peserta lain.";
                continue;
            }

            $entry->update(['bib_number' => $bibNumber]);
            $this->successCount++;
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
