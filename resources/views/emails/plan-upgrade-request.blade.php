<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Upgrade Request</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            margin: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #4338ca;
            color: white;
            padding: 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: 600;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            color: #4338ca;
        }
        .details {
            background-color: #f5f7ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .label {
            font-weight: 600;
            display: inline-block;
            width: 140px;
        }
        .footer {
            padding: 15px;
            text-align: center;
            background-color: #f5f5f5;
            color: #666;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            background-color:rgb(204, 202, 222);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Plan Upgrade Request</h1>
        </div>
        <div class="content">
            <div class="section">
                <p>A tenant has requested to upgrade their subscription plan.</p>
            </div>
            
            <div class="section">
                <h2 class="section-title">Tenant Information</h2>
                <div class="details">
                    <p><span class="label">Tenant Name:</span> {{ $data['tenant_name'] }}</p>
                    <p><span class="label">Tenant ID:</span> {{ $data['tenant_id'] }}</p>
                    <p><span class="label">Domain:</span> {{ $data['domain'] }}</p>
                    <p><span class="label">Current Plan:</span> {{ $data['current_plan'] }}</p>
                    <p><span class="label">Requested Plan:</span> {{ $data['requested_plan'] }}</p>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">Requester Information</h2>
                <div class="details">
                    <p><span class="label">Name:</span> {{ $data['requester_name'] }}</p>
                    <p><span class="label">Email:</span> {{ $data['requester_email'] }}</p>
                    <p><span class="label">Request Time:</span> {{ $data['request_time'] }}</p>
                </div>
            </div>
            
            @if($data['request_details'])
            <div class="section">
                <h2 class="section-title">Additional Details</h2>
                <div class="details">
                    <p>{{ $data['request_details'] }}</p>
                </div>
            </div>
            @endif
            
            <div class="section" style="text-align: center;">
                <a href="http://127.0.0.1:8000/tenants/create?tab=list" class="button">Manage Tenants</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated message from the Alumni Tracking System.</p>
        </div>
    </div>
</body>
</html> 