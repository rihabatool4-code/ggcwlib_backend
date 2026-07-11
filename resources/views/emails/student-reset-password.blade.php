<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: #ffffff; max-width: 500px; margin: auto; padding: 30px; border-radius: 8px; }
        .btn { display: inline-block; padding: 12px 24px; background: #0f8a65; color: #ffffff; text-decoration: none; border-radius: 6px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <p>We received a request to reset your GGCW Library account password. Click the button below to set a new password:</p>
        <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
        <p style="margin-top: 20px; color: #888; font-size: 13px;">This link will expire in 60 minutes. If you didn't request this, ignore this email.</p>
    </div>
</body>
</html>