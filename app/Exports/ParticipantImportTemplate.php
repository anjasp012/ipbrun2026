<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ParticipantImportTemplate implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return ['Name', 'NIK', 'Phone Number', 'Jersey Size', 'Race Category', 'Blood Type'];
    }

    public function array(): array
    {
        // Return empty — NIK will be written manually as string in registerEvents()
        return [];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF003366']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            // Example row
            2 => [
                'font' => ['italic' => true, 'color' => ['argb' => 'FF555555']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE8F0FE']],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Format ENTIRE column B (NIK) and C (Phone) as Text
                $sheet->getStyle('B2:B1000')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);

                $sheet->getStyle('C2:C1000')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);

                // 2. Write example row — NIK written with ' prefix to force text in Excel
                $sheet->setCellValueExplicit('A2', 'John Doe', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('B2', "'3201320012345678", DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C2', "'08123456789", DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D2', 'L', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E2', '10K', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F2', 'O', DataType::TYPE_STRING);

                // 3. Add comment on B1 header
                $comment = $sheet->getComment('B1');
                $comment->getText()->createTextRun("PENTING: Tambahkan tanda petik (') di depan NIK saat mengisi.\nContoh: '3201320012345678\nIni mencegah Excel mengubah NIK menjadi scientific notation.");
            },
        ];
    }
}
