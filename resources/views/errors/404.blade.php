@extends('layouts.surface.app')

@section('title', '404 Page Not Found!')

@section('style')

@endsection

@section('content')
    <div class="min-h-[calc(100vh-210px)] flex items-center justify-center">
        <div class="col-span-3 p-8 rounded-lg bg-[#F6F6F6] mx-4 lg:mx-auto empty_results text-center">
            <img src="/assets/images/empty_review.png" alt="" class="w-32 rounded-lg mx-auto">
            <h3 class="text-[40px] my-4 font-semibold">404</h3>
            <p class="text-[16px] text-gray-500 mb-2">
                This page does not exist. Please go back to the homepage.
            </p>
            <a href="/" class="text-[16px] text-[#003FB4] hover:underline">
                Go to home
            </a>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
