<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>QR Code RPC - IPB RUN 2026</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #003366;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #003366;
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .participant-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .participant-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .qr-section {
            page-break-inside: avoid;
            margin-bottom: 40px;
            text-align: center;
        }
        .ticket-name {
            font-size: 18px;
            font-weight: bold;
            color: #e8630a;
            margin-bottom: 10px;
        }
        .qr-code-box {
            border: 2px dashed #cbd5e1;
            padding: 20px;
            display: inline-block;
            background: #fff;
        }
        .qr-code-box img {
            width: 200px;
            height: 200px;
        }
        .qr-id {
            font-family: monospace;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TANDA PENGAMBILAN RACE PACK</h1>
        <p>IPB RUN 2026</p>
    </div>

    <div class="participant-info">
        <p><strong>Nama:</strong> {{ $participant->name }}</p>
        <p><strong>NIK / Identitas:</strong> {{ $participant->nik }}</p>
        <p><strong>Email:</strong> {{ $participant->email }}</p>
        <p><strong>No. HP:</strong> {{ $participant->phone_number }}</p>
    </div>

    <p style="text-align: center; margin-bottom: 30px; font-size: 14px;">
        Tunjukkan QR Code di bawah ini kepada panitia saat pengambilan Race Pack.
    </p>

    @foreach($participant->raceEntries->where('status', 'paid') as $entry)
        <div class="qr-section">
            <div class="ticket-name">
                Kategori: {{ $entry->ticket->category->name ?? 'Unknown' }} 
                ({{ $entry->ticket->name ?: strtoupper($entry->ticket->type) }})
            </div>
            <div class="qr-code-box">
                @php
                    // URL endpoint external API untuk generate QR code
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($entry->id) . '&margin=10';
                    
                    // Supaya DomPDF tidak terkendala jaringan, kita embed base64
                    try {
                        $qrData = file_get_contents($qrUrl);
                        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrData);
                    } catch(\Exception $e) {
                        $qrBase64 = '';
                    }
                @endphp
                
                @if($qrBase64)
                    <img src="{{ $qrBase64 }}" alt="QR Code">
                @else
                    <div style="width:200px; height:200px; line-height:200px; border:1px solid #ccc;">Gagal memuat QR</div>
                @endif
                <div class="qr-id">ID: {{ $entry->id }}</div>
            </div>
        </div>
    @endforeach

    <div class="footer">
        Harap simpan dokumen ini dengan aman. Dokumen ini adalah bukti kepemilikan tiket Anda.<br>
        &copy; 2026 IPB RUN. All rights reserved.
    </div>
</body>
</html>
