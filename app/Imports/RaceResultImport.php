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
        $genderVal = $row['gender'] ?? $row['sex'] ?? $row['jenis_kelamin'] ?? $row['jenis kelamin'] ?? $row['jk'] ?? $row['gender_l_p'] ?? null;
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

        // Named checkpoint columns — try multiple possible header variations
        // Laravel Excel normalizes headers: spaces→underscore, comma/dot stripped or → underscore
        // "3KM" → "3km", "8,9KM" → "8_9km" or "89km", "6.4KM" → "6_4km" or "64km"
        $cp3km    = $row['3km']    ?? $row['cp_3km']    ?? $row['3_km']    ?? null;
        $cp6_4km  = $row['6_4km']  ?? $row['cp_6_4km']  ?? $row['64km']    ?? $row['6km']    ?? null;
        $cp8_9km  = $row['8_9km']  ?? $row['cp_8_9km']  ?? $row['89km']    ?? $row['8km']    ?? null;
        $cp10km   = $row['10km']   ?? $row['cp_10km']   ?? $row['10_km']   ?? null;
        $cp16_1km = $row['16_1km'] ?? $row['cp_16_1km'] ?? $row['161km']   ?? $row['16km']   ?? null;
        $cp19km   = $row['19km']   ?? $row['cp_19km']   ?? $row['19_km']   ?? null;
        $cp26_1km = $row['26_1km'] ?? $row['cp_26_1km'] ?? $row['261km']   ?? $row['26km']   ?? null;
        $cp29km   = $row['29km']   ?? $row['cp_29km']   ?? $row['29_km']   ?? null;
        $cp36km   = $row['36km']   ?? $row['cp_36km']   ?? $row['36_km']   ?? null;
        $cp38_5km = $row['38_5km'] ?? $row['cp_38_5km'] ?? $row['385km']   ?? $row['38km']   ?? null;

        // Clean and uppercase values
        $bibStr  = $bib !== null ? trim((string) $bib) : null;
        $itemStr = $item !== null ? strtoupper(trim((string) $item)) : null;
        $nameStr = $name !== null ? strtoupper(trim((string) $name)) : null;

        $gunTimeStr   = $this->formatTimeValue($gunTime);
        $netTimeStr   = $this->formatTimeValue($netTime);
        $startTimeStr = $this->formatTimeValue($startTime);
        $cp1Str       = $this->formatTimeValue($cp1);
        $cp2Str       = $this->formatTimeValue($cp2);
        $cp3kmStr     = $this->formatTimeValue($cp3km);
        $cp6_4kmStr   = $this->formatTimeValue($cp6_4km);
        $cp8_9kmStr   = $this->formatTimeValue($cp8_9km);
        $cp10kmStr    = $this->formatTimeValue($cp10km);
        $cp16_1kmStr  = $this->formatTimeValue($cp16_1km);
        $cp19kmStr    = $this->formatTimeValue($cp19km);
        $cp26_1kmStr  = $this->formatTimeValue($cp26_1km);
        $cp29kmStr    = $this->formatTimeValue($cp29km);
        $cp36kmStr    = $this->formatTimeValue($cp36km);
        $cp38_5kmStr  = $this->formatTimeValue($cp38_5km);

        $data = [
            'item'       => $itemStr,
            'name'       => $nameStr,
            'gender'     => $gender !== null ? strtoupper(trim((string) $gender)) : null,
            'gun_time'   => $gunTimeStr,
            'net_time'   => $netTimeStr,
            'start_time' => $startTimeStr,
            'cp1'        => $cp1Str,
            'cp2'        => $cp2Str,
            'cp_3km'     => $cp3kmStr,
            'cp_6_4km'   => $cp6_4kmStr,
            'cp_8_9km'   => $cp8_9kmStr,
            'cp_10km'    => $cp10kmStr,
            'cp_16_1km'  => $cp16_1kmStr,
            'cp_19km'    => $cp19kmStr,
            'cp_26_1km'  => $cp26_1kmStr,
            'cp_29km'    => $cp29kmStr,
            'cp_36km'    => $cp36kmStr,
            'cp_38_5km'  => $cp38_5kmStr,
            'status'     => $status !== null ? strtoupper(trim((string) $status)) : null,
        ];

        if ($bibStr !== null && $bibStr !== '') {
            // Upsert: update if bib+tab exists, else create
            RaceResult::updateOrCreate(
                ['bib' => $bibStr, 'tab' => $this->tabName],
                $data
            );
        } else {
            // If bib is missing, create a new record
            RaceResult::create(array_merge($data, ['bib' => $bibStr, 'tab' => $this->tabName]));
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
