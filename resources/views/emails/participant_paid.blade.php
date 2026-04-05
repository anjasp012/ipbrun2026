<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Success - IPB Run 2026</title>
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
                    <h1 style="margin:0 0 15px;font-size:26px;color:#1a2b4b;letter-spacing:-0.5px;">Congratulations!
                        🏃‍♂️</h1>
                    <p style="margin:0;font-size:16px;line-height:1.6;color:#556677;">
                        Dear <strong style="color:#1a2b4b;">{{ $participant->name }}</strong>, your registration for:
                    </p>
                    <div style="margin-top:15px;">
                        @foreach($participant->raceEntries as $entry)
                            <div style="background-color:#f0f9f6; padding:12px 20px; border-radius:10px; margin-bottom:8px; border-left:4px solid #00875a;">
                                <span style="color:#00875a;font-weight:bold;font-size:15px;display:block;">
                                    IPB Run 2026 – {{ $entry->ticket->category->name }}
                                </span>
                                <span style="font-size:11px; color:#778899; text-transform:uppercase; font-weight:bold;">
                                    Category: {{ $entry->ticket->name ?: strtoupper($entry->ticket->type) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <p style="margin:15px 0 0;font-size:16px;line-height:1.6;color:#556677;">
                        is successfully confirmed.
                    </p>
                </td>
            </tr>

            <!-- Registration data table -->
            <tr>
                <td style="padding:0 40px 30px;">
                    <div style="background-color:#f9fbff;border-radius:12px;padding:25px;border:1px solid #eef2f7;">
                        <h3
                            style="margin:0 0 15px;font-size:14px;color:#00875a;text-transform:uppercase;letter-spacing:1.5px;">
                            Registration Summary</h3>
                        <table role="presentation" width="100%" style="border-collapse:collapse;">
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Order Code</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    #{{ $participant->order_code }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Full Name</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->name }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Phone Number</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->phone_number }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Address</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ Str::limit($participant->address, 30) }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    T-Shirt Size</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->jersey_size }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Nationality</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->nationality }}</td>
                            </tr>
                            <tr>
                                <td
                                    style="padding:10px 0;font-size:13px;color:#778899;border-bottom:1px solid #edf0f5;">
                                    Emergency Contact</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;border-bottom:1px solid #edf0f5;">
                                    {{ $participant->emergency_contact_name }}
                                    ({{ $participant->emergency_contact_phone_number }})</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;font-size:13px;color:#778899;">NIK / NIM</td>
                                <td
                                    style="padding:10px 0;font-size:14px;color:#1a2b4b;font-weight:600;text-align:right;">
                                    {{ $participant->nik }} / {{ $participant->nim_nrp ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Dashboard Access Account Box -->
            <tr>
                <td style="padding:0 40px 40px;">
                    <div
                        style="background:linear-gradient(135deg, #00875a 0%, #006a46 100%);border-radius:12px;padding:30px;color:#ffffff;text-align:center;">
                        <h3 style="margin:0 0 10px;font-size:18px;font-weight:bold;">Login Access</h3>
                        <p style="margin:0 0 20px;font-size:14px;opacity:0.9;">Manage your BIB number and check results
                            here:</p>

                        <table role="presentation" width="100%"
                            style="background-color:rgba(255,255,255,0.1);border-radius:8px;margin-bottom:25px;">
                            <tr>
                                <td align="center" style="padding:15px;border-right:1px solid rgba(255,255,255,0.2);">
                                    <span
                                        style="font-size:11px;opacity:0.7;text-transform:uppercase;">Username/Email</span><br>
                                    <strong style="font-size:14px;">{{ $participant->email }}</strong>
                                </td>
                                <td align="center" style="padding:15px;">
                                    <span
                                        style="font-size:11px;opacity:0.7;text-transform:uppercase;">Password</span><br>
                                    <strong style="font-size:14px;">{{ $password }}</strong>
                                </td>
                            </tr>
                        </table>

                        <a href="{{ url('/login') }}"
                            style="display:inline-block;background-color:#ffffff;color:#00875a;text-decoration:none;padding:14px 35px;border-radius:8px;font-weight:bold;font-size:14px;box-shadow:0 4px 15px rgba(0,0,0,0.1);">LOGIN
                            PORTAL</a>
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="padding:30px 40px;background-color:#f9fbfc;text-align:center;border-top:1px solid #f0f0f0;">
                    <p style="margin:0;font-size:13px;color:#99aabb;">Regards,<br><strong style="color:#1a2b4b;">PANITIA
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
