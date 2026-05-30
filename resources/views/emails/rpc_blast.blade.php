<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi Pengambilan Race Pack (RPC) - IPB RUN 2026</title>
</head>

<body style="margin:0;padding:20px 0;background-color:#f4f7f9;font-family:'Helvetica Neue', Arial, sans-serif;color:#333;">
    <center>
        <table role="presentation" width="100%"
            style="max-width:600px;background-color:#ffffff;border-collapse:separate;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.05);border:1px solid #e1e8ed;">
            <!-- Header with Logo -->
            <tr>
                <td
                    style="padding:40px 0;text-align:center;background:linear-gradient(135deg, #ffffff 0%, #ffffff 100%);border-bottom:1px solid #f0f0f0;">
                    <img src="{{ $message->embed(public_path('assets/images/logo_ipbrun2026.png')) }}" width="180"
                        alt="IPB RUN 2026" style="display:block;margin:0 auto;"> </td>
            </tr> 
            
            <!-- Main Greeting -->
            <tr>
                <td style="padding:40px 40px 20px;">
                    <p style="margin:0;font-size:16px;line-height:1.6;color:#556677;">
                        Hai Runner</strong>! 👋<br><br>
                        Waktunya semakin dekat menuju IPB RUN 2026 – Lestari Untuk Bumi<br><br>
                        Sebelum berlari, pastikan kamu sudah mengambil Racepack milikmu ya.
                    </p>
                </td>
            </tr> 
            
            <!-- Information Box -->
            <tr>
                <td style="padding:0 40px 30px;">
                    <div style="background-color:#f9fbff;border-radius:12px;padding:25px;border:1px solid #eef2f7;">
                        <p style="margin:0 0 15px;font-size:14px;line-height:1.6;color:#556677;">
                            📍 <strong>Tempat:</strong> IPB International Convention Center (IICC)<br>
                            📅 <strong>Tanggal:</strong> 3 - 4 Juni 2026<br>
                            🕘 <strong>Waktu:</strong> 09.00 – 20.00 WIB
                        </p>
                        <p style="margin:0 0 15px;font-size:14px;line-height:1.6;color:#556677;">
                            Gunakan QR Code di bawah ini untuk menukarkan Racepack di lokasi.<br>
                            Cukup tunjukkan QR Code (digital atau cetak) ke petugas registrasi.
                        </p>
                        
                        <h3 style="margin:0 0 10px;font-size:14px;color:#00875a;text-transform:uppercase;letter-spacing:1.5px;">
                            Syarat & Ketentuan Pengambilan Racepack:
                        </h3>
                        <ul style="margin:0;padding-left:20px;font-size:14px;line-height:1.6;color:#556677;">
                            <li style="margin-bottom:8px;">Pengambilan dilakukan oleh peserta secara langsung.</li>
                            <li style="margin-bottom:8px;">Jika diwakilkan, wajib melampirkan surat kuasa dan kartu identitas yang diwakilkan dalam bentuk print.</li>
                            <li style="margin-bottom:8px;">Formulir surat kuasa dapat diunduh di: <br><a href="{{ url('/surat-kuasa') }}" style="color:#00875a;">{{ url('/surat-kuasa') }}</a></li>
                            <li style="margin-bottom:8px;">Baca juga rules & regulations di: <br><a href="{{ url('/rules') }}" style="color:#00875a;">{{ url('/rules') }}</a></li>
                        </ul>
                        <p style="margin:15px 0 0;font-size:14px;line-height:1.6;color:#556677;">
                            Pastikan kamu datang sesuai jadwal, karena panitia tidak melayani pengambilan RPC diluar jadwal & waktu yang telah ditentukan. Jangan lupa bawa semangat terbaikmu untuk <strong>#LestariUntukBumi</strong>
                        </p>
                    </div>
                </td>
            </tr> 

            
            <!-- Footer -->
            <tr>
                <td style="padding:30px 40px;background-color:#f9fbfc;text-align:center;border-top:1px solid #f0f0f0;">
                    <p style="margin:0;font-size:13px;color:#99aabb;">Sampai jumpa di garis Start!<br><strong
                            style="color:#1a2b4b;">PANITIA IPB RUN</strong></p>
                    <div style="margin-top:20px;padding-top:20px;border-top:1px dashed #e1e8ed;">
                        <p style="margin:0;font-size:11px;color:#b0c0d0;">© 2026 IPB RUN. All rights reserved.</p>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
