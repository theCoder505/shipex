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
                                Admin Account Reset — One‑Time Password (OTP)
                            </h2>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 20px; text-align: center;">
                                Hello <strong style="color: #111827;">{{ $admin_name ?? 'Admin' }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 30px; text-align: center;">
                                We generated a 6‑digit One‑Time Password (OTP) for your {{ $brandname }} admin account. Enter the OTP where prompted to complete your password reset.
                            </p>

                            <div style="text-align: center; margin: 24px 0;">
                                <div style="display: inline-block; background: #111827; color: #ffffff; font-weight: 700; padding: 18px 28px; border-radius: 8px; font-size: 28px; letter-spacing: 6px;">
                                    {{ $otp }}
                                </div>
                            </div>

                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
                                <p style="font-size: 14px; color: #92400e; margin: 0;">
                                    <strong>Note:</strong> This OTP will expire in 30 minutes. If you did not request this, please ignore this email or contact support immediately.
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #6b7280; text-align: center; margin-bottom: 6px;">
                                For security, do not share this OTP with anyone.
                            </p>

                            <p style="font-size: 13px; color: #6b7280; text-align: center; margin-top: 10px;">
                                If you need assistance, contact support at <a href="mailto:{{ $support_email ?? 'support@example.com' }}" style="color: #2563eb; text-decoration: none;">{{ $support_email ?? 'support@example.com' }}</a>.
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
