<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset your admin password</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:12px;padding:28px;border:1px solid #e5e7eb;">
                    <tr>
                        <td>
                            <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#111827;">Reset your admin password</h1>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#4b5563;">
                                Hello {{ $user->name }}, we received a request to reset your Zenotic Biotech admin password.
                            </p>
                            <p style="margin:0 0 24px;">
                                <a href="{{ $resetUrl }}" style="display:inline-block;background:#16a34a;color:#ffffff;text-decoration:none;font-weight:700;padding:12px 18px;border-radius:8px;">
                                    Reset password
                                </a>
                            </p>
                            <p style="margin:0 0 12px;font-size:14px;line-height:1.6;color:#4b5563;">
                                This link will expire soon. If you did not request a password reset, you can ignore this email.
                            </p>
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#6b7280;word-break:break-all;">
                                {{ $resetUrl }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
