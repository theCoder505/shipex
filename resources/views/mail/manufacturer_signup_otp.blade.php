<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$brandname}} - OTP Verification</title>
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
                        <td style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px 20px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 0;">
                                {{$brandname}}
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
                                Your Manufacturer Signup Verified!
                            </h2>
                            
                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Hello <strong style="color: #111827;">{{ $email }}</strong>,
                            </p>
                            
                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
                                Thank you for joining {{$brandname}}. Please use the following One-Time Password (OTP) to verify your email address:
                            </p>
                            
                            <div style="background: linear-gradient(135deg, #f3f4f6 0%, #f9fafb 100%); border-radius: 12px; padding: 25px; text-align: center; margin: 0 auto 30px; max-width: 300px; border: 1px solid #e5e7eb;">
                                <div style="font-size: 14px; color: #6b7280; margin-bottom: 12px;">Your verification code:</div>
                                <div style="font-size: 36px; font-weight: 700; letter-spacing: 4px; color: #1e3a8a; padding: 10px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">{{ $otp }}</div>
                            </div>
                            
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
                                <p style="font-size: 14px; color: #92400e; margin: 0;">
                                    <strong>Note:</strong> This code will expire in 10 minutes. If you didn't request this, please ignore this email.
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin-top: 30px;">
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">
                                    Having trouble with the code?
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 25px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="font-size: 12px; color: #9ca3af; margin: 0 0 10px 0;">
                                &copy; {{ date('Y') }} {{$brandname}}. All rights reserved.
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