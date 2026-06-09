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

        $gunTimeStr   = $this->formatTimeValue($gunTime);
        $netTimeStr   = $this->formatTimeValue($netTime);
        $startTimeStr = $this->formatTimeValue($startTime);
        $cp1Str       = $this->formatTimeValue($cp1);
        $cp2Str       = $this->formatTimeValue($cp2);

        if ($bibStr !== null && $bibStr !== '' && $itemStr !== null && $itemStr !== '') {
            // Upsert: update if bib+item exists, else create
            RaceResult::updateOrCreate(
                ['bib' => $bibStr, 'item' => $itemStr],
                [
                    'name'       => $nameStr,
                    'gender'     => $gender !== null ? trim((string) $gender) : null,
                    'gun_time'   => $gunTimeStr,
                    'net_time'   => $netTimeStr,
                    'start_time' => $startTimeStr,
                    'cp1'        => $cp1Str,
                    'cp2'        => $cp2Str,
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
                'gun_time'   => $gunTimeStr,
                'net_time'   => $netTimeStr,
                'start_time' => $startTimeStr,
                'cp1'        => $cp1Str,
                'cp2'        => $cp2Str,
                'status'     => $status !== null ? trim((string) $status) : null,
            ]);
        }

        $this->successCount++;

        return null; // We save database records manually
    }

    private function formatTimeValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // If it's numeric (Excel internal representation of time/date)
        if (is_numeric($value)) {
            try {
                // excelToDateTimeObject converts fraction/float into a DateTime object
                $dateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return $dateTime->format('H:i:s');
            } catch (\Exception $e) {
                return (string) $value;
            }
        }

        return trim((string) $value);
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
