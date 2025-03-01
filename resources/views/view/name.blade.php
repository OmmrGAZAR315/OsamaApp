<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Password Reset Request</h2>
    <p>Hello {{ $data['name'] }},</p>
    <p>You have requested to reset your password for your Furnisique account. Click the button below to proceed.</p>
    <a href="{{ url('password/reset') }}" class="btn">Reset Password</a>
    <p>If you did not request this, please ignore this email.</p>
    <p>Thank you,<br>Furnisique Team</p>
</div>
</body>
</html>
