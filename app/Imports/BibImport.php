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
    protected $successCount = 0;
    protected $errors = [];

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception("File Excel kosong atau tidak memiliki data di bawah header.");
        }

        foreach ($rows as $index => $row) {
            $lineNum = $index + 2;

            $orderCode = isset($row['id']) ? trim((string)$row['id']) : null;
            $bibNumber = isset($row['bib']) ? trim((string)$row['bib']) : null;
            $nama = isset($row['nama']) ? trim((string)$row['nama']) : null;
            $ket = isset($row['ket']) ? trim((string)$row['ket']) : null;

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

            // Find race entries
            $entries = $order->raceEntries;
            if ($entries->isEmpty()) {
                $this->errors[] = "Baris $lineNum: Order '$orderCode' tidak memiliki tiket/race entry.";
                continue;
            }

            // Unique check for BIB number
            $existingBibEntry = RaceEntry::where('bib_number', $bibNumber)->first();

            if ($entries->count() === 1) {
                $entry = $entries->first();
                if ($existingBibEntry && $existingBibEntry->id !== $entry->id) {
                    $this->errors[] = "Baris $lineNum: BIB number '$bibNumber' sudah digunakan oleh peserta lain.";
                    continue;
                }
                $entry->update(['bib_number' => $bibNumber]);
                $this->successCount++;
            } else {
                // Match by category/ticket name in KET
                $matched = false;
                foreach ($entries as $entry) {
                    $categoryName = strtolower($entry->ticket->category->name ?? '');
                    $ketVal = strtolower($ket ?? '');
                    if ($categoryName && $ketVal && (str_contains($ketVal, $categoryName) || str_contains($categoryName, $ketVal))) {
                        if ($existingBibEntry && $existingBibEntry->id !== $entry->id) {
                            $this->errors[] = "Baris $lineNum: BIB number '$bibNumber' sudah digunakan oleh peserta lain.";
                            $matched = true;
                            break;
                        }
                        $entry->update(['bib_number' => $bibNumber]);
                        $this->successCount++;
                        $matched = true;
                        break;
                    }
                }

                if (!$matched) {
                    // Fallback to first race entry that has no bib_number
                    $entry = $entries->whereNull('bib_number')->first() ?? $entries->first();
                    if ($entry) {
                        if ($existingBibEntry && $existingBibEntry->id !== $entry->id) {
                            $this->errors[] = "Baris $lineNum: BIB number '$bibNumber' sudah digunakan oleh peserta lain.";
                            continue;
                        }
                        $entry->update(['bib_number' => $bibNumber]);
                        $this->successCount++;
                    }
                }
            }
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
