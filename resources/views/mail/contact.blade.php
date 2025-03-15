<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation of Receipt - International MAOI Expert Group</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .email-body {
            font-size: 16px;
            color: #555;
        }

        .message-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #007BFF;
            margin: 20px 0;
        }

        .email-footer {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">Confirmation of Receipt - International MAOI Expert Group</div>
        <div class="email-body">
            <p>Dear {{ $contactData['fname'] }} {{ $contactData['lname'] }},</p>
            <p>Thank you for reaching out to the International MAOI Expert Group. We have received your message:</p>
            <div class="message-details">
                <p><strong>Subject:</strong> {{ $contactData['subject'] }}</p>
                <p><strong>Message:</strong> {{ $contactData['message'] }}</p>
            </div>
            <p>We will get back to you as soon as possible.</p>
        </div>
        <div class="email-footer">
            <p>Sincerely,</p>
            <p>The International MAOI Expert Group</p>
        </div>
    </div>
</body>

</html>
