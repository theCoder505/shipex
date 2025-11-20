<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brandname }} - New Conversation Started</title>
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
                                New Conversation Started
                            </h2>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Hello <strong style="color: #111827;">{{ $receiver_name }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
                                You have received a new message from <strong>{{ $sender_name }}</strong> 
                                @if($sender_type === 'wholesaler')
                                    (Wholesaler)
                                @else
                                    (Manufacturer)
                                @endif
                                on {{ $brandname }}.
                            </p>

                            <!-- Message Preview -->
                            <div
                                style="background-color: #f8fafc; border-radius: 12px; padding: 25px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
                                <h3 style="color: #1f2937; font-size: 18px; font-weight: 600; margin: 0 0 15px 0;">
                                    Message Preview
                                </h3>
                                <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                                    <p style="font-size: 16px; color: #374151; line-height: 1.6; margin: 0; font-style: italic;">
                                        "{{ $message_preview }}"
                                    </p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div style="text-align: center; margin: 35px 0;">
                                <a href="{{ $chat_url }}"
                                    style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                    Reply to Message
                                </a>
                            </div>

                            <!-- Important Note -->
                            <div
                                style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin-bottom: 25px; border-radius: 0 8px 8px 0;">
                                <h4 style="color: #92400e; font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">
                                    💡 Important
                                </h4>
                                <p style="font-size: 14px; color: #78350f; margin: 0; line-height: 1.6;">
                                    This is the first message from {{ $sender_name }}. Respond promptly to build a good business relationship and explore potential opportunities.
                                </p>
                            </div>

                            <!-- Next Steps -->
                            <div
                                style="background: linear-gradient(135deg, #f3f4f6 0%, #f9fafb 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                                <h4 style="color: #1f2937; font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
                                    What to do next?
                                </h4>
                                <ul
                                    style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.8;">
                                    <li>Click the "Reply to Message" button above to respond</li>
                                    <li>Be professional and clear in your communication</li>
                                    <li>Discuss your business requirements and capabilities</li>
                                    <li>Build a strong business relationship</li>
                                </ul>
                            </div>

                            <!-- Support Section -->
                            <div style="text-align: center; margin-top: 30px;">
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 15px;">
                                    Need help with messaging?
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