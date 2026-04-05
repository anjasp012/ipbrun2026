<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectStr }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f9; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333;">
    <center>
        <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-collapse: separate; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e1e8ed; margin-top: 20px;">
            <!-- Header with Logo -->
            <tr>
                <td style="padding: 40px 0; text-align: center; background-color: #ffffff; border-bottom: 1px solid #f0f0f0;">
                    <img src="{{ $message->embed(public_path('assets/images/logo_ipbrun2026.png')) }}" width="180" alt="IPB RUN 2026" style="display: block; margin: 0 auto;">
                </td>
            </tr>

            <!-- Message Body -->
            <tr>
                <td style="padding: 40px;">
                    <h1 style="margin: 0 0 20px; font-size: 24px; color: #1a2b4b; letter-spacing: -0.5px;">{{ $subjectStr }}</h1>
                    <div style="font-size: 16px; line-height: 1.8; color: #556677; white-space: pre-line;">
                        {!! nl2br(e($messageStr)) !!}
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="padding: 30px 40px; background-color: #f9fbfc; text-align: center; border-top: 1px solid #f0f0f0;">
                    <p style="margin: 0; font-size: 13px; color: #99aabb;">Regards,<br><strong style="color: #1a2b4b;">PANITIA IPB RUN 2026</strong></p>
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed #e1e8ed;">
                        <p style="margin: 0; font-size: 11px; color: #b0c0d0;">© 2026 IPB RUN. All rights reserved.</p>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
