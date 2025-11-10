@extends('layouts.surface.app')

@section('title', 'Help Center')

@section('style')

@endsection

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Help Center</h1>
            <p class="text-xl text-gray-600">Find answers to common questions and get support</p>
        </div>

        <!-- FAQ Section -->
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Frequently Asked Questions</h2>
            <div class="space-y-6">
                @forelse ($faqs as $item)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ $item->question }}</h3>
                        <p class="text-gray-600">{{ $item->answer }}</p>
                    </div>

                @empty
                @endforelse
            </div>
        </div>

        <!-- Contact Section -->
        <div class="bg-gray-50 rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Still Need Help?</h2>
            <p class="text-gray-600 mb-6">Can't find what you're looking for? Contact our support team and we'll get back to
                you within 24 hours.</p>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Email Support</h3>
                    <p class="text-gray-600">{{ $contact_mail }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Live Chat</h3>
                    <p class="text-gray-600">Available {{ $open_dys }}, {{ $open_time }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
