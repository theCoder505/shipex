<footer class="px-4 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="">
            <a href="/" class="inline-block">
                <img src="{{ asset($brandlogo) }}" alt="" class="h-18">
            </a>
        </div>

        <div class="footer_links flex gap-4 lg:gap-6 items-center text-center justify-center">
            <a href="/help" class="gray_text underline font-semibold">Help</a>
            <a href="/privacy-policy" class="gray_text underline font-semibold">Privacy Policy</a>
            <a href="/terms-of-use" class="gray_text underline font-semibold">Terms of Use</a>
        </div>

        <div class="language">
            <div class="choose_language">Language</div>
            <div id="gLang"></div>
        </div>
    </div>
</footer>
