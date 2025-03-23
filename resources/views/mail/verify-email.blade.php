<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <div class="email-container">
        <div class="email-header" style="font-weight: bold">Verify your Email Address - International MAOI Expert Group
        </div>
        <br>
        <div class="email-body">
            <p>Dear {{ is_object($user) ? $user->name : 'User' }},</p>
            <p>Thank you for your interest in joining the International MAOI Expert Group. To proceed with your
                membership application, please use the following One-Time Password (OTP):</p>
            <div class="otp-code" style="font-weight: bold">{{ $otp }}</div>
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
