@extends('layouts.surface.app')

@section('title', 'Our all manufacturers')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <link rel="stylesheet" href="/assets/css/index_style.css">
@endsection

@section('content')
    <div class="hero_section my-12 px-4 lg:px-0 mx-auto">
        <img src="/assets/images/global_manufacturers.png" alt="Image" class="block max-h-40 mx-auto">

        <h3 class="text-2xl lg:text-[40px] text-center text-black">
            All global Manufacturers
        </h3>
    </div>

    <input type="hidden" name="token" class="csrf_token" value="{{ csrf_token() }}">

    <div class="mt-20 mb-12 px-4 lg:px-8">
        @include('includes.manufacturer')
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
