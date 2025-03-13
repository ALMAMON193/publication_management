<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
    <div style="margin: 50px auto; width: 70%; padding: 20px 0; font-family: Arial, sans-serif; color: #333;">
        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
            <a href="{{ url('/') }}"
                style="font-size: 1.4em; color: #00466a; text-decoration: none; font-weight: 600;">
                {{ htmlspecialchars($header_message) }}
            </a>
        </div>

        <p style="font-size: 1.1em; margin-bottom: 20px;">Dear {{ $user->name }},</p>

        <p style="font-size: 1.1em; line-height: 1.6;">
            Thank you for choosing <strong>{{ config('app.name') }}</strong>. To complete your sign-up process, please
            use the following One Time Password One Time Passowrd (OTP).
            This OTP is valid for 1 hour from the time of this email.
        </p>

        <h2
            style="background: #00466a; margin: 20px auto; padding: 10px 20px; color: #fff; border-radius: 4px; font-size: 1.5em; text-align: center;">
            {{ $otp }}
        </h2>

        <p style="font-size: 1.1em; line-height: 1.6;">If you did not request this One Time Password (OTP), please ignore
            this email.</p>

        <p style="font-size: 1.1em; line-height: 1.6;">Regards,</p>
        <p style="font-size: 1.1em; font-weight: bold;">The {{ config('app.name') }} Team</p>

        <hr style="border: none; border-top: 1px solid #eee; margin-top: 40px;">

        <div style="float: right; padding: 8px 0; color: #aaa; font-size: 0.8em; line-height: 1; font-weight: 300;">
            <p style="margin: 0;">{{ config('app.name') }} Inc.</p>
            <p style="margin: 0;">All rights reserved. {{ date('Y') }}</p>
        </div>
    </div>

</div>
