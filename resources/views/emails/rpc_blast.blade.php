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
                    <h1 style="margin:0 0 15px;font-size:24px;color:#1a2b4b;letter-spacing:-0.5px;">Persiapan Pengambilan Race Pack 📦🏃‍♂️</h1>
                    <p style="margin:0;font-size:16px;line-height:1.6;color:#556677;">
                        Halo <strong style="color:#1a2b4b;">{{ $participant->name }}</strong>,<br><br>
                        Hari perlombaan IPB RUN 2026 semakin dekat! Ini saatnya untuk mengambil Race Pack Anda.
                    </p>
                </td>
            </tr> 
            
            <!-- Information Box -->
            <tr>
                <td style="padding:0 40px 30px;">
                    <div style="background-color:#f9fbff;border-radius:12px;padding:25px;border:1px solid #eef2f7;">
                        <h3 style="margin:0 0 15px;font-size:14px;color:#00875a;text-transform:uppercase;letter-spacing:1.5px;">
                            Jadwal & Instruksi Pengambilan
                        </h3>
                        <p style="margin:0 0 15px;font-size:14px;line-height:1.6;color:#556677;">
                            Pengambilan Race Pack (RPC) akan dilaksanakan pada tanggal <strong>3 & 4 Juni 2026</strong>. 
                            Untuk memperlancar proses pengambilan, Anda wajib menunjukkan <strong>QR Code RPC</strong> kepada panitia. 
                            Tanda Pengambilan berserta QR Code Anda telah kami <strong>lampirkan dalam bentuk file PDF</strong> di email ini.
                        </p>
                        
                        <ul style="margin:0;padding-left:20px;font-size:14px;line-height:1.6;color:#556677;">
                            <li style="margin-bottom:8px;">Pastikan membawa <strong>KTP / Identitas Asli</strong> (atau fotokopi) saat pengambilan.</li>
                            <li style="margin-bottom:8px;">Tunjukkan PDF terlampir (atau QR Code di Dashboard Anda) kepada petugas scan.</li>
                            <li style="margin-bottom:8px;">Jika diwakilkan, pastikan perwakilan membawa surat kuasa dan salinan identitas Anda.</li>
                        </ul>
                    </div>
                </td>
            </tr> 
            
            <!-- Dashboard Access Account Box -->
            <tr>
                <td style="padding:0 40px 40px;">
                    <div style="background:linear-gradient(135deg, #e8630a 0%, #d85300 100%);border-radius:12px;padding:30px;color:#ffffff;text-align:center;">
                        <h3 style="margin:0 0 10px;font-size:18px;font-weight:bold;">Akses QR Code Anda</h3>
                        <p style="margin:0 0 20px;font-size:14px;opacity:0.9;">
                            Silakan login ke portal peserta untuk melihat jadwal, lokasi, dan QR Code RPC Anda.
                        </p>
                        <a href="{{ url('/login') }}"
                            style="display:inline-block;background-color:#ffffff;color:#e8630a;text-decoration:none;padding:14px 35px;border-radius:8px;font-weight:bold;font-size:14px;box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                            KE DASHBOARD PESERTA
                        </a>
                    </div>
                </td>
            </tr>
            
            <!-- Footer -->
            <tr>
                <td style="padding:30px 40px;background-color:#f9fbfc;text-align:center;border-top:1px solid #f0f0f0;">
                    <p style="margin:0;font-size:13px;color:#99aabb;">Sampai jumpa di garis start!<br><strong
                            style="color:#1a2b4b;">PANITIA IPB RUN 2026</strong></p>
                    <div style="margin-top:20px;padding-top:20px;border-top:1px dashed #e1e8ed;">
                        <p style="margin:0;font-size:11px;color:#b0c0d0;">© 2026 IPB RUN. All rights reserved.</p>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
