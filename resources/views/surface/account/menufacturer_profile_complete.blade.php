@extends('layouts.surface.app')

@section('title', 'Complete your manufacturer profile')

@section('style')
    <link rel="stylesheet" href="/assets/css/manufacturer_profile.css">
@endsection

@section('content')
    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px] py-8">
        <input type="hidden" class="current_step" value="{{ $step }}">
        <div class="steps pb-3">
            <div class="step_one step_line @if ($step == 1) active_step @endif"></div>
            <div class="step_two step_line @if ($step == 2) active_step @endif"></div>
            <div class="step_three step_line @if ($step == 3) active_step @endif"></div>
            <div class="step_four step_line @if ($step == 4) active_step @endif"></div>
            <div class="step_five step_line @if ($step == 5) active_step @endif"></div>
        </div>
        <div class="px-4 pb-6 lg:px-8">
            <div class="hidden lg:block step-indicator">
                <div class="step-progress" id="stepProgress" style="width: 0%;"></div>
                <div class="grid grid-cols-5 gap-4 relative">
                    <!-- Step 1 -->
                    <div class="step-item @if ($step == 1) active @endif" data-step="1">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 1</div>
                        <div class="step-title mt-2 text-sm font-medium">Company Information</div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step-item @if ($step == 2) active @endif" data-step="2">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 2</div>
                        <div class="step-title mt-2 text-sm font-medium">Business Profile</div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step-item @if ($step == 3) active @endif" data-step="3">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 3</div>
                        <div class="step-title mt-2 text-sm font-medium">Product Information</div>
                    </div>

                    <!-- Step 4 -->
                    <div class="step-item @if ($step == 4) active @endif" data-step="4">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 4</div>
                        <div class="step-title mt-2 text-sm font-medium">Trust & Verifications</div>
                    </div>

                    <!-- Step 5 -->
                    <div class="step-item @if ($step == 5) active @endif" data-step="5">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 5</div>
                        <div class="step-title mt-2 text-sm font-medium">Declaration</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="mobileStepIndicator"
            class="step_of_step uppercase text-[#05660C] font-semibold block lg:hidden mt-[-0.75rem] px-4">
            STEP 1 Out of 5
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <!-- Form -->
            <form id="profileForm" action="/manufacturer/complete-application" method="POST"
                enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Company Information -->
                @include('includes.manufacturer_profile.step_one')

                <!-- Step 2: Business Profile -->
                @include('includes.manufacturer_profile.step_two')


                <!-- Step 3: Product Information -->
                @include('includes.manufacturer_profile.step_three')


                <!-- Step 4: Trust & Verifications -->
                @include('includes.manufacturer_profile.step_four')


                <!-- Step 5: Declaration -->
                @include('includes.manufacturer_profile.step_five')


                <!-- Step 6: Review & Submit -->
                @include('includes.manufacturer_profile.step_six')

            </form>
        </div>
    </section>
@endsection


@section('scripts')
    <script>
        function toggleAccordion(reviewSection) {
            $(reviewSection).toggleClass("collapsed");
        }
    </script>
    <script src="/assets/js/manufacturer_profile.js"></script>
@endsection
