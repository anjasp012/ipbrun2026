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

    public function __construct($participants, $status = 'paid')
    {
        $this->participants = $participants;
        $this->status = $status ?: 'paid';
    }

    public function collection()
    {
        return $this->participants;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'NIK',
            'Birth Date',
            'Gender',
            'Blood Type',
            'Jersey Size',
            'NIM/NRP',
            'Emergency Contact Name',
            'Emergency Contact Phone',
            'Emergency Relationship',
            'Order Codes',
            'Order Statuses',
            'Ticket Details',
            'Paid Amount',
            'Donation Scholarship',
            'Donation Event',
            'Admin Fee',
            'Total Paid Amount',
            'First Registered At'
        ];
    }

    public function map($p): array
    {
        // Aggregate Entry Data
        $orderCodes = $p->raceEntries->map(fn($e) => $e->order->order_code ?? '-')->unique()->filter()->implode(' | ');
        $statuses = $p->raceEntries->map(fn($e) => strtoupper($e->order->status ?? ($e->status ?? 'unknown')))->unique()->implode(' | ');
        
        $ticketDetails = $p->raceEntries->map(function($e) {
            $cat = $e->ticket->category->name ?? '-';
            $type = strtoupper($e->ticket->type ?? '-');
            return "($type - $cat)";
        })->unique()->implode(' | ');

        // Sums for orders with the specified status (default 'paid')
        $targetOrders = $p->raceEntries->filter(function($e) {
            return ($e->order->status ?? $e->status) === $this->status;
        })->map(fn($e) => $e->order)->unique('id')->filter();

        $totalPaid = $targetOrders->sum('total_price');
        $donationScholarship = $targetOrders->sum('donation_scholarship');
        $donationEvent = $targetOrders->sum('donation_event');
        $adminFee = $targetOrders->sum('admin_fee');
        $paidAmount = $totalPaid - $donationScholarship - $donationEvent - $adminFee;

        return [
            $p->name,
            $p->email,
            $p->phone_number,
            $p->nik,
            $p->date_birth,
            strtoupper($p->sex),
            $p->blood_type,
            $p->jersey_size,
            $p->nim_nrp ?: '-',
            $p->emergency_contact_name,
            $p->emergency_contact_phone_number,
            $p->emergency_contact_relationship,
            $orderCodes,
            $statuses,
            $ticketDetails,
            $paidAmount,
            $donationScholarship,
            $donationEvent,
            $adminFee,
            $totalPaid,
            $p->created_at->format('Y-m-d H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1    => ['font' => ['bold' => true]],
        ];
    }
}
