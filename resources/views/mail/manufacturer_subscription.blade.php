<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIPEX - Account Status Notification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 8px;
        }
        .header-title {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .success-icon {
            text-align: center;
            margin: 30px 0;
        }
        .success-icon svg {
            width: 80px;
            height: 80px;
        }
        .warning-icon {
            text-align: center;
            margin: 30px 0;
        }
        .warning-icon svg {
            width: 80px;
            height: 80px;
        }
        .info-icon {
            text-align: center;
            margin: 30px 0;
        }
        .info-icon svg {
            width: 80px;
            height: 80px;
        }
        .info-box {
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .info-box h3 {
            color: #2d3748;
            font-size: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .info-box p {
            color: #4a5568;
            font-size: 15px;
            margin: 8px 0;
        }
        .subscription-plans {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .plan-card {
            flex: 1;
            min-width: 150px;
            background: white;
            border-radius: 8px;
            padding: 20px 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .plan-card:hover {
            transform: translateY(-5px);
            border-color: #667eea;
        }
        .plan-card.recommended {
            border-color: #48bb78;
            position: relative;
        }
        .plan-card.recommended::before {
            content: "Recommended";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: #48bb78;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .plan-name {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }
        .plan-amount {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        .plan-period {
            font-size: 14px;
            color: #718096;
        }
        .admin-comment-box {
            background-color: #fffaf0;
            border-left: 4px solid #d69e2e;
            padding: 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .admin-comment-box h3 {
            color: #744210;
            font-size: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .admin-comment-box p {
            color: #744210;
            font-size: 15px;
            margin: 8px 0;
            font-style: italic;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff!important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .cta-button-warning {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            box-shadow: 0 4px 15px rgba(237, 137, 54, 0.4);
        }
        .cta-button-warning:hover {
            box-shadow: 0 6px 20px rgba(237, 137, 54, 0.6);
        }
        .cta-button-danger {
            background: linear-gradient(135deg, #f56565 0%, #c53030 100%);
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
        }
        .cta-button-danger:hover {
            box-shadow: 0 6px 20px rgba(245, 101, 101, 0.6);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #cbd5e0, transparent);
            margin: 30px 0;
        }
        .footer {
            background-color: #2d3748;
            color: #a0aec0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 8px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #a0aec0;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 30px 20px;
            }
            .header-title {
                font-size: 24px;
            }
            .cta-button {
                padding: 14px 30px;
                font-size: 15px;
            }
            .subscription-plans {
                flex-direction: column;
            }
            .plan-card {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ $brandlogo ?? 'logo.png' }}" alt="Brand Logo" class="logo">
            <h1 class="header-title">Account Status Update</h1>
        </div>

        <!-- Content -->
        <div class="content">
            @if($status == 5)
                <!-- Approval Status -->
                <div class="success-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="45" fill="#48bb78" opacity="0.2"/>
                        <circle cx="50" cy="50" r="35" fill="#48bb78" opacity="0.4"/>
                        <path d="M30 50 L45 65 L70 35" stroke="#ffffff" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h2 class="greeting">Hello {{ $company_name }},</h2>
                
                <p class="message">
                    We are thrilled to inform you that your application to join <strong>{{ $brandname ?? 'SHIPEX' }}</strong> has been <strong style="color: #48bb78;">approved</strong> by our admin team!
                </p>

                <p class="message">
                    Welcome to our growing network of trusted manufacturers. We're excited to have you on board and look forward to a successful partnership.
                </p>

                @if(!empty($admin_comment))
                <div class="admin-comment-box">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke="#d69e2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Application Review Summary
                    </h3>
                    <p>{{ $admin_comment }}</p>
                </div>
                @endif

                <div class="info-box">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Next Step: Choose Your Subscription Plan
                    </h3>
                    <p>To activate your account and start using our platform, please choose and complete your subscription payment.</p>
                    
                    <div class="subscription-plans">
                        <div class="plan-card">
                            <div class="plan-name">Monthly</div>
                            <div class="plan-amount">${{ number_format($monthly_fee_amount, 2) }}</div>
                            <div class="plan-period">per month</div>
                        </div>
                        <div class="plan-card recommended">
                            <div class="plan-name">Half Yearly</div>
                            <div class="plan-amount">${{ number_format($half_yearly_fee_amount, 2) }}</div>
                            <div class="plan-period">6 months</div>
                        </div>
                        <div class="plan-card">
                            <div class="plan-name">Yearly</div>
                            <div class="plan-amount">${{ number_format($yearly_fee_amount, 2) }}</div>
                            <div class="plan-period">per year</div>
                        </div>
                    </div>
                </div>

                <p class="message">
                    Click the button below to view all subscription plans and choose the one that works best for you:
                </p>

                <div class="button-container">
                    <a href="{{ url('/') }}/manufacturer/packages" class="cta-button">
                        Choose Subscription Plan
                    </a>
                </div>

                <p class="message" style="font-size: 14px; color: #718096;">
                    If you have any questions or need assistance with the subscription process, our support team is here to help.
                </p>

            @elseif($status == 1)
                <!-- Under Review Status -->
                <div class="info-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="45" fill="#4299e1" opacity="0.2"/>
                        <circle cx="50" cy="50" r="35" fill="#4299e1" opacity="0.4"/>
                        <path d="M50 30 L50 55 M50 65 L50 70" stroke="#ffffff" stroke-width="6" stroke-linecap="round"/>
                        <circle cx="50" cy="40" r="3" fill="#ffffff"/>
                    </svg>
                </div>

                <h2 class="greeting">Hello {{ $company_name }},</h2>
                
                <p class="message">
                    Your application to join <strong>{{ $brandname ?? 'SHIPEX' }}</strong> is currently <strong style="color: #4299e1;">under review</strong> by our admin team.
                </p>

                <p class="message">
                    We are carefully evaluating your application details. Your account has been kept as <strong>verified</strong> during this review process.
                </p>

                @if(!empty($admin_comment))
                <div class="admin-comment-box">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke="#d69e2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Application Review Summary
                    </h3>
                    <p>{{ $admin_comment }}</p>
                </div>
                @endif

                <div class="info-box" style="border-left-color: #4299e1;">
                    <h3 style="color: #2b6cb0;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke="#2b6cb0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Account Status: Verified & Under Review
                    </h3>
                    <p>Your account remains verified while our team completes the review process. We'll notify you as soon as a decision has been made.</p>
                </div>

                <p class="message">
                    This process typically takes 2-3 business days. You'll receive another notification once the review is complete.
                </p>

                <p class="message" style="font-size: 14px; color: #718096;">
                    If you have any questions about the review process, feel free to contact our support team.
                </p>

            @elseif($status == 3)
                <!-- Rejected Status -->
                <div class="warning-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="45" fill="#f56565" opacity="0.2"/>
                        <circle cx="50" cy="50" r="35" fill="#f56565" opacity="0.4"/>
                        <path d="M35 35 L65 65 M65 35 L35 65" stroke="#ffffff" stroke-width="6" stroke-linecap="round"/>
                    </svg>
                </div>

                <h2 class="greeting">Hello {{ $company_name }},</h2>
                
                <p class="message">
                    We regret to inform you that your application to join <strong>{{ $brandname ?? 'SHIPEX' }}</strong> has been <strong style="color: #f56565;">rejected</strong> by our admin team.
                </p>

                @if(!empty($admin_comment))
                <div class="admin-comment-box">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke="#d69e2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Application Review Summary
                    </h3>
                    <p>{{ $admin_comment }}</p>
                </div>
                @endif

                <div class="info-box" style="border-left-color: #f56565; background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);">
                    <h3 style="color: #c53030;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="#c53030" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Application Status: Rejected
                    </h3>
                    <p>Unfortunately, your application did not meet our current requirements. This could be due to incomplete information, policy violations, or other concerns identified during review.</p>
                </div>

                <p class="message">
                    We encourage you to review your application details and submit a new application if you believe you can address the issues that led to this decision.
                </p>

                <div class="button-container">
                    <a href="{{ url('/') }}/manufacturer/application" class="cta-button cta-button-warning">
                        Submit New Application
                    </a>
                </div>

                <p class="message" style="font-size: 14px; color: #718096;">
                    If you have questions about why your application was rejected or need assistance with the reapplication process, please contact our support team.
                </p>

            @else
                <!-- Default/Other Status -->
                <div class="warning-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="45" fill="#a0aec0" opacity="0.2"/>
                        <circle cx="50" cy="50" r="35" fill="#a0aec0" opacity="0.4"/>
                        <path d="M50 30 L50 55 M50 65 L50 70" stroke="#ffffff" stroke-width="6" stroke-linecap="round"/>
                    </svg>
                </div>

                <h2 class="greeting">Hello {{ $company_name }},</h2>
                
                <p class="message">
                    Your account status on <strong>{{ $brandname ?? 'SHIPEX' }}</strong> has been updated.
                </p>

                @if(!empty($admin_comment))
                <div class="admin-comment-box">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke="#d69e2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Application Review Summary
                    </h3>
                    <p>{{ $admin_comment }}</p>
                </div>
                @endif

                <div class="info-box" style="border-left-color: #a0aec0;">
                    <h3 style="color: #4a5568;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#4a5568" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Account Status Update
                    </h3>
                    <p>Your account status has been changed to: <strong>{{ $status }}</strong></p>
                </div>

                <p class="message">
                    For more information about this status change, please contact our support team.
                </p>

                <div class="button-container">
                    <a href="{{ url('/') }}/contact-us" class="cta-button">
                        Contact Support
                    </a>
                </div>

            @endif

            <div class="divider"></div>

            <p class="message" style="font-size: 14px; color: #718096; text-align: center;">
                This is an automated notification. Please do not reply directly to this email.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $brandname ?? 'SHIPEX' }}</strong></p>
            <p>&copy; {{ date('Y') }} {{ $brandname ?? 'SHIPEX' }}. All rights reserved.</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Visit our website</a> | 
                <a href="{{ url('/') }}/contact-us">Contact Us</a> | 
                <a href="{{ url('/') }}/privacy-policy">Privacy Policy</a>
            </p>
        </div>
    </div>
</body>
</html>