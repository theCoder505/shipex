@extends('layouts.surface.app')

@section('title', 'Password Changed successfully')

@section('style')

@section('content')
    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px]">
        <section class="min-h-screen flex justify-center bg-white px-4">
            <div class="px-2 lg:px-8 w-full my-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="grid items-start md:px-9">
                    <div class="mt-12 max-w-[500px]">
                        <div class="w-[52px] h-[52px] mb-2">
                            <img src="/assets/images/right.png" alt="" class="w-full rounded-full">
                        </div>

                        <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                            Password Changed <br> successfully
                        </h2>
                        <p class="text-[16px] text-gray-500 mb-6">
                            Please log in using your new password.
                        </p>

                        <a href="/{{ $user_type }}/login"
                            class="enabled_acc_btn w-full mt-12 block text-center font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg">
                            Go to login
                        </a>
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
