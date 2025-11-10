<div class="step-content px-4 py-8 lg:px-8 {{ $step == 5 ? 'active' : '' }}" data-step="5">
    <h2 class="text-3xl lg:text-[40px] font-bold mb-8">Declaration</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="space-y-4 mb-8">
                <label class="flex items-start">
                    <input type="checkbox" name="agree_terms" required
                        {{ old('agree_terms', $profile_data->agree_terms ?? false) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                    <span class="ml-3 text-sm text-gray-700">
                        I agree to the <a href="/terms-of-use" target="_blank" class="text-blue-600 underline">Terms of Service</a> <span class="text-gray-500">*</span>
                    </span>
                </label>
                <label class="flex items-start">
                    <input type="checkbox" name="consent_background_check" required
                        {{ old('consent_background_check', $profile_data->consent_background_check ?? false) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                    <span class="ml-3 text-sm text-gray-700">
                        I consent to going through a background check <span class="text-gray-500">*</span>
                    </span>
                </label>
            </div>

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

            <div class="mb-8 lg:w-1/2">
                <label class="block text-sm text-gray-700 mb-2">
                    Digital Signature <span class="text-gray-500">*</span>
                </label>
                <input type="text" name="digital_signature" required value="{{ old('digital_signature', $profile_data->digital_signature ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-2">Please enter your full legal name</p>
            </div>

            <hr class="my-12 text-gray-400">
        </div>

        <div>
            <div class="help-box sticky top-24">
                <div class="w-24 h-24 from-blue-400 to-blue-600 rounded-xl flex items-center mb-4 justify-left">
                    <img src="/assets/images/question_img.png" alt="" class="w-full">
                </div>
                <h4 class="text-lg font-semibold mb-2">Need help filling this out?</h4>
                <p class="text-sm text-gray-600">
                    Feel free to reach out to
                    <a href="mailto:{{ $contact_mail }}">{{ $contact_mail }}</a>
                </p>
            </div>
        </div>
    </div>

    <div class="grid lg:flex justify-center gap-4 items-center relative">
        <div class="text-sm text-gray-500 lg:absolute left-0">
            <svg class="inline w-4 h-4 mr-1 animate-spin hidden" id="saving_indicator" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            <span id="save_text">Auto-saved</span>
        </div>
        <button type="button"
            class="prev-btn text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition border border-blue-600">
            ← Previous
        </button>
        <button type="button"
            class="next-btn bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
            Next →
        </button>
    </div>
</div>