<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brandname }} - New User Registered</title>
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

                    <!-- Alert Badge -->
                    <tr>
                        <td style="padding: 30px 30px 0 30px; text-align: center;">
                            <span style="display: inline-block; background-color: #dcfce7; color: #166534; font-size: 13px; font-weight: 600; padding: 6px 16px; border-radius: 999px; letter-spacing: 0.5px; text-transform: uppercase;">
                                🎉 New Registration
                            </span>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 24px 30px 30px 30px;">
                            <h2 style="color: #1f2937; font-size: 22px; font-weight: 600; margin-bottom: 10px; text-align: center;">
                                A New {{ ucfirst($usertype ?? 'User') }} Has Registered
                            </h2>

                            <p style="font-size: 15px; color: #4b5563; line-height: 1.7; margin-bottom: 24px; text-align: center;">
                                A new <strong>{{ $usertype ?? 'user' }}</strong> has just registered on <strong>{{ $brandname }}</strong> and successfully completed their profile details.
                            </p>

                            <!-- User Details Card -->
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <p style="font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.6px; margin: 0 0 16px 0;">
                                            User Details
                                        </p>

                                        <!-- Name -->
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 12px;">
                                            <tr>
                                                <td style="font-size: 14px; color: #6b7280; padding: 8px 0;">Email Address</td>
                                                <td style="font-size: 14px; color: #2563eb; font-weight: 500; padding: 8px 0;">
                                                    <a href="mailto:{{ $useremail ?? '' }}" style="color: #2563eb; text-decoration: none;">{{ $useremail ?? 'N/A' }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #e5e7eb; padding: 0;"></td>
                                            </tr>

                                            <!-- Company -->
                                            <tr>
                                                <td style="font-size: 14px; color: #6b7280; padding: 8px 0;">Company Name</td>
                                                <td style="font-size: 14px; color: #111827; font-weight: 600; padding: 8px 0;">{{ $company_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #e5e7eb; padding: 0;"></td>
                                            </tr>

                                            <!-- User Type -->
                                            <tr>
                                                <td style="font-size: 14px; color: #6b7280; padding: 8px 0;">Account Type</td>
                                                <td style="font-size: 14px; color: #111827; padding: 8px 0;">
                                                    <span style="display: inline-block; background-color: #dbeafe; color: #1e40af; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 999px; text-transform: capitalize;">
                                                        {{ $usertype ?? 'user' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-bottom: 1px solid #e5e7eb; padding: 0;"></td>
                                            </tr>

                                            <!-- Registration Date -->
                                            <tr>
                                                <td style="font-size: 14px; color: #6b7280; padding: 8px 0;">Registered At</td>
                                                <td style="font-size: 14px; color: #111827; font-weight: 500; padding: 8px 0;">{{ $registered_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Note -->
                            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 14px 16px; margin-bottom: 24px; border-radius: 0 8px 8px 0;">
                                <p style="font-size: 14px; color: #1e40af; margin: 0; line-height: 1.6;">
                                    <strong>Action Required:</strong> Please log in to the admin panel to review and approve or manage this new {{ $usertype ?? 'user' }}'s account.
                                </p>
                            </div>

                            <p style="font-size: 13px; color: #6b7280; text-align: center; margin: 0;">
                                This is an automated notification from <strong>{{ $brandname }}</strong>. Please do not reply to this email.
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