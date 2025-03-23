<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header"style="font-weight: bold">Membership Expiration Notification</div>
        <br>
        <!-- Body -->
        <div class="email-body">
            <p>Hello {{ $membership->user->name }},</p>
            <p>Your membership <strong>{{ $membership->membership->name }}</strong> is about to expire in
                <strong>{{ $today->diffInDays($membership->end_date) }} days</strong>.
            </p>
            <p>Please Again Purchase a membership to continue enjoying our services.</p>
            <p>Thank you!</p>
        </div>
        <!-- Footer -->
        <div class="email-footer">
            <p>Sincerely,</p>
            <p>The {{ config('app.name') }} Team</p>
        </div>
    </div>
</body>

</html>
