<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Alumni Tracking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <h1>Welcome to Alumni Tracking System</h1>
    
    <p>Dear {{ $emailData['name'] }},</p>
    
    <p>An administrator has created an account for you in our Alumni Tracking System. You can now log in with the following credentials:</p>
    
    <p><strong>Email:</strong> {{ $emailData['email'] }}</p>
    <p><strong>Password:</strong> {{ $emailData['password'] }}</p>
    
    <p><strong>Important:</strong> For security reasons, you will be required to change your password upon first login.</p>
    
    <p>To access your account, please visit: <a href="{{ $emailData['login_url'] }}">{{ $emailData['login_url'] }}</a></p>
    
    <p>If you have any questions or need assistance, please contact the administrator.</p>
    
    <p>This is an automated message, please do not reply directly to this email.</p>
</body>
</html> 