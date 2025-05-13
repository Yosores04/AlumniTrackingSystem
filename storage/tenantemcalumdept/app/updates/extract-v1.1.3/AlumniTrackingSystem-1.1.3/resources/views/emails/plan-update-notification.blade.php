<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plan Updated</title>
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
            background-color: #4338ca;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-premium {
            background-color: #8b5cf6;
            color: white;
        }
        .badge-basic {
            background-color: #3b82f6;
            color: white;
        }
        .badge-free {
            background-color: #6b7280;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Subscription Plan Updated</h1>
        </div>
        <div class="content">
            <div class="section">
                <p>Your subscription plan has been updated by the system administrator.</p>
            </div>
            
            <div class="section">
                <h2 class="section-title">Subscription Details</h2>
                <div class="details">
                    <p><span class="label">Previous Plan:</span> 
                        <span class="badge badge-{{ strtolower($data['previous_plan']) }}">{{ $data['previous_plan'] }}</span>
                    </p>
                    <p><span class="label">New Plan:</span> 
                        <span class="badge badge-{{ strtolower($data['new_plan']) }}">{{ $data['new_plan'] }}</span>
                    </p>
                    <p><span class="label">Effective Date:</span> {{ $data['effective_date'] }}</p>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">What's Included</h2>
                <div class="details">
                    @if($data['new_plan'] == 'Premium')
                        <p>Your Premium plan includes:</p>
                        <ul>
                            <li><strong>All Text Customizations:</strong> Full control over all text elements in your site</li>
                            <li><strong>Color Customization:</strong> Complete color scheme customization</li>
                            <li><strong>Media Features:</strong> Logo & background image uploads</li>
                            <li><strong>Social Media Integration:</strong> Connect all your organization's social platforms</li>
                            <li><strong>Advanced Reporting:</strong> Comprehensive alumni analytics and insights</li>
                            <li><strong>Priority Support:</strong> Get help within 24 hours</li>
                        </ul>
                        <p class="mt-3 text-sm">This upgrade gives you access to <strong>all premium features</strong> of the Alumni Tracking System.</p>
                    @elseif($data['new_plan'] == 'Basic')
                        <p>Your Basic plan includes:</p>
                        <ul>
                            <li><strong>Text Customizations:</strong> Essential text editing capabilities</li>
                            <li><strong>Color Scheme:</strong> Basic color customization options</li>
                            <li><strong>Standard Reporting:</strong> Access to essential alumni reports</li>
                            <li><strong>Standard Support:</strong> Get help within 48 hours</li>
                        </ul>
                        <p class="mt-3 text-sm">Consider upgrading to Premium for advanced features like media uploads and social integration.</p>
                    @else
                        <p>Your Free plan includes:</p>
                        <ul>
                            <li><strong>Basic Text Editing:</strong> Limited text customization</li>
                            <li><strong>Default Design:</strong> Standard system appearance</li>
                            <li><strong>Basic Reports:</strong> Limited reporting capabilities</li>
                            <li><strong>Community Support:</strong> Access to documentation and forums</li>
                        </ul>
                        <p class="mt-3 text-sm">Upgrade to Basic or Premium to unlock more features and customization options.</p>
                    @endif
                </div>
            </div>
            
            @if(isset($data['admin_message']) && !empty($data['admin_message']))
            <div class="section">
                <h2 class="section-title">Message from Administrator</h2>
                <div class="details">
                    <p>{{ $data['admin_message'] }}</p>
                </div>
            </div>
            @else
            <div class="section">
                <h2 class="section-title">Plan Upgrade Information</h2>
                <div class="details">
                    <p>Your subscription has been upgraded by the system administrator from <strong>{{ $data['previous_plan'] }}</strong> to <strong>{{ $data['new_plan'] }}</strong>.</p>
                    <p>This change is effective immediately, and you now have access to all features included in your new plan.</p>
                    <p>Visit your settings page to explore the new features and customization options available to you.</p>
                </div>
            </div>
            @endif
            
            <div class="section" style="text-align: center;">
                <a href="{{ $data['login_url'] }}" class="button">Go to Settings</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated message from the Alumni Tracking System.</p>
        </div>
    </div>
</body>
</html> 