<div class="max-w-[1600px] mx-auto my-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-center all_products">
        @forelse ($products as $key => $product)
            <div class="item rounded-lg product-item" data-product-name="{{ strtolower($product['name'] ?? '') }}">
                <div class="relative">
                    <div class="owl-carousel owl-theme owl_items product_imgs">
                        @if (isset($product['image']) && $product['image'])
                            <div class="item">
                                <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] ?? 'Product Image' }}"
                                    class="w-full h-64 object-cover rounded-lg">
                            </div>
                        @else
                            <div class="item">
                                <img src="/assets/images/menufacturer_camera.png" alt="Default Product Image"
                                    class="w-full h-64 object-cover rounded-lg">
                            </div>
                        @endif
                        <!-- Additional images can be added here if available in product data -->
                        <div class="item">
                            <img src="/assets/images/menufacturer_three.jpg" alt="Additional view"
                                class="w-full h-64 object-cover rounded-lg">
                        </div>
                        <div class="item">
                            <img src="/assets/images/menufacturer_company.jpeg" alt="Additional view"
                                class="w-full h-64 object-cover rounded-lg">
                        </div>
                    </div>
                    <img src="/assets/images/full_box.png" alt="View full size"
                        class="full_show absolute top-2 right-2 z-10 cursor-pointer" onclick="showFull(this)">
                </div>
                <div class="text-xl px-4 py-2">{{ $product['name'] ?? 'Product ' . ($key + 1) }}</div>
            </div>
        @empty
            <div class="col-span-3 p-4 rounded-lg bg-[#F6F6F6] mx-4 lg:w-[680px] lg:mx-auto empty-products-message">
                <img src="/assets/images/empty_box.png" alt="No products" class="w-32 rounded block mx-auto">
                <h3 class="text-xl my-4 text-40px text-center">
                    No products found
                </h3>
                <p class="text-[16px] text-gray-500 mb-2 text-center">
                    No products have been added yet!
                </p>
            </div>
        @endforelse

        <!-- Ask about specific product section -->
        <div class="product-item mt-8" data-product-name="ask-about-product">
            <div class="flex items-center justify-center h-[250px] border border-gray-400 p-4 rounded-lg">
                <p class="text-xl text-[#46484d]">
                    Want to ask about a specific product?
                </p>
            </div>
            @if (Auth::guard('wholesaler')->check())
                <div class="mt-8">
                    <a href="/wholesaler/chats/{{ $manufacturer_uid }}"
                        class="text-white hover:bg-[#003FB4] rounded-lg px-4 py-3 bg-[#003FB4] text-center font-semibold transition-all duration-200">
                        Chat now
                    </a>
                    {{-- <button data-manufacturer="{{ $manufacturer_uid }}" onclick="showChatPopup(this)"
                        class="text-white hover:bg-[#003FB4] rounded-lg px-4 py-3 bg-[#003FB4] text-center font-semibold transition-all duration-200">
                        Chat now
                    </button> --}}
                </div>
            @else
                <div class="mt-8 py-4"></div>
            @endif
        </div>
    </div>
</div>

<div id="imageModal" class="hidden fixed inset-0 bg-[#000000cc] z-50 flex items-center justify-center p-4">
    <div class="relative w-full lg:w-[1000px] flex items-center justify-center bg-white p-4 rounded-lg">
        <button onclick="closePopup()"
            class="absolute top-4 right-4 text-gray-600 text-xl lg:text-3xl font-bold z-10 flex items-center justify-center w-6 h-6 lg:w-10 lg:h-10 rounded-full">
            &times;
        </button>
        <div class="max-w-full max-h-full">
            <img id="modalImage" src="" alt="Full size image" class="w-full h-full rounded-lg">
        </div>
    </div>
</div>
