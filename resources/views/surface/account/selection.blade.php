@extends('layouts.surface.app')

@section('title', 'Create Your Account')

@section('style')

@section('content')
    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px]">
        <section class="min-h-screen flex items-center justify-center bg-white px-4">
            <div class="px-2 lg:px-8 w-full my-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col justify-center md:px-9">
                    <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                        Create an account
                    </h2>
                    <p class="text-[16px] text-gray-500 mb-6">
                        Already have an account? <a href="/manufacturer/login" class="text-[#003FB4] underline">Sign in</a>
                    </p>


                    <div class="my-4 grid gap-8">
                        <label class="acc_opt" for="manufacturer" onclick="chooseOption(this)">
                            <div class="flex gap-4 items-center">
                                <input type="radio" name="account_option" value="manufacturer" id="manufacturer"
                                    class="w-7 h-7 accent-[#D6E2F7]" />
                                <p>I manufacture products</p>
                            </div>
                            <div class="bg-[#D6E2F7] w-[72px] h-[72px] flex items-center justify-center p-2 rounded">
                                <img src="/assets/images/wrench.png" alt="" class="w-full">
                            </div>
                        </label>

                        <label class="acc_opt" for="wholesaler" onclick="chooseOption(this)">
                            <div class="flex gap-4 items-center">
                                <input type="radio" name="account_option" value="wholesaler" id="wholesaler"
                                    class="w-7 h-7 accent-[#D6E2F7]" />
                                <p>I sell products as a wholesaler</p>
                            </div>
                            <div class="bg-[#D6E2F7] w-[72px] h-[72px] flex items-center justify-center p-2 rounded">
                                <img src="/assets/images/dollar.png" alt="" class="w-full">
                            </div>
                        </label>
                    </div>


                    <div class="mt-4">
                        <button class="create_acc_btn w-full lg:w-auto" disabled onclick="redirectChoice()"> Create account </button>
                    </div>
                </div>

                <div class="">
                    <img src="/assets/images/boxes.png" alt="SHIPEX"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
            </div>
        </section>
    </section>
@endsection

@section('scripts')
    <script>
        function chooseOption(passedThis) {
            $(".acc_opt").removeClass("active_acc_opt");
            $(passedThis).addClass("active_acc_opt");
            $(".create_acc_btn").attr("disabled", false);
            $(".create_acc_btn").removeClass("create_acc_btn").addClass("enabled_acc_btn");
        }


        function redirectChoice() {
            let account_option = $("input[name='account_option']:checked").val();
            if (account_option === 'manufacturer') {
                window.location.href = '/manufacturer/signup';
            } else if (account_option === 'wholesaler') {
                window.location.href = '/wholesaler/signup';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "Please select an option first!",
                    timer: 4000,
                    showConfirmButton: true
                });
            }
        }
    </script>
@endsection
