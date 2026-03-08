@if ($manufacturer)
    @php
        $showAlert =
            $manufacturer->status == 0 ||
            $manufacturer->status == 1 ||
            ($manufacturer->status == 5 && $manufacturer->subscription == 0) ||
            ($manufacturer->status == 5 && $manufacturer->subscription == 1 && $manufacturer->subscription_end_date <= now());
    @endphp


    @if ($manufacturer->auto_visibility == 0 && $showAlert)
        <div id="manufacturer-toast"
            class="fixed top-4 right-0 lg:top-6 lg:right-6 z-[999] w-full max-w-lg transition-all duration-500 ease-in-out"
            style="animation: slideInRight 0.4s ease forwards;">

            {{-- Status 0: Verification --}}
            @if ($manufacturer->status == 0)
                <div class="flex items-start gap-3 bg-white border-l-4 border-amber-400 rounded-xl p-4 shadow-lg">
                    <div class="flex-shrink-0 w-9 h-9 bg-amber-100 rounded-full flex items-center justify-center mt-0.5">
                        <svg class="w-4.5 h-4.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-xs lg:text-sm">Account Verification Required</p>
                        <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">Please verify your account to proceed.</p>
                    </div>
                    <button onclick="dismissToast()" class="cursor-pointer flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors ml-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Status 1: Pending Approval --}}
            @if ($manufacturer->status == 1)
                <div class="flex items-start gap-3 bg-white border-l-4 border-blue-400 rounded-xl p-4 shadow-lg">
                    <div class="flex-shrink-0 w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                        <svg class="w-4.5 h-4.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-xs lg:text-sm">Application Under Review</p>
                        <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">You are not yet approved by the <span class="font-medium text-gray-600">{{ $brandname }}</span> team. We'll notify you once reviewed.</p>
                    </div>
                    <button onclick="dismissToast()" class="cursor-pointer flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors ml-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Status 5 + No Subscription --}}
            @if ($manufacturer->status == 5 && $manufacturer->subscription == 0)
                <div class="flex items-start gap-3 bg-white border-l-4 border-violet-400 rounded-xl p-4 shadow-lg">
                    <div class="flex-shrink-0 w-9 h-9 bg-violet-100 rounded-full flex items-center justify-center mt-0.5">
                        <svg class="w-4.5 h-4.5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-xs lg:text-sm">No Active Subscription</p>
                        <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">Purchase a plan to get visible to <span class="font-medium text-gray-600">{{ $brandname }}</span>.</p>
                        <a href="/manufacturer/packages" class="inline-flex items-center gap-1 mt-2.5 text-xs font-semibold text-violet-600 hover:text-violet-700 transition-colors">
                            View Plans
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                    <button onclick="dismissToast()" class="cursor-pointer flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors ml-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Status 5 + Expired Subscription --}}
            @if ($manufacturer->status == 5 && $manufacturer->subscription == 1 && $manufacturer->subscription_end_date <= now())
                <div class="flex items-start gap-3 bg-white border-l-4 border-red-400 rounded-xl p-4 shadow-lg">
                    <div class="flex-shrink-0 w-9 h-9 bg-red-100 rounded-full flex items-center justify-center mt-0.5">
                        <svg class="w-4.5 h-4.5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-xs lg:text-sm">Subscription Expired</p>
                        <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">
                            Expired on <span class="font-medium text-gray-600">{{ \Carbon\Carbon::parse($manufacturer->subscription_end_date)->format('jS F, Y') }}</span>. Renew to stay visible to <span class="font-medium text-gray-600">{{ $brandname }}</span>.
                        </p>
                        <a href="/manufacturer/packages" class="inline-flex items-center gap-1 mt-2.5 text-xs font-semibold text-red-600 hover:text-red-700 transition-colors">
                            Renew Now
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                    <button onclick="dismissToast()" class="cursor-pointer flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors ml-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

        </div>

        <style>
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(110%); }
                to   { opacity: 1; transform: translateX(0); }
            }
            @keyframes slideOutRight {
                from { opacity: 1; transform: translateX(0); }
                to   { opacity: 0; transform: translateX(110%); }
            }
            .toast-hiding {
                animation: slideOutRight 0.35s ease forwards !important;
            }
        </style>

        <script>
            function dismissToast() {
                const warning_msg = document.getElementById('manufacturer-toast');
                warning_msg.classList.add('toast-hiding');
                setTimeout(() => warning_msg.remove(), 350);
            }
        </script>
    @endif
@endif