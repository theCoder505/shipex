@extends('layouts.surface.app')

@section('title', 'Help Center')

@section('style')
    <style>
        /* Container background for better contrast */
        .help-container {
            background: #FFFFFF;
            min-height: 100vh;
        }

        /* Custom styling for help center content */
        .help-content {
            line-height: 1.8;
            color: #1F2937;
        }

        .help-content h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: #111827;
            margin-top: 2rem;
            margin-bottom: 1rem;
            line-height: 1.2;
            border-bottom: 3px solid #3B82F6;
            padding-bottom: 0.5rem;
        }

        .help-content h2 {
            font-size: 1.875rem;
            font-weight: 600;
            color: #111827;
            margin-top: 2.5rem;
            margin-bottom: 0.875rem;
            line-height: 1.3;
            position: relative;
            padding-left: 1rem;
        }

        .help-content h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.25rem;
            height: 1.5rem;
            width: 4px;
            background: #3B82F6;
            border-radius: 2px;
        }

        .help-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1F2937;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .help-content p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
            color: #374151;
            line-height: 1.8;
        }

        .help-content strong,
        .help-content b {
            font-weight: 600;
            color: #111827;
        }

        .help-content a {
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .help-content a:hover {
            color: #1D4ED8;
            text-decoration: none;
        }

        /* FAQ item styling */
        .faq-item {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .faq-item:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-color: #3B82F6;
        }

        .faq-question {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .faq-answer {
            color: #374151;
            line-height: 1.7;
        }

        /* Contact section styling */
        .contact-section {
            background: #F3F4F6;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #E5E7EB;
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .help-container {
                background: #111827;
            }

            .help-content {
                color: #D1D5DB;
            }

            .help-content h1 {
                color: #F9FAFB;
                border-bottom-color: #60A5FA;
            }

            .help-content h2 {
                color: #F9FAFB;
            }

            .help-content h2::before {
                background: #60A5FA;
            }

            .help-content h3 {
                color: #F3F4F6;
            }

            .help-content p {
                color: #D1D5DB;
            }

            .help-content strong,
            .help-content b {
                color: #F9FAFB;
            }

            .help-content a:hover {
                color: #93C5FD;
            }

            .faq-item {
                background: #1F2937;
                border-color: #374151;
                color: #D1D5DB;
            }

            .faq-question {
                color: #F9FAFB;
            }

            .faq-answer {
                color: #D1D5DB;
            }

            .contact-section {
                background: #1F2937;
                border-color: #374151;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .help-content h1 {
                font-size: 1.875rem;
            }

            .help-content h2 {
                font-size: 1.5rem;
            }

            .help-content h3 {
                font-size: 1.25rem;
            }

            .faq-item {
                padding: 1.25rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="help-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Help Center</h1>
                <p class="text-xl text-gray-600 dark:text-gray-400">Find answers to common questions and get support</p>
            </div>

            <!-- FAQ Section -->
            <div class="mb-16 help-content">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Frequently Asked Questions</h2>
                <div class="space-y-6">
                    @forelse ($faqs as $item)
                        <div class="faq-item">
                            <h3 class="faq-question">{{ $item->question }}</h3>
                            <p class="faq-answer">{{ $item->answer }}</p>
                        </div>
                    @empty
                        <div class="faq-item text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No FAQs available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Contact Section -->
            <div class="contact-section help-content">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Still Need Help?</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Can't find what you're looking for? Contact our support team and we'll get back to you within 24 hours.</p>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Email Support</h3>
                        <a href="mailto:{{ $contact_mail }}" class="text-gray-700 dark:text-gray-300">{{ $contact_mail }}</a>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Live Chat</h3>
                        <p class="text-gray-700 dark:text-gray-300">Available {{ $open_dys }}, {{ $open_time }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection