<div class="step-content px-4 py-8 lg:px-8">
    <h2 class="text-3xl lg:text-[40px] font-bold mb-8">Declaration</h2>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('manufacturer.application.step.save', ['step' => 5]) }}" method="POST"
        enctype="multipart/form-data" id="stepForm">
        @csrf
        @method('POST')

        <input type="hidden" name="next_step" value="6">
        <input type="hidden" name="action" id="formAction" value="next">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <!-- Agreement Checkboxes -->
                <div class="space-y-4 mb-8">
                    <label class="flex items-start @error('agree_terms') text-red-600 @enderror">
                        <input type="checkbox" name="agree_terms" required
                            {{ old('agree_terms', $profile_data->agree_terms ?? false) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1 @error('agree_terms') border-red-500 @enderror">
                        <span class="ml-3 text-sm @error('agree_terms') text-red-600 @else text-gray-700 @enderror">
                            I agree to the <a href="/terms-of-use" target="_blank" class="text-blue-600 underline">Terms
                                of Service</a> <span class="text-red-500">*</span>
                        </span>
                    </label>
                    @error('agree_terms')
                        <p class="text-red-500 text-xs mt-1 ml-8">{{ $message }}</p>
                    @enderror

                    <label class="flex items-start @error('consent_background_check') text-red-600 @enderror">
                        <input type="checkbox" name="consent_background_check" required
                            {{ old('consent_background_check', $profile_data->consent_background_check ?? false) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1 @error('consent_background_check') border-red-500 @enderror">
                        <span
                            class="ml-3 text-sm @error('consent_background_check') text-red-600 @else text-gray-700 @enderror">
                            I consent to going through a background check <span class="text-red-500">*</span>
                        </span>
                    </label>
                    @error('consent_background_check')
                        <p class="text-red-500 text-xs mt-1 ml-8">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Warning Box -->
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-8">
                    <div class="flex">
                        <svg class="w-6 h-6 text-orange-400 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-orange-800">
                                By typing your full name below, you are providing us with your digital
                                signature, which is legally binding just like a handwritten signature.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Digital Signature -->
                <div class="mb-8 lg:w-1/2">
                    <label class="block text-sm text-gray-700 mb-2">
                        Digital Signature <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="digital_signature" required
                        value="{{ old('digital_signature', $profile_data->digital_signature ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('digital_signature') border-red-500 @enderror"
                        placeholder="Enter your full legal name">
                    <p class="text-xs text-gray-500 mt-2">Please enter your full legal name</p>
                    @error('digital_signature')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="my-12 text-gray-400">
            </div>

            <div>
                <div class="help-box sticky top-24">
                    <div class="w-24 h-24 from-blue-400 to-blue-600 rounded-xl flex items-center mb-4 justify-left">
                        <img src="/assets/images/question_img.png" alt="" class="w-full">
                    </div>
                    <h4 class="text-lg font-semibold mb-2">Legal Declaration</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        This declaration is legally binding. Please ensure all information provided is accurate.
                    </p>
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <strong>Important:</strong> Providing false information may result in rejection of your
                            application.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:flex justify-center gap-4 items-center relative">
            <div class="text-sm text-gray-500 lg:absolute left-0">
                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                    </path>
                </svg>
                Auto-saved when you click Next
            </div>
            <div class="flex gap-4">
                <a href="/manufacturer/application/step/4"
                    class="prev-btn text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition border border-blue-600">
                    ← Previous
                </a>
                <button type="button" onclick="submitForm('next')"
                    class="next-btn bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Next →
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function submitForm(action) {
        document.getElementById('formAction').value = action;
        document.getElementById('stepForm').submit();
    }
</script>
