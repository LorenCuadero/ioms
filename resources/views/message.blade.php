<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>OTP Message</title>
</head>

<body>
    <p>Hello {{ $email }},</p>
    <p>You have requested a one-time password (OTP) to access your account. Your OTP is: <b>{{ $otp }}</b>.
        Please use this OTP to complete your verification.</p>
    <p>If you did not request this OTP, please ignore this message.</p>
    <p>Thank you,<br>Passerelles Numeriques Philippines</p>
</body>

</html>
