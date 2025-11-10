<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brandname }} - Account Status Update</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
</head>

<body style="font-family: 'Inter', Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px 20px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 0;">
                                {{ $brandname }}
                            </h1>
                            <p style="color: #dbeafe; font-size: 16px; margin: 8px 0 0 0;">
                                Manufacturer & Wholesaler Relationship Management System
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2
                                style="color: #1f2937; font-size: 22px; font-weight: 600; margin-bottom: 20px; text-align: center;">
                                Account Status Update Notification
                            </h2>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Hello <strong style="color: #111827;">{{ $company_name }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
                                We are writing to inform you about an important update to your wholesaler account on
                                {{ $brandname }}.
                            </p>

                            <!-- Account Details -->
                            <div
                                style="background-color: #f9fafb; border-radius: 12px; padding: 25px; margin-bottom: 25px; border: 1px solid #e5e7eb;">
                                <h3 style="color: #1f2937; font-size: 18px; font-weight: 600; margin: 0 0 15px 0;">
                                    Account Information
                                </h3>
                                <table width="100%" cellpadding="8" cellspacing="0">
                                    <tr>
                                        <td style="font-size: 14px; color: #6b7280; padding: 8px 0;">
                                            <strong>Wholesaler UID:</strong>
                                        </td>
                                        <td style="font-size: 14px; color: #111827; padding: 8px 0; text-align: right;">
                                            {{ $wholesaler_uid }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; color: #6b7280; padding: 8px 0;">
                                            <strong>Email:</strong>
                                        </td>
                                        <td style="font-size: 14px; color: #111827; padding: 8px 0; text-align: right;">
                                            {{ $email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; color: #6b7280; padding: 8px 0;">
                                            <strong>Current Status:</strong>
                                        </td>
                                        <td style="font-size: 14px; padding: 8px 0; text-align: right;">
                                            @if ($action_type === 'restrict')
                                                <span
                                                    style="color: #dc2626; font-weight: 600;">{{ $status_text }}</span>
                                            @else
                                                <span
                                                    style="color: #16a34a; font-weight: 600;">{{ $status_text }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Status Alert Box -->
                            @if ($action_type === 'restrict')
                                <div
                                    style="background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 20px; margin-bottom: 25px; border-radius: 0 8px 8px 0;">
                                    <h4 style="color: #991b1b; font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">
                                        ⚠️ Account Restricted
                                    </h4>
                                    <p style="font-size: 14px; color: #7f1d1d; margin: 0; line-height: 1.6;">
                                        Your account has been temporarily restricted by our administrative team. You
                                        will not be able to access certain features or perform transactions until this
                                        restriction is lifted.
                                    </p>
                                </div>
                            @else
                                <div
                                    style="background-color: #f0fdf4; border-left: 4px solid #16a34a; padding: 20px; margin-bottom: 25px; border-radius: 0 8px 8px 0;">
                                    <h4 style="color: #15803d; font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">
                                        ✓ Account Unrestricted
                                    </h4>
                                    <p style="font-size: 14px; color: #166534; margin: 0; line-height: 1.6;">
                                        Great news! Your account restrictions have been lifted. You now have full access
                                        to all features and can resume normal business operations.
                                    </p>
                                </div>
                            @endif

                            <!-- Admin Comment -->
                            <div
                                style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
                                <h4 style="color: #92400e; font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">
                                    📋 Administrator's Comment
                                </h4>
                                <p
                                    style="font-size: 14px; color: #78350f; margin: 0; line-height: 1.6; font-style: italic;">
                                    "{{ $admin_comment }}"
                                </p>
                            </div>

                            <!-- Next Steps -->
                            <div
                                style="background: linear-gradient(135deg, #f3f4f6 0%, #f9fafb 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                                <h4 style="color: #1f2937; font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
                                    What should you do next?
                                </h4>
                                @if ($action_type === 'restrict')
                                    <ul
                                        style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.8;">
                                        <li>Review the administrator's comment carefully</li>
                                        <li>Take necessary actions to address any concerns mentioned</li>
                                        <li>Contact our support team if you need clarification</li>
                                        <li>Wait for further communication regarding account restoration</li>
                                    </ul>
                                @else
                                    <ul
                                        style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.8;">
                                        <li>Log in to your account and resume your business activities</li>
                                        <li>Review any pending orders or transactions</li>
                                        <li>Update your profile information if needed</li>
                                        <li>Continue following our platform guidelines and policies</li>
                                    </ul>
                                @endif
                            </div>

                            <!-- Support Section -->
                            <div style="text-align: center; margin-top: 30px;">
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 15px;">
                                    Need assistance or have questions?
                                </p>
                                <p style="font-size: 14px; color: #4b5563; margin: 0;">
                                    Contact our support team at <a href="mailto:{{ $contact_mail }}"
                                        style="color: #3b82f6; text-decoration: none; font-weight: 500;">{{ $contact_mail }}</a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f9fafb; padding: 25px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="font-size: 12px; color: #9ca3af; margin: 0 0 10px 0;">
                                &copy; {{ date('Y') }} {{ $brandname }}. All rights reserved.
                            </p>
                            <p style="font-size: 12px; color: #6b7280; margin: 0 0 15px 0;">
                                Manufacturer & Wholesaler Relationship Management System
                            </p>
                            <p style="font-size: 11px; color: #9ca3af; margin: 0;">
                                This is an automated notification. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
