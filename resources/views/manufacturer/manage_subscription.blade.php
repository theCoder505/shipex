@extends('layouts.surface.app')

@section('title', 'Manage Subscription')

@section('style')
    <style>
        .subscription-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }

        .status-active {
            background-color: #10B981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-canceled {
            background-color: #EF4444;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-trialing {
            background-color: #F59E0B;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-past_due {
            background-color: #DC2626;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .payment-record {
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .payment-record:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .package-badge {
            background-color: #EFF6FF;
            color: #1E40AF;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Manage Subscription</h1>
                <p class="text-gray-600 mt-2">View and manage your subscription details and payment history</p>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Current Subscription Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
                <div class="subscription-card p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-2">Current Subscription</h2>
                            <div class="flex items-center space-x-4">
                                @if ($manufacturer->subscription)
                                    <span class="status-active">ACTIVE</span>
                                    <span class="text-white/90 font-semibold text-lg">
                                        {{ ucfirst($manufacturer->subscription_type) }} Plan
                                    </span>
                                    @if ($manufacturer->coupon_code)
                                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">
                                            Coupon: {{ $manufacturer->coupon_code }}
                                        </span>
                                    @endif
                                @else
                                    <span class="status-canceled">INACTIVE</span>
                                    <span class="text-white/90">No active subscription</span>
                                @endif
                            </div>
                        </div>

                        @if ($manufacturer->subscription)
                            <div class="mt-4 md:mt-0">
                                <form action="{{ route('manufacturer.cancel-subscription') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-white text-red-600 hover:bg-red-50 px-6 py-2 rounded-lg font-semibold transition duration-200">
                                        Cancel Subscription
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-4 md:mt-0">
                                <a href="{{ route('manufacturer.subscription') }}"
                                    class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-2 rounded-lg font-semibold transition duration-200">
                                    Subscribe Now
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Subscription Details -->
                @if ($manufacturer->subscription)
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Subscription Type</h3>
                                <p class="text-lg font-semibold text-gray-900 capitalize">
                                    {{ $manufacturer->subscription_type }}</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Coupon Code</h3>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $manufacturer->coupon_code ?: 'None' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Payment ID</h3>
                                <p class="text-sm font-mono text-gray-900 truncate">
                                    {{ $manufacturer->paypal_payment_id ?: 'N/A' }}
                                </p>
                            </div>
                        </div>

                        @if ($manufacturer->created_at)
                            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <!-- Clock Icon -->
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-12a.75.75 0 00-1.5 0v4.25c0 .414.336.75.75.75h3.25a.75.75 0 000-1.5H10.75V6z"
                                            clip-rule="evenodd" />
                                    </svg>

                                    <p class="text-blue-800 font-medium">
                                        Subscription started from:
                                        {{ \Carbon\Carbon::parse($manufacturer->created_at)->format('F j, Y') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Payment History</h2>
                    <p class="text-gray-600 text-sm mt-1">Your subscription payment records</p>
                </div>

                <div class="p-6">
                    @if ($payment_records->count() > 0)
                        <div class="space-y-4">
                            @foreach ($payment_records as $record)
                                <div class="payment-record bg-white border border-gray-200 rounded-lg p-6">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4 mb-3">
                                                <span class="package-badge capitalize">
                                                    {{ $record->package_type }} Plan
                                                </span>
                                                <span class="status-{{ $record->payment_status }}">
                                                    {{ strtoupper($record->payment_status) }}
                                                </span>
                                                <span class="text-lg font-bold text-gray-900">
                                                    ${{ number_format($record->amount, 2) }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">Payment Date:</span>
                                                    <span>{{ $record->payment_date->format('M j, Y g:i A') }}</span>
                                                </div>

                                                @if ($record->next_billing_date)
                                                    <div>
                                                        <span class="font-medium">Next Billing:</span>
                                                        <span>{{ $record->next_billing_date->format('M j, Y') }}</span>
                                                    </div>
                                                @endif

                                                <div>
                                                    <span class="font-medium">Payment Method:</span>
                                                    <span class="capitalize">{{ $record->payment_method }}</span>
                                                </div>
                                            </div>

                                            @if ($record->coupon_code)
                                                <div class="mt-2">
                                                    <span class="text-sm text-green-600 font-medium">
                                                        Coupon Applied: {{ $record->coupon_code }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if ($record->stripe_invoice_id)
                                                <div class="mt-2">
                                                    <span class="text-xs text-gray-500 font-mono">
                                                        Invoice: {{ $record->stripe_invoice_id }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-4 md:mt-0 md:ml-6">
                                            @if ($record->payment_status === 'active')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Current
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No payment records found</h3>
                            <p class="mt-2 text-gray-500">Your payment history will appear here once you make a
                                subscription.</p>
                            <div class="mt-6">
                                <a href="{{ route('manufacturer.subscription') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Subscribe Now
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Additional Information -->
            @if ($manufacturer->subscription)
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-lg font-medium text-blue-900 mb-2">Need Help with Your Subscription?</h3>
                            <p class="text-blue-800 mb-3">
                                If you have any questions about your subscription, billing, or need to make changes,
                                please contact our support team.
                            </p>
                            <div class="flex space-x-4">
                                <a href="mailto:support@example.com" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Email Support
                                </a>
                                <a href="tel:+1234567890" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Call Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any JavaScript functionality here if needed
            console.log('Manage subscription page loaded');

            // Example: Confirm subscription cancellation
            const cancelForms = document.querySelectorAll('form[action*="cancel-subscription"]');
            cancelForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm(
                            'Are you sure you want to cancel your subscription? You will lose access to premium features at the end of your billing period.'
                        )) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
