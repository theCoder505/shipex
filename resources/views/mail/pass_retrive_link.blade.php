<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brandname }} - Password Reset</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
</head>

<body style="font-family: 'Inter', Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px 20px; text-align: center;">
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
                            <h2 style="color: #1f2937; font-size: 22px; font-weight: 600; margin-bottom: 20px; text-align: center;">
                                Reset Your Password
                            </h2>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Hello <strong style="color: #111827;">{{ $company_name }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
                                We received a request to reset your password for your {{ $brandname }} account. Click the button below to reset your password securely.
                            </p>

                            <div style="text-align: center; margin: 40px 0;">
                                <a href="{{ $verification_link }}" 
                                    style="display: inline-block; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color: #ffffff; text-decoration: none; font-weight: 600; padding: 14px 36px; border-radius: 8px; font-size: 16px;">
                                    Reset Password
                                </a>
                            </div>

                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
                                <p style="font-size: 14px; color: #92400e; margin: 0;">
                                    <strong>Note:</strong> This password reset link will expire in 30 minutes. If you did not request a password reset, please ignore this email.
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #6b7280; text-align: center;">
                                If the button doesn’t work, copy and paste the following URL into your browser:
                            </p>

                            <p style="font-size: 13px; color: #3b82f6; word-break: break-all; text-align: center; margin-top: 10px;">
                                <a href="{{ $verification_link }}" style="color: #2563eb; text-decoration: none;">{{ $verification_link }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 25px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="font-size: 12px; color: #9ca3af; margin: 0 0 10px 0;">
                                &copy; {{ date('Y') }} {{ $brandname }}. All rights reserved.
                            </p>
                            <p style="font-size: 12px; color: #6b7280; margin: 0;">
                                Manufacturer & Wholesaler Relationship Management System
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
