<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantImportTemplate implements FromArray, WithHeadings, WithColumnFormatting, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return ['Name', 'NIK', 'Phone Number', 'Jersey Size', 'Race Category'];
    }

    public function array(): array
    {
        return [
            ['John Doe', '3201320012345678', '08123456789', 'L', '10K'],
        ];
    }

    /**
     * Force NIK column (B) to Text so Excel doesn't convert to scientific notation.
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * Style the header row and example data row.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row: bold, dark blue bg, white text
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF003366']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            // Example row: light blue background as hint
            2 => [
                'font' => ['italic' => true, 'color' => ['argb' => 'FF555555']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE8F0FE']],
            ],
        ];
    }
}
