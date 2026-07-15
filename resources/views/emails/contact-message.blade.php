<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>New Contact Form Submission — GGCW Library</h2>
    <p><strong>Name:</strong> {{ $senderName }}</p>
    <p><strong>Email:</strong> {{ $senderEmail }}</p>
    <p><strong>Subject:</strong> {{ $subjectLine }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $messageBody }}</p>
</body>
</html>