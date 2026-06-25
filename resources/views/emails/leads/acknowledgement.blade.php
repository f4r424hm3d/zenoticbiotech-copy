<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>We received your message</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border-radius:12px;padding:28px;border:1px solid #e5e7eb;">
                    <tr>
                        <td>
                            <h1 style="margin:0 0 12px;font-size:23px;line-height:1.3;color:#111827;">Thank you for contacting Zenotic Biotech</h1>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#4b5563;">
                                Hello {{ $lead->name }}, we have received your message. Our team will review your enquiry and get back to you soon.
                            </p>
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px;margin-bottom:18px;">
                                <p style="margin:0 0 8px;font-size:14px;color:#166534;"><strong>Your enquiry summary</strong></p>
                                <p style="margin:0;font-size:14px;line-height:1.7;color:#374151;white-space:pre-line;">{{ $lead->message }}</p>
                            </div>
                            <p style="margin:0;font-size:14px;line-height:1.7;color:#6b7280;">
                                Regards,<br>
                                Zenotic Biotech Team
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
