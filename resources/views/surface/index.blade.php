@extends('layouts.surface.app')

@section('title', 'Welcome to multi lingual communication and global outsourcing trade service or platform')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <link rel="stylesheet" href="/assets/css/index_style.css">
@endsection

@section('content')
    <div class="hero_section my-12 px-4 lg:px-0 mx-auto">
        <img src="/assets/images/global_manufacturers.png" alt="Image" class="block max-h-40 mx-auto">

        <h3 class="text-2xl lg:text-[40px] text-center text-black">
            Connecting with verified
            <br>
            global Manufacturers
        </h3>

        <div
            class="relative rounded-lg border-gray-400 border flex items-center overflow-hidden max-w-[680px] mx-auto mt-8">
            <img src="/assets/images/search.png" alt="Search" class="px-4 py-3 cursor-pointer"
                onclick="searchMenufacturer(this)">
            <input type="text" class="border-none outline-none w-full py-2 serach_input" name="search_query"
                placeholder="Search for manufacturers...">
        </div>

        <div class="flex justify-between items-center gap-4 mt-4 max-w-[680px] mx-auto flex-wrap">
            <div class="flex gap-4 items-center flex-wrap">
                <div class="all active_tab" onclick="showSpecManufacturer(this)" data-id="all">All</div>
                <div class="new homepage_tab" onclick="showSpecManufacturer(this)" data-id="new">New Products</div>
                <div class="refurbished homepage_tab" onclick="showSpecManufacturer(this)" data-id="refurbished">Refurbished Products
                </div>
            </div>

            @if (Auth::guard('wholesaler')->check() || Auth::guard('manufacturer')->check())
                <div class="filters flex gap-2 cursor-pointer items-center" onclick="filterMenufacturers(this)">
                    <img src="/assets/images/filters.png" alt="" class="h-4">
                    <div class="text-gray-700">Filters <span class="filter_span"></span> </div>
                </div>
            @else
                <div class="filters flex gap-2 cursor-pointer items-center" onclick="openAccountModal(this)">
                    <img src="/assets/images/filters.png" alt="" class="h-4">
                    <div class="text-gray-700">Filters <span class="filter_span"></span> </div>
                </div>
            @endif
        </div>
    </div>

    <input type="hidden" name="token" class="csrf_token" value="{{ csrf_token() }}">

    <div class="mt-20 mb-12 px-4 lg:px-8">
        @include('includes.manufacturer')
    </div>

    @include('includes.account_modal')

    @include('includes.filters')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center mt-12 mb-30 px-4 lg:px-8 max-w-[1600px] mx-auto">
        <div class="px-6 py-6 rounded-xl border border-gray-400 hover:border-[#c5d8f9] create_grid_item h-full">
            <div class="text-black font-normal text-xl lg:text-[40px] lg:h-[120px] text-center lg:text-left">
                Create an account or log in <br> to see all manufacturers
            </div>

            <div class="grid justify-center lg:flex lg:justify-between items-center gap-4 mt-4 bottom-0">
                <a href="/wholesaler/signup" class="primary_btn">Create a wholesaler account</a>

                <div class="img_container mx-auto lg:mx-0">
                    <img src="/assets/images/wholesaler_signup_card.png" alt="" class="">
                </div>
            </div>
        </div>

        <div class="px-6 py-6 rounded-xl border border-gray-400 hover:border-[#c5d8f9] create_grid_item h-full">
            <div class="text-black font-normal text-xl lg:text-[40px] lg:h-[120px] text-center lg:text-left">
                Are you a manufacturer?
            </div>

            <div class="grid justify-center lg:flex lg:justify-between items-center gap-4 mt-4 bottom-0">
                <a href="/manufacturer/signup" class="primary_btn">Apply as a manufacturer</a>

                <div class="img_container mx-auto lg:mx-0">
                    <img src="/assets/images/manufacturer_signup_card.png" alt="" class="">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.owl_items').owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: true,
                autoplay: false,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                navText: ['', ''],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            });
        });
    </script>

    <script src="/assets/js/landing.js"></script>
@endsection
