<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $participants;
    protected $status;
    protected $selectedColumns;

    public function __construct($participants, $status = 'paid', $selectedColumns = [])
    {
        $this->participants = $participants;
        $this->status = $status ?: 'paid';
        
        // If empty, assume all columns (fallback)
        if (empty($selectedColumns)) {
            $this->selectedColumns = [
                'name', 'email', 'phone', 'nik', 'birth_date', 'sex', 'blood_type', 
                'jersey_size', 'nim_nrp', 'nationality', 'address', 'running_community',
                'medical_condition', 'shuttle_bus', 'best_time', 'previous_events',
                'emergency_name', 'emergency_phone', 'emergency_relationship', 
                'order_codes', 'order_statuses', 'ticket_details', 'paid_amount', 
                'donation_scholarship', 'donation_event', 'admin_fee', 'total_paid', 'created_at'
            ];
        } else {
            $this->selectedColumns = $selectedColumns;
        }
    }

    public function collection()
    {
        return $this->participants;
    }

    public function headings(): array
    {
        $allPossibleColumns = [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'nik' => 'NIK',
            'birth_date' => 'Birth Date',
            'sex' => 'Gender',
            'blood_type' => 'Blood Type',
            'jersey_size' => 'Jersey Size',
            'nim_nrp' => 'NIM/NRP',
            'nationality' => 'Nationality',
            'address' => 'Address',
            'running_community' => 'Running Community',
            'medical_condition' => 'Medical Condition',
            'shuttle_bus' => 'Shuttle Bus',
            'best_time' => 'Best Time',
            'previous_events' => 'Previous Events',
            'emergency_name' => 'Emergency Contact Name',
            'emergency_phone' => 'Emergency Contact Phone',
            'emergency_relationship' => 'Emergency Relationship',
            'order_codes' => 'Order Codes',
            'order_statuses' => 'Order Statuses',
            'ticket_details' => 'Ticket Details',
            'paid_amount' => 'Paid Amount',
            'donation_scholarship' => 'Donation Scholarship',
            'donation_event' => 'Donation Event',
            'admin_fee' => 'Admin Fee',
            'total_paid' => 'Total Paid Amount',
            'created_at' => 'First Registered At'
        ];

        $headers = [];
        foreach ($this->selectedColumns as $key) {
            if (isset($allPossibleColumns[$key])) {
                $headers[] = $allPossibleColumns[$key];
            }
        }

        return $headers;
    }

    public function map($p): array
    {
        // Pre-calculate common data
        $targetOrders = $p->raceEntries->filter(function($e) {
            return ($e->order->status ?? $e->status) === $this->status;
        })->map(fn($e) => $e->order)->unique('id')->filter();

        $totalPaid = $targetOrders->sum('total_price');
        $donationScholarship = $targetOrders->sum('donation_scholarship');
        $donationEvent = $targetOrders->sum('donation_event');
        $adminFee = $targetOrders->sum('admin_fee');
        $paidAmount = $totalPaid - $donationScholarship - $donationEvent - $adminFee;

        $rowData = [
            'name' => $p->name,
            'email' => $p->email,
            'phone' => $p->phone_number,
            'nik' => $p->nik,
            'birth_date' => $p->date_birth,
            'sex' => strtoupper($p->sex),
            'blood_type' => $p->blood_type,
            'jersey_size' => $p->jersey_size,
            'nim_nrp' => $p->nim_nrp ?: '-',
            'nationality' => $p->nationality ?? 'WNI',
            'address' => $p->address,
            'running_community' => $p->running_community ?: '-',
            'medical_condition' => $p->medical_condition ?: '-',
            'shuttle_bus' => $p->shuttle_bus ?: 'No',
            'best_time' => $p->best_time ?: '-',
            'previous_events' => $p->previous_events ?: '-',
            'emergency_name' => $p->emergency_contact_name,
            'emergency_phone' => $p->emergency_contact_phone_number,
            'emergency_relationship' => $p->emergency_contact_relationship,
            'order_codes' => $p->raceEntries->map(fn($e) => $e->order->order_code ?? '-')->unique()->filter()->implode(' | '),
            'order_statuses' => $p->raceEntries->map(fn($e) => strtoupper($e->order->status ?? ($e->status ?? 'unknown')))->unique()->implode(' | '),
            'ticket_details' => $p->raceEntries->map(function($e) {
                $cat = $e->ticket->category->name ?? '-';
                $type = strtoupper($e->ticket->type ?? '-');
                return "($type - $cat)";
            })->unique()->implode(' | '),
            'paid_amount' => $paidAmount,
            'donation_scholarship' => $donationScholarship,
            'donation_event' => $donationEvent,
            'admin_fee' => $adminFee,
            'total_paid' => $totalPaid,
            'created_at' => $p->created_at->format('Y-m-d H:i')
        ];

        $row = [];
        foreach ($this->selectedColumns as $key) {
            if (array_key_exists($key, $rowData)) {
                $row[] = $rowData[$key];
            }
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1    => ['font' => ['bold' => true]],
        ];
    }
}
