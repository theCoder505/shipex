@extends('layouts.surface.app')

@section('title', 'Your application has been sent successfully!')

@section('style')

@section('content')
    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px]">
        <section class="min-h-screen flex justify-center bg-white px-4">
            <div class="px-2 lg:px-8 w-full my-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="grid items-start md:px-9">
                    <div class="mt-12">
                        <div class="w-[52px] h-[52px] mb-2">
                            <img src="/assets/images/right.png" alt="" class="w-full rounded-full">
                        </div>

                        <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                            Your application has been sent successfully!
                        </h2>
                        <p class="text-[16px] text-gray-500 mb-6">
                            You can expect to hear from us within 1 week to update you <br> on the status of your application. Please make sure you add <br> <a href="mailto:{{$contact_mail}}">{{$contact_mail}}</a> as your authorized sender in your email box.
                        </p>
                    </div>
                </div>

                <div class="">
                    <img src="/assets/images/reset_mail_sent.png" alt="SHIPEX"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
            </div>
        </section>
    </section>
@endsection

@section('scripts')
@endsection
