<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background:#f4f6f8; padding:24px; margin:0;">
    <div style="max-width:480px;margin:0 auto;background:#fff;border-radius:8px;padding:24px;">
        <h2 style="color:#1a5f7a;margin-top:0;">GGCW Library</h2>
        @if($recipientName)
            <p>Hi {{ $recipientName }},</p>
        @endif
        <h3 style="margin-bottom:6px; color:#222;">{{ $notifTitle }}</h3>
        <p style="color:#444; line-height:1.5;">{{ $notifSubtitle }}</p>
        <hr style="border:none;border-top:1px solid #eee;margin:20px 0;">
        <p style="font-size:12px;color:#999;">This is an automated message from GGCW Library. Please do not reply to this email.</p>
    </div>
</body>
</html>