<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Us</title>
</head>

<body>
    <p>Dear {{ $contactData['fname'] }} {{ $contactData['lname'] }},</p>
    <p>Thank you for reaching out to us. We have received your message:</p>
    <p><strong>Subject:</strong> {{ $contactData['subject'] }}</p>
    <p><strong>Message:</strong> {{ $contactData['message'] ?? 'No message provided.' }}</p>
    <p>Our team will get back to you as soon as possible.</p>
    <p>Best regards,</p>
    <p>{{ config('app.name') }} Team</p>
</body>

</html>
