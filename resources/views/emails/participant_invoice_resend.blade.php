<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Invoice - IPB Run 2026</title>
</head>

<body
    style="margin:0;padding:20px 0;background-color:#f4f7f9;font-family:'Helvetica Neue', Arial, sans-serif;color:#333;">
    <center>
        <table role="presentation" width="100%"
            style="max-width:600px;background-color:#ffffff;border-collapse:separate;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.05);border:1px solid #e1e8ed;">
            <!-- Header with Logo -->
            <tr>
                <td
                    style="padding:40px 0;text-align:center;background:linear-gradient(135deg, #ffffff 0%, #ffffff 100%);border-bottom:1px solid #f0f0f0;">
                    <img src="{{ $message->embed(public_path('assets/images/logo_ipbrun2026.png')) }}" width="180"
                        alt="IPB RUN 2026" style="display:block;margin:0 auto;">
                </td>
            </tr>

            <!-- Main Greeting -->
            <tr>
                <td style="padding:40px 40px 20px;">
                    <h1 style="margin:0 0 15px;font-size:26px;color:#1a2b4b;letter-spacing:-0.5px;">E-Invoice IPB RUN 2026 🏃‍♂️</h1>
                    <p style="margin:0;font-size:16px;line-height:1.6;color:#556677;">
                        Halo <strong style="color:#1a2b4b;">{{ $participant->name }}</strong>, berikut adalah salinan E-Invoice pendaftaran Anda untuk kegiatan:
                        <div style="margin-top:15px;">
                            @foreach($participant->raceEntries as $entry)
                                <div style="background-color:#f0f9f6; padding:10px 15px; border-radius:8px; margin-bottom:5px; border-left:3px solid #00875a;">
                                    <span style="color:#00875a;font-weight:bold;font-size:14px;">
                                        IPB Run 2026 – {{ $entry->ticket->category->name }} ({{ $entry->ticket->name }})
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </p>
                    <p style="margin-top:15px;font-size:14px;line-height:1.6;color:#556677;">
                        Silakan simpan dokumen ini sebagai bukti pendaftaran resmi Anda. Detail pendaftaran Anda dapat dilihat pada tabel di bawah ini.
                    </p>
                </td>
            </tr>

            <!-- Registration data table -->
            <tr>
                <td style="padding:0 40px 30px;">
                    <div style="background-color:#f9fbff;border-radius:12px;padding:25px;border:1px solid #eef2f7;">
                        <h3
                            style="margin:0 0 15px;font-size:14px;color:#00875a;text-transform:uppercase;letter-spacing:1.5px;">
                            Ringkasan Pendaftaran</h3>
                        <table role="presentation" width="100%" style="border-collapse:collapse;">
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Kode Pesanan</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    #{{ $participant->order_code }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Nama Lengkap</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->name }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Nomor WhatsApp</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->phone_number }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Status Pembayaran</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#00875a;font-weight:bold;text-align:right;border-bottom:1px solid #edf0f5;text-transform:uppercase;">
                                    {{ $participant->status }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Ukuran Jersey</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->jersey_size }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;font-size:13px;color:#778899;">Total Pembayaran</td>
                                <td
                                    style="padding:10px 0;font-size:16px;color:#e8630a;font-weight:800;text-align:right;">
                                    Rp {{ number_format($participant->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="padding:30px 40px;background-color:#f9fbfc;text-align:center;border-top:1px solid #f0f0f0;">
                    <p style="margin:0;font-size:13px;color:#99aabb;">Salam hangat,<br><strong style="color:#1a2b4b;">PANITIA
                            IPB RUN 2026</strong></p>
                    <div style="margin-top:20px;padding-top:20px;border-top:1px dashed #e1e8ed;">
                        <p style="margin:0;font-size:11px;color:#b0c0d0;">© 2026 IPB RUN. All rights reserved.</p>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
