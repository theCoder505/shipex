<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brandname }} - New Message</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
</head>

<body style="font-family: 'Inter', Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px 20px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 0;">
                                {{ $brandname }}
                            </h1>
                            <p style="color: #dbeafe; font-size: 16px; margin: 8px 0 0 0;">
                                Manufacturer &amp; Wholesaler Relationship Management System
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">

                            {{-- ── Dynamic heading based on first/subsequent message ── --}}
                            <h2 style="color: #1f2937; font-size: 22px; font-weight: 600; margin-bottom: 20px; text-align: center;">
                                @if($is_first_message)
                                    🎉 New Conversation Started
                                @else
                                    💬 New Message Received
                                @endif
                            </h2>

                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Hello <strong style="color: #111827;">{{ $receiver_name }}</strong>,
                            </p>

                            {{-- ── Context sentence ── --}}
                            <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
                                @if($is_first_message)
                                    <strong>{{ $sender_name }}</strong>
                                    ({{ $sender_type === 'wholesaler' ? 'Wholesaler' : 'Manufacturer' }})
                                    has started a <strong>new conversation</strong> with you on {{ $brandname }}.
                                    This is their first message — reply promptly to start building a strong business relationship!
                                @else
                                    You have received a new message from
                                    <strong>{{ $sender_name }}</strong>
                                    ({{ $sender_type === 'wholesaler' ? 'Wholesaler' : 'Manufacturer' }})
                                    on {{ $brandname }}.
                                @endif
                            </p>

                            {{-- ── Message / File block ── --}}
                            @if($message_type === 'file')

                                {{-- File message --}}
                                <div style="background-color: #f0f9ff; border-radius: 12px; padding: 25px; margin-bottom: 25px; border: 1px solid #bae6fd;">
                                    <h3 style="color: #0369a1; font-size: 17px; font-weight: 600; margin: 0 0 15px 0;">
                                        📎 File Sent
                                    </h3>

                                    {{-- File type badge --}}
                                    <div style="display:inline-block; background-color:#0ea5e9; color:#ffffff; font-size:13px; font-weight:600; padding:4px 12px; border-radius:20px; margin-bottom:14px;">
                                        {{ $file_type_label }}
                                    </div>

                                    {{-- File name --}}
                                    <div style="background-color:#ffffff; border:1px solid #e0f2fe; border-radius:8px; padding:14px 18px; margin-bottom:12px;">
                                        <p style="margin:0; font-size:14px; color:#374151;">
                                            <span style="color:#6b7280;">File name:</span>&nbsp;
                                            <strong style="color:#111827;">{{ $file_data['original_name'] ?? 'Unknown file' }}</strong>
                                        </p>
                                    </div>

                                    {{-- Optional caption --}}
                                    @if($message_preview && $message_preview !== 'No additional message.')
                                        <div style="background-color:#ffffff; border:1px solid #e0f2fe; border-radius:8px; padding:14px 18px;">
                                            <p style="margin:0 0 6px 0; font-size:13px; color:#6b7280; font-weight:500;">Caption / Message:</p>
                                            <p style="margin:0; font-size:15px; color:#374151; line-height:1.6; font-style:italic;">
                                                "{{ $message_preview }}"
                                            </p>
                                        </div>
                                    @endif
                                </div>

                            @else

                                {{-- Text message --}}
                                <div style="background-color: #f8fafc; border-radius: 12px; padding: 25px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
                                    <h3 style="color: #1f2937; font-size: 17px; font-weight: 600; margin: 0 0 15px 0;">
                                        💬 Message Preview
                                    </h3>
                                    <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                                        <p style="font-size: 15px; color: #374151; line-height: 1.6; margin: 0; font-style: italic;">
                                            "{{ $message_preview }}"
                                        </p>
                                    </div>
                                </div>

                            @endif

                            {{-- ── Action Button ── --}}
                            <div style="text-align: center; margin: 35px 0;">
                                <a href="{{ $chat_url }}"
                                    style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                    {{ $message_type === 'file' ? 'View File in Chat' : 'Reply to Message' }}
                                </a>
                            </div>

                            {{-- ── First-message callout ── --}}
                            @if($is_first_message)
                            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin-bottom: 25px; border-radius: 0 8px 8px 0;">
                                <h4 style="color: #92400e; font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">
                                    💡 First Message — Make It Count!
                                </h4>
                                <p style="font-size: 14px; color: #78350f; margin: 0; line-height: 1.6;">
                                    This is the very first message from <strong>{{ $sender_name }}</strong>.
                                    Respond promptly and professionally to explore potential business opportunities.
                                </p>
                            </div>
                            @endif

                            {{-- ── Next steps ── --}}
                            <div style="background: linear-gradient(135deg, #f3f4f6 0%, #f9fafb 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                                <h4 style="color: #1f2937; font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
                                    What to do next?
                                </h4>
                                <ul style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.8;">
                                    @if($message_type === 'file')
                                        <li>Click <strong>"View File in Chat"</strong> to open and download the file</li>
                                        <li>Review the {{ $file_type_label }} sent by {{ $sender_name }}</li>
                                    @else
                                        <li>Click <strong>"Reply to Message"</strong> above to respond</li>
                                        <li>Be professional and clear in your communication</li>
                                    @endif
                                    <li>Discuss your business requirements and capabilities</li>
                                    <li>Build a strong and lasting business relationship</li>
                                </ul>
                            </div>

                            <!-- Support Section -->
                            <div style="text-align: center; margin-top: 30px;">
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 15px;">
                                    Need help with messaging?
                                </p>
                                <p style="font-size: 14px; color: #4b5563; margin: 0;">
                                    Contact our support team at
                                    <a href="mailto:{{ $contact_mail }}"
                                        style="color: #3b82f6; text-decoration: none; font-weight: 500;">{{ $contact_mail }}</a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 25px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="font-size: 12px; color: #9ca3af; margin: 0 0 10px 0;">
                                &copy; {{ date('Y') }} {{ $brandname }}. All rights reserved.
                            </p>
                            <p style="font-size: 12px; color: #6b7280; margin: 0 0 15px 0;">
                                Manufacturer &amp; Wholesaler Relationship Management System
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