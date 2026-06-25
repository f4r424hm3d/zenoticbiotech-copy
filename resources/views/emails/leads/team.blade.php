<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Lead</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:12px;padding:28px;border:1px solid #e5e7eb;">
                    <tr>
                        <td>
                            <h1 style="margin:0 0 8px;font-size:24px;line-height:1.3;color:#111827;">New Contact Lead</h1>
                            <p style="margin:0 0 22px;font-size:14px;line-height:1.6;color:#6b7280;">A new enquiry was submitted from the Zenotic Biotech contact page.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;">
                                @foreach ([
                                    'Lead ID' => $lead->id,
                                    'Name' => $lead->name,
                                    'Email' => $lead->email,
                                    'Phone' => $lead->phone ?: '-',
                                    'Subject' => $lead->subject ?: 'Contact enquiry',
                                    'Source' => $lead->source ?: '-',
                                    'Page URL' => $lead->source_path ?: '-',
                                    'IP Address' => $lead->ip_address ?: '-',
                                    'Submitted At' => optional($lead->created_at)->format('d M Y, h:i A'),
                                ] as $label => $value)
                                    <tr>
                                        <td style="width:150px;padding:10px;border-bottom:1px solid #e5e7eb;color:#6b7280;font-weight:700;">{{ $label }}</td>
                                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;color:#111827;">{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <h2 style="margin:22px 0 8px;font-size:16px;color:#111827;">Message</h2>
                            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:14px;font-size:15px;line-height:1.7;color:#374151;white-space:pre-line;">{{ $lead->message }}</div>

                            <p style="margin:18px 0 0;font-size:12px;line-height:1.6;color:#9ca3af;word-break:break-all;">
                                User agent: {{ $lead->user_agent ?: '-' }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
