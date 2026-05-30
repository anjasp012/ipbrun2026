<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RaceEntry;
use Carbon\Carbon;

class ScanController extends Controller
{
    /**
     * Halaman utama scan QR race pack
     */
    public function index()
    {
        $user = auth()->user();

        $query = RaceEntry::whereNotNull('scanned_at')
            ->with(['participant', 'ticket.category', 'scanner'])
            ->orderBy('scanned_at', 'desc');

        // Jika user bukan superadmin/admin (misal PIC), hanya tampilkan scan miliknya
        if (!in_array($user->role, ['superadmin', 'admin'])) {
            $query->where('scanned_by', $user->id);
        }

        // Ambil 50 scan terbaru untuk log
        $recentScans = $query->limit(50)->get();

        return view('pages.admin.scan.index', compact('recentScans'));
    }

    /**
     * Proses scan QR code (API endpoint, return JSON)
     */
    public function process(Request $request)
    {
        $request->validate([
            'race_entry_id' => 'required|string',
        ]);

        $raceEntryId = trim($request->race_entry_id);

        $entry = RaceEntry::with(['participant', 'ticket.category', 'ticket.period', 'scanner'])
            ->find($raceEntryId);

        if (!$entry) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'QR Code tidak valid atau tidak ditemukan di sistem.',
            ], 404);
        }

        if ($entry->status !== 'paid') {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Peserta ini belum melakukan pembayaran atau statusnya tidak valid.',
                'participant_name' => $entry->participant->name ?? '-',
                'category' => $entry->ticket->category->name ?? '-',
                'entry_status' => $entry->status,
            ], 422);
        }

        if ($entry->scanned_at) {
            return response()->json([
                'status' => 'already_scanned',
                'message' => 'Race Pack untuk peserta ini sudah pernah diambil sebelumnya.',
                'participant_name' => $entry->participant->name ?? '-',
                'category' => $entry->ticket->category->name ?? '-',
                'scanned_at' => Carbon::parse($entry->scanned_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s'),
                'scanned_by' => $entry->scanner->name ?? 'Unknown',
            ], 200);
        }

        // Update scan status
        $entry->update([
            'scanned_at' => now(),
            'scanned_by' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Race Pack berhasil dicatat sebagai sudah diambil!',
            'participant_name' => $entry->participant->name ?? '-',
            'participant_nik' => $entry->participant->nik ?? '-',
            'category' => $entry->ticket->category->name ?? '-',
            'ticket_name' => $entry->ticket->name ?? '-',
            'bib_number' => $entry->bib_number ?? 'Belum ditetapkan',
            'jersey_size' => $entry->participant->jersey_size ?? '-',
            'scanned_at' => Carbon::now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s'),
            'scanned_by' => auth()->user()->name,
        ], 200);
    }

    /**
     * Reset status scan (superadmin only, untuk koreksi)
     */
    public function reset(RaceEntry $raceEntry)
    {
        if (!$raceEntry->scanned_at) {
            return back()->with('error', 'Race entry ini belum pernah di-scan.');
        }

        $raceEntry->update([
            'scanned_at' => null,
            'scanned_by' => null,
        ]);

        return back()->with('success', "Status pengambilan Race Pack untuk {$raceEntry->participant->name} ({$raceEntry->ticket->category->name}) berhasil direset.");
    }
}
