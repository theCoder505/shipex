@extends('layouts.surface.app')

@section('title', 'Terms of Use')

@section('style')
    <style>
        /* Container background for better contrast */
        .terms-container {
            background: #FFFFFF;
            min-height: 100vh;
        }

        /* Custom styling for rich text content */
        .rich-content {
            line-height: 1.8;
            color: #1F2937;
        }

        .rich-content h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: #111827;
            margin-top: 2rem;
            margin-bottom: 1rem;
            line-height: 1.2;
            border-bottom: 3px solid #3B82F6;
            padding-bottom: 0.5rem;
        }

        .rich-content h2 {
            font-size: 1.875rem;
            font-weight: 600;
            color: #111827;
            margin-top: 2.5rem;
            margin-bottom: 0.875rem;
            line-height: 1.3;
            position: relative;
            padding-left: 1rem;
        }

        .rich-content h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.25rem;
            height: 1.5rem;
            width: 4px;
            background: #3B82F6;
            border-radius: 2px;
        }

        .rich-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1F2937;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .rich-content h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-top: 1.5rem;
            margin-bottom: 0.625rem;
            line-height: 1.4;
        }

        .rich-content h5 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #4B5563;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .rich-content h6 {
            font-size: 1rem;
            font-weight: 600;
            color: #6B7280;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            line-height: 1.5;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .rich-content p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
            color: #374151;
            line-height: 1.8;
        }

        .rich-content strong,
        .rich-content b {
            font-weight: 600;
            color: #111827;
        }

        .rich-content em,
        .rich-content i {
            font-style: italic;
        }

        .rich-content ul,
        .rich-content ol {
            margin-top: 1rem;
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .rich-content ul {
            list-style-type: disc;
        }

        .rich-content ol {
            list-style-type: decimal;
        }

        .rich-content ul ul,
        .rich-content ol ol {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .rich-content li {
            margin-bottom: 0.75rem;
            padding-left: 0.5rem;
            color: #374151;
            line-height: 1.8;
        }

        .rich-content li::marker {
            color: #3B82F6;
            font-weight: 600;
        }

        .rich-content a {
            color: #2563EB;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .rich-content a:hover {
            color: #1D4ED8;
            text-decoration: none;
        }

        .rich-content blockquote {
            border-left: 4px solid #3B82F6;
            padding-left: 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #4B5563;
            background: #F3F4F6;
            padding: 1rem 1.5rem;
            border-radius: 0.375rem;
        }

        .rich-content code {
            background: #F3F4F6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-family: 'Courier New', monospace;
            color: #DC2626;
            border: 1px solid #E5E7EB;
        }

        .rich-content pre {
            background: #1F2937;
            color: #F9FAFB;
            padding: 1.5rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.5rem 0;
        }

        .rich-content pre code {
            background: transparent;
            color: #F9FAFB;
            padding: 0;
            border: none;
        }

        .rich-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .rich-content th {
            background: #3B82F6;
            color: white;
            padding: 0.875rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .rich-content td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #E5E7EB;
            color: #374151;
        }

        .rich-content tr:last-child td {
            border-bottom: none;
        }

        .rich-content tr:hover {
            background: #F9FAFB;
        }

        .rich-content hr {
            border: none;
            height: 2px;
            background: linear-gradient(to right, #3B82F6, transparent);
            margin: 2rem 0;
        }

        .rich-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .terms-container {
                background: #111827;
            }

            .rich-content {
                color: #D1D5DB;
            }

            .rich-content h1 {
                color: #F9FAFB;
                border-bottom-color: #60A5FA;
            }

            .rich-content h2 {
                color: #F9FAFB;
            }

            .rich-content h2::before {
                background: #60A5FA;
            }

            .rich-content h3 {
                color: #F3F4F6;
            }

            .rich-content h4 {
                color: #E5E7EB;
            }

            .rich-content h5,
            .rich-content h6 {
                color: #D1D5DB;
            }

            .rich-content p {
                color: #D1D5DB;
            }

            .rich-content li {
                color: #D1D5DB;
            }

            .rich-content strong,
            .rich-content b {
                color: #F9FAFB;
            }

            .rich-content a {
                color: #60A5FA;
            }

            .rich-content a:hover {
                color: #93C5FD;
            }

            .rich-content blockquote {
                background: #1F2937;
                color: #9CA3AF;
                border-left-color: #60A5FA;
            }

            .rich-content code {
                background: #1F2937;
                color: #FCA5A5;
                border-color: #374151;
            }

            .rich-content table {
                background: #1F2937;
                border-color: #374151;
            }

            .rich-content th {
                background: #1E40AF;
            }

            .rich-content td {
                border-bottom-color: #374151;
                color: #D1D5DB;
            }

            .rich-content tr:hover {
                background: #374151;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .rich-content h1 {
                font-size: 1.875rem;
            }

            .rich-content h2 {
                font-size: 1.5rem;
            }

            .rich-content h3 {
                font-size: 1.25rem;
            }

            .rich-content ul,
            .rich-content ol {
                padding-left: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="terms-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-white mb-4">Terms of Use</h1>
                <p class="text-lg text-gray-400">
                    Last updated: {{ \Carbon\Carbon::parse($updated_at)->format('F j, Y') }}
                </p>
            </div>

            <div class="prose prose-lg max-w-none">
                <div class="my-10 rich-content">{!! $page_content !!}</div>

                <!-- Contact -->
                <div class="bg-blue-50 dark:bg-gray-800 rounded-2xl p-6 mt-12 border border-blue-100 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Contact Information</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        If you have any questions about these Terms, please contact us at:
                    </p>
                    <div class="text-gray-700 dark:text-gray-300 space-y-2">
                        <p><strong class="text-gray-900 dark:text-white">Email:</strong> {{ $contact_mail }}</p>
                        <p><strong class="text-gray-900 dark:text-white">Address:</strong> {{ $business_address }}</p>
                        <p><strong class="text-gray-900 dark:text-white">Business Registration No:</strong> {{ $business_registration_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection