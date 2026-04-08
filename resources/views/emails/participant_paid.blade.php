<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Success - IPB Run 2026</title>
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
            </tr> <!-- Main Greeting -->
            <tr>
                <td style="padding:40px 40px 20px;">
                    @if ($userExists ?? false)
                        <h1 style="margin:0 0 15px;font-size:26px;color:#1a2b4b;letter-spacing:-0.5px;">E-Invoice: Tiket
                            Tambahan 🏃‍♂️</h1>
                        <p style="margin:0;font-size:16px;line-height:1.6;color:#556677;"> Halo <strong
                                style="color:#1a2b4b;">{{ $participant->name }}</strong>, pesanan tiket tambahan Anda
                            telah berhasil dikonfirmasi. Berikut adalah detail untuk: </p>
                    @else
                        <h1 style="margin:0 0 15px;font-size:26px;color:#1a2b4b;letter-spacing:-0.5px;">Congratulations!
                            🏃‍♂️</h1>
                        <p style="margin:0;font-size:16px;line-height:1.6;color:#556677;"> Dear <strong
                                style="color:#1a2b4b;">{{ $participant->name }}</strong>, your registration for: </p>
                    @endif
                    <div style="margin-top:15px;">
                        @foreach ($orders ?? ([$order ?? null] ?: []) as $o)
                            @if ($o)
                                @foreach ($o->raceEntries as $entry)
                                    <div
                                        style="background-color:#f0f9f6; padding:12px 20px; border-radius:10px; margin-bottom:8px; border-left:4px solid #00875a;">
                                        <span style="color:#00875a;font-weight:bold;font-size:15px;display:block;"> IPB
                                            Run 2026 – {{ $entry->ticket->category->name }}
                                            {{ $entry->ticket->name ?: strtoupper($entry->ticket->type) }} </span>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                    <p style="margin:15px 0 0;font-size:16px;line-height:1.6;color:#556677;"> is successfully confirmed.
                    </p>
                </td>
            </tr> <!-- Registration data table -->
            <tr>
                <td style="padding:0 40px 30px;">
                    <div style="background-color:#f9fbff;border-radius:12px;padding:25px;border:1px solid #eef2f7;">
                        <h3
                            style="margin:0 0 15px;font-size:14px;color:#00875a;text-transform:uppercase;letter-spacing:1.5px;">
                            Registration Summary</h3>
                        <table role="presentation" width="100%" style="border-collapse:collapse;">
                            <tr>
                                <td style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Order Code</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    #{{ $order->order_code }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Full Name</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Phone Number</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->phone_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Email</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->email }}</td>
                            </tr>
                            @if (!($userExists ?? false))
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        NIK / NIM</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->nik }} / {{ $participant->nim_nrp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Tanggal Lahir</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->date_birth }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Jenis Kelamin</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->sex === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Golongan Darah</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->blood_type }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Jersey Size</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->jersey_size }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Kewarganegaraan</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->nationality }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Alamat</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->address }}</td>
                                </tr>

                                <tr>
                                    <td colspan="2"
                                        style="padding:30px 0 10px;font-size:11px;color:#00875a;font-weight:bold;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #edf0f5;">
                                        Emergency Contact</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Nama</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->emergency_contact_name }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Nomor HP</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->emergency_contact_phone_number }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                        Hubungan</td>
                                    <td
                                        style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                        {{ $participant->emergency_contact_relationship }}</td>
                                </tr>

                                @if ($participant->running_community || $participant->best_time || $participant->shuttle_bus)
                                    <tr>
                                        <td colspan="2"
                                            style="padding:30px 0 10px;font-size:11px;color:#00875a;font-weight:bold;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #edf0f5;">
                                            Running Data</td>
                                    </tr>
                                    @if ($participant->running_community)
                                        <tr>
                                            <td
                                                style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                                Komunitas</td>
                                            <td
                                                style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                                {{ $participant->running_community }}</td>
                                        </tr>
                                    @endif
                                    @if ($participant->best_time)
                                        <tr>
                                            <td
                                                style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                                Best Time</td>
                                            <td
                                                style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                                {{ $participant->best_time }}</td>
                                        </tr>
                                    @endif
                                    @if ($participant->shuttle_bus)
                                        <tr>
                                            <td
                                                style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                                Shuttle Bus</td>
                                            <td
                                                style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                                {{ $participant->shuttle_bus }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endif
                        </table>
                    </div>
                </td>
            </tr> <!-- Dashboard Access Account Box -->
            @if (!($userExists ?? false))
                <tr>
                    <td style="padding:0 40px 40px;">
                        <div
                            style="background:linear-gradient(135deg, #00875a 0%, #006a46 100%);border-radius:12px;padding:30px;color:#ffffff;text-align:center;">
                            <h3 style="margin:0 0 10px;font-size:18px;font-weight:bold;">Login Access</h3>
                            <p style="margin:0 0 20px;font-size:14px;opacity:0.9;">Manage your BIB number and check
                                results here:</p>
                            <table role="presentation" width="100%"
                                style="background-color:rgba(255,255,255,0.1);border-radius:8px;margin-bottom:25px;">
                                <tr>
                                    <td align="center" style="padding:15px;border-right:1px solid rgba(255,255,255,0.2);">
                                        <span
                                            style="font-size:11px;opacity:0.7;text-transform:uppercase;">Username/Email</span><br>
                                        <strong style="font-size:14px;">{{ $participant->email }}</strong> </td>
                                    <td align="center" style="padding:15px;"> <span
                                            style="font-size:11px;opacity:0.7;text-transform:uppercase;">Password</span><br>
                                        <strong style="font-size:14px;">{{ $password }}</strong> </td>
                                </tr>
                            </table> <a href="{{ url('/login') }}"
                                style="display:inline-block;background-color:#ffffff;color:#00875a;text-decoration:none;padding:14px 35px;border-radius:8px;font-weight:bold;font-size:14px;box-shadow:0 4px 15px rgba(0,0,0,0.1);">LOGIN
                                PORTAL</a>
                        </div>
                    </td>
                </tr>
            @endif <!-- Footer -->
            <tr>
                <td style="padding:30px 40px;background-color:#f9fbfc;text-align:center;border-top:1px solid #f0f0f0;">
                    <p style="margin:0;font-size:13px;color:#99aabb;">Regards,<br><strong
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
