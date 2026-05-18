<!DOCTYPE html>
<html>
<head>
    <title>Laporan Error Import Peserta</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Laporan Error Import Peserta - IPB Run 2026</h2>
    <p>Halo,</p>
    <p>Berikut adalah daftar peserta yang <strong>gagal diimport</strong> dari proses import massal terbaru Anda:</p>

    <ul style="color: #d9534f; background: #f9f2f2; padding: 15px 30px; border-radius: 5px; border: 1px solid #ebccd1;">
        @foreach($importErrors as $error)
            <li style="margin-bottom: 5px;">{{ $error }}</li>
        @endforeach
    </ul>

    <p>Data peserta lainnya (jika ada yang berhasil) telah sukses diimport ke dalam sistem dan email notifikasi e-invoice masing-masing telah dikirim.</p>
    <br>
    <p>Terima kasih,</p>
    <p><strong>Tim IPB Run 2026</strong></p>
</body>
</html>
