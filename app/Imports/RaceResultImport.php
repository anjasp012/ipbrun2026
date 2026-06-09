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
    private string $tabName;

    public function __construct(string $tabName)
    {
        $this->tabName = strtoupper(trim($tabName));
    }

    public function model(array $row)
    {
        // Normalize header keys (handle various naming)
        $item      = $row['item'] ?? null;
        $bib       = $row['bib'] ?? null;
        $name      = $row['name'] ?? null;
        $genderVal = $row['gender'] ?? $row['sex'] ?? $row['jenis_kelamin'] ?? $row['jenis_kelamin'] ?? $row['jenis kelamin'] ?? $row['jk'] ?? $row['gender_l_p'] ?? null;
        $gender    = ($genderVal !== null && trim((string) $genderVal) !== '') ? trim((string) $genderVal) : null;

        // Standardize gender values to uppercase MALE/FEMALE
        if ($gender !== null) {
            $gClean = strtoupper($gender);
            if ($gClean === 'M' || $gClean === 'L' || $gClean === 'MALE' || $gClean === 'LAKI-LAKI' || $gClean === 'LAKI') {
                $gender = 'MALE';
            } elseif ($gClean === 'F' || $gClean === 'P' || $gClean === 'FEMALE' || $gClean === 'PEREMPUAN' || $gClean === 'WANITA') {
                $gender = 'FEMALE';
            } else {
                $gender = $gClean;
            }
        }

        // Smart Fallback: Parse from item/category name if gender is empty
        if ($gender === null && $item !== null) {
            $itemLower = strtolower((string) $item);
            if (str_contains($itemLower, 'female') || str_contains($itemLower, 'wanita') || str_contains($itemLower, 'perempuan') || str_contains($itemLower, ' w ')) {
                $gender = 'FEMALE';
            } elseif (str_contains($itemLower, 'male') || str_contains($itemLower, 'pria') || str_contains($itemLower, 'laki') || str_contains($itemLower, ' m ')) {
                $gender = 'MALE';
            }
        }

        $gunTime   = $row['gun_time'] ?? $row['gun time'] ?? null;
        $netTime   = $row['net_time'] ?? $row['net time'] ?? null;
        $startTime = $row['start_time'] ?? $row['start time'] ?? null;
        $cp1       = $row['cp1'] ?? null;
        $cp2       = $row['cp2'] ?? null;
        $status    = $row['status'] ?? null;

        // Clean and uppercase values
        $bibStr  = $bib !== null ? trim((string) $bib) : null;
        $itemStr = $item !== null ? strtoupper(trim((string) $item)) : null;
        $nameStr = $name !== null ? strtoupper(trim((string) $name)) : null;

        $gunTimeStr   = $this->formatTimeValue($gunTime);
        $netTimeStr   = $this->formatTimeValue($netTime);
        $startTimeStr = $this->formatTimeValue($startTime);
        $cp1Str       = $this->formatTimeValue($cp1);
        $cp2Str       = $this->formatTimeValue($cp2);

        if ($bibStr !== null && $bibStr !== '') {
            // Upsert: update if bib+tab exists, else create
            RaceResult::updateOrCreate(
                ['bib' => $bibStr, 'tab' => $this->tabName],
                [
                    'item'       => $itemStr,
                    'name'       => $nameStr,
                    'gender'     => $gender !== null ? strtoupper(trim((string) $gender)) : null,
                    'gun_time'   => $gunTimeStr,
                    'net_time'   => $netTimeStr,
                    'start_time' => $startTimeStr,
                    'cp1'        => $cp1Str,
                    'cp2'        => $cp2Str,
                    'status'     => $status !== null ? strtoupper(trim((string) $status)) : null,
                ]
            );
        } else {
            // If bib is missing, we create a new record since we can't safely upsert
            RaceResult::create([
                'bib'        => $bibStr,
                'tab'        => $this->tabName,
                'item'       => $itemStr,
                'name'       => $nameStr,
                'gender'     => $gender !== null ? strtoupper(trim((string) $gender)) : null,
                'gun_time'   => $gunTimeStr,
                'net_time'   => $netTimeStr,
                'start_time' => $startTimeStr,
                'cp1'        => $cp1Str,
                'cp2'        => $cp2Str,
                'status'     => $status !== null ? strtoupper(trim((string) $status)) : null,
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
