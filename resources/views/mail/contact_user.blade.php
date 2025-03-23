<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>

<body>
    <div class="email-container">
        <div class="email-header" style="font-weight: bold">Confirmation of Receipt - International MAOI Expert Group
        </div>
        <br>
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
