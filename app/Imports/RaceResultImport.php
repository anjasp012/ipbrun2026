<?php

namespace App\Imports;

use App\Models\RaceResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RaceResultImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private int $successCount = 0;
    private array $errors = [];

    public function model(array $row)
    {
        // Normalize header keys (handle various naming)
        $item      = $row['item'] ?? null;
        $bib       = $row['bib'] ?? null;
        $name      = $row['name'] ?? null;
        $gender    = $row['gender'] ?? null;
        $gunTime   = $row['gun_time'] ?? $row['gun time'] ?? null;
        $netTime   = $row['net_time'] ?? $row['net time'] ?? null;
        $startTime = $row['start_time'] ?? $row['start time'] ?? null;
        $cp1       = $row['cp1'] ?? null;
        $cp2       = $row['cp2'] ?? null;
        $status    = $row['status'] ?? null;

        // Clean values
        $bibStr  = $bib !== null ? trim((string) $bib) : null;
        $itemStr = $item !== null ? trim((string) $item) : null;
        $nameStr = $name !== null ? trim((string) $name) : null;

        if ($bibStr !== null && $bibStr !== '' && $itemStr !== null && $itemStr !== '') {
            // Upsert: update if bib+item exists, else create
            RaceResult::updateOrCreate(
                ['bib' => $bibStr, 'item' => $itemStr],
                [
                    'name'       => $nameStr,
                    'gender'     => $gender !== null ? trim((string) $gender) : null,
                    'gun_time'   => $gunTime !== null ? trim((string) $gunTime) : null,
                    'net_time'   => $netTime !== null ? trim((string) $netTime) : null,
                    'start_time' => $startTime !== null ? trim((string) $startTime) : null,
                    'cp1'        => $cp1 !== null ? trim((string) $cp1) : null,
                    'cp2'        => $cp2 !== null ? trim((string) $cp2) : null,
                    'status'     => $status !== null ? trim((string) $status) : null,
                ]
            );
        } else {
            // If bib or item is missing, we create a new record since we can't safely upsert
            RaceResult::create([
                'bib'        => $bibStr,
                'item'       => $itemStr,
                'name'       => $nameStr,
                'gender'     => $gender !== null ? trim((string) $gender) : null,
                'gun_time'   => $gunTime !== null ? trim((string) $gunTime) : null,
                'net_time'   => $netTime !== null ? trim((string) $netTime) : null,
                'start_time' => $startTime !== null ? trim((string) $startTime) : null,
                'cp1'        => $cp1 !== null ? trim((string) $cp1) : null,
                'cp2'        => $cp2 !== null ? trim((string) $cp2) : null,
                'status'     => $status !== null ? trim((string) $status) : null,
            ]);
        }

        $this->successCount++;

        return null; // We save database records manually
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
