<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your Email Address - International MAOI Expert Group</title>
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

        .otp-code {
            font-size: 20px;
            font-weight: bold;
            color: #007BFF;
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
        <div class="email-header">Verify your Email Address - International MAOI Expert Group</div>
        <div class="email-body">
            <p>Dear {{ is_object($user) ? $user->name : 'User' }},</p>
            <p>Thank you for your interest in joining the International MAOI Expert Group. To proceed with your
                membership application, please use the following One-Time Password (OTP):</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>This OTP is valid for 1 hour from the time of this email.</p>
            <p>If you did not apply for membership, please disregard this email.</p>
        </div>
        <div class="email-footer">
            <p>Sincerely,</p>
            <p>The International MAOI Expert Group</p>
        </div>
    </div>
</body>

</html>
